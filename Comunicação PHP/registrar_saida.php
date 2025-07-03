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

// Dados do formulário
$codigo = $_POST['codigo'] ?? '';
$quantidade = $_POST['quantidade'] ?? '';
$data = $_POST['data'] ?? '';

// Validação
if (empty($codigo) || empty($quantidade) || empty($data)) {
  echo "<script>alert('Preencha todos os campos!'); window.location.href='saida.estoque.html';</script>";
  exit;
}

// Buscar o ID do produto com base no código
$stmt = $conn->prepare("SELECT id FROM Produto WHERE codigo = ?");
$stmt->bind_param("s", $codigo);
$stmt->execute();
$stmt->bind_result($produto_id);
$stmt->fetch();
$stmt->close();

if (!$produto_id) {
  echo "<script>alert('Produto não encontrado.'); window.location.href='saida.estoque.html';</script>";
  exit;
}

// Registrar saída
$insert = $conn->prepare("INSERT INTO SaidaEstoque (produto_id, quantidade, data) VALUES (?, ?, ?)");
$insert->bind_param("iis", $produto_id, $quantidade, $data);
$insert->execute();

// Atualizar estoque
$update = $conn->prepare("UPDATE Produto SET quantidade = quantidade - ? WHERE id = ?");
$update->bind_param("ii", $quantidade, $produto_id);
$update->execute();

echo "<script>alert('Saída registrada com sucesso!'); window.location.href='saida.estoque.html';</script>";

$conn->close();
