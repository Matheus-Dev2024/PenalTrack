<?php

namespace App\Http\Middleware;

use App\Models\Facade\UsuarioLocalDB;
use App\Models\Regras\UsuarioLocalRegras;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PoliciaCivil\Seguranca\Models\Facade\UsuarioDB;
use Symfony\Component\HttpFoundation\Response;

class AcessoUnico
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            $usuarioId = $request->usuario;
            $token = $request->token;

            $usuario = UsuarioLocalDB::getUsuario($usuarioId);//http://localhost:8004/acesso/usuario/token/ILrWs3Phb6lh7BXjt0CMGfdNIy4OeOWHZcxHuNRJ0lvom9zEjKaaYUcxa1Ie/5082

            //Verifica se o token está válido http://localhost:8004/acesso/usuario/token/ILrWs3Phb6lh7BXjt0CMGfdNIy4OeOWHZcxHuNRJ0lvom9zEjKaaYUcxa1Ie/587
            $accessToken = UsuarioLocalDB::getToken($token, $usuarioId);

            $rotaLogin = config('policia.url_front').'/autenticacao';
            try {

                if(empty($usuario)) {
                    return response()->json(['acesso_unico' => true, 'message' => "Seu usuário não foi encontrado ou você não está logado na Intranet."], 403);
                }

                if(empty($accessToken)) {
                    abort(403, 'Token inválido');
                }

//                $dtHoraCorrente  = new \DateTime(date('Y-m-d H:i'));
//                $dtHoraExpiracao = new \DateTime(date('Y-m-d H:i'));
//                $dtHoraExpiracao->add(new \DateInterval('PT15S')); //expira em 15 segundos


                //Verifica se o token está expirado
                 $dtHoraCorrente  = new \DateTime(date('Y-m-d H:i'));
                 $dtHoraExpiracao = new \DateTime($accessToken->created_at);
                 $dtHoraExpiracao->add(new \DateInterval('PT15S')); //expira em 15 segundos

                if($dtHoraCorrente > $dtHoraExpiracao) {
                    abort(403, 'Token expirado');
                }
                //Loga o usuário automaticamente
                Auth::login($usuario);

                //Verifica a permissão no sistema
                $permissao = UsuarioLocalDB::isPermissaoSistema($usuario->id);

                if($permissao) {
                    //abort(403, 'Usuário sem permissão');

                    //grava o menus de acesso
                    UsuarioDB::registrarLogin($usuario, $request->ip(), $request->userAgent());

                    //Renovar o acesso do sistema
                    UsuarioDB::renovarAcesso($usuario);
                }

                //Remove o acesso via token
                UsuarioLocalRegras::removerTokenUsuario($accessToken->id);

                return $next($request);

            } catch (\Exception $ex) {

                //LIMPAR O COOKIE AQUI

                return redirect($rotaLogin."?".$ex->getMessage());
            }

        } else {

            //Só cai nesta condição, se o usuário já estiver autenticado no eprobatorio, e clicou novamente no link do eprobatorio pela Intranet
            if(isset($request->token) && isset($request->usuario)) {
                $usuarioId = $request->usuario;
                $token = $request->token;

                //Verifica se o token está válido
                $accessToken = UsuarioLocalDB::getToken($token, $usuarioId);

                if($accessToken) {
                    //Remove o acesso via token
                    UsuarioLocalRegras::removerTokenUsuario($accessToken->id);
                }
            }

            return $next($request);
        }
    }
}
