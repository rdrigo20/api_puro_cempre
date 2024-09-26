<?php
// Incluir o arquivo de conexão
include 'conecta.php';

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
