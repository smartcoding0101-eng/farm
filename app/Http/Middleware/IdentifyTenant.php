<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\TenantManager;
use Illuminate\Support\Facades\Auth;

class IdentifyTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantManager = app(TenantManager::class);

        // Si el usuario está autenticado en el panel de administración
        if (Auth::check()) {
            $user = Auth::user();
            $tenantManager->initForUser($user);
        } else {
            // Contexto público: inicializar primera tienda activa sin pasar un modelo User vacío
            $tenantManager->initPublic();
        }

        return $next($request);
    }
}
