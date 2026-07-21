<?php
require_once __DIR__ . '/includes/ApiClient.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $result = ApiClient::delete($id);

    if ($result['ok']) {
        header('Location: index.php?msg=' . urlencode('Producto eliminado correctamente.'));
        exit;
    }
}

header('Location: index.php?msg=' . urlencode('No se pudo eliminar el producto.'));
exit;
