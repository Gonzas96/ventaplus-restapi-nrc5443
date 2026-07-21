<?php
require_once __DIR__ . '/includes/ApiClient.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto = [
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
        $result = ApiClient::create($producto);

        if ($result['ok']) {
            header('Location: index.php?msg=' . urlencode('Producto registrado correctamente.'));
            exit;
        } else {
            $errores[] = 'La API respondio con un error (HTTP ' . $result['status'] . ').';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Venta Plus - Nuevo Producto</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <h1>Nuevo Producto</h1>
    <p class="subtitle"><a href="index.php">&larr; Volver al listado</a></p>

    <div class="card">
        <?php foreach ($errores as $e): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($e); ?></div>
        <?php endforeach; ?>

        <form method="post" action="create.php">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" maxlength="100" required
                   value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">

            <label for="descripcion">Descripcion</label>
            <textarea id="descripcion" name="descripcion" rows="3" maxlength="250"><?php echo htmlspecialchars($_POST['descripcion'] ?? ''); ?></textarea>

            <label for="categoria">Categoria</label>
            <input type="text" id="categoria" name="categoria" maxlength="50"
                   value="<?php echo htmlspecialchars($_POST['categoria'] ?? ''); ?>">

            <label for="precio">Precio</label>
            <input type="number" step="0.01" min="0.01" id="precio" name="precio" required
                   value="<?php echo htmlspecialchars($_POST['precio'] ?? ''); ?>">

            <label for="stock">Stock</label>
            <input type="number" step="1" min="0" id="stock" name="stock" required
                   value="<?php echo htmlspecialchars($_POST['stock'] ?? '0'); ?>">

            <label>
                <input type="checkbox" name="activo" style="width:auto; display:inline-block;"
                       <?php echo (!isset($_POST['nombre']) || isset($_POST['activo'])) ? 'checked' : ''; ?>>
                Activo
            </label>

            <div class="actions" style="margin-top: 20px;">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a class="btn btn-secondary" href="index.php">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
