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


<div class="d-flex  justify-content-start py-1">
    <button class="btn btn-success d-inline-flex align-items-center" type="button"
        onclick="window.location.href ='adicionarParceiro.php'">
        Adicionar Parceiro

    </button>

</div>


<table class="table table-dark table-striped">

    <thead class="table-light">
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
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary d-inline-flex align-items-center" type="button"
                            onclick="window.location.href ='editarParceiro.php?id=<?php echo $item['id_parceiro']; ?>'">
                            EDITAR

                        </button>



                        <button class="btn btn-danger d-inline-flex align-items-center" type="button"
                            onclick="abrirModalConfirmacao(
  'Excluir Parceiro',
  'Tem certeza que deseja excluir este parceiro?',
  () => window.location.href = 'excluirParceiro.php?id=<?php echo $item['id_parceiro']; ?>'
)">
EXCLUIR

                        </button>

                    </div>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'inc/footer.inc.php'; ?>
