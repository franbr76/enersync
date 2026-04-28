let confirmAction = null;

function abrirModalConfirmacao(titulo, mensagem, callback) {
  document.getElementById("modalTitle").innerText = titulo;
  document.getElementById("modalBody").innerText = mensagem;

  confirmAction = callback;

  let modal = new bootstrap.Modal(document.getElementById('confirmModal'));
  modal.show();
}

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("confirmBtn").addEventListener("click", function () {
    if (confirmAction) confirmAction();

    let modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
    modal.hide();
  });
});