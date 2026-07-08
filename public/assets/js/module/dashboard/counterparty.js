/* ======================================================== */
/*   COUNTERPARTY JS                                        */
/* ======================================================== */

let dtProveedores = null;
let dtClientes = null;
let departmentsList = [];

$(function () {
    // 1.0 Clics en la barra lateral
    $('#btn_open_proveedor').on('click', function () {
        showScreen(SCREENS.proveedor);
        initProveedoresTable();
    });

    $('#btn_open_cliente').on('click', function () {
        showScreen(SCREENS.cliente);
        initClientesTable();
    });

    // 2.0 Manejadores del Formulario de Creación
    $('#form_counterparty_create input[name="person_type"]').on('change', function () {
        const val = $(this).val();
        toggleFormSections(val);
    });

    // Volver a la lista correspondiente
    $('#btn_back_to_counterparty_list').on('click', function () {
        const type = $('#form_contraparte_type').val();
        switchSubScreen('counterparty_create_view', type + '_list_view');
    });

    // Guardar registro directo de contraparte
    $('#btn_save_counterparty').on('click', function () {
        const form = $('#form_counterparty_create');
        
        // Validar el formulario activo
        if (!validateActiveForm()) {
            return;
        }

        const formData = form.serialize();
        const type = $('#form_contraparte_type').val();

        $.ajax({
            url: SITE_URL + '/counterparty/save',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    swal("¡Éxito!", resp.message, "success");
                    switchSubScreen('counterparty_create_view', type + '_list_view');
                    reloadTables();
                } else {
                    swal("Error", resp.message || "No se pudo guardar el registro", "error");
                }
            },
            error: function () {
                swal("Error", "Error al comunicarse con el servidor", "error");
            }
        });
    });

    // 3.0 Manejador del Modal para Compartir Enlace
    $('#btn_generate_share_link').on('click', function () {
        const nitCedula = $('#share_numero_identificacion').val();
        const personType = $('#share_person_type').val();
        const contraparteType = $('#share_contraparte_type').val();
        if (!nitCedula) {
            swal("Advertencia", "Debe ingresar el NIT o Cédula.", "warning");
            return;
        }

        $.ajax({
            url: SITE_URL + '/counterparty/share',
            type: 'POST',
            data: $('#form_share_counterparty').serialize(),
            dataType: 'json',
            success: function (resp) {
                if (resp.status === 'success') {
                    $('#input_generated_link').val(resp.link);
                    
                    // Configurar el botón de compartir por WhatsApp
                    const text = `Hola, por favor diligencie el siguiente formulario de registro de datos y PEP: ${resp.link}`;
                    const waUrl = `https://api.whatsapp.com/send?text=${encodeURIComponent(text)}`;
                    $('#btn_whatsapp_share').attr('href', waUrl);

                    $('#div_generated_link').removeClass('d-none');
                    swal("¡Generado!", "El enlace personalizado ha sido generado.", "success");
                } else {
                    swal("Error", resp.message || "No se pudo generar el enlace.", "error");
                }
            },
            error: function () {
                swal("Error", "Ocurrió un error al procesar la solicitud.", "error");
            }
        });
    });

    // Copiar enlace al portapapeles
    $('#btn_copy_link').on('click', function () {
        const input = document.getElementById('input_generated_link');
        input.select();
        input.setSelectionRange(0, 99999); // Para móviles
        navigator.clipboard.writeText(input.value);

        // Feedback visual temporal
        const btn = $(this);
        const originalText = btn.html();
        btn.html('<i class="fas fa-check"></i> Copiado');
        btn.removeClass('btn-outline-info').addClass('btn-success');
        setTimeout(() => {
            btn.html(originalText);
            btn.removeClass('btn-success').addClass('btn-outline-info');
        }, 2000);
    });

    // 4.0 Dropdowns encadenados para la sección de residencia
    $('#form_dept_residencia').on('change', function () {
        const deptId = $(this).val();
        loadCitiesDropdown(deptId, '#form_city_residencia');
    });

    // 4.1 Dropdowns encadenados para la sección de nacimiento
    $('#form_dept_nacimiento').on('change', function () {
        const deptId = $(this).val();
        loadCitiesDropdown(deptId, '#form_city_nacimiento');
    });

    // 5.0 Subir archivo de Política de Privacidad PDF
    $('#form_upload_policy').on('submit', function (e) {
        e.preventDefault();
        
        const fileInput = $('#policy_file')[0];
        if (fileInput.files.length === 0) {
            swal("Advertencia", "Debe seleccionar un archivo PDF.", "warning");
            return;
        }

        const formData = new FormData(this);
        $('#btn_submit_policy_file').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Subiendo...');

        $.ajax({
            url: SITE_URL + '/counterparty/upload_policy',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (resp) {
                $('#btn_submit_policy_file').prop('disabled', false).html('Subir Archivo');
                if (resp.status === 'success') {
                    $('#modal_upload_policy').modal('hide');
                    swal("¡Subido!", resp.message, "success");
                } else {
                    swal("Error", resp.message || "No se pudo subir el archivo.", "error");
                }
            },
            error: function () {
                $('#btn_submit_policy_file').prop('disabled', false).html('Subir Archivo');
                swal("Error", "Ocurrió un error al intentar subir el archivo.", "error");
            }
        });
    });

    // 6.0 Restricciones de entrada en tiempo real (solo números para teléfonos y experiencia)
    $(document).on('input', 'input[name="telefono_principal"], input[name="telefono_secundario"], input[name="anos_experiencia"], input[name="tiempo_residencia_meses"]', function () {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});

