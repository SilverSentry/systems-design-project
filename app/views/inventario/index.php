<?php

/** @var array $products Arreglo de productos de inventario provisto por InventarioController */

require __DIR__ . '/../layouts/head.php';

use App\Core\Session;

$isEmployee = Session::isEmployee();
?>

<style>
  h1 {
    color: #000000c7 !important;
  }
</style>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

  <?php require __DIR__ . '/../layouts/navbar.php'; ?>

  <!-- Contenido principal -->
  <div class="col-12 col-md-11 mx-auto mt-4 animate-fadeIn">
    <div class="card shadow border-0">
      <div class="card-header py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
        <h1 class="card-title mb-0 fw-bold d-flex align-items-center gap-2">
          <i class="bi bi-box-seam text-golden"></i> Inventario de Productos
        </h1>
        <div class="d-flex flex-wrap gap-2">
          <button type="button" class="btn btn-second btn-lg d-flex align-items-end gap-2" data-bs-toggle="modal" data-bs-target="#modalHistory" id="btn-open-history">
            <i class="bi bi-clock-history"></i> Historial
          </button>
          <button type="button" class="btn btn-golden-all btn-lg d-flex align-items-end gap-2" data-bs-toggle="modal" data-bs-target="#modalMovement">
            <i class="bi bi-arrow-left-right"></i> Registrar Movimiento
          </button>
          <?php if (!$isEmployee): ?>
            <button type="button" class="btn btn-golden-all btn-lg d-flex align-items-end gap-2" data-bs-toggle="modal" data-bs-target="#modalAddProduct">
              <i class="bi bi-plus-lg"></i> Agregar Producto
            </button>
          <?php endif; ?>
        </div>
      </div>

      <div class="card-body p-4">
        <div class="table-responsive">
          <table id="tabla-inventario" class="table table-striped table-hover align-middle" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Descripción</th>
                <th>Precio Compra</th>
                <th>Stock Mín.</th>
                <th>Stock Actual</th>
                <th>Estado Stock</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $prod):
                $isLowStock = intval($prod['sotck_actual']) <= intval($prod['stock_minimo']);
                $rowClass = $isLowStock ? 'row-stock-bajo' : '';
                $stockClass = $isLowStock ? 'cell-stock-bajo' : '';
              ?>
                <tr class="<?= $rowClass ?>" id="product-row-<?= $prod['id'] ?>">
                  <td><?= htmlspecialchars($prod['id']) ?></td>
                  <td class="fw-semibold text-dark"><?= htmlspecialchars(ucfirst($prod['nombre'])) ?></td>
                  <td class="text-secondary small"><?= htmlspecialchars($prod['descripcion']) ?: '<em class="text-muted">Sin descripción</em>' ?></td>
                  <td>$<?= number_format($prod['precio_compra'], 2, ',', '.') ?></td>
                  <td><?= htmlspecialchars($prod['stock_minimo']) ?></td>
                  <td class="<?= $stockClass ?>"><?= htmlspecialchars($prod['sotck_actual']) ?></td>
                  <td>
                    <?php if ($isLowStock): ?>
                      <span class="badge rounded-pill bg-warning">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Stock Bajo
                      </span>
                    <?php else: ?>
                      <span class="badge rounded-pill text-bg-success">
                        <i class="bi bi-check-circle-fill me-1"></i> Suficiente
                      </span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if (!$isEmployee): ?>
                      <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm btn-edit-product d-flex align-items-center gap-1"
                          data-id="<?= $prod['id'] ?>"
                          data-nombre="<?= htmlspecialchars($prod['nombre'], ENT_QUOTES) ?>"
                          data-descripcion="<?= htmlspecialchars($prod['descripcion'], ENT_QUOTES) ?>"
                          data-sotck_actual="<?= $prod['sotck_actual'] ?>"
                          data-stock_minimo="<?= $prod['stock_minimo'] ?>"
                          data-precio_compra="<?= $prod['precio_compra'] ?>">
                          <i class="bi bi-pencil-square"></i> Editar
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete-product d-flex align-items-center gap-1"
                          data-id="<?= $prod['id'] ?>"
                          data-nombre="<?= htmlspecialchars($prod['nombre'], ENT_QUOTES) ?>">
                          <i class="bi bi-trash-fill"></i> Eliminar
                        </button>
                      </div>
                    <?php else: ?>
                      <span class="text-muted small">Sin permisos</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- MODAL AGREGAR PRODUCTO -->
