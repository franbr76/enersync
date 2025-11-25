<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/parceiro.class.php';
include 'classes/funcoes.class.php';

// --- VERIFICAÇÃO DE AUTENTICAÇÃO E PERMISSÃO ---
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

// Verifica permissão (somente admin pode editar)
if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}
// ------------------------------------------------

$parceiro = new Parceiro();
$id = 0;
$info = false;

// 1. CARREGA O PARCEIRO PARA EDIÇÃO
if (!empty($_GET['id'])) {
    $id = (int) $_GET['id'];
    $info = $parceiro->buscar($id);

    if (!$info) {
        // Parceiro não encontrado -> volta pra listagem
        header("Location: gestaoParceiro.php");
        exit;
    }
} else {
    // id não foi informado -> volta pra listagem
    header("Location: gestaoParceiro.php");
    exit;
}


// 2. PROCESSA O FORMULÁRIO DE EDIÇÃO
if (isset($_POST['nome_empresa']) && !empty($_POST['nome_empresa'])) {
    $id_parceiro = (int) $_POST['id_parceiro'];
    
    // Captura e limpa os dados do formulário
    $nome_empresa = trim($_POST['nome_empresa']);
    $cnpj = trim($_POST['cnpj']);
    $contato = trim($_POST['contato']);
    $porcentagem_comissao = (float) trim($_POST['porcentagem_comissao']);

    // Chama o método editar da classe Parceiro
    $resultado = $parceiro->editar(
        $id_parceiro, 
        $nome_empresa, 
        $cnpj, 
        $contato, 
        $porcentagem_comissao
    );
    
    if ($resultado === true) {
        // Redireciona para a página de gestão
        header('Location: gestaoParceiro.php');
        exit;
    } else {
        // Exibe erro se a edição falhar
        echo '<span style="color: red; justify-content: center; font-size: 24px;">Erro ao editar parceiro: ' . $resultado . '</span>';
    }
}

?>

<button class="btnVoltar">
    <a href="gestaoParceiro.php">VOLTAR</a>
</button>

<h1>Editar Parceiro</h1>

<div class="card-conteudo">
    <form method="POST">
        <div class="card" style="width: 100%;">

            <input type="hidden" name="id_parceiro" value="<?php echo $info['id_parceiro']; ?>">
            
            Nome da Empresa <br>
            <input type="text" name="nome_empresa" value="<?php echo $info['nome_empresa']; ?>" required/> <br><br>

            CNPJ <br>
            <input type="text" name="cnpj" value="<?php echo $info['cnpj']; ?>" required/> <br><br>

            Contato <br>
            <input type="text" name="contato" value="<?php echo $info['contato']; ?>" required/> <br><br>

            Porcentagem de Comissão (%) <br>
            <input type="number" step="0.01" name="porcentagem_comissao" value="<?php echo $info['porcentagem_comissao']; ?>" required/> <br><br>

            <input class="btnSubmit" type="submit" value="SALVAR" />
            <a href="gestaoParceiro.php" class="acoes" style="background-color: #5a6268;">VOLTAR</a>

        </div>
    </form>
</div>

<?php
include 'inc/footer.inc.php';
?>
