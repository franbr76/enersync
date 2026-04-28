<?php
session_start();
include 'inc/header.inc.php';

include 'classes/veiculo.class.php';

if (!isset($_SESSION['logado'])) {
    header('Location: login.php');
    exit;
}

$veiculo = new Veiculo();
$lista = $veiculo->listar();

$v1 = null;
$v2 = null;
$comparar = false;

if (isset($_POST['veiculo1']) && isset($_POST['veiculo2'])) {
    if ($_POST['veiculo1'] != $_POST['veiculo2']) {
        $v1 = $veiculo->buscar($_POST['veiculo1']);
        $v2 = $veiculo->buscar($_POST['veiculo2']);
        $comparar = true;
    } else {
        echo "<script>alert('Selecione veículos diferentes!');</script>";
    }
}
?>

<style>
    .card-comparar {
        background: var(--bs-body-bg);
        color: var(--bs-body-color);
        border-radius: 20px;
        padding: 25px;
        transition: 0.3s;
        border: 1px solid var(--bs-border-color);
    }

    .card-comparar:hover {
        transform: scale(1.02);
    }

    .select {
        background: var(--bs-secondary-bg);
        color: var(--bs-body-color);
        border: 1px solid var(--bs-border-color);
        border-radius: 12px;
        padding: 10px;
    }

    .title {
        font-size: 20px;
        font-weight: 600;
    }

    .value {
        font-size: 18px;
    }

    .win {
        color: #30d158;
        font-weight: bold;
    }
</style>

<h1 class="mb-4">Comparar Veículos</h1>

<form method="POST" class="mb-5">

    <div class="row g-3">

        <div class="col-md-5">
            <select name="veiculo1" class="form-control select" required>
                <option value="">Selecione o Veículo 1</option>
                <?php foreach ($lista as $item): ?>
                    <option value="<?= $item['id_veiculo'] ?>">
                        <?= $item['marca'] . " " . $item['modelo'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-5">
            <select name="veiculo2" class="form-control select" required>
                <option value="">Selecione o Veículo 2</option>
                <?php foreach ($lista as $item): ?>
                    <option value="<?= $item['id_veiculo'] ?>">
                        <?= $item['marca'] . " " . $item['modelo'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-light w-100">Comparar</button>
        </div>

    </div>

</form>

<?php if ($comparar): ?>

    <div class="row g-4">

        <!-- VEÍCULO 1 -->
        <div class="col-md-6">
            <div class="card-comparar">

                <div class="title mb-3">
                    <?= $v1['marca'] . " " . $v1['modelo'] ?>
                </div>

                <p class="value">
                    Autonomia:
                    <span class="<?= ($v1['autonomia_km'] > $v2['autonomia_km']) ? 'win' : '' ?>">
                        <?= $v1['autonomia_km'] ?> km
                    </span>
                </p>

                <p class="value">
                    Bateria:
                    <span class="<?= ($v1['capacidade_bateria_kwh'] > $v2['capacidade_bateria_kwh']) ? 'win' : '' ?>">
                        <?= $v1['capacidade_bateria_kwh'] ?> kWh
                    </span>
                </p>

                <p class="value">
                    Eficiência:
                    <span class="<?= ($v1['eficiencia_km_por_kwh'] > $v2['eficiencia_km_por_kwh']) ? 'win' : '' ?>">
                        <?= $v1['eficiencia_km_por_kwh'] ?> km/kWh
                    </span>
                </p>

                <p class="value">
                    Desgaste:
                    <span class="<?= ($v1['desgaste_percentual'] < $v2['desgaste_percentual']) ? 'win' : '' ?>">
                        <?= $v1['desgaste_percentual'] ?>%
                    </span>
                </p>

                <p class="value">
                    Ano:
                    <span class="<?= ($v1['ano'] > $v2['ano']) ? 'win' : '' ?>">
                        <?= $v1['ano'] ?>
                    </span>
                </p>

            </div>
        </div>

        <!-- VEÍCULO 2 -->
        <div class="col-md-6">
            <div class="card-comparar">

                <div class="title mb-3">
                    <?= $v2['marca'] . " " . $v2['modelo'] ?>
                </div>

                <p class="value">
                    Autonomia:
                    <span class="<?= ($v2['autonomia_km'] > $v1['autonomia_km']) ? 'win' : '' ?>">
                        <?= $v2['autonomia_km'] ?> km
                    </span>
                </p>

                <p class="value">
                    Bateria:
                    <span class="<?= ($v2['capacidade_bateria_kwh'] > $v1['capacidade_bateria_kwh']) ? 'win' : '' ?>">
                        <?= $v2['capacidade_bateria_kwh'] ?> kWh
                    </span>
                </p>

                <p class="value">
                    Eficiência:
                    <span class="<?= ($v2['eficiencia_km_por_kwh'] > $v1['eficiencia_km_por_kwh']) ? 'win' : '' ?>">
                        <?= $v2['eficiencia_km_por_kwh'] ?> km/kWh
                    </span>
                </p>

                <p class="value">
                    Desgaste:
                    <span class="<?= ($v2['desgaste_percentual'] < $v1['desgaste_percentual']) ? 'win' : '' ?>">
                        <?= $v2['desgaste_percentual'] ?>%
                    </span>
                </p>

                <p class="value">
                    Ano:
                    <span class="<?= ($v2['ano'] > $v1['ano']) ? 'win' : '' ?>">
                        <?= $v2['ano'] ?>
                    </span>
                </p>

            </div>
        </div>

    </div>

<?php endif; ?>

<?php include 'inc/footer.inc.php'; ?>