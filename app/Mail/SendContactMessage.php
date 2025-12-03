<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address; // Nhớ import cái này để dùng Address
use Illuminate\Queue\SerializesModels;

class SendContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    // 1. Khai báo biến public để truyền dữ liệu sang View tự động
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope. (Cấu hình Tiêu đề, Người gửi)
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Thiết lập người gửi động từ dữ liệu form
            from: new Address($this->data['email'], $this->data['name']),

            // Thiết lập tiêu đề động
            subject: 'Liên Hệ Mới: ' . ($this->data['subject'] ?? 'Không tiêu đề'),
        );
    }

    /**
     * Get the message content definition. (Cấu hình View)
     */
    public function content(): Content
    {
        return new Content(
            // 2. Trỏ đúng đến file view bạn đã tạo
            view: 'emails.contact-message',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
