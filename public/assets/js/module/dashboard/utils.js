/* ======================================================== */
/*   UTILS                                                */
/* ======================================================== */

function toggleLoader(isLoading) {
    // 1.4 Si isLoading es true, mostrar loader y bloquear
    if (isLoading === true) {
        $('#cont_loading').removeClass('d-none');

        // 1.6 Bloquear la pantalla
        // wrapper.style.pointerEvents = 'none';
        // wrapper.style.opacity = '0.5';

        // 1.7 Prevenir scroll
        // document.body.style.overflow = 'hidden';
    } else if (isLoading === false) {
        // 1.9 Remover clases del loader con transición suave
        $('#cont_loading').addClass('d-none');

        // 1.10 Usar timeout para animar la desaparición
        // setTimeout(() => {
        //   // 1.11 Desbloquear la pantalla
        //   wrapper.style.pointerEvents = 'auto';
        //   wrapper.style.opacity = '1';

        //   // 1.12 Permitir scroll nuevamente
        //   document.body.style.overflow = 'auto';
        // }, 300); // Esperar la transición CSS
    }
}

function showScreen(screenId) {
    // 1.0 Ocultar todas las pantallas
    for (const key in SCREENS) {
        $('#' + SCREENS[key][0]).addClass('d-none');
    }
    // 1.1 Mostrar loader
    toggleLoader(true)
    // 1.2 Actualizar navegación
    $('#cont_title').text(screenId[1]);
    // 1.3 Mostrar pantalla después de un breve retraso
    setTimeout(() => {
        toggleLoader(false)
        $('#' + screenId[0]).removeClass('d-none');
    }, 800)
}

function switchSubScreen(idClose, idShow) {
    // 1.0 Alternar visibilidad de pantallas - Ocultar pantalla actual
    $('#' + idClose).addClass('d-none');
    // 1.1 Mostrar nueva pantalla
    $('#' + idShow).removeClass('d-none');
    // 1.2 Ajustar scroll
    $('html, body').scrollTop($('#' + idShow).offset().top - 100);
}

function viewPdf(url, title) {
    $('#modalViewPdfLabel').text(title);
    $('#pdf_viewer_iframe').attr('src', url);
    $('#modal_view_pdf').modal('show');
}

// Imagenes_G03 - Abrir modal de subida de imagen de la galeria
$('#btn_upload_picture').on('click', function () {
    const familyId = $(this).data('family-id');
    if (!familyId) {
        swal('Error', 'No hay una familia seleccionada', 'error');
        return;
    }
    $('#picture_family_id_upload').val(familyId);
    $('#file_input_picture').val('');
    $('#modal_upload_picture').modal('show');
});

// Imagenes_G03 - Guardar imagen de la galeria
$('#btn_save_upload_picture').on('click', function () {
    const familyId = $('#picture_family_id_upload').val();
    const fileInput = document.getElementById('file_input_picture');
    const selectedFile = fileInput.files[0];

    if (!selectedFile) {
        swal('Atención', 'Selecciona una imagen primero', 'warning');
        return;
    }

    let formData = new FormData();
    formData.append('image', selectedFile);
    formData.append('family_id', familyId);

    const btn = $(this);
    btn.prop('disabled', true).text('Subiendo...');

    $.ajax({
        url: SITE_URL + '/product/upload_family_picture',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (resp) {
            if (typeof resp === 'string') {
                try { resp = JSON.parse(resp); } catch (e) { }
            }

            if (resp.status === 'success') {
                swal('Éxito', 'Imagen subida correctamente', 'success');
                $('#modal_upload_picture').modal('hide');

                const familyId = $('#btn_upload_pdf').data('family-id');
                if (familyId) {
                    reloadImages(familyId);
                }
            } else {
                swal('Error', resp.message || 'Error al subir la imagen', 'error');
            }
        },
        error: function (xhr, status, error) {
            swal('Error', 'Hubo un problema de conexión', 'error');
            console.error(error);
        },
        complete: function () {
            btn.prop('disabled', false).text('Subir Imagen');
        }
    });
});

