<?php

namespace App\Models\Traits;

use App\Models\Scopes\TenantScope;
use App\Models\Tienda;
use App\Services\TenantManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenant
{
    /**
     * Boot the trait and apply global scope and creating listener
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (empty($model->tienda_id)) {
                $tenantManager = app(TenantManager::class);
                $tiendaId = $tenantManager->getTiendaId();
                if ($tiendaId) {
                    $model->tienda_id = $tiendaId;
                }
            }
        });
    }

    /**
     * Relationship to Tienda
     */
    public function tienda(): BelongsTo
    {
        return $this->belongsTo(Tienda::class);
    }
}
