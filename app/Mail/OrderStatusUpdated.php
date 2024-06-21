<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    protected string $title;

    /**
     * Create a new message instance.
     */
    public function __construct(protected readonly Order $order, protected readonly ?string $customContent = null)
    {
        $this->title = 'Din beställning '.$order->order_number.' har uppdaterats';
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
            view: 'mail.order-status-updated',
            with: [
                'title' => $this->title,
                'order' => $this->order,
                'customContent' => $this->customContent,
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
