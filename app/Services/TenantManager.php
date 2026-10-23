<?php

namespace App\Services;

use App\Models\Tienda;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class TenantManager
{
    protected ?Tienda $tienda = null;

    /**
     * Obtiene la tienda activa en el contexto actual
     */
    public function getTienda(): ?Tienda
    {
        return $this->tienda;
    }

    /**
     * Obtiene el ID de la tienda activa
     */
    public function getTiendaId(): ?int
    {
        return $this->tienda ? $this->tienda->id : null;
    }

    /**
     * Establece la tienda activa
     */
    public function setTienda(?Tienda $tienda): void
    {
        $this->tienda = $tienda;
        if ($tienda) {
            Session::put('tienda_activa_id', $tienda->id);
            Session::put('tienda_activa_nombre', $tienda->nombre);
        }
    }

    /**
     * Valida si un usuario tiene autorización de gestión en una tienda
     */
    public function userHasAccessToTienda(User $user, int $tiendaId): bool
    {
        return $user->tiendas()->where('tiendas.id', $tiendaId)->exists();
    }

    /**
     * Inicializa la tienda para un usuario logueado en el panel administrativo
     */
    public function initForUser(User $user): ?Tienda
    {
        $sessionTiendaId = Session::get('tienda_activa_id');

        if ($sessionTiendaId && $this->userHasAccessToTienda($user, $sessionTiendaId)) {
            $tienda = Tienda::find($sessionTiendaId);
            if ($tienda && $tienda->activo) {
                $this->setTienda($tienda);
                return $tienda;
            }
        }

        // Si no hay en sesión o no tiene acceso, usar su tienda predeterminada
        $tiendaDefault = $user->tiendaPredeterminada();
        if ($tiendaDefault) {
            $this->setTienda($tiendaDefault);
            return $tiendaDefault;
        }

        // Fallback a primera tienda activa del sistema
        $primeraTienda = Tienda::where('activo', true)->first();
        if ($primeraTienda) {
            $this->setTienda($primeraTienda);
            return $primeraTienda;
        }

        return null;
    }

    /**
     * Inicializa el contexto público para visitantes
     */
    public function initPublic(): ?Tienda
    {
        $tienda = Tienda::where('activo', true)->first();
        if ($tienda) {
            $this->setTienda($tienda);
            return $tienda;
        }

        return null;
    }
}
