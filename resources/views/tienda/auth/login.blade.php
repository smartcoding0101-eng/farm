@extends('layouts.tienda')

@section('titulo', 'Acceso de Clientes - NovaStore Bolivia')

@section('contenido')
<div class="container" style="padding-top: 3rem; padding-bottom: 5rem; max-width: 900px;">
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; align-items: start;">
        
        <!-- INICIAR SESIÓN -->
        <div class="card" style="padding: 2rem;">
            <h2 style="font-size: 1.4rem; font-weight: 800; margin: 0 0 0.5rem 0;">Iniciar Sesión</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0 0 1.5rem 0;">Ingresa a tu cuenta para ver el estado de tus compras.</p>

            <form action="{{ route('cliente.login.post') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="tu@correo.com" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Contraseña</label>
                        <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.82rem;">
                        <label style="display: flex; align-items: center; gap: 0.4rem; cursor: pointer;">
                            <input type="checkbox" name="remember"> Recordarme
                        </label>
                    </div>

                    <button type="submit" class="btn-primary" style="justify-content: center; padding: 0.8rem; margin-top: 0.5rem;">
                        <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
                    </button>
                </div>
            </form>

            <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border-color); font-size: 0.82rem; color: var(--text-muted); text-align: center;">
                Cliente demo: <code>cliente@tienda.test</code> / <code>cliente12345</code>
            </div>
        </div>

        <!-- CREAR CUENTA NUEVA -->
        <div class="card" style="padding: 2rem;">
            <h2 style="font-size: 1.4rem; font-weight: 800; margin: 0 0 0.5rem 0;">Crear Cuenta Nueva</h2>
            <p style="color: var(--text-muted); font-size: 0.88rem; margin: 0 0 1.5rem 0;">Regístrate gratis para gestionar tus pedidos y recibir ofertas exclusivas.</p>

            <form action="{{ route('cliente.register.post') }}" method="POST">
                @csrf
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Nombre Completo *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ej: Patricia Flores" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Correo Electrónico *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required placeholder="correo@ejemplo.com" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Teléfono / WhatsApp *</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" required placeholder="Ej: 71234567" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Contraseña *</label>
                        <input type="password" name="password" required placeholder="Mínimo 6 caracteres" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.35rem;">Confirmar Contraseña *</label>
                        <input type="password" name="password_confirmation" required placeholder="Repite tu contraseña" style="width: 100%; padding: 0.65rem; border: 1px solid var(--border-color); border-radius: 0.4rem; box-sizing: border-box;">
                    </div>

                    <button type="submit" class="btn-primary" style="justify-content: center; padding: 0.8rem; margin-top: 0.5rem; background: #059669;">
                        <i class="fa-solid fa-user-plus"></i> Registrarme Gratis
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
