<?php
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Carrossel</title>
    <link rel="stylesheet" href="styles.css"> <!-- Opcional: incluir CSS externo -->
</head>
<body>

<h2>Upload de Imagem para o Carrossel</h2>

<form action="criar_carrousel.php" method="POST" enctype="multipart/form-data">
    <label for="titulo">Título:</label>
    <input type="text" name="titulo" id="titulo" required>
    <br>
    <label for="link">Link (opcional):</label>
    <input type="url" name="link" id="link">
    <br>
    <label for="arquivo">Imagem do Carrossel:</label>
    <input type="file" name="arquivo" id="arquivo"  required>
    <br>
    <button type="submit">Enviar Imagem</button>
    <br>
</form>

    <a href="controle.php"><button>Painel de Controle</button></a>

</body>
</html>
