<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel de Controle</title>
</head>
<body>
    <h2>Painel de Controle</h2>
    <p>Bem-vindo, <?= $_SESSION['nome_usuario']; ?>!</p>
    
    <!-- Botão para criar novos registros -->
    <button onclick="window.location.href='criar.php'">Área de Criar</button>

    <!-- Botão para visualizar (pegar) registros -->
    <button onclick="window.location.href='pegar.php'">Área de Pegar</button>

    <br><br>
    <a href="logout.php"><button>Logout</button></a>
</body>
</html>
