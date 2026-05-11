<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AthleteMiddleware
{
    /**
     * Solo usuarios con rol "user" (atleta) acceden a rutinas y progreso.
     * El administrador gestiona el catálogo, no registra entrenamientos en esta app.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'user') {
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Las rutinas y el progreso son solo para cuentas de atleta. Como administrador, usa el panel de administración.');
    }
}
