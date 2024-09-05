<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Lógica para criar registros aqui
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Área de Criar</title>
</head>
<body>
    <h2>Área de Criar</h2>
    <p>O que você deseja criar ?</p>
    <!-- Botão para criar news -->
    <button onclick="window.location.href='criar_news.php'">news</button>
    <br>
    <button onclick="window.location.href='criar_eventos.php'">eventos</button>
    <br><br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
