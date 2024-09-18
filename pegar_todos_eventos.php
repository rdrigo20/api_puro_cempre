<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Buscar todos os eventos no banco de dados
$sql = "SELECT * FROM eventos ORDER BY data_evento DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Todos os Eventos</title>
</head>
<body>
    <h2>Lista de Eventos</h2>

    <?php if ($result->num_rows > 0): ?>
        <ul>
            <?php while($evento = $result->fetch_assoc()): ?>
                <li>
                    <h3><?= htmlspecialchars($evento['nome_evento']); ?></h3>
                    <p><strong>Descrição:</strong> <?= htmlspecialchars($evento['descricao_evento']); ?></p>
                    <p><strong>Conteúdo:</strong> <?= nl2br(htmlspecialchars($evento['conteudo_evento'])); ?></p>
                    <p><strong>Data do Evento:</strong> <?= htmlspecialchars($evento['data_evento']); ?></p>
                    <p><strong>Slug:</strong> <?= htmlspecialchars($evento['slug']); ?></p>
                    <p><small>Publicado em: <?= htmlspecialchars($evento['data_cadastro']); ?></small></p>
                    <p><small>Última atualização: <?= htmlspecialchars($evento['data_atualizacao']); ?></small></p>
                    <p><small>Cadastrado por (ID): <?= htmlspecialchars($evento['usuario_cadastro']); ?></small></p>
                    <?php if (!empty($evento['anexo'])): ?>
                        <p><strong>Anexo:</strong> <a href="<?= htmlspecialchars($evento['anexo']); ?>" target="_blank">Ver anexo</a></p>
                    <?php endif; ?>
                    <br>
                    <p><a href="editar_eventos.php?slug=<?= urlencode($evento['slug']); ?>">Editar</a></p>
                    <p><a href="deletar_eventos.php?slug=<?= urlencode($evento['slug']); ?>">Deletar</a></p>
                    <hr>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>Não há eventos cadastrados.</p>
    <?php endif; ?>

    <br><br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
