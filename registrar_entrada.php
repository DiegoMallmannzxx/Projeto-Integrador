<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "minestock";

// Conexão
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Receber dados do formulário
$codigo = $_POST['produto_id'] ?? '';  // Código do produto
$quantidade = $_POST['quantidade'] ?? '';
$data = $_POST['data'] ?? '';

// Validação
if (empty($codigo) || empty($quantidade) || empty($data)) {
    echo "<script>alert('Preencha todos os campos!'); window.location.href='entrada.estoque.html';</script>";
    exit;
}

// Buscar ID do produto pelo código
$stmt = $conn->prepare("SELECT id FROM Produto WHERE codigo = ?");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Produto não encontrado!'); window.location.href='entrada.estoque.html';</script>";
    exit;
}

$produto = $result->fetch_assoc();
$produto_id = $produto['id'];

// Inserir entrada de estoque
$insert = $conn->prepare("INSERT INTO EntradaEstoque (produto_id, quantidade, data) VALUES (?, ?, ?)");
$insert->bind_param("iis", $produto_id, $quantidade, $data);

if ($insert->execute()) {
    echo "<script>alert('Entrada registrada com sucesso!'); window.location.href='entrada.estoque.html';</script>";
} else {
    echo "<script>alert('Erro ao registrar entrada.'); window.location.href='entrada.estoque.html';</script>";
}

$conn->close();
