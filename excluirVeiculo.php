<?php
include 'classes/veiculo.class.php';
$con = new Veiculo();

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $con->excluir($id);
    header("Location: gestaoVeiculo.php");
} else {
    echo '<script type="text/javascript">alert("Erro ao excluir veiculo");</script>';
    header("Location: gestaoVeiculo.php");
}