// Abrir modal de subir política
function openUploadPolicyModal() {
    $('#form_upload_policy')[0].reset();
    $('#modal_upload_policy').modal('show');
}

// Inicializar la tabla de proveedores
function initProveedoresTable() {
    if (dtProveedores !== null) return;

    dtProveedores = $("#tbl_list_proveedores").DataTable({
        ajax: SITE_URL + '/counterparty/listing?type=proveedor',
        columns: [
            { data: 'id' },
            { data: 'nombre_completo' },
            { data: 'numero_identificacion' },
            { 
                data: 'person_type',
                render: function (data) {
                    return data === 'natural' ? '<span class="badge badge-info">Natural</span>' : '<span class="badge badge-primary">Jurídica</span>';
                }
            },
            { data: 'email_principal' },
            { data: 'telefono_principal' },
            { data: 'regimen_tributario' },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-round btn-info btn-sm me-1" onclick="viewCounterpartyDetail(${row.id})"><i class="fas fa-eye"></i></button>
                    `;
                }
            }
        ],
        processing: true,
        serverSide: false,
        pageLength: 10,
        language: getDatatablesLanguage()
    });
}

// Inicializar la tabla de clientes
function initClientesTable() {
    if (dtClientes !== null) return;

    dtClientes = $("#tbl_list_clientes").DataTable({
        ajax: SITE_URL + '/counterparty/listing?type=cliente',
        columns: [
            { data: 'id' },
            { data: 'nombre_completo' },
            { data: 'numero_identificacion' },
            { 
                data: 'person_type',
                render: function (data) {
                    return data === 'natural' ? '<span class="badge badge-info">Natural</span>' : '<span class="badge badge-primary">Jurídica</span>';
                }
            },
            { data: 'email_principal' },
            { data: 'telefono_principal' },
            { data: 'regimen_tributario' },
            {
                data: null,
                className: 'text-center',
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-round btn-info btn-sm me-1" onclick="viewCounterpartyDetail(${row.id})"><i class="fas fa-eye"></i></button>
                    `;
                }
            }
        ],
        processing: true,
        serverSide: false,
        pageLength: 10,
        language: getDatatablesLanguage()
    });
}

