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
                    const pre = (row.city_code ? row.city_code : (row.city_name ? row.city_name.substring(0, 3) : 'GM')).toUpperCase();
                    const seq = String(row.sequence).padStart(3, '0');
                    return `${pre}-${seq}`;
                }
            },
            { data: 'client' },
            { data: 'nit' },
            { data: 'city_name' },
            {
                data: 'created_at',
                render: function (data) {
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