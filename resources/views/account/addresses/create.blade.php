@extends('layouts.app')

@section('content')
<div classclass="container">
    <h2>Thêm Địa Chỉ Mới</h2>

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

    <form action="{{ route('addresses.store') }}" method="POST">
        @csrf {{-- Rất quan trọng! --}}

        <div class="row">
            {{-- Họ và tên --}}
            <div classclass="form-group col-md-6">
                <label for="full_name">Họ và tên:</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                @error('full_name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Số điện thoại --}}
            <div classclass="form-group col-md-6">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Địa chỉ dòng 1 --}}
        <div class="form-group">
            <label for="address_line1">Địa chỉ (Số nhà, Tên đường):</label>
            <input type="text" class="form-control" id="address_line1" name="address_line1" value="{{ old('address_line1') }}" required>
            @error('address_line1')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Địa chỉ dòng 2 (Tùy chọn) --}}
        <div class="form-group">
            <label for="address_line2">Địa chỉ 2 (Tòa nhà, Hẻm... - Tùy chọn):</label>
            <input type="text" class="form-control" id="address_line2" name="address_line2" value="{{ old('address_line2') }}">
            @error('address_line2')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            {{-- Thành phố/Tỉnh --}}
            <div class="form-group col-md-6">
                <label for="city">Tỉnh / Thành phố:</label>
                <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                @error('city')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Quận/Huyện --}}
            <div class="form-group col-md-6">
                <label for="state">Quận / Huyện:</label>
                <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                @error('state')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            {{-- Mã bưu điện --}}
            <div class="form-group col-md-6">
                <label for="postal_code">Mã bưu điện (Postal Code):</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code') }}" required>
                @error('postal_code')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            {{-- Quốc gia --}}
            <div class="form-group col-md-6">
                <label for="country">Quốc gia:</label>
                {{-- Bạn có thể thay bằng dropdown nếu muốn --}}
                <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Việt Nam') }}" required>
                @error('country')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Đặt làm mặc định --}}
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_default">Đặt làm địa chỉ mặc định</label>
        </div>

        <br>
        <button type="submit" class="btn btn-primary">Lưu Địa Chỉ</button>
        <a href="{{ route('addresses.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection