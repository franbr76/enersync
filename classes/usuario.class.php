<?php
require_once 'conexao.class.php';
class Usuario
{

    private $id;
    private $nome;
    private $email;
    private $senhaHash;
    private $tipoUsuario;
    private $dataCadastro;

    private $con;

    public function __construct()
    {
        $this->con = new Conexao();
    }

    // --------------------------------------------------------------------
    // GETTERS
    // --------------------------------------------------------------------
    public function getTipoUsuario()
    {
        return $this->tipoUsuario;
    }

    // --------------------------------------------------------------------
    // SETA OS DADOS DO USUÁRIO LOGADO
    // --------------------------------------------------------------------
    public function setUsuario($id)
    {
        $this->id = $id;

        $sql = $this->con->conectar()->prepare("
            SELECT * FROM usuarios WHERE id_usuario = :id
        ");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            $dados = $sql->fetch(PDO::FETCH_ASSOC);

            $this->nome = $dados['nome'];
            $this->email = $dados['email'];
            $this->tipoUsuario = $dados['tipo_usuario'];
        }
    }

    // --------------------------------------------------------------------
    // BUSCA OS DADOS DE UM USUÁRIO PELO ID
    // --------------------------------------------------------------------
    public function buscar($id)
    {
        $sql = $this->con->conectar()->prepare("
            SELECT * FROM usuarios WHERE id_usuario = :id
        ");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return $sql->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // --------------------------------------------------------------------
    // VERIFICA SE O EMAIL JÁ EXISTE
    // --------------------------------------------------------------------
    public function existeEmail($email)
    {
        $sql = $this->con->conectar()->prepare("
            SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1
        ");
        $sql->bindValue(":email", $email);
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }


    // --------------------------------------------------------------------
    // ADICIONAR USUÁRIO
    // --------------------------------------------------------------------
    public function adicionar($nome, $email, $senha, $tipoUsuario)
    {

        if (count($this->existeEmail($email)) > 0) {
            return false; // email já existe
        }

        try {
            $this->nome = $nome;
            $this->email = $email;
            $this->senhaHash = md5($senha);
            $this->tipoUsuario = $tipoUsuario;

            $sql = $this->con->conectar()->prepare("
                INSERT INTO usuarios (nome, email, senha_hash, tipo_usuario, data_cadastro)
                VALUES (:nome, :email, :senhaHash, :tipoUsuario, NOW())
            ");

            $sql->bindParam(":nome", $this->nome);
            $sql->bindParam(":email", $this->email);
            $sql->bindParam(":senhaHash", $this->senhaHash);
            $sql->bindParam(":tipoUsuario", $this->tipoUsuario);

            $sql->execute();
            return true;

        } catch (PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    // --------------------------------------------------------------------
    // EDITAR USUÁRIO
    // --------------------------------------------------------------------
    public function editar($id, $nome, $email, $tipoUsuario, $novaSenha = null)
    {

        $campos = "nome = :nome, email = :email, tipo_usuario = :tipoUsuario";
        $params = [
            ":id" => $id,
            ":nome" => $nome,
            ":email" => $email,
            ":tipoUsuario" => $tipoUsuario // <-- Agora deve atualizar corretamente
        ];

        if (!empty($novaSenha)) {
            $senhaHash = md5($novaSenha);
            $campos .= ", senha_hash = :senhaHash";
            $params[":senhaHash"] = $senhaHash;
        }

        $sql = $this->con->conectar()->prepare("
        UPDATE usuarios 
        SET {$campos}
        WHERE id_usuario = :id
    ");

        return $sql->execute($params); // <-- Uso de array de parâmetros, mais seguro
    }


    // --------------------------------------------------------------------
    // EXCLUIR USUÁRIO
    // --------------------------------------------------------------------
    public function excluir($id)
    {
        $sql = $this->con->conectar()->prepare("
            DELETE FROM usuarios WHERE id_usuario = :id
        ");
        $sql->bindValue(":id", $id);

        return $sql->execute();
    }

    // --------------------------------------------------------------------
    // LISTAR TODOS OS USUÁRIOS
    // --------------------------------------------------------------------
    public function listar()
    {
        $sql = $this->con->conectar()->prepare("
            SELECT * FROM usuarios ORDER BY id_usuario DESC
        ");
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    // --------------------------------------------------------------------
    // LOGIN DO USUÁRIO
    // --------------------------------------------------------------------
    public function fazerLogin($email, $senha)
    {
        $senhaHash = $senha;

        $sql = $this->con->conectar()->prepare("
            SELECT id_usuario FROM usuarios 
            WHERE email = :email AND senha_hash = :senha_hash 
            LIMIT 1
        ");

        $sql->bindValue(":email", $email);
        $sql->bindValue(":senha_hash", $senhaHash);

        $sql->execute();

        if ($sql->rowCount() > 0) {
            $user = $sql->fetch(PDO::FETCH_ASSOC);
            return $user['id_usuario'];   // CORRETO
        }

        return false;
    }

    // --------------------------------------------------------------------
    // VERIFICA PERMISSÃO (TIPO DE USUÁRIO)
    // --------------------------------------------------------------------
    public function temPermissao($tipo)
    {
        return ($this->tipoUsuario === $tipo);
    }

}
?>