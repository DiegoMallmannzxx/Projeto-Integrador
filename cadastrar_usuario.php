<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$nome = $_POST['nome'];
$senha = $_POST['senha'];
$perfil = $_POST['perfil'];

// Ajusta para o enum correto no banco (administrador ou operador)
$perfilCorrigido = ($perfil === "admin") ? "administrador" : "operador";

// Inserir no banco
$sql = "INSERT INTO Usuario (nome, perfil) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $nome, $perfilCorrigido);

if ($stmt->execute()) {
    echo "<script>alert('Usuário criado com sucesso!'); window.location.href='cadastro.usuario.html';</script>";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
