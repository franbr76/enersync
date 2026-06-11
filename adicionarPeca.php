<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/veiculo.class.php';
include 'classes/peca.class.php';

if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>
        alert("Você não tem permissão para acessar esta página!");
        window.location.href="index.php";
    </script>';
    exit;
}

$veiculo = new Veiculo();
$listaVeiculos = $veiculo->listar();

$peca = new Peca();

if(isset($_POST['nome']) && !empty($_POST['nome'])){

    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $fabricante = trim($_POST['fabricante']);
    $preco = (float) $_POST['preco'];
    $estoque = (int) $_POST['estoque'];
    $imagem = trim($_POST['imagem']);

    $idVeiculo = (int) $_POST['veiculo'];

    $dadosVeiculo = $veiculo->buscar($idVeiculo);

    $resultado = $peca->adicionar(
        $nome,
        $descricao,
        $fabricante,
        $preco,
        $estoque,
        $imagem,
        $dadosVeiculo['marca'],
        $dadosVeiculo['modelo']
    );

    if($resultado === true){
        header('Location: gestaoPecas.php');
        exit;
    }else{
        echo '<div class="alert alert-danger">'.$resultado.'</div>';
    }
}
?>

<button
    class="btn btn-success d-inline-flex align-items-center"
    type="button"
    onclick="window.location.href='gestaoPecas.php'">

    VOLTAR

</button>

<h1>Cadastrar Peça</h1>

<div class="card-conteudo" style="display:flex; gap:30px;">

<form method="POST">

    <div class="card" style="width:43%;">

        Nome da Peça <br>
        <input
            type="text"
            name="nome"
            placeholder="Ex: Pastilha de Freio"
            required>

        <br><br>

        Fabricante <br>
        <input
            type="text"
            name="fabricante"
            placeholder="Ex: Bosch"
            required>

        <br><br>

        Preço <br>
        <input
            type="number"
            step="0.01"
            name="preco"
            placeholder="Ex: 299.90"
            required>

        <br><br>

        Estoque <br>
        <input
            type="number"
            name="estoque"
            placeholder="Ex: 15"
            required>

        <br><br>

        Veículo Compatível <br>

        <select name="veiculo" required>

            <option value="">
                Selecione
            </option>

            <?php foreach($listaVeiculos as $item): ?>

                <option value="<?= $item['id_veiculo'] ?>">

                    <?= $item['marca'] ?>
                    <?= $item['modelo'] ?>
                    (<?= $item['ano'] ?>)

                </option>

            <?php endforeach; ?>

        </select>

        <br><br>

    </div>

    <div class="card" style="width:43%;">

        Descrição <br>

        <textarea
            name="descricao"
            rows="6"
            required></textarea>

        <br><br>

        URL da Imagem <br>

        <input
            type="text"
            name="imagem"
            placeholder="https://site.com/imagem.jpg">

        <br><br>

        <input
            class="btnSubmit"
            type="submit"
            value="ADICIONAR PEÇA">

    </div>

</form>

</div>

<?php include 'inc/footer.inc.php'; ?>