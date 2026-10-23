@extends('layouts.tienda')

@section('titulo', 'Pedido ' . $pedido->numero_pedido . ' - Confirmación & Pago QR')

@section('contenido')
<div class="container" style="padding-top: 2rem; padding-bottom: 4rem;">

    <!-- ENCABEZADO DE ÉXITO -->
    <div class="card" style="padding: 2.5rem; text-align: center; margin-bottom: 2rem; background: #FFFFFF; border-top: 4px solid #10B981;">
        <div style="width: 64px; height: 64px; background: #ECFDF5; color: #10B981; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.25rem auto;">
            <i class="fa-solid fa-check"></i>
        </div>
        <h1 style="font-size: 1.85rem; font-weight: 800; margin: 0 0 0.5rem 0;">¡Pedido Generado Exitosamente!</h1>
        <p style="font-size: 1.05rem; color: var(--text-muted); margin: 0 0 1.25rem 0;">
            Número de Pedido: <strong style="color: var(--primary);">{{ $pedido->numero_pedido }}</strong>
        </p>
        <span style="background: #EFF6FF; color: var(--primary); font-weight: 700; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.85rem; text-transform: uppercase;">
            Estado de Pago: {{ ucfirst(str_replace('_', ' ', $pedido->estado_pago)) }}
        </span>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; align-items: start;">
        
        <!-- PASARELA DE PAGO QR Y TRANSFERENCIA -->
        <div class="card" style="padding: 2rem;">
            <h3 style="font-size: 1.25rem; font-weight: 800; margin-top: 0; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fa-solid fa-qrcode" style="color: var(--primary);"></i> Pagar con Código QR Simple
            </h3>
            <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 1.5rem;">
                Escanea el código QR desde tu app bancaria favorita (BNB, BCP, BancoSol, Ganadero, Bisa, etc.) por el monto exacto:
            </p>

            <div style="text-align: center; background: #F8FAFC; border: 2px dashed #CBD5E1; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1.5rem; position: relative;">
                <div style="position: absolute; top: 1rem; right: 1rem; background: #FEF2F2; color: #DC2626; padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <i class="fa-solid fa-clock"></i> Expira en: <span id="timer-qr">09:45</span>
                </div>

                <div style="font-size: 0.95rem; color: var(--text-muted); margin-bottom: 0.3rem;">Total a Pagar</div>
                <div style="font-size: 2.25rem; font-weight: 900; color: #0F172A; margin-bottom: 0.75rem;">
                    Bs. {{ number_format($pedido->total, 2) }}
                </div>

                <!-- Botones de Acción Rápida para la App Bancaria -->
                <div style="display: flex; justify-content: center; gap: 0.5rem; margin-bottom: 1.25rem; flex-wrap: wrap;">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $pedido->total }}'); alert('Monto copiado al portapapeles: Bs. {{ $pedido->total }}');" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.78rem;">
                        <i class="fa-solid fa-copy"></i> Copiar Monto
                    </button>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $pedido->numero_pedido }}'); alert('Referencia copiada: {{ $pedido->numero_pedido }}');" class="btn-outline" style="padding: 0.35rem 0.75rem; font-size: 0.78rem;">
                        <i class="fa-solid fa-copy"></i> Copiar Glosa
                    </button>
                </div>
                
                <!-- QR CODE GENERADO VISUALMENTE -->
                <div style="background: white; padding: 1rem; border-radius: 0.75rem; display: inline-block; box-shadow: 0 4px 12px rgba(0,0,0,0.08); margin-bottom: 1rem;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode('PAGO NOVASTORE | PEDIDO: ' . $pedido->numero_pedido . ' | MONTO: Bs. ' . $pedido->total) }}" alt="Código QR de Pago" style="display: block; width: 220px; height: 220px;">
                </div>

                <div style="font-size: 0.82rem; color: var(--text-muted);">
                    Cuenta destino: <strong>{{ $configs['qr_titular'] ?? 'NOVASTORE S.R.L.' }}</strong><br>
                    Banco: {{ $configs['qr_banco'] ?? 'Banco Nacional de Bolivia / BCP' }}
                </div>
            </div>

            <script>
                // Temporizador de expiración de sesión QR
                let segs = 585;
                const timerElem = document.getElementById('timer-qr');
                if (timerElem) {
                    setInterval(() => {
                        if (segs <= 0) return;
                        segs--;
                        let m = Math.floor(segs / 60);
                        let s = segs % 60;
                        timerElem.innerText = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                    }, 1000);
                }
            </script>

            <!-- FORMULARIO DE SUBIDA DE COMPROBANTE -->
            <div style="background: #FFFFFF; border: 1px solid var(--border-color); border-radius: 0.75rem; padding: 1.25rem;">
                <h4 style="margin: 0 0 0.5rem 0; font-size: 0.95rem; font-weight: 700;">Subir Comprobante de Pago</h4>
                <p style="font-size: 0.82rem; color: var(--text-muted); margin-bottom: 1rem;">
                    Adjunta una captura o PDF de la transferencia para que validemos tu pago de inmediato.
                </p>

                <form action="{{ route('checkout.comprobante', $pedido->numero_pedido) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div style="margin-bottom: 1rem;">
                        <input type="file" name="comprobante" required accept="image/*,.pdf" style="font-size: 0.85rem; width: 100%;">
                    </div>
                    <button type="submit" class="btn-primary" style="width: 100%; justify-content: center;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Enviar Comprobante
                    </button>
                </form>
            </div>

        </div>

        <!-- RESUMEN DE LA ORDEN Y DATOS DE ENTREGA -->
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            
            <div class="card" style="padding: 1.5rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    Detalle del Pedido
                </h3>

                <div style="margin-bottom: 1rem;">
                    @foreach($pedido->detalles as $det)
                        <div style="display: flex; justify-content: space-between; font-size: 0.9rem; padding: 0.5rem 0; border-bottom: 1px solid #F1F5F9;">
                            <div>
                                <strong style="display: block;">{{ $det->producto_nombre }}</strong>
                                <span style="font-size: 0.78rem; color: var(--text-muted);">{{ $det->cantidad }} x Bs. {{ number_format($det->precio_unitario, 2) }}</span>
                            </div>
                            <span style="font-weight: 700;">Bs. {{ number_format($det->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <div style="font-size: 0.88rem; line-height: 1.8;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Subtotal:</span>
                        <span>Bs. {{ number_format($pedido->subtotal, 2) }}</span>
                    </div>
                    @if($pedido->descuento > 0)
                        <div style="display: flex; justify-content: space-between; color: #059669;">
                            <span>Descuento cupón:</span>
                            <span>- Bs. {{ number_format($pedido->descuento, 2) }}</span>
                        </div>
                    @endif
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: var(--text-muted);">Costo de Envío:</span>
                        <span>Bs. {{ number_format($pedido->costo_envio, 2) }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-weight: 800; font-size: 1.1rem; border-top: 2px solid var(--border-color); padding-top: 0.5rem; margin-top: 0.5rem;">
                        <span>Total:</span>
                        <span style="color: var(--primary);">Bs. {{ number_format($pedido->total, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="card" style="padding: 1.5rem;">
                <h3 style="font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem;">
                    Destino y Contacto
                </h3>
                <div style="font-size: 0.88rem; line-height: 1.6; color: #475569;">
                    <p style="margin: 0 0 0.4rem 0;"><strong>Cliente:</strong> {{ $pedido->cliente_nombre }}</p>
                    <p style="margin: 0 0 0.4rem 0;"><strong>Teléfono:</strong> {{ $pedido->cliente_telefono }}</p>
                    <p style="margin: 0 0 0.4rem 0;"><strong>Modalidad:</strong> {{ $pedido->modalidad_entrega === 'domicilio' ? 'Entrega a Domicilio' : 'Retiro en Tienda' }}</p>
                    <p style="margin: 0 0 0.4rem 0;"><strong>Dirección:</strong> {{ $pedido->direccion_envio }}</p>
                    @if($pedido->referencia_envio)
                        <p style="margin: 0;"><strong>Referencia:</strong> {{ $pedido->referencia_envio }}</p>
                    @endif
                </div>

                <div style="margin-top: 1.25rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
                    <a href="https://wa.me/{{ $configs['telefono_whatsapp'] ?? '59170012345' }}?text={{ urlencode('Hola NovaStore, acabo de realizar el pedido ' . $pedido->numero_pedido . ' por Bs. ' . $pedido->total . '. Adjunto mi consulta.') }}" target="_blank" class="btn-primary" style="width: 100%; justify-content: center; background: #25D366;">
                        <i class="fa-brands fa-whatsapp"></i> Notificar Pago por WhatsApp
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
