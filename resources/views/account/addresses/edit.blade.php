@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Chỉnh Sửa Địa Chỉ</h2>

    {{-- Hiển thị lỗi validation chung (nếu có) --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{--
      - action trỏ đến route 'addresses.update' và truyền vào $address
      - method là POST, nhưng bên trong dùng @method('PUT')
    --}}
    <form action="{{ route('addresses.update', $address) }}" method="POST">
        @csrf
        @method('PUT') {{-- Rất quan trọng cho việc Update --}}

        <div class="row">
            {{-- Họ và tên --}}
            <div class="form-group col-md-6">
                <label for="full_name">Họ và tên:</label>
                {{--
                  - old('full_name', $address->full_name) 
                  - Ưu tiên hiển thị dữ liệu 'old' (nếu validation fail)
                  - Nếu không có 'old', hiển thị dữ liệu từ CSDL ($address->full_name)
                --}}
                <input type="text" class="form-control" id="full_name" name="full_name"
                    value="{{ old('full_name', $address->full_name) }}" required>
                @error('full_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Số điện thoại --}}
            <div class="form-group col-md-6">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="{{ old('phone', $address->phone) }}" required>
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Địa chỉ dòng 1 --}}
        <div class="form-group">
            <label for="address_line1">Địa chỉ (Số nhà, Tên đường):</label>
            <input type="text" class="form-control" id="address_line1" name="address_line1"
                value="{{ old('address_line1', $address->address_line1) }}" required>
            @error('address_line1')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Địa chỉ dòng 2 (Tùy chọn) --}}
        <div class="form-group">
            <label for="address_line2">Địa chỉ 2 (Tòa nhà, Hẻm... - Tùy chọn):</label>
            <input type="text" class="form-control" id="address_line2" name="address_line2"
                value="{{ old('address_line2', $address->address_line2) }}">
            @error('address_line2')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            {{-- Thành phố/Tỉnh --}}
            <div class="form-group col-md-6">
                <label for="city">Tỉnh / Thành phố:</label>
                <input type="text" class="form-control" id="city" name="city"
                    value="{{ old('city', $address->city) }}" required>
                @error('city')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Quận/Huyện --}}
            <div class="form-group col-md-6">
                <label for="state">Quận / Huyện:</label>
                <input type="text" class="form-control" id="state" name="state"
                    value="{{ old('state', $address->state) }}" required>
                @error('state')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            {{-- Mã bưu điện --}}
            <div class="form-group col-md-6">
                <label for="postal_code">Mã bưu điện (Postal Code):</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code"
                    value="{{ old('postal_code', $address->postal_code) }}" required>
                @error('postal_code')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Quốc gia --}}
            <div class="form-group col-md-6">
                <label for="country">Quốc gia:</label>
                <input type="text" class="form-control" id="country" name="country"
                    value="{{ old('country', $address->country) }}" required>
                @error('country')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Đặt làm mặc định --}}
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1"
                {{ old('is_default', $address->is_default) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
        </div>

        <br>
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="{{ route('account.profile') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection