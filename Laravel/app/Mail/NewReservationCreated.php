<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewReservationCreated extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;

    public string $start_date;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $start_date)
    {
        $this->code       = $code;
        $this->start_date = $start_date;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Reservation Created',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.new-reservation-created',
            with: [
                'code'       => $this->code,
                'start_date' => $this->start_date,
            ],
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
