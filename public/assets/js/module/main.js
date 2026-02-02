/* GLOBAL */

const SCREENS = {
    dashboard: ['cont_dashboard', 'Inicio', 'Dashboard'],
    product_create: ['cont_product_create', 'Productos', 'Crear'],
    product_listing: ['cont_product_list', 'Productos', 'Listar'],
}

/* STATES */
/* INIT */
$(function() {

    // Datatables
    $(document).ready(function () {
        $("#basic-datatables").DataTable({});
    });

});

/* EVENTS */

// Abrir dashboard
$('#btn_open_dashboard').on('click', function() {
    showScreen(SCREENS.dashboard);
});

// Abrir productos crear
$('#btn_open_product_create').on('click', function() {
    showScreen(SCREENS.product_create);
});

// Abrir productos listar
$('#btn_open_product_list').on('click', function() {
    showScreen(SCREENS.product_listing);
});

$('#btn_open_product_modify').on('click', function() {
    $.notify({title: 'Advertencia', message:'Modulo en desarollo',  icon:"fas fa-cogs"}, {
        type: 'warning',
        placement: {
        from: 'top',
        align: 'right',
        },
        time: 1000,
        delay: 100,
    });
})

$('.module_bloq').on('click', function() {
    $.notify({title: 'Advertencia', message:'Modulo bloqueado',  icon:"fas fa-unlock"}, {
        type: 'danger',
        placement: {
        from: 'top',
        align: 'right',
        },
        time: 1000,
        delay: 100,
    });
})

$('.module_development').on('click', function() {
    $.notify({title: 'Advertencia', message:'Modulo en desarollo',  icon:"fas fa-cogs"}, {
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