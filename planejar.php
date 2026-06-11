# NOVA ESTRUTURA RECOMENDADA

```plaintext
/pages
   ├── planejar.php
   ├── processar_rota.php
   ├── inc/
   ├── assets/
         ├── css/
         ├── js/
```

---

# planejar.php

```php
<?php
session_start();
include 'inc/header.inc.php';
?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>

body{
    background:#111827;
    color:white;
}

.main-layout{
    display:grid;
    grid-template-columns:380px 1fr;
    gap:20px;
    width:100%;
    min-height:85vh;
    padding:20px;
    box-sizing:border-box;
}

.sidebar{
    background:#1f2937;
    border-radius:20px;
    padding:25px;
    border:1px solid #374151;
    height:fit-content;
}

.map-area{
    width:100%;
    min-width:0;
}

#map{
    width:100%;
    height:85vh;
    border-radius:20px;
    overflow:hidden;
    border:1px solid #374151;
}

.title{
    font-size:28px;
    font-weight:bold;
    margin-bottom:25px;
}

.form-label{
    margin-top:15px;
    margin-bottom:8px;
}

.form-control,
.form-select{
    background:#111827;
    border:1px solid #4b5563;
    color:white;
    border-radius:12px;
    padding:12px;
}

.form-control:focus,
.form-select:focus{
    background:#111827;
    color:white;
    box-shadow:none;
    border-color:#10b981;
}

.btn-gerar{
    width:100%;
    margin-top:25px;
    border:none;
    border-radius:14px;
    padding:14px;
    background:#10b981;
    color:white;
    font-weight:bold;
    transition:0.2s;
}

.btn-gerar:hover{
    background:#059669;
}

.info-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
    margin-top:25px;
}

.info-card{
    background:#111827;
    border-radius:14px;
    padding:15px;
    border:1px solid #374151;
}

.info-card h3{
    font-size:14px;
    color:#9ca3af;
    margin-bottom:10px;
}

.info-card p{
    font-size:24px;
    font-weight:bold;
    color:#10b981;
    margin:0;
}

.loading{
    display:none;
    margin-top:15px;
    color:#10b981;
}

@media(max-width:1100px){

    .main-layout{
        grid-template-columns:1fr;
    }

    #map{
        height:600px;
    }
}

</style>

<div class="main-layout">

    <div class="sidebar">

        <div class="title">
            Planejar Viagem EV
        </div>

        <form id="formRota">

            <label class="form-label">
                Origem
            </label>

            <input
                type="text"
                class="form-control"
                id="origem"
                placeholder="Ex: Curitiba PR"
                required
            >

            <label class="form-label">
                Destino
            </label>

            <input
                type="text"
                class="form-control"
                id="destino"
                placeholder="Ex: São Paulo SP"
                required
            >

            <label class="form-label">
                Bateria Atual (%)
            </label>

            <input
                type="number"
                class="form-control"
                id="bateria"
                min="1"
                max="100"
                value="80"
            >

            <label class="form-label">
                Autonomia do Veículo (km)
            </label>

            <input
                type="number"
                class="form-control"
                id="autonomia"
                value="450"
            >

            <button type="submit" class="btn-gerar">
                Gerar Planejamento
            </button>

            <div class="loading" id="loading">
                Calculando rota...
            </div>

        </form>

        <div class="info-grid">

            <div class="info-card">
                <h3>Distância</h3>
                <p id="distancia">--</p>
            </div>

            <div class="info-card">
                <h3>Tempo</h3>
                <p id="tempo">--</p>
            </div>

            <div class="info-card">
                <h3>Paradas</h3>
                <p id="paradas">--</p>
            </div>

            <div class="info-card">
                <h3>Bateria Final</h3>
                <p id="bateriaFinal">--</p>
            </div>

        </div>

    </div>

    <div class="map-area">
        <div id="map"></div>
    </div>

</div>

<script>

const map = L.map('map').setView([-25.429, -49.271], 6);

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        attribution:'© OpenStreetMap',
        maxZoom:19
    }
).addTo(map);

let rotaAtual = null;
let marcadores = [];

async function buscarCoordenadas(local){

    const response = await fetch(
        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(local)}`
    );

    const data = await response.json();

    if(!data.length){
        throw new Error('Local não encontrado');
    }

    return {
        lat: parseFloat(data[0].lat),
        lon: parseFloat(data[0].lon)
    };
}

async function gerarRota(origem,destino){

    const url = `https://router.project-osrm.org/route/v1/driving/${origem.lon},${origem.lat};${destino.lon},${destino.lat}?overview=full&geometries=geojson`;

    const response = await fetch(url);

    return await response.json();
}

function limparMapa(){

    if(rotaAtual){
        map.removeLayer(rotaAtual);
    }

    marcadores.forEach(m => map.removeLayer(m));

    marcadores = [];
}

function calcularParadas(distancia, autonomia, bateriaAtual){

    const autonomiaReal = autonomia * (bateriaAtual / 100);

    if(distancia <= autonomiaReal){

        return {
            paradas:0,
            bateriaFinal:Math.max(
                0,
                bateriaAtual - ((distancia/autonomia)*100)
            ).toFixed(0)
        };
    }

    const restante = distancia - autonomiaReal;

    const paradas = Math.ceil(restante / autonomia);

    return {
        paradas,
        bateriaFinal:20
    };
}

async function processarRota(event){

    event.preventDefault();

    document.getElementById('loading').style.display = 'block';

    try{

        limparMapa();

        const origemTexto = document.getElementById('origem').value;
        const destinoTexto = document.getElementById('destino').value;

        const bateria = parseInt(document.getElementById('bateria').value);
        const autonomia = parseInt(document.getElementById('autonomia').value);

        const origem = await buscarCoordenadas(origemTexto);
        const destino = await buscarCoordenadas(destinoTexto);

        const rota = await gerarRota(origem,destino);

        const coords = rota.routes[0].geometry.coordinates;

        const latlngs = coords.map(c => [c[1], c[0]]);

        rotaAtual = L.polyline(latlngs, {
            color:'#10b981',
            weight:6
        }).addTo(map);

        const markerOrigem = L.marker([origem.lat, origem.lon])
            .addTo(map)
            .bindPopup('Origem');

        const markerDestino = L.marker([destino.lat, destino.lon])
            .addTo(map)
            .bindPopup('Destino');

        marcadores.push(markerOrigem);
        marcadores.push(markerDestino);

        map.fitBounds(rotaAtual.getBounds(), {
            padding:[50,50]
        });

        const distanciaKm = rota.routes[0].distance / 1000;
        const tempoMin = rota.routes[0].duration / 60;

        const dados = calcularParadas(
            distanciaKm,
            autonomia,
            bateria
        );

        document.getElementById('distancia').innerText =
            distanciaKm.toFixed(1) + ' km';

        document.getElementById('tempo').innerText =
            Math.round(tempoMin) + ' min';

        document.getElementById('paradas').innerText =
            dados.paradas;

        document.getElementById('bateriaFinal').innerText =
            dados.bateriaFinal + '%';

    }
    catch(error){

        alert('Erro ao calcular rota');
        console.error(error);
    }

    document.getElementById('loading').style.display = 'none';
}



document
    .getElementById('formRota')
    .addEventListener('submit', processarRota);

</script>

<?php include 'inc/footer.inc.php'; ?>
