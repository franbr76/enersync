<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/veiculo.class.php';
include 'classes/funcoes.class.php';

// --- VERIFICAÇÃO DE AUTENTICAÇÃO E PERMISSÃO ---
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

// Verifica permissão (somente admin pode editar veículos)
if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}
// ------------------------------------------------

$veiculo = new Veiculo();
$id = 0;
$info = false;

// 1. CARREGA O VEÍCULO PARA EDIÇÃO
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $info = $veiculo->buscar($id);

    if (!$info) {
        // veículo não encontrado -> volta pra listagem
        header("Location: gestaoVeiculo.php");
        exit;
    }
} else {
    // id não foi informado -> volta pra listagem
    header("Location: gestaoVeiculo.php");
    exit;
}


// 2. PROCESSA O FORMULÁRIO DE EDIÇÃO
if (isset($_POST['marca']) && !empty($_POST['marca'])) {
    $id_veiculo = (int) $_POST['id_veiculo'];

    // Captura e limpa os dados do formulário
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);
    $autonomia_km = (float) trim($_POST['autonomia_km']);
    $capacidade_bateria_kwh = (float) trim($_POST['capacidade_bateria_kwh']);
    $eficiencia_km_por_kwh = (float) trim($_POST['eficiencia_km_por_kwh']);
    $desgaste_percentual = (float) trim($_POST['desgaste_percentual']);
    $ano = (int) trim($_POST['ano']);

    // Chama o método editar da classe Veiculo
    $resultado = $veiculo->editar(
        $id_veiculo,
        $marca,
        $modelo,
        $autonomia_km,
        $capacidade_bateria_kwh,
        $eficiencia_km_por_kwh,
        $desgaste_percentual,
        $ano
    );

    if ($resultado === true) {
        // Redireciona para a página de gestão
        header('Location: gestaoVeiculo.php');
        exit;
    } else {
        // Exibe erro se a edição falhar
        echo '<span style="color: red; justify-content: center; font-size: 24px;">Erro ao editar veículo: ' . $resultado . '</span>';
    }
}

?>

<button class="btnVoltar">
    <a href="gestaoVeiculo.php">VOLTAR</a>
</button>

<h1>Editar Veículo</h1>

<div class="card-conteudo" style="display: flex;">
    <form method="POST">
        <div class="card" style="width: 43%;">

            <input type="hidden" name="id_veiculo" value="<?php echo $info['id_veiculo']; ?>">

            Marca <br>
            <input type="text" name="marca" value="<?php echo $info['marca']; ?>" required /> <br><br>

            Modelo <br>
            <input type="text" name="modelo" value="<?php echo $info['modelo']; ?>" required /> <br><br>

            Ano <br>
            <input type="number" name="ano" value="<?php echo $info['ano']; ?>" required /> <br><br>

            Autonomia (km) <br>
            <input type="number" step="0.01" name="autonomia_km" value="<?php echo $info['autonomia_km']; ?>"
                required /> <br><br>
        </div>
        <div class="card" style="width: 43%;">

            Capacidade Bateria (kWh) <br>
            <input type="number" step="0.01" name="capacidade_bateria_kwh"
                value="<?php echo $info['capacidade_bateria_kwh']; ?>" required /> <br><br>

            Eficiência (km/kWh) <br>
            <input type="number" step="0.01" name="eficiencia_km_por_kwh"
                value="<?php echo $info['eficiencia_km_por_kwh']; ?>" required /> <br><br>

            Desgaste (%) <br>
            <input type="number" step="0.01" name="desgaste_percentual"
                value="<?php echo $info['desgaste_percentual']; ?>" required /> <br><br>

            <input class="btnSubmit" type="submit" value="SALVAR" />
            <a href="gestaoVeiculo.php" class="acoes" style="background-color: #38A3A5;">VOLTAR</a>

        </div>
    </form>
</div>

<?php
include 'inc/footer.inc.php';
?>