<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Crear tabla de tiendas
        Schema::create('tiendas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('slug', 180)->unique();
            $table->string('dominio', 150)->nullable()->unique();
            $table->string('logo')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('moneda', 10)->default('BOB');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // 2. Crear tabla intermedia de usuarios con tiendas (N a M)
        Schema::create('tienda_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tienda_id')->constrained('tiendas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('rol', 50)->default('dueno'); // dueno, administrador, vendedor
            $table->boolean('es_predeterminada')->default(false);
            $table->timestamps();

            $table->unique(['tienda_id', 'user_id']);
        });

        // 3. Crear tienda predeterminada inicial para salvaguardar datos existentes
        $tiendaDefaultId = DB::table('tiendas')->insertGetId([
            'nombre' => 'Tienda Principal',
            'slug' => 'tienda-principal',
            'moneda' => 'BOB',
            'activo' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asociar usuarios existentes con rol admin a esta tienda por defecto
        $adminUsers = DB::table('users')->pluck('id');
        foreach ($adminUsers as $userId) {
            DB::table('tienda_user')->insert([
                'tienda_id' => $tiendaDefaultId,
                'user_id' => $userId,
                'rol' => 'dueno',
                'es_predeterminada' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 4. Agregar columna tienda_id en tablas dependientes

        // Categorías
        Schema::table('categorias', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Marcas
        Schema::table('marcas', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Productos
        Schema::table('productos', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Zonas de envío
        Schema::table('zonas_envio', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Cupones
        Schema::table('cupones', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Carritos
        Schema::table('carritos', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Pedidos
        Schema::table('pedidos', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Movimientos inventario
        Schema::table('movimientos_inventario', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Banners
        Schema::table('banners', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });

        // Configuraciones
        Schema::table('configuraciones', function (Blueprint $table) use ($tiendaDefaultId) {
            $table->foreignId('tienda_id')->default($tiendaDefaultId)->after('id')->constrained('tiendas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'configuraciones',
            'banners',
            'movimientos_inventario',
            'pedidos',
            'carritos',
            'cupones',
            'zonas_envio',
            'productos',
            'marcas',
            'categorias'
        ];

        foreach ($tables as $tbl) {
            if (Schema::hasColumn($tbl, 'tienda_id')) {
                Schema::table($tbl, function (Blueprint $table) {
                    $table->dropForeign(['tienda_id']);
                    $table->dropColumn('tienda_id');
                });
            }
        }

        Schema::dropIfExists('tienda_user');
        Schema::dropIfExists('tiendas');
    }
};
