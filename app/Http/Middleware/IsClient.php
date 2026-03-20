<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->hasRole('gestionnaire')) {
            abort(403, 'Accès réservé aux gestionnaires.');
        }

        return $next($request);
    }
}