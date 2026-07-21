<?php
require_once __DIR__ . '/../config.php';

/**
 * Cliente HTTP muy simple sobre cURL para consumir la API REST de Productos.
 * Esta es la UNICA clase que habla con la API; ningun otro archivo PHP
 * accede directamente a base de datos.
 */
class ApiClient
{
    private static function request($method, $endpoint = '', $body = null)
    {
        $url = API_BASE_URL . $endpoint;

        $ch = curl_init($url);

        $headers = ['Content-Type: application/json', 'Accept: application/json'];

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['ok' => false, 'status' => 0, 'error' => $error, 'data' => null];
        }

        $data = $response !== '' ? json_decode($response, true) : null;

        return [
            'ok' => $httpCode >= 200 && $httpCode < 300,
            'status' => $httpCode,
            'error' => null,
            'data' => $data
        ];
    }

    public static function getAll()
    {
        return self::request('GET');
    }

    public static function getById($id)
    {
        return self::request('GET', '/' . intval($id));
    }

    public static function create($producto)
    {
        return self::request('POST', '', $producto);
    }

    public static function update($id, $producto)
    {
        return self::request('PUT', '/' . intval($id), $producto);
    }

    public static function delete($id)
    {
        return self::request('DELETE', '/' . intval($id));
    }
}
