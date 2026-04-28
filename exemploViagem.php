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

.card-custom {
  background-color: var(--bs-body-bg);
  color: var(--bs-body-color);
  border: 1px solid var(--bs-border-color);
}

.header-result {
  background-color: var(--bs-secondary-bg);
  color: var(--bs-body-color);
}

.info-list li {
  color: var(--bs-body-color);
}

.map-container {
  border-radius: 10px;
  overflow: hidden;
}
</style>

<h1 class="titulo">Resultado da Viagem</h1>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="mx-auto mb-4 p-4" style="max-width: 1600px;">
        <div class="header-result p-2 shadow">
            Seu Plano de viagem EnerSync - <b>Curitiba</b> para <b>Faculdade Senac</b>
        </div>
    </div>

    <!-- CARD PRINCIPAL -->
    <div class="row justify-content-center">
        <div class="col-12">

            <div class="card card-custom text-light mx-auto w-100 p-4" style="max-width: 1600px;">

                <div class="row g-4 align-items-center">

                    <!-- MAPA -->
                    <div class="col-12 col-lg-6">
                        <div class="map-container">
                            <iframe src="https://www.google.com/maps?q=Curitiba+PR+to+Ponta+Grossa&output=embed"
                                width="100%" height="400" style="border:0;" allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <!-- DADOS -->
                    <div class="col-12 col-lg-6">

                        <ul class="info-list">

                            <li><strong>Modelo do veículo escolhido:</strong> Renault Megane E-Tech</li>

                            <li><strong>Tempo total:</strong> 1h 46min</li>

                            <li><strong>Bateria:</strong>
                                <ul>
                                    <li>Inicial: 90%</li>
                                    <li>Após parada: 100%</li>
                                    <li>Final: aproximadamente 50%</li>
                                </ul>
                            </li>

                            <li><strong>Número de paradas necessárias:</strong> 1</li>

                            <li><strong>Valor total:</strong> R$ 4,43</li>

                        </ul>

                        <!-- BOTÕES -->
                        <div class="mt-4 d-flex gap-3">

                            <button class="btn btn-success w-100" onclick="window.location.href='planejarViagem.php'">
                                NOVA VIAGEM
                            </button>

                            <button class="btn btn-outline-secondary w-100">
                                SALVAR VIAGEM
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<?php include 'inc/footer.inc.php'; ?>