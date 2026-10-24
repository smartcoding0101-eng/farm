<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Rutas Públicas de la Tienda
|--------------------------------------------------------------------------
*/
Route::get('/', [TiendaController::class, 'inicio'])->name('tienda.inicio');
Route::get('/catalogo', [TiendaController::class, 'catalogo'])->name('tienda.catalogo');
Route::get('/catalogo/pdf', [App\Http\Controllers\CatalogoPdfController::class, 'descargar'])->name('tienda.catalogo.pdf');
Route::get('/producto/{slug}', [TiendaController::class, 'producto'])->name('tienda.producto');
Route::get('/rastreo', [App\Http\Controllers\AuthClienteController::class, 'rastreo'])->name('tienda.rastreo');

// Autenticación de Clientes y Perfil
Route::get('/cuenta/login', [App\Http\Controllers\AuthClienteController::class, 'showLogin'])->name('cliente.login');
Route::post('/cuenta/login', [App\Http\Controllers\AuthClienteController::class, 'login'])->name('cliente.login.post');
Route::post('/cuenta/registro', [App\Http\Controllers\AuthClienteController::class, 'register'])->name('cliente.register.post');
Route::post('/cuenta/logout', [App\Http\Controllers\AuthClienteController::class, 'logout'])->name('cliente.logout');
Route::get('/cuenta/perfil', [App\Http\Controllers\AuthClienteController::class, 'perfil'])->middleware('auth')->name('cliente.perfil');

// Carrito de compras
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::post('/carrito/actualizar', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::get('/carrito/eliminar/{clave}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::post('/carrito/cupon', [CarritoController::class, 'aplicarCupon'])->name('carrito.cupon');
Route::get('/carrito/cupon/remover', [CarritoController::class, 'removerCupon'])->name('carrito.cupon.remover');

// Checkout y Pagos QR
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/procesar', [CheckoutController::class, 'procesar'])->name('checkout.procesar');
Route::get('/pedido/{numero}/confirmacion', [CheckoutController::class, 'confirmacion'])->name('checkout.confirmacion');
Route::post('/pedido/{numero}/comprobante', [CheckoutController::class, 'subirComprobante'])->name('checkout.comprobante');

/*
|--------------------------------------------------------------------------
| Panel Administrativo de la Tienda (Requiere Autenticación)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Gestión de Productos, Carga Masiva y Stock Kardex
    Route::get('/productos', [App\Http\Controllers\Admin\AdminProductoController::class, 'index'])->name('productos.index');
    Route::get('/productos/plantilla', [App\Http\Controllers\Admin\AdminProductoController::class, 'descargarPlantilla'])->name('productos.plantilla');
    Route::post('/productos/importar', [App\Http\Controllers\Admin\AdminProductoController::class, 'importarExcel'])->name('productos.importar');
    Route::get('/productos/crear', [App\Http\Controllers\Admin\AdminProductoController::class, 'create'])->name('productos.create');
    Route::post('/productos', [App\Http\Controllers\Admin\AdminProductoController::class, 'store'])->name('productos.store');
    Route::get('/productos/{id}/editar', [App\Http\Controllers\Admin\AdminProductoController::class, 'edit'])->name('productos.edit');
    Route::post('/productos/{id}', [App\Http\Controllers\Admin\AdminProductoController::class, 'update'])->name('productos.update');
    Route::post('/productos/{id}/stock', [App\Http\Controllers\Admin\AdminProductoController::class, 'ajustarStock'])->name('productos.stock');

    // Gestión de Categorías Multirrubro
    Route::get('/categorias', [App\Http\Controllers\Admin\AdminCategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [App\Http\Controllers\Admin\AdminCategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [App\Http\Controllers\Admin\AdminCategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}/editar', [App\Http\Controllers\Admin\AdminCategoriaController::class, 'edit'])->name('categorias.edit');
    Route::post('/categorias/{id}', [App\Http\Controllers\Admin\AdminCategoriaController::class, 'update'])->name('categorias.update');

    // Gestión de Zonas de Envío y Tarifas
    Route::get('/zonas', [App\Http\Controllers\Admin\AdminZonaEnvioController::class, 'index'])->name('zonas.index');
    Route::get('/zonas/crear', [App\Http\Controllers\Admin\AdminZonaEnvioController::class, 'create'])->name('zonas.create');
    Route::post('/zonas', [App\Http\Controllers\Admin\AdminZonaEnvioController::class, 'store'])->name('zonas.store');
    Route::get('/zonas/{id}/editar', [App\Http\Controllers\Admin\AdminZonaEnvioController::class, 'edit'])->name('zonas.edit');
    Route::post('/zonas/{id}', [App\Http\Controllers\Admin\AdminZonaEnvioController::class, 'update'])->name('zonas.update');

    // Gestión Multi-Tienda
    Route::post('/tiendas/cambiar', [App\Http\Controllers\Admin\AdminTiendaController::class, 'switchTienda'])->name('tiendas.switch');
    Route::get('/tiendas', [App\Http\Controllers\Admin\AdminTiendaController::class, 'index'])->name('tiendas.index');
    Route::get('/tiendas/crear', [App\Http\Controllers\Admin\AdminTiendaController::class, 'create'])->name('tiendas.create');
    Route::post('/tiendas', [App\Http\Controllers\Admin\AdminTiendaController::class, 'store'])->name('tiendas.store');

    Route::get('/reportes/movimientos', [AdminDashboardController::class, 'reporteMovimientos'])->name('reportes.movimientos');
    Route::get('/reportes/ventas', [AdminDashboardController::class, 'reporteVentas'])->name('reportes.ventas');
    Route::get('/pagos', [AdminDashboardController::class, 'pagos'])->name('pagos.index');
    Route::post('/pagos/{id}/estado', [AdminDashboardController::class, 'cambiarEstadoPago'])->name('pagos.estado');
    Route::get('/configuracion', [AdminDashboardController::class, 'configuracion'])->name('configuracion.index');
    Route::post('/configuracion', [AdminDashboardController::class, 'guardarConfiguracion'])->name('configuracion.guardar');
});
