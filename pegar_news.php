<?php
session_start();

// Incluir o arquivo de conexão
include 'conecta.php';

// Variável para armazenar a notícia encontrada
$news = null;

// Verificar se o formulário foi submetido e se o slug foi passado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slug'])) {
    $slug = $_POST['slug'];

    // Preparar a consulta para evitar injeções de SQL
    $stmt = $conn->prepare("SELECT titulo, subtitulo, conteudo, data_cadastro FROM news WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Armazenar a notícia encontrada
        $news = $result->fetch_assoc();
    } else {
        $erro = "Notícia não encontrada.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Notícia por Slug</title>
</head>
<body>
    <h2>Buscar Notícia</h2>

    <!-- Formulário para entrada do slug da notícia -->
    <form action="pegar_news.php" method="POST">
        <label for="slug">Slug da Notícia:</label><br>
        <input type="text" id="slug" name="slug" required><br><br>
        <input type="submit" value="Buscar">
    </form>

    <!-- Exibição da notícia se encontrada -->
    <?php if ($news): ?>
        <h2><?= htmlspecialchars($news['titulo']); ?></h2>
        <h4><?= htmlspecialchars($news['subtitulo']); ?></h4>
        <p><?= nl2br(htmlspecialchars($news['conteudo'])); ?></p>
        <p><small>Publicado em: <?= htmlspecialchars($news['data_cadastro']); ?></small></p>
    <?php elseif (isset($erro)): ?>
        <p><?= $erro; ?></p>
    <?php endif; ?>

    <br>
    <a href="controle.php">Voltar para o Painel de Controle</a>
</body>
</html>
