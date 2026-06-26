<?php
//Modelo para manejar los datos relacionados con el inventario y movimientos de stock

namespace App\Models;

use App\Core\Model;
use PDO;

class Inventory extends Model
{
    protected $tableName = "inventario";

    /**
     * Obtiene todos los productos activos (estado = 1)
     */
    public function read()
    {
        $sql = "SELECT * FROM " . $this->tableName . " WHERE estado = 1 ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * Registra un nuevo producto en el inventario
     */
    public function createItem($name, $description, $actualStock, $MinStock, $purchasePrice)
    {
        $sql = "INSERT INTO " . $this->tableName . " (nombre, descripcion, sotck_actual, stock_minimo, precio_compra, estado) 
                VALUES (:name, :description, :actualStock, :MinStock, :purchasePrice, 1)";

        $this->query($sql, [
            ':name' => $name,
            ':description' => $description,
            ':actualStock' => $actualStock,
            ':MinStock' => $MinStock,
            ':purchasePrice' => $purchasePrice
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Actualiza la información de un producto existente
     */
    public function updateItem($id, $name, $description, $actualStock, $MinStock, $purchasePrice)
    {
        $sql = "UPDATE " . $this->tableName . " 
                SET nombre = :name, descripcion = :description, sotck_actual = :actualStock, stock_minimo = :MinStock, precio_compra = :purchasePrice 
                WHERE id = :id";

        $stmt = $this->query($sql, [
            ':id' => $id,
            ':name' => $name,
            ':description' => $description,
            ':actualStock' => $actualStock,
            ':MinStock' => $MinStock,
            ':purchasePrice' => $purchasePrice
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Desactiva un producto (borrado lógico, estado = 0)
     */
    public function deleteItem($id)
    {
        $sql = "UPDATE " . $this->tableName . " SET estado = 0 WHERE id = :id";
        $stmt = $this->query($sql, [':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Registra un movimiento de inventario (entrada/salida) y actualiza el stock del producto
     * en una transacción segura de base de datos
     */
    public function recordMovement($productId, $userId, $typeMovement, $quantity, $reason)
    {
        $this->db->beginTransaction();
        try {
            //1. Obtener el stock actual del producto con bloqueo de fila (FOR UPDATE)
            $sqlProd = "SELECT sotck_actual FROM " . $this->tableName . " WHERE id = :id_producto FOR UPDATE";
            $stmtProd = $this->db->prepare($sqlProd);
            $stmtProd->execute([':id_producto' => $productId]);
            $producto = $stmtProd->fetch(PDO::FETCH_ASSOC);

            if (!$producto) {
                throw new \Exception("Producto no encontrado.");
            }

            $stockActual = intval($producto['sotck_actual']);
            $cantidad = intval($quantity);

            if ($cantidad <= 0) {
                throw new \Exception("La cantidad debe ser mayor a cero.");
            }

            // 2. Calcular el nuevo stock
            if ($typeMovement === 'entrada') {
                $nuevoStock = $stockActual + $cantidad;
            } else if ($typeMovement === 'salida') {
                $nuevoStock = $stockActual - $cantidad;
                if ($nuevoStock < 0) {
                    throw new \Exception("Stock insuficiente para realizar la salida. Stock actual: $stockActual.");
                }
            } else {
                throw new \Exception("Tipo de movimiento inválido.");
            }

            // 3. Actualizar stock en la tabla de inventario
            $sqlUp = "UPDATE " . $this->tableName . " SET sotck_actual = :nuevoStock WHERE id = :id_producto";
            $stmtUp = $this->db->prepare($sqlUp);
            $stmtUp->execute([
                ':nuevoStock' => $nuevoStock,
                ':id_producto' => $productId
            ]);

            // 4. Registrar en la tabla movimientos_inventario
            $sqlMov = "INSERT INTO movimientos_inventario (id_producto, id_usuario, tipo_movimiento, cantidad, motivo) 
                       VALUES (:id_producto, :id_usuario, :tipo_movimiento, :cantidad, :motivo)";
            $stmtMov = $this->db->prepare($sqlMov);
            $stmtMov->execute([
                ':id_producto' => $productId,
                ':id_usuario' => $userId,
                ':tipo_movimiento' => $typeMovement,
                ':cantidad' => $quantity,
                ':motivo' => $reason
            ]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene el historial de movimientos de inventario.
     * Puede filtrarse por un producto específico si se proporciona.
     */
    public function getMovementHistory($productId = null)
    {
        $sql = "SELECT m.*, i.nombre AS producto_nombre, CONCAT(u.nombre, ' ', u.apellido) AS usuario_nombre 
                FROM movimientos_inventario m
                JOIN inventario i ON m.id_producto = i.id
                JOIN usuarios u ON m.id_usuario = u.id";

        $params = [];
        if ($productId !== null) {
            $sql .= " WHERE m.id_producto = :id_producto";
            $params[':id_producto'] = $productId;
        }

        $sql .= " ORDER BY m.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
