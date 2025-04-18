<?php
include_once('config.php');
header('Content-Type: application/json');

// Verifica se o método é POST e há dados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $dados = json_decode($json, true);

    if (!isset($dados['produtos']) || !is_array($dados['produtos'])) {
        echo json_encode(['erro' => 'Lista de produtos inválida']);
        exit;
    }

    $nomes = $dados['produtos'];
    $placeholders = implode(',', array_fill(0, count($nomes), '?'));

    $stmt = $conexao->prepare("SELECT nome, preco FROM produtos WHERE nome IN ($placeholders)");
    $tipos = str_repeat('s', count($nomes));
    $stmt->bind_param($tipos, ...$nomes);
    $stmt->execute();

    $resultado = $stmt->get_result();
    $produtos = [];

    while ($row = $resultado->fetch_assoc()) {
        $produtos[] = [
            'nome' => $row['nome'],
            'preco' => $row['preco']
        ];
    }

    echo json_encode($produtos);
} else {
    echo json_encode(['erro' => 'Requisição inválida']);
}
