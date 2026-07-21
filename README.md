# Venta Plus - API REST + Cliente Web PHP

Proyecto para NRC-5443 (Desarrollo de Aplicaciones Empresariales Avanzado). Migracion del sistema Venta Plus de WCF a una arquitectura REST.

## Estructura del repositorio

```
/VentaPlus.WebApi        -> Proyecto 1: ASP.NET Web API REST (.NET Framework 4.7.2, EF Code First)
/VentaPlus.WebClientPHP  -> Proyecto 2: Cliente Web en PHP (consume la API por HTTP)
VentaPlus.sln            -> Solucion de Visual Studio (solo abre el proyecto de la API)
```

La aplicacion cliente **no usa Entity Framework ni se conecta a SQL Server**; toda la data llega mediante peticiones HTTP a `VentaPlus.WebApi`.

---

## 1. Proyecto 1: VentaPlus.WebApi (Visual Studio)

### Requisitos
- Visual Studio 2019 o 2022 con la carga de trabajo "Desarrollo web y ASP.NET"
- SQL Server (LocalDB, Express o completo)

### Pasos para levantar el proyecto

1. Abre `VentaPlus.sln` en Visual Studio.
2. Visual Studio restaurara automaticamente los paquetes NuGet (EntityFramework, Microsoft.AspNet.WebApi, Newtonsoft.Json). Si no lo hace: clic derecho en la solucion → **Restaurar paquetes NuGet**.
3. Abre `VentaPlus.WebApi/Web.config` y ajusta la cadena de conexion `VentaPlusDbContext` según tu instancia de SQL Server:
   ```xml
   <add name="VentaPlusDbContext"
        connectionString="Data Source=.\SQLEXPRESS;Initial Catalog=VentaPlusRestDB;Integrated Security=True;MultipleActiveResultSets=True"
        providerName="System.Data.SqlClient" />
   ```

### Pasos para generar la migración inicial (IMPORTANTE)

El repositorio incluye `Migrations/Configuration.cs` (habilita Code First Migrations), pero **no** incluye el archivo de migración inicial, porque ese archivo debe generarse desde tu máquina para que Entity Framework capture correctamente el snapshot del modelo. Hazlo así:

1. En Visual Studio: **Herramientas → Administrador de paquetes NuGet → Consola del Administrador de paquetes**.
2. Asegúrate que el "Default project" sea `VentaPlus.WebApi`.
3. Ejecuta:
   ```powershell
   Add-Migration InitialCreate
   Update-Database
   ```
4. Esto crea la base de datos `VentaPlusRestDB` con la tabla `Productos` y la deja poblada con 3 productos de prueba (ver `Seed` en `Configuration.cs`).

### Ejecutar la API

- Presiona **F5** (o Iniciar sin depurar). Visual Studio levantará el proyecto con IIS Express.
- Anota el puerto que asigne (ej. `http://localhost:52301/`) y actualízalo en `VentaPlus.WebClientPHP/config.php`.

### Endpoints REST

| Método | Ruta                     | Descripción              |
|--------|--------------------------|---------------------------|
| GET    | `/api/productos`         | Lista todos los productos |
| GET    | `/api/productos/{id}`    | Obtiene un producto por id|
| POST   | `/api/productos`         | Crea un producto          |
| PUT    | `/api/productos/{id}`    | Actualiza un producto     |
| DELETE | `/api/productos/{id}`    | Elimina un producto       |

Ejemplo de body para POST/PUT:
```json
{
  "nombre": "Monitor LG 24 pulgadas",
  "descripcion": "Monitor Full HD IPS",
  "precio": 650.00,
  "stock": 12,
  "categoria": "Computo",
  "activo": true
}
```

Prueba los endpoints con **Postman** antes de conectar el cliente PHP; es la evidencia que pide la Pregunta 3 del enunciado.

---

## 2. Proyecto 2: VentaPlus.WebClientPHP

### Requisitos
- PHP 7.4+ con la extensión `curl` habilitada.

### Configuración

Edita `config.php` con la URL donde quedó corriendo la API:
```php
define('API_BASE_URL', 'http://localhost:52301/api/productos');
```

### Ejecutar el cliente

Desde la carpeta `VentaPlus.WebClientPHP`:
```bash
php -S localhost:8000
```
Abre `http://localhost:8000` en el navegador.

### Funcionalidad

- `index.php` — Lista los productos (GET).
- `create.php` — Formulario para registrar un producto (POST).
- `edit.php` — Formulario para editar un producto existente (PUT).
- `delete.php` — Elimina un producto (DELETE) y redirige al listado.
- `includes/ApiClient.php` — Única clase que se comunica con la API vía cURL; el resto de archivos PHP no hacen llamadas HTTP directamente.

---

## 3. Evidencias sugeridas para la entrega (Pregunta 3)

1. Capturas de los endpoints funcionando en Postman (GET, GET por id, POST, PUT, DELETE).
2. Capturas del CRUD funcionando desde el cliente web PHP (listar, registrar, editar, eliminar).
3. Captura de la base de datos generada por Code First (tabla `Productos` en SQL Server Management Studio), y el script de la migración (`Migrations/InitialCreate.cs` una vez generado).
4. Comprimir todo el proyecto en un `.zip` con el nombre `ApellidoPaterno.zip` según indica el enunciado.

---

## Notas de arquitectura

- **Proyecto 1** implementa: arquitectura por capas (Models / Data / Controllers), Entity Framework Code First, Data Annotations en `Producto.cs`, migraciones (`Migrations/Configuration.cs`) y CRUD completo vía Web API REST.
- **Proyecto 2** consume exclusivamente la API REST vía HTTP (cURL), sin Entity Framework ni acceso directo a SQL Server, cumpliendo la restricción del enunciado.
- CORS está habilitado en `Web.config` (`Access-Control-Allow-Origin: *`) para permitir que el cliente PHP, corriendo en otro puerto/servidor, pueda consumir la API sin bloqueos del navegador.
