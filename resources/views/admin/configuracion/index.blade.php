@extends('layouts.admin')

@section('titulo', 'Configuración de Tienda - Admin')

@section('contenido')
<div style="max-width: 900px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem; font-weight: 800; margin: 0 0 0.35rem 0; color: #0F2414;">Configuraciones de la Tienda</h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.92rem;">Parámetros de identidad farmacéutica, canal directo de WhatsApp y pasarela de cobros QR.</p>
    </div>

    <form action="{{ route('admin.configuracion.guardar') }}" method="POST">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 1.75rem;">
            <!-- SECCIÓN 1: IDENTIDAD -->
            <div class="card">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem;">
                    <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: var(--primary-light); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-store"></i>
                    </div>
                    <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0F2414;">Identidad Comercial</h2>
                </div>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">Nombre Comercial de la Tienda</label>
                        <input type="text" name="nombre_tienda" class="form-control" value="{{ $configs['nombre_tienda'] ?? 'FARMALUZ - Preparados Magistrales' }}">
                    </div>

                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">Slogan / Especialidad Farmacéutica</label>
                        <input type="text" name="slogan" class="form-control" value="{{ $configs['slogan'] ?? 'Farmacia Natural, Fitoterapia y Fórmulas Magistrales Personalizadas' }}">
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                        <div>
                            <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">
                                <i class="fa-brands fa-whatsapp" style="color: #25D366;"></i> WhatsApp de Atención (con código de país sin +)
                            </label>
                            <input type="text" name="telefono_whatsapp" class="form-control" value="{{ $configs['telefono_whatsapp'] ?? '59170012345' }}">
                        </div>
                        <div>
                            <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">Email de Contacto Oficial</label>
                            <input type="email" name="email_contacto" class="form-control" value="{{ $configs['email_contacto'] ?? 'contacto@farmaluz.bo' }}">
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">Dirección Física (Laboratorio / Sucursal Principal)</label>
                        <input type="text" name="direccion_tienda" class="form-control" value="{{ $configs['direccion_tienda'] ?? 'Av. Arce esq. Cordero, Edificio Platinum Mezzanine 2, La Paz - Bolivia' }}">
                    </div>
                </div>
            </div>

            <!-- SECCIÓN 2: PASARELA Y COBROS QR -->
            <div class="card" style="border-left: 4px solid var(--secondary);">
                <div style="display: flex; align-items: center; gap: 0.65rem; margin-bottom: 1.25rem;">
                    <div style="width: 32px; height: 32px; border-radius: var(--radius-sm); background: #FDF2F8; color: var(--secondary); display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <h2 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #0F2414;">Parámetros de Cobro QR Simple y Banco</h2>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">Titular de la Cuenta / Razón Social</label>
                        <input type="text" name="qr_titular" class="form-control" value="{{ $configs['qr_titular'] ?? 'FARMALUZ LABORATORIOS S.R.L.' }}">
                    </div>
                    <div>
                        <label style="display: block; font-weight: 700; font-size: 0.84rem; margin-bottom: 0.35rem; color: #1E293B;">Entidad Bancaria Destino</label>
                        <input type="text" name="qr_banco" class="form-control" value="{{ $configs['qr_banco'] ?? 'Banco Nacional de Bolivia / BCP' }}">
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-primary" style="padding: 0.85rem 2rem; font-size: 0.95rem;">
                    <i class="fa-solid fa-floppy-disk"></i> Guardar Configuraciones
                </button>
            </div>
        </div>
    </form>
</div>

@endsection
