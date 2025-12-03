@extends('layouts.app')

@section('content')
<style>
    /* 1. Nền & Container chính */
    body {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
    }

    .custom-error-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        /* Giữ độ cao để căn giữa theo chiều dọc */
        padding: 20px;
    }

    /* 2. Khối nội dung (Card) - Mở rộng chiều ngang */
    .error-card {
        max-width: 1000px;
        /* Tăng từ 600px lên 1000px để phủ rộng hơn */
        width: 100%;
        background-color: #ffffff;
        border-radius: 20px;
        /* Bo góc mềm mại hơn */
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        /* Bóng đổ lan tỏa rộng hơn */
        display: flex;
        /* Dùng Flexbox để chia cột */
        flex-direction: row;
        /* Xếp ngang */
        align-items: center;
        /* Căn giữa theo trục dọc */
        overflow: hidden;
        /* Để cắt các phần tử trang trí nếu có */
        position: relative;
    }

    /* 3. Cột bên trái: Số 404 */
    .error-left {
        flex: 1;
        /* Chiếm 1 phần không gian */
        background-color: #e9f7ef;
        /* Nền xanh lá rất nhạt để làm nổi bật số */
        padding: 80px 40px;
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }

    .error-code {
        font-size: 12rem;
        /* Tăng kích thước cực đại */
        font-weight: 900;
        color: #28a745;
        /* Màu xanh lá chủ đạo (Bootstrap success) */
        line-height: 0.8;
        letter-spacing: -5px;
        text-shadow: 5px 5px 0px rgba(40, 167, 69, 0.1);
        margin: 0;
    }

    .error-sub-text {
        margin-top: 10px;
        font-size: 1.2rem;
        font-weight: 600;
        color: #28a745;
        opacity: 0.8;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    /* 4. Cột bên phải: Nội dung text */
    .error-right {
        flex: 1.2;
        /* Chiếm không gian rộng hơn một chút (1.2 phần) */
        padding: 60px 60px 60px 40px;
        text-align: left;
        /* Căn trái để dễ đọc */
    }

    .error-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #333;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .error-message {
        font-size: 1.1rem;
        color: #666;
        margin-bottom: 40px;
        line-height: 1.6;
        max-width: 90%;
        /* Giới hạn độ rộng dòng chữ để dễ đọc */
    }

    /* Nút bấm được style lại */
    .home-button {
        display: inline-flex;
        align-items: center;
        padding: 15px 35px;
        font-size: 1rem;
        font-weight: 700;
        color: #ffffff;
        background-color: #28a745;
        border: none;
        border-radius: 50px;
        /* Bo tròn hoàn toàn (Pill shape) */
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
        /* Bóng đổ màu xanh */
    }

    .home-button:hover {
        background-color: #218838;
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(40, 167, 69, 0.4);
        color: #fff;
    }

    .home-button i {
        margin-right: 10px;
    }

    /* 5. Responsive: Trên mobile sẽ quay về dọc */
    @media (max-width: 991px) {
        .error-card {
            flex-direction: column;
            /* Xếp dọc lại */
            max-width: 500px;
        }

        .error-left {
            width: 100%;
            padding: 40px 20px;
        }

        .error-right {
            width: 100%;
            padding: 40px 30px;
            text-align: center;
            /* Quay về căn giữa trên mobile */
        }

        .error-message {
            margin-left: auto;
            margin-right: auto;
        }

        .error-code {
            font-size: 8rem;
        }
    }
</style>

<div class="custom-error-container">
    <div class="error-card">
        {{-- Cột Trái: Visual Số 404 --}}
        <div class="error-left">
            <h1 class="error-code">404</h1>
            <div class="error-sub-text">Page Not Found</div>
        </div>

        {{-- Cột Phải: Nội dung & Hành động --}}
        <div class="error-right">
            <h2 class="error-title">Oops! <br>Trang bạn truy cập hiện không tồn tại!</h2>

            <p class="error-message">
                Trang bạn tìm kiếm có thể đã bị gỡ bỏ, đổi tên hoặc tạm thời không khả dụng.
                Đừng lo, hãy quay lại trang sản phẩm để tìm bộ keycap ưng ý khác nhé!
            </p>

            <a href="{{ url('/') }}" class="home-button">
                <i class="fas fa-arrow-left"></i> Quay về trang chủ
            </a>
        </div>
    </div>
</div>
@endsection