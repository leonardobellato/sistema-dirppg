<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;

class EmailController extends Controller
{
    public function redefinicaoSenha($emailUsuario, $senha)
    {
        Mail::to($emailUsuario)->send(new Email(
            "<p>Sua senha foi redefinida para: <b>$senha</b></p>"
            . "<p>Por favor, altere sua senha no menu do usuário após o login.</p>"
        ));
        return back()->with('success', 'E-mail de redefinição de senha enviado com sucesso!');
    }   
}