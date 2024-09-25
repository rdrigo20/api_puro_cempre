<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Variável para armazenar o evento encontrado
$evento = null;

// Verificar se o formulário foi submetido e se o slug foi passado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slug'])) {
    $slug = $_POST['slug'];

    // Preparar a consulta para evitar injeções de SQL
    $stmt = $conn->prepare("SELECT nome_evento, descricao_evento, conteudo_evento, slug, data_evento, data_cadastro, data_alteracao, usuario_cadastro FROM eventos WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Armazenar o evento encontrado
        $evento = $result->fetch_assoc();
    } else {
        $erro = "Evento não encontrado.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Buscar Evento por Slug</title>
    <!-- framework da bootstrap --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style type="text/css">

    </style>

</head>
<body>
    <h2>Buscar Evento</h2>

    <!-- Formulário para entrada do slug do evento -->
    <form action="pegar_eventos.php" method="POST">
        <label for="slug">Slug do Evento:</label><br>
        <input type="text" id="slug" name="slug" required><br><br>
        <input type="submit" value="Buscar">
    </form>

    <!-- Exibição do evento se encontrado -->
    <?php if ($evento): ?>
        <h2><?= htmlspecialchars($evento['nome_evento']); ?></h2>
        <p><strong>Descrição:</strong> <?= htmlspecialchars($evento['descricao_evento']); ?></p>
        <p><strong>Conteúdo:</strong> <?= nl2br(htmlspecialchars($evento['conteudo_evento'])); ?></p>
        <p><strong>Data do Evento:</strong> <?= htmlspecialchars($evento['data_evento']); ?></p>
        <p><strong>Slug:</strong> <?= htmlspecialchars($evento['slug']); ?></p>
        <p><small>Publicado em: <?= htmlspecialchars($evento['data_cadastro']); ?></small></p>
        <p><small>Última atualização: <?= htmlspecialchars($evento['data_alteracao']); ?></small></p>
        <p><small>Cadastrado por (ID): <?= htmlspecialchars($evento['usuario_cadastro']); ?></small></p>
        <br>
        <p><a href="editar_eventos.php?slug=<?= htmlspecialchars($evento['slug']); ?>">Editar</a></p>
        <p><a href="deletar_eventos.php?slug=<?= urlencode($evento['slug']); ?>">Deletar</a></p>
        
    <?php elseif (isset($erro)): ?>
        <p><?= $erro; ?></p>
    <?php endif; ?>
    <hr>
    <br>
    
    <a href="pegar_todos_eventos.php"><button>Todos os Eventos</button></a>
    <br>
    <br>
    <a href="controle.php"><button>Painel de Controle</button></a>

</body>
</html>
