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
    <p>Implementar a lógica para visualizar registros aqui.</p>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
