<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ampliación de tabla de usuarios para roles, teléfono y estado
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('cliente')->after('email'); // admin, operador, cliente
            $table->string('telefono', 20)->nullable()->after('role');
            $table->boolean('activo')->default(true)->after('telefono');
        });

        // Tabla de Direcciones de clientes
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo')->default('Principal'); // Casa, Oficina, etc.
            $table->string('departamento', 50)->default('La Paz'); // La Paz, Cochabamba, Santa Cruz, etc.
            $table->string('ciudad', 100);
            $table->string('zona', 100);
            $table->text('direccion');
            $table->text('referencia')->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->boolean('es_predeterminada')->default(false);
            $table->timestamps();
        });

        // Tabla de Categorías Multirrubro
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_padre_id')->nullable()->constrained('categorias')->onDelete('set null');
            $table->string('nombre', 150);
            $table->string('slug', 180)->unique();
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->string('icono', 50)->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->boolean('destacada')->default(false);
            $table->string('meta_titulo')->nullable();
            $table->text('meta_descripcion')->nullable();
            $table->timestamps();
        });

        // Tabla de Marcas
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('slug', 150)->unique();
            $table->string('logo')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de Productos
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $table->foreignId('marca_id')->nullable()->constrained('marcas')->onDelete('set null');
            $table->string('nombre', 200);
            $table->string('slug', 230)->unique();
            $table->string('sku', 60)->unique();
            $table->string('codigo_barras', 80)->nullable()->index();
            $table->text('descripcion_corta')->nullable();
            $table->longText('descripcion_completa')->nullable();
            $table->decimal('precio_venta', 12, 2);
            $table->decimal('costo_adquisicion', 12, 2)->default(0.00); // confidencial para reportes de margen
            $table->decimal('precio_oferta', 12, 2)->nullable();
            $table->timestamp('oferta_inicio')->nullable();
            $table->timestamp('oferta_fin')->nullable();
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->decimal('peso_kg', 8, 2)->default(0.00);
            $table->enum('tipo', ['fisico', 'digital', 'servicio'])->default('fisico');
            $table->enum('estado', ['borrador', 'publicado', 'archivado'])->default('publicado');
            $table->boolean('destacado')->default(false);
            $table->boolean('nuevo')->default(true);
            $table->string('imagen_principal')->nullable();
            $table->string('meta_titulo')->nullable();
            $table->text('meta_descripcion')->nullable();
            $table->timestamps();
        });

        // Imágenes secundarias de productos
        Schema::create('imagenes_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('ruta');
            $table->string('alt_texto')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // Variantes de Producto (Tallas, Colores, Capacidad, etc.)
        Schema::create('variantes_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('nombre', 120); // ej: Talla L / Color Azul
            $table->string('sku_variante', 60)->unique();
            $table->decimal('precio_adicional', 12, 2)->default(0.00);
            $table->integer('stock')->default(0);
            $table->string('imagen')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variantes_producto');
        Schema::dropIfExists('imagenes_producto');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('direcciones');

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn(['role', 'telefono', 'activo']);
            });
        }
    }
};
