<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $assunto;
    public $mensagem;

    public function __construct($assunto, $mensagem)
    {
        $this->assunto = $assunto;
        $this->mensagem = $mensagem;
    }
 
    public function build()
    {
        return $this->subject($this->assunto)
                    ->view('admin.emails.index')
                    ->with([
                        'mensagem' => $this->mensagem,
                        'caminhoImagem' => public_path('logo_dirppg_preto.png'),
                    ]);
                    
    }
}
