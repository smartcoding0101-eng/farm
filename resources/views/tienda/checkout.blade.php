@extends('layouts.tienda')

@section('titulo', 'Finalizar Compra - Checkout Seguro')

@section('contenido')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">

    <h1 style="font-size: 2rem; font-weight: 800; margin: 0 0 1.5rem 0;">Checkout & Finalización de Compra</h1>

    <form action="{{ route('checkout.procesar') }}" method="POST">
        @csrf
        <div style="display: grid; grid-template-columns: 1fr 380px; gap: 2rem; align-items: start;">
            
            <!-- DATOS DEL CLIENTE, ENTREGA Y PAGO -->
            <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                
                <!-- 1. Datos de Contacto -->
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="background: #EFF6FF; color: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">1</span>
                        Datos del Cliente
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Nombre Completo *</label>
                            <input type="text" name="nombre" value="{{ old('nombre', auth()->user()?->name) }}" required placeholder="Ej: Carlos Rodrigo Mendoza" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.5rem; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Teléfono / WhatsApp *</label>
                            <input type="text" name="telefono" value="{{ old('telefono', auth()->user()?->telefono) }}" required placeholder="Ej: 70012345" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.5rem; box-sizing: border-box;">
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Correo Electrónico (Opcional)</label>
                            <input type="email" name="email" value="{{ old('email', auth()->user()?->email) }}" placeholder="correo@ejemplo.com" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.5rem; box-sizing: border-box;">
                        </div>
                    </div>
                </div>

                <!-- 2. Modalidad de Entrega -->
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="background: #EFF6FF; color: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">2</span>
                        Modalidad de Entrega
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.25rem;">
                        <label style="border: 2px solid var(--primary); border-radius: 0.5rem; padding: 1rem; cursor: pointer; display: flex; align-items: flex-start; gap: 0.75rem; background: #EFF6FF;">
                            <input type="radio" name="modalidad" value="domicilio" checked style="margin-top: 0.2rem;">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Envío a Domicilio</strong>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Entrega en tu casa u oficina</span>
                            </div>
                        </label>

                        <label style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; cursor: pointer; display: flex; align-items: flex-start; gap: 0.75rem;">
                            <input type="radio" name="modalidad" value="retiro_tienda" style="margin-top: 0.2rem;">
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Retiro en Tienda</strong>
                                <span style="font-size: 0.8rem; color: var(--text-muted);">Gratis en nuestra sucursal</span>
                            </div>
                        </label>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Zona de Cobertura *</label>
                            <select name="zona_envio_id" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.5rem; background: #FFF; box-sizing: border-box;">
                                @foreach($zonas as $z)
                                    <option value="{{ $z->id }}">
                                        {{ $z->nombre }} - Tarifa: Bs. {{ number_format($z->costo_envio, 2) }} ({{ $z->tiempo_estimado }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Dirección Exacta (Calle, Nro, Edificio) *</label>
                            <input type="text" name="direccion" placeholder="Ej: Calle 21 de Calacoto #450, Edif. Los Pinos Piso 3" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.5rem; box-sizing: border-box;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.35rem;">Referencias adicionales</label>
                            <input type="text" name="referencia" placeholder="Ej: Frente al supermercado Ketal, portón negro" style="width: 100%; padding: 0.7rem; border: 1px solid var(--border-color); border-radius: 0.5rem; box-sizing: border-box;">
                        </div>
                    </div>
                </div>

                <!-- 3. Método de Pago -->
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                        <span style="background: #EFF6FF; color: var(--primary); width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.85rem;">3</span>
                        Método de Pago
                    </h3>

                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <label style="border: 2px solid var(--primary); border-radius: 0.5rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; gap: 1rem; background: #EFF6FF;">
                            <input type="radio" name="metodo_pago" value="qr_simple" checked>
                            <div style="font-size: 1.5rem; color: var(--primary);"><i class="fa-solid fa-qrcode"></i></div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Pago con Código QR Simple (Recomendado)</strong>
                                <span style="font-size: 0.82rem; color: var(--text-muted);">Paga al instante escaneando desde BNB, BCP, BancoSol, Mercantil, Fassil o cualquier app móvil bancaria.</span>
                            </div>
                        </label>

                        <label style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; gap: 1rem;">
                            <input type="radio" name="metodo_pago" value="transferencia">
                            <div style="font-size: 1.5rem; color: #475569;"><i class="fa-solid fa-building-columns"></i></div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Transferencia Bancaria Directa</strong>
                                <span style="font-size: 0.82rem; color: var(--text-muted);">Recibirás nuestras cuentas BNB / BCP para transferir y subir tu comprobante.</span>
                            </div>
                        </label>

                        <label style="border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1rem; cursor: pointer; display: flex; align-items: center; gap: 1rem;">
                            <input type="radio" name="metodo_pago" value="efectivo_contraentrega">
                            <div style="font-size: 1.5rem; color: #059669;"><i class="fa-solid fa-money-bill-wave"></i></div>
                            <div>
                                <strong style="display: block; font-size: 0.95rem;">Pago en Efectivo contra Entrega</strong>
                                <span style="font-size: 0.82rem; color: var(--text-muted);">Paga al recibir el producto en mano (Disponible en La Paz y El Alto).</span>
                            </div>
                        </label>
                    </div>
                </div>

            </div>

            <!-- RESUMEN LATERAL DE ORDEN -->
            <div class="card" style="padding: 1.5rem; position: sticky; top: 90px;">
                <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1.25rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    Resumen del Pedido
                </h3>

                <div style="max-height: 250px; overflow-y: auto; margin-bottom: 1.25rem;">
                    @foreach($carrito as $item)
                        <div style="display: flex; justify-content: space-between; font-size: 0.88rem; margin-bottom: 0.6rem;">
                            <span style="color: var(--text-main); font-weight: 500;">{{ $item['cantidad'] }}x {{ Str::limit($item['nombre'], 24) }}</span>
                            <span style="font-weight: 700;">Bs. {{ number_format($item['precio'] * $item['cantidad'], 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; font-size: 0.9rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-muted);">Subtotal</span>
                        <span style="font-weight: 600;">Bs. {{ number_format($totales['subtotal'], 2) }}</span>
                    </div>

                    @if($totales['descuento'] > 0)
                        <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; color: #059669;">
                            <span>Descuento</span>
                            <span style="font-weight: 700;">- Bs. {{ number_format($totales['descuento'], 2) }}</span>
                        </div>
                    @endif

                    <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                        <span style="color: var(--text-muted);">Envío estimado</span>
                        <span style="font-weight: 600;">Bs. {{ number_format($totales['costo_envio'], 2) }}</span>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: baseline; border-top: 2px solid var(--border-color); padding-top: 0.85rem; margin-top: 0.75rem; margin-bottom: 1.5rem;">
                        <span style="font-size: 1.05rem; font-weight: 800;">Total a Pagar</span>
                        <span style="font-size: 1.6rem; font-weight: 900; color: var(--primary);">Bs. {{ number_format($totales['total'], 2) }}</span>
                    </div>

                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 0.9rem; font-size: 1rem;">
                        <i class="fa-solid fa-check"></i> Confirmar Pedido
                    </button>
                    
                    <p style="text-align: center; font-size: 0.78rem; color: var(--text-muted); margin-top: 0.75rem;">
                        <i class="fa-solid fa-lock"></i> Compra 100% segura con garantía oficial
                    </p>
                </div>
            </div>

        </div>
    </form>

</div>
@endsection
