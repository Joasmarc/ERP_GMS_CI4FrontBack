/* ======================================================== */
/*   GLOBAL                                               */
/* ======================================================== */

const SITE_URL = window.location.hostname === 'localhost' ? 'http://localhost/adminitradorgm' : '';

const SCREENS = {
    dashboard: ['cont_dashboard', 'Dashboard'],
    product_listing: ['cont_product', 'Productos'],
    remisiones: ['cont_remisiones', 'Remisiones'],
}

/* ======================================================== */
/*   STATES                                               */
/* ======================================================== */

let GLOBAL_PRODUCTS_DATA = [];

/* ======================================================== */
/*   INIT                                                 */
/* ======================================================== */

$(function () {

    $(document).ready(function () {
        // 0.0 Variables Macro
        const MACRO_INIT = {
            contenedor_catalogo: $('#catalog_list_productos'),
            productos_agrupados: {},
            html_catalogo: ''
        };

        // 1.0 Cargar catálogo de productos - Mostrar loader
        MACRO_INIT.contenedor_catalogo.html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3">Cargando catálogo...</p></div>');
        // 1.1 Ejecutar petición AJAX
        $.ajax({
            url: SITE_URL + '/product/listing_siigo',
            type: 'GET',
            dataType: 'json',
            success: function (resp) {
                GLOBAL_PRODUCTS_DATA = resp.data || [];
                // 2.0 Procesar productos obtenidos - Verificar si hay productos
                MACRO_INIT.contenedor_catalogo.empty();
                if (GLOBAL_PRODUCTS_DATA.length === 0) {
                    MACRO_INIT.contenedor_catalogo.html('<div class="col-12 text-center py-5"><i class="fas fa-box-open fa-3x mb-3 text-muted"></i><p class="text-muted">No se encontraron productos disponibles.</p></div>');
                    return;
                }
                // 2.1 Agrupar por nombre similar
                GLOBAL_PRODUCTS_DATA.forEach(p => {
                    let nombre_bruto = p.nombre ? p.nombre.toLowerCase().trim() : 'sin nombre';
                    if (!MACRO_INIT.productos_agrupados[nombre_bruto]) {
                        MACRO_INIT.productos_agrupados[nombre_bruto] = { principal: p, count: 1, ids: [p.id] };
                    } else {
                        MACRO_INIT.productos_agrupados[nombre_bruto].count++;
                        MACRO_INIT.productos_agrupados[nombre_bruto].ids.push(p.id);
                    }
                });
                // 2.2 Generar HTML del catálogo
                Object.values(MACRO_INIT.productos_agrupados).forEach(grupo => {
                    let p = grupo.principal;
                    let count = grupo.count;
                    let nombre = p.nombre ? p.nombre.charAt(0).toUpperCase() + p.nombre.slice(1) : 'Sin nombre';
                    let categoria = p.id_categoria ? p.id_categoria.charAt(0).toUpperCase() + p.id_categoria.slice(1) : 'Sin categoría';
                    let marca = p.id_marca ? p.id_marca.charAt(0).toUpperCase() + p.id_marca.slice(1) : 'Sin marca';
                    let presentacion = p.presentacion ? p.presentacion.charAt(0).toUpperCase() + p.presentacion.slice(1) : 'Sin presentación';
                    let imgSrc = p.img ? (SITE_URL + '/' + p.img.replace(/^\/+/, '')) : (SITE_URL + '/public/assets/img/kaiadmin/favicon.ico');
                    let badgeHtml = count > 1 ? `<span class="badge badge-primary position-absolute" style="top: 10px; right: 10px; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">${count} Referencias</span>` : '';
                    MACRO_INIT.html_catalogo += `
                        <div class="col-sm-6 col-md-4 col-lg-3 mb-4 product-card-item" 
                             data-nombre="${nombre.toLowerCase()}" 
                             data-categoria="${categoria.toLowerCase()}" 
                             data-marca="${marca.toLowerCase()}">
                            <div class="card card-post card-round h-100 shadow-sm border-0 position-relative" 
                                 style="transition: all 0.3s ease;"
                                 onmouseover="this.style.transform='translateY(-5px)'; this.classList.remove('shadow-sm'); this.classList.add('shadow');" 
                                 onmouseout="this.style.transform='none'; this.classList.remove('shadow'); this.classList.add('shadow-sm');">
                                ${badgeHtml}
                                <div class="card-img-container p-3 d-flex align-items-center justify-content-center" style="height: 200px; background-color: #f8f9fa; border-radius: 10px 10px 0 0;">
                                    <img class="card-img-top" src="${imgSrc}" alt="${nombre}" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h3 class="card-title text-primary font-weight-bold mb-3" style="font-size: 1.1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; min-height: 2.8rem;">${nombre}</h3>
                                    <div class="mb-auto">
                                        <p class="card-text text-muted mb-1"><i class="fas fa-tags text-secondary me-2"></i> <small>${categoria}</small></p>
                                        <p class="card-text text-muted mb-1"><i class="fas fa-industry text-secondary me-2"></i> <small>${marca}</small></p>
                                        <p class="card-text text-muted mb-3"><i class="fas fa-box text-secondary me-2"></i> <small>${presentacion}</small></p>
                                    </div>
                                    <button class="btn btn-primary btn-border btn-round btn-sm w-100 mt-3" data-selector="abrir" data-id="${p.id}">
                                        <i class="fas fa-share"></i> Ver Detalles
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                // 2.3 Renderizar HTML en contenedor
                MACRO_INIT.contenedor_catalogo.html(MACRO_INIT.html_catalogo);
            },
            error: function (xhr, status, error) {
                MACRO_INIT.contenedor_catalogo.html('<div class="col-12 text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><p>Error al cargar el catálogo de productos.</p></div>');
            }
        });
    });

    $("#tbl_list_remisiones").DataTable({
        ajax: SITE_URL + '/dispatch/listing',
        columns: [
            { data: 'id' },
            { 
                data: null,
                render: function (data, type, row) {
                    const pre = (row.city_code ? row.city_code : (row.city_name ? row.city_name.substring(0,3) : 'GM')).toUpperCase();
                    const seq = String(row.sequence).padStart(3, '0');
                    return `${pre}-${seq}`;
                }
            },
            { data: 'client' },
            { data: 'nit' },
            { data: 'city_name' },
            { 
                data: 'created_at',
                render: function(data) {
                    return data ? data.split(' ')[0] : '';
                }
            },
            { data: 'id' }
        ],
        columnDefs: [
            {
                targets: 6,
                className: 'text-center',
                render: function (data, type, row, meta) {
                    return `<button class="btn btn-round btn-info btn-sm" data-selector="ver_remision_pdf" data-id="${row.id}"><i class="fas fa-file-pdf"></i> Ver</button>`;
                }
            }
        ],
        rowId: "id",
        processing: true,
        serverSide: false,
        pageLength: 10,
        language: {
            processing: 'Procesando...',
            lengthMenu: 'Mostrar _MENU_ registros',
            zeroRecords: 'No se encontraron resultados',
            emptyTable: 'No hay datos disponibles',
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
        }
    });

});

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
            if (resp.imagePaths && resp.imagePaths.length > 0) {
                imagenes_html += '<div class="d-flex flex-wrap justify-content-center">';
                resp.imagePaths.forEach(path => {
                    imagenes_html += `<img src="${SITE_URL}${path}" alt="Producto" style="max-width: 200px; width: auto; max-height: 200px; margin: 5px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">`;
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
            if (resp.videoPaths && resp.videoPaths.length > 0) {
                resp.videoPaths.forEach(path => {
                    videos_html += `<video 
                        src="${SITE_URL}${path}" 
                        controls 
                        preload="metadata" 
                        style="max-width: 100%; width: auto; max-height: 500px; margin: 5px;"
                        onerror="this.style.display='none'" 
                        >
                        Tu navegador no soporta el elemento de video.
                    </video>`;
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
                        <div class="col-6 col-md-4 text-center mb-3">
                            <a href="javascript:void(0)" class="text-danger text-decoration-none" onclick="viewPdf('${SITE_URL}${document.path}', '${document.name}')">
                                <i class="fas fa-file-pdf fa-3x"></i>
                                <p class="mt-2 mb-0 text-dark fw-bold" style="font-size:0.85rem; line-height: 1.2;">${document.name}</p>
                            </a>
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
                        // Recargar los detalles del producto automáticamente con los datos frescos
                        const productId = $('#btn_product_edit').data('id');
                        if (productId) {
                            $(`[data-selector="abrir"][data-id="${productId}"]`).trigger('click');
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

                // Recargar los detalles del producto automáticamente
                const productId = $('#btn_product_edit').data('id');
                if (productId) {
                    $(`[data-selector="abrir"][data-id="${productId}"]`).trigger('click');
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

                const productId = $('#btn_product_edit').data('id');
                if (productId) {
                    $(`[data-selector="abrir"][data-id="${productId}"]`).trigger('click');
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

                const productId = $('#btn_product_edit').data('id');
                if (productId) {
                    $(`[data-selector="abrir"][data-id="${productId}"]`).trigger('click');
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
