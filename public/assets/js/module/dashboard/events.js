/* ======================================================== */
/*   EVENTS                                                 */
/* ======================================================== */

// Mobile - Cerrar sidebar al hacer clic en un item del menú
$('.sidebar .nav-item a').on('click', function () {
    if ($(window).width() <= 991) {
        $('html').removeClass('nav_open');
        $('.sidenav-toggler').removeClass('toggled');
    }
});

// Main - Abrir dashboard
$('#btn_open_dashboard').on('click', function () {
    showScreen(SCREENS.dashboard);
});

// Main - Abrir productos listar
$('#btn_open_product_list').on('click', function () {
    showScreen(SCREENS.product_listing);
});

// Main - Abrir remisiones
$('#btn_open_remisiones').on('click', function () {
    showScreen(SCREENS.remisiones);
});

// Remisiones - Abrir pantalla de crear remisión (sin inline onclick)
$('#btn_create_remision').on('click', function () {
    $('#remisiones_list_view').addClass('d-none');
    $('#remisiones_create_view').removeClass('d-none');
});

// Remisiones - Volver al listado de remisiones (sin inline onclick)
$('#btn_back_to_remisiones_list').on('click', function () {
    $('#remisiones_create_view').addClass('d-none');
    $('#remisiones_list_view').removeClass('d-none');
});

// Remisiones - Limpiar formulario de creación (sin inline onclick)
$('#btn_reset_remision_form').on('click', function () {
    $('#form_remision_create')[0].reset();
});

// Recomendaciones - Guardar recomendación (sin inline onclick)
$('#btn_save_recommendation').on('click', function () {
    if (typeof saveRecommendation === 'function') {
        saveRecommendation();
    } else {
        $('#modal_recommend_book').modal('hide');
        swal("¡Gracias!", "Tu recomendación ha sido compartida con el equipo.", "success");
    }
});

// Bodegas - Ver balance desde tabla principal (sin inline onclick)
$(document).on('click', '.btn-view-warehouse-balance', function () {
    const id = $(this).data('id');
    if (id) {
        viewWarehouseBalance(id);
    }
});

// Documentos PDF - Ver PDF (sin inline onclick)
$(document).on('click', '.btn-view-pdf-doc', function (e) {
    e.preventDefault();
    const path = $(this).data('path');
    const name = $(this).data('name');
    if (typeof viewPdf === 'function') {
        viewPdf(path, name);
    }
});

// Main - Abrir bodegas
$('#btn_open_bodega').on('click', function () {
    showScreen(SCREENS.bodega);
    initBodegasTable();
});

