<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Variável para armazenar a notícia encontrada
$news = null;
$slug = '';
$mensagem = '';

// Verificar se o slug foi passado via GET e buscar a notícia correspondente
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    // Preparar a consulta para evitar injeções de SQL
    $stmt = $conn->prepare("SELECT id, titulo, subtitulo, conteudo FROM news WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $news = $result->fetch_assoc();
    } else {
        $mensagem = "Notícia não encontrada.";
    }

    $stmt->close();
}

// Verificar se o formulário foi submetido para atualizar a notícia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slug'])) {
    $slug = $_POST['slug'];
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $conteudo = $_POST['conteudo'];

    // Gerar novo slug baseado no título atualizado
    function gerarSlug($string) {
        $slug = strtolower($string);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));
        return $slug;
    }
    $novo_slug = gerarSlug($titulo);

    // Preparar a consulta para atualizar a notícia
    $stmt = $conn->prepare("UPDATE news SET titulo = ?, subtitulo = ?, conteudo = ?, slug = ?, usuario_alteracao = ? WHERE slug = ?");
    $usuario_alteracao = $_SESSION['nome_usuario'];
    $usuario_id_stmt = $conn->prepare("SELECT id FROM usuarios WHERE nome_usuario = ?");
    $usuario_id_stmt->bind_param("s", $usuario_alteracao);
    $usuario_id_stmt->execute();
    $usuario_id_result = $usuario_id_stmt->get_result();

    if ($usuario_id_result->num_rows > 0) {
        $usuario = $usuario_id_result->fetch_assoc();
        $usuario_alteracao_id = $usuario['id'];

        // Atualizar a notícia com os novos dados
        $stmt->bind_param("ssssss", $titulo, $subtitulo, $conteudo, $novo_slug, $usuario_alteracao_id, $slug);

        if ($stmt->execute()) {
            $mensagem = "Notícia atualizada com sucesso!";
            // Atualizar o slug para o novo, caso tenha sido alterado
            $slug = $novo_slug;
        } else {
            $mensagem = "Erro ao atualizar a notícia: " . $stmt->error;
        }
    } else {
        $mensagem = "Erro: Usuário não encontrado.";
    }

    $stmt->close();
    $usuario_id_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Notícia</title>
    <!-- framework da bootstrap --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style type="text/css">

    </style>

</head>
<body>
    <h2>Editar Notícia</h2>

    <?php if ($news): ?>
        <form id="editando" action="editar_news.php?slug=<?= htmlspecialchars($slug); ?>" method="POST">
            <input type="hidden" name="slug" value="<?= htmlspecialchars($slug); ?>">

            <label for="titulo">Título:</label><br>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($news['titulo']); ?>" required><br><br>

            <label for="subtitulo">Subtítulo:</label><br>
            <input type="text" id="subtitulo" name="subtitulo" value="<?= htmlspecialchars($news['subtitulo']); ?>" required><br><br>

            <label for="conteudo">Conteúdo:</label><br>
            <textarea id="conteudo" name="conteudo" rows="5" required><?= htmlspecialchars($news['conteudo']); ?></textarea><br><br>

            <input type="submit" value="Atualizar Notícia">
        </form>
        
    <?php else: ?>
        <p><?= $mensagem; ?></p>
    <?php endif; ?>

    <br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
