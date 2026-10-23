@extends('layouts.admin')

@section('titulo', 'Configuración de Tienda - Admin')

@section('contenido')
<div class="card" style="max-width: 800px;">
    <h1 style="font-size: 1.5rem; font-weight: 800; margin: 0 0 0.5rem 0;">Configuraciones de la Tienda</h1>
    <p style="color: var(--text-muted); margin: 0 0 1.5rem 0; font-size: 0.88rem;">Personaliza la información comercial, datos de WhatsApp y parámetros de la pasarela de pagos QR.</p>

    <form action="{{ route('admin.configuracion.guardar') }}" method="POST">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.35rem;">Nombre Comercial de la Tienda</label>
                <input type="text" name="nombre_tienda" value="{{ $configs['nombre_tienda'] ?? 'NovaStore Bolivia' }}" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
            </div>

            <div>
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.35rem;">Slogan / Descripción Corta</label>
                <input type="text" name="slogan" value="{{ $configs['slogan'] ?? 'Tu tienda virtual multirrubro con entregas seguras y pagos QR' }}" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.35rem;">Número WhatsApp (con código de país sin +)</label>
                    <input type="text" name="telefono_whatsapp" value="{{ $configs['telefono_whatsapp'] ?? '59170012345' }}" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.35rem;">Email de Contacto</label>
                    <input type="email" name="email_contacto" value="{{ $configs['email_contacto'] ?? 'contacto@novastore.bo' }}" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                </div>
            </div>

            <div>
                <label style="display: block; font-weight: 600; font-size: 0.88rem; margin-bottom: 0.35rem;">Dirección Física (Punto de Retiro)</label>
                <input type="text" name="direccion_tienda" value="{{ $configs['direccion_tienda'] ?? 'Av. Arce esq. Cordero, Edificio Platinum Mezzanine 2, La Paz - Bolivia' }}" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
            </div>

            <div style="background: #F8FAFC; border: 1px solid var(--border-color); border-radius: 0.5rem; padding: 1.25rem;">
                <h3 style="margin: 0 0 0.75rem 0; font-size: 1rem; font-weight: 700; color: var(--primary);">
                    <i class="fa-solid fa-qrcode"></i> Parámetros de Pasarela QR y Cuentas Bancarias
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.82rem; margin-bottom: 0.35rem;">Titular de la Cuenta Destino</label>
                        <input type="text" name="qr_titular" value="{{ $configs['qr_titular'] ?? 'NOVASTORE S.R.L.' }}" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 600; font-size: 0.82rem; margin-bottom: 0.35rem;">Banco Principal</label>
                        <input type="text" name="qr_banco" value="{{ $configs['qr_banco'] ?? 'Banco Nacional de Bolivia / BCP' }}" style="width: 100%; padding: 0.6rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-primary" style="padding: 0.75rem 1.5rem; font-size: 0.95rem;">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
