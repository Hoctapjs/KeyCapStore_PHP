<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu cấp lại mật khẩu</title>
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

        .button-container {
            text-align: center;
            margin: 30px 0;
        }

        .button {
            display: inline-block;
            padding: 12px 25px;
            background-color: #61B745;
            /* Màu xanh lá cây đậm từ nút Reset */
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

        .highlight {
            font-weight: bold;
            color: #e57373;
            /* Màu đỏ nhẹ cho thông báo quan trọng như hết hạn */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>IT KeyCap</h1>
        </div>
        <div class="content">
            <p>Chào bạn,</p>
            <p>Bạn nhận được email này vì chúng tôi nhận được yêu cầu <strong>cấp lại mật khẩu</strong> cho tài khoản của bạn tại IT KeyCap.</p>

            <div class="button-container">
                <a href="{{ $resetUrl }}" class="button">Cấp lại mật khẩu</a>
            </div>

            <p>Vui lòng nhấp vào nút trên để tạo mật khẩu mới. Vì lý do bảo mật, link này sẽ hết hạn trong <span class="highlight">60 phút</span>.</p>
            <p>Nếu bạn <strong>không</strong> yêu cầu cấp lại mật khẩu, vui lòng bỏ qua email này. Mật khẩu hiện tại của bạn sẽ không bị thay đổi.</p>
        </div>
        <div class="footer">
            <p>&copy; IT KeyCap. Cảm ơn bạn đã tin tưởng chúng tôi.</p>
            <p>Đây là email tự động, vui lòng không trả lời.</p>
        </div>
    </div>
</body>

</html>