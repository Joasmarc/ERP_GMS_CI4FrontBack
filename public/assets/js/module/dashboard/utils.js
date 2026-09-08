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
    // Asegurar que las sub-pantallas vuelvan a su estado de lista por defecto al navegar
    $('#proveedor_list_view').removeClass('d-none');
    $('#cliente_list_view').removeClass('d-none');
    $('#counterparty_create_view').addClass('d-none');
    $('#bodega_list_view').removeClass('d-none');
    $('#bodega_detail_view').addClass('d-none');
    $('#btn_add_warehouse_item').addClass('d-none');
    $('#btn_open_mass_upload_modal').addClass('d-none');

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
                            <a href="javascript:void(0)" class="text-danger text-decoration-none btn-view-pdf-doc" data-path="${SITE_URL}${doc.path}" data-name="${doc.name}">
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

/* ======================================================== */
/*   BODEGAS (WAREHOUSE) UTILS                              */
/* ======================================================== */

/**
 * Agregar una nueva fila de artículo a la tabla de remisión de bodega
 */
function addWarehouseRemisionLine() {
    const rowId = 'rem_row_' + Date.now() + '_' + Math.floor(Math.random() * 1000);
    const rowHtml = `
        <tr id="${rowId}">
            <td>
                <div class="position-relative remision-family-cell">
                    <div class="remision-search-group position-relative">
                        <i class="fas fa-search remision-search-icon"></i>
                        <input type="text" class="form-control remision-family-search" placeholder="Buscar familia o producto..." autocomplete="off" required>
                    </div>
                    <input type="hidden" name="id_family[]" class="remision-family-id" value="" required>
                    <input type="hidden" name="item_name[]" class="remision-family-name" value="">
                    <div class="remision-family-selected d-none">
                        <div class="d-flex align-items-center text-truncate me-2">
                            <i class="fas fa-check-circle text-success me-2 fs-5"></i>
                            <span class="fw-bold text-dark fs-6 remision-family-label text-truncate"></span>
                        </div>
                        <button type="button" class="btn btn-outline-danger btn-round p-1 px-2 btn-clear-remision-family" title="Cambiar producto">
                            <i class="fas fa-times me-1"></i>Cambiar
                        </button>
                    </div>
                    <div class="dropdown-menu w-100 shadow-lg p-0 mt-1 border-0 remision-family-dropdown" style="max-height: 260px; overflow-y: auto; z-index: 1080; display: none;"></div>
                </div>
            </td>
            <td>
                <input type="text" class="form-control" name="item_referencia[]" placeholder="Ej: REF-01">
            </td>
            <td>
                <input type="text" class="form-control" name="item_lote[]" placeholder="Ej: L-01" maxlength="25">
            </td>
            <td>
                <input type="date" class="form-control" name="item_vencimiento[]">
            </td>
            <td>
                <input type="number" class="form-control text-center fw-bold" name="item_cantidad[]" min="1" value="1" required>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-round btn-remove-warehouse-remision-line" title="Quitar fila">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `;
    $('#remision_warehouse_items_body').append(rowHtml);
}

/**
 * Agregar una nueva fila de artículo a la tabla del modal de ajuste de bodega
 */
function addWarehouseAdjustLine() {
    let optionsHtml = '<option value="">Seleccione un artículo / lote...</option>';
    if (typeof currentWarehouseItems !== 'undefined' && currentWarehouseItems && currentWarehouseItems.length > 0) {
        currentWarehouseItems.forEach(item => {
            const itemName = item.family_name || item.name_item || ('Producto #' + (item.id_family || item.id));
            const lotInfo = (item.lot && item.lot.trim() !== '') ? ` [Lote: ${item.lot.trim()}]` : ' [Sin Lote]';
            let expInfo = '';
            if (item.expiration_date && item.expiration_date !== '0000-00-00' && item.expiration_date.trim() !== '') {
                const expOnly = item.expiration_date.split(' ')[0];
                expInfo = ` - Vence: ${expOnly}`;
            }
            const safeItemName = $('<div>').text(itemName).html();
            const safeLotInfo = $('<div>').text(lotInfo).html();
            const safeExpInfo = $('<div>').text(expInfo).html();
            const currentQty = parseInt(item.quantity || 0);
            optionsHtml += `<option value="${item.id}" data-quantity="${currentQty}">${safeItemName}${safeLotInfo}${safeExpInfo} (Saldo: ${currentQty})</option>`;
        });
    } else {
        optionsHtml = '<option value="">No hay artículos registrados con saldo en esta bodega</option>';
    }

    const rowHtml = `
        <tr>
            <td>
                <select name="balance_id[]" class="form-select adjust-item-select" required>
                    ${optionsHtml}
                </select>
            </td>
            <td class="text-center">
                <span class="badge badge-secondary fs-6 adjust-current-badge">0</span>
            </td>
            <td>
                <input type="number" class="form-control text-center fw-bold adjust-qty-input" name="item_cantidad[]" step="1" placeholder="Ej: 5 o -5" required disabled>
            </td>
            <td class="text-center">
                <span class="badge badge-light border fs-6 adjust-new-badge">-</span>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-outline-danger btn-round btn-remove-warehouse-adjust-line" title="Quitar fila">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `;
    $('#adjust_warehouse_items_body').append(rowHtml);
}

