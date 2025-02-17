<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Models\Produto;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProdutoDisponivel extends Mailable
{
    use Queueable, SerializesModels;
    public $produto;
    public function __construct(Produto $produto)
    {
        $this->produto = $produto;
    }

 public function build()
 {
    return $this->subject('Produto Disponivel:' . $this->produto->nome)
                ->markdown('emails.produto_disponivel')
                ->with([
                    'produtoNome' => $this->produto->nome,
                    'produtoLink' => route('produto/', $this->produto->slug),
                ]);
 }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Produto Disponivel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.produto_disponivel',
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
