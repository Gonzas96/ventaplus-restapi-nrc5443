<?php
require_once __DIR__ . '/includes/ApiClient.php';

$result = ApiClient::getAll();
$productos = ($result['ok'] && is_array($result['data'])) ? $result['data'] : [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta Plus - Listado de Productos</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Venta Plus</h1>
    <p class="subtitle">Cliente Web (PHP) &mdash; consume la API REST de Productos</p>

    <?php if (isset($_GET['msg'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
    <?php endif; ?>

    <?php if (!$result['ok']): ?>
        <div class="alert alert-error">
            No se pudo conectar con la API REST (<?php echo htmlspecialchars($result['error'] ?? 'HTTP ' . $result['status']); ?>).
            Verifica que el proyecto VentaPlus.WebApi este corriendo y que API_BASE_URL en config.php sea correcta.
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="actions">
            <a class="btn btn-primary" href="create.php">+ Nuevo Producto</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Categoria</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($productos)): ?>
                    <tr><td colspan="7">No hay productos registrados.</td></tr>
                <?php endif; ?>

                <?php foreach ($productos as $p): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($p['idProducto']); ?></td>
                        <td><?php echo htmlspecialchars($p['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($p['categoria']); ?></td>
                        <td>S/ <?php echo number_format((float)$p['precio'], 2); ?></td>
                        <td><?php echo htmlspecialchars($p['stock']); ?></td>
                        <td>
                            <?php if (!empty($p['activo'])): ?>
                                <span class="badge-activo">Activo</span>
                            <?php else: ?>
                                <span class="badge-inactivo">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-edit" href="edit.php?id=<?php echo urlencode($p['idProducto']); ?>">Editar</a>
                            <a class="btn btn-delete" href="delete.php?id=<?php echo urlencode($p['idProducto']); ?>"
                               onclick="return confirm('¿Eliminar el producto &quot;<?php echo htmlspecialchars($p['nombre']); ?>&quot;?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
