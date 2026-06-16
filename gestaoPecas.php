<?php
session_start();
include 'inc/header.inc.php';

include 'classes/usuario.class.php';
include 'classes/peca.class.php';
include 'classes/funcoes.class.php';

if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$usuario = new Usuario();
$usuario->setUsuario($_SESSION['logado']);

$peca = new Peca();
$fn = new Funcoes();

if ($usuario->getTipoUsuario() != "admin") {
    echo '<script>
        alert("Você não tem permissão para acessar esta página!");
        window.location.href="index.php";
    </script>';
    exit;
}
?>

<h1 class="titulo">Gestão de Peças</h1>

<div style="margin-bottom: 20px;">

<div class="d-flex justify-content-start py-1">

    <button
        class="btn btn-success d-inline-flex align-items-center"
        type="button"
        onclick="window.location.href ='adicionarPeca.php'">

        Adicionar Peça

    </button>

</div>

<table class="table table-dark table-striped">

    <thead class="table-light">

        <tr>

            <th>ID</th>
            <th>NOME</th>
            <th>FABRICANTE</th>
            <th>PREÇO</th>
            <th>ESTOQUE</th>
            <th>AÇÕES</th>

        </tr>

    </thead>

    <tbody>

        <?php

        $lista = $peca->listar();

        foreach ($lista as $item):

        ?>

        <tr class="linha">

            <td><?php echo $item['id_peca']; ?></td>

            <td><?php echo $item['nome']; ?></td>

            <td><?php echo $item['fabricante']; ?></td>

            <td>
                R$
                <?php echo number_format($item['preco'], 2, ',', '.'); ?>
            </td>

            <td><?php echo $item['estoque']; ?></td>

            <td>

                <div class="d-flex gap-2">

                    <button
                        class="btn btn-primary d-inline-flex align-items-center"
                        type="button"
                        onclick="window.location.href ='editarPeca.php?id=<?php echo $item['id_peca']; ?>'">

                        EDITAR

                    </button>

                    <button
                        class="btn btn-danger d-inline-flex align-items-center"
                        type="button"

                        onclick="abrirModalConfirmacao(
                            'Excluir Peça',
                            'Tem certeza que deseja excluir esta peça?',
                            () => window.location.href = 'excluirPeca.php?id=<?php echo $item['id_peca']; ?>'
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