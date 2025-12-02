<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel AI Chatbot</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS Reset & Global --- */
        * {
            box-sizing: border-box;
        }

        body {
            background: #E8EBF5;
            padding: 0;
            margin: 0;
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
        }

        /* --- Chat Box Main Container --- */
        .chat-box {
            height: 85%;
            width: 380px;
            position: fixed;
            margin: 0 auto;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            z-index: 9999;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
            right: 20px;
            bottom: 20px;
            background: #fff;
            border-radius: 20px;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            transform: translateY(20px);
        }

        .chat-box.active {
            visibility: visible;
            opacity: 1;
            transform: translateY(0);
        }

        /* --- Header --- */
        .chat-box-header {
            padding: 15px 20px;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: linear-gradient(135deg, #2C50EF, #4D73FF);
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }

        .chat-box-header h3 {
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
        }

        .chat-box-header .close-btn {
            cursor: pointer;
            padding: 5px;
            font-size: 1.2rem;
            transition: transform 0.2s;
        }

        .chat-box-header .close-btn:hover {
            transform: scale(1.1);
        }

        /* --- Body (Messages) --- */
        .chat-box-body {
            flex-grow: 1;
            background: #f4f7f6;
            overflow-y: auto;
            padding: 20px;
            scroll-behavior: smooth;
        }

        /* Message Styles */
        .chat-box-body-send,
        .chat-box-body-receive {
            width: fit-content;
            max-width: 80%;
            padding: 12px 18px;
            margin-bottom: 16px;
            position: relative;
            word-wrap: break-word;
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* Tin nhắn User (Gửi đi) */
        .chat-box-body-send {
            float: right;
            clear: both;
            background: #2C50EF;
            color: white;
            border-radius: 20px 20px 5px 20px;
            box-shadow: 0 2px 5px rgba(44, 80, 239, 0.2);
        }

        /* Tin nhắn Bot (Nhận về) */
        .chat-box-body-receive {
            float: left;
            clear: both;
            background: white;
            color: #333;
            border-radius: 20px 20px 20px 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
        }

        .chat-box-body-send p,
        .chat-box-body-receive p {
            margin: 0;
        }

        /* Thời gian tin nhắn */
        .chat-box-body span {
            display: block;
            font-size: 0.75rem;
            margin-top: 5px;
            opacity: 0.8;
            font-weight: 600;
        }

        .chat-box-body-send span {
            text-align: right;
            color: #e0e0e0;
        }

        .chat-box-body-receive span {
            text-align: left;
            color: #999;
        }

        /* Scrollbar tinh tế */
        .chat-box-body::-webkit-scrollbar {
            width: 6px;
        }

        .chat-box-body::-webkit-scrollbar-thumb {
            background: #dcdcdc;
            border-radius: 10px;
        }

        .chat-box-body::-webkit-scrollbar-track {
            background: transparent;
        }

        /* --- Footer (Input) --- */
        .chat-box-footer {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 15px;
            background: #fff;
            border-top: 1px solid #f0f0f0;
        }

        .chat-box-footer input {
            padding: 12px 20px;
            border: 1px solid #e0e0e0;
            border-radius: 30px;
            background: #f8f9fa;
            margin-right: 10px;
            font-family: 'Nunito', sans-serif;
            color: #444;
            flex-grow: 1;
            outline: none;
            transition: all 0.2s;
        }

        .chat-box-footer input:focus {
            background: #fff;
            border-color: #2C50EF;
            box-shadow: 0 0 0 3px rgba(44, 80, 239, 0.1);
        }

        .chat-box-footer .send {
            background: none;
            border: none;
            cursor: pointer;
            color: #2C50EF;
            font-size: 20px;
            padding: 10px;
            transition: transform 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chat-box-footer .send:hover {
            transform: scale(1.1);
        }

        .chat-box-footer .send.disabled {
            color: #ccc;
            cursor: not-allowed;
        }

        /* Typing Indicator */
        .typing-indicator-container {
            display: table;
            clear: both;
            float: left;
            margin-bottom: 10px;
        }

        .typing-bubble {
            background-color: #fff;
            padding: 10px 15px;
            border-radius: 20px;
            border-bottom-left-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            border: 1px solid #eee;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-width: 60px;
            height: 40px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background-color: #90949c;
            border-radius: 50%;
            display: inline-block;
            animation: typing-bounce 1.4s infinite ease-in-out both;
        }

        .typing-dot:nth-child(1) {
            animation-delay: -0.32s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: -0.16s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0s;
        }

        @keyframes typing-bounce {

            0%,
            80%,
            100% {
                transform: scale(0);
                opacity: 0.5;
            }

            40% {
                transform: scale(1);
                opacity: 1;
            }
        }

        /* --- Chat Button Launcher --- */
        .chat-button {
            padding: 12px 24px;
            background: #2C50EF;
            position: fixed;
            bottom: 20px;
            right: 20px;
            border-radius: 50px;
            box-shadow: 0 4px 15px rgba(44, 80, 239, 0.4);
            cursor: pointer;
            z-index: 20;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .chat-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(44, 80, 239, 0.5);
        }

        .chat-button span {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-button .status-dot {
            height: 10px;
            width: 10px;
            background: #47cf73;
            border-radius: 50%;
            box-shadow: 0 0 5px #47cf73;
        }

        .chat-button .btn-text {
            font-size: 15px;
            color: white;
            font-weight: 700;
        }

        /* Responsive Mobile */
        @media screen and (max-width: 450px) {
            .chat-box {
                width: 100% !important;
                right: 0;
                bottom: 0;
                margin: 0;
                height: 100%;
                border-radius: 0;
            }

            .chat-box-header {
                border-radius: 0;
                padding-top: 40px;
            }
        }
    </style>
</head>

<body>

    <div class="chat-box">
        <div class="chat-box-header">
            <h3>Trợ lý ảo IT Keycap</h3>
            <div class="close-btn"><i class="fa fa-times"></i></div>
        </div>

        <div id="chatBoxBody" class="chat-box-body">
            <div class="chat-box-body-receive">
                <p>Chào bạn! Mình là ChatBot hỗ trợ của IT Keycap. Bạn cần tìm keycap, switch hay tư vấn build phím cơ cứ hỏi mình nhé!</p>
                <span>Bây giờ</span>
            </div>

            <div id="typingIndicator" class="typing-indicator-container" style="display: none;">
                <div class="typing-bubble">
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                    <span class="typing-dot"></span>
                </div>
            </div>
        </div>

        <div class="chat-box-footer">
            <input id="userInput" placeholder="Nhập câu hỏi của bạn..." type="text" autocomplete="off">
            <button id="sendBtn" class="send">
                <i class="fa fa-paper-plane"></i>
            </button>
        </div>
    </div>

    <div class="chat-button">
        <span>
            <div class="status-dot"></div>
            <div class="btn-text">Chat ngay</div>
        </span>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // --- UI Logic ---
            const chatBox = $('.chat-box');
            const chatButton = $('.chat-button');
            const closeBtn = $('.close-btn');

            chatButton.on('click', function() {
                $(this).addClass('d-none').hide();
                chatBox.addClass('active');
                setTimeout(() => $('#userInput').focus(), 300);
            });

            closeBtn.on('click', function() {
                chatButton.removeClass('d-none').show();
                chatBox.removeClass('active');
            });

            // --- Messaging Logic ---
            const sendBtn = $('#sendBtn');
            const userInput = $('#userInput');
            const chatBoxBody = $('#chatBoxBody');
            const typingIndicator = $('#typingIndicator');

            async function handleSendMessage() {
                const text = userInput.val().trim();
                if (!text) return;

                // 1. Hiển thị tin nhắn User
                appendMessage(text, 'send');

                userInput.val('');
                userInput.prop('disabled', true);
                sendBtn.addClass('disabled');

                // --- ĐOẠN ĐƯỢC SỬA ---
                // Di chuyển bong bóng 3 chấm xuống cuối cùng của chatBoxBody
                chatBoxBody.append(typingIndicator);

                // Sau đó mới cho hiển thị
                typingIndicator.css('display', 'table');
                scrollToBottom();
                // ---------------------

                try {
                    const response = await fetch('/chat/send', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        body: JSON.stringify({
                            message: text
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.choices) {
                        appendMessage(data.choices[0].message.content, 'receive');
                    } else {
                        appendMessage('Hệ thống đang bận, vui lòng thử lại sau.', 'receive');
                    }

                } catch (error) {
                    console.error(error);
                    appendMessage('Lỗi kết nối server.', 'receive');
                } finally {
                    userInput.prop('disabled', false);
                    sendBtn.removeClass('disabled');

                    // Ẩn hiệu ứng đi
                    typingIndicator.hide();

                    userInput.focus();
                    scrollToBottom();
                }
            }

            sendBtn.on('click', function(e) {
                e.preventDefault();
                handleSendMessage();
            });

            userInput.on('keypress', function(e) {
                if (e.which === 13) {
                    handleSendMessage();
                }
            });

            function appendMessage(text, type) {
                const className = type === 'send' ? 'chat-box-body-send' : 'chat-box-body-receive';
                const time = new Date().toLocaleTimeString([], {
                    hour: '2-digit',
                    minute: '2-digit'
                });
                const formattedText = text.replace(/\n/g, '<br>');

                const html = `
                    <div class="${className}">
                        <p>${formattedText}</p>
                        <span>${time}</span>
                    </div>
                `;

                chatBoxBody.append(html);
                scrollToBottom();
            }

            function scrollToBottom() {
                chatBoxBody.scrollTop(chatBoxBody[0].scrollHeight);
            }
        });
    </script>
</body>

</html>