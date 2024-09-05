<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['nome_usuario'])) {
    header("Location: login.php");
    exit();
}

// Incluir o arquivo de conexão
include 'conecta.php';

// Função para gerar slugs
function gerarSlug($string) {
    // Converter para minúsculas
    $slug = strtolower($string);
    // Remover caracteres especiais e substituir espaços por hifens
    $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $slug);
    // Remover hifens duplicados e espaços adicionais
    $slug = preg_replace('/-+/', '-', trim($slug, '-'));
    return $slug;
}

// Inserir novo evento no banco de dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_evento = $_POST['nome_evento'];
    $descricao_evento = $_POST['descricao_evento'];
    $conteudo_evento = $_POST['conteudo_evento'];
    $data_evento = $_POST['data_evento'];
    $anexo = $_POST['anexo'];
    $slug = gerarSlug($nome_evento);
    
    // Recuperar o ID do usuário logado
    $nome_usuario = $_SESSION['nome_usuario'];
    $sql_usuario = "SELECT id FROM usuarios WHERE nome_usuario='$nome_usuario'";
    $result_usuario = $conn->query($sql_usuario);

    if ($result_usuario->num_rows > 0) {
        $usuario = $result_usuario->fetch_assoc();
        $usuario_cadastro = $usuario['id'];

        // Verificar se o slug já existe
        $sql_verificar_slug = "SELECT id FROM eventos WHERE slug = '$slug'";
        $result_verificar = $conn->query($sql_verificar_slug);

        // Se o slug já existir, adicionar um sufixo para torná-lo único
        if ($result_verificar->num_rows > 0) {
            $slug .= '-' . time(); // Adiciona um timestamp para garantir unicidade
        }

        // Inserir o novo evento com slug
        $sql = "INSERT INTO eventos (slug, nome_evento, descricao_evento, conteudo_evento, data_evento, anexo, usuario_cadastro) 
                VALUES ('$slug', '$nome_evento', '$descricao_evento', '$conteudo_evento', '$data_evento', '$anexo', $usuario_cadastro)";
        
        if ($conn->query($sql) === TRUE) {
            echo "Evento cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar evento: " . $conn->error;
        }
    } else {
        echo "Erro: Usuário não encontrado.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Criar Novo Evento</title>
</head>
<body>
    <h2>Criar Novo Evento</h2>
    
    <!-- Formulário para entrada de dados do evento -->
    <form action="criar_eventos.php" method="POST">
        <label for="nome_evento">Nome do Evento:</label><br>
        <input type="text" id="nome_evento" name="nome_evento" required><br><br>

        <label for="descricao_evento">Descrição do Evento:</label><br>
        <textarea id="descricao_evento" name="descricao_evento" rows="3" required></textarea><br><br>

        <label for="conteudo_evento">Conteúdo do Evento:</label><br>
        <textarea id="conteudo_evento" name="conteudo_evento" rows="5" required></textarea><br><br>

        <label for="data_evento">Data do Evento:</label><br>
        <input type="datetime-local" id="data_evento" name="data_evento" required><br><br>

        <label for="anexo">Anexo (Link):</label><br>
        <input type="text" id="anexo" name="anexo"><br><br>

        <input type="submit" value="Cadastrar Evento">
    </form>

    <br><br>
    <a href="controle.php"><button>Painel de Controle</button></a>
</body>
</html>
