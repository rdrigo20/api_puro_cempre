<?php
session_start();
include 'conecta.php'; // Inclui a conexão ao banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Consulta SQL para buscar os registros de carrossel da tabela 'anexos'
$sql = "SELECT caminho, titulo, link, data_cadastro FROM anexos WHERE anexo_tipo = 'IMAGEM' ORDER BY data_cadastro DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Ver Carrossel</title>
    <link rel="stylesheet" href="style.css"> <!-- Referencia o CSS -->
    <!-- framework da bootstrap --> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style type="text/css">
        .carousel-item {
            text-align: center;
        }

        .carousel-item img {
            max-width: 100%;
            height: auto;
        }

        .carousel-caption {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
        }
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

<h2>Imagens do Carrossel</h2>

<?php if ($result->num_rows > 0): ?>
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < $result->num_rows; $i++): ?>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $i; ?>" class="<?= $i == 0 ? 'active' : ''; ?>" aria-current="true" aria-label="Slide <?= $i + 1; ?>"></button>
            <?php endfor; ?>
        </div>
        <div class="carousel-inner">
            <?php $i = 0; ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="carousel-item <?= $i == 0 ? 'active' : ''; ?>">
                    <img src="<?= htmlspecialchars($row['caminho']); ?>" alt="<?= htmlspecialchars($row['titulo']); ?>">
                    <div class="carousel-caption">
                        <h5><?= htmlspecialchars($row['titulo']); ?></h5>
                        <?php if (!empty($row['link'])): ?>
                            <a href="<?= htmlspecialchars($row['link']); ?>" class="btn btn-primary">Saiba Mais</a>
                        <?php endif; ?>
                        <p>Publicado em: <?= date('d/m/Y H:i:s', strtotime($row['data_cadastro'])); ?></p>
                    </div>
                </div>
                <?php $i++; ?>
            <?php endwhile; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Próximo</span>
        </button>
    </div>
<?php else: ?>
    <p>Nenhuma imagem no carrossel foi encontrada.</p>
<?php endif; ?>
<br>
<br>

<footer>
    <a href="controle.php"><button>Painel de Controle</button></a>
</footer>
</body>
</html>

<?php
// Fecha a conexão com o banco
$conn->close();
?>
