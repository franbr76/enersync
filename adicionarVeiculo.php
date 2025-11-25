<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/veiculo.class.php';

// --- VERIFICAÇÃO DE AUTENTICAÇÃO E PERMISSÃO ---
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

// Verifica permissão (somente admin pode adicionar veículos)
if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}
// ------------------------------------------------

$veiculo = new Veiculo();

// Processamento do formulário
if (isset($_POST['marca']) && !empty($_POST['marca'])) {

    // Captura o ID do usuário logado
    $id_usuario = $_SESSION['logado'];

    // Captura e limpa os dados do formulário
    $marca = trim($_POST['marca']);
    $modelo = trim($_POST['modelo']);
    $autonomia_km = (float) trim($_POST['autonomia_km']);
    $capacidade_bateria_kwh = (float) trim($_POST['capacidade_bateria_kwh']);
    $eficiencia_km_por_kwh = (float) trim($_POST['eficiencia_km_por_kwh']);
    $desgaste_percentual = (float) trim($_POST['desgaste_percentual']);
    $ano = (int) trim($_POST['ano']);

    // Chama o método adicionar da classe Veiculo
    $resultado = $veiculo->adicionar(
        $id_usuario,
        $marca,
        $modelo,
        $autonomia_km,
        $capacidade_bateria_kwh,
        $eficiencia_km_por_kwh,
        $desgaste_percentual,
        $ano
    );

    if ($resultado === true) {
        header('Location: gestaoVeiculo.php');
        exit;
    } else {
        // Exibe erro se a adição falhar
        echo '<span style="color: red; justify-content: center; font-size: 24px;">Erro ao adicionar veículo: ' . $resultado . '</span>';
    }
}

?>

<button class="btnVoltar">
    <a href="gestaoVeiculo.php">VOLTAR</a>
</button>

<h1>Cadastrar Veículo</h1>

<div class="card-conteudo" style="display: flex; gap: 30px;">
    <form method="POST">
        <div class="card" style="width: 43%;">

            Marca <br>
            <input type="text" name="marca" placeholder="Ex: Tesla" required /> <br><br>

            Modelo <br>
            <input type="text" name="modelo" placeholder="Ex: Model 3" required /> <br><br>

            Ano <br>
            <input type="number" name="ano" placeholder="Ex: 2023" required /> <br><br>

            Autonomia (km) <br>
            <input type="number" step="0.01" name="autonomia_km" placeholder="Ex: 450.5" required /> <br><br>
        </div>
        <div class="card" style="width: 43%;">

            Capacidade Bateria (kWh) <br>
            <input type="number" step="0.01" name="capacidade_bateria_kwh" placeholder="Ex: 75.0" required /> <br><br>

            Eficiência (km/kWh) <br>
            <input type="number" step="0.01" name="eficiencia_km_por_kwh" placeholder="Ex: 6.0" required /> <br><br>

            Desgaste (%) <br>
            <input type="number" step="0.01" name="desgaste_percentual" placeholder="Ex: 5.5" required /> <br><br>

            <input class="btnSubmit" type="submit" value="ADICIONAR" />

        </div>
    </form>
</div>

<?php
include 'inc/footer.inc.php';
?>