// Tabla_G03 - Búsqueda en catálogo
$('#search_products').on('input', function () {
    let searchVal = $(this).val().toLowerCase();
    $('.product-card-item').each(function () {
        let nombre = $(this).data('nombre') || '';
        let categoria = $(this).data('categoria') || '';
        let marca = $(this).data('marca') || '';

        if (nombre.includes(searchVal) || categoria.includes(searchVal) || marca.includes(searchVal)) {
            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// Tabla_G03 - Seleccionar Producto
$(document).on('click', '[data-selector="abrir"]', function () {
    // 0.0 Variables Macro
    const MACRO = {
        id_producto: $(this).attr('data-id'),
        fila_producto: null,
        variantes_html: ''
    };

    // 1.0 Buscar producto y variantes - Buscar producto
    MACRO.fila_producto = GLOBAL_PRODUCTS_DATA.find(p => p.id == MACRO.id_producto);
    if (!MACRO.fila_producto) return;
    // 1.1 Buscar variantes
    let nombre_base = MACRO.fila_producto.nombre ? MACRO.fila_producto.nombre.toLowerCase().trim() : 'sin nombre';
    let lista_variantes = GLOBAL_PRODUCTS_DATA.filter(p => {
        let n = p.nombre ? p.nombre.toLowerCase().trim() : 'sin nombre';
        return n === nombre_base;
    });

    // 2.0 Renderizar variantes del producto
    if (lista_variantes.length > 1) {
        $('#cont_variantes_wrapper').removeClass('d-none');
        lista_variantes.forEach(v => {
            let badge = v.id == MACRO.id_producto ? '<span class="badge badge-success">Actual</span>' : '';
            MACRO.variantes_html += `
                <tr>
                    <td>${v.presentacion || 'N/A'} ${badge}</td>
                    <td>${v.id_categoria || 'N/A'}</td>
                    <td>${v.id_marca || 'N/A'}</td>
                    <td>
                        ${v.id != MACRO.id_producto ? `<button class="btn btn-sm btn-outline-primary btn-round" data-selector="abrir" data-id="${v.id}"><i class="fas fa-eye"></i> Ver Detalle</button>` : ''}
                    </td>
                </tr>
            `;
        });
        $('#list_variantes_producto').html(MACRO.variantes_html);
    } else {
        $('#cont_variantes_wrapper').addClass('d-none');
    }

    // 3.0 Mostrar interfaz de detalle - Cambiar a pantalla de detalle
    switchSubScreen('screen_catalog_products', 'screen_product_detail');
    // 3.1 Guardar el ID actual en el botón de modificar para poder subir la foto
    $('#btn_product_edit').data('id', MACRO.fila_producto.id);

    // 4.0 Mostrar información general
    $('#in_nombre_producto').val((MACRO.fila_producto.nombre || '').toUpperCase());
    $('#in_categoria_producto').val(MACRO.fila_producto.id_categoria.toUpperCase());
    $('#in_presentacion_producto').val(MACRO.fila_producto.presentacion.toUpperCase());
    $('#in_marca_producto').val(MACRO.fila_producto.id_marca.toUpperCase());
    $('#in_observacion_producto').val(MACRO.fila_producto.observacion?.toUpperCase());

    // 5.0 Mostrar imagen del producto
    $('#cont_imagenes_producto').empty();
    if (MACRO.fila_producto.img) {
        let familyImgSrc = SITE_URL + '/' + MACRO.fila_producto.img.replace(/^\/+/, '');
        $('#info_general_family_image').attr('src', familyImgSrc);
        $('#cont_info_general_image').removeClass('d-none');
    } else {
        $('#info_general_family_image').attr('src', '');
        $('#cont_info_general_image').addClass('d-none');
    }

    $.ajax({
        url: SITE_URL + '/product/img/' + (MACRO.fila_producto.family_id || MACRO.fila_producto.id),
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            let imagenes_html = '';
            if (resp.images && resp.images.length > 0) {
                imagenes_html += '<div class="d-flex flex-wrap justify-content-center">';
                resp.images.forEach(img => {
                    imagenes_html += `
                        <div class="position-relative d-inline-block" style="margin: 5px;">
                            <img src="${SITE_URL}${img.path}" alt="Producto" style="max-width: 200px; width: auto; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                            ${CAN_DELETE ? `<button class="btn btn-danger btn-sm btn-round btn-delete-file" data-delete-type="picture" data-delete-id="${img.id}" style="position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Eliminar imagen">
                                <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                            </button>` : ''}
                        </div>`;
                });
                imagenes_html += '</div>';
            } else {
                imagenes_html = '<div class="text-center text-muted"><p>No hay imágenes adicionales asociadas</p></div>';
            }
            $('#cont_imagenes_producto').html(imagenes_html);
        },
        error: function (xhr, status, error) {
            console.log(error);
        },
        complete: function () {
            // always
        }
    });

    // 6.0 Mostrar video del producto
    $('#cont_videos_producto').empty();
    $.ajax({
        url: SITE_URL + '/product/video/' + (MACRO.fila_producto.family_id || MACRO.fila_producto.id),
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            let videos_html = '';
            if (resp.videos && resp.videos.length > 0) {
                resp.videos.forEach(vid => {
                    videos_html += `
                        <div class="position-relative d-inline-block" style="margin: 5px;">
                            <video 
                                src="${SITE_URL}${vid.path}" 
                                controls 
                                preload="metadata" 
                                style="max-width: 100%; width: auto; max-height: 500px;"
                                onerror="this.style.display='none'" 
                                >
                                Tu navegador no soporta el elemento de video.
                            </video>
                            ${CAN_DELETE ? `<button class="btn btn-danger btn-sm btn-round btn-delete-file" data-delete-type="video" data-delete-id="${vid.id}" style="position: absolute; top: -8px; right: -8px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Eliminar video">
                                <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                            </button>` : ''}
                        </div>`;
                });
            } else {
                videos_html = '<div class="text-center text-muted"><p>No hay videos asociados</p></div>';
            }
            $('#cont_videos_producto').html(videos_html);
        },
        error: function (xhr, status, error) {
            console.log(error);
            Swal.fire('<h5> ❌ Error al buscar videos</h5>')
        },
        complete: function () {
            // always
        }
    });

    // 7.0 Mostrar documentos del producto
    $('#cont_documentos_producto').empty();
    // 7.1 Guardar ID en los botones de subir
    $('#btn_upload_pdf').data('family-id', MACRO.fila_producto.family_id || MACRO.fila_producto.id);
    $('#btn_upload_picture').data('family-id', MACRO.fila_producto.family_id || MACRO.fila_producto.id);
    $('#btn_upload_video').data('family-id', MACRO.fila_producto.family_id || MACRO.fila_producto.id);
    // 7.2 Obtener documentos
    $.ajax({
        url: SITE_URL + '/product/document/' + (MACRO.fila_producto.family_id || MACRO.fila_producto.id),
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            if (resp.documents && resp.documents.length && resp.documents[0].name !== null) {
                let documentos_html = '';
                resp.documents.forEach((document, index) => {
                    documentos_html += `
                        <div class="col-6 col-md-4 text-center mb-3 position-relative">
                            <a href="javascript:void(0)" class="text-danger text-decoration-none btn-view-pdf-doc" data-path="${SITE_URL}${document.path}" data-name="${document.name}">
                                <i class="fas fa-file-pdf fa-3x"></i>
                                <p class="mt-2 mb-0 text-dark fw-bold" style="font-size:0.85rem; line-height: 1.2;">${document.name}</p>
                            </a>
                            ${CAN_DELETE ? `<button class="btn btn-danger btn-sm btn-round btn-delete-file" data-delete-type="document" data-delete-id="${document.id}" style="position: absolute; top: -8px; right: 5px; width: 28px; height: 28px; padding: 0; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 6px rgba(0,0,0,0.3);" title="Eliminar documento">
                                <i class="fas fa-trash-alt" style="font-size: 12px;"></i>
                            </button>` : ''}
                        </div>
                    `;
                });
                $('#cont_documentos_producto').html(documentos_html);
            } else {
                $('#cont_documentos_producto').html('<div class="col-12 text-center text-muted"><p>No hay documentos asociados</p></div>');
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
            Swal.fire('<h5> ❌ Error al cargar docs</h5>')
        },
        complete: function () {
            // always
        }
    });
});

// Tabla_G03 - Boton Volver
$('#btn_back_to_catalog').on('click', function () {
    // 1.0 Volver al catálogo - Cambiar a pantalla principal
    switchSubScreen('screen_product_detail', 'screen_catalog_products');
});

// Documentos_G03 - Abrir Modal de Subida de Documento PDF
$('#btn_upload_pdf').on('click', function () {
    const familyId = $(this).data('family-id');
    if (!familyId) {
        swal('Error', 'No hay una familia seleccionada', 'error');
        return;
    }
    $('#siigo_family_id_upload').val(familyId);

    // Reset modal state
    $('#pdf_document_name').val('');
    $('#file_input_pdf').val('');

    $('#modal_upload_pdf').modal('show');
});

// Imagen_G03 - Abrir Modal de Subida de Imagen Principal (Familia)
$(document).on('click', '#btn_change_family_image', function () {
    const familyId = $('#btn_upload_pdf').data('family-id');
    if (!familyId) {
        swal('Error', 'No se encontró el ID de la familia de productos', 'error');
        return;
    }
    // Set family id and reset modal preview/files
    $('#family_id_upload').val(familyId);
    $('#siigo_product_id_upload').val(''); // Clear product upload to distinguish
    $('#file_input_image').val('');
    $('#image_preview_container').addClass('d-none');
    $('#image_preview').attr('src', '');
    $('#image_filename').text('');
    $('#modal_upload_image').modal('show');
});

// Imagen_G03 - Manejar Drag & Drop y Click en el área
$(document).on('click', '#drag_drop_area', function () {
    $('#file_input_image').trigger('click');
});

$(document).on('change', '#file_input_image', function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $('#image_preview').attr('src', e.target.result);
            $('#image_filename').text(file.name);
            $('#image_preview_container').removeClass('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// Drag & drop events
$(document).on('dragover', '#drag_drop_area', function (e) {
    e.preventDefault();
    $(this).addClass('bg-light');
});

$(document).on('dragleave', '#drag_drop_area', function (e) {
    e.preventDefault();
    $(this).removeClass('bg-light');
});

$(document).on('drop', '#drag_drop_area', function (e) {
    e.preventDefault();
    $(this).removeClass('bg-light');
    const files = e.originalEvent.dataTransfer.files;
    if (files.length) {
        document.getElementById('file_input_image').files = files;
        $('#file_input_image').trigger('change');
    }
});

// Imagen_G03 - Enviar Imagen por AJAX
$(document).on('click', '#btn_upload_image', function () {
    const familyId = $('#family_id_upload').val();
    const fileInput = document.getElementById('file_input_image');
    const selectedImage = fileInput.files[0];

    if (!familyId) {
        // Si no hay family_id, podría ser el flujo antiguo de producto
        const productId = $('#siigo_product_id_upload').val();
        if (!productId) {
            swal('Error', 'No se ha definido el producto o familia a actualizar', 'error');
            return;
        }
    }

    if (!selectedImage) {
        swal('Atención', 'Selecciona una imagen primero', 'warning');
        return;
    }

    let formData = new FormData();
    formData.append('image', selectedImage);
    formData.append('family_id', familyId);

    const btn = $(this);
    btn.prop('disabled', true).text('Subiendo...');

    $.ajax({
        url: SITE_URL + '/product/upload_image',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (resp) {
            if (typeof resp === 'string') {
                try {
                    resp = JSON.parse(resp);
                } catch (e) { }
            }

            if (resp.status === 'success') {
                swal('Éxito', 'Imagen actualizada correctamente', 'success');
                $('#modal_upload_image').modal('hide');

                // Recargar el catálogo completo para actualizar la imagen en GLOBAL_PRODUCTS_DATA
                $.ajax({
                    url: SITE_URL + '/product/listing_siigo',
                    type: 'GET',
                    dataType: 'json',
                    success: function (catalog_resp) {
                        GLOBAL_PRODUCTS_DATA = catalog_resp.data || [];
                        // Recargar imágenes del producto
                        const familyId = $('#btn_upload_pdf').data('family-id');
                        if (familyId) {
                            reloadImages(familyId);
                        }
                    }
                });
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

// Documentos_G03 - Limpiar iframe al cerrar el modal para detener la carga
$('#modal_view_pdf').on('hidden.bs.modal', function () {
    $('#pdf_viewer_iframe').attr('src', '');
});

// Documento_G03 - Subir Documento PDF
$('#btn_save_upload_pdf').on('click', function () {
    const familyId = $('#siigo_family_id_upload').val();
    const documentName = $('#pdf_document_name').val();
    const fileInput = document.getElementById('file_input_pdf');
    const selectedPdf = fileInput.files[0];

    if (!selectedPdf) {
        swal('Atención', 'Selecciona un archivo PDF primero', 'warning');
        return;
    }

    if (selectedPdf.type !== 'application/pdf') {
        swal('Atención', 'Solo se permiten archivos PDF', 'warning');
        return;
    }

    let formData = new FormData();
    formData.append('document', selectedPdf);
    formData.append('family_id', familyId);
    formData.append('document_name', documentName);

    const btn = $(this);
    btn.prop('disabled', true).text('Subiendo...');

    $.ajax({
        url: SITE_URL + '/product/upload_family_document',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (resp) {
            if (typeof resp === 'string') {
                try {
                    resp = JSON.parse(resp);
                } catch (e) { }
            }

            if (resp.status === 'success') {
                swal('Éxito', 'Documento PDF subido correctamente', 'success');
                $('#modal_upload_pdf').modal('hide');

                // Recargar los documentos del producto automáticamente
                const familyId = $('#btn_upload_pdf').data('family-id');
                if (familyId) {
                    reloadDocuments(familyId);
                }
            } else {
                swal('Error', resp.message || 'Error al subir el documento', 'error');
            }
        },
        error: function (xhr, status, error) {
            swal('Error', 'Hubo un problema de conexión', 'error');
            console.error(error);
        },
        complete: function () {
            btn.prop('disabled', false).text('Subir Documento');
        }
    });
});


// Tabla_G05 - Ver PDF de Remisión
$(document).on('click', '[data-selector="ver_remision_pdf"]', function () {
    // 1.0 Mostrar modal de visor PDF - Asignar URL al iframe y abrir modal
    const iframe = document.getElementById('remision_pdf_iframe');
    iframe.src = SITE_URL + '/dispatch/view_pdf/' + $(this).data('id');
    $('#modal_view_remision').modal('show');
});

// Formulario_G05 - Agregar Línea de Remisión
$('#btn_agregar_linea_remision').on('click', function () {
    /* ----------------- MACRO-BLOQUE ----------------- */

    // 0.0 Variables Macro
    const MACRO_ADD_LINE = {
        tbody: document.getElementById('remision_items_body'),
        tr: document.createElement('tr')
    };

    // 1.0 Crear estructura de la línea - Construir HTML
    MACRO_ADD_LINE.tr.innerHTML = `
        <td>
            <input type="text" class="form-control" name="item_referencia[]" placeholder="Ej: REF-01">
        </td>
        <td>
            <input type="text" class="form-control" name="item_descripcion[]" placeholder="Descripción del item">
        </td>
        <td>
            <input type="text" class="form-control" name="item_lote[]" placeholder="Ej: L-01">
        </td>
        <td>
            <input type="date" class="form-control" name="item_vencimiento[]">
        </td>
        <td>
            <input type="number" class="form-control" name="item_cantidad[]" min="0" value="1">
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm btn-round btn-remove-item" data-selector="remover_linea_remision"><i class="fas fa-trash"></i></button>
        </td>
    `;
    // 1.1 Adjuntar línea al cuerpo de la tabla
    MACRO_ADD_LINE.tbody.appendChild(MACRO_ADD_LINE.tr);
});

// Formulario_G05 - Remover Línea de Remisión
$(document).on('click', '[data-selector="remover_linea_remision"]', function () {
    /* ----------------- MACRO-BLOQUE ----------------- */

    // 1.0 Remover línea de remisión - Identificar fila y verificar total
    const tr = $(this).closest('tr');
    if (document.querySelectorAll('#remision_items_body tr').length > 1) {
        tr.remove();
    } else {
        swal('Atención', 'Debe haber al menos una línea en la remisión.', 'warning');
    }
});

// Formulario_G05 - Guardar Remisión
$('#btn_guardar_remision').on('click', function () {
    /* ----------------- MACRO-BLOQUE ----------------- */

    // 0.0 Variables Macro
    const MACRO_SAVE = {
        ciudad: document.getElementById('remision_ciudad').value,
        form: document.getElementById('form_remision_create'),
        btn: document.querySelector('#remisiones_create_view .btn-success'),
        originalText: ''
    };

    // 1.0 Validar datos iniciales - Validar ciudad
    if (!MACRO_SAVE.ciudad) {
        swal('Validación', 'Por favor seleccione una ciudad para generar el consecutivo', 'warning');
        return;
    }

    // 2.0 Preparar y enviar petición - Cambiar estado del botón
    MACRO_SAVE.originalText = MACRO_SAVE.btn.innerHTML;
    MACRO_SAVE.btn.disabled = true;
    MACRO_SAVE.btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    // 2.1 Enviar AJAX
    $.ajax({
        url: SITE_URL + '/dispatch/save',
        type: 'POST',
        data: new FormData(MACRO_SAVE.form),
        processData: false,
        contentType: false,
        success: function (resp) {
            if (typeof resp === 'string') {
                try { resp = JSON.parse(resp); } catch (e) { }
            }
            if (resp.status === 'success') {
                swal('Éxito', resp.message, 'success').then(() => {
                    MACRO_SAVE.form.reset();
                    $('#remisiones_create_view').addClass('d-none');
                    $('#remisiones_list_view').removeClass('d-none');
                    if ($.fn.DataTable.isDataTable('#tbl_list_remisiones')) {
                        $('#tbl_list_remisiones').DataTable().ajax.reload(null, false);
                    }
                });
            } else {
                swal('Error', resp.message || 'Error al guardar la remisión', 'error');
            }
        },
        error: function () {
            swal('Error', 'Hubo un problema de conexión con el servidor', 'error');
        },
        complete: function () {
            MACRO_SAVE.btn.disabled = false;
            MACRO_SAVE.btn.innerHTML = MACRO_SAVE.originalText;
        }
    });
});

// Archivo_G03 - Eliminación lógica de archivos (documentos, imágenes, videos)
$(document).on('click', '.btn-delete-file', function (e) {
    console.log('DELETE CLICK', $(this).data('delete-type'), $(this).data('delete-id'));
    e.preventDefault();
    e.stopPropagation();

    // 0.0 Variables Macro
    const MACRO_DELETE = {
        type: $(this).data('delete-type'),
        id: $(this).data('delete-id'),
        btn: $(this),
        endpoints: {
            'document': '/product/delete_document',
            'picture': '/product/delete_picture',
            'video': '/product/delete_video'
        },
        labels: {
            'document': 'documento',
            'picture': 'imagen',
            'video': 'video'
        }
    };

    // 1.0 Confirmar eliminación con SweetAlert
    swal({
        title: '¿Eliminar ' + MACRO_DELETE.labels[MACRO_DELETE.type] + '?',
        text: 'Esta acción ocultará el archivo del listado.',
        icon: 'warning',
        buttons: {
            cancel: {
                text: 'Cancelar',
                value: null,
                visible: true
            },
            confirm: {
                text: 'Sí, eliminar',
                value: true,
                className: 'swal-button--danger'
            }
        },
        dangerMode: true
    }).then(function (isConfirm) {
        if (isConfirm) {
            // 2.0 Enviar petición AJAX de eliminación lógica
            $.ajax({
                url: SITE_URL + MACRO_DELETE.endpoints[MACRO_DELETE.type],
                type: 'POST',
                data: { id: MACRO_DELETE.id },
                dataType: 'json',
                success: function (resp) {
                    if (resp.status === 'success') {
                        swal('Eliminado', resp.message, 'success').then(function () {
                            // 3.0 Recargar contenido del producto para reflejar cambios
                            const familyId = $('#btn_upload_pdf').data('family-id');
                            if (familyId) {
                                // 3.1 Recargar documentos
                                reloadDocuments(familyId);
                                // 3.2 Recargar imágenes
                                reloadImages(familyId);
                                // 3.3 Recargar videos
                                reloadVideos(familyId);
                            }
                        });
                    } else {
                        swal('Error', resp.message || 'Error al eliminar', 'error');
                    }
                },
                error: function () {
                    swal('Error', 'Hubo un problema de conexión', 'error');
                }
            });
        }
    });
});

/* ======================================================== */
/*   BODEGAS (WAREHOUSE) EVENTS                             */
/* ======================================================== */

// Bodegas - Volver a la lista de bodegas desde la sub-pantalla de detalle
$('#btn_back_to_bodegas').on('click', function () {
    switchSubScreen('bodega_detail_view', 'bodega_list_view');
    $('#btn_add_warehouse_item').addClass('d-none');
    currentWarehouseData = null;
    currentWarehouseId = null;
});

// Bodegas - Autocompletado de productos desde la tabla de familias
let familySearchTimer = null;
let activeFamilyItemIndex = -1;

function resetWarehouseItemAutocomplete() {
    $('#in_item_family_id').val('');
    $('#in_item_family_search').val('').removeClass('is-invalid');
    $('#family_selected_badge').addClass('d-none');
    $('#family_selected_name').text('');
    $('#family_selected_id_badge').text('');
    $('#btn_clear_family_search').addClass('d-none');
    $('#family_autocomplete_dropdown').hide().empty();
    $('#in_item_lot').val('');
    $('#in_item_expiration_date').val('');
    activeFamilyItemIndex = -1;
}

function highlightFamilyMatch(text, query) {
    if (!query) return $('<div>').text(text).html();
    const safeText = $('<div>').text(text).html();
    const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex = new RegExp(`(${escapedQuery})`, 'gi');
    return safeText.replace(regex, '<mark class="bg-warning text-dark p-0">$1</mark>');
}

// Evento al escribir en el campo de búsqueda de familias
$('#in_item_family_search').on('input', function () {
    const query = $(this).val();
    const selectedId = $('#in_item_family_id').val();

    // Si el usuario edita el texto después de haber seleccionado, invalidar selección
    if (selectedId) {
        $('#in_item_family_id').val('');
        $('#family_selected_badge').addClass('d-none');
        $('#family_selected_name').text('');
        $('#family_selected_id_badge').text('');
    }

    if (query.trim().length > 0) {
        $('#btn_clear_family_search').removeClass('d-none');
    } else {
        $('#btn_clear_family_search').addClass('d-none');
        $('#family_autocomplete_dropdown').hide().empty();
        return;
    }

    clearTimeout(familySearchTimer);
    familySearchTimer = setTimeout(function () {
        const dropdown = $('#family_autocomplete_dropdown');
        dropdown.html(`
            <div class="p-3 text-center text-muted small">
                <i class="fas fa-spinner fa-spin me-2 text-success"></i>Buscando en familias...
            </div>
        `).show();

        $.ajax({
            url: SITE_URL + '/warehouse/search_families',
            type: 'GET',
            data: { q: query.trim() },
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success' && resp.data && resp.data.length > 0) {
                    let itemsHtml = '';
                    resp.data.forEach((item) => {
                        const escapedKeyword = $('<div>').text(item.keyword).html();
                        const highlighted = highlightFamilyMatch(item.keyword, query.trim());
                        itemsHtml += `
                            <a href="javascript:void(0);" class="dropdown-item family-autocomplete-item d-flex align-items-center py-2 px-3 border-bottom text-decoration-none" data-id="${item.id}" data-keyword="${escapedKeyword}">
                                <div class="avatar avatar-xs me-2">
                                    <span class="avatar-title rounded-circle bg-success-light text-success fw-bold">
                                        <i class="fas fa-box"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 text-truncate">
                                    <div class="fw-bold text-dark text-truncate">${highlighted}</div>
                                    <small class="text-muted">ID Familia: #${item.id}</small>
                                </div>
                                <span class="badge badge-success badge-sm ms-2"><i class="fas fa-check"></i> Elegir</span>
                            </a>
                        `;
                    });
                    dropdown.html(itemsHtml).show();
                    activeFamilyItemIndex = -1;
                } else {
                    dropdown.html(`
                        <div class="p-3 text-center text-muted small">
                            <i class="fas fa-exclamation-circle text-warning me-1"></i>
                            No se encontraron coincidencias en la tabla <strong>families</strong>.
                        </div>
                    `).show();
                }
            },
            error: function () {
                dropdown.html(`
                    <div class="p-3 text-center text-danger small">
                        <i class="fas fa-times-circle me-1"></i> Error al consultar familias.
                    </div>
                `).show();
            }
        });
    }, 250);
});

// Selección de un producto del listado autocompletado
$(document).on('click', '.family-autocomplete-item', function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    const keyword = $(this).data('keyword');

    $('#in_item_family_id').val(id);
    $('#in_item_family_search').val(keyword).removeClass('is-invalid');
    $('#family_selected_name').text(keyword);
    $('#family_selected_id_badge').text('ID #' + id);
    $('#family_selected_badge').removeClass('d-none');
    $('#btn_clear_family_search').removeClass('d-none');
    $('#family_autocomplete_dropdown').hide().empty();

    // Enfocar y seleccionar el valor del campo cantidad para facilitar el ingreso
    setTimeout(function () {
        const qtyEl = document.getElementById('in_item_quantity');
        if (qtyEl) {
            qtyEl.focus();
            qtyEl.select();
        }
    }, 50);
});

// Seleccionar automáticamente el número al enfocar el campo de cantidad
$('#in_item_quantity').on('focus', function () {
    const el = this;
    setTimeout(function () {
        el.select();
    }, 50);
});

// Botón para limpiar producto seleccionado
$('#btn_clear_family_search').on('click', function () {
    resetWarehouseItemAutocomplete();
    $('#in_item_family_search').focus();
});

// Navegación con teclado en el autocompletado (Flechas, Enter, Escape)
$('#in_item_family_search').on('keydown', function (e) {
    const dropdown = $('#family_autocomplete_dropdown');
    const items = dropdown.find('.family-autocomplete-item');

    if (!dropdown.is(':visible') || items.length === 0) {
        if (e.key === 'Enter') {
            e.preventDefault();
        }
        return;
    }

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        activeFamilyItemIndex = (activeFamilyItemIndex + 1) >= items.length ? 0 : (activeFamilyItemIndex + 1);
        items.removeClass('active bg-light');
        const current = items.eq(activeFamilyItemIndex);
        current.addClass('active bg-light');
        if (current[0]) {
            current[0].scrollIntoView({ block: 'nearest' });
        }
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        activeFamilyItemIndex = (activeFamilyItemIndex - 1) < 0 ? (items.length - 1) : (activeFamilyItemIndex - 1);
        items.removeClass('active bg-light');
        const current = items.eq(activeFamilyItemIndex);
        current.addClass('active bg-light');
        if (current[0]) {
            current[0].scrollIntoView({ block: 'nearest' });
        }
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (activeFamilyItemIndex >= 0 && activeFamilyItemIndex < items.length) {
            items.eq(activeFamilyItemIndex).trigger('click');
        }
    } else if (e.key === 'Escape') {
        dropdown.hide();
    }
});

// Cerrar autocompletado al hacer clic fuera del componente
$(document).on('click', function (e) {
    if (!$(e.target).closest('#in_item_family_search, #family_autocomplete_dropdown, #btn_clear_family_search').length) {
        $('#family_autocomplete_dropdown').hide();
        // Si el usuario escribió pero no seleccionó nada de la tabla de familias, limpiar para exigir selección
        const selectedId = $('#in_item_family_id').val();
        if (!selectedId) {
            $('#in_item_family_search').val('');
            $('#btn_clear_family_search').addClass('d-none');
        }
    }
});

// Bodegas - Preparar modal y verificar permiso antes de abrir modal de ingreso
$('#modal_add_warehouse_item').on('show.bs.modal', function (e) {
    if (!currentWarehouseData || !currentWarehouseData.is_main) {
        e.preventDefault();
        swal("No permitido", "Solo la bodega principal puede registrar ingresos de inventario.", "warning");
        return false;
    }
    $('#in_item_warehouse_id').val(currentWarehouseId);

    // Si la tabla no tiene filas, añadir la primera fila lista
    if ($('#remision_warehouse_items_body tr').length === 0) {
        addWarehouseRemisionLine();
    }
});

// Bodegas - Limpiar al cerrar modal de remisión de bodega
$('#modal_add_warehouse_item').on('hidden.bs.modal', function () {
    $('#form_add_warehouse_item')[0].reset();
    $('#remision_warehouse_items_body').empty();
});

// Bodegas - Agregar otra línea en la remisión de entrada
$('#btn_add_remision_warehouse_line').on('click', function () {
    addWarehouseRemisionLine();
});

// Bodegas - Remover línea de remisión de entrada
$(document).on('click', '.btn-remove-warehouse-remision-line', function () {
    $(this).closest('tr').remove();
    if ($('#remision_warehouse_items_body tr').length === 0) {
        addWarehouseRemisionLine();
    }
});

// Bodegas - Búsqueda de familias por línea con autocompletado dinámico
let remisionSearchTimer = null;
$(document).on('input', '.remision-family-search', function () {
    const input = $(this);
    const query = input.val().trim();
    const cell = input.closest('.remision-family-cell');
    const dropdown = cell.find('.remision-family-dropdown');
    const hiddenId = cell.find('.remision-family-id');
    const hiddenName = cell.find('.remision-family-name');

    hiddenId.val('');
    hiddenName.val('');

    clearTimeout(remisionSearchTimer);

    if (query.length < 1) {
        dropdown.hide().empty();
        cell.removeClass('is-searching');
        cell.closest('tr').removeClass('is-searching').css('z-index', '');
        return;
    }

    cell.addClass('is-searching');
    cell.closest('tr').addClass('is-searching').css('z-index', '1050');

    dropdown.html(`
        <div class="p-2 text-center text-muted small">
            <i class="fas fa-spinner fa-spin me-1 text-success"></i> Buscando familias...
        </div>
    `).show();

    remisionSearchTimer = setTimeout(function () {
        $.ajax({
            url: SITE_URL + '/warehouse/search_families',
            type: 'GET',
            data: { q: query },
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success' && resp.data && resp.data.length > 0) {
                    let itemsHtml = '';
                    resp.data.forEach(item => {
                        const escaped = $('<div>').text(item.keyword).html();
                        const highlighted = highlightFamilyMatch(item.keyword, query);
                        itemsHtml += `
                            <a href="javascript:void(0);" class="dropdown-item remision-family-dropdown-item py-2 px-3 border-bottom text-decoration-none" data-id="${item.id}" data-keyword="${escaped}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="me-2" style="white-space: normal; line-height: 1.35;">
                                        <span class="fw-bold text-dark">${highlighted}</span>
                                    </div>
                                    <span class="badge badge-success badge-sm flex-shrink-0 ms-2">ID #${item.id}</span>
                                </div>
                            </a>
                        `;
                    });
                    dropdown.html(itemsHtml).show();
                } else {
                    dropdown.html(`
                        <div class="p-3 text-center text-muted small">
                            <i class="fas fa-exclamation-circle text-warning me-1"></i> No se encontraron familias activas.
                        </div>
                    `).show();
                }
            },
            error: function () {
                dropdown.html(`
                    <div class="p-2 text-center text-danger small">
                        Error al consultar familias.
                    </div>
                `).show();
            }
        });
    }, 250);
});

// Bodegas - Selección de familia en la línea de remisión
$(document).on('click', '.remision-family-dropdown-item', function (e) {
    e.preventDefault();
    const item = $(this);
    const famId = item.data('id');
    const keyword = item.data('keyword');
    const cell = item.closest('.remision-family-cell');

    cell.find('.remision-family-id').val(famId);
    cell.find('.remision-family-name').val(keyword);
    cell.find('.remision-family-label').text(keyword);
    cell.find('.remision-search-group').addClass('d-none');
    cell.find('.remision-family-selected').removeClass('d-none');
    cell.find('.remision-family-dropdown').hide().empty();
    cell.removeClass('is-searching');
    cell.closest('tr').removeClass('is-searching').css('z-index', '');
});

// Bodegas - Limpiar selección de familia para volver a buscar
$(document).on('click', '.btn-clear-remision-family', function () {
    const cell = $(this).closest('.remision-family-cell');
    cell.find('.remision-family-id').val('');
    cell.find('.remision-family-name').val('');
    cell.find('.remision-family-label').text('');
    cell.find('.remision-family-selected').addClass('d-none');
    cell.find('.remision-search-group').removeClass('d-none');
    cell.find('.remision-family-search').val('').focus();
});

// Cerrar desplegables de búsqueda al hacer clic fuera
$(document).on('click', function (e) {
    if (!$(e.target).closest('.remision-family-cell').length) {
        $('.remision-family-dropdown').hide();
        $('.remision-family-cell').removeClass('is-searching');
        $('#tbl_remision_warehouse_items tbody tr').removeClass('is-searching').css('z-index', '');
    }
});

// Bodegas - Guardar nueva bodega
$('#btn_save_warehouse').on('click', function () {
    const form = $('#form_create_warehouse');
    const name = $('#in_warehouse_name').val().trim();
    const adress = $('#in_warehouse_adress').val().trim();

    if (!name || !adress) {
        swal("Atención", "Por favor complete todos los campos obligatorios.", "warning");
        return;
    }

    const btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');

    $.ajax({
        url: SITE_URL + '/warehouse/save',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (resp) {
            btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Bodega');
            if (resp.status === 'success') {
                $('#modal_create_warehouse').modal('hide');
                form[0].reset();
                swal("¡Éxito!", resp.message, "success");
                reloadBodegasTable();
            } else {
                swal("Error", resp.message || "No se pudo crear la bodega.", "error");
            }
        },
        error: function () {
            btn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Bodega');
            swal("Error", "Ocurrió un error al comunicarse con el servidor.", "error");
        }
    });
});

// Bodegas - Guardar Ingreso de Artículos a la Bodega (Genera remisión automáticamente en el backend)
$('#btn_save_warehouse_item').on('click', function () {
    const form = $('#form_add_warehouse_item');

    if (!currentWarehouseData || !currentWarehouseData.is_main) {
        swal("No permitido", "Solo se permite registrar ingresos directamente en la bodega principal de la empresa.", "warning");
        $('#modal_add_warehouse_item').modal('hide');
        return;
    }

    // Validar líneas
    let hasValidLines = false;
    let hasErrors = false;

    $('#remision_warehouse_items_body tr').each(function (index) {
        const row = $(this);
        const famId = row.find('.remision-family-id').val();
        const searchInput = row.find('.remision-family-search');
        const rawName = searchInput.val() ? searchInput.val().trim() : '';
        const qty = parseInt(row.find('input[name="item_cantidad[]"]').val() || 0);

        if (!famId && !rawName) {
            swal("Atención", `Debe seleccionar un producto de la tabla de familias en la línea #${index + 1}.`, "warning");
            searchInput.focus();
            hasErrors = true;
            return false;
        }

        if (qty <= 0) {
            swal("Atención", `La cantidad en la línea #${index + 1} debe ser mayor a 0.`, "warning");
            row.find('input[name="item_cantidad[]"]').focus();
            hasErrors = true;
            return false;
        }

        hasValidLines = true;
    });

    if (hasErrors) return;

    if (!hasValidLines) {
        swal("Atención", "Debe agregar al menos una línea con producto y cantidad válida.", "warning");
        return;
    }

    $('#in_item_warehouse_id').val(currentWarehouseId);

    const btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Guardando...');

    $.ajax({
        url: SITE_URL + '/warehouse/save_remision',
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (resp) {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Guardar Ingreso');
            if (resp.status === 'success') {
                $('#modal_add_warehouse_item').modal('hide');
                form[0].reset();
                $('#remision_warehouse_items_body').empty();
                swal("¡Ingreso Registrado!", resp.message, "success");
                if (currentWarehouseId) {
                    viewWarehouseBalance(currentWarehouseId);
                }
                reloadBodegasTable();
                if ($.fn.DataTable.isDataTable('#tbl_list_remisiones')) {
                    $('#tbl_list_remisiones').DataTable().ajax.reload(null, false);
                }
            } else {
                swal("Error", resp.message || "No se pudo generar la remisión.", "error");
            }
        },
        error: function () {
            btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Guardar Ingreso');
            swal("Error", "Ocurrió un error al procesar el ingreso.", "error");
        }
    });
});

// Bodegas - Abrir modal de transferencia entre bodegas
$('#btn_open_transfer_modal').on('click', function () {
    if (!currentWarehouseId || !currentWarehouseData) {
        swal("Atención", "No hay una bodega activa seleccionada.", "warning");
        return;
    }

    if (currentWarehouseItems.length === 0) {
        swal("Atención", "Esta bodega no tiene artículos disponibles con saldo para transferir.", "info");
        return;
    }

    $('#transfer_origin_warehouse_id').val(currentWarehouseId);
    $('#transfer_origin_warehouse_name').text(currentWarehouseData.name || 'Bodega');

    // Cargar lista de bodegas destino disponibles
    const selectDest = $('#transfer_dest_warehouse');
    selectDest.html('<option value="">Cargando bodegas...</option>');

    $.ajax({
        url: SITE_URL + '/warehouse/active_list/' + currentWarehouseId,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            if (resp.status === 'success') {
                selectDest.empty();
                selectDest.append('<option value="">Seleccione bodega destino...</option>');
                const list = resp.data || [];
                if (list.length === 0) {
                    selectDest.html('<option value="">No hay otras bodegas activas disponibles</option>');
                } else {
                    list.forEach(w => {
                        selectDest.append(`<option value="${w.id}">${$('<div>').text(w.name).html()} (${$('<div>').text(w.adress).html()})</option>`);
                    });
                }
            } else {
                selectDest.html('<option value="">Error al cargar bodegas</option>');
            }
        },
        error: function () {
            selectDest.html('<option value="">Error de conexión</option>');
        }
    });

    // Limpiar filas de artículos e insertar la primera fila
    $('#transfer_lines_tbody').empty();
    addTransferLine();

    $('#modal_transfer_warehouse').modal('show');
});

// Bodegas - Agregar otra línea de transferencia
$('#btn_add_transfer_line').on('click', function () {
    addTransferLine();
});

// Bodegas - Quitar línea de transferencia
$(document).on('click', '.btn-remove-transfer-line', function () {
    $(this).closest('tr').remove();
    if ($('#transfer_lines_tbody tr').length === 0) {
        addTransferLine();
    }
});

// Bodegas - Cambio en selector de artículo en la línea de transferencia
$(document).on('change', '.transfer-item-select', function () {
    const select = $(this);
    const row = select.closest('tr');
    const selectedVal = select.val();
    const badge = row.find('.transfer-avail-badge');
    const inputQty = row.find('.transfer-qty-input');
    const hiddenName = row.find('.transfer-item-name');

    if (!selectedVal) {
        badge.text('0').removeClass('badge-success badge-warning').addClass('badge-secondary');
        inputQty.val('').prop('disabled', true).attr('max', 0).attr('placeholder', 'Cant.');
        hiddenName.val('');
        return;
    }

    const selectedId = parseInt(selectedVal);
    const found = currentWarehouseItems.find(i => parseInt(i.id) === selectedId || i.name_item === selectedVal);
    const available = found ? parseInt(found.quantity || 0) : 0;
    const itemName = found ? (found.family_name || found.name_item || ('Producto #' + found.id)) : '';

    hiddenName.val(itemName);

    badge.text(available.toLocaleString());
    if (available > 10) {
        badge.removeClass('badge-secondary badge-warning').addClass('badge-success');
    } else {
        badge.removeClass('badge-secondary badge-success').addClass('badge-warning');
    }

    // No forzar 1 por defecto para que el usuario pueda escribir directamente su cantidad
    inputQty.prop('disabled', false).attr('max', available).attr('min', 1).val('').attr('placeholder', `1 - ${available}`).focus();
});

// Auto-seleccionar el texto al hacer clic o enfocar para cambiar el número sin tener que borrarlo manualmente
$(document).on('focus', '.transfer-qty-input, input[name="item_cantidad[]"]', function () {
    $(this).select();
});

// Bodegas - Validación en tiempo real de cantidad a transferir (permite borrar y escribir libremente)
$(document).on('input', '.transfer-qty-input', function () {
    const input = $(this);
    const max = parseInt(input.attr('max') || 0);
    const raw = input.val();
    if (raw === '') return; // Permite limpiar el campo para escribir el número deseado

    const val = parseInt(raw);
    if (!isNaN(val) && max > 0 && val > max) {
        input.val(max);
        swal("Atención", `La cantidad máxima disponible para transferir es de ${max} unidades.`, "warning");
    }
});

// Al salir del campo (blur), solo si se ingresó un valor inválido menor a 1 se corrige
$(document).on('blur', '.transfer-qty-input', function () {
    const input = $(this);
    const max = parseInt(input.attr('max') || 0);
    const raw = input.val().trim();
    if (raw !== '') {
        const val = parseInt(raw);
        if (isNaN(val) || val <= 0) {
            if (max > 0) input.val(1);
        }
    }
});

// Bodegas - Confirmar y enviar transferencia
$('#btn_submit_transfer').on('click', function () {
    const destWarehouse = $('#transfer_dest_warehouse').val();
    if (!destWarehouse) {
        swal("Atención", "Debe seleccionar una bodega de destino.", "warning");
        return;
    }

    // Validar que haya al menos una línea con producto y cantidad
    let hasValidItems = false;
    let hasErrors = false;
    const selectedItems = new Set();

    $('#transfer_lines_tbody tr').each(function () {
        const select = $(this).find('.transfer-item-select');
        const input = $(this).find('.transfer-qty-input');
        const selectedVal = select.val();
        const qty = parseInt(input.val() || 0);
        const max = parseInt(input.attr('max') || 0);
        const itemText = select.find('option:selected').text();

        if (selectedVal) {
            if (selectedItems.has(selectedVal)) {
                swal("Atención", `La opción "${itemText}" está duplicada en varias líneas. Por favor consolídela en una sola fila.`, "warning");
                hasErrors = true;
                return false;
            }
            selectedItems.add(selectedVal);

            if (isNaN(qty) || qty <= 0 || qty > max) {
                swal("Atención", `Por favor ingrese una cantidad válida (entre 1 y ${max}) para "${itemText}".`, "warning");
                input.focus();
                hasErrors = true;
                return false;
            }
            hasValidItems = true;
        }
    });

    if (hasErrors) return;

    if (!hasValidItems) {
        swal("Atención", "Debe seleccionar al menos un artículo para transferir.", "warning");
        return;
    }

    const btn = $(this);
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Procesando...');

    $.ajax({
        url: SITE_URL + '/warehouse/transfer',
        type: 'POST',
        data: $('#form_transfer_warehouse').serialize(),
        dataType: 'json',
        success: function (resp) {
            btn.prop('disabled', false).html('<i class="fas fa-exchange-alt me-1"></i> Confirmar Transferencia');
            if (resp.status === 'success') {
                $('#modal_transfer_warehouse').modal('hide');
                swal("¡Transferencia Exitosa!", resp.message, "success");
                // Recargar datos de la bodega actual y de la lista principal
                if (currentWarehouseId) {
                    viewWarehouseBalance(currentWarehouseId);
                }
                reloadBodegasTable();
                if ($.fn.DataTable.isDataTable('#tbl_list_remisiones')) {
                    $('#tbl_list_remisiones').DataTable().ajax.reload(null, false);
                }
            } else {
                swal("Error en Transferencia", resp.message || "No se pudo completar la transferencia.", "error");
            }
        },
        error: function () {
            btn.prop('disabled', false).html('<i class="fas fa-exchange-alt me-1"></i> Confirmar Transferencia');
            swal("Error", "Ocurrió un error al comunicarse con el servidor.", "error");
        }
    });
});

