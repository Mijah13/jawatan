<?php
$this->title = "Terima Kasih";
?>

<div id="thanksModal" class="modal fade show" style="display: block;" tabindex="-1" aria-modal="true" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center">
      <div class="modal-header bg-success text-white justify-content-center">
        <h5 class="modal-title d-flex align-items-center gap-2 m-0">
          <span class="fs-4">&#10003;</span> <!-- Tik icon -->
          Diterima
        </h5>
      </div>
      <div class="modal-body">
        <p class="fs-4">Data anda telah diterima, terima kasih.</p>
      </div>
      <!-- <div class="modal-footer justify-content-center">
        <a href="<?= \yii\helpers\Url::to(['site/login']) ?>" class="btn btn-success">Kembali ke Laman Utama</a>
      </div> -->
    </div>
  </div>
</div>

<style>
.modal-backdrop {
    display: none !important;
}
</style>
