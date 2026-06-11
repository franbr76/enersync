<?php
session_start();
include 'inc/header.inc.php';

if (!isset($_POST['origem']) || !isset($_POST['destino'])) {
  echo "Dados da viagem não enviados.";
  exit;
}

$origem = $_POST['origem'];
$destino = $_POST['destino'];
$paradas = $_POST['paradas'] ?? '';
$bateria = $_POST['bateria'] ?? 100;
$preferencia = $_POST['preferencia'] ?? 'rapida';
$bateria_min = $_POST['bateria_min'] ?? 20;

function buscarCoordenadas($local)
{
  $local = urlencode($local);

  $url = "https://nominatim.openstreetmap.org/search?q={$local}&format=json&limit=1";

  $options = [
    "http" => [
      "header" => "User-Agent: MinhaStartupEV/1.0\r\n"
    ]
  ];

  $context = stream_context_create($options);

  $resposta = file_get_contents($url, false, $context);

  if (!$resposta) {
    return null;
  }

  $dados = json_decode($resposta, true);

  if (empty($dados)) {
    return null;
  }

  return [
    'lat' => $dados[0]['lat'],
    'lon' => $dados[0]['lon']
  ];
}

$coordOrigem = buscarCoordenadas($origem);
$coordDestino = buscarCoordenadas($destino);

if (!$coordOrigem || !$coordDestino) {
  echo "Não foi possível localizar origem ou destino.";
  exit;
}
?>

<link rel="stylesheet" href="leaflet/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />

<script src="leaflet/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>

<style>
  body {
    background-color: #121212;
    color: white;
  }

  #map {
    width: 100%;
    height: 700px;
    border-radius: 18px;
    overflow: hidden;
    margin-top: 20px;
    border: 1px solid #333;
  }

  .info-card {
    background: #1c1c1c;
    border: 1px solid #333;
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 20px;
  }

  .info-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .route-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-top: 20px;
  }

  .route-box {
    background: #212529;
    padding: 20px;
    border-radius: 16px;
    border: 1px solid #444;
  }

  .route-box h3 {
    font-size: 18px;
    margin-bottom: 10px;
  }

  .route-box p {
    font-size: 22px;
    margin: 0;
    color: #00ff88;
    font-weight: bold;
  }

  .leaflet-routing-container {
    background: #1c1c1c !important;
    color: white !important;
    border-radius: 12px;
  }

  .leaflet-routing-alt {
    background: #1c1c1c !important;
    color: white !important;
  }
</style>

<div class="container-fluid mt-4 mb-5">

  <div class="info-card">
    <div class="info-title">Resumo da Viagem</div>

    <div class="route-info">

      <div class="route-box">
        <h3>Origem</h3>
        <p><?php echo htmlspecialchars($origem); ?></p>
      </div>

      <div class="route-box">
        <h3>Destino</h3>
        <p><?php echo htmlspecialchars($destino); ?></p>
      </div>

      <div class="route-box">
        <h3>Bateria Atual</h3>
        <p><?php echo $bateria; ?>%</p>
      </div>

      <div class="route-box">
        <h3>Bateria Mínima</h3>
        <p><?php echo $bateria_min; ?>%</p>
      </div>

    </div>
  </div>

  <div class="map-container">
    <div id="map"></div>
  </div>

</div>

<script>

  const origem = L.latLng(
    <?php echo $coordOrigem['lat']; ?>,
    <?php echo $coordOrigem['lon']; ?>
  );

  const destino = L.latLng(
    <?php echo $coordDestino['lat']; ?>,
    <?php echo $coordDestino['lon']; ?>
  );

  const map = L.map('map').setView(origem, 7);

  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  }).addTo(map);

  L.marker(origem)
    .addTo(map)
    .bindPopup('Origem')
    .openPopup();

  L.marker(destino)
    .addTo(map)
    .bindPopup('Destino');

  const rota = L.Routing.control({

    waypoints: [
      origem,
      destino
    ],

    routeWhileDragging: false,

    draggableWaypoints: false,

    addWaypoints: false,

    showAlternatives: true,

    lineOptions: {
      styles: [{
        color: '#00ff88',
        opacity: 0.9,
        weight: 7
      }]
    },

    altLineOptions: {
      styles: [{
        color: '#666',
        opacity: 0.7,
        weight: 5
      }]
    },

    createMarker: function () {
      return null;
    }

  }).addTo(map);

  rota.on('routesfound', function (e) {

    const routes = e.routes;

    const summary = routes[0].summary;

    const distanciaKm = (summary.totalDistance / 1000).toFixed(1);

    const tempoMin = Math.round(summary.totalTime / 60);

    const bateriaAtual = <?php echo (int) $bateria; ?>;

    const consumoEstimado = distanciaKm * 0.18;

    const bateriaRestante = Math.max(
      0,
      bateriaAtual - consumoEstimado
    ).toFixed(0);

    const painel = document.createElement('div');

    painel.innerHTML = `

        <div class="route-info mt-4">

            <div class="route-box">
                <h3>Distância</h3>
                <p>${distanciaKm} km</p>
            </div>

            <div class="route-box">
                <h3>Tempo Estimado</h3>
                <p>${tempoMin} min</p>
            </div>

            <div class="route-box">
                <h3>Consumo Estimado</h3>
                <p>${consumoEstimado.toFixed(1)}%</p>
            </div>

            <div class="route-box">
                <h3>Bateria ao Chegar</h3>
                <p>${bateriaRestante}%</p>
            </div>

        </div>
    `;

    document.querySelector('.container-fluid').appendChild(painel);

  });

</script>

<?php include 'inc/footer.inc.php'; ?>