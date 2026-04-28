<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/veiculo.class.php';
include 'classes/funcoes.class.php';

if (!isset($_SESSION['logado'])) {
  header('Location: login.php');
  exit;
}

$usuario = new Usuario();
$usuario->setUsuario($_SESSION['logado']);

$veiculo = new Veiculo();
$fn = new Funcoes();
?>

<style>
  /* Corrige largura dos inputs e select -- o important subscreve o style do arquivo geral */
  .form-control,
  .form-select {
    width: 100% !important; 
  }

  /* Padroniza tema escuro */
  .form-control,
  .form-select {
    background-color: #212529;
    color: #fff;
    border: 1px solid #6c757d;
  }

  .form-control::placeholder {
    color: #adb5bd;
  }

  .form-select option {
    background-color: #212529;
    color: #fff;
  }
</style>

<h1 class="titulo">Planejar Viagem</h1>

<div class="container-fluid">

  <!-- VEÍCULO -->
  <div class="mb-3" style="max-width: 400px;">
    <select class="form-select" name="tecnologias">
      <option value="" disabled selected>Selecione seu veículo</option>
      <option value="megane">Renault Megane E-Tech</option>
    </select>
  </div>

  <!-- BOTÃO -->
  <div class="mb-4">
    <button class="btn btn-success" type="button">
      Histórico de Viagens
    </button>
  </div>

  <!-- FORM -->
  <div class="row justify-content-center">
    <div class="col-12">

      <div class="card bg-dark text-light border-secondary mx-auto w-100" style="max-width: 1600px;">

        <div class="card-header bg-dark border-secondary">
          <h2 class="text-center mb-0">Dados da Rota</h2>
        </div>

        <div class="card-body">

          <form action="/planejar_viagem.php" method="POST">

            <div class="row g-4">

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Origem:</label>
                <input type="text" class="form-control" name="origem" placeholder="Digite o local de saída" required>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Destino:</label>
                <input type="text" class="form-control" name="destino" placeholder="Digite o destino" required>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Paradas (opcional):</label>
                <input type="text" class="form-control" name="paradas" placeholder="Ex: Restaurante">
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Bateria atual (%):</label>
                <input type="number" class="form-control" name="bateria" min="1" max="100" placeholder="Ex: 65" required>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Preferência de rota:</label>
                <select class="form-select" name="preferencia">
                  <option value="rapida">Mais rápida</option>
                  <option value="economica">Mais econômica</option>
                  <option value="equilibrada">Equilibrada</option>
                </select>
              </div>

              <div class="col-12 col-md-6 col-lg-4">
                <label class="form-label">Bateria mínima ao chegar (%):</label>
                <input type="number" class="form-control" name="bateria_min" min="1" max="100" placeholder="Ex: 20">
              </div>

            </div>

            <div class="form-check mt-4 mb-4">
              <input class="form-check-input" type="checkbox" name="carregador_rapido">
              <label class="form-check-label">
                Preferir carregadores rápidos
              </label>
            </div>

            <button type="submit" onclick="window.location.href = 'exemploViagem.php'" class="btn btn-success w-100">
              GERAR PLANEJAMENTO DE VIAGEM
            </button>

          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'inc/footer.inc.php'; ?>