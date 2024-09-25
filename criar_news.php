<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Função para gerar slugs
function gerarSlug($string) {
    // Converter para minúsculas
    $slug = strtolower($string);
    // Remover caracteres especiais e substituir espaços por hifens
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
    // Remover hifens duplicados e espaços adicionais
    $slug = preg_replace('/-+/', '-', trim($slug, '-'));
    return $slug;
}

// Inserir nova notícia no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $conteudo = $_POST['conteudo'];
    $slug = gerarSlug($titulo);
    
    // Recuperar o ID do usuário logado
    $nome_usuario = $_SESSION['nome_usuario'];
    $sql_usuario = "SELECT id FROM usuarios WHERE nome_usuario='$nome_usuario'";
    $result_usuario = $conn->query($sql_usuario);

    if ($result_usuario->num_rows > 0) {
        $usuario = $result_usuario->fetch_assoc();
        $usuario_cadastro = $usuario['id'];

        // Verificar se o slug já existe
        $sql_verificar_slug = "SELECT id FROM news WHERE slug = '$slug'";
        $result_verificar = $conn->query($sql_verificar_slug);

        // Se o slug já existir, adicionar um sufixo para torná-lo único
        if ($result_verificar->num_rows > 0) {
            $slug .= '-' . time(); // Adiciona um timestamp para garantir unicidade
        }

        // Inserir a nova notícia com slug
        $sql = "INSERT INTO news (titulo, subtitulo, conteudo, slug, usuario_cadastro) VALUES ('$titulo', '$subtitulo', '$conteudo', '$slug', $usuario_cadastro)";
        
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
    <!-- framework da bootstrap --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style type="text/css">

    </style>

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
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
