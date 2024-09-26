<?php
session_start();

// Incluir o arquivo de conexão
include 'conecta.php';

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
            <span class="fs-4">Simple header</span>
        </a>
    </header>

    <center>  
        <h2>Login do Usuário</h2>

        
        <?php if (!$logado): ?><!-- se n tiver ninguem logado --> 
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
    </center>    
</body>
</html>
