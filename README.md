# plantilla-back

Plantilla base de backend (Laravel 10 + PHP 8.2) lista para clonar y empezar un nuevo sistema con la infraestructura de autenticación ya funcionando: login JWT, usuarios, roles y permisos.

## Incluye

- Login JWT (`POST /api/users/login`) vía `tymon/jwt-auth`
- Usuarios CRUD (`create`, `update`, `delete`, `restore`, `cajeros`, `impersonar`, `cambiarContraseña`)
- Permisos (`index`, `index-agrupados`, `index-usuario`, `index/{tipoUsuario}`, `misPermisos`, `agregarPermiso`)
- Roles CRUD
- Middleware `jwt.verify` (protección de rutas) y `permisos.verify` (gate por permisos)
- Modelo `User` con: soft deletes, auditoría (`owen-it/laravel-auditing`), `is_admin` (bypass de permisos), `chequearPermisos`, relación con roles/permisos/sucursales
- Soporte multi-entidad: `TipoUsuario`, `Sucursal`, `Comercio`, `UsuarioSucursal` con filtrado por tipo de usuario (`FiltroTipoUsuarioEntidadesTrait`)
- `Alerta`/toasts del lado frontend no aplican aquí; los errores se devuelven JSON

## Arranque

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
php artisan migrate:fresh --seed
php artisan serve
```

### Usuario seedeado (base)

- Email: `admin@example.com`
- Contraseña: `admin123`
- Tipo de usuario: `USR` (usuario del sistema), rol `admin` con los 11 permisos base
- Además se crea un `CLI` (usuario cliente), un comercio y una sucursal de ejemplo

## Configuración

El `.env` base asume MySQL con la DB `db_template`. Cambiar `DB_*` según el entorno.

## Estructura de permisos

Los códigos se comparan en minúsculas. `PermisosSeeder::PERMISOS_LISTA` define los permisos base:

- `usuarios`: list, view, create, update, delete, impersonate
- `roles`: list, view, create, update, delete

`User::is_admin` evita la verificación de permisos. Para el resto se resuelve por `id_rol` + `permisos_usuarios` (`User::chequearPermisos`).

## Agregar un módulo nuevo

1. Crear modelo + migración
2. Crear `Controller` + `FormRequest`
3. Registrar rutas en `routes/api.php` dentro del grupo `jwt.verify`, con `permisos.verify:xxx` si requiere permiso
4. Agregar el permiso en `PermisosSeeder::PERMISOS_LISTA`
5. Asignar el permiso al rol en `rol_permisos` (o desde el frontend en roles/usuarios)# back-sovic
