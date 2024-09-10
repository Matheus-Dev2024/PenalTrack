<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function acessoViaIntranet(Request $request, $token, $usuario)
    {
        return redirect(config('policia.url_front').'/autenticacao');
    }
}
