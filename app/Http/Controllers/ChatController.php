<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.chat');
    }

    public function sendMessage(Request $request)
    {

        $user = Auth::user();
        $dailyLimit = 2000;

        $cacheKey = 'token_usage_' . $user->id . '_' . now()->format('Y-m-d');
        $usedToday = Cache::get($cacheKey, 0);

        if ($usedToday >= $dailyLimit) {
            return response()->json([
                'error' => 'Bạn đã dùng hết hạn mức trong ngày. Quay lại vào ngày mai nhé!'
            ], 403);
        }

        // Validate dữ liệu đầu vào
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');

        // Prompt hệ thống
        $systemPrompt = "Bạn là trợ lý ảo thông minh của 1 cửa hàng bán keycap tên là ITKeyCap. Hãy trả lời ngắn gọn, nhưng hài hước, có thể dùng meme đang nổi tại việt nam, tập trung vào keycap, mọi câu hỏi không liên quan đều nhận câu trả lời 'Tôi không trả lời câu hỏi nằm ngoài phạm vi'.";

        try {
            // Gọi API bằng Laravel HTTP Client
            // $response = Http::withToken(config('services.openai.key')) // Lấy key từ config
            //     ->timeout(30) // Set timeout 30s phòng trường hợp mạng lag
            //     ->post('https://api.openai.com/v1/chat/completions', [
            //         "model" => "gpt-4o-mini",
            //         "messages" => [
            //             ["role" => "system", "content" => $systemPrompt],
            //             ["role" => "user", "content" => $userMessage]
            //         ],
            //         "temperature" => 0.7
            //     ]);

            $response = Http::withToken(config('services.openai.key')) // Lấy key từ config
                ->timeout(30) // Set timeout 30s
                ->post('https://api.openai.com/v1/chat/completions', [
                    "model" => "gpt-5-nano",
                    "messages" => [
                        ["role" => "system", "content" => $systemPrompt],
                        ["role" => "user", "content" => $userMessage]
                    ],
                ]);

            // Kiểm tra nếu gọi thành công
            if ($response->successful()) {
                // return response()->json($response->json());

                $data = $response->json();
                $tokensUsed = $data['usage']['total_tokens'] ?? 0;

                // 4. Cộng dồn vào Cache và đặt thời gian hết hạn là CUỐI NGÀY
                // now()->endOfDay() sẽ trả về thời điểm 23:59:59 của hôm nay
                Cache::put($cacheKey, $usedToday + $tokensUsed, now()->endOfDay());

                // return response()->json([
                //     'choices' => $data['choices'],
                //     'used_today' => $usedToday + $tokensUsed,
                //     'daily_limit' => $dailyLimit,
                //     'remaining' => $dailyLimit - ($usedToday + $tokensUsed)
                // ]);

                return response()->json($response->json());
            } else {
                // Log lỗi để debug nếu cần
                Log::error('OpenAI Error: ' . $response->body());
                return response()->json(['error' => 'Lỗi từ phía OpenAI'], 500);
            }
        } catch (\Exception $e) {
            Log::error('OpenAI Server Error: ' . $e->getMessage());
            Log::error('OpenAI Error Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Lỗi kết nối server'], 500);
        }
    }
}
