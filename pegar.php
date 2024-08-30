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
</head>
<body>
    <h2>Área de Pegar</h2>
    <p>O que você quer pegar ?</p>
    <!-- Botão para criar news -->
    <button onclick="window.location.href='pegar_news.php'">news</button>
    <br><br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
