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
    $stmt = $conn->prepare("SELECT titulo, subtitulo, conteudo, slug, data_cadastro FROM news WHERE slug = ?");
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
        <p><strong>Slug:</strong> <?= htmlspecialchars($news['slug']); ?></p>
        <!--Nao consigo colocar o usuario_cadastro que o ID do usuario, o ideal seria ter o nome do usuario mas isso exigiria um join de tabela -->
        <p><small>Cadastrado por (ID): <?= htmlspecialchars($news['usuario_cadastro']); ?></small></p>
        <p><small>Publicado por: <?= (int) $news['usuario_cadastro']; ?></small></p>
        <br>
        <p><a href="editar_news.php?slug=<?= htmlspecialchars($news['slug']); ?>">Editar</a></p>
        <p><a href="deletar_news.php?slug=<?= htmlspecialchars($news['slug']); ?>">Deletar</a></p>
        
    <?php elseif (isset($erro)): ?>
        <p><?= $erro; ?></p>
    <?php endif; ?>
    <hr>
    <br>
    
    <a href="pegar_todas_news.php"><button>Todas as News</button></a>
    <br>
    <br>
    <a href="controle.php"><button>Painel de Controle</button></a>

</body>
</html>
