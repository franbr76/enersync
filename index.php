<?php

session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/funcoes.class.php';

// Se não estiver logado, volta para o login
// if (!isset($_SESSION['logado'])) {
//     $_SESSION['logado'] = $idUsuario;
//     header('Location: login.php');
//     exit;
// } //colando pra não precisar de login na primeira pagina

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

<!-- 
<h1 class="titulo">Bem vindo(a), <?php echo $info['nome']; ?></h1>


</div> -->


<style>
    .btn-ener {
        background-color: #57CC99;
        color: white;
        border: none;
    }

    .btn-ener:hover {
        background-color: #38a3a5;
    }
</style>



<div class="d-flex  gap-3 justify-content-start py-1">
    <?php if ($usuario->temPermissao("admin")): ?>
        <button class="btn btn-ener d-inline-flex align-items-center" type="button"
            onclick="window.location.href ='gestaoUsuario.php'">
            Gestão Usuário
        <?php endif; ?>
        <?php if ($usuario->temPermissao("admin")): ?>
            <button class="btn btn-ener d-inline-flex align-items-center" type="button"
                onclick="window.location.href ='gestaoVeiculo.php'">
                Gestão Veículo
            <?php endif; ?>
            <?php if ($usuario->temPermissao("admin")): ?>
                <button class="btn btn-ener d-inline-flex align-items-center" type="button"
                    onclick="window.location.href ='gestaoParceiro.php'">
                    Gestão Parceiro
                <?php endif; ?>

            </button>

</div>


<div class="px-4 pt-5 my-5 text-center border-bottom ">
    <h1 class="display-4 fw-bold text-body-emphasis">Seja bem-vindo, <?php echo $info['nome']; ?></h1>
    <div class="col-lg-10 mx-auto">
        <p class="lead mb-4">A EnerSync é a solução inteligente para quem dirige veículos elétricos e quer viajar com
            segurança e previsibilidade. Calculamos a melhor rota, as paradas ideais para recarga e o custo total da
            viagem — tudo com base na autonomia real da sua bateria. Mais do que um app, somos seu assistente de energia
            em cada quilômetro.</p>
        <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
            <button type="button" class="btn btn-ener btn-lg px-4 me-sm-3" onclick="window.location.href='planejarViagem.php'">Planejar viagem</button>
            <button type="button" class="btn btn-outline-secondary btn-lg px-4">Comparar veículos</button>
        </div>
    </div>

    <div class="container align-items-center ">
        <img src="./image/logo.png" class="img-fluid border rounded-3 shadow-lg mb-5 px-5"
            alt="Exemplo de rota planeja pela startup" width="700" height="500" loading="lazy">
    </div>

</div>

<?php include 'inc/footer.inc.php'; ?>