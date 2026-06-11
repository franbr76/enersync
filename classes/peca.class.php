<?php
require_once 'conexao.class.php';

class Peca
{
    private $con;

    public function __construct()
    {
        $this->con = new Conexao();
    }

    // ---------------------------------------------------------
    // BUSCAR PEÇA
    // ---------------------------------------------------------
    public function buscar($id)
    {
        $sql = $this->con->conectar()->prepare("
            SELECT * FROM pecas
            WHERE id_peca = :id
        ");

        $sql->bindValue(":id", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return $sql->fetch(PDO::FETCH_ASSOC);
        }

        return false;
    }

    // ---------------------------------------------------------
    // ADICIONAR PEÇA
    // ---------------------------------------------------------
    public function adicionar(
        $nome,
        $descricao,
        $fabricante,
        $preco,
        $estoque,
        $imagem,
        $marca,
        $modelo
    ) {

        try {

            $sql = $this->con->conectar()->prepare("
                INSERT INTO pecas
                (
                    nome,
                    descricao,
                    fabricante,
                    preco,
                    estoque,
                    imagem
                )
                VALUES
                (
                    :nome,
                    :descricao,
                    :fabricante,
                    :preco,
                    :estoque,
                    :imagem
                )
            ");

            $sql->bindParam(":nome", $nome);
            $sql->bindParam(":descricao", $descricao);
            $sql->bindParam(":fabricante", $fabricante);
            $sql->bindParam(":preco", $preco);
            $sql->bindParam(":estoque", $estoque);
            $sql->bindParam(":imagem", $imagem);

            $sql->execute();

            $idPeca = $this->con
                ->conectar()
                ->lastInsertId();

            $sqlCompatibilidade = $this->con
                ->conectar()
                ->prepare("
                    INSERT INTO compatibilidade_peca
                    (
                        id_peca,
                        marca,
                        modelo
                    )
                    VALUES
                    (
                        :id_peca,
                        :marca,
                        :modelo
                    )
                ");

            $sqlCompatibilidade->bindParam(":id_peca", $idPeca);
            $sqlCompatibilidade->bindParam(":marca", $marca);
            $sqlCompatibilidade->bindParam(":modelo", $modelo);

            $sqlCompatibilidade->execute();

            return true;

        } catch (PDOException $ex) {

            return 'ERRO: ' . $ex->getMessage();

        }
    }

    // ---------------------------------------------------------
    // EDITAR PEÇA
    // ---------------------------------------------------------
    public function editar(
        $id_peca,
        $nome,
        $descricao,
        $fabricante,
        $preco,
        $estoque,
        $imagem
    ) {

        $sql = $this->con->conectar()->prepare("
            UPDATE pecas
            SET
                nome = :nome,
                descricao = :descricao,
                fabricante = :fabricante,
                preco = :preco,
                estoque = :estoque,
                imagem = :imagem
            WHERE id_peca = :id_peca
        ");

        $sql->bindParam(":id_peca", $id_peca);
        $sql->bindParam(":nome", $nome);
        $sql->bindParam(":descricao", $descricao);
        $sql->bindParam(":fabricante", $fabricante);
        $sql->bindParam(":preco", $preco);
        $sql->bindParam(":estoque", $estoque);
        $sql->bindParam(":imagem", $imagem);

        return $sql->execute();
    }

    // ---------------------------------------------------------
    // EXCLUIR PEÇA
    // ---------------------------------------------------------
    public function excluir($id)
    {
        $sql = $this->con->conectar()->prepare("
            DELETE FROM compatibilidade_peca
            WHERE id_peca = :id
        ");

        $sql->bindValue(":id", $id);
        $sql->execute();

        $sql = $this->con->conectar()->prepare("
            DELETE FROM pecas
            WHERE id_peca = :id
        ");

        $sql->bindValue(":id", $id);

        return $sql->execute();
    }

    // ---------------------------------------------------------
    // LISTAR PEÇAS
    // ---------------------------------------------------------
    public function listar()
    {
        $sql = $this->con->conectar()->prepare("
            SELECT *
            FROM pecas
            ORDER BY id_peca DESC
        ");

        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>