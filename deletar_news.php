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
