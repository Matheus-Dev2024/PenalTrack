<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidarIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lista de IPs permitidos
        $ipsAutorizados = [
            '172.18.0.4', //ip intranet-api teste
            '172.18.0.1',// ip local de teste
            '127.0.0.1', // Localhost
        ];

        if (!in_array($request->ip(), $ipsAutorizados)) {
            return response()->json(['message' => 'Acesso negado.'], 403);
        }

        return $next($request);
    }
    

}
