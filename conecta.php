<?php
// Dados da conexão
$servername = "localhost";
$username = "root"; // Usuário padrão do MAMP
$password = "root"; // Senha padrão do MAMP
$dbname = "banquinho"; // Nome do banco de dados

// Criar a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
