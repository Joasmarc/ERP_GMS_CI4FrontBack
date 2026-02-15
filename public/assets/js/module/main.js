/* GLOBAL */
const SITE_URL = window.location.hostname === 'localhost' ? 'http://localhost/adminitradorgm' : '';

const SCREENS = {
    dashboard: ['cont_dashboard', 'Inicio', 'Dashboard'],
    product_create: ['cont_product_create', 'Productos', 'Crear'],
    product_listing: ['cont_product_list', 'Productos', 'Listar'],
}

/* STATES */
/* INIT */
$(function () {

    // Datatables
    $(document).ready(function () {
        $("#tbl_list_productos").DataTable({
            ajax: SITE_URL + '/product/listing',
            columns: [
                { data: 'id' },
                { data: 'nombre' },
                { data: 'id_categoria' },
                { data: 'presentacion' },
                { data: 'id_marca' },
                { data: 'observacion' },
                { data: 'img' },
                { data: 'id' },
            ],
            rowId: "id",
            columnDefs: [
                {
                    targets: [0, 1, 2, 3, 4, 5], // Exclude the 6th column
                    render: function (data, type, row, meta) {
                        if (data === null || data === undefined) return data;
                        if (typeof data === 'string' && data.length > 0) {
                            return data.charAt(0).toUpperCase() + data.slice(1);
                        }
                        return data;
                    }
                },
                {
                    targets: 6,
                    render: function (data, type, row, meta) {
                        if (data === null || data === undefined) return '';
                        return '<img src="' + SITE_URL + data + '" alt="Producto" style="max-width: 50px; max-height: 50px;">';
                    }
                },
                {
                    targets: 7,
                    render: function (data, type, row, meta) {
                        return `<button class="btn btn-primary btn-sm" data-selector="abrir" data-id="${row.id}"><i class="fas fa-share"></i></button>`;
                    }
                }
            ],
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

// Abrir productos detallado
$('#tbl_list_productos').on('click', '[data-selector="abrir"]', function () {
    const row = $('#tbl_list_productos').DataTable().row('#' + $(this).attr('data-id')).data();

    $('#cont_detalle_producto').removeClass('d-none');

    // Ir directamente a un elemento
    $('html, body').scrollTop($('#cont_detalle_producto').offset().top - 100);

    // Mostrar informacion general
    $('#in_nombre_producto').val(row.nombre.toUpperCase());
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
            Swal.fire('<h5> ❌ OC No pudo ser registrada</h5>')
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
            if (resp.documents[0].name !== null) {
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
            Swal.fire('<h5> ❌ OC No pudo ser registrada</h5>')
        },
        complete: function () {
            // always
        }
    })


});


/* Development */

$('.en-desarrollo').on('click', function () {
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