// Recargar las tablas de contrapartes
function reloadTables() {
    if (dtProveedores) dtProveedores.ajax.reload();
    if (dtClientes) dtClientes.ajax.reload();
}

// Abrir el modal de compartir enlace
function openShareModal(type) {
    $('#share_contraparte_type').val(type);
    $('#form_share_counterparty')[0].reset();
    $('#div_generated_link').addClass('d-none');
    $('#modal_share_form').modal('show');
}

// Abrir el formulario de registro directo desde el panel
function openCreateForm(type) {
    $('#form_counterparty_create')[0].reset();
    $('#form_counterparty_id').val('');
    $('#form_contraparte_type').val(type);
    
    // Título dinámico
    const label = type === 'proveedor' ? 'Proveedor' : 'Cliente';
    $('#counterparty_form_title').html(`<i class="fas fa-user-plus"></i> Registrar Nuevo ${label}`);
    
    // Activar sección jurídica por defecto
    $('#form_person_type_juridica').prop('checked', true).trigger('change');
    
    // Cargar departamentos si es necesario
    loadDepartmentsDropdown('#form_dept_residencia');
    loadDepartmentsDropdown('#form_dept_nacimiento');

    // Cambiar de vista
    switchSubScreen(type + '_list_view', 'counterparty_create_view');
}

// Ver detalle en modo lectura
function viewCounterpartyDetail(id) {
    toggleLoader(true);
    
    $.ajax({
        url: SITE_URL + '/counterparty/listing', // Buscamos por id o usamos la lista existente
        type: 'GET',
        dataType: 'json',
        success: function (resp) {
            toggleLoader(false);
            const list = resp.data || [];
            const row = list.find(r => r.id == id);
            
            if (!row) {
                swal("Error", "No se encontró el registro seleccionado", "error");
                return;
            }

            // Llenar el formulario
            $('#form_counterparty_id').val(row.id);
            $('#form_contraparte_type').val(row.contraparte_type);
            $(`#form_person_type_${row.person_type}`).prop('checked', true);
            
            toggleFormSections(row.person_type);
            
            const prefix = row.person_type === 'juridica' ? 'jur_' : 'nat_';
            
            // Llenar campos comunes e independientes
            $('#form_counterparty_create [name]').each(function () {
                const name = $(this).attr('name');
                if (row[name] !== undefined && row[name] !== null) {
                    $(this).val(row[name]);
                }
            });

            // Título dinámico
            const label = row.contraparte_type === 'proveedor' ? 'Proveedor' : 'Cliente';
            $('#counterparty_form_title').html(`<i class="fas fa-eye"></i> Detalle de ${label} - #${row.id}`);

            // Cargar dropdowns
            loadDepartmentsDropdown('#form_dept_residencia', row.departamento_residencia, function () {
                // Al cargar departamentos, buscar el ID y cargar ciudades correspondientes
                const selectedDept = $('#form_dept_residencia').val();
                if (selectedDept) {
                    loadCitiesDropdown(selectedDept, '#form_city_residencia', row.ciudad_residencia);
                }
            });
            loadDepartmentsDropdown('#form_dept_nacimiento', row.departamento_nacimiento, function () {
                const selectedDept = $('#form_dept_nacimiento').val();
                if (selectedDept) {
                    loadCitiesDropdown(selectedDept, '#form_city_nacimiento', row.lugar_nacimiento_ciudad);
                }
            });

            // Alternar vista
            switchSubScreen(row.contraparte_type + '_list_view', 'counterparty_create_view');
        },
        error: function () {
            toggleLoader(false);
            swal("Error", "Error al consultar el detalle en el servidor.", "error");
        }
    });
}

