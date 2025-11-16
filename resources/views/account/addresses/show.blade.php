@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Chi Tiết Địa Chỉ</h2>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">
                        {{ $address->full_name }}
                        @if ($address->is_default)
                        <span class="badge badge-primary">Mặc định</span>
                        @endif
                    </h5>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Số điện thoại:</strong>
                        </div>
                        <div class="col-md-8">
                            {{ $address->phone }}
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-4">
                            <strong>Địa chỉ:</strong>
                        </div>
                        <div class="col-md-8">
                            <p class="mb-0">{{ $address->address_line1 }}</p>
                            @if ($address->address_line2)
                            <p class="mb-0">{{ $address->address_line2 }}</p>
                            @endif
                            <p class="mb-0">{{ $address->state }}, {{ $address->city }}</p>
                            <p class="mb-0">{{ $address->country }}</p>
                            <p class="mb-0"><strong>Mã bưu điện:</strong> {{ $address->postal_code }}</p>
                        </div>
                    </div>

                    <hr>

                    <a href="{{ route('addresses.index') }}" class="btn btn-secondary">
                        &larr; Quay lại danh sách
                    </a>
                    <a href="{{ route('addresses.edit', $address) }}" class="btn btn-primary">
                        Chỉnh sửa
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection