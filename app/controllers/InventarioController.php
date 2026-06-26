<?php
// Controlador para la gestión de inventario y movimientos de stock

namespace App\Controllers;

use App\Models\Inventory;
use App\Core\Session;
use App\Core\Paths;
use App\Core\ValidationHelper;

class InventarioController
{
    private $inventarioModel;

    public function __construct()
    {
        $this->inventarioModel = new Inventory();
    }

    /**
     * Muestra la vista principal del inventario
     */
    public function index()
    {
        // 1. Validar la sesión
        if (!Session::isLogged()) {
            redirect('login');
        }

        $user = Session::getUser();

        // 2. Obtener productos
        $products = $this->inventarioModel->read();

        // 3. Cargar productos dummy si la base de datos está vacía
        if (empty($products)) {
            $this->seedDummyProducts();
            $products = $this->inventarioModel->read();
        }

        // 4. Configurar metadatos para la vista
        $title = 'Inventario';
        $bodyClass = 'layout-footer';
        $extraScripts = [
            'DataTables/jquery-3.7.0.min.js',
            'DataTables/jquery.dataTables.min.js',
            'DataTables/dataTables.bootstrap5.min.js',
            'js/sidebar.js',
            'js/inventory.js'
        ];

        require_once __DIR__ . '/../views/inventario/index.php';
    }

    /**
     * Registra un nuevo producto (petición POST AJAX)
     */
    public function create()
    {
        if (!Session::isLogged()) {
            $this->respondWithError('No autorizado. Debes iniciar sesión.');
        }

        // Validar campos
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $sotck_actual = trim($_POST['sotck_actual'] ?? '');
        $stock_minimo = trim($_POST['stock_minimo'] ?? '');
        $precio_compra = trim($_POST['precio_compra'] ?? '');

        $rules = [
            [
                'condition' => empty($nombre) || $sotck_actual === '' || $stock_minimo === '' || empty($precio_compra),
                'message' => 'Todos los campos excepto la descripción son obligatorios.',
                'field' => 'all'
            ],
            [
                'condition' => !is_numeric($sotck_actual) || intval($sotck_actual) < 0,
                'message' => 'El stock actual debe ser un número entero mayor o igual a 0.',
                'field' => 'sotck_actual'
            ],
            [
                'condition' => !is_numeric($stock_minimo) || intval($stock_minimo) < 0,
                'message' => 'El stock mínimo debe ser un número entero mayor o igual a 0.',
                'field' => 'stock_minimo'
            ],
            [
                'condition' => !is_numeric($precio_compra) || floatval($precio_compra) < 0,
                'message' => 'El precio de compra debe ser un número decimal mayor o igual a 0.',
                'field' => 'precio_compra'
            ]
        ];

        ValidationHelper::validate($rules);

        try {
            $productId = $this->inventarioModel->createItem(
                $nombre,
                $descripcion,
                intval($sotck_actual),
                intval($stock_minimo),
                floatval($precio_compra)
            );

            $this->respondWithSuccess('Producto agregado exitosamente al inventario.', ['id' => $productId]);
        } catch (\Exception $e) {
            $this->respondWithError('Error al agregar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Edita un producto existente (petición POST AJAX)
     */
    public function edit()
    {
        if (!Session::isLogged()) {
            $this->respondWithError('No autorizado. Debes iniciar sesión.');
        }

        $id = intval($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $sotck_actual = trim($_POST['sotck_actual'] ?? '');
        $stock_minimo = trim($_POST['stock_minimo'] ?? '');
        $precio_compra = trim($_POST['precio_compra'] ?? '');

        $rules = [
            [
                'condition' => $id <= 0,
                'message' => 'ID de producto no válido.',
                'field' => 'id'
            ],
            [
                'condition' => empty($nombre) || $sotck_actual === '' || $stock_minimo === '' || empty($precio_compra),
                'message' => 'Todos los campos excepto la descripción son obligatorios.',
                'field' => 'all'
            ],
            [
                'condition' => !is_numeric($sotck_actual) || intval($sotck_actual) < 0,
                'message' => 'El stock actual debe ser un número entero mayor o igual a 0.',
                'field' => 'sotck_actual'
            ],
            [
                'condition' => !is_numeric($stock_minimo) || intval($stock_minimo) < 0,
                'message' => 'El stock mínimo debe ser un número entero mayor o igual a 0.',
                'field' => 'stock_minimo'
            ],
            [
                'condition' => !is_numeric($precio_compra) || floatval($precio_compra) < 0,
                'message' => 'El precio de compra debe ser un número decimal mayor o igual a 0.',
                'field' => 'precio_compra'
            ]
        ];

        ValidationHelper::validate($rules);

        try {
            $this->inventarioModel->updateItem(
                $id,
                $nombre,
                $descripcion,
                intval($sotck_actual),
                intval($stock_minimo),
                floatval($precio_compra)
            );

            $this->respondWithSuccess('Producto actualizado correctamente.');
        } catch (\Exception $e) {
            $this->respondWithError('Error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Elimina un producto de forma lógica (petición POST AJAX)
     */
    public function delete()
    {
        if (!Session::isLogged()) {
            $this->respondWithError('No autorizado. Debes iniciar sesión.');
        }

        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->respondWithError('ID de producto no válido.');
        }

        try {
            $this->inventarioModel->deleteItem($id);
            $this->respondWithSuccess('Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            $this->respondWithError('Error al eliminar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Registra un movimiento de stock (entrada/salida) (petición POST AJAX)
     */
    public function movement()
    {
        if (!Session::isLogged()) {
            $this->respondWithError('No autorizado. Debes iniciar sesión.');
        }

        $id_producto = intval($_POST['id_producto'] ?? 0);
        $tipo_movimiento = trim($_POST['tipo_movimiento'] ?? '');
        $cantidad = trim($_POST['cantidad'] ?? '');
        $motivo = trim($_POST['motivo'] ?? '');

        $rules = [
            [
                'condition' => $id_producto <= 0,
                'message' => 'Debe seleccionar un producto válido.',
                'field' => 'id_producto'
            ],
            [
                'condition' => !in_array($tipo_movimiento, ['entrada', 'salida']),
                'message' => 'El tipo de movimiento debe ser Entrada o Salida.',
                'field' => 'tipo_movimiento'
            ],
            [
                'condition' => empty($cantidad) || !is_numeric($cantidad) || intval($cantidad) <= 0,
                'message' => 'La cantidad debe ser un número entero mayor a 0.',
                'field' => 'cantidad'
            ],
            [
                'condition' => empty($motivo),
                'message' => 'Debe especificar el motivo del movimiento.',
                'field' => 'motivo'
            ]
        ];

        ValidationHelper::validate($rules);

        $user = Session::getUser();
        $id_usuario = $user['id'] ?? null;

        if (!$id_usuario) {
            $this->respondWithError('Usuario no identificado en la sesión actual.');
        }

        try {
            $this->inventarioModel->recordMovement(
                $id_producto,
                $id_usuario,
                $tipo_movimiento,
                intval($cantidad),
                $motivo
            );

            $this->respondWithSuccess('Movimiento de stock registrado y stock actualizado exitosamente.');
        } catch (\Exception $e) {
            $this->respondWithError('Error al registrar el movimiento: ' . $e->getMessage());
        }
    }

    /**
     * Retorna el historial de movimientos de inventario en JSON (petición GET)
     */
    public function history()
    {
        if (!Session::isLogged()) {
            $this->respondWithError('No autorizado.');
        }

        $id_producto = isset($_GET['id_producto']) && intval($_GET['id_producto']) > 0 ? intval($_GET['id_producto']) : null;

        try {
            $history = $this->inventarioModel->getMovementHistory($id_producto);
            // Formatear fechas para mejor visualización
            foreach ($history as &$mov) {
                if (!empty($mov['created_at'])) {
                    $mov['fecha_formateada'] = date('d/m/Y h:i A', strtotime($mov['created_at']));
                }
            }
            $this->respondWithSuccess('Historial obtenido.', ['history' => $history]);
        } catch (\Exception $e) {
            $this->respondWithError('Error al obtener el historial: ' . $e->getMessage());
        }
    }

    /**
     * Inserta datos iniciales de prueba en la tabla de inventario si se encuentra vacía
     */
    private function seedDummyProducts()
    {
        $dummyProducts = [
            [
                'nombre' => 'Aceite de Coco Orgánico',
                'descripcion' => 'Aceite hidratante de coco para masajes corporales y aromaterapia.',
                'sotck_actual' => 15,
                'stock_minimo' => 5,
                'precio_compra' => 12.50
            ],
            [
                'nombre' => 'Crema Exfoliante Facial',
                'descripcion' => 'Crema exfoliante suave con microesferas de aloe vera para limpieza de cutis.',
                'sotck_actual' => 3, // Bajo Stock
                'stock_minimo' => 8,
                'precio_compra' => 18.20
            ],
            [
                'nombre' => 'Gel Conductor Neutro',
                'descripcion' => 'Gel conductor especial para tratamientos de radiofrecuencia y aparatología.',
                'sotck_actual' => 25,
                'stock_minimo' => 10,
                'precio_compra' => 24.00
            ],
            [
                'nombre' => 'Toallas Desechables Premium',
                'descripcion' => 'Paquete de 50 toallas desechables absorbentes para uso higiénico en cabina.',
                'sotck_actual' => 4, // Bajo Stock
                'stock_minimo' => 5,
                'precio_compra' => 9.99
            ]
        ];

        foreach ($dummyProducts as $prod) {
            $this->inventarioModel->createItem(
                $prod['nombre'],
                $prod['descripcion'],
                $prod['sotck_actual'],
                $prod['stock_minimo'],
                $prod['precio_compra']
            );
        }
    }

    /**
     * Envía una respuesta JSON exitosa
     */
    private function respondWithSuccess($message, $extra = [])
    {
        header('Content-Type: application/json');
        echo json_encode(array_merge([
            'status' => 'success',
            'message' => $message
        ], $extra));
        exit();
    }

    /**
     * Envía una respuesta JSON de error
     */
    private function respondWithError($message)
    {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'error',
            'message' => $message
        ]);
        exit();
    }
}