// Videos_G03 - Abrir modal de subida de video
$('#btn_upload_video').on('click', function () {
    const familyId = $(this).data('family-id');
    if (!familyId) {
        swal('Error', 'No hay una familia seleccionada', 'error');
        return;
    }
    $('#video_family_id_upload').val(familyId);
    $('#file_input_video').val('');
    $('#modal_upload_video').modal('show');
});

// Videos_G03 - Guardar video
$('#btn_save_upload_video').on('click', function () {
    const familyId = $('#video_family_id_upload').val();
    const fileInput = document.getElementById('file_input_video');
    const selectedFile = fileInput.files[0];

    if (!selectedFile) {
        swal('Atención', 'Selecciona un video primero', 'warning');
        return;
    }

    let formData = new FormData();
    formData.append('video', selectedFile);
    formData.append('family_id', familyId);

    const btn = $(this);
    btn.prop('disabled', true).text('Subiendo...');

    $.ajax({
        url: SITE_URL + '/product/upload_family_video',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (resp) {
            if (typeof resp === 'string') {
                try { resp = JSON.parse(resp); } catch (e) { }
            }

            if (resp.status === 'success') {
                swal('Éxito', 'Video subido correctamente', 'success');
                $('#modal_upload_video').modal('hide');

                const familyId = $('#btn_upload_pdf').data('family-id');
                if (familyId) {
                    reloadVideos(familyId);
                }
            } else {
                swal('Error', resp.message || 'Error al subir el video', 'error');
            }
        },
        error: function (xhr, status, error) {
            swal('Error', 'Hubo un problema de conexión', 'error');
            console.error(error);
        },
        complete: function () {
            btn.prop('disabled', false).text('Subir Video');
        }
    });
});

// 30.0 Cambiar PIN de seguridad - Configurar eventos para el cambio de PIN
// 30.1 Restringir entradas a números únicamente en el modal de cambio de PIN
$('#pin_actual, #pin_nuevo, #pin_confirmar').on('input', function (e) {
    e.target.value = e.target.value.replace(/[^0-9]/g, '');
});
// 30.2 Mostrar modal al hacer clic en la opción
$('#btn_change_pin_trigger').on('click', function (e) {
    e.preventDefault();
    $('#form_change_pin')[0].reset();
    $('#modal_change_pin').modal('show');
});
// 30.3 Enviar y validar cambio de PIN por AJAX
$('#btn_save_pin').on('click', function () {
    const pinActual = $('#pin_actual').val();
    const pinNuevo = $('#pin_nuevo').val();
    const pinConfirmar = $('#pin_confirmar').val();
    // 30.4 Validar que los campos no estén vacíos y tengan longitud exacta de 4
    if (!pinActual || !pinNuevo || !pinConfirmar) {
        swal('Atención', 'Por favor complete todos los campos.', 'warning');
        return;
    }
    if (pinActual.length !== 4 || pinNuevo.length !== 4 || pinConfirmar.length !== 4) {
        swal('Atención', 'El PIN debe ser exactamente de 4 dígitos.', 'warning');
        return;
    }
    // 30.5 Validar que el nuevo PIN coincida con la confirmación
    if (pinNuevo !== pinConfirmar) {
        swal('Atención', 'El nuevo PIN y la confirmación no coinciden.', 'warning');
        return;
    }
    // 30.6 Realizar petición AJAX para guardar el PIN
    const btn = $(this);
    btn.prop('disabled', true).text('Guardando...');
    $.ajax({
        url: SITE_URL + '/auth/change_pin',
        type: 'POST',
        data: $('#form_change_pin').serialize(),
        dataType: 'json',
        success: function (resp) {
            if (resp.status === 'success') {
                swal('Éxito', resp.message || 'PIN actualizado correctamente', 'success');
                $('#modal_change_pin').modal('hide');
            } else {
                swal('Error', resp.message || 'Error al actualizar el PIN', 'error');
            }
        },
        error: function (xhr, status, error) {
            swal('Error', 'Hubo un problema de conexión', 'error');
            console.error(error);
        },
        complete: function () {
            btn.prop('disabled', false).text('Guardar PIN');
        }
    });
});

