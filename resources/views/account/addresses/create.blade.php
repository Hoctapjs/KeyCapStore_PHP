@extends('layouts.app')

@section('title', 'Thêm Địa Chỉ Mới')

@section('content')
<style>
    /* CSS tùy chỉnh cho bản đồ */
    .map-container {
        height: 100%;
        min-height: 450px;
        /* Đảm bảo chiều cao tối thiểu */
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: 0;
        position: absolute;
        top: 0;
        left: 0;
    }

    /* Giúp bản đồ trượt theo khi form dài (Sticky) */
    .map-sticky-wrapper {
        position: sticky;
        top: 20px;
        /* Cách mép trên 20px */
    }
</style>

<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <h2 class="mb-0 me-3">Thêm Địa Chỉ Mới</h2>
        <small class="text-muted">Nhập thông tin giao hàng chi tiết của bạn</small>
    </div>

    {{-- Hiển thị lỗi validation chung --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <strong>Vui lòng kiểm tra lại dữ liệu:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <form action="{{ route('addresses.store') }}" method="POST">
        @csrf

        <div class="row g-4"> {{-- g-4 tạo khoảng cách giữa 2 cột --}}

            {{-- CỘT TRÁI: FORM NHẬP LIỆU --}}
            <div class="col-lg-7">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">

                        {{-- Hàng 1: Tên & SĐT --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                    id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder="VD: Nguyễn Văn A" required>
                                @error('full_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}" placeholder="VD: 0901234567" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Địa chỉ 1 --}}
                        <div class="mb-3">
                            <label for="address_line1" class="form-label fw-bold">Địa chỉ (Số nhà, Tên đường) <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address_line1') is-invalid @enderror"
                                id="address_line1" name="address_line1" value="{{ old('address_line1') }}"
                                placeholder="VD: 99 Nguyễn Thị Minh Khai" required>
                            @error('address_line1')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Địa chỉ 2 --}}
                        <div class="mb-3">
                            <label for="address_line2" class="form-label">Địa chỉ 2 (Tòa nhà, Hẻm...)</label>
                            <input type="text" class="form-control @error('address_line2') is-invalid @enderror"
                                id="address_line2" name="address_line2" value="{{ old('address_line2') }}"
                                placeholder="VD: Tòa nhà Bitexco, Tầng 5">
                            @error('address_line2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Hàng 2: Tỉnh & Huyện --}}
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="city" class="form-label fw-bold">Tỉnh / Thành phố <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror"
                                    id="city" name="city" value="{{ old('city') }}" required>
                                @error('city')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="state" class="form-label fw-bold">Quận / Huyện <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror"
                                    id="state" name="state" value="{{ old('state') }}" required>
                                @error('state')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Hàng 3: Postal & Quốc gia --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="postal_code" class="form-label fw-bold">Mã bưu điện</label>
                                <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                    id="postal_code" name="postal_code" value="{{ old('postal_code') }}" placeholder="VD: 700000" required>
                                @error('postal_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="country" class="form-label fw-bold">Quốc gia <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('country') is-invalid @enderror"
                                    id="country" name="country" value="{{ old('country', 'Việt Nam') }}" required>
                                @error('country')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        {{-- Mặc định & Nút bấm --}}
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_default" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                                <label class="form-check-label user-select-none" for="is_default">Đặt làm địa chỉ mặc định</label>
                            </div>

                            <div>
                                <a href="{{ route('addresses.index') }}" class="btn btn-outline-secondary me-2">Hủy</a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-1"></i> Lưu Địa Chỉ
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- CỘT PHẢI: BẢN ĐỒ --}}
            <div class="col-lg-5">
                <div class="map-sticky-wrapper">
                    <div class="map-container bg-light">
                        {{-- Placeholder bản đồ Google Maps (Mặc định trỏ về TP.HCM) --}}
                        <iframe
                            id="google-map-preview"
                            src="https://maps.google.com/maps?q=Ho%20Chi%20Minh%20City,%20Vietnam&output=embed"
                            allowfullscreen=""
                            loading="lazy">
                        </iframe>
                    </div>
                    <div class="mt-3 text-muted small text-center">
                        <i class="fas fa-info-circle me-1"></i>
                        Nhập địa chỉ để xem vị trí tương ứng trên bản đồ.
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Sử dụng 'DOMContentLoaded' thay vì $(document).ready() để không phụ thuộc jQuery
    document.addEventListener('DOMContentLoaded', function() {

        // Hàm cập nhật bản đồ
        function updateMap() {
            var addressComponents = [];

            // Lấy giá trị bằng Vanilla JS
            var addr1 = document.getElementById('address_line1').value;
            var city = document.getElementById('city').value;
            var state = document.getElementById('state').value;
            var country = document.getElementById('country').value;

            // Chỉ thêm vào mảng nếu có dữ liệu
            if (addr1) addressComponents.push(addr1);
            if (state) addressComponents.push(state);
            if (city) addressComponents.push(city);
            if (country) addressComponents.push(country);

            // Nếu có ít nhất một thành phần địa chỉ
            if (addressComponents.length > 0) {
                var fullAddress = addressComponents.join(', ');

                // Tạo URL embed cho Google Maps (Search mode)
                var embedUrl = "https://maps.google.com/maps?q=" + encodeURIComponent(fullAddress) + "&output=embed";

                // Cập nhật src cho iframe
                var mapFrame = document.getElementById('google-map-preview');
                if (mapFrame) {
                    mapFrame.src = embedUrl;
                }
            }
        }

        // Danh sách các ID cần lắng nghe
        var inputIds = ['address_line1', 'city', 'state', 'country'];

        // Lặp qua từng input để gán sự kiện
        inputIds.forEach(function(id) {
            var element = document.getElementById(id);
            if (element) {
                // Lắng nghe sự kiện blur (khi rời khỏi ô nhập) và change
                element.addEventListener('blur', updateMap);
                element.addEventListener('change', updateMap);
            }
        });
    });
</script>
@endpush