/**
 * Agregar una nueva fila de artículo a la tabla del modal de transferencia
 */
function addTransferLine() {
    let optionsHtml = '<option value="">Seleccione un artículo / lote...</option>';
    currentWarehouseItems.forEach(item => {
        const itemName = item.family_name || item.name_item || ('Producto #' + item.id);
        const lotInfo = (item.lot && item.lot.trim() !== '') ? `[Lote: ${item.lot.trim()}]` : '[Sin Lote]';
        const safeItemName = $('<div>').text(itemName).html();
        const safeLotInfo = $('<div>').text(lotInfo).html();
        optionsHtml += `<option value="${item.id}" data-name="${safeItemName}">${safeItemName} ${safeLotInfo}</option>`;
    });

    const rowHtml = `
        <tr>
            <td>
                <select name="balance_id[]" class="form-select transfer-item-select" required>
                    ${optionsHtml}
                </select>
                <input type="hidden" name="item_name[]" class="transfer-item-name" value="">
            </td>
            <td class="text-center">
                <span class="badge badge-secondary fs-6 transfer-avail-badge">0</span>
            </td>
            <td>
                <input type="number" name="item_quantity[]" class="form-control text-center transfer-qty-input" min="1" max="0" value="" placeholder="Cant." required disabled>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm btn-round btn-remove-transfer-line" title="Quitar línea">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;

    $('#transfer_lines_tbody').append(rowHtml);
}

/**
 * Cargar y mostrar la sub-pantalla con el balance/inventario de una bodega (warehouses_balance)
 */
function viewWarehouseBalance(warehouseId) {
    if (!warehouseId) return;
    currentWarehouseId = warehouseId;

    toggleLoader(true);

    $.ajax({
        url: SITE_URL + '/warehouse/balance/' + warehouseId,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            toggleLoader(false);
            if (resp.status === 'success') {
                const warehouse = resp.warehouse;
                const rawItems = resp.data || [];
                // No mostrar líneas que tienen cantidades en 0 en el módulo de bodegas
                const items = rawItems.filter(i => parseInt(i.quantity || 0) > 0);

                const isMainWarehouse = resp.is_main === true || (warehouse && warehouse.is_main === true);

                currentWarehouseData = warehouse;
                if (currentWarehouseData) {
                    currentWarehouseData.is_main = isMainWarehouse;
                }

                // Guardar artículos con saldo disponible > 0 para transferencias
                currentWarehouseItems = items;

                // Actualizar títulos e información de cabecera
                $('#bodega_detail_title').text(warehouse.name || 'Bodega');
                $('#bodega_detail_adress').text(warehouse.adress || '');
                
                let statusBadge = warehouse.state === 'ACTIVE' 
                    ? '<span class="badge badge-success"><i class="fas fa-check-circle me-1"></i> Activo</span>'
                    : '<span class="badge badge-danger"><i class="fas fa-times-circle me-1"></i> Inactivo</span>';

                if (isMainWarehouse) {
                    statusBadge += ' <span class="badge badge-primary ms-1"><i class="fas fa-star me-1"></i> Bodega Principal</span>';
                }
                $('#bodega_detail_status').html(statusBadge);

                // Mostrar botón "Agregar Artículo" y "Cargue Masivo" únicamente si es la bodega principal
                if (isMainWarehouse) {
                    $('#btn_add_warehouse_item').removeClass('d-none');
                    $('#btn_open_mass_upload_modal').removeClass('d-none');
                } else {
                    $('#btn_add_warehouse_item').addClass('d-none');
                    $('#btn_open_mass_upload_modal').addClass('d-none');
                }

                // Actualizar total de artículos con saldo disponible
                $('#stat_bodega_items').text(items.length);

                // Actualizar o inicializar DataTable del balance
                renderBalanceTable(items);

                // Cambiar sub-pantalla
                switchSubScreen('bodega_list_view', 'bodega_detail_view');
            } else {
                swal("Error", resp.message || "No se pudo cargar la información de la bodega.", "error");
            }
        },
        error: function () {
            toggleLoader(false);
            swal("Error", "Error al consultar los datos del servidor.", "error");
        }
    });
}

/**
 * Agrupar balance de inventario por familias de productos (solo ítems con stock > 0)
 */
function groupWarehouseBalanceByFamily(items) {
    const map = new Map();

    (items || []).forEach(item => {
        const qty = parseInt(item.quantity || 0);
        if (qty <= 0) return; // Omitir cualquier línea con stock en 0

        const famId = item.id_family || 0;
        const famName = item.family_name || item.name_item || 'Sin nombre';
        const key = famId > 0 ? `fam_${famId}` : `name_${famName}`;

        if (!map.has(key)) {
            map.set(key, {
                id_family: famId > 0 ? famId : item.id,
                family_name: famName,
                total_quantity: 0,
                latest_update: item.updated_at || item.created_at || '-',
                variations: []
            });
        }

        const group = map.get(key);
        group.total_quantity += qty;
        group.variations.push(item);

        if (item.updated_at && (!group.latest_update || group.latest_update === '-' || item.updated_at > group.latest_update)) {
            group.latest_update = item.updated_at;
        }
    });

    return Array.from(map.values()).filter(g => g.total_quantity > 0);
}

/**
 * Sub-líneas que lucen visualmente como filas de la tabla sin ID y sin repetir el nombre (solo stock > 0)
 */
function formatFamilySubLines(rowData) {
    if (!rowData || !rowData.variations) {
        return '';
    }

    const validVariations = rowData.variations.filter(v => parseInt(v.quantity || 0) > 0);
    if (validVariations.length <= 1) {
        return '';
    }

    let rowsHtml = '';
    validVariations.forEach(v => {
        const safeLot = v.lot 
            ? `<span class="badge badge-secondary"><i class="fas fa-barcode me-1"></i>${$('<div>').text(v.lot).html()}</span>` 
            : '<span class="text-muted small">-</span>';
        const safeExp = v.expiration_date 
            ? `<span class="small text-muted"><i class="fas fa-calendar-alt me-1 text-secondary"></i>${$('<div>').text(v.expiration_date.split(' ')[0]).html()}</span>` 
            : '<span class="text-muted small">-</span>';
        const qty = parseInt(v.quantity || 0);
        const badgeClass = qty > 10 ? 'badge-success' : 'badge-warning';
        const safeDate = v.updated_at ? $('<div>').text(v.updated_at).html() : '-';

        rowsHtml += `
            <tr class="subline-row">
                <td class="border-top-0 text-center"></td>
                <td class="border-top-0 ps-3 text-muted">
                    <i class="fas fa-level-up-alt fa-rotate-90 text-muted opacity-50 me-2"></i>
                </td>
                <td class="border-top-0">${safeLot}</td>
                <td class="border-top-0">${safeExp}</td>
                <td class="text-center border-top-0">
                    <span class="badge ${badgeClass} fs-6 fw-bold">${qty.toLocaleString()}</span>
                </td>
                <td class="border-top-0 small text-muted">${safeDate}</td>
            </tr>
        `;
    });

    return `
        <table class="table table-hover mb-0 w-100 align-middle subline-table">
            <tbody>
                ${rowsHtml}
            </tbody>
        </table>
    `;
}

/**
 * Sincronizar el ancho de las columnas de la sub-tabla con la tabla principal
 */
function syncSublineColumns() {
    setTimeout(function () {
        const ths = $('#tbl_list_bodega_balance thead th');
        if (ths.length < 6) return;

        const colWidths = [];
        ths.each(function () {
            colWidths.push($(this).outerWidth());
        });

        $('.subline-table tr.subline-row').each(function () {
            $(this).find('> td').each(function (i) {
                if (colWidths[i] !== undefined) {
                    $(this).css({
                        'width': colWidths[i] + 'px',
                        'min-width': colWidths[i] + 'px',
                        'max-width': colWidths[i] + 'px',
                        'box-sizing': 'border-box'
                    });
                }
            });
        });
    }, 20);
}

/**
 * Renderizar la tabla de balance agrupada por familia con sub-líneas permanentemente visibles
 */
function renderBalanceTable(items) {
    if ($.fn.DataTable.isDataTable('#tbl_list_bodega_balance')) {
        $('#tbl_list_bodega_balance').DataTable().destroy();
        $('#tbl_list_bodega_balance tbody').off();
    }

    const groupedData = groupWarehouseBalanceByFamily(items);

    dtBodegaBalance = $("#tbl_list_bodega_balance").DataTable({
        data: groupedData,
        autoWidth: false,
        columns: [
            {
                data: null,
                width: '50px',
                render: function (data, type, row) {
                    if (row && row.variations && row.variations.length === 1) {
                        return row.variations[0].id || '-';
                    }
                    return (row && row.id_family) ? row.id_family : (row ? row.id : '-');
                }
            },
            { 
                data: 'family_name',
                defaultContent: '',
                render: function (data) {
                    const safeName = $('<div>').text(data || 'Sin nombre').html();
                    return `<div class="d-inline-flex align-items-center py-1 text-wrap">
                        <i class="fas fa-box text-secondary me-2 fs-6 flex-shrink-0"></i>
                        <strong class="text-dark" style="font-size: 0.94rem; word-break: break-word;">${safeName}</strong>
                    </div>`;
                }
            },
            {
                data: 'variations',
                width: '120px',
                render: function (variations) {
                    if (!variations || variations.length === 0) return '<span class="text-muted small">-</span>';
                    if (variations.length === 1) {
                        const lot = variations[0].lot;
                        return lot ? `<span class="badge badge-secondary"><i class="fas fa-barcode me-1"></i>${$('<div>').text(lot).html()}</span>` : '<span class="text-muted small">-</span>';
                    }
                    return `<span class="badge badge-secondary"><i class="fas fa-layer-group me-1"></i>${variations.length} lotes</span>`;
                }
            },
            {
                data: 'variations',
                width: '120px',
                render: function (variations) {
                    if (!variations || variations.length === 0) return '<span class="text-muted small">-</span>';
                    if (variations.length === 1) {
                        const exp = variations[0].expiration_date;
                        return exp ? `<span class="small text-muted"><i class="fas fa-calendar-alt me-1 text-secondary"></i>${$('<div>').text(exp.split(' ')[0]).html()}</span>` : '<span class="text-muted small">-</span>';
                    }
                    return '<span class="text-muted small">-</span>';
                }
            },
            { 
                data: null,
                width: '100px',
                className: 'text-center',
                render: function (data, type, row) {
                    let qty = 0;
                    if (row && row.variations && row.variations.length === 1) {
                        qty = parseInt(row.variations[0].quantity || 0);
                    } else if (row) {
                        qty = parseInt(row.total_quantity || 0);
                    }
                    let badgeClass = qty > 10 ? 'badge-success' : (qty > 0 ? 'badge-warning' : 'badge-danger');
                    return `<span class="badge ${badgeClass} fs-6 fw-bold">${qty.toLocaleString()}</span>`;
                }
            },
            {
                data: null,
                width: '150px',
                render: function (data, type, row) {
                    let updateVal = '-';
                    if (row && row.variations && row.variations.length === 1) {
                        updateVal = row.variations[0].updated_at || '-';
                    } else if (row) {
                        updateVal = row.latest_update || '-';
                    }
                    return updateVal ? `<span class="small text-muted">${updateVal}</span>` : '-';
                }
            }
        ],
        processing: true,
        pageLength: 10,
        language: getDatatablesLanguageBodegas(),
        createdRow: function (row, data) {
            // Aplicar estilo de fila padre solo si la familia tiene más de 1 lote
            if (data && data.variations && data.variations.length > 1) {
                $(row).addClass('family-parent-row');
            }
        },
        drawCallback: function () {
            // Solo mostrar sub-líneas si la familia tiene más de 1 lote
            const api = this.api();
            api.rows({ page: 'current' }).every(function () {
                const data = this.data();
                if (data && data.variations && data.variations.length > 1) {
                    this.child(formatFamilySubLines(data), 'child-subline p-0').show();
                } else {
                    this.child.hide();
                }
            });
            syncSublineColumns();
        }
    });

    $(window).off('resize.sublineColumns').on('resize.sublineColumns', syncSublineColumns);
}

/**
 * Recargar la tabla de bodegas
 */
function reloadBodegasTable() {
    if (dtBodegas) {
        dtBodegas.ajax.reload(null, false);
    }
}

/**
 * Configuración de idioma español para DataTables de Bodegas
 */
function getDatatablesLanguageBodegas() {
    return {
        processing: 'Procesando...',
        lengthMenu: 'Mostrar _MENU_ registros',
        zeroRecords: 'No se encontraron registros',
        emptyTable: 'No hay datos disponibles en esta tabla',
        info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
        infoEmpty: 'Mostrando 0 a 0 de 0 registros',
        infoFiltered: '(filtrado de _MAX_ registros totales)',
        search: 'Buscar:',
        paginate: {
            first: 'Primero',
            last: 'Último',
            next: 'Siguiente',
            previous: 'Anterior'
        }
    };
}

