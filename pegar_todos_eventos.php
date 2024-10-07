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
$sql = "SELECT * FROM eventos ORDER BY data_cadastro DESC";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Todos os Eventos</title>
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
            <span class="fs-4">Painel de Controle</span>
        </a>
    </header>
    
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
                    <p><small>Cadastrado por (ID): <?= htmlspecialchars($evento['usuario_cadastro']); ?></small></p>
                    <!--só vai aparecer caso tenha ocorrido alteração-->
                    <?php if (!empty($evento['data_alteracao'])): ?>
                        <p><small>Última atualização: <?= htmlspecialchars($evento['data_alteracao']); ?></small></p>
                    <?php endif; ?>
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