// Alternar visibilidad de las secciones Natural vs Jurídico
function toggleFormSections(type) {
    if (type === 'juridica') {
        $('#sec_juridica').removeClass('d-none');
        $('#sec_natural').addClass('d-none');
        
        // Agregar/Quitar required dinámicamente
        $('#jur_nombre_completo').prop('required', true);
        $('#jur_tipo_identificacion').prop('required', true);
        $('#jur_numero_identificacion').prop('required', true);
        $('#jur_email_principal').prop('required', true);
        $('#jur_telefono_principal').prop('required', true);

        $('#nat_nombre_completo').prop('required', false);
        $('#nat_primer_apellido').prop('required', false);
        $('#nat_tipo_identificacion').prop('required', false);
        $('#nat_numero_identificacion').prop('required', false);
        $('#nat_fecha_nacimiento').prop('required', false);
        $('#nat_pais_residencia').prop('required', false);
        $('#nat_direccion_completa').prop('required', false);
        $('#nat_telefono_principal').prop('required', false);
        $('#nat_actividad_principal').prop('required', false);
        $('#nat_profesion').prop('required', false);
        $('#nat_area_especializacion').prop('required', false);
        $('#nat_sector_economico').prop('required', false);
        $('#nat_anos_experiencia').prop('required', false);
    } else {
        $('#sec_natural').removeClass('d-none');
        $('#sec_juridica').addClass('d-none');

        // Agregar/Quitar required dinámicamente
        $('#jur_nombre_completo').prop('required', false);
        $('#jur_tipo_identificacion').prop('required', false);
        $('#jur_numero_identificacion').prop('required', false);
        $('#jur_email_principal').prop('required', false);
        $('#jur_telefono_principal').prop('required', false);

        $('#nat_nombre_completo').prop('required', true);
        $('#nat_primer_apellido').prop('required', true);
        $('#nat_tipo_identificacion').prop('required', true);
        $('#nat_numero_identificacion').prop('required', true);
        $('#nat_fecha_nacimiento').prop('required', true);
        $('#nat_pais_residencia').prop('required', true);
        $('#nat_direccion_completa').prop('required', true);
        $('#nat_telefono_principal').prop('required', true);
        $('#nat_actividad_principal').prop('required', true);
        $('#nat_profesion').prop('required', true);
        $('#nat_area_especializacion').prop('required', true);
        $('#nat_sector_economico').prop('required', true);
        $('#nat_anos_experiencia').prop('required', true);
    }
}

// Cargar Dropdown de Departamentos de Colombia
function loadDepartmentsDropdown(selectorId, selectedValue = null, callback = null) {
    const dropdown = $(selectorId);
    
    // Si ya los cargamos previamente, no hacer AJAX repetido
    if (departmentsList.length > 0) {
        populateDeptDropdown(dropdown, departmentsList, selectedValue);
        if (callback) callback();
        return;
    }

    $.ajax({
        url: SITE_URL + '/public/departments',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            departmentsList = data || [];
            populateDeptDropdown(dropdown, departmentsList, selectedValue);
            if (callback) callback();
        }
    });
}

function populateDeptDropdown(dropdown, list, selectedValue) {
    dropdown.empty();
    dropdown.append('<option value="">Seleccione Departamento</option>');
    list.forEach(dept => {
        const selected = selectedValue && (selectedValue == dept.id || selectedValue == dept.name) ? 'selected' : '';
        dropdown.append(`<option value="${dept.id}" ${selected}>${dept.name}</option>`);
    });
}

// Cargar Dropdown de Ciudades filtrado por Departamento
function loadCitiesDropdown(deptId, selectorId, selectedValue = null, callback = null) {
    const dropdown = $(selectorId);
    dropdown.empty();
    dropdown.append('<option value="">Cargando ciudades...</option>');

    if (!deptId) {
        dropdown.empty();
        dropdown.append('<option value="">Seleccione Ciudad</option>');
        return;
    }

    $.ajax({
        url: SITE_URL + '/public/cities/' + deptId,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            dropdown.empty();
            dropdown.append('<option value="">Seleccione Ciudad</option>');
            const list = data || [];
            list.forEach(city => {
                const selected = selectedValue && (selectedValue == city.id || selectedValue == city.name) ? 'selected' : '';
                dropdown.append(`<option value="${city.name}" ${selected}>${city.name}</option>`);
            });
            if (callback) callback();
        }
    });
}

