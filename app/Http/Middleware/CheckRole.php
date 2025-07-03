<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($role === 'bibliotecario' && $user->role !== 'bibliotecario') {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'No tienes permisos para acceder a esta sección.']);
        }

        if ($role === 'usuario' && $user->role !== 'usuario') {
            return redirect()->route('dashboard')
                ->withErrors(['error' => 'No tienes permisos para acceder a esta sección.']);
        }

        return $next($request);
    }
} 