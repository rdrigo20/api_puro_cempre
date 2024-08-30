<?php
// Conectar ao banco de dados
$servername = "localhost";
$username = "root"; // Usuário padrão do MAMP
$password = "root"; // Senha padrão do MAMP
$dbname = "banquinho"; // Nome do banco de dados

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se há dados enviados via GET (usando HTML sem form)
if (isset($_GET['nome_usuario']) && isset($_GET['senha']) && isset($_GET['email'])) {
    $nome_usuario = $_GET['nome_usuario'];
    $senha = md5($_GET['senha']); // Hash da senha usando MD5
    $email = $_GET['email'];

    $sql = "INSERT INTO usuarios (nome_usuario, senha, email) VALUES ('$nome_usuario', '$senha', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Novo registro criado com sucesso.";
        echo '<br><a href="login.php">Voltar para o login</a>'; // Link para voltar ao login
    } else {
        echo "Erro: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!-- HTML para entrada de dados -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registro de Usuário</title>
</head>
<body>
    <h2>Registro de Usuário</h2>
    <input type="text" id="nome_usuario" placeholder="Nome de usuário" required>
    <input type="password" id="senha" placeholder="Senha" required>
    <input type="email" id="email" placeholder="Email" required>
    <button onclick="registrarUsuario()">Registrar</button>

    <script>
        function registrarUsuario() {
            // Capturar valores dos campos
            var nome_usuario = document.getElementById('nome_usuario').value;
            var senha = document.getElementById('senha').value;
            var email = document.getElementById('email').value;

            // Redirecionar para a URL com os parâmetros
            window.location.href = `?nome_usuario=${nome_usuario}&senha=${senha}&email=${email}`;
        }
    </script>
</body>
</html>
