/* ======================================================== */
/*   GLOBAL                                               */
/* ======================================================== */

const SITE_URL = window.BASE_URL ? window.BASE_URL.replace(/\/$/, '') : (window.location.hostname === 'localhost' ? 'http://localhost/adminitradorgm' : '');

const SCREENS = {
    dashboard: ['cont_dashboard', 'Dashboard'],
    product_listing: ['cont_product', 'Productos'],
    remisiones: ['cont_remisiones', 'Remisiones'],
    proveedor: ['cont_proveedor', 'Proveedores'],
    cliente: ['cont_cliente', 'Clientes'],
}

/* ======================================================== */
/*   STATES                                               */
/* ======================================================== */

let GLOBAL_PRODUCTS_DATA = [];
