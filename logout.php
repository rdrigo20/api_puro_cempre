<?php
session_start();

// Encerrar a sessão do usuário
session_unset();
session_destroy();

// Redirecionar para a página de login
header("Location: login.php");
exit();
?>
