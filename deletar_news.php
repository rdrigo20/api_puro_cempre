<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Variável para armazenar mensagens de status
$mensagem = '';

// Verificar se o slug foi passado via GET
if (isset($_GET['slug'])) {
    $slug = $_GET['slug'];

    // Se o formulário de confirmação for submetido
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['confirmar']) && $_POST['confirmar'] === 'sim') {
            // Preparar a consulta para deletar a notícia
            $stmt = $conn->prepare("DELETE FROM news WHERE slug = ?");
            $stmt->bind_param("s", $slug);

            if ($stmt->execute()) {
                $mensagem = "Notícia deletada com sucesso!";
                header("Location: pegar_todas_news.php"); // Redireciona após a exclusão
                exit();
            } else {
                $mensagem = "Erro ao deletar a notícia: " . $stmt->error;
            }

            $stmt->close();
        } else {
            // Se o usuário não confirmar, redirecionar para outra página (por exemplo, a lista de notícias)
            header("Location: pegar_todas_news.php");
            exit();
        }
    }
} else {
    $mensagem = "Slug não fornecido.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Deletar Notícia</title>
</head>
<body>
    <h2>Deletar Notícia</h2>

    <?php if ($mensagem): ?>
        <p><?= htmlspecialchars($mensagem); ?></p>
    <?php else: ?>
        <form action="deletar_news.php?slug=<?= htmlspecialchars($slug); ?>" method="POST">
            <p>Você tem certeza que deseja deletar esta notícia?</p>
            <input type="submit" name="confirmar" value="sim"> 
            <input type="submit" name="confirmar" value="não">
        </form>
    <?php endif; ?>

    <br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
