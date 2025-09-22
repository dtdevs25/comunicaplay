<?php
// api/midias/upload-video.php

header('Content-Type: application/json');

require_once __DIR__ . '/../../controllers/MidiaController.php';

try {
    // Verifica se recebeu os dados necessários
    if (!isset($_FILES['video']) || !isset($_POST['nome']) || !isset($_POST['duracao'])) {
        throw new Exception('Dados incompletos');
    }

    $controller = new MidiaController();
    
    // Processa o upload
    $result = $controller->uploadVideo(
        $_FILES['video'],
        $_POST['nome'],
        $_POST['duracao'],
        !empty($_POST['pasta_id']) ? $_POST['pasta_id'] : null
    );

    // Retorna o resultado
    echo json_encode($result);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>

<script>
$("#formVideo").on("submit", function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const $progress = $(this).find('.progress');
    const $progressBar = $progress.find('.progress-bar');
    
    $progress.show();
    
    $.ajax({
        url: '<?= SITE_URL ?>/public/api/midias/upload-video.php',  // URL correta
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                    $progressBar.width(percentComplete + '%').text(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            console.log('Upload response:', response); // Para debug
            if (response.success) {
                // Mostra mensagem de sucesso
                alert('Vídeo enviado com sucesso!');
                
                // Limpa o formulário
                $('#formVideo')[0].reset();
                $('.file-preview').empty();
                
                // Redireciona para a lista de mídias
                window.location.href = 'midias.php';
            } else {
                alert(response.message || 'Erro ao enviar vídeo');
            }
        },
        error: function(xhr, status, error) {
            console.error('Upload error:', {xhr, status, error}); // Para debug
            alert('Erro ao enviar vídeo: ' + error);
        },
        complete: function() {
            $progress.hide();
            $progressBar.width('0%').text('0%');
        }
    });
});

// Adicione esta função no seu JavaScript
function showAlert(type, message) {
    const alertDiv = $(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`);
    
    $('.card-body').prepend(alertDiv);
    
    // Remove o alerta após 5 segundos
    setTimeout(() => {
        alertDiv.alert('close');
    }, 5000);
}
</script><?php
public function uploadVideo($file, $nome, $duracao, $pastaId = null) {
    try {
        // Verifica e cria o diretório de uploads se necessário
        $uploadDir = VIDEO_PATH;
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!is_writable($uploadDir)) {
            throw new Exception('Diretório de upload não tem permissão de escrita');
        }

        // ... resto do código ...
