{{--  resources/views/products/review.blade.php  --}}
@extends('layouts.app')

@section('title', 'Viết đánh giá - ' . $product->title)

@push('styles')
<style>
    .star-rating {
        font-size: 2.5rem;
        cursor: pointer;
        user-select: none;
    }
    .star-rating .star {
        color: #ddd;
        transition: color 0.2s;
    }
    .star-rating .star:hover,
    .star-rating .star.active {
        color: #ffc107 !important;
        text-shadow: 0 0 5px rgba(255,193,7,0.6);
        transform: scale(1.1);
        transition: all 0.2s;
    }
    .review-form .form-control,
    .review-form .form-select {
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
    }
    .review-form textarea.form-control {
        min-height: 150px;
        resize: vertical;
    }
</style>
@endpush

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.show', $product->slug) }}">{{ Str::limit($product->title, 40) }}</a></li>
                <li class="breadcrumb-item active">Viết đánh giá</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold">Đánh giá sản phẩm</h2>
                            <p class="text-muted">Chia sẻ trải nghiệm của bạn để giúp người khác mua sắm tốt hơn</p>
                        </div>

                        <!-- Product Info -->
                        <div class="d-flex align-items-center gap-4 p-4 bg-white rounded shadow-sm mb-5">
                            @if($product->productImages->first())
                                <img src="{{ $product->productImages->first()->image_url }}"
                                    alt="{{ $product->title }}"
                                    class="rounded"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 100px; height: 100px;">
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $product->title }}</h5>
                                @if($product->brand)
                                    <p class="text-muted mb-0">Thương hiệu: {{ $product->brand->name }}</p>
                                @endif
                                <p class="h4 fw-bold text-primary mb-0">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </p>
                            </div>
                        </div>

                        <!-- Review Form -->
                        <form action="{{ route('review.store', $product->id) }}" method="POST" class="review-form">
                            @csrf

                            <!-- Rating -->
                            <div class="mb-4 text-center">
                                <label class="form-label d-block fw-bold fs-5 mb-3">
                                    Bạn đánh giá bao nhiêu sao? <span class="text-danger">*</span>
                                </label>
                            <div class="star-rating d-inline-block" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star" data-value="{{ $i }}">★</span>
                                @endfor
                            </div>
                                <input type="hidden" name="rating" id="ratingValue" value="5" required>
                                <div class="mt-2">
                                    <small id="ratingText" class="text-muted fw-bold">Rất hài lòng</small>
                                </div>
                            </div>
                            @error('rating')
                                <div class="text-danger text-center mb-3">{{ $message }}</div>
                            @enderror

                            <!-- Title -->
                            <div class="mb-4">
                                <label for="title" class="form-label fw-bold">
                                    Tiêu đề đánh giá <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}"
                                    placeholder="Ví dụ: Sản phẩm tuyệt vời, giao hàng nhanh!"
                                    required>

                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Content -->
                            <div class="mb-4">
                                <label for="content" class="form-label fw-bold">
                                    Nội dung đánh giá <span class="text-danger">*</span>
                                </label>
                                <textarea name="content" id="content" class="form-control @error('content') is-invalid @enderror"
                                        placeholder="Hãy chia sẻ cảm nhận của bạn về sản phẩm này... (chất lượng, đóng gói, giao hàng,...)"
                                        required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                                <a href="{{ route('products.show', $product->slug) }}"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-send me-2"></i>Gửi đánh giá
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Note -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        Đánh giá của bạn sẽ được kiểm duyệt trước khi hiển thị công khai.
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const stars = document.querySelectorAll('.star-rating .star');
    const ratingValue = document.getElementById('ratingValue');
    const ratingText = document.getElementById('ratingText');

    const ratingMessages = {
        1: 'Rất tệ',
        2: 'Tệ',
        3: 'Bình thường',
        4: 'Hài lòng',
        5: 'Rất hài lòng'
    };

    // Hàm cập nhật màu sao + text theo giá trị
    function updateRatingDisplay(value) {
        stars.forEach(star => {
            if (parseInt(star.dataset.value) <= value) {
                star.classList.add('active');
                star.style.color = '#ffc107';
            } else {
                star.classList.remove('active');
                star.style.color = '#ddd';
            }
        });

        ratingText.textContent = ratingMessages[value];
        ratingText.className = 'fw-bold ';
        if (value >= 4) ratingText.classList.add('text-success');
        else if (value <= 2) ratingText.classList.add('text-primary');
        else ratingText.classList.add('text-primary');
    }

    // Khởi tạo: mặc định 5 sao
    let currentRating = 5;
    ratingValue.value = 5;
    updateRatingDisplay(currentRating);

    // Khi CLICK → lưu giá trị đã chọn
    stars.forEach(star => {
        star.addEventListener('click', function () {
            currentRating = parseInt(this.dataset.value);
            ratingValue.value = currentRating;
            updateRatingDisplay(currentRating);
        });

        // Khi HOVER → tạm thời hiện text tương ứng
        star.addEventListener('mouseover', function () {
            const hoverValue = parseInt(this.dataset.value);
            updateRatingDisplay(hoverValue); // hiện tạm text khi hover
        });
    });

    // Khi RỜI CHUỘT khỏi khu vực sao → quay về giá trị đã chọn
    document.getElementById('starRating').addEventListener('mouseleave', function () {
        updateRatingDisplay(currentRating);
    });

    // Nếu có old input (lỗi validate) → khôi phục trạng thái
    const savedRating = @json(old('rating'));
    if (savedRating !== null) {
        currentRating = savedRating;
        ratingValue.value = currentRating;
        updateRatingDisplay(currentRating);
    }
});
</script>
@endpush
