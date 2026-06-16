<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/peca.class.php';

if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}

$peca = new Peca();
$info = false;

// CARREGA A PEÇA
if (!empty($_GET['id'])) {

    $id = (int) $_GET['id'];

    $info = $peca->buscar($id);

    if (!$info) {

        header("Location: gestaoPecas.php");
        exit;

    }

} else {

    header("Location: gestaoPecas.php");
    exit;

}

// PROCESSA EDIÇÃO
if (isset($_POST['nome']) && !empty($_POST['nome'])) {

    $id = (int) $_POST['id'];

    $nome = trim($_POST['nome']);
    $descricao = trim($_POST['descricao']);
    $fabricante = trim($_POST['fabricante']);
    $preco = (float) $_POST['preco'];
    $estoque = (int) $_POST['estoque'];
    $imagem = trim($_POST['imagem']);

    $peca->editar(
        $id,
        $nome,
        $descricao,
        $fabricante,
        $preco,
        $estoque,
        $imagem
    );

    header('Location: gestaoPecas.php');
    exit;
}

?>

<h1>EDITAR PEÇA</h1>

<div class="card-conteudo">

    <form method="POST">

        <div class="card" style="width: 100%;">

            <input
                type="hidden"
                name="id"
                value="<?php echo $info['id_peca']; ?>">

            Nome da Peça:<br>

            <input
                type="text"
                name="nome"
                value="<?php echo $info['nome']; ?>"
                required>

            <br><br>

            Fabricante:<br>

            <input
                type="text"
                name="fabricante"
                value="<?php echo $info['fabricante']; ?>"
                required>

            <br><br>

            Preço:<br>

            <input
                type="number"
                step="0.01"
                name="preco"
                value="<?php echo $info['preco']; ?>"
                required>

            <br><br>

            Estoque:<br>

            <input
                type="number"
                name="estoque"
                value="<?php echo $info['estoque']; ?>"
                required>

            <br><br>

            URL da Imagem:<br>

            <input
                type="text"
                name="imagem"
                value="<?php echo $info['imagem']; ?>">

            <br><br>

            Descrição:<br>

            <textarea
                name="descricao"
                rows="6"
                required><?php echo $info['descricao']; ?></textarea>

            <br><br>

            <input
                type="submit"
                value="SALVAR"
                class="btnSubmit">

            <a
                href="gestaoPecas.php"
                class="acoes"
                style="background-color:#38A3A5;">

                VOLTAR

            </a>

        </div>

    </form>

</div>

<?php include 'inc/footer.inc.php'; ?>