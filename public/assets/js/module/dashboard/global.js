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
const CAN_DELETE = window.userCredentials && window.userCredentials[8] === '1';
