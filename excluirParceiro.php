<?php
include 'classes/parceiro.class.php';
$con = new Parceiro();

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $con->excluir($id);
    header("Location: gestaoParceiro.php");
} else {
    echo '<script type="text/javascript">alert("Erro ao excluir veiculo");</script>';
    header("Location: gestaoParceiro.php");
}