<?php

require_once __DIR__ . '/../../../controllers/MidiaController.php';

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

// Verifica se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => 'ID da mídia é obrigatório.'
    ]);
    exit;
}

$id = (int)$_GET['id'];

try {
    $midiaController = new MidiaController();
    $result = $midiaController->getById($id);
    
    if ($result['success'] && $result['midia']) {
        $midia = $result['midia'];
        
        // Ajustar URLs para visualização
        if ($midia['tipo'] === 'video' && $midia['caminho_arquivo']) {
            $midia['arquivo_local'] = $midia['caminho_arquivo'];
        } elseif ($midia['tipo'] === 'imagem' && $midia['caminho_arquivo']) {
            $midia['arquivo_local'] = $midia['caminho_arquivo'];
        } elseif ($midia['tipo'] === 'youtube' && $midia['url_externa']) {
            // URL já está correta
        } elseif ($midia['tipo'] === 'link_imagem' && $midia['url_externa']) {
            // URL já está correta
        } elseif ($midia['tipo'] === '' && $midia['url_externa']) {
            // Tipo site/página
            $midia['tipo'] = 'site';
        }
        
        echo json_encode([
            'success' => true,
            'midia' => $midia
        ]);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Mídia não encontrada.'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Erro na API get-midia: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno do servidor.'
    ]);
}


