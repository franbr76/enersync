<?php
require_once 'conexao.class.php';

class Veiculo {
    private $id;
    private $id_usuario;
    private $marca;
    private $modelo;
    private $autonomia_km;
    private $capacidade_bateria_kwh;
    private $eficiencia_km_por_kwh;
    private $desgaste_percentual;
    private $ano;

    private $con;

    public function __construct() {
        $this->con = new Conexao();
    }

    public function adicionar($id_usuario, $marca, $modelo, $autonomia, $capacidade, $eficiencia, $desgaste, $ano) {
        try {
            $sql = $this->con->conectar()->prepare("
                INSERT INTO veiculos (
                    id_usuario, marca, modelo, autonomia_km, capacidade_bateria_kwh,
                    eficiencia_km_por_kwh, desgaste_percentual, ano
                ) VALUES (
                    :id_usuario, :marca, :modelo, :autonomia, :capacidade,
                    :eficiencia, :desgaste, :ano
                )
            ");

            $sql->bindParam(":id_usuario", $id_usuario);
            $sql->bindParam(":marca", $marca);
            $sql->bindParam(":modelo", $modelo);
            $sql->bindParam(":autonomia", $autonomia);
            $sql->bindParam(":capacidade", $capacidade);
            $sql->bindParam(":eficiencia", $eficiencia);
            $sql->bindParam(":desgaste", $desgaste);
            $sql->bindParam(":ano", $ano);

            $sql->execute();
            return TRUE;

        } catch(PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    public function listar() {
        try {
            $sql = $this->con->conectar()->prepare("SELECT * FROM veiculos");
            $sql->execute();
            return $sql->fetchAll();
        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }

    public function buscar($id) {
        try {
            $sql = $this->con->conectar()->prepare("SELECT * FROM veiculos WHERE id_veiculo = :id");
            $sql->bindValue(":id", $id);
            $sql->execute();

            return ($sql->rowCount() > 0) ? $sql->fetch() : array();

        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }

    public function editar($id, $marca, $modelo, $autonomia, $capacidade, $eficiencia, $desgaste, $ano) {
        try {
            $sql = $this->con->conectar()->prepare("
                UPDATE veiculos SET
                    marca = :marca,
                    modelo = :modelo,
                    autonomia_km = :autonomia,
                    capacidade_bateria_kwh = :capacidade,
                    eficiencia_km_por_kwh = :eficiencia,
                    desgaste_percentual = :desgaste,
                    ano = :ano
                WHERE id_veiculo = :id
            ");

            $sql->bindParam(":marca", $marca);
            $sql->bindParam(":modelo", $modelo);
            $sql->bindParam(":autonomia", $autonomia);
            $sql->bindParam(":capacidade", $capacidade);
            $sql->bindParam(":eficiencia", $eficiencia);
            $sql->bindParam(":desgaste", $desgaste);
            $sql->bindParam(":ano", $ano);
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
            $sql = $this->con->conectar()->prepare("DELETE FROM veiculos WHERE id_veiculo = :id");
            $sql->bindValue(':id', $id);
            $sql->execute();
        } catch(PDOException $ex) {
            echo "ERRO: ".$ex->getMessage();
        }
    }
}
