@extends('layouts.app')

@section('title', 'Về chúng tôi')

@push('styles')
<style>
    /* Tái sử dụng phong cách của product-item cho các khối tính năng */
    .feature-item {
        height: 100%;
        display: flex;
        flex-direction: column;
        padding: 2rem;
        background: #fff;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        text-align: center;
        border-radius: 8px;
    }

    .feature-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.05);
        border-color: var(--bs-primary);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-radius: 50%;
        color: var(--bs-primary);
        font-size: 1.5rem;
    }

    /* Style cho ảnh giới thiệu */
    .about-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
        height: 100%;
        min-height: 400px;
    }

    .about-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .about-image-container:hover .about-image {
        transform: scale(1.05);
    }

    /* Thống kê số liệu */
    .stat-box {
        text-align: center;
        padding: 2rem;
        border-right: 1px solid #eee;
    }

    .stat-box:last-child {
        border-right: none;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--bs-primary);
        margin-bottom: 0.5rem;
        display: block;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        text-transform: uppercase;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')

<section class="py-5 overflow-hidden">
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="section-header d-flex flex-wrap justify-content-between mb-5">
                    <h2 class="section-title">Câu Chuyện Của Chúng Tôi</h2>
                </div>
            </div>
        </div>

        <div class="row mb-5 align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div class="about-image-container shadow-sm">
                    {{-- Placeholder ảnh: Bạn thay bằng ảnh shop thực tế --}}

                    <img src="{{ asset('images/keycapabout.jpg') }}"
                        alt="Keycap Workshop" class="about-image">
                </div>
            </div>
            <div class="col-lg-6 ps-lg-5">
                <h3 class="fw-bold mb-3">Từ Đam Mê Đến Sự Hoàn Hảo</h3>
                <p class="text-muted mb-4 lead">
                    Chào mừng bạn đến với thế giới của những phím bấm đầy màu sắc. Chúng tôi không chỉ bán keycap, chúng tôi bán trải nghiệm gõ phím tuyệt vời nhất.
                </p>
                <p class="text-muted mb-3">
                    Bắt đầu từ một nhóm nhỏ những người đam mê bàn phím cơ vào năm 2020, chúng tôi nhận thấy thị trường thiếu vắng những bộ keycap vừa chất lượng, vừa có mức giá hợp lý nhưng vẫn mang đậm dấu ấn cá nhân.
                </p>
                <p class="text-muted mb-4">
                    Tại đây, mỗi bộ keycap đều được tuyển chọn kỹ lưỡng từ chất liệu nhựa PBT cao cấp, công nghệ in Double-shot bền bỉ cho đến những mẫu Artisan được chế tác thủ công tinh xảo. Sứ mệnh của chúng tôi là biến góc làm việc của bạn trở thành một tác phẩm nghệ thuật.
                </p>

                <div class="d-flex gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4">Xem Sản Phẩm</a>
                    <a href="/contact" class="btn btn-outline-dark btn-lg px-4">Liên Hệ</a>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <span class="stat-number">3+</span>
                                    <span class="stat-label">Năm Hoạt Động</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <span class="stat-number">500+</span>
                                    <span class="stat-label">Mẫu Keycap</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <span class="stat-number">10k+</span>
                                    <span class="stat-label">Đơn Hàng</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="stat-box">
                                    <span class="stat-number">98%</span>
                                    <span class="stat-label">Hài Lòng</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="section-header mb-5 text-center">
                    <h2 class="section-title">Tại Sao Chọn Chúng Tôi?</h2>
                    <p class="text-muted mt-2">Cam kết chất lượng trên từng switch</p>
                </div>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 mb-5">
            <div class="col">
                <div class="feature-item">
                    <div class="feature-icon">
                        {{-- Icon Quality --}}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-3">Chất Lượng Cao Cấp</h5>
                    <p class="text-muted mb-0">Chuyên các dòng keycap PBT, ABS Double-shot, đảm bảo độ bền và cảm giác gõ tốt nhất.</p>
                </div>
            </div>

            <div class="col">
                <div class="feature-item">
                    <div class="feature-icon">
                        {{-- Icon Shipping --}}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-3">Giao Hàng Tốc Độ</h5>
                    <p class="text-muted mb-0">Đóng gói kỹ càng chống va đập (full box, tray) và giao hàng nhanh chóng toàn quốc.</p>
                </div>
            </div>

            <div class="col">
                <div class="feature-item">
                    <div class="feature-icon">
                        {{-- Icon Support --}}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-3">Tư Vấn Tận Tâm</h5>
                    <p class="text-muted mb-0">Đội ngũ am hiểu về layout, profile keycap sẵn sàng hỗ trợ bạn chọn set phù hợp.</p>
                </div>
            </div>

            <div class="col">
                <div class="feature-item">
                    <div class="feature-icon">
                        {{-- Icon Return --}}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                    </div>
                    <h5 class="fw-bold mb-3">Đổi Trả Dễ Dàng</h5>
                    <p class="text-muted mb-0">Chính sách bảo hành rõ ràng, hỗ trợ đổi trả nếu sản phẩm có lỗi từ nhà sản xuất.</p>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection