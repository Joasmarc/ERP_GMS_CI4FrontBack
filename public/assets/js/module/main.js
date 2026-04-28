/* GLOBAL */
const SITE_URL = window.location.hostname === 'localhost' ? 'http://localhost/adminitradorgm' : '';

const SCREENS = {
    dashboard: ['cont_dashboard', 'Inicio', 'Dashboard'],
    product_create: ['cont_product_create', 'Productos', 'Crear'],
    product_listing: ['cont_product_list', 'Productos', 'Listar'],
    client_create: ['cont_client_create', 'Clientes', 'Crear'],
    client_listing: ['cont_client_list', 'Clientes', 'Listar'],
}

/* STATES */
let globalProductsData = [];

/* INIT */
$(function () {

    // Datatables
    $(document).ready(function () {
        loadCatalogProducts();

        // Datatable Clientes
        $("#tbl_list_clientes").DataTable({
            ajax: SITE_URL + '/client/listing',
            columns: [
                { data: 'id' },
                { data: 'nombre_cliente' },
                { data: 'tipo_documento' },
                { data: 'numero_documento' },
                { data: 'telefono_cliente' },
                { data: 'correo_cliente' },
                { data: 'direccion_cliente' },
                { data: 'id' }
            ],
            columnDefs: [
                {
                    targets: 7,
                    render: function (data, type, row, meta) {
                        return `<button class="btn btn-round btn-primary btn-sm" data-selector="abrir_cliente" data-id="${row.id}"><i class="fas fa-share"></i></button>`;
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

});

/* EVENTS */

// Abrir dashboard
$('#btn_open_dashboard').on('click', function () {
    showScreen(SCREENS.dashboard);
});

// Abrir productos crear
$('#btn_open_product_create').on('click', function () {
    showScreen(SCREENS.product_create);
});

// Abrir productos listar
$('#btn_open_product_list').on('click', function () {

    showScreen(SCREENS.product_listing);
});

// Abrir clientes crear
$('#btn_open_client_create').on('click', function () {
    showScreen(SCREENS.client_create);
});

// Abrir clientes listar
$('#btn_open_client_list').on('click', function () {
    showScreen(SCREENS.client_listing);
});

// Función para abrir detalle de producto
function openProductDetail(id) {
    const row = globalProductsData.find(p => p.id == id);
    if (!row) return;

    // Buscar variantes
    let rawName = row.nombre ? row.nombre.toLowerCase().trim() : 'sin nombre';
    let variants = globalProductsData.filter(p => {
        let n = p.nombre ? p.nombre.toLowerCase().trim() : 'sin nombre';
        return n === rawName;
    });

    // Renderizar variantes
    let variantsHtml = '';
    if (variants.length > 1) {
        $('#cont_variantes_wrapper').removeClass('d-none');
        variants.forEach(v => {
            let badge = v.id == id ? '<span class="badge badge-success">Actual</span>' : '';
            variantsHtml += `
                <tr>
                    <td>${v.id} ${badge}</td>
                    <td>${v.presentacion || 'N/A'}</td>
                    <td>${v.id_categoria || 'N/A'}</td>
                    <td>${v.id_marca || 'N/A'}</td>
                    <td>
                        ${v.id != id ? `<button class="btn btn-sm btn-outline-primary btn-round" onclick="openProductDetail('${v.id}')"><i class="fas fa-eye"></i> Ver Detalle</button>` : ''}
                    </td>
                </tr>
            `;
        });
        $('#list_variantes_producto').html(variantsHtml);
    } else {
        $('#cont_variantes_wrapper').addClass('d-none');
    }

    $('#wrapper_catalog').addClass('d-none');
    $('#cont_detalle_producto').removeClass('d-none');

    // Ir directamente a un elemento
    $('html, body').scrollTop($('#cont_detalle_producto').offset().top - 100);

    // Guardar el ID actual en el botón de modificar para poder subir la foto
    $('#btn_product_edit').data('id', row.id);

    // Mostrar informacion general
    $('#in_nombre_producto').val((row.nombre || '').toUpperCase());
    $('#in_categoria_producto').val(row.id_categoria.toUpperCase());
    $('#in_presentacion_producto').val(row.presentacion.toUpperCase());
    $('#in_marca_producto').val(row.id_marca.toUpperCase());
    $('#in_observacion_producto').val(row.observacion?.toUpperCase());

    // Mostrar imagen 
    $('#cont_imagenes_producto').empty();
    $.ajax({
        url: SITE_URL + '/product/img/' + row.id,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            let imagesHTML = '';
            resp.imagePaths.forEach(path => {
                imagesHTML += `<img src="${SITE_URL}${path}" alt="Producto" style="max-width: 500px; max-height: 500px; margin: 5px;">`;
            });
            $('#cont_imagenes_producto').html(imagesHTML);
        },
        error: function (xhr, status, error) {
            console.log(error);
            Swal.fire('<h5> ❌ OC No pudo ser registrada</h5>')
        },
        complete: function () {
            // always
        }
    })

    // Mostrar video 
    $('#cont_videos_producto').empty();
    $.ajax({
        url: SITE_URL + '/product/video/' + row.id,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            let videosHTML = '';
            resp.videoPaths.forEach(path => {
                videosHTML += `<video 
                    src="${SITE_URL}${path}" 
                    controls 
                    preload="metadata" 
                    style="max-width: 500px; max-height: 500px; margin: 5px;"
                    onerror="this.style.display='none'" 
                    >
                    Tu navegador no soporta el elemento de video.
                </video>`;
            });

            $('#cont_videos_producto').html(videosHTML);
        },
        error: function (xhr, status, error) {
            console.log(error);
            Swal.fire('<h5> ❌ Error al buscar videos</h5>')
        },
        complete: function () {
            // always
        }
    })

    // Mostrar documentos 
    $('#pills-tab').empty();
    $('#pills-tabContent').empty();
    $.ajax({
        url: SITE_URL + '/product/document/' + row.id,
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            if (resp.documents && resp.documents.length && resp.documents[0].name !== null) {
                let tabsHtml = '';
                let contentHtml = '';
                let first = false;
                resp.documents.forEach((document, index) => {
                    first = index === 0 ? 'active' : '';
                    tabsHtml += `<li class="nav-item submenu" role="presentation"><a class="nav-link ${first}" id="pills-${index}-tab" data-bs-toggle="pill" href="#pills-${index}" role="tab" aria-controls="pills-${index}" aria-selected="true">${document.name}</a></li>`;
                    contentHtml += `<div class="tab-pane fade show ${first}" id="pills-${index}" role="tabpanel" aria-labelledby="pills-${index}-tab"><iframe src="${SITE_URL}${document.path}" style="width: 100%; height: 500px;"></iframe></div>`;
                });
                $('#pills-tab').html(tabsHtml);
                $('#pills-tabContent').html(contentHtml);
            }
        },
        error: function (xhr, status, error) {
            console.log(error);
            Swal.fire('<h5> ❌ Error al cargar docs</h5>')
        },
        complete: function () {
            // always
        }
    })


}

$('#catalog_list_productos').on('click', '[data-selector="abrir"]', function () {
    const id = $(this).attr('data-id');
    openProductDetail(id);
});

$('#btn_back_to_catalog').on('click', function() {
    $('#cont_detalle_producto').addClass('d-none');
    $('#wrapper_catalog').removeClass('d-none');
    $('html, body').scrollTop($('#wrapper_catalog').offset().top - 100);
});

$('#tbl_list_clientes').on('click', '[data-selector="abrir_cliente"]', function () {
    const row = $('#tbl_list_clientes').DataTable().row('#' + $(this).attr('data-id')).data();
    $('#cont_detalle_cliente').removeClass('d-none');
    $('html, body').scrollTop($('#cont_detalle_cliente').offset().top - 100);
    
    $('#detalle_client_id').val(row.id);
    $('#detalle_cliente_nombre').text(row.nombre_cliente);
    
    loadClientComments(row.id);
});

$('#form_add_comment').on('submit', function(e) {
    e.preventDefault();
    const btn = $(this).find('button[type="submit"]');
    btn.prop('disabled', true);
    
    $.ajax({
       url: SITE_URL + '/client/add_comment',
       type: 'POST',
       data: $(this).serialize(),
       success: function(resp) {
           if(resp.status==='success') {
               $('#detalle_comment').val('');
               loadClientComments($('#detalle_client_id').val());
           } else {
               swal('Error', resp.message, 'error');
           }
       },
       complete: function() {
           btn.prop('disabled', false);
       }
    });
});

function loadClientComments(clientId) {
    $('#list_client_comments').html('<li class="list-group-item text-center">Cargando...</li>');
    $.get(SITE_URL + '/client/list_comments/' + clientId, function(resp) {
        if(resp && resp.status === 'success') {
            let html = '';
            if(resp.data.length === 0) {
                html = '<li class="list-group-item text-center text-muted">Sin evaluaciones registradas</li>';
            } else {
                resp.data.forEach(item => {
                   html += `<li class="list-group-item">
                     <p class="mb-1">${item.comment}</p>
                     <small class="text-muted"><i class="fas fa-clock"></i> ${item.created_at}</small>
                   </li>`;
                });
            }
            $('#list_client_comments').html(html);
        }
    });
}


/* Development */

$('.en-desarrollo').not('#btn_product_edit').on('click', function () {
    $.notify({ title: 'Advertencia', message: 'Funcionalidad en desarollo', icon: "fas fa-cogs" }, {
        type: 'warning',
        placement: {
            from: 'top',
            align: 'right',
        },
        time: 1000,
        delay: 100,
    });
})

$('#btn_open_product_modify').on('click', function () {
    $.notify({ title: 'Advertencia', message: 'Modulo en desarollo', icon: "fas fa-cogs" }, {
        type: 'warning',
        placement: {
            from: 'top',
            align: 'right',
        },
        time: 1000,
        delay: 100,
    });
})

// Lógica para Abrir Modal de Subida de Imagen
$('#btn_product_edit').on('click', function () {
    const productId = $(this).data('id');
    if (!productId) {
        swal('Error', 'No hay un producto seleccionado', 'error');
        return;
    }
    $('#siigo_product_id_upload').val(productId);
    
    // Reset modal state
    $('#file_input_image').val('');
    $('#image_preview_container').addClass('d-none');
    $('#image_preview').attr('src', '');
    $('#image_filename').text('');
    $('#drag_drop_area').removeClass('d-none');
    
    $('#modal_upload_image').modal('show');
});

// Drag and Drop Lógica
const dropArea = document.getElementById('drag_drop_area');
const fileInput = document.getElementById('file_input_image');
let selectedFile = null;

// Click en el area abre el input
dropArea.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', function() {
    handleFiles(this.files);
});

// Drag Events
['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, preventDefaults, false);
});

function preventDefaults (e) {
    e.preventDefault();
    e.stopPropagation();
}

['dragenter', 'dragover'].forEach(eventName => {
    dropArea.addEventListener(eventName, highlight, false);
});

['dragleave', 'drop'].forEach(eventName => {
    dropArea.addEventListener(eventName, unhighlight, false);
});

function highlight(e) {
    dropArea.classList.add('bg-light');
}

function unhighlight(e) {
    dropArea.classList.remove('bg-light');
}

dropArea.addEventListener('drop', handleDrop, false);

function handleDrop(e) {
    let dt = e.dataTransfer;
    let files = dt.files;
    handleFiles(files);
}

function handleFiles(files) {
    if (files.length > 0) {
        selectedFile = files[0];
        
        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#image_preview').attr('src', e.target.result);
            $('#image_filename').text(selectedFile.name);
            $('#drag_drop_area').addClass('d-none');
            $('#image_preview_container').removeClass('d-none');
        }
        reader.readAsDataURL(selectedFile);
    }
}

