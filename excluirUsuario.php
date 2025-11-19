<?php
include 'classes/usuario.class.php';
$con = new Usuario();

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $con->excluir($id);
    header ("Location: gestaoUsuario.php");
} else {
    echo '<script type="text/javascript">alert("Erro ao excluir contato");</script>';
    header ("Location: gestaoUsuario.php");
}