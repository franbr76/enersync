<?php
require_once 'conexao.class.php';

class Parceiro {
    private $id;
    private $nome_empresa;
    private $cnpj;
    private $contato;
    private $comissao;

    private $con;

    public function __construct() {
        $this->con = new Conexao();
    }

    // --------------------------------------------------------------------
    // VERIFICA SE O CNPJ JÁ EXISTE
    // --------------------------------------------------------------------
    public function existeCnpj($cnpj, $id_parceiro = 0) {
        $sql = $this->con->conectar()->prepare("
            SELECT id_parceiro FROM parceiros WHERE cnpj = :cnpj AND id_parceiro != :id_parceiro LIMIT 1
        ");
        $sql->bindValue(":cnpj", $cnpj);
        $sql->bindValue(":id_parceiro", $id_parceiro);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function adicionar($nome, $cnpj, $contato, $comissao) {
        if ($this->existeCnpj($cnpj)) {
            return 'CNPJ já cadastrado!';
        }
        
        try {
            $sql = $this->con->conectar()->prepare("
                INSERT INTO parceiros (nome_empresa, cnpj, contato, porcentagem_comissao)
                VALUES (:nome, :cnpj, :contato, :comissao)
            ");

            $sql->bindParam(":nome", $nome);
            $sql->bindParam(":cnpj", $cnpj);
            $sql->bindParam(":contato", $contato);
            $sql->bindParam(":comissao", $comissao);

            $sql->execute();
            return TRUE;

        } catch(PDOException $ex) {
            return 'ERRO: '.$ex->getMessage();
        }
    }

    public function listar() {
        try {
            $sql = $this->con->conectar()->prepare("SELECT * FROM parceiros");
            $sql->execute();
            return $sql->fetchAll();

        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }

    public function buscar($id) {
        try {
            $sql = $this->con->conectar()->prepare("SELECT * FROM parceiros WHERE id_parceiro = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            return ($sql->rowCount() > 0) ? $sql->fetch() : array();

        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }

    public function editar($id, $nome, $cnpj, $contato, $comissao) {
        if ($this->existeCnpj($cnpj, $id)) {
            return 'CNPJ já cadastrado por outro parceiro!';
        }
        
        try {
            $sql = $this->con->conectar()->prepare("
                UPDATE parceiros SET
                    nome_empresa = :nome,
                    cnpj = :cnpj,
                    contato = :contato,
                    porcentagem_comissao = :comissao
                WHERE id_parceiro = :id
            ");

            $sql->bindParam(":nome", $nome);
            $sql->bindParam(":cnpj", $cnpj);
            $sql->bindParam(":contato", $contato);
            $sql->bindParam(":comissao", $comissao);
            $sql->bindParam(":id", $id);

            $sql->execute();
            return TRUE;

        } catch(PDOException $ex){
            echo "ERRO: ".$ex->getMessage();
            return FALSE;
        }
    }

    public function excluir($id) {
        try {
            $sql = $this->con->conectar()->prepare("DELETE FROM parceiros WHERE id_parceiro = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }
}
