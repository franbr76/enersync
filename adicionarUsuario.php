<?php

require 'inc/header.inc.php';
require 'classes/usuario.class.php';

$usuario = new Usuario();

if (isset($_POST['email']) && !empty($_POST['email'])) {

    $nome = addslashes($_POST['nome']);
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);
    $tipoUsuario = addslashes($_POST['tipo_usuario']);


    $usuario->adicionar($nome, $email, $senha, $tipoUsuario);

    header('Location: gestaoUsuario.php');
    exit;
}

?>

<button class="btnVoltar">
    <a href="gestaoUsuario.php">VOLTAR</a>
</button>

<h1>Cadastrar Usuário</h1>

<div class="card-conteudo">
    <form method="POST">
        <div class="card" style="width: 100%;">

            Nome <br>
            <input type="text" name="nome" placeholder="Digite o nome" required /> <br><br>

            Email <br>
            <input type="email" name="email" placeholder="Digite o email" required /> <br><br>

            Senha <br>
            <input type="password" name="senha" placeholder="Digite a senha" required /> <br><br>

            Tipo de Usuário <br>
            <select name="tipo_usuario" required>
                <option value="admin">Administrador</option>
                <option value="usuario">Usuario</option>
            </select>
            <br><br>

            <input class="btnSubmit" type="submit" value="ADICIONAR" />

        </div>
    </form>
</div>

<?php
require 'inc/footer.inc.php';
?>