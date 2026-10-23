# Tienda Virtual Multirrubro Bolivia - NovaStore (Laravel + MySQL)

Plataforma completa de comercio electrónico multirrubro desarrollada con **Laravel 12**, **MySQL en XAMPP**, pasarela de pago con **Código QR Simple**, transacciones atómicas con inventario auditado (**Kardex**), cálculo dinámico de fletes por zonas en Bolivia, cupones de descuento y panel administrativo independiente.

---

## 🚀 Requisitos del Sistema

- **PHP**: 8.2 o superior con extensiones `pdo_mysql`, `mbstring`, `fileinfo`, `openssl`.
- **Base de Datos**: MySQL / MariaDB (XAMPP en puerto estándar 3306).
- **Composer**: Para dependencias PHP.
- **Node.js y npm**: Para compilación de recursos frontend.

---

## 🛠️ Instalación y Configuración Paso a Paso

### 1. Clonar o acceder al proyecto
```bash
cd "c:\xampp\htdocs\Proyectos\Tienda Virtual\Tienda"
```

### 2. Configurar Base de Datos en MySQL
Asegúrate de que MySQL esté activo en el Panel de Control de XAMPP. La base de datos configurada es:
- **Base de Datos:** `tienda_multirrubro`
- **Host:** `127.0.0.1`
- **Puerto:** `3306`
- **Usuario:** `root`
- **Contraseña:** *(vacía por defecto en XAMPP)*

### 3. Migraciones y Carga de Datos Iniciales (Seeders)
Ejecuta el comando para crear la estructura completa y sembrar los datos demostrativos en español:
```bash
php artisan migrate:fresh --seed
```

### 4. Generar Enlace Simbólico de Almacenamiento (Para comprobantes e imágenes)
```bash
php artisan storage:link
```

### 5. Iniciar Servidor Local
```bash
php artisan serve --port=8004
```

---

## 🔐 Cuentas de Acceso Demostrativas

### 1. Panel Administrativo
- **URL:** [http://127.0.0.1:8004/admin](http://127.0.0.1:8004/admin)
- **Email:** `admin@tienda.test`
- **Contraseña:** `admin12345`

### 2. Cuenta de Cliente de Prueba
- **URL:** [http://127.0.0.1:8004/cuenta/login](http://127.0.0.1:8004/cuenta/login)
- **Email:** `cliente@tienda.test`
- **Contraseña:** `cliente12345`

---

## 📦 Módulos Principales Disponibles

| Módulo | Ruta | Descripción |
| :--- | :--- | :--- |
| **Inicio & Hero** | `/` | Banner moderno con temporada 2026, categorías multirrubro y ofertas. |
| **Catálogo General** | `/catalogo` | Filtro por categoría, marca, precio y ordenación ascendente/descendente. |
| **Detalle de Producto** | `/producto/{slug}` | Ficha técnica, cálculo de ofertas y selector de cantidad con control de stock. |
| **Carrito de Compras** | `/carrito` | Carrito en sesión con validación de stock y cupones (`BIENVENIDO10`, `NOVA50`). |
| **Checkout & Pagos QR** | `/checkout` | Tarifas de envío por zonas en Bolivia, selección de QR y confirmación. |
| **Pasarela QR y Comprobante** | `/pedido/{numero}/confirmacion` | QR dinámico para escaneo interbancario y subida de comprobante de pago. |
| **Rastreo de Pedidos** | `/rastreo` | Consulta pública con línea de tiempo del estado comercial, financiero y logístico. |
| **Mi Cuenta / Perfil** | `/cuenta/perfil` | Registro de clientes, inicio de sesión e historial de órdenes. |
| **Dashboard Admin** | `/admin` | Métricas en vivo (Ventas cobradas, pagos por verificar, stock bajo). |
| **Gestión de Productos** | `/admin/productos` | Alta, edición y ajuste directo de existencias en Kardex. |
| **Auditoría Kardex** | `/admin/reportes/movimientos` | Registro inmutable de entradas, salidas y ajustes con usuarios y justificación. |
| **Validación de Pagos QR** | `/admin/pagos` | Aprobación o rechazo de comprobantes de pago subidos por los compradores. |
| **Configuración General** | `/admin/configuracion` | Datos de WhatsApp, titular de cuentas bancarias y parámetros de tienda. |
