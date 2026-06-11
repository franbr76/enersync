<?php
session_start();
include 'inc/header.inc.php';

include 'classes/conexao.class.php';
include 'classes/veiculo.class.php';

if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$veiculo = new Veiculo();
$lista = $veiculo->listar();

$pecas = [];
$veiculoSelecionado = null;

if (isset($_POST['veiculo'])) {

    $idVeiculo = intval($_POST['veiculo']);

    $veiculoSelecionado = $veiculo->buscar($idVeiculo);

    $conexao = new Conexao();
    $pdo = $conexao->conectar();

    $sql = "
        SELECT p.*
        FROM pecas p
        INNER JOIN compatibilidade_peca cp
            ON p.id_peca = cp.id_peca
        WHERE cp.marca = ?
        AND cp.modelo = ?
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $veiculoSelecionado['marca'],
        $veiculoSelecionado['modelo']
    ]);

    $pecas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<style>

.card-peca {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 20px;
    overflow: hidden;
    transition: .3s;
    height: 100%;
}

.card-peca:hover {
    transform: translateY(-5px);
}

.card-peca img {
    width: 100%;
    height: 220px;
    object-fit: cover;
}

.card-body-custom {
    padding: 20px;
}

.nome-peca {
    font-size: 18px;
    font-weight: 600;
}

.fabricante {
    color: gray;
    font-size: 14px;
}

.preco {
    font-size: 24px;
    font-weight: bold;
    color: #30d158;
}

.estoque {
    font-size: 14px;
}

.select {
    background: var(--bs-secondary-bg);
    color: var(--bs-body-color);
    border: 1px solid var(--bs-border-color);
    border-radius: 12px;
    padding: 10px;
}

.card-info {
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    border-radius: 20px;
    padding: 20px;
}

</style>

<div class="container">

    <h1 class="mb-2">Peças de Reposição</h1>

    <p class="text-secondary mb-4">
        Selecione um veículo para visualizar peças compatíveis.
    </p>

    <form method="POST" class="mb-5">

        <div class="row g-3">

            <div class="col-md-10">

                <select name="veiculo" class="form-control select" required>

                    <option value="">
                        Selecione um veículo
                    </option>

                    <?php foreach($lista as $item): ?>

                        <option
                            value="<?= $item['id_veiculo'] ?>"
                            <?= isset($_POST['veiculo']) && $_POST['veiculo'] == $item['id_veiculo'] ? 'selected' : '' ?>
                        >

                            <?= $item['marca'] ?>
                            <?= $item['modelo'] ?>
                            (<?= $item['ano'] ?>)

                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="col-md-2">

                <button class="btn btn-light w-110 me-2">
                    Buscar
                </button>

            </div>

        </div>

    </form>

    <?php if($veiculoSelecionado): ?>

        <div class="card-info mb-4">

            <h4>
                <?= $veiculoSelecionado['marca'] ?>
                <?= $veiculoSelecionado['modelo'] ?>
            </h4>

            <div class="row mt-3">

                <div class="col-md-3">
                    <strong>Autonomia</strong><br>
                    <?= $veiculoSelecionado['autonomia_km'] ?> km
                </div>

                <div class="col-md-3">
                    <strong>Bateria</strong><br>
                    <?= $veiculoSelecionado['capacidade_bateria_kwh'] ?> kWh
                </div>

                <div class="col-md-3">
                    <strong>Eficiência</strong><br>
                    <?= $veiculoSelecionado['eficiencia_km_por_kwh'] ?> km/kWh
                </div>

                <div class="col-md-3">
                    <strong>Desgaste</strong><br>
                    <?= $veiculoSelecionado['desgaste_percentual'] ?>%
                </div>

            </div>

        </div>

    <?php endif; ?>

    <div class="row g-4">

        <?php if(count($pecas) > 0): ?>

            <?php foreach($pecas as $peca): ?>

                <div class="col-md-4">

                    <div class="card-peca">

                        <?php if(!empty($peca['imagem'])): ?>

                            <img src="<?= $peca['imagem'] ?>">

                        <?php else: ?>

                            <img src="https://via.placeholder.com/600x400?text=Peça">

                        <?php endif; ?>

                        <div class="card-body-custom">

                            <div class="nome-peca">
                                <?= $peca['nome'] ?>
                            </div>

                            <div class="fabricante mb-2">
                                <?= $peca['fabricante'] ?>
                            </div>

                            <p style="text-align: justify;">
                                <?= $peca['descricao'] ?>
                            </p>

                            <div class="estoque mb-3">
                                Estoque: <?= $peca['estoque'] ?>
                            </div>

                            <div class="preco mb-3">
                                R$ <?= number_format($peca['preco'], 2, ',', '.') ?>
                            </div>

                            <button class="btn btn-success w-100">
                                Ir para o site de compra
                            </button>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php elseif($veiculoSelecionado): ?>

            <div class="col-12">

                <div class="alert alert-warning">

                    Nenhuma peça cadastrada para este veículo.

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>

<?php include 'inc/footer.inc.php'; ?>