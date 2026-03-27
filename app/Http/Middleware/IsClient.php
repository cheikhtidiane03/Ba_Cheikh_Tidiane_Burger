<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Gestionnaire a accès à tout
        if (auth()->user()->hasRole('gestionnaire')) {
            return $next($request);
        }

        // Client a accès à ses propres pages
        if (auth()->user()->hasRole('client')) {
            return $next($request);
        }

        abort(403, 'Accès non autorisé.');
    }
}