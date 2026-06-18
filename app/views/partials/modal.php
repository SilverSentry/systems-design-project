<?php
/**
 * Partial de modal reutilizable
 * Variables esperadas:
 * - $modalId
 * - $modalTitle
 * - $modalBodyHtml
 * - $modalFooterHtml (opcional)
 * - $modalSize (opcional, por ejemplo "modal-lg")
 */
$modalId = $modalId ?? 'modalBase';
$modalTitle = $modalTitle ?? '';
$modalBodyHtml = $modalBodyHtml ?? '';
$modalFooterHtml = $modalFooterHtml ?? '<button type="button" class="btn btn-second" data-bs-dismiss="modal">Cerrar</button>';
$modalSize = isset($modalSize) ? trim($modalSize) : '';
?>
<div class="modal fade" id="<?= htmlspecialchars($modalId) ?>" tabindex="-1" aria-labelledby="<?= htmlspecialchars($modalId . 'Label') ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered <?= htmlspecialchars($modalSize) ?>">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="<?= htmlspecialchars($modalId . 'Label') ?>"><?= htmlspecialchars($modalTitle) ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <?= $modalBodyHtml ?>
            </div>
            <div class="modal-footer">
                <?= $modalFooterHtml ?>
            </div>
        </div>
    </div>
</div>
