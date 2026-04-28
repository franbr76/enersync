<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/veiculo.class.php'; // Incluir a nova classe Veiculo
include 'classes/funcoes.class.php';

// Se não estiver logado, volta para o login
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

// Carrega usuário logado
$usuario = new Usuario();
$usuario->setUsuario($_SESSION['logado']);

$veiculo = new Veiculo(); // Instanciar a classe Veiculo
$fn = new Funcoes();

// Verifica permissão (somente admin pode ver a gestão)
if ($usuario->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}

?>
<h1 class="titulo">Gestão de Veículos</h1>

<div style="margin-bottom: 20px;">
    

<div class="d-flex  justify-content-start py-1">
    <button class="btn btn-success d-inline-flex align-items-center" type="button"
        onclick="window.location.href ='adicionarVeiculo.php'">
        Adicionar Veiculo

    </button>

</div>

<table class="table table-dark table-striped">

    <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>MARCA</th>
            <th>MODELO</th>
            <th>AUTONOMIA (km)</th>
            <th>CAPACIDADE (kWh)</th>
            <th>EFICIÊNCIA (km/kWh)</th>
            <th>DESGASTE (%)</th>
            <th>ANO</th>
            <th>AÇÕES</th>
        </tr>
    </thead>
    <tbody>
        <?php

        $lista = $veiculo->listar(); // Chamar o método listar() da classe Veiculo
        
        foreach ($lista as $item):
            ?>
            <tr class="linha">
                <td><?php echo $item['id_veiculo']; ?></td>
                <td><?php echo $item['marca']; ?></td>
                <td><?php echo $item['modelo']; ?></td>
                <td><?php echo $item['autonomia_km']; ?></td>
                <td><?php echo $item['capacidade_bateria_kwh']; ?></td>
                <td><?php echo $item['eficiencia_km_por_kwh']; ?></td>
                <td><?php echo $item['desgaste_percentual']; ?></td>
                <td><?php echo $item['ano']; ?></td>

                <td>
                   <div class="d-flex gap-2">
                        <button class="btn btn-primary d-inline-flex align-items-center" type="button"
                            onclick="window.location.href ='editarVeiculo.php?id=<?php echo $item['id_veiculo']; ?>'">
                            EDITAR

                        </button>



                        <button class="btn btn-danger d-inline-flex align-items-center" type="button"
                            onclick="abrirModalConfirmacao(
  'Excluir Veículo',
  'Tem certeza que deseja excluir este veículo?',
  () => window.location.href = 'excluirVeiculo.php?id=<?php echo $item['id_veiculo']; ?>'
)">
EXCLUIR

                        </button>

                    </div>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'inc/footer.inc.php'; ?>