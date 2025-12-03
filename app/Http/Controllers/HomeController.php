<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendContactMessage;

class HomeController extends Controller
{
    //
    public function index()
    {
        // Trả về file: resources/views/pages/home.blade.php
        return view('pages.home');
    }

    public function about()
    {
        // Trả về file: resources/views/pages/about.blade.php
        return view('pages.about');
    }

    public function contact()
    {
        // Trả về file: resources/views/pages/about.blade.php
        return view('pages.contact');
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            $contactData = $validated;

            Mail::to('sonht27@huynhthanhson.io.vn')->send(new SendContactMessage($contactData));

            return back()->with('success', 'Cảm ơn bạn! Tin nhắn của bạn đã được gửi thành công. Chúng tôi sẽ phản hồi sớm nhất.');
        } catch (\Throwable $e) {
            \Log::error("Lỗi gửi mail liên hệ: " . $e->getMessage());
            return back()->with('error', 'Có lỗi xảy ra trong quá trình gửi tin nhắn. Vui lòng thử lại sau.');
        }

        return back()->with('success', 'Tin nhắn của bạn đã được gửi thành công!');
    }

    public function temp()
    {
        // Trả về file: resources/views/pages/home.blade.php
        return view('pages.temp');
    }
}
