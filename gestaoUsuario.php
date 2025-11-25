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

$fn = new Funcoes();

// Verifica permissão (somente admin pode ver a gestão)
if ($usuario->getTipoUsuario() != "admin") {
    echo '<script>alert("Você não tem permissão para acessar esta página!"); window.location.href="index.php";</script>';
    exit;
}

?>
<h1 class="titulo">Usuários</h1>

<div style="margin-bottom: 20px;">
    <button><a href="adicionarUsuario.php">ADICIONAR</a></button>
    <button><a href="index.php">INÍCIO</a></button>
    <a class="sair" href="sair.php" onclick="return confirm('Tem certeza que deseja sair?');">SAIR</a>
</div>

<table border="3" width="100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>NOME</th>
            <th>EMAIL</th>
            <th>TIPO DE USUÁRIO</th>
            <th>DATA DE CADASTRO</th>
            <th>AÇÕES</th>
        </tr>
    </thead> 
    <tbody>
        <?php

        $lista = $usuario->listar();

        foreach ($lista as $item):
            ?>
            <tr class="linha">
                <td><?php echo $item['id_usuario']; ?></td>
                <td><?php echo $item['nome']; ?></td>
                <td><?php echo $item['email']; ?></td>
                <td><?php echo strtoupper($item['tipo_usuario']); ?></td>
                <td><?php echo $fn->formatarDataHora($item['data_cadastro']); ?></td>

                <td>
                    <a class="acoes" href="editarUsuario.php?id=<?php echo $item['id_usuario']; ?>">EDITAR</a>

                    <a class="acoes" href="excluirUsuario.php?id=<?php echo $item['id_usuario']; ?>"
                        onclick="return confirm('Tem certeza que deseja excluir este usuário?');">
                        EXCLUIR
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'inc/footer.inc.php'; ?>