<?php
$modalId = 'modalAddProduct';
$modalTitle = 'Agregar Nuevo Producto al Inventario';
ob_start();
?>
<form id="formAddProduct" class="needs-validation" novalidate>
  <div class="mb-3">
    <label for="add_nombre" class="form-label fw-semibold">Nombre del Producto</label>
    <input type="text" class="form-control" id="add_nombre" name="nombre" required placeholder="Ej. Toallas de microfibra, Ampollas de colágeno...">
    <div class="invalid-feedback">Por favor ingrese el nombre del producto.</div>
  </div>
  <div class="mb-3">
    <label for="add_descripcion" class="form-label fw-semibold">Descripción</label>
    <textarea class="form-control" id="add_descripcion" name="descripcion" rows="3" placeholder="Detalles de presentación, uso o especificaciones..."></textarea>
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="add_sotck_actual" class="form-label fw-semibold">Stock Inicial</label>
      <input type="number" class="form-control" id="add_sotck_actual" name="sotck_actual" min="0" required value="0">
      <div class="invalid-feedback">Ingrese un valor de stock inicial (mínimo 0).</div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="add_stock_minimo" class="form-label fw-semibold">Stock Mínimo (Alerta)</label>
      <input type="number" class="form-control" id="add_stock_minimo" name="stock_minimo" min="0" required value="5">
      <div class="invalid-feedback">Ingrese un stock mínimo (mínimo 0).</div>
    </div>
  </div>
  <div class="mb-3">
    <label for="add_precio_compra" class="form-label fw-semibold">Precio de Compra ($)</label>
    <input type="number" class="form-control" id="add_precio_compra" name="precio_compra" min="0" step="0.01" required placeholder="0.00">
    <div class="invalid-feedback">Ingrese un precio de compra válido (mayor o igual a 0).</div>
  </div>
</form>
<?php
$modalBodyHtml = ob_get_clean();
$modalFooterHtml = '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cancelar</button>'
  . '<button type="submit" form="formAddProduct" class="btn btn-golden btn-golden-all">Guardar Producto</button>';
$modalSize = '';
require __DIR__ . '/../partials/modal.php';
?>

<!-- MODAL EDITAR PRODUCTO -->
<?php
$modalId = 'modalEditProduct';
$modalTitle = 'Modificar Detalles del Producto';
ob_start();
?>
<form id="formEditProduct" class="needs-validation" novalidate>
  <input type="hidden" id="edit_id" name="id">
  <div class="mb-3">
    <label for="edit_nombre" class="form-label fw-semibold">Nombre del Producto</label>
    <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
    <div class="invalid-feedback">Por favor ingrese el nombre del producto.</div>
  </div>
  <div class="mb-3">
    <label for="edit_descripcion" class="form-label fw-semibold">Descripción</label>
    <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label for="edit_sotck_actual" class="form-label fw-semibold">Stock Actual</label>
      <input type="number" class="form-control" id="edit_sotck_actual" name="sotck_actual" min="0" required>
      <div class="invalid-feedback">Ingrese el stock actual.</div>
    </div>
    <div class="col-md-6 mb-3">
      <label for="edit_stock_minimo" class="form-label fw-semibold">Stock Mínimo (Alerta)</label>
      <input type="number" class="form-control" id="edit_stock_minimo" name="stock_minimo" min="0" required>
      <div class="invalid-feedback">Ingrese el stock mínimo.</div>
    </div>
  </div>
  <div class="mb-3">
    <label for="edit_precio_compra" class="form-label fw-semibold">Precio de Compra ($)></label>
    <input type="number" class="form-control" id="edit_precio_compra" name="precio_compra" min="0" step="0.01" required>
    <div class="invalid-feedback">Ingrese un precio de compra válido.</div>
  </div>
</form>

