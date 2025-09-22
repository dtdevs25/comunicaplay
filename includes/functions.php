<?php
// Este arquivo contém apenas funções PHP reutilizáveis

// Função para upload de vídeo
function uploadVideo($file, $nome, $duracao, $pastaId = null) {
    try {
        $uploadDir = VIDEO_PATH;
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('Diretório de upload não tem permissão de escrita');
        }

        // ... restante da lógica de upload ...
    } catch (Exception $e) {
        error_log("Erro no upload de vídeo: " . $e->getMessage());
        return ['success' => false, 'message' => 'Erro interno do servidor.'];
    }
}
?>
