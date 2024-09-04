<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventDirectoryAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->path(); //pegar o diretorio sem o dominio
        if (strpos($path, '...') !== false || strpos($path, 'storage') !== false) {
            return response()->view('errors.404', [], 404);
        }
        return $next($request);
    }

}