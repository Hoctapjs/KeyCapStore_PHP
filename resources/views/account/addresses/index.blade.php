@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Sổ Địa Chỉ Của Tôi</h2>
        </div>
        <div class="col-md-6 text-md-right">
            <a href="{{ route('addresses.create') }}" class="btn btn-primary">
                + Thêm Địa Chỉ Mới
            </a>
        </div>
    </div>

    {{-- Hiển thị thông báo thành công (nếu có) --}}
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    {{-- Kiểm tra xem có địa chỉ nào không --}}
    @if ($addresses->isEmpty())
    <div class="alert alert-info">
        Bạn chưa có địa chỉ nào được lưu.
    </div>
    @else
    <div class="row">
        {{-- Lặp qua từng địa chỉ --}}
        @foreach ($addresses as $address)
        <div class="col-md-6 mb-3">
            <div class="card {{ $address->is_default ? 'border-primary' : '' }}">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $address->full_name }}
                        {{-- Hiển thị tag Mặc Định --}}
                        @if ($address->is_default)
                        <span class="badge badge-primary" style="font-size: 0.8rem;">Mặc định</span>
                        @endif
                    </h5>

                    <p class="card-text mb-1">
                        <strong>Địa chỉ:</strong>
                        {{ $address->address_line1 }}
                        @if ($address->address_line2), {{ $address->address_line2 }} @endif
                        <br>
                        {{ $address->state }}, {{ $address->city }}, {{ $address->country }}
                    </p>
                    <p class="card-text">
                        <strong>Điện thoại:</strong> {{ $address->phone }}
                    </p>

                    <hr>

                    {{-- Nút Sửa --}}
                    <a href="{{ route('addresses.edit', $address) }}" class="btn btn-sm btn-secondary">
                        Chỉnh sửa
                    </a>

                    {{-- Nút Xóa (Dùng Form) --}}
                    <form action="{{ route('addresses.destroy', $address) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này không?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">
                            Xóa
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection