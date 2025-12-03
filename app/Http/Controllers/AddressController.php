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
        $userId = Auth::id();

        $addresses = Address::where('user_id', $userId)
            ->orderByDesc('is_default') // Xếp địa chỉ mặc định lên đầu
            ->get();

        return view('account.addresses.index', compact('addresses'));
    }

    /**
     * Hiển thị form để tạo địa chỉ mới.
     */
    public function create()
    {
        return view('account.addresses.create');
    }

    /**
     * Lưu địa chỉ mới vào database.
     */
    public function store(Request $request)
    {
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

        $validatedData['user_id'] = Auth::id();
        $validatedData['address_line2'] = $validatedData['address_line2'] ?? 'Không';

        if ($request->boolean('is_default')) {
            DB::table('addresses')
                ->where('user_id', Auth::id())
                ->update(['is_default' => false]);

            $validatedData['is_default'] = true;
        } else {
            $validatedData['is_default'] = false;
        }

        Address::create($validatedData);

        return redirect()->route('account.profile')
            ->with('success', 'Đã thêm địa chỉ mới thành công!');
    }

    /**
     * Hiển thị một địa chỉ cụ thể (thường ít dùng trong trang 'Tài khoản').
     */
    public function show(Address $address)
    {
        $this->authorizeUser($address);

        return view('account.addresses.show', compact('address'));
    }

    /**
     * Hiển thị form để chỉnh sửa địa chỉ.
     */
    public function edit(Address $address)
    {
        $this->authorizeUser($address);

        return view('account.addresses.edit', compact('address'));
    }

    /**
     * Cập nhật địa chỉ trong database.
     */
    public function update(Request $request, Address $address)
    {
        $this->authorizeUser($address);

        $validatedData = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'nullable|boolean',
        ]);

        $validatedData['address_line2'] = $validatedData['address_line2'] ?? 'Không';


        if ($request->boolean('is_default')) {
            DB::table('addresses')
                ->where('user_id', Auth::id())
                ->where('id', '!=', $address->id) // Trừ địa chỉ đang sửa
                ->update(['is_default' => false]);

            $validatedData['is_default'] = true;
        } else {
            $validatedData['is_default'] = false;
        }

        $address->update($validatedData);

        return redirect()->route('account.profile')
            ->with('success', 'Đã cập nhật địa chỉ thành công!');
    }

    /**
     * Xóa địa chỉ khỏi database.
     */
    public function destroy(Address $address)
    {
        $this->authorizeUser($address);

        $address->delete();

        return redirect()->route('account.profile')
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
