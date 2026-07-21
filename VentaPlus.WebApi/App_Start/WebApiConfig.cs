using System.Web.Http;

namespace VentaPlus.WebApi
{
    public static class WebApiConfig
    {
        public static void Register(HttpConfiguration config)
        {
            // Rutas basadas en atributos (permite usar [Route] en los controllers)
            config.MapHttpAttributeRoutes();

            // Ruta convencional por defecto
            config.Routes.MapHttpRoute(
                name: "DefaultApi",
                routeTemplate: "api/{controller}/{id}",
                defaults: new { id = RouteParameter.Optional }
            );

            // Formatea la respuesta JSON de forma legible
            var jsonFormatter = config.Formatters.JsonFormatter;
            jsonFormatter.SerializerSettings.Formatting = Newtonsoft.Json.Formatting.Indented;
            jsonFormatter.SerializerSettings.ReferenceLoopHandling = Newtonsoft.Json.ReferenceLoopHandling.Ignore;

            // Quita el formateador XML para que solo responda en JSON
            config.Formatters.Remove(config.Formatters.XmlFormatter);
        }
    }
}
