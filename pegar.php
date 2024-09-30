<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Lógica para visualizar (pegar) registros aqui
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área de Pegar</title>
    <link rel="stylesheet" href="style.css"> <!-- Referencia o CSS -->
    <!-- framework da bootstrap --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style type="text/css">

    </style>

</head>
<body>
    <!--Cabeçalho-->
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="controle.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            <span class="fs-4">Painel de Controle</span>
        </a>
    </header>
    <center>
        <h2>Área de Pegar</h2>
        <p>O que você quer pegar ?</p>
        <!-- Botão para criar news -->
        <button onclick="window.location.href='pegar_news.php'">News</button>
        
        <button onclick="window.location.href='pegar_eventos.php'">Eventos</button>
        <br><br>
        <a href="controle.php"><button>Painel de Controle</button></a>
    </center>
</body>
</html>
