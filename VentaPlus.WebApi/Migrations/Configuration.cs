namespace VentaPlus.WebApi.Migrations
{
    using System.Data.Entity.Migrations;
    using VentaPlus.WebApi.Data;
    using VentaPlus.WebApi.Models;

    // NOTA IMPORTANTE:
    // Este archivo habilita las Migraciones de Code First para el DbContext.
    // Debes generar la migración inicial DESDE Visual Studio (Package Manager Console)
    // para que EF genere correctamente el snapshot del modelo. Ver README.md,
    // sección "Pasos para generar la migración inicial".
    internal sealed class Configuration : DbMigrationsConfiguration<VentaPlusDbContext>
    {
        public Configuration()
        {
            AutomaticMigrationsEnabled = false;
            AutomaticMigrationsDataLossAllowed = false;
        }

        protected override void Seed(VentaPlusDbContext context)
        {
            // Datos de prueba iniciales (se insertan al ejecutar Update-Database)
            context.Productos.AddOrUpdate(
                p => p.Nombre,
                new Producto
                {
                    Nombre = "Laptop Lenovo ThinkPad",
                    Descripcion = "Laptop empresarial 14 pulgadas, 16GB RAM",
                    Precio = 3200.00m,
                    Stock = 15,
                    Categoria = "Computo",
                    Activo = true
                },
                new Producto
                {
                    Nombre = "Mouse Inalambrico Logitech",
                    Descripcion = "Mouse inalambrico ergonomico",
                    Precio = 45.90m,
                    Stock = 80,
                    Categoria = "Accesorios",
                    Activo = true
                },
                new Producto
                {
                    Nombre = "Teclado Mecanico Redragon",
                    Descripcion = "Teclado mecanico retroiluminado RGB",
                    Precio = 120.50m,
                    Stock = 30,
                    Categoria = "Accesorios",
                    Activo = true
                }
            );
        }
    }
}
