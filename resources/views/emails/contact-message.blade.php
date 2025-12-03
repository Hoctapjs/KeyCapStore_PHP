<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin nhắn liên hệ mới - IT KeyCap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            /* Nền nhẹ nhàng */
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            /* Nền nội dung trắng */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-top: 5px solid #61B745;
            /* Đường viền trên màu xanh lá chủ đạo */
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eeeeee;
        }

        .header h1 {
            color: #61B745;
            font-size: 24px;
            margin: 0;
        }

        .content {
            padding: 20px 0;
            line-height: 1.6;
        }

        /* Style riêng cho danh sách thông tin */
        .info-list {
            margin-bottom: 20px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            width: 80px;
            /* Căn thẳng hàng nhãn */
        }

        /* Style cho khối nội dung tin nhắn */
        .message-box {
            background-color: #f9f9f9;
            border-left: 4px solid #61B745;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #555;
            white-space: pre-line;
            /* Giữ nguyên xuống dòng của khách */
        }

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #61B745;
            /* Màu xanh lá cây đậm */
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .button:hover {
            background-color: #4a9235;
            /* Màu đậm hơn khi hover */
        }

        .footer {
            padding-top: 20px;
            border-top: 1px solid #eeeeee;
            text-align: center;
            font-size: 12px;
            color: #999999;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>IT KeyCap Contact</h1>
        </div>
        <div class="content">
            <p>Chào Admin,</p>
            <p>Hệ thống vừa nhận được một <strong>tin nhắn liên hệ mới</strong> từ website.</p>

            <div class="info-list">
                <div class="info-item">
                    <span class="label">Họ tên:</span> {{ $data['name'] }}
                </div>
                <div class="info-item">
                    <span class="label">Email:</span> <a href="mailto:{{ $data['email'] }}" style="color: #61B745; text-decoration: none;">{{ $data['email'] }}</a>
                </div>
                <div class="info-item">
                    <span class="label">Chủ đề:</span> {{ $data['subject'] ?? 'Không có chủ đề' }}
                </div>
            </div>

            <p><strong>Nội dung tin nhắn:</strong></p>

            <div class="message-box">
                "{{ $data['message'] }}"
            </div>

            <div class="button-container">
                {{-- Nút này dùng mailto: để admin bấm vào là trả lời ngay --}}
                <a href="mailto:{{ $data['email'] }}?subject=Re: {{ $data['subject'] ?? 'Hỗ trợ từ IT KeyCap' }}" class="button">
                    Trả lời khách hàng ngay
                </a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} IT KeyCap System.</p>
            <p>Email này được gửi tự động từ hệ thống website.</p>
        </div>
    </div>
</body>

</html>