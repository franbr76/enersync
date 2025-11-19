<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/funcoes.class.php';

// Se não estiver logado, volta para o login
if (!isset($_SESSION['logado'])) {
    $_SESSION['logado'] = $idUsuario;
    header('Location: login.php');
    exit;
}

// Carrega usuário logado
$usuario = new Usuario();
$usuario->setUsuario($_SESSION['logado']);
$info = $usuario->buscar($_SESSION['logado']);
$fn = new Funcoes();

// Verifica permissão (somente admin pode ver a gestão)
// if ($usuario->getTipoUsuario() != "admin") {
//     echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
//     exit;
// }

?>
<h1 class="titulo">Bem vindo(a), <?php  echo $info['nome']; ?></h1>
    <a class="sair" href="sair.php">SAIR</a>
<div class="menu">
    <button class="menu-button"><a href="#">Planejar Viagem</a></button>
    <button class="menu-button"><a href="#">Comparar Veiculos</a></button>
    <button class="menu-button"><a href="#">Comprar Peças</a></button>
</div>

<div class="menu" style="gap: 100px;">
    
    <?php if($usuario->temPermissao("admin")): ?>
<button class="menu-button"><a href="gestaoUsuario.php">Gestão de Usuários</a></button>
<?php endif; ?>
    <?php if($usuario->temPermissao("admin")): ?>
<button class="menu-button"><a href="gestaoUsuario.php">Gestão de Veiculos</a></button>
<?php endif; ?>
</div>

<?php include 'inc/footer.inc.php'; ?>