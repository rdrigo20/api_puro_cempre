<?php
session_start();

// Conectar ao banco de dados
$servername = "localhost";
$username = "root"; // Usuário padrão do MAMP
$password = "root"; // Senha padrão do MAMP
$dbname = "banquinho"; // Nome do banco de dados

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

//confere se conectou corretamente
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se há dados enviados via GET (usando HTML sem form)
if (isset($_GET['nome_usuario']) && isset($_GET['senha'])) {
    $nome_usuario = $_GET['nome_usuario'];
    $senha = md5($_GET['senha']); // Hash da senha usando MD5

    $sql = "SELECT * FROM usuarios WHERE nome_usuario='$nome_usuario' AND senha='$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['nome_usuario'] = $nome_usuario;
        echo "Login bem-sucedido! Sessão iniciada.";
        // Redirecionar para controle.php após login bem-sucedido
        header("Location: controle.php");
        exit();
    } else {
        echo "Usuário ou senha incorretos.";
    }
}

// Verificar se o usuário está logado para exibir o botão de logout
$logado = isset($_SESSION['nome_usuario']);

$conn->close();
?>

<!-- HTML para entrada de dados -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login do Usuário</title>
</head>
<body>
    <h2>Login do Usuário</h2>

    <?php if (!$logado): ?><!-- se n tiver nimguem logado --> 
        <input type="text" id="nome_usuario" placeholder="Nome de usuário" required>
        <input type="password" id="senha" placeholder="Senha" required>
        <button onclick="loginUsuario()">Login</button>
        <br><br>
        <a href="registro.php"><button>Registrar novo usuário</button></a> <!-- Link para a página de registro -->
    <?php else: ?>
        <p>Bem-vindo, <?= $_SESSION['nome_usuario']; ?>!</p>
        <a href="logout.php"><button>Logout</button></a>
    <?php endif; ?>

    <script>
        function loginUsuario() {
            // Capturar valores dos campos
            var nome_usuario = document.getElementById('nome_usuario').value;
            var senha = document.getElementById('senha').value;

            // Redirecionar para a URL com os parâmetros
            window.location.href = `?nome_usuario=${nome_usuario}&senha=${senha}`;
        }
    </script>
</body>
</html>
