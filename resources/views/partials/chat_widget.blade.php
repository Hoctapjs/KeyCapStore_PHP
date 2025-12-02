<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    /* ... (Giữ nguyên CSS cũ nhưng tôi paste lại đây cho bạn dễ copy) ... */
    /* Scope một chút để không ảnh hưởng layout chính */
    .chat-widget-wrapper {
        font-family: 'Nunito', sans-serif;
        font-size: 15px;
    }

    .chat-box {
        height: 500px;
        /* Cố định chiều cao tránh bị vỡ layout chính */
        max-height: 80vh;
        width: 380px;
        position: fixed;
        bottom: 20px;
        /* Cao hơn nút bấm một chút */
        right: 20px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.15);
        display: flex;
        flex-direction: column;
        z-index: 9999;
        visibility: hidden;
        opacity: 0;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .chat-box.active {
        visibility: visible;
        opacity: 1;
        transform: translateY(0);
    }

    /* Header */
    .chat-box-header {
        padding: 15px 20px;
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #2C50EF, #4D73FF);
        color: white;
        flex-shrink: 0;
    }

    .chat-box-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: white;
    }

    .chat-box-header .close-btn {
        cursor: pointer;
        font-size: 1.2rem;
    }

    .chat-box-header .close-btn:hover {
        transform: scale(1.1);
    }

    /* Body */
    .chat-box-body {
        flex-grow: 1;
        background: #f4f7f6;
        overflow-y: auto;
        padding: 20px;
        scroll-behavior: smooth;
    }

    /* Messages */
    .chat-box-body-send,
    .chat-box-body-receive {
        width: fit-content;
        max-width: 80%;
        padding: 10px 15px;
        margin-bottom: 10px;
        border-radius: 20px;
        position: relative;
        word-wrap: break-word;
        line-height: 1.5;
    }

    .chat-box-body-send {
        float: right;
        clear: both;
        background: #2C50EF;
        color: white;
        border-bottom-right-radius: 5px;
    }

    .chat-box-body-receive {
        float: left;
        clear: both;
        background: white;
        color: #333;
        border: 1px solid #eee;
        border-bottom-left-radius: 5px;
    }

    .chat-box-body span {
        display: block;
        font-size: 0.7rem;
        margin-top: 5px;
        opacity: 0.7;
    }

    .chat-box-body-send span {
        text-align: right;
        color: #e0e0e0;
    }

    .chat-box-body-receive span {
        text-align: left;
        color: #999;
    }

    /* Footer */
    .chat-box-footer {
        padding: 15px;
        background: #fff;
        border-top: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
    }

    .chat-box-footer input {
        flex-grow: 1;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        border-radius: 30px;
        background: #f8f9fa;
        outline: none;
        margin-right: 10px;
    }

    .chat-box-footer input:focus {
        border-color: #2C50EF;
        background: #fff;
    }

    .chat-box-footer .send {
        background: none;
        border: none;
        color: #2C50EF;
        font-size: 20px;
        cursor: pointer;
    }

    /* Typing Indicator */
    .typing-indicator-container {
        display: table;
        clear: both;
        float: left;
        margin-bottom: 10px;
    }

    .typing-bubble {
        background: #fff;
        padding: 10px 15px;
        border-radius: 20px;
        border-bottom-left-radius: 5px;
        border: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        height: 35px;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        background: #90949c;
        border-radius: 50%;
        animation: typing-bounce 1.4s infinite ease-in-out both;
    }

    .typing-dot:nth-child(1) {
        animation-delay: -0.32s;
    }

    .typing-dot:nth-child(2) {
        animation-delay: -0.16s;
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

    /* Button Launcher */
    .chat-button {
        padding: 12px 24px;
        background: #2C50EF;
        position: fixed;
        bottom: 20px;
        right: 20px;
        border-radius: 50px;
        box-shadow: 0 4px 15px rgba(44, 80, 239, 0.4);
        cursor: pointer;
        z-index: 9998;
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
        gap: 10px;
        color: white;
        font-weight: 700;
    }

    .status-dot {
        height: 10px;
        width: 10px;
        background: #47cf73;
        border-radius: 50%;
        box-shadow: 0 0 5px #47cf73;
    }

    /* Mobile */
    @media screen and (max-width: 450px) {
        .chat-box {
            width: 100% !important;
            right: 0;
            bottom: 0;
            height: 100%;
            border-radius: 0;
            max-height: 100%;
            bottom: 0 !important;
        }

        .chat-box-header {
            padding-top: 40px;
        }

        .chat-button {
            bottom: 10px;
            right: 10px;
            padding: 10px 20px;
        }
    }
</style>

<div class="chat-widget-wrapper">
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
                    <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>
                </div>
            </div>
        </div>

        <div class="chat-box-footer">
            <input id="userInput" placeholder="Nhập câu hỏi của bạn..." type="text" autocomplete="off">
            <button id="sendBtn" class="send"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>

    <div class="chat-button">
        <span>
            <div class="status-dot"></div>Chat ngay
        </span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        const chatBox = $('.chat-box');
        const chatButton = $('.chat-button');
        const closeBtn = $('.close-btn');
        const sendBtn = $('#sendBtn');
        const userInput = $('#userInput');
        const chatBoxBody = $('#chatBoxBody');
        const typingIndicator = $('#typingIndicator');

        // Toggle UI
        chatButton.on('click', function() {
            $(this).addClass('d-none').hide();
            chatBox.addClass('active');
            setTimeout(() => userInput.focus(), 300);
        });
        closeBtn.on('click', function() {
            chatButton.removeClass('d-none').show();
            chatBox.removeClass('active');
        });

        // Send Logic
        async function handleSendMessage() {
            const text = userInput.val().trim();
            if (!text) return;

            appendMessage(text, 'send');
            userInput.val('').prop('disabled', true);
            sendBtn.addClass('disabled');

            chatBoxBody.append(typingIndicator);
            typingIndicator.css('display', 'table').show();
            scrollToBottom();

            try {
                // CSRF Token lấy từ meta tag của layout chính
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                const response = await fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: text
                    })
                });

                const data = await response.json();
                if (response.ok && data.choices) {
                    appendMessage(data.choices[0].message.content, 'receive');
                } else {
                    appendMessage('Xin lỗi, hệ thống đang bận.', 'receive');
                }
            } catch (error) {
                console.error(error);
                appendMessage('Lỗi kết nối.', 'receive');
            } finally {
                userInput.prop('disabled', false).focus();
                sendBtn.removeClass('disabled');
                typingIndicator.hide();
                scrollToBottom();
            }
        }

        sendBtn.on('click', (e) => {
            e.preventDefault();
            handleSendMessage();
        });
        userInput.on('keypress', (e) => {
            if (e.which === 13) handleSendMessage();
        });

        function appendMessage(text, type) {
            const className = type === 'send' ? 'chat-box-body-send' : 'chat-box-body-receive';
            const time = new Date().toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });
            const html = `<div class="${className}"><p>${text.replace(/\n/g, '<br>')}</p><span>${time}</span></div>`;
            chatBoxBody.append(html);
            scrollToBottom();
        }

        function scrollToBottom() {
            chatBoxBody.scrollTop(chatBoxBody[0].scrollHeight);
        }
    });
</script>