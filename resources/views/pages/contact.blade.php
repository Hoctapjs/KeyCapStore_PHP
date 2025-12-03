@extends('layouts.app')

@section('title', 'Liên Hệ Với Chúng Tôi')

@push('styles')
<style>
    /* Style cho các khối thông tin liên hệ (mô phỏng product-item) */
    /* ... (CSS cũ giữ nguyên) ... */
    .contact-info-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .contact-info-card:hover {
        transform: translateY(-5px);
    }

    .contact-icon {
        font-size: 2.5rem;
        color: var(--bs-primary);
        margin-bottom: 15px;
    }

    /* Style riêng cho Form */
    .contact-form-card {
        background: #f8f9fa;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Style cho Bản đồ (tùy chỉnh chiều cao) */
    .map-container {
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
    }
</style>
@endpush

@section('content')

<section class="py-5 overflow-hidden">
    <div class="container-fluid">

        {{-- KHỐI THÔNG BÁO TỐI ƯU --}}
        {{-- Đặt ngay dưới container-fluid để hiển thị trên cùng --}}
        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- Hiển thị thông báo Gửi thành công (Success) --}}
                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <strong>Thành công!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {{-- Hiển thị thông báo Lỗi (Error) --}}
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Lỗi!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
            </div>
        </div>
        {{-- KẾT THÚC KHỐI THÔNG BÁO TỐI ƯU --}}

        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex flex-wrap justify-content-between mb-5 text-center">
                    <h2 class="section-title mx-auto">Liên Hệ: Chúng Tôi Luôn Sẵn Sàng Phục Vụ</h2>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">

            {{-- Card 1: Địa chỉ --}}
            <div class="col">
                <div class="contact-info-card">
                    <i class="fas fa-map-marker-alt contact-icon"></i>
                    <h5 class="fw-bold mb-2">Vị Trí Cửa Hàng</h5>
                    <p class="text-muted mb-0">140 Lê Trọng Tấn, Tây Thạnh, Tân Phú, TP.HCM</p>
                </div>
            </div>

            {{-- Card 2: Email --}}
            <div class="col">
                <div class="contact-info-card">
                    <i class="fas fa-envelope contact-icon"></i>
                    <h5 class="fw-bold mb-2">Hỗ Trợ Email</h5>
                    <p class="mb-0"><a href="mailto:sonht27@huynhthanhson.io.vn" class="text-primary">sonht27@huynhthanhson.io.vn</a></p>
                    <p class="text-muted mb-0"><small>Phản hồi trong vòng 24 giờ</small></p>
                </div>
            </div>

            {{-- Card 3: Điện thoại --}}
            <div class="col">
                <div class="contact-info-card">
                    <i class="fas fa-phone-alt contact-icon"></i>
                    <h5 class="fw-bold mb-2">Đường Dây Nóng</h5>
                    <p class="mb-0"><a href="tel:0815656123" class="text-primary">0815 656 123</a></p>
                    <p class="text-muted mb-0"><small>Thứ 2 - Thứ 6 (8h00 - 17h00)</small></p>
                </div>
            </div>
        </div>

        <div class="row g-4">

            {{-- Phần 1: Form Liên hệ (Đã tối ưu lỗi xác thực) --}}
            <div class="col-lg-6">
                <div class="contact-form-card">
                    <h3 class="fw-bold mb-4">Gửi Tin Nhắn Cho Chúng Tôi</h3>
                    <form action="{{ route('contact.submit') ?? '#' }}" method="POST">
                        @csrf

                        {{-- Tên của bạn --}}
                        <div class="mb-3">
                            <input type="text"
                                name="name"
                                class="form-control form-control-lg @error('name') is-invalid @enderror"
                                placeholder="Tên của bạn *"
                                required
                                value="{{ old('name') }}">
                            @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Email của bạn --}}
                        <div class="mb-3">
                            <input type="email"
                                name="email"
                                class="form-control form-control-lg @error('email') is-invalid @enderror"
                                placeholder="Email của bạn *"
                                required
                                value="{{ old('email') }}">
                            @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Chủ đề --}}
                        <div class="mb-3">
                            <input type="text"
                                name="subject"
                                class="form-control form-control-lg @error('subject') is-invalid @enderror"
                                placeholder="Chủ đề"
                                value="{{ old('subject') }}">
                            @error('subject')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        {{-- Nội dung tin nhắn --}}
                        <div class="mb-4">
                            <textarea name="message"
                                class="form-control @error('message') is-invalid @enderror"
                                rows="5"
                                placeholder="Nội dung tin nhắn *"
                                required>{{ old('message') }}</textarea>
                            @error('message')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-paper-plane me-2"></i> Gửi Tin Nhắn
                        </button>
                    </form>
                </div>
            </div>

            {{-- Phần 2: Bản đồ/Vị trí --}}
            <div class="col-lg-6">
                <div class="map-container shadow-sm">
                    {{-- Mã nhúng Google Map của vị trí Shop Keycap --}}

                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.0572728105417!2d106.62625411079361!3d10.806925558585647!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27d8b4f4d%3A0x92dcba2950430867!2sHCMC%20University%20of%20Industry%20and%20Trade!5e0!3m2!1sen!2s!4v1764752867265!5m2!1sen!2s" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function() {
                $(this).remove();
            });
        }, 10000);
    });
</script>
@endpush