<?php
$modalBodyHtml = ob_get_clean();
$modalFooterHtml = '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cancelar</button>'
  . '<button type="submit" form="formEditProduct" class="btn btn-golden btn-golden-all">Guardar Cambios</button>';
$modalSize = '';
require __DIR__ . '/../partials/modal.php';
?>

<!-- MODAL REGISTRAR MOVIMIENTO -->
<?php
$modalId = 'modalMovement';
$modalTitle = 'Registrar Ajuste o Movimiento de Stock';
ob_start();
?>
<form id="formMovement" class="needs-validation" novalidate>
  <div class="mb-3">
    <label for="mov_id_producto" class="form-label fw-semibold">Producto</label>
    <select class="form-select" id="mov_id_producto" name="id_producto" required>
      <option value="" selected disabled>Seleccione el producto...</option>
      <?php foreach ($products as $prod): ?>
        <option value="<?= $prod['id'] ?>" data-stock="<?= $prod['sotck_actual'] ?>">
          <?= htmlspecialchars($prod['nombre']) ?> (Stock actual: <?= $prod['sotck_actual'] ?>)
        </option>
      <?php endforeach; ?>
    </select>
    <div class="invalid-feedback">Debe seleccionar un producto.</div>
  </div>

  <div class="mb-3">
    <label class="form-label fw-semibold d-block">Tipo de Ajuste</label>
    <div class="form-check form-check-inline me-4">
      <input class="form-check-input" type="radio" name="tipo_movimiento" id="mov_tipo_entrada" value="entrada" checked required>
      <label class="form-check-label text-success fw-bold d-flex align-items-center gap-1" for="mov_tipo_entrada">
        <i class="bi bi-box-arrow-in-down fs-5"></i> Entrada (Aumentar stock)
      </label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="tipo_movimiento" id="mov_tipo_salida" value="salida" required>
      <label class="form-check-label text-danger fw-bold d-flex align-items-center gap-1" for="mov_tipo_salida">
        <i class="bi bi-box-arrow-up-right fs-5"></i> Salida (Disminuir stock)
      </label>
    </div>
  </div>

  <div class="mb-3">
    <label for="mov_cantidad" class="form-label fw-semibold">Cantidad</label>
    <input type="number" class="form-control" id="mov_cantidad" name="cantidad" min="1" required value="1">
    <div class="invalid-feedback">Ingrese una cantidad mayor o igual a 1.</div>
  </div>

  <div class="mb-3">
    <label for="mov_motivo" class="form-label fw-semibold">Motivo / Notas</label>
    <input type="text" class="form-control" id="mov_motivo" name="motivo" required placeholder="Ej. Compra de inventario, Uso en servicio facial, Rotura...">
    <div class="invalid-feedback">Indique un motivo para el ajuste.</div>
  </div>
</form>
<?php
$modalBodyHtml = ob_get_clean();
$modalFooterHtml = '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cancelar</button>'
  . '<button type="submit" form="formMovement" class="btn btn-golden btn-golden-all">Aplicar Ajuste</button>';
$modalSize = '';
require __DIR__ . '/../partials/modal.php';
?>

<!-- MODAL HISTORIAL DE MOVIMIENTOS -->
<?php
$modalId = 'modalHistory';
$modalTitle = 'Bitácora Histórica de Ajustes y Movimientos';
ob_start();
?>
<div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
  <table id="tabla-historial-movimientos" class="table table-striped table-hover align-middle" style="width:100%">
    <thead class="table-dark">
      <tr>
        <th>Fecha y Hora</th>
        <th>Producto</th>
        <th>Tipo</th>
        <th>Cantidad</th>
        <th>Motivo / Notas</th>
        <th>Responsable</th>
      </tr>
    </thead>
    <tbody id="body-historial-movimientos">
      <tr class="loader-row">
        <td colspan="6" class="text-center py-4">
          <div class="spinner-border text-golden" role="status">
            <span class="visually-hidden">Cargando historial...</span>
          </div>
        </td>
      </tr>
    </tbody>
  </table>
</div>
<?php
$modalBodyHtml = ob_get_clean();
$modalFooterHtml = '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cerrar</button>';
$modalSize = 'modal-lg';
require __DIR__ . '/../partials/modal.php';
?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>