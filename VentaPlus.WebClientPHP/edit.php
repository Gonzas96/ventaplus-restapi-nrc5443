<?php
require_once __DIR__ . '/includes/ApiClient.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['idProducto']) ? intval($_POST['idProducto']) : 0);

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto = [
        'idProducto'  => $id,
        'nombre'      => trim($_POST['nombre'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'precio'      => (float)($_POST['precio'] ?? 0),
        'stock'       => (int)($_POST['stock'] ?? 0),
        'categoria'   => trim($_POST['categoria'] ?? ''),
        'activo'      => isset($_POST['activo'])
    ];

    if ($producto['nombre'] === '') {
        $errores[] = 'El nombre es obligatorio.';
    }
    if ($producto['precio'] <= 0) {
        $errores[] = 'El precio debe ser mayor a 0.';
    }

    if (empty($errores)) {
        $result = ApiClient::update($id, $producto);

        if ($result['ok']) {
            header('Location: index.php?msg=' . urlencode('Producto actualizado correctamente.'));
            exit;
        } else {
            $errores[] = 'La API respondio con un error (HTTP ' . $result['status'] . ').';
        }
    }

    $actual = $producto;
} else {
    $result = ApiClient::getById($id);

    if (!$result['ok'] || !$result['data']) {
        header('Location: index.php?msg=' . urlencode('El producto no existe.'));
        exit;
    }

    $actual = $result['data'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta Plus - Editar Producto</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Editar Producto</h1>
    <p class="subtitle"><a href="index.php">&larr; Volver al listado</a></p>

    <div class="card">
        <?php foreach ($errores as $e): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>

        <form method="post" action="edit.php?id=<?php echo $id; ?>">
            <input type="hidden" name="idProducto" value="<?php echo $id; ?>">

            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="100" required
                   value="<?php echo htmlspecialchars($actual['nombre'] ?? ''); ?>">

            <label for="descripcion">Descripcion</label>
            <textarea id="descripcion" name="descripcion" rows="3" maxlength="250"><?php echo htmlspecialchars($actual['descripcion'] ?? ''); ?></textarea>

            <label for="categoria">Categoria</label>
            <input type="text" id="categoria" name="categoria" maxlength="50"
                   value="<?php echo htmlspecialchars($actual['categoria'] ?? ''); ?>">

            <label for="precio">Precio</label>
            <input type="number" step="0.01" min="0.01" id="precio" name="precio" required
                   value="<?php echo htmlspecialchars($actual['precio'] ?? ''); ?>">

            <label for="stock">Stock</label>
            <input type="number" step="1" min="0" id="stock" name="stock" required
                   value="<?php echo htmlspecialchars($actual['stock'] ?? '0'); ?>">

            <label>
                <input type="checkbox" name="activo" style="width:auto; display:inline-block;"
                       <?php echo !empty($actual['activo']) ? 'checked' : ''; ?>>
                Activo
            </label>

            <div class="actions" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a class="btn btn-secondary" href="index.php">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
