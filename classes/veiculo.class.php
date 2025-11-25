<?php
require_once 'conexao.class.php';

class Veiculo
{

    private $con;

    public function __construct()
    {
        $this->con = new Conexao();
    }

    // --------------------------------------------------------------------
    // BUSCA OS DADOS DE UM VEÍCULO PELO ID
    // --------------------------------------------------------------------
    public function buscar($id)
    {
        $sql = $this->con->conectar()->prepare("
            SELECT * FROM veiculos WHERE id_veiculo = :id
        ");
        $sql->bindValue(":id", $id);
        $sql->execute();

        if ($sql->rowCount() > 0) {
            return $sql->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    // --------------------------------------------------------------------
    // ADICIONAR VEÍCULO
    // --------------------------------------------------------------------
    public function adicionar($id_usuario, $marca, $modelo, $autonomia_km, $capacidade_bateria_kwh, $eficiencia_km_por_kwh, $desgaste_percentual, $ano)
    {
        try {
            $sql = $this->con->conectar()->prepare("
                INSERT INTO veiculos (
                    id_usuario, marca, modelo, autonomia_km, capacidade_bateria_kwh,
                    eficiencia_km_por_kwh, desgaste_percentual, ano
                ) VALUES (
                    :id_usuario, :marca, :modelo, :autonomia_km, :capacidade_bateria_kwh,
                    :eficiencia_km_por_kwh, :desgaste_percentual, :ano
                )
            ");

            $sql->bindParam(":id_usuario", $id_usuario);
            $sql->bindParam(":marca", $marca);
            $sql->bindParam(":modelo", $modelo);
            $sql->bindParam(":autonomia_km", $autonomia_km);
            $sql->bindParam(":capacidade_bateria_kwh", $capacidade_bateria_kwh);
            $sql->bindParam(":eficiencia_km_por_kwh", $eficiencia_km_por_kwh);
            $sql->bindParam(":desgaste_percentual", $desgaste_percentual);
            $sql->bindParam(":ano", $ano);

            $sql->execute();
            return true;

        } catch (PDOException $ex) {
            return 'ERRO: ' . $ex->getMessage();
        }
    }

    // --------------------------------------------------------------------
    // EDITAR VEÍCULO
    // --------------------------------------------------------------------
    public function editar($id_veiculo, $marca, $modelo, $autonomia_km, $capacidade_bateria_kwh, $eficiencia_km_por_kwh, $desgaste_percentual, $ano)
    {

        $sql = $this->con->conectar()->prepare("
            UPDATE veiculos 
            SET 
                marca = :marca, 
                modelo = :modelo, 
                autonomia_km = :autonomia_km, 
                capacidade_bateria_kwh = :capacidade_bateria_kwh,
                eficiencia_km_por_kwh = :eficiencia_km_por_kwh,
                desgaste_percentual = :desgaste_percentual,
                ano = :ano
            WHERE id_veiculo = :id_veiculo
        ");

        $sql->bindParam(":id_veiculo", $id_veiculo);
        $sql->bindParam(":marca", $marca);
        $sql->bindParam(":modelo", $modelo);
        $sql->bindParam(":autonomia_km", $autonomia_km);
        $sql->bindParam(":capacidade_bateria_kwh", $capacidade_bateria_kwh);
        $sql->bindParam(":eficiencia_km_por_kwh", $eficiencia_km_por_kwh);
        $sql->bindParam(":desgaste_percentual", $desgaste_percentual);
        $sql->bindParam(":ano", $ano);

        return $sql->execute();
    }

    // --------------------------------------------------------------------
    // EXCLUIR VEÍCULO
    // --------------------------------------------------------------------
    public function excluir($id)
    {
        $sql = $this->con->conectar()->prepare("
            DELETE FROM veiculos WHERE id_veiculo = :id
        ");
        $sql->bindValue(":id", $id);

        return $sql->execute();
    }

    // --------------------------------------------------------------------
    // LISTAR TODOS OS VEÍCULOS
    // --------------------------------------------------------------------
    public function listar()
    {
        $sql = $this->con->conectar()->prepare("
            SELECT * FROM veiculos ORDER BY id_veiculo DESC
        ");
        $sql->execute();

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>