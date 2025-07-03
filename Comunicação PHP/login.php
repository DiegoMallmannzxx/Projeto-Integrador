<?php
session_start();

header("Content-Type: application/json");

$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "mensagem" => "Erro de conexão."]);
    exit;
}

$usuarioForm = $_POST['usuario'] ?? '';
$senhaForm = $_POST['senha'] ?? '';

// Consulta pelo nome do usuário
$sql = "SELECT * FROM Usuario WHERE nome = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuarioForm);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuarioEncontrado = $result->fetch_assoc();

    // Aqui você pode validar a senha se necessário (caso esteja usando criptografia, adicione password_verify)
    $_SESSION['usuario'] = $usuarioEncontrado['nome'];

    echo json_encode([
        "success" => true,
        "nome" => $usuarioEncontrado['nome']
    ]);
} else {
    echo json_encode(["success" => false]);
}

$conn->close();
