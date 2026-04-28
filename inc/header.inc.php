<?php
session_start();
$usuario_logado = isset($_SESSION['logado']); // Usando a variável de sessão 'logado' 
?>
<!DOCTYPE html>
<html lang="pt-br" data-bs-theme="dark" >
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EnerSync</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="shortcut icon" href="image/favicon.ico" type="image/x-icon">
    
    <!-- Para bootstrap -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
 

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>



<body>
<div class="container">
        
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="image/logo.png" style="width: 100px" alt="Logo da Enersync"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav me-auto mb-2 mb-md-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">Início</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="planejarViagem.php">Planejar Viagem</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Comparar Veiculos</a></li>
            <li class="nav-item"><a class="nav-link" href="#">Comprar Peças</a></li>
            

            
          </ul>
          <div class="text-end px-5">
        <button onclick="toggleTheme()" class="btn btn-outline-light">
  Alternar Tema
</button>

<script>
function toggleTheme() {
  const html = document.documentElement;
  const current = html.getAttribute("data-bs-theme");
  html.setAttribute("data-bs-theme", current === "dark" ? "light" : "dark");
}
</script>

          </div>
            <div class="text-end px-5">

              <?php if ($usuario_logado): ?>
                <button class="btn btn-danger rounded-pill px-3 " type="button"  onclick="abrirModalConfirmacao('Encerrar Sessão', 'Tem certeza que deseja sair?', () => window.location.href = 'sair.php')">
                  Sair
                </button>
                <?php else: ?>
                  <a href="login.php" class="btn btn-primary rounded-pill px-3 ">
                    Login
                  </a>
                  <?php endif; ?>
                  
                </div>

          
        </div>
      </div>
      

    </nav>
        
        <div class="main" style="margin-top: 35px;">

<div class="espaco" style="margin-top: 100px;"></div>