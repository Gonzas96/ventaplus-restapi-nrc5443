using System.Data.Entity;
using VentaPlus.WebApi.Models;

namespace VentaPlus.WebApi.Data
{
    public class VentaPlusDbContext : DbContext
    {
        // El nombre debe coincidir con la connectionString definida en Web.config
        public VentaPlusDbContext() : base("name=VentaPlusDbContext")
        {
        }

        public DbSet<Producto> Productos { get; set; }

        protected override void OnModelCreating(DbModelBuilder modelBuilder)
        {
            base.OnModelCreating(modelBuilder);
            // Evita que EF pluralice el nombre de las tablas automáticamente
            modelBuilder.Conventions.Remove<System.Data.Entity.ModelConfiguration.Conventions.PluralizingTableNameConvention>();
        }
    }
}
