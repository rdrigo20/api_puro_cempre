<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Inserir nova notícia no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $conteudo = $_POST['conteudo'];
    
    // Recuperar o ID do usuário logado
    $nome_usuario = $_SESSION['nome_usuario'];
    $sql_usuario = "SELECT id FROM usuarios WHERE nome_usuario='$nome_usuario'";
    $result_usuario = $conn->query($sql_usuario);

    if ($result_usuario->num_rows > 0) {
        $usuario = $result_usuario->fetch_assoc();
        $usuario_cadastro = $usuario['id'];

        // Inserir a nova notícia
        $sql = "INSERT INTO news (titulo, subtitulo, conteudo, usuario_cadastro) VALUES ('$titulo', '$subtitulo', '$conteudo', $usuario_cadastro)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Notícia cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar notícia: " . $conn->error;
        }
    } else {
        echo "Erro: Usuário não encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Nova Notícia</title>
</head>
<body>
    <h2>Criar Nova Notícia</h2>
    
    <!-- Formulário para entrada de dados da notícia -->
    <form action="criar_news.php" method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" id="titulo" name="titulo" required><br><br>

        <label for="subtitulo">Subtítulo:</label><br>
        <input type="text" id="subtitulo" name="subtitulo" required><br><br>

        <label for="conteudo">Conteúdo:</label><br>
        <textarea id="conteudo" name="conteudo" rows="5" required></textarea><br><br>

        <input type="submit" value="Cadastrar Notícia">
    </form>

    <br><br>
    <a href="controle.php">Voltar para o Painel de Controle</a>
</body>
</html>
