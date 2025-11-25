<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/parceiro.class.php';

// --- VERIFICAÇÃO DE AUTENTICAÇÃO E PERMISSÃO ---
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuarioLogado = new Usuario();
$usuarioLogado->setUsuario($_SESSION['logado']);

// Verifica permissão (somente admin pode adicionar)
if ($usuarioLogado->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}
// ------------------------------------------------

$parceiro = new Parceiro();

// Processamento do formulário
if (isset($_POST['nome_empresa']) && !empty($_POST['nome_empresa'])) {
    
    // Captura e limpa os dados do formulário
    $nome_empresa = trim($_POST['nome_empresa']);
    $cnpj = trim($_POST['cnpj']);
    $contato = trim($_POST['contato']);
    $porcentagem_comissao = (float) trim($_POST['porcentagem_comissao']);
    
    // Chama o método adicionar da classe Parceiro
    $resultado = $parceiro->adicionar(
        $nome_empresa, 
        $cnpj, 
        $contato, 
        $porcentagem_comissao
    );

    if ($resultado === true) {
        header('Location: gestaoParceiro.php');
        exit;
    } else {
        // Exibe erro se a adição falhar
        echo '<span style="color: red; justify-content: center; font-size: 24px;">Erro ao adicionar parceiro: ' . $resultado . '</span>';
    }
}

?>

<button class="btnVoltar">
    <a href="gestaoParceiro.php">VOLTAR</a>
</button>

<h1>Cadastrar Parceiro</h1>

<div class="card-conteudo">
    <form method="POST">
        <div class="card" style="width: 100%;">

            Nome da Empresa <br>
            <input type="text" name="nome_empresa" placeholder="Digite o nome da empresa" required/> <br><br>

            CNPJ <br>
            <input type="text" name="cnpj" placeholder="Digite o CNPJ" required/> <br><br>

            Contato <br>
            <input type="text" name="contato" placeholder="Digite o contato (e-mail ou telefone)" required/> <br><br>

            Porcentagem de Comissão (%) <br>
            <input type="number" step="0.01" name="porcentagem_comissao" placeholder="Ex: 5.5" required/> <br><br>

            <input class="btnSubmit" type="submit" value="ADICIONAR" />

        </div>
    </form>
</div>

<?php
include 'inc/footer.inc.php';
?>
