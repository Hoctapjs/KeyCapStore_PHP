@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Thanh toán</h2>

    <div class="row">
        {{-- Cột trái: thông tin giao hàng --}}
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    Thông tin giao hàng
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
                        @csrf

                        {{-- Dropdown chọn địa chỉ đã lưu --}}
                        @if($addresses->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Chọn địa chỉ đã lưu</label>
                            <select class="form-select" id="saved-addresses">
                                <option value="">-- Nhập địa chỉ mới --</option>
                                @foreach($addresses as $addr)
                                <option value="{{ $addr->id }}"
                                    data-name="{{ $addr->full_name }}"
                                    data-phone="{{ $addr->phone }}"
                                    data-address="{{ $addr->address_line1 }}{{ $addr->address_line2 ? ', ' . $addr->address_line2 : '' }}{{ $addr->city ? ', ' . $addr->city : '' }}{{ $addr->state ? ', ' . $addr->state : '' }}"
                                    {{ $addr->is_default ? 'selected' : '' }}>
                                    {{ $addr->full_name }} - {{ $addr->phone }}
                                    @if($addr->is_default) (Mặc định) @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">Họ và tên</label>
                            <input type="text" name="full_name" id="full_name" class="form-control"
                                value="{{ old('full_name', $defaultAddress->full_name ?? auth()->user()->name ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" class="form-control"
                                value="{{ old('phone', $defaultAddress->phone ?? auth()->user()->phone ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="address" id="address" rows="3" class="form-control" required>{{ old('address', $defaultAddress ? ($defaultAddress->address_line1 . ($defaultAddress->address_line2 ? ', ' . $defaultAddress->address_line2 : '') . ($defaultAddress->city ? ', ' . $defaultAddress->city : '') . ($defaultAddress->state ? ', ' . $defaultAddress->state : '')) : '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" rows="3" class="form-control">{{ old('note') }}</textarea>
                        </div>

                        <!-- {{-- Nếu sau này dùng mã giảm giá:
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <input type="text" name="coupon_code" class="form-control" value="{{ old('coupon_code') }}">
                </div>
                --}} -->
                        <div class="mb-3">
                            <label class="form-label">Mã giảm giá</label>
                            <input
                                type="text"
                                name="coupon_code"
                                class="form-control"
                                value="{{ old('coupon_code') }}"
                                placeholder="Nhập mã nếu có">
                            @error('coupon_code')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Chọn phương thức thanh toán -->
                        @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    id="pm_cod"
                                    value="cod"
                                    {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pm_cod">
                                    Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    id="pm_vnpay"
                                    value="vnpay"
                                    {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pm_vnpay">
                                    Thanh toán online qua VNPay
                                </label>
                            </div>

                            <div class="form-check">
                                <input
                                    class="form-check-input"
                                    type="radio"
                                    name="payment_method"
                                    id="pm_momo"
                                    value="momo"
                                    {{ old('payment_method') === 'momo' ? 'checked' : '' }}>
                                <label class="form-check-label" for="pm_momo">
                                    Thanh toán ví MoMo (dự kiến)
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cột phải: tóm tắt đơn hàng --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    Đơn hàng của bạn
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        @foreach($cart->items as $item)
                        <li class="list-group-item d-flex justify-content-between">
                            <div>
                                <div>{{ $item->product->title }}</div>
                                @if($item->variant)
                                <small class="text-muted">{{ $item->variant->name }}</small><br>
                                @endif
                                <small class="text-muted">SL: {{ $item->quantity }}</small>
                            </div>
                            <span>
                                {{ number_format($item->price_snapshot * $item->quantity, 0, ',', '.') }}₫
                            </span>
                        </li>
                        @endforeach

                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tạm tính</span>
                            <strong>{{ number_format($subtotal, 0, ',', '.') }}₫</strong>
                        </li>
                    </ul>

                    <button type="submit" form="checkout-form" class="btn btn-success w-100">
                        Xác nhận đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const savedAddresses = document.getElementById('saved-addresses');
    
    if (savedAddresses) {
        savedAddresses.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (this.value === '') {
                // Nhập địa chỉ mới - xóa các trường
                document.getElementById('full_name').value = '{{ auth()->user()->name ?? "" }}';
                document.getElementById('phone').value = '{{ auth()->user()->phone ?? "" }}';
                document.getElementById('address').value = '';
            } else {
                // Điền thông tin từ địa chỉ đã chọn
                document.getElementById('full_name').value = selectedOption.dataset.name || '';
                document.getElementById('phone').value = selectedOption.dataset.phone || '';
                document.getElementById('address').value = selectedOption.dataset.address || '';
            }
        });
    }
});
</script>
@endpush
@endsection