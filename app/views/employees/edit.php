<?php

/** @var array $user */
/** @var array $employee */

use App\Core\Paths;

require __DIR__ . '/../layouts/head.php';
?>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>

<div id="page-content-wrapper" class="w-100">

    <?php require __DIR__ . '/../layouts/navbar.php'; ?>

    <div class="col-12 col-md-8 mx-auto mt-4 animate-fadeIn">
        <!-- Tarjeta premium con sombra suave y esquinas controladas -->
        <div class="card shadow-sm border-0" style="border-radius: 12px; background: #ffffff;">

            <!-- Cabecera limpia y estilizada con tu color de marca -->
            <div class="card-header bg-transparent pt-4 pb-2 px-4" style="border-bottom: 1px solid rgba(194, 156, 85, 0.15);">
                <h2 class="mb-0 fw-bold" style="color: #1a1a1a; letter-spacing: 0.5px;">
                    <i class="bi bi-person-gear me-2"></i> Editar Empleado
                </h2>
                <small class="text-muted">Modifica las credenciales y el estado de los empleados.</small>
            </div>

            <div class="card-body p-4">
                <form method="post" action="<?= Paths::to('employees/update') ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($employee['id']) ?>">

                    <!-- Fila combinada para Nombre y Apellido -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">
                                <i class="bi bi-person me-1"></i> Nombre
                            </label>
                            <input type="text" name="nombre" class="form-control px-3 py-2" value="<?= htmlspecialchars($employee['nombre']) ?>" required style="border-radius: 8px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">
                                <i class="bi bi-person me-1"></i> Apellido
                            </label>
                            <input type="text" name="apellido" class="form-control px-3 py-2" value="<?= htmlspecialchars($employee['apellido']) ?>" required style="border-radius: 8px;">
                        </div>
                    </div>

                    <!-- Campo Email -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">
                            <i class="bi bi-envelope me-1"></i> Correo Electrónico
                        </label>
                        <input type="email" name="email" class="form-control px-3 py-2" value="<?= htmlspecialchars($employee['email']) ?>" required style="border-radius: 8px;">
                    </div>

                    <!-- Campo Estado (Select Estilizado) -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-secondary" style="font-size: 0.9rem;">
                            <i class="bi bi-toggle-on me-1"></i> Estado Laboral
                        </label>
                        <select name="estado" class="form-select px-3 py-2" style="border-radius: 8px;">
                            <option value="1" <?= intval($employee['id_estado'] ?? 1) === 1 ? 'selected' : '' ?>>Activo</option>
                            <option value="2" <?= intval($employee['id_estado'] ?? 1) === 2 ? 'selected' : '' ?>>Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones de Acción -->
                     <div class="row g-2 justify-content-end">
                        <div class="col-auto m-1">
                        <a href="<?= Paths::to('employees') ?>" class="btn btn-second btn-lg" style="border-radius: 8px;"><i class="bi bi-box-arrow-in-left"></i> Cancelar</a>
                        </div>
                        <div class="col-auto m-1">
                        <button type="submit" class="btn btn-golden-all btn-lg" style="border-radius: 8px; box-shadow: 0 4px 12px rgba(194, 156, 85, 0.2);">
                            <i class="bi bi-check-circle me-1"></i> Guardar cambios
                        </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <?php require __DIR__ . '/../layouts/footer.php'; ?>