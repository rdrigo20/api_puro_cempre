<?php
//Para ver os erros caso tenham
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conecta.php'; // Inclui a conexão ao banco de dados

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'];
    $link = isset($_POST['link']) ? $_POST['link'] : NULL;
    $usuario_cadastro = 1;//$_SESSION['id'];   solução temporária para o problema com o id

    // Verifica se um arquivo foi enviado e se não há erros
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] == 0) {
        // Pega a extensão do arquivo
        $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
        $nome_arquivo = $usuario_cadastro . '_' . date('Ymd_His') . '_carrossel.' . $extensao;
        
        // Verifica se o diretório existe, se não, cria
        if (!file_exists('../arquivos/carrossel/')) {
            mkdir('../arquivos/carrossel/', 0777, true);
        }


        // Define o caminho para salvar o arquivo
        $caminho_destino = "../arquivos/carrossel/" . $nome_arquivo;

        // Preparar a consulta SQL para inserir os dados na tabela 'anexos'
        $sql_anexo = "INSERT INTO anexos (caminho, titulo, link, anexo_tipo, usuario_cadastro) 
                      VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_anexo);

        $anexo_tipo = 'IMAGEM'; // Definido como 'IMAGEM' para o carrossel
        $stmt->bind_param("ssssi", $caminho_destino, $titulo, $link, $anexo_tipo, $usuario_cadastro);

        // Executa a consulta e move o arquivo
        if ($stmt->execute()) {
            // Tenta mover o arquivo para o diretório correto
            if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_destino)) {
                echo "<div class='alert alert-success'>Imagem do carrossel cadastrada com sucesso!</div>";
            } else {
                // Caso o movimento do arquivo falhe, remove o registro do banco de dados
                $stmt = $conn->prepare("DELETE FROM anexos WHERE id = ?");
                $ultimo_id = $conn->insert_id;
                $stmt->bind_param("i", $ultimo_id);
                $stmt->execute();
                echo "<div class='alert alert-warning'>Erro ao mover o arquivo. Registro removido do banco de dados.</div>";
            }
        } else {
            echo "<div class='alert alert-warning'>Erro ao cadastrar a imagem no banco de dados: " . $conn->error . "</div>";
        }

        $stmt->close();
    } else {
        echo "<div class='alert alert-danger'>Nenhuma imagem foi enviada ou houve um erro no upload!</div>";
        // Exibe detalhes do erro de upload
        echo "<br>Erro de Upload: " . $_FILES['arquivo']['error'];
    }

    // Fecha a conexão com o banco
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    
    <title>Criar Carrossel</title>
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
<h2>Upload de Imagem para o Carrossel</h2>

<form action="criar_carrousel.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" required><br><br>
    
    <label for="link">Link (opcional):</label>
    <input type="url" name="link" id="link"><br><br>
    
    <label for="arquivo">Imagem do Carrossel:</label>
    <input type="file" name="arquivo" id="arquivo"  required><br><br>
    
    <button type="submit">Enviar Imagem</button><br>
</form>

    <a href="controle.php"><button>Painel de Controle</button></a>

</body>
</html>