// Validar formulario del lado del cliente antes de enviar
function validateActiveForm() {
    const personType = $('#form_counterparty_create input[name="person_type"]:checked').val();
    let valid = true;
    let missingFields = [];

    if (personType === 'juridica') {
        if (!$('#jur_nombre_completo').val()) { valid = false; missingFields.push('Razón Social / Nombre Completo'); }
        if (!$('#jur_numero_identificacion').val()) { valid = false; missingFields.push('Número de Identificación'); }
        if (!$('#jur_email_principal').val()) { valid = false; missingFields.push('Email Principal'); }
        if (!$('#jur_telefono_principal').val()) { valid = false; missingFields.push('Teléfono Principal'); }
    } else {
        if (!$('#nat_nombre_completo').val()) { valid = false; missingFields.push('Nombre Completo'); }
        if (!$('#nat_primer_apellido').val()) { valid = false; missingFields.push('Primer Apellido'); }
        if (!$('#nat_numero_identificacion').val()) { valid = false; missingFields.push('Número de Identificación'); }
        if (!$('#nat_fecha_nacimiento').val()) { valid = false; missingFields.push('Fecha de Nacimiento'); }
        if (!$('#nat_pais_residencia').val()) { valid = false; missingFields.push('País de Residencia'); }
        if (!$('#nat_direccion_completa').val()) { valid = false; missingFields.push('Dirección Completa'); }
        if (!$('#nat_telefono_principal').val()) { valid = false; missingFields.push('Teléfono Residencial'); }
        if (!$('#nat_actividad_principal').val()) { valid = false; missingFields.push('Actividad Principal'); }
        if (!$('#nat_profesion').val()) { valid = false; missingFields.push('Profesión'); }
        if (!$('#nat_area_especializacion').val()) { valid = false; missingFields.push('Área de Especialización'); }
        if (!$('#nat_sector_economico').val()) { valid = false; missingFields.push('Sector Económico (CIIU)'); }
        if (!$('#nat_anos_experiencia').val()) { valid = false; missingFields.push('Años de Experiencia'); }
    }

    if (!valid) {
        swal("Campos Faltantes", "Los siguientes campos son obligatorios: \n- " + missingFields.join("\n- "), "warning");
        return false;
    }

    // Validar formato de correos si es jurídica
    if (personType === 'juridica') {
        const email = $('#jur_email_principal').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            swal("Formato Incorrecto", "El correo electrónico principal no tiene un formato válido.", "warning");
            return false;
        }
    }

    // Validar formato de teléfonos
    const phoneRegex = /^[0-9]+$/;
    const mainPhone = personType === 'juridica' ? $('#jur_telefono_principal').val() : $('#nat_telefono_principal').val();
    if (mainPhone && !phoneRegex.test(mainPhone)) {
        swal("Formato Incorrecto", "El teléfono principal debe contener únicamente números.", "warning");
        return false;
    }

    const secPhone = personType === 'juridica' ? $('#jur_telefono_secundario').val() : $('#nat_telefono_secundario').val();
    if (secPhone && !phoneRegex.test(secPhone)) {
        swal("Formato Incorrecto", "El teléfono secundario debe contener únicamente números.", "warning");
        return false;
    }

    // Validar formato de páginas web (si aplica)
    const webUrl = personType === 'juridica' ? $('input[name="pagina_web"]').val() : '';
    if (webUrl) {
        try {
            new URL(webUrl);
        } catch (_) {
            swal("Formato Incorrecto", "La dirección de la página web no es una URL válida (ej: https://ejemplo.com).", "warning");
            return false;
        }
    }

    return true;
}

// Obtener el objeto de traducción para DataTables
function getDatatablesLanguage() {
    return {
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
    };
}
