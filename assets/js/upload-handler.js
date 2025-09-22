// Adiciona preview do vídeo antes do envio
$('#videoInput').on('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const url = URL.createObjectURL(file);
        $('.file-preview').html(`<video src="${url}" controls width="300"></video>`);
    }
});

// Substitui alert por showAlert
$('#formVideo').on('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const $progress = $(this).find('.progress');
    const $progressBar = $progress.find('.progress-bar');

    $progress.show();

    $.ajax({
        url: '<?= SITE_URL ?>/public/api/midias/upload-video.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        xhr: function() {
            const xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener('progress', function(evt) {
                if (evt.lengthComputable) {
                    const percentComplete = Math.round((evt.loaded / evt.total) * 100);
                    $progressBar.width(percentComplete + '%').text(percentComplete + '%');
                }
            }, false);
            return xhr;
        },
        success: function(response) {
            console.log('Upload response:', response);
            if (response.success) {
                showAlert('success', 'Vídeo enviado com sucesso!');
                $('#formVideo')[0].reset();
                $('.file-preview').empty();
                window.location.href = 'midias.php';
            } else {
                showAlert('danger', response.message || 'Erro ao enviar vídeo');
            }
        },
        error: function(xhr, status, error) {
            console.error('Upload error:', {xhr, status, error});
            showAlert('danger', 'Erro ao enviar vídeo: ' + error);
        },
        complete: function() {
            $progress.hide();
            $progressBar.width('0%').text('0%');
        }
    });
});