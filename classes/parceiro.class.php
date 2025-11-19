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

    public function adicionar($nome, $cnpj, $contato, $comissao) {
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

    public function deletar($id) {
        try {
            $sql = $this->con->conectar()->prepare("DELETE FROM parceiros WHERE id_parceiro = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }
}
