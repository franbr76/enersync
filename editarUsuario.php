<?php
session_start();
include 'inc/header.inc.php';
include 'classes/usuario.class.php';

// --- VERIFICAÇÃO DE AUTENTICAÇÃO E PERMISSÃO ---
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

// Verifica permissão (somente admin pode ver a gestão)
if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}
// ------------------------------------------------

$usuario = new Usuario();
// $id = 0;
$info = false;

// 1. CARREGA O USUÁRIO PARA EDIÇÃO
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $info = $usuario->buscar($id);

    if (!$info) {
        // usuário não encontrado -> volta pra listagem
        header("Location: gestaoUsuario.php");
        exit;
    }
} else {
    // id não foi informado -> volta pra listagem
    header("Location: gestaoUsuario.php");
    exit;
}


// 2. PROCESSA O FORMULÁRIO DE EDIÇÃO
if (isset($_POST['email']) && !empty($_POST['email'])) {
    $id = (int) $_POST['id'];
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $tipoUsuario = trim($_POST['tipo_usuario']);
    $novaSenha = isset($_POST['senha']) ? trim($_POST['senha']) : '';

    // O método editar foi adaptado para receber a nova senha (opcional)
    $usuario->editar($id, $nome, $email, $tipoUsuario, $novaSenha);

    // Redireciona para a página de gestão (index.php)
    header('Location: gestaoUsuario.php');
    exit;
}

// Tipos de usuário disponíveis no seu projeto (baseado na sua tabela)
$tiposUsuarioDisponiveis = ['admin', 'usuario'];

?>

<h1>EDITAR USUÁRIO</h1>
<div class="card-conteudo">
    <form method="POST">

        <div class="card" style="width: 100%;">
            <input type="hidden" name="id" value="<?php echo $info['id_usuario']; ?>">


            Nome: <br>
            <input type="text" name="nome" value="<?php echo $info['nome']; ?>" required /> <br><br>

            Email: <br>
            <input type="email" name="email" value="<?php echo $info['email']; ?>" required /> <br><br>

            Senha: <br>
            <input type="password" name="senha" value="" placeholder="Deixe vazio para manter" /> <br><br>


            Tipo de Usuário:

            <?php foreach ($tiposUsuarioDisponiveis as $tipo): ?>
                <label>
                    <input type="radio" name="tipo_usuario" value="<?php echo $tipo; ?>" <?php echo ($info['tipo_usuario'] == $tipo) ? 'checked' : ''; ?> required>
                    <?php echo ucfirst($tipo); ?>
                </label>

            <?php endforeach; ?>

            <br><br>


            <input type="submit" value="SALVAR" class="btnSubmit" />
            <a href="index.php" class="acoes" style="background-color: #38A3A5;">VOLTAR</a>
        </div>
    </form>
</div>


<?php
include 'inc/footer.inc.php';
?>