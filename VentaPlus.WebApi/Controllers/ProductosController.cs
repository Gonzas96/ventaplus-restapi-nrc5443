using System;
using System.Data.Entity;
using System.Linq;
using System.Net;
using System.Net.Http;
using System.Web.Http;
using VentaPlus.WebApi.Data;
using VentaPlus.WebApi.Models;

namespace VentaPlus.WebApi.Controllers
{
    [RoutePrefix("api/productos")]
    public class ProductosController : ApiController
    {
        private readonly VentaPlusDbContext db = new VentaPlusDbContext();

        // GET: api/productos
        [HttpGet]
        [Route("")]
        public IHttpActionResult GetProductos()
        {
            var productos = db.Productos
                               .AsNoTracking()
                               .OrderBy(p => p.IdProducto)
                               .ToList();

            return Ok(productos);
        }

        // GET: api/productos/5
        [HttpGet]
        [Route("{id:int}")]
        public IHttpActionResult GetProducto(int id)
        {
            var producto = db.Productos.AsNoTracking()
                                        .FirstOrDefault(p => p.IdProducto == id);

            if (producto == null)
            {
                return NotFound();
            }

            return Ok(producto);
        }

        // POST: api/productos
        [HttpPost]
        [Route("")]
        public IHttpActionResult PostProducto([FromBody] Producto producto)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            producto.FechaRegistro = DateTime.Now;

            db.Productos.Add(producto);
            db.SaveChanges();

            return CreatedAtRoute("DefaultApi", new { id = producto.IdProducto }, producto);
        }

        // PUT: api/productos/5
        [HttpPut]
        [Route("{id:int}")]
        public IHttpActionResult PutProducto(int id, [FromBody] Producto producto)
        {
            if (!ModelState.IsValid)
            {
                return BadRequest(ModelState);
            }

            if (id != producto.IdProducto)
            {
                return BadRequest("El id de la ruta no coincide con el id del producto enviado.");
            }

            var productoExistente = db.Productos.FirstOrDefault(p => p.IdProducto == id);
            if (productoExistente == null)
            {
                return NotFound();
            }

            productoExistente.Nombre = producto.Nombre;
            productoExistente.Descripcion = producto.Descripcion;
            productoExistente.Precio = producto.Precio;
            productoExistente.Stock = producto.Stock;
            productoExistente.Categoria = producto.Categoria;
            productoExistente.Activo = producto.Activo;

            try
            {
                db.SaveChanges();
            }
            catch (DbUpdateConcurrencyException)
            {
                if (!ProductoExiste(id))
                {
                    return NotFound();
                }
                throw;
            }

            return StatusCode(HttpStatusCode.NoContent);
        }

        // DELETE: api/productos/5
        [HttpDelete]
        [Route("{id:int}")]
        public IHttpActionResult DeleteProducto(int id)
        {
            var producto = db.Productos.FirstOrDefault(p => p.IdProducto == id);
            if (producto == null)
            {
                return NotFound();
            }

            db.Productos.Remove(producto);
            db.SaveChanges();

            return Ok(producto);
        }

        private bool ProductoExiste(int id)
        {
            return db.Productos.Any(p => p.IdProducto == id);
        }

        protected override void Dispose(bool disposing)
        {
            if (disposing)
            {
                db.Dispose();
            }
            base.Dispose(disposing);
        }
    }
}
