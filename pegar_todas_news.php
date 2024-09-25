<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Consulta para buscar todas as notícias
$sql = "SELECT titulo, subtitulo, conteudo, slug, data_cadastro FROM news ORDER BY data_cadastro DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Listar Todas as Notícias</title>
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
    
    <h2>Todas as Notícias</h2>

    <!-- Verificar se existem notícias no banco de dados -->
    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while($news = $result->fetch_assoc()): ?>
                <li>
                    <h3><?= htmlspecialchars($news['titulo']); ?></h3>
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
                    <hr>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Não há notícias cadastradas.</p>
    <?php endif; ?>

    <br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