// Subir Imagen via AJAX
$('#btn_upload_image').on('click', function() {
    if (!selectedFile) {
        swal('Atención', 'Selecciona una imagen primero', 'warning');
        return;
    }

    const productId = $('#siigo_product_id_upload').val();
    let formData = new FormData();
    formData.append('product_image', selectedFile);
    formData.append('id_product', productId);

    const btn = $(this);
    btn.prop('disabled', true).text('Subiendo...');

    $.ajax({
        url: SITE_URL + '/product/upload_image',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(resp) {
            // Verificar si es string y parsear (a veces CodeIgniter lo manda como string html)
            if(typeof resp === 'string') {
                try {
                    resp = JSON.parse(resp);
                } catch(e) {}
            }

            if (resp.status === 'success') {
                swal('Éxito', 'Imagen subida correctamente', 'success');
                $('#modal_upload_image').modal('hide');
                
                // Recargar las imágenes del producto automáticamente
                $(`[data-selector="abrir"][data-id="${productId}"]`).trigger('click');
            } else {
                swal('Error', resp.message || 'Error al subir la imagen', 'error');
            }
        },
        error: function(xhr, status, error) {
            swal('Error', 'Hubo un problema de conexión', 'error');
            console.error(error);
        },
        complete: function() {
            btn.prop('disabled', false).text('Subir Imagen');
        }
    });
});

