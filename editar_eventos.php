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
$slug = '';
$mensagem = '';

// Verificar se o slug foi passado via GET e buscar o evento correspondente
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    // Preparar a consulta para evitar injeções de SQL
    $stmt = $conn->prepare("SELECT id, nome_evento, descricao_evento, conteudo_evento, data_evento FROM eventos WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $evento = $result->fetch_assoc();
    } else {
        $mensagem = "Evento não encontrado.";
    }

    $stmt->close();
}

// Verificar se o formulário foi submetido para atualizar o evento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['slug'])) {
    $slug = $_POST['slug'];
    $nome_evento = $_POST['nome_evento'];
    $descricao_evento = $_POST['descricao_evento'];
    $conteudo_evento = $_POST['conteudo_evento'];
    $data_evento = $_POST['data_evento'];

    // Função para gerar slug
    function gerarSlug($string) {
        $slug = strtolower($string);
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', trim($slug, '-'));
        return $slug;
    }
    $novo_slug = gerarSlug($nome_evento);

    // Preparar a consulta para atualizar o evento
    $stmt = $conn->prepare("UPDATE eventos SET nome_evento = ?, descricao_evento = ?, conteudo_evento = ?, data_evento = ?, slug = ?, usuario_alteracao = ? WHERE slug = ?");
    $usuario_alteracao = $_SESSION['nome_usuario'];
    $usuario_id_stmt = $conn->prepare("SELECT id FROM usuarios WHERE nome_usuario = ?");
    $usuario_id_stmt->bind_param("s", $usuario_alteracao);
    $usuario_id_stmt->execute();
    $usuario_id_result = $usuario_id_stmt->get_result();

    if ($usuario_id_result->num_rows > 0) {
        $usuario = $usuario_id_result->fetch_assoc();
        $usuario_alteracao_id = $usuario['id'];

        // Atualizar o evento com os novos dados
        $stmt->bind_param("sssssis", $nome_evento, $descricao_evento, $conteudo_evento, $data_evento, $novo_slug, $usuario_alteracao_id, $slug);

        if ($stmt->execute()) {
            $mensagem = "Evento atualizado com sucesso!";
            // Atualizar o slug para o novo, caso tenha sido alterado
            $slug = $novo_slug;
        } else {
            $mensagem = "Erro ao atualizar o evento: " . $stmt->error;
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
    <title>Editar Evento</title>
    <!-- framework da bootstrap --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style type="text/css">

    </style>

</head>
<body>
    <h2>Editar Evento</h2>

    <?php if ($evento): ?>
        <form id="editando" action="editar_eventos.php?slug=<?= htmlspecialchars($slug); ?>" method="POST">
            <input type="hidden" name="slug" value="<?= htmlspecialchars($slug); ?>">

            <label for="nome_evento">Nome do Evento:</label><br>
            <input type="text" id="nome_evento" name="nome_evento" value="<?= htmlspecialchars($evento['nome_evento']); ?>" required><br><br>

            <label for="descricao_evento">Descrição do Evento:</label><br>
            <textarea id="descricao_evento" name="descricao_evento" rows="3" required><?= htmlspecialchars($evento['descricao_evento']); ?></textarea><br><br>

            <label for="conteudo_evento">Conteúdo do Evento:</label><br>
            <textarea id="conteudo_evento" name="conteudo_evento" rows="5" required><?= htmlspecialchars($evento['conteudo_evento']); ?></textarea><br><br>

            <label for="data_evento">Data do Evento:</label><br>
            <input type="datetime-local" id="data_evento" name="data_evento" value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($evento['data_evento']))); ?>" required><br><br>

            <input type="submit" value="Atualizar Evento">
        </form>
    <?php else: ?>
        <p><?= $mensagem; ?></p>
    <?php endif; ?>

    <br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
