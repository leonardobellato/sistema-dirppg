<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Email;
use App\Models\Inscricao;
use App\Models\Entrevista;
use Carbon\Carbon;

class EmailController extends Controller
{
    public function redefinicaoSenha($emailUsuario, $senha)
    {
        Mail::to($emailUsuario)->send(new Email(
            "DIRPPG-PG: Redefinição de senha",
            "<p>Sua senha foi redefinida para: <b>$senha</b></p>"
            . "<p>Por favor, altere sua senha no menu do usuário após o login.</p>"
        ));
        return back()->with('success', 'E-mail de redefinição de senha enviado com sucesso!');
    }   

    public function comunicacaoGeral(Request $request, $idEdital)
    {
        $mensagem = Str::markdown($request->input('mensagem'));
        $emails = Inscricao::where('id_edital', $idEdital)
            ->with('candidato:id_usuario,nome,email')
            ->get()
            ->pluck('candidato.email')
            ->unique()
            ->values()
            ->toArray();
        
        Mail::to($emails)->send(new Email("DIRPPG-PG: Comunicação Geral", $mensagem));

        return back()->with('success', 'Mensagem enviada com sucesso!');
    }

    public function agendamentoEntrevista($idEntrevista)
    {
        $entrevista = Entrevista::with('candidato:id_usuario,email')->findOrFail($idEntrevista);

        $dataHora = $entrevista->data_hora ? Carbon::parse($entrevista->data_hora)->format('d/m/Y H:i') : '—';

        $mensagem = Str::markdown(
            "<p>Nova entrevista agendada para <b>" . $dataHora . "</b> </p>"
            . "<p>Por favor, verifique os detalhes no sistema.</p>"
        );

        Mail::to($entrevista->candidato->email)->send(new Email("DIRPPG-PG: Nova entrevista", $mensagem));

        return back()->with('success', 'Aviso enviado com sucesso!');
    }

    public function atualizacaoEntrevista($emailUsuario)
    {
        $mensagem = Str::markdown(
            "<p>Sua entrevista foi atualizada.</p>"
            . "<p>Por favor, verifique os detalhes no sistema.</p>"
        );

        Mail::to($emailUsuario)->send(new Email("DIRPPG-PG: Atualização de entrevista", $mensagem));

        return back()->with('success', 'Aviso enviado com sucesso!');
    }
}