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
    bodega: ['cont_bodega', 'Bodegas'],
}

/* ======================================================== */
/*   STATES                                               */
/* ======================================================== */

let GLOBAL_PRODUCTS_DATA = [];
const CAN_DELETE = window.userCredentials && window.userCredentials[8] === '1';
const CAN_ADJUST_WAREHOUSE = window.userCredentials && window.userCredentials[14] === '1';
const CAN_MANAGE_ALL_WAREHOUSES = window.userCredentials && (window.userCredentials[14] === '1' || window.userCredentials[0] === '1');

// Bodegas States
let dtBodegas = null;
let dtBodegaBalance = null;
let currentWarehouseId = null;
let currentWarehouseData = null;
let currentWarehouseItems = [];