// Reload_G03 - Recargar documentos del producto
function reloadDocuments(familyId) {
    $('#cont_documentos_producto').empty();
    $.ajax({
        url: SITE_URL + '/product/document/' + familyId,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            if (resp.documents && resp.documents.length && resp.documents[0].name !== null) {
                let html = '';
                resp.documents.forEach(function (doc) {
                    html += `
                        <div class="col-6 col-md-4 text-center mb-3 position-relative">
                            <a href="javascript:void(0)" class="text-danger text-decoration-none" onclick="viewPdf('${SITE_URL}${doc.path}', '${doc.name}')">
                                <i class="fas fa-file-pdf fa-3x"></i>
                                <p class="mt-2 mb-0 text-dark fw-bold" style="font-size:0.85rem; line-height: 1.2;">${doc.name}</p>
                            </a>
                            ${CAN_DELETE ? `<button class="btn btn-danger btn-sm btn-round btn-delete-file" data-delete-type="document" data-delete-id="${doc.id}" style="position: absolute; top: -8px; right: 5px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Eliminar documento">
                                <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                            </button>` : ''}
                        </div>
                    `;
                });
                $('#cont_documentos_producto').html(html);
            } else {
                $('#cont_documentos_producto').html('<div class="col-12 text-center text-muted"><p>No hay documentos asociados</p></div>');
            }
        },
        error: function () {
            console.log('Error al recargar documentos');
        }
    });
}

// Reload_G03 - Recargar imágenes del producto
function reloadImages(familyId) {
    $('#cont_imagenes_producto').empty();
    $.ajax({
        url: SITE_URL + '/product/img/' + familyId,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            let html = '';
            if (resp.images && resp.images.length > 0) {
                html += '<div class="d-flex flex-wrap justify-content-center">';
                resp.images.forEach(function (img) {
                    html += `
                        <div class="position-relative d-inline-block" style="margin: 5px;">
                            <img src="${SITE_URL}${img.path}" alt="Producto" style="max-width: 200px; width: auto; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            ${CAN_DELETE ? `<button class="btn btn-danger btn-sm btn-round btn-delete-file" data-delete-type="picture" data-delete-id="${img.id}" style="position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Eliminar imagen">
                                <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                            </button>` : ''}
                        </div>`;
                });
                html += '</div>';
            } else {
                html = '<div class="text-center text-muted"><p>No hay imágenes adicionales asociadas</p></div>';
            }
            $('#cont_imagenes_producto').html(html);
        },
        error: function () {
            console.log('Error al recargar imágenes');
        }
    });
}

// Reload_G03 - Recargar videos del producto
function reloadVideos(familyId) {
    $('#cont_videos_producto').empty();
    $.ajax({
        url: SITE_URL + '/product/video/' + familyId,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            let html = '';
            if (resp.videos && resp.videos.length > 0) {
                resp.videos.forEach(function (vid) {
                    html += `
                        <div class="position-relative d-inline-block" style="margin: 5px;">
                            <video src="${SITE_URL}${vid.path}" controls preload="metadata" style="max-width: 100%; width: auto; max-height: 500px;" onerror="this.style.display='none'">
                                Tu navegador no soporta el elemento de video.
                            </video>
                            ${CAN_DELETE ? `<button class="btn btn-danger btn-sm btn-round btn-delete-file" data-delete-type="video" data-delete-id="${vid.id}" style="position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Eliminar video">
                                <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                            </button>` : ''}
                        </div>`;
                });
            } else {
                html = '<div class="text-center text-muted"><p>No hay videos asociados</p></div>';
            }
            $('#cont_videos_producto').html(html);
        },
        error: function () {
            console.log('Error al recargar videos');
        }
    });
}