$('.module_bloq').on('click', function () {
    $.notify({ title: 'Advertencia', message: 'Modulo bloqueado', icon: "fas fa-unlock" }, {
        type: 'danger',
        placement: {
            from: 'top',
            align: 'right',
        },
        time: 1000,
        delay: 100,
    });
})

$('.module_development').on('click', function () {
    $.notify({ title: 'Advertencia', message: 'Modulo en desarollo', icon: "fas fa-cogs" }, {
        type: 'warning',
        placement: {
            from: 'top',
            align: 'right',
        },
        time: 1000,
        delay: 100,
    });
})


function loadCatalogProducts() {
    $('#catalog_list_productos').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-3">Cargando catálogo...</p></div>');
    $.ajax({
        url: SITE_URL + '/product/listing_siigo',
        type: 'GET',
        dataType: 'json',
        success: function(resp) {
            globalProductsData = resp.data || [];
            renderCatalogProducts(globalProductsData);
        },
        error: function(xhr, status, error) {
            $('#catalog_list_productos').html('<div class="col-12 text-center py-5 text-danger"><i class="fas fa-exclamation-triangle fa-3x mb-3"></i><p>Error al cargar el catálogo de productos.</p></div>');
        }
    });
}

function renderCatalogProducts(products) {
    const container = $('#catalog_list_productos');
    container.empty();

    if (products.length === 0) {
        container.html('<div class="col-12 text-center py-5"><i class="fas fa-box-open fa-3x mb-3 text-muted"></i><p class="text-muted">No se encontraron productos disponibles.</p></div>');
        return;
    }

    // Agrupar por nombre similar
    const groupedProducts = {};
    
    products.forEach(p => {
        // Normalizar nombre (minúsculas, sin espacios extra)
        let rawName = p.nombre ? p.nombre.toLowerCase().trim() : 'sin nombre';
        
        // Si el grupo no existe, lo creamos y le asignamos este producto como el "principal"
        if (!groupedProducts[rawName]) {
            groupedProducts[rawName] = {
                principal: p,
                count: 1,
                ids: [p.id] // Guardar los IDs agrupados
            };
        } else {
            groupedProducts[rawName].count++;
            groupedProducts[rawName].ids.push(p.id);
        }
    });

    let html = '';
    Object.values(groupedProducts).forEach(group => {
        let p = group.principal;
        let count = group.count;
        
        let nombre = p.nombre ? p.nombre.charAt(0).toUpperCase() + p.nombre.slice(1) : 'Sin nombre';
        let categoria = p.id_categoria ? p.id_categoria.charAt(0).toUpperCase() + p.id_categoria.slice(1) : 'Sin categoría';
        let marca = p.id_marca ? p.id_marca.charAt(0).toUpperCase() + p.id_marca.slice(1) : 'Sin marca';
        let presentacion = p.presentacion ? p.presentacion.charAt(0).toUpperCase() + p.presentacion.slice(1) : 'Sin presentación';
        let imgSrc = p.img ? (SITE_URL + p.img) : (SITE_URL + '/public/assets/img/kaiadmin/favicon.ico');

        let badgeHtml = count > 1 ? `<span class="badge badge-primary position-absolute" style="top: 10px; right: 10px; z-index: 2; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">${count} Variantes</span>` : '';

        html += `
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
    container.html(html);
}

// Búsqueda en catálogo
$('#search_products').on('input', function() {
    let searchVal = $(this).val().toLowerCase();
    $('.product-card-item').each(function() {
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


/* UTILS */

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
        $('#' + SCREENS[key]).addClass('d-none');
    }
    // 1.1 Mostrar loader
    toggleLoader(true)
    // 1.2 Actualizar navegación
    $('#nav_first').text(screenId[1]);
    $('#nav_second').text(screenId[2]);
    // 1.3 Mostrar pantalla después de un breve retraso
    setTimeout(() => {
        toggleLoader(false)
        $('#' + screenId[0]).removeClass('d-none');
    }, 800)
}