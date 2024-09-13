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
</head>
<body>
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
