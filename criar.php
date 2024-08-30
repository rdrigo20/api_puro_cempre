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
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
