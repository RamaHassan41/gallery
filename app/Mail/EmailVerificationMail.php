<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class EmailVerificationMail extends Mailable
{
    use Queueable, SerializesModels;

    //public $data;

    public $name,$verificationCode;

    /**
     * Create a new message instance.
     */
    // public function __construct($data)
    // {
    //     $this->data = $data;
    // }

    public function __construct($name,$verificationCode)
    {
        $this->name=$name;
        $this->verificationCode = $verificationCode;
    }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Test Mail',
    //         from: new Address('test@mail.dev', 'Test Mail'),
    //     );
    // }

    // public function envelope(): Envelope
    // {
    //     return new Envelope(
    //         subject: 'Email verification',
    //         //from: new Address('test@mail.dev', 'Test Mail'),
    //         view: 'emailVerification',
    //         with:(['verificationCode' => $this->verificationCode]),
    //     );
    // }
    // public function build()
    // {
    //     return $this->subject('Email Verification Code')
    //                 ->view('emailVerification')
    //                 ->with([
    //                     'verificationCode' => $this->verificationCode
    //                 ]);
    // }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emailVerification',
            with:(['name' => $this->name, 'verificationCode' => $this->verificationCode]),
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
