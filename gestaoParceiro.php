<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/parceiro.class.php'; // Incluir a classe Parceiro
include 'classes/funcoes.class.php';

// Se não estiver logado, volta para o login
if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

// Carrega usuário logado
$usuario = new Usuario();
$usuario->setUsuario($_SESSION['logado']);

$parceiro = new Parceiro(); // Instanciar a classe Parceiro
$fn = new Funcoes();

// Verifica permissão (somente admin pode ver a gestão)
if ($usuario->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}

?>
<h1 class="titulo">Gestão de Parceiros</h1>

<div style="margin-bottom: 20px;">
    <button><a href="adicionarParceiro.php">ADICIONAR PARCEIRO</a></button>
    <button><a href="index.php">INÍCIO</a></button>
    <a class="sair" href="sair.php">SAIR</a>
</div>

<table border="3" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOME DA EMPRESA</th>
            <th>CNPJ</th>
            <th>CONTATO</th>
            <th>COMISSÃO (%)</th>
            <th>AÇÕES</th>
        </tr>
    </thead>
    <tbody>
 <?php

    $lista = $parceiro->listar(); // Chamar o método listar() da classe Parceiro

    foreach ($lista as $item):
        ?>
            <tr class="linha">
                <td><?php echo $item['id_parceiro']; ?></td>
                <td><?php echo $item['nome_empresa']; ?></td>
                <td><?php echo $item['cnpj']; ?></td>
                <td><?php echo $item['contato']; ?></td>
                <td><?php echo $item['porcentagem_comissao']; ?></td>

                <td>
                    <a class="acoes" href="editarParceiro.php?id=<?php echo $item['id_parceiro']; ?>">EDITAR</a>

                    <a class="acoes" href="excluirParceiro.php?id=<?php echo $item['id_parceiro']; ?>"
                        onclick="return confirm('Tem certeza que deseja excluir este parceiro?');">
                        EXCLUIR
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'inc/footer.inc.php'; ?>
