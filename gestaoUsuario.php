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


<h1 class="titulo">Gestão de Usuários</h1>


<div class="d-flex  justify-content-start py-1">
    <button class="btn btn-success d-inline-flex align-items-center" type="button"
        onclick="window.location.href ='adicionarUsuario.php'">
        Adicionar Usuário

    </button>

</div>


<table class="table table-dark table-striped">

    <thead class="table-light">
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
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary d-inline-flex align-items-center" type="button"
                            onclick="window.location.href ='editarUsuario.php?id=<?php echo $item['id_usuario']; ?>'">
                            EDITAR

                        </button>



                        <button class="btn btn-danger d-inline-flex align-items-center" type="button" onclick="abrirModalConfirmacao(
                 'Excluir Usuário',
             'Tem certeza que deseja excluir este usuário?',
                 () => window.location.href = 'excluirUsuario.php?id=<?php echo $item['id_usuario']; ?>'
                            )">
                            EXCLUIR

                        </button>




                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'inc/footer.inc.php'; ?>