{{--  resources/views/reviews/edit.blade.php  --}}
@extends('layouts.app')

@section('title', 'Chỉnh sửa đánh giá - ' . $review->product->title)

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
                <li class="breadcrumb-item"><a href="{{ route('products.show', $review->product->slug) }}">{{ Str::limit($review->product->title, 40) }}</a></li>
                <li class="breadcrumb-item active">Chỉnh sửa đánh giá</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold">Chỉnh sửa đánh giá</h2>
                            <p class="text-muted">Cập nhật trải nghiệm của bạn về sản phẩm</p>
                        </div>

                        <!-- Product Info -->
                        <div class="d-flex align-items-center gap-4 p-4 bg-white rounded shadow-sm mb-5">
                            @if($review->product->productImages->first())
                                <img src="{{ $review->product->productImages->first()->image_url }}"
                                    alt="{{ $review->product->title }}"
                                    class="rounded"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width: 100px; height: 100px;">
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $review->product->title }}</h5>
                                @if($review->product->brand)
                                    <p class="text-muted mb-0">Thương hiệu: {{ $review->product->brand->name }}</p>
                                @endif
                                <p class="h4 fw-bold text-primary mb-0">
                                    {{ number_format($review->product->price, 0, ',', '.') }}đ
                                </p>
                            </div>
                        </div>

                        <!-- Review Form -->
                        <form action="{{ route('review.update', $review) }}" method="POST" class="review-form">
                            @csrf
                            @method('PUT')

                            <!-- Rating -->
                            <div class="mb-4 text-center">
                                <label class="form-label d-block fw-bold fs-5 mb-3">
                                    Bạn đánh giá bao nhiêu sao? <span class="text-danger">*</span>
                                </label>
                            <div class="star-rating d-inline-block" id="starRating">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? 'active' : '' }}" data-value="{{ $i }}">★</span>
                                @endfor
                            </div>
                                <input type="hidden" name="rating" id="ratingValue" value="{{ old('rating', $review->rating) }}" required>
                                <div class="mt-2">
                                    <small id="ratingText" class="text-muted fw-bold">
                                        @switch($review->rating)
                                            @case(5) Rất hài lòng @break
                                            @case(4) Hài lòng @break
                                            @case(3) Bình thường @break
                                            @case(2) Không hài lòng @break
                                            @case(1) Rất không hài lòng @break
                                        @endswitch
                                    </small>
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
                                    value="{{ old('title', $review->title) }}"
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
                                        required>{{ old('content', $review->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="d-grid gap-3 d-md-flex justify-content-md-center">
                                <a href="{{ route('products.show', $review->product->slug) }}"
                                    class="btn btn-outline-secondary btn-lg px-5">
                                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                                </a>
                                <button type="submit" class="btn btn-warning btn-lg px-5">
                                    <i class="bi bi-check-circle me-2"></i>Cập nhật đánh giá
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Note -->
                <div class="text-center mt-4">
                    <small class="text-muted">
                        <i class="bi bi-info-circle"></i>
                        @if($review->status === 'rejected')
                            Đánh giá của bạn sẽ được kiểm duyệt lại sau khi chỉnh sửa.
                        @else
                            Đánh giá của bạn sẽ được cập nhật ngay lập tức.
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.star');
        const ratingValue = document.getElementById('ratingValue');
        const ratingText = document.getElementById('ratingText');
        
        const messages = {
            1: 'Rất không hài lòng',
            2: 'Không hài lòng',
            3: 'Bình thường',
            4: 'Hài lòng',
            5: 'Rất hài lòng'
        };

        // Set initial state based on current rating
        const currentRating = parseInt(ratingValue.value);
        highlightStars(currentRating);

        stars.forEach(star => {
            // Hover effect
            star.addEventListener('mouseenter', function() {
                const value = parseInt(this.getAttribute('data-value'));
                highlightStars(value);
                ratingText.textContent = messages[value];
            });

            // Click to select rating
            star.addEventListener('click', function() {
                const value = parseInt(this.getAttribute('data-value'));
                ratingValue.value = value;
                ratingText.textContent = messages[value];
            });
        });

        // Reset on mouse leave
        document.getElementById('starRating').addEventListener('mouseleave', function() {
            const selectedValue = parseInt(ratingValue.value);
            highlightStars(selectedValue);
            ratingText.textContent = messages[selectedValue];
        });

        function highlightStars(count) {
            stars.forEach((star, index) => {
                if (index < count) {
                    star.classList.add('active');
                } else {
                    star.classList.remove('active');
                }
            });
        }
    });
</script>
@endpush
