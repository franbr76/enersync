<?php

include 'classes/peca.class.php';

$peca = new Peca();

if (!empty($_GET['id'])) {

    $id = (int) $_GET['id'];

    $peca->excluir($id);

    header("Location: gestaoPecas.php");

} else {

    echo '<script type="text/javascript">
        alert("Erro ao excluir peça");
    </script>';

    header("Location: gestaoPecas.php");

}
?>