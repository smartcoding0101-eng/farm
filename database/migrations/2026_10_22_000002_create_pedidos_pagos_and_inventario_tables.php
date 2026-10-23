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
        // Zonas de Envío y Tarifas
        Schema::create('zonas_envio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100); // Ej: La Paz Centro, El Alto, Zona Sur, Interprovincial
            $table->string('departamento', 50)->default('La Paz');
            $table->decimal('costo_envio', 10, 2)->default(0.00);
            $table->decimal('monto_minimo_envio_gratis', 10, 2)->nullable();
            $table->string('tiempo_estimado', 80)->default('24 a 48 horas');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Cupones de Descuento
        Schema::create('cupones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->enum('tipo', ['porcentaje', 'fijo'])->default('porcentaje');
            $table->decimal('valor', 10, 2);
            $table->decimal('monto_minimo', 10, 2)->default(0.00);
            $table->integer('limite_usos')->nullable();
            $table->integer('usos_actuales')->default(0);
            $table->timestamp('fecha_inicio')->nullable();
            $table->timestamp('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Carrito de compras
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('session_id', 100)->nullable()->index();
            $table->timestamps();
        });

        Schema::create('items_carrito', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrito_id')->constrained('carritos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('variante_id')->nullable()->constrained('variantes_producto')->onDelete('set null');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 2);
            $table->timestamps();
        });

        // Pedidos
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('numero_pedido', 50)->unique(); // Código visible único ej: PED-2026-0001
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Datos del cliente
            $table->string('cliente_nombre', 150);
            $table->string('cliente_email', 150)->nullable();
            $table->string('cliente_telefono', 30);
            
            // Datos de Entrega
            $table->enum('modalidad_entrega', ['domicilio', 'retiro_tienda'])->default('domicilio');
            $table->foreignId('zona_envio_id')->nullable()->constrained('zonas_envio')->onDelete('set null');
            $table->string('departamento', 50)->nullable();
            $table->string('ciudad', 100)->nullable();
            $table->text('direccion_envio')->nullable();
            $table->text('referencia_envio')->nullable();
            
            // Importes Calculados (en BOB)
            $table->decimal('subtotal', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0.00);
            $table->decimal('costo_envio', 12, 2)->default(0.00);
            $table->decimal('total', 12, 2);
            $table->string('cupon_aplicado', 50)->nullable();
            
            // Estados independientes
            $table->enum('estado_pedido', [
                'pendiente',
                'confirmado',
                'en_preparacion',
                'listo_despacho',
                'en_camino',
                'entregado',
                'cancelado'
            ])->default('pendiente');
            
            $table->enum('metodo_pago', [
                'qr_simple',
                'transferencia',
                'tarjeta',
                'efectivo_contraentrega',
                'pasarela_online'
            ])->default('qr_simple');
            
            $table->enum('estado_pago', [
                'pendiente',
                'en_verificacion',
                'pagado',
                'fallido',
                'reembolsado'
            ])->default('pendiente');
            
            $table->text('notas_cliente')->nullable();
            $table->text('notas_administrador')->nullable();
            $table->timestamps();
        });

        // Detalles del Pedido
        Schema::create('detalles_pedido', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('restrict');
            $table->foreignId('variante_id')->nullable()->constrained('variantes_producto')->onDelete('set null');
            $table->string('producto_nombre', 200);
            $table->string('sku', 60);
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('costo_adquisicion_unitario', 12, 2)->default(0.00);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        // Transacciones y Pagos (Especialmente QR y Transferencia)
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->constrained('pedidos')->onDelete('cascade');
            $table->string('referencia_pago', 80)->unique();
            $table->enum('metodo', ['qr', 'transferencia', 'tarjeta', 'efectivo', 'pasarela'])->default('qr');
            $table->decimal('monto', 12, 2);
            $table->string('comprobante_archivo')->nullable(); // Imagen o PDF del comprobante
            $table->text('datos_transaccion')->nullable(); // JSON con detalles técnicos o webhook payload
            $table->enum('estado', ['pendiente', 'en_revision', 'aprobado', 'rechazado'])->default('pendiente');
            $table->foreignId('verificado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('fecha_verificacion')->nullable();
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();
        });

        // Movimientos de Inventario (Kardex)
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('variante_id')->nullable()->constrained('variantes_producto')->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos')->onDelete('set null');
            $table->enum('tipo', ['entrada', 'salida', 'ajuste', 'devolucion'])->default('entrada');
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_posterior');
            $table->text('motivo');
            $table->timestamps();
        });

        // Banners y promociones administrables
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 150);
            $table->string('subtitulo', 250)->nullable();
            $table->string('imagen');
            $table->string('enlace')->nullable();
            $table->string('texto_boton', 60)->default('Ver Catálogo');
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Tabla de Configuraciones Generales de la Tienda
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 80)->unique();
            $table->text('valor')->nullable();
            $table->string('grupo', 50)->default('general'); // general, pagos, envios, contacto, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('movimientos_inventario');
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('detalles_pedido');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('items_carrito');
        Schema::dropIfExists('carritos');
        Schema::dropIfExists('cupones');
        Schema::dropIfExists('zonas_envio');
    }
};
