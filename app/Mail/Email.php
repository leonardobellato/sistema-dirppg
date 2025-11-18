<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Email extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($mensagem)
    {
        $this->mensagem = $mensagem;
    }

 
    public function build()
    {
        return $this->subject('Aviso – DIRPPG UTFPR-PG')
                    ->view('admin.emails.index')
                    ->with([
                        'mensagem' => $this->mensagem,
                        'caminhoImagem' => public_path('logo_dirppg_preto.png'),
                    ]);
                    
    }
}
