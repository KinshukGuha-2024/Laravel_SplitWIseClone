<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpEmailTemplate extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $data, $subject;
    public function __construct($data)
    {
        $this->data = $data;
        if($data->page == "register") {
            $this->subject = env('APP_NAME').' – Verify OTP to Complete Sign-Up';
        } elseif($data->page == "forget-password") {
            $this->subject = env('APP_NAME').' – Verify OTP to Reset Your Password';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        
        return new Envelope(
            subject: $this->subject
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'EmailTemplates.OtpTemplate',
            with: [
                "user" => $this->data->user,
                "otp" => $this->data->otp,
                "page" => $this->data->page,
                "minutes" => $this->data->minutes,
                "is_resend_otp" => !empty($this->data->is_resend) ? 1 : 0
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
