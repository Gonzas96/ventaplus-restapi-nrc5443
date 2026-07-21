# Venta Plus - API REST (Proyecto 1)

Proyecto para NRC-5443 (Desarrollo de Aplicaciones Empresariales Avanzado). Migración del sistema Venta Plus de WCF a una arquitectura REST.

> El cliente web (Proyecto 2) vive en un repositorio separado: `venta-plus-webclient` (Node.js + Express + EJS).

## Estructura

```
/VentaPlus.WebApi   -> Proyecto 1: ASP.NET Web API REST (.NET Framework 4.7.2, EF Code First)
VentaPlus.sln        -> Solución de Visual Studio
```

## Requisitos

- Visual Studio 2019 o 2022 con la carga de trabajo "Desarrollo web y ASP.NET"
- SQL Server (LocalDB, Express o completo)

## Pasos para levantar el proyecto

1. Clona el repositorio y abre `VentaPlus.sln` en Visual Studio.
2. Visual Studio restaurará automáticamente los paquetes NuGet (EntityFramework, Microsoft.AspNet.WebApi, Newtonsoft.Json). Si no lo hace: clic derecho en la solución → **Restaurar paquetes NuGet**.
3. Abre `VentaPlus.WebApi/Web.config` y ajusta la cadena de conexión `VentaPlusDbContext` según tu instancia de SQL Server:
   ```xml
   <add name="VentaPlusDbContext"
        connectionString="Data Source=.\SQLEXPRESS;Initial Catalog=VentaPlusRestDB;Integrated Security=True;MultipleActiveResultSets=True"
        providerName="System.Data.SqlClient" />
   ```
4. Compila la solución (Ctrl+Shift+B) para confirmar que no hay errores antes de tocar migraciones.
5. Clic derecho sobre `VentaPlus.WebApi` → **"Establecer como proyecto de inicio"** (debe quedar en negrita). Esto es importante porque el Package Manager Console lee el config del proyecto de inicio.

## Migraciones de Entity Framework (Code First) — flujo completo

Este repositorio **no** incluye la carpeta `Migrations`; la generas tú desde Visual Studio para tener tus propias capturas/scripts como evidencia de la Pregunta 3.

En **Herramientas → Administrador de paquetes NuGet → Consola del Administrador de paquetes** (confirma que el "Default project" sea `VentaPlus.WebApi`):

```powershell
Enable-Migrations
Add-Migration InitialCreate
Update-Database
```

- `Enable-Migrations` crea la carpeta `Migrations` y el archivo `Configuration.cs`.
- `Add-Migration InitialCreate` genera el script de migración (`XXXXXX_InitialCreate.cs`) a partir del modelo (`Producto.cs`).
- `Update-Database` aplica la migración y crea la base de datos `VentaPlusRestDB` con la tabla `Productos`.

Si quieres datos de prueba automáticos, abre `Migrations/Configuration.cs` (generado por `Enable-Migrations`) y agrega en el método `Seed`:
```csharp
protected override void Seed(VentaPlus.WebApi.Data.VentaPlusDbContext context)
{
    context.Productos.AddOrUpdate(
        p => p.Nombre,
        new VentaPlus.WebApi.Models.Producto { Nombre = "Laptop Lenovo ThinkPad", Descripcion = "14 pulgadas, 16GB RAM", Precio = 3200.00m, Stock = 15, Categoria = "Computo", Activo = true },
        new VentaPlus.WebApi.Models.Producto { Nombre = "Mouse Inalambrico Logitech", Descripcion = "Mouse ergonomico", Precio = 45.90m, Stock = 80, Categoria = "Accesorios", Activo = true }
    );
}
```
Luego vuelve a correr `Update-Database` para que se inserten.

## Ejecutar la API

- Presiona **F5** (o Iniciar sin depurar). Visual Studio levantará el proyecto con IIS Express.
- Anota el puerto asignado (ej. `http://localhost:52301/`); lo necesitarás para configurar el cliente web.

## Endpoints REST

| Método | Ruta                     | Descripción                |
|--------|--------------------------|-----------------------------|
| GET    | `/api/productos`         | Lista todos los productos   |
| GET    | `/api/productos/{id}`    | Obtiene un producto por id  |
| POST   | `/api/productos`         | Crea un producto            |
| PUT    | `/api/productos/{id}`    | Actualiza un producto       |
| DELETE | `/api/productos/{id}`    | Elimina un producto         |

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

Prueba los endpoints con **Postman** — captura los 5 métodos para la evidencia de la Pregunta 3.

## Arquitectura

- Arquitectura por capas: `Models` (entidad + Data Annotations), `Data` (DbContext / Code First), `Controllers` (Web API REST).
- CORS habilitado en `Web.config` (`Access-Control-Allow-Origin: *`) para que el cliente web, corriendo en otro puerto/servidor, pueda consumir la API sin bloqueos del navegador.
