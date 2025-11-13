<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Address;


class AddressController extends Controller
{
    public function index()
    {
        // Lấy user_id của người đang đăng nhập
        $userId = Auth::id();

        // Chỉ lấy địa chỉ của user đó, sắp xếp cho 'default' lên đầu
        $addresses = Address::where('user_id', $userId)
            ->orderByDesc('is_default') // Xếp địa chỉ mặc định lên đầu
            ->get();

        // Trả về view và truyền danh sách địa chỉ
        return view('account.addresses.index', compact('addresses'));
        // (Bạn cần tạo file: /resources/views/account/addresses/index.blade.php)
    }

    /**
     * Hiển thị form để tạo địa chỉ mới.
     */
    public function create()
    {
        return view('account.addresses.create');
        // (Bạn cần tạo file: /resources/views/account/addresses/create.blade.php)
    }

    /**
     * Lưu địa chỉ mới vào database.
     */
    public function store(Request $request)
    {
        // 1. Validate dữ liệu đầu vào
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean', // Dùng checkbox
        ]);

        // 2. Lấy ID của user đang đăng nhập
        $validatedData['user_id'] = Auth::id();

        // 3. Xử lý logic "Đặt làm mặc định"
        if ($request->boolean('is_default')) {
            // Nếu user chọn đây là mặc định,
            // HÃY BỎ mặc định của tất cả địa chỉ cũ của user này
            DB::table('addresses')
                ->where('user_id', Auth::id())
                ->update(['is_default' => false]);

            $validatedData['is_default'] = true;
        } else {
            $validatedData['is_default'] = false;
        }

        // 4. Tạo địa chỉ
        Address::create($validatedData);

        // 5. Chuyển hướng về trang danh sách địa chỉ
        return redirect()->route('addresses.index')
            ->with('success', 'Đã thêm địa chỉ mới thành công!');
    }

    /**
     * Hiển thị một địa chỉ cụ thể (thường ít dùng trong trang 'Tài khoản').
     */
    public function show(Address $address)
    {
        // 403 Forbidden nếu địa chỉ này không phải của user
        $this->authorizeUser($address);

        // Trả về view (nếu bạn thực sự cần)
        return view('account.addresses.show', compact('address'));
    }

    /**
     * Hiển thị form để chỉnh sửa địa chỉ.
     */
    public function edit(Address $address)
    {
        // 403 Forbidden nếu địa chỉ này không phải của user
        $this->authorizeUser($address);

        return view('account.addresses.edit', compact('address'));
        // (Bạn cần tạo file: /resources/views/account/addresses/edit.blade.php)
    }

    /**
     * Cập nhật địa chỉ trong database.
     */
    public function update(Request $request, Address $address)
    {
        // 403 Forbidden nếu địa chỉ này không phải của user
        $this->authorizeUser($address);

        // 1. Validate (giống hệt store)
        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            // ... (các trường validate khác) ...
            'is_default' => 'nullable|boolean',
        ]);

        // 2. Xử lý logic "Đặt làm mặc định"
        if ($request->boolean('is_default')) {
            // HÃY BỎ mặc định của tất cả địa chỉ cũ
            DB::table('addresses')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $address->id) // Trừ địa chỉ đang sửa
                ->update(['is_default' => false]);

            $validatedData['is_default'] = true;
        } else {
            $validatedData['is_default'] = false;
        }

        // 3. Cập nhật địa chỉ
        $address->update($validatedData);

        // 4. Chuyển hướng
        return redirect()->route('addresses.index')
            ->with('success', 'Đã cập nhật địa chỉ thành công!');
    }

    /**
     * Xóa địa chỉ khỏi database.
     */
    public function destroy(Address $address)
    {
        // 403 Forbidden nếu địa chỉ này không phải của user
        $this->authorizeUser($address);

        // Cẩn thận: Nếu xóa địa chỉ mặc định, bạn có thể 
        // chọn một địa chỉ khác làm mặc định (nếu muốn)
        // Nhưng đơn giản nhất là cứ xóa.

        $address->delete();

        return redirect()->route('addresses.index')
            ->with('success', 'Đã xóa địa chỉ thành công!');
    }

    /**
     * HÀM BẢO MẬT (HELPER): 
     * Kiểm tra xem địa chỉ có thuộc về người dùng đang đăng nhập không.
     */
    protected function authorizeUser(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Bạn không có quyền thực hiện hành động này.');
        }
    }
}
