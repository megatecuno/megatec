<?php
header('Content-Type: text/html; charset=utf-8');
require_once dirname(__dir__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';
require_once dirname(__dir__, 2) . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'db.php';

class mercadolibreapi extends database {
    private $app_id = '5683816652855584';
    private $client_secret = 'tu_nuevo_secret_aqui'; // ¡reemplaza esto!
    private $redirect_uri = 'http://localhost/megatec/public/callback_ml.php';
    private $access_token = null;
    private $refresh_token = null;
    private $base_url = 'https://api.mercadolibre.com';

    public function __construct($access_token = null, $refresh_token = null) {
        parent::__construct();
        if ($access_token) {
            $this->access_token = $access_token;
        }
        if ($refresh_token) {
            $this->refresh_token = $refresh_token;
        }
    }

    public function settokens($access_token, $refresh_token = null) {
        $this->access_token = $access_token;
        if ($refresh_token) {
            $this->refresh_token = $refresh_token;
        }
    }

    public function refreshtoken() {
        if (!$this->refresh_token) {
            throw new exception("no refresh token available");
        }

        $url = "$this->base_url/oauth/token";
        $ch = curl_init();
        curl_setopt($ch, curlopt_url, $url);
        curl_setopt($ch, curlopt_post, true);
        curl_setopt($ch, curlopt_returntransfer, true);
        curl_setopt($ch, curlopt_postfields, http_build_query([
            'grant_type' => 'refresh_token',
            'client_id' => $this->app_id,
            'client_secret' => $this->client_secret,
            'refresh_token' => $this->refresh_token
        ]));

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, curlinfo_http_code);
        curl_close($ch);

        if ($http_code === 200) {
            $data = json_decode($response, true);
            $this->access_token = $data['access_token'];
            if (isset($data['refresh_token'])) {
                $this->refresh_token = $data['refresh_token'];
            }
            return $data;
        } else {
            throw new exception("error refreshing token: " . $response);
        }
    }

    private function makerequest($url, $method = 'get', $data = null) {
        if (!$this->access_token) {
            throw new exception("access token not available");
        }

        $ch = curl_init();
        curl_setopt($ch, curlopt_url, $url);
        curl_setopt($ch, curlopt_returntransfer, true);
        curl_setopt($ch, curlopt_httpheader, [
            "authorization: bearer $this->access_token",
            "content-type: application/json"
        ]);

        if ($method === 'post') {
            curl_setopt($ch, curlopt_post, true);
            if ($data) {
                curl_setopt($ch, curlopt_postfields, json_encode($data));
            }
        } elseif ($method === 'put') {
            curl_setopt($ch, curlopt_customrequest, 'put');
            if ($data) {
                curl_setopt($ch, curlopt_postfields, json_encode($data));
            }
        }

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, curlinfo_http_code);
        curl_close($ch);

        if ($http_code === 401 && $this->refresh_token) {
            try {
                $this->refreshtoken();
                return $this->makerequest($url, $method, $data);
            } catch (exception $e) {
                error_log("token refresh failed: " . $e->getmessage());
                throw new exception("authentication failed even after refresh");
            }
        }

        if ($http_code >= 400) {
            error_log("ml api error ($http_code): $response");
            return null;
        }

        return json_decode($response, true);
    }

    public function getitemsbyseller($seller_id) {
        $url = "$this->base_url/sites/mla/search?seller_id=$seller_id";
        $data = $this->makerequest($url);
        return $data['results'] ?? [];
    }

    public function getitemdetails($item_id) {
        $url = "$this->base_url/items/$item_id";
        return $this->makerequest($url);
    }

    public function syncitemstodb($seller_id) {
        if (!$this->access_token) {
            throw new exception("access token no proporcionado");
        }

        $items = $this->getitemsbyseller($seller_id);

        foreach ($items as $item) {
            $details = $this->getitemdetails($item['id']);

            $this->query("select id from articulos where ml_item_id = :ml_item_id or (titulo = :titulo and creador_id = 0)");
            $this->bind(':ml_item_id', $item['id']);
            $this->bind(':titulo', $item['title']);
            $existing = $this->single();

            $precio = $item['price'] ?? 0;
            $stock = $item['available_quantity'] ?? 0;
            $descripcion = $details['description']['plain_text'] ?? substr($item['title'], 0, 500);
            $condicion = $item['condition'] ?? 'new';
            $envio_gratis = $item['shipping']['free_shipping'] ?? false;

            if ($existing) {
                $this->query("update articulos set 
                    titulo = :titulo, 
                    descripcion = :descripcion, 
                    precio = :precio, 
                    stock = :stock, 
                    condicion = :condicion,
                    envio_gratis = :envio_gratis,
                    fecha_actualizacion = now()
                    where id = :id");

                $this->bind(':titulo', $item['title']);
                $this->bind(':descripcion', $descripcion);
                $this->bind(':precio', $precio);
                $this->bind(':stock', $stock);
                $this->bind(':condicion', $condicion);
                $this->bind(':envio_gratis', $envio_gratis);
                $this->bind(':id', $existing['id']);
                $this->execute();
            } else {
                $this->query("insert into articulos (
                    titulo, descripcion, precio, stock, condicion, 
                    envio_gratis, creador_id, estado, fecha_creacion, ml_item_id
                ) values (
                    :titulo, :descripcion, :precio, :stock, :condicion, 
                    :envio_gratis, 1, 'activo', now(), :ml_item_id
                )");

                $this->bind(':titulo', $item['title']);
                $this->bind(':descripcion', $descripcion);
                $this->bind(':precio', $precio);
                $this->bind(':stock', $stock);
                $this->bind(':condicion', $condicion);
                $this->bind(':envio_gratis', $envio_gratis);
                $this->bind(':ml_item_id', $item['id']);

                $this->execute();
            }
        }

        return true;
    }
}
?>

