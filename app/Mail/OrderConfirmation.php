<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected string $title;

    /**
     * Create a new message instance.
     */
    public function __construct(protected readonly Order $order)
    {
        $this->title = 'Orderbekräftelse från '.$this->order->shop->name.' - Order nr.'.$this->order->order_number;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.order-confirmation',
            with: [
                'title' => $this->title,
                'order' => $this->order,
                'billingAddress' => $this->order->customer->billingAddress->inline(true),
                'shippingAddress' => $this->order->customer->shippingAddress->inline(true),
                'company' => $this->order->shop->company,
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
