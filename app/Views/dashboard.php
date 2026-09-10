<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Gm Suministros - SAS</title>
  <meta
    content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
    name="viewport" />
  <link
    rel="icon"
    href="<?= base_url('public/assets/img/kaiadmin/favicon.ico') ?>"
    type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="<?= base_url('public/assets/js/plugin/webfont/webfont.min.js') ?>"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["<?= base_url('public/assets/css/fonts.min.css') ?>"],
      },
      active: function() {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <!-- CSS Files -->
  <link rel="stylesheet" href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/assets/css/plugins.min.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/assets/css/kaiadmin.min.css') ?>" />

  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link rel="stylesheet" href="<?= base_url('public/assets/css/demo.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('public/assets/css/module/main.css') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/css/module/main.css') ?>" />
</head>

<body>

  <!-- ======================================================== -->
  <!--   MAIN                                               -->
  <!-- ======================================================== -->
  <div class="wrapper">

    <!-- Ini Sidebar -->
    <div class="sidebar" data-background-color="dark">
      <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" data-background-color="dark">
          <a href="index.html" class="logo">
            <img
              src="<?= base_url('public/assets/img/kaiadmin/banner_gms.jpg') ?>"
              alt="navbar brand"
              class="navbar-brand rounded"
              height="40" />
          </a>
          <div class="nav-toggle">
            <button class="btn btn-round btn-toggle toggle-sidebar">
              <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-round btn-toggle sidenav-toggler">
              <i class="gg-menu-left"></i>
            </button>
          </div>
          <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
          </button>
        </div>
        <!-- End Logo Header -->
      </div>
      <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
          <ul class="nav nav-secondary">
            <li class="nav-section">
              <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
              </span>
              <h4 class="text-section">Inicio</h4>
            </li>
            <li class="nav-item" style="background: transparent !important;">
              <a href="#" id="btn_open_dashboard">
                <i class="fas fa-home"></i>
                <p>Dashboard</p>
                <!-- <span class="badge badge-secondary">1</span> -->
              </a>
            </li>
            <?php if ($credentials[3] === '1'): ?>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Suministros</h4>
              </li>
            <?php endif; ?>
            <?php if ($credentials[4] === '1'): ?>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_product_list">
                  <i class="fas fa-syringe"></i>
                  <p>Productos</p>
                  <!-- <span class="badge badge-secondary">1</span> -->
                </a>
              </li>
            <?php endif; ?>
            <?php if ($credentials[0] === '1'): ?>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Seguridad</h4>
              </li>
              <?php if (isset($credentials[1]) && $credentials[1] === '1'): ?>
                <li class="nav-item" style="background: transparent !important;">
                  <a href="#" class="module_bloq">
                    <i class="fas fa-user-friends"></i>
                    <p>Usuarios</p>
                    <!-- <span class="badge badge-secondary">1</span> -->
                  </a>
                </li>
              <?php endif; ?>
              <?php if (isset($credentials[2]) && $credentials[2] === '1'): ?>
                <li class="nav-item" style="background: transparent !important;">
                  <a href="#" class="module_bloq">
                    <i class="fas fa-user-friends"></i>
                    <p>Accesos</p>
                    <!-- <span class="badge badge-secondary">1</span> -->
                  </a>
                </li>
              <?php endif; ?>
            <?php endif; ?>
            <?php if ($credentials[5] === '1'): ?>
              <li class="nav-section">
                <h4 class="text-section">Ventas</h4>
              </li>
            <?php endif; ?>
            <?php if ($credentials[6] === '1'): ?>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_client_list">
                  <i class="fas fa-user-lock"></i>
                  <p>Clientes</p>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($credentials[7] === '1'): ?>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_client_create">
                  <i class="fas fa-user-plus"></i>
                  <p>Crear</p>
                </a>
              </li>
            <?php endif; ?>
            <?php if ($credentials[9] === '1'): ?>
              <li class="nav-section">
                <h4 class="text-section">Logistica</h4>
              </li>
            <?php endif; ?>
            <?php if ($credentials[10] === '1'): ?>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_remisiones">
                  <i class="icon-doc"></i>
                  <p>Remisiones</p>
                </a>
              </li>
            <?php endif; ?>
            <?php if ((isset($credentials[13]) && $credentials[13] === '1') || (isset($credentials[14]) && $credentials[14] === '1')): ?>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_bodega">
                  <i class="fas fa-warehouse"></i>
                  <p>Bodega</p>
                </a>
              </li>
            <?php endif; ?>
            <li class="nav-section">
              <span class="sidebar-mini-icon">
                <i class="fa fa-ellipsis-h"></i>
              </span>
              <h4 class="text-section">Contraparte</h4>
            </li>
            <li class="nav-item" style="background: transparent !important;">
              <a href="#" id="btn_open_proveedor">
                <i class="fas fa-truck"></i>
                <p>Proveedor</p>
              </a>
            </li>
            <li class="nav-item" style="background: transparent !important;">
              <a href="#" id="btn_open_cliente">
                <i class="fas fa-user-tie"></i>
                <p>Cliente</p>
              </a>
            </li>
            <li class="nav-item" style="background: transparent !important;">
              <a href="#" id="btn_open_empleado">
                <i class="fas fa-id-card"></i>
                <p>Empleado</p>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <!-- End Sidebar -->

    <!-- Ini Content -->
    <div class="main-panel">
      <!-- Ini Header -->
      <div class="main-header">
        <div class="main-header-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="<?= base_url('public/assets/img/kaiadmin/banner_gms.jpg') ?>"
                alt="navbar brand"
                class="navbar-brand rounded"
                height="40" />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-round btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-round btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <!-- Navbar Header -->
        <nav
          class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
          <div class="container-fluid">
            <nav
              class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex">
            </nav>

            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
              <li
                class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                <a
                  class="nav-link dropdown-toggle"
                  data-bs-toggle="dropdown"
                  href="#"
                  role="button"
                  aria-expanded="false"
                  aria-haspopup="true">
                  <i class="fa fa-search"></i>
                </a>
                <ul class="dropdown-menu dropdown-search animated fadeIn">
                  <form class="navbar-left navbar-form nav-search">
                    <div class="input-group">
                      <input
                        type="text"
                        placeholder="Search ..."
                        class="form-control" />
                    </div>
                  </form>
                </ul>
              </li>
              <li class="nav-item topbar-icon dropdown hidden-caret">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="notifDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false">
                  <i class="fa fa-bell"></i>
                  <span class="notification">1</span>
                </a>
                <ul
                  class="dropdown-menu notif-box animated fadeIn"
                  aria-labelledby="notifDropdown">
                  <li>
                    <div class="dropdown-title">
                      Tienes 1 nueva notificacion.
                    </div>
                  </li>
                  <li>
                    <div class="notif-scroll scrollbar-outer">
                      <div class="notif-center">
                        <a href="#">
                          <div class="notif-icon notif-primary">
                            <i class="fa fa-user-plus"></i>
                          </div>
                          <div class="notif-content">
                            <span class="block"> Bienvenido!!! </span>
                            <span class="time">Hace 1 minuto</span>
                          </div>
                        </a>
                      </div>
                    </div>
                  </li>
                  <li>
                    <a class="see-all" href="javascript:void(0);">Ver todas las notificaciones<i class="fa fa-angle-right"></i>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item topbar-user dropdown hidden-caret">
                <a
                  class="dropdown-toggle profile-pic"
                  data-bs-toggle="dropdown"
                  href="#"
                  aria-expanded="false">
                  <div class="avatar-sm">
                    <img
                      src="<?= session()->get('gender') === 'male' ? base_url('public/assets/img/avatar-male.jpg') : base_url('public/assets/img/avatar-female.png') ?>"
                      alt="..."
                      class="avatar-img rounded-circle" />
                  </div>
                  <span class="profile-username">
                    <span class="op-7">Hola,</span>
                    <span class="fw-bold"><?= session()->get('name') ?></span>
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                  <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                      <div class="user-box">
                        <div class="avatar-lg">
                          <img
                            src="<?= session()->get('gender') === 'male' ? base_url('public/assets/img/avatar-male.jpg') : base_url('public/assets/img/avatar-female.png') ?>"
                            alt="image profile"
                            class="avatar-img rounded" />
                        </div>
                        <div class="u-text">
                          <h4>Jose</h4>
                          <p class="text-muted">jose@gmsuministros.com</p>
                          <a
                            href="#"
                            class="btn btn-round btn-xs btn-secondary btn-sm">Ver Perfil</a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Reportar Error</a>
                      <a class="dropdown-item" href="#">Limpiar Cache</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Configuracion</a>
                      <a class="dropdown-item" href="#" id="btn_change_pin_trigger">Cambiar PIN</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="<?= base_url('logout') ?>">Salir</a>
                    </li>
                  </div>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
      <!-- End Header -->

      <!-- Ini Body -->
      <div class="container">
        <div class="page-inner">
          <!-- Ini Panel Nav -->
          <div class="page-header">
            <h4 id="cont_title" class="page-title">Dashboard</h4>
          </div>
          <!-- End Panel Nav -->

          <!-- ======================================================== -->
          <!--   Screens                                               -->
          <!-- ======================================================== -->

          <!-- Ini Loading Screen -->
          <div id='cont_loading' class="row d-none">
            <div class="col-md-12 d-flex justify-content-center">
              <div class="loader_spinner"></div>
            </div>
          </div>
          <!-- End Loading Screen -->

          <!-- Ini Dashboard Screen -->
          <div id="cont_dashboard" class="row">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-body">
                  <h2 class="fw-bold text-primary">¡Bienvenido al Sistema de Gestión!</h2>
                  <p class="text-muted">Seleccione una opción del menú lateral para comenzar a administrar sus implementos médicos y visualizar reportes detallados.</p>
                </div>
              </div>
            </div>
          </div>
          <!-- End Dashboard Screen -->

          <!-- Ini Product Screen -->
          <div id="cont_product" class="row d-none">
            <!-- Ini Producct List Sub-Screen -->
            <div class="col-md-12" id="screen_catalog_products">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                  <h4 class="card-title mb-0">Catálogo de Productos</h4>
                  <div class="form-group mb-0 pb-0 pt-0">
                    <div class="input-icon" style="min-width: 250px;">
                      <span class="input-icon-addon">
                        <i class="fa fa-search"></i>
                      </span>
                      <input type="text" id="search_products" class="form-control" placeholder="Buscar producto, marca, categoría...">
                    </div>
                  </div>
                </div>
                <div class="card-body bg-light" style="border-radius: 0 0 10px 10px;">
                  <div class="row" id="catalog_list_productos">
                    <!-- Las tarjetas de productos se renderizarán aquí -->
                  </div>
                </div>
              </div>
            </div>
            <!-- End Producct List Sub-Screen -->

            <!-- Ini Product Detail Sub-Screen -->
            <div class="row d-none" id="screen_product_detail">
              <!-- Panel Opciones -->
              <div class="col-md-12 d-flex align-items-center mb-4">
                <button class="btn btn-secondary btn-round me-3" id="btn_back_to_catalog">
                  <i class="fas fa-arrow-left"></i> Volver al Catálogo
                </button>
                <h1 class="m-0 flex-grow-1 text-center" style="margin-left: -150px !important;">Detalles</h1>
              </div>

              <!-- Panel Información General -->
              <div class="col-md-4">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Información General</h4>
                  </div>
                  <div class="card-body">
                    <!-- Contenedor para Imagen de la Familia en Información General -->
                    <div id="cont_info_general_image" class="form-group text-center d-none" style="border-top: 1px solid #ebedf2; padding-top: 15px; margin-top: 15px;">
                      <img id="info_general_family_image" src="" alt="Imagen Principal" class="img-fluid rounded shadow-sm" style="max-height: 180px; object-fit: contain;">
                    </div>

                    <?php if (isset($credentials[12]) && $credentials[12] === '1'): ?>
                      <div class="form-group text-center mt-2">
                        <button id="btn_change_family_image" class="btn btn-primary btn-round btn-sm w-100">
                          <i class="fas fa-camera"></i> Cambiar Imagen Principal
                        </button>
                      </div>
                    <?php endif; ?>
                    <div class="form-group">
                      <label for="in_nombre_producto">Nombre</label>
                      <input id="in_nombre_producto" disabled type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                      <label for="in_categoria_producto">Categoria</label>
                      <input id="in_categoria_producto" disabled type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                      <label for="in_presentacion_producto">Presentacion</label>
                      <input id="in_presentacion_producto" disabled type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                      <label for="in_marca_producto">Modelo</label>
                      <input id="in_marca_producto" disabled type="text" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                      <label for="in_comentario_producto">Comentario</label>
                      <textarea id="in_comentario_producto" class="form-control" rows="5" disabled>                          </textarea>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Panel Contenido -->
              <div class="col-md-7">
                <!-- Panel Documentos -->
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h4 class="card-title">Documentos</h4>
                      <?php if (isset($credentials[8]) && $credentials[8] === '1'): ?>
                        <button id="btn_upload_pdf" class="btn btn-round btn-primary btn-sm"><i class="fas fa-upload"></i> Subir PDF</button>
                      <?php endif; ?>
                    </div>
                    <div class="card-body">
                      <div id="cont_documentos_producto" class="row align-items-start">
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Panel Imagenes -->
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h4 class="card-title">Imagenes</h4>
                      <?php if (isset($credentials[8]) && $credentials[8] === '1'): ?>
                        <button id="btn_upload_picture" class="btn btn-round btn-primary btn-sm"><i class="fas fa-upload"></i> Subir Imagen</button>
                      <?php endif; ?>
                    </div>
                    <div class="card-body">
                      <div id="cont_imagenes_producto"></div>
                    </div>
                  </div>
                </div>
                <!-- Panel Videos -->
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h4 class="card-title">Videos</h4>
                      <?php if (isset($credentials[8]) && $credentials[8] === '1'): ?>
                        <button id="btn_upload_video" class="btn btn-round btn-primary btn-sm"><i class="fas fa-upload"></i> Subir Video</button>
                      <?php endif; ?>
                    </div>
                    <div class="card-body">
                      <div id="cont_videos_producto"></div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Panel Referencias -->
              <div class="col-md-12 d-none" id="cont_variantes_wrapper">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Referencias Disponibles</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table class="table table-hover">
                        <thead>
                          <tr>
                            <th>Presentación</th>
                            <th>Categoría</th>
                            <th>Modelo</th>
                            <th>Acción</th>
                          </tr>
                        </thead>
                        <tbody id="list_variantes_producto">
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <!-- End Product Detail Sub-Screen -->
          </div>
          <!-- End Product Create Screen -->

          <!-- Ini Remisiones Screen -->
          <div id="cont_remisiones" class="row d-none">

            <!-- Ini Lista de Remisiones Sub-Screen -->
            <div class="col-md-12" id="remisiones_list_view">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Remisiones</h4>
                  <div class="card-tools">
                    <?php if (isset($credentials[11]) && $credentials[11] === '1'): ?>
                      <button class="btn btn-round btn-primary" id="btn_create_remision">
                        <span class="btn-label">
                          <i class="fa fa-plus"></i>
                        </span>
                        Crear
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="tbl_list_remisiones" class="display table table-striped table-hover w-100">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Consecutivo</th>
                          <th>Tipo</th>
                          <th>Cliente</th>
                          <th>NIT</th>
                          <th>Ciudad</th>
                          <th>Fecha</th>
                          <th class="text-center">Ver</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Lista de Remisiones Sub-Screen -->

            <!-- Ini Crear Remisión Sub-Screen -->
            <div class="col-md-12 d-none" id="remisiones_create_view">
              <div class="card">
                <div class="card-header">
                  <div class="card-title d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-file-invoice"></i> Crear Remisión</span>
                    <button class="btn btn-sm btn-secondary btn-round" id="btn_back_to_remisiones_list">
                      <i class="fas fa-arrow-left"></i> Volver
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <form id="form_remision_create">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="remision_ciudad">Ciudad</label>
                          <select class="form-select" id="remision_ciudad" name="ciudad" required>
                            <option value="">Seleccione una ciudad</option>
                            <?php if (isset($cities) && is_array($cities)): ?>
                              <?php foreach ($cities as $city): ?>
                                <option value="<?= esc($city['id']) ?>"><?= esc($city['name']) ?></option>
                              <?php endforeach; ?>
                            <?php endif; ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="remision_dispatcher">Despachador</label>
                          <input type="text" class="form-control" id="remision_dispatcher" name="dispatcher" placeholder="Nombre de quien despacha">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label for="remision_cliente">Cliente</label>
                          <input type="text" class="form-control" id="remision_cliente" name="cliente" placeholder="Nombre del cliente">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label for="remision_nit">NIT / ID</label>
                          <input type="text" class="form-control" id="remision_nit" name="nit" placeholder="NIT del cliente">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="remision_adress">Dirección de Envío</label>
                          <input type="text" class="form-control" id="remision_adress" name="adress" placeholder="Dirección completa">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group form-group-default">
                          <label for="remision_observacion">Observaciones</label>
                          <input type="text" class="form-control" id="remision_observacion" name="observacion" placeholder="Observaciones adicionales">
                        </div>
                      </div>
                    </div>

                    <hr>
                    <h4 class="mt-4 mb-3">Líneas de Remisión</h4>
                    <div class="table-responsive">
                      <table class="table table-bordered table-hover" id="tbl_remision_items">
                        <thead class="bg-light">
                          <tr>
                            <th>Referencia</th>
                            <th>Descripción / Producto</th>
                            <th width="150">Lote</th>
                            <th width="140">F. Venc.</th>
                            <th width="120">Cantidad</th>
                            <th width="80" class="text-center">Acción</th>
                          </tr>
                        </thead>
                        <tbody id="remision_items_body">
                          <tr>
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
                          </tr>
                        </tbody>
                      </table>
                      <button type="button" class="btn btn-info btn-sm btn-round mt-2" id="btn_agregar_linea_remision">
                        <i class="fas fa-plus"></i> Agregar Línea
                      </button>
                    </div>

                  </form>
                </div>
                <div class="card-action text-end">
                  <button class="btn btn-round btn-secondary" id="btn_reset_remision_form">
                    <i class="fas fa-redo"></i> Limpiar
                  </button>
                  <button class="btn btn-round btn-success" id="btn_guardar_remision">
                    <i class="fas fa-save"></i> Generar Remisión
                  </button>
                </div>
              </div>
            </div>
            <!-- End Crear Remisión Sub-Screen -->

          </div>
          <!-- End Remisiones Screen -->

          <!-- ======================================================== -->
          <!--   BODEGA SCREENS                                         -->
          <!-- ======================================================== -->

          <!-- Ini Bodega Screen -->
          <div id="cont_bodega" class="row d-none">

            <!-- Ini Lista de Bodegas Sub-Screen -->
            <div class="col-md-12" id="bodega_list_view">
              <div class="card card-round">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                  <div>
                    <h4 class="card-title mb-0"><i class="fas fa-warehouse text-primary me-2"></i>Gestión de Bodegas</h4>
                    <p class="text-muted small mb-0">Listado de centros de almacenamiento y sus existencias</p>
                  </div>
                  <div class="card-tools">
                    <button class="btn btn-round btn-primary" data-bs-toggle="modal" data-bs-target="#modal_create_warehouse">
                      <span class="btn-label">
                        <i class="fa fa-plus"></i>
                      </span>
                      Nueva Bodega
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="tbl_list_bodegas" class="display table table-striped table-hover w-100">
                      <thead>
                        <tr>
                          <th style="width: 50px;">ID</th>
                          <th>Bodega</th>
                          <th>Dirección</th>
                          <th>Admin</th>
                          <th>Titular</th>
                          <th>Estado</th>
                          <th class="text-center">Artículos</th>
                          <th>Fecha Registro</th>
                          <th class="text-center" style="width: 140px;">Acción</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Lista de Bodegas Sub-Screen -->

            <!-- Ini Detalle / Balance de Bodega Sub-Screen -->
            <div class="col-md-12 d-none" id="bodega_detail_view">
              <div class="card card-round">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                  <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-secondary btn-round me-3" id="btn_back_to_bodegas">
                      <i class="fas fa-arrow-left me-1"></i> Volver a Bodegas
                    </button>
                    <div>
                      <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="fas fa-boxes text-info me-2"></i>
                        <span id="bodega_detail_title">Inventario de Bodega</span>
                        <span id="bodega_detail_status" class="ms-2"></span>
                        <span id="bodega_detail_role_badge" class="ms-2"></span>
                      </h4>
                      <p class="text-muted small mb-0">
                        <span id="bodega_detail_adress"></span>
                        <span id="bodega_detail_resp_wrapper" class="ms-2 ps-2 border-start d-none">
                          <i class="fas fa-user-shield text-primary me-1"></i>Admin: <strong id="bodega_detail_responsible" class="text-dark"></strong>
                        </span>
                        <span id="bodega_detail_titular_wrapper" class="ms-2 ps-2 border-start d-none">
                          <i class="fas fa-user-tie text-info me-1"></i>Titular: <strong id="bodega_detail_titular" class="text-dark"></strong>
                        </span>
                      </p>
                    </div>
                  </div>
                  <div class="card-tools mt-2 mt-sm-0">
                    <?php if (isset($credentials[14]) && $credentials[14] === '1'): ?>
                    <button class="btn btn-round btn-dark btn-sm me-2 text-white d-none" id="btn_open_adjust_modal">
                      <span class="btn-label">
                        <i class="fas fa-sliders-h"></i>
                      </span>
                      Ajustar
                    </button>
                    <?php endif; ?>
                    <button class="btn btn-round btn-warning btn-sm me-2" id="btn_open_transfer_modal">
                      <span class="btn-label">
                        <i class="fas fa-exchange-alt"></i>
                      </span>
                      Transferir Stock
                    </button>
                    <button class="btn btn-round btn-outline-success btn-sm me-2 d-none" id="btn_open_mass_upload_modal" data-bs-toggle="modal" data-bs-target="#modal_mass_upload_warehouse">
                      <span class="btn-label">
                        <i class="fas fa-file-excel"></i>
                      </span>
                      Cargue Masivo
                    </button>
                    <button class="btn btn-round btn-success btn-sm d-none" id="btn_add_warehouse_item" data-bs-toggle="modal" data-bs-target="#modal_add_warehouse_item">
                      <span class="btn-label">
                        <i class="fa fa-plus"></i>
                      </span>
                      Generar Ingreso
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <!-- Tarjetas de resumen del balance -->
                  <div class="row mb-4">
                    <div class="col-md-12">
                      <div class="card card-stats card-round bg-primary text-white shadow-sm mb-0">
                        <div class="card-body py-3">
                          <div class="row align-items-center">
                            <div class="col-icon">
                              <div class="icon-big text-center icon-primary bubble-shadow-small bg-white text-primary">
                                <i class="fas fa-cubes"></i>
                              </div>
                            </div>
                            <div class="col col-stats ms-3 ms-sm-0">
                              <div class="numbers">
                                <p class="card-category text-white-50 mb-0">Total Artículos Registrados en esta Bodega</p>
                                <h4 class="card-title text-white fw-bold mb-0" id="stat_bodega_items">0</h4>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Tabla de existencias/balance -->
                  <div class="table-responsive">
                    <table id="tbl_list_bodega_balance" class="display table table-hover w-100 align-middle">
                      <thead>
                        <tr>
                          <th style="width: 50px;">ID</th>
                          <th style="min-width: 250px;">Familia / Referencia</th>
                          <th style="width: 120px;">Lote</th>
                          <th style="width: 120px;">Vencimiento</th>
                          <th class="text-center" style="width: 100px;">Cantidad</th>
                          <th style="width: 150px;">Última Actualización</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <!-- End Detalle / Balance de Bodega Sub-Screen -->

          </div>
          <!-- End Bodega Screen -->

          <!-- ======================================================== -->
          <!--   PROVEEDORES Y CLIENTES (CONTRAPARTE) SCREENS           -->
          <!-- ======================================================== -->

          <!-- Ini Proveedor Screen -->
          <div id="cont_proveedor" class="row d-none">
            <div class="col-12">
              <div class="card card-round shadow-none border">
                <div class="card-body text-center py-5">
                  <i class="fas fa-tools fa-2x text-muted mb-3 opacity-75"></i>
                  <h4 class="fw-bold text-dark mb-1">Proveedores</h4>
                  <p class="text-muted small mb-0">Módulo en desarrollo</p>
                </div>
              </div>
            </div>
          </div>
          <!-- End Proveedor Screen -->

          <!-- Ini Cliente Screen -->
          <div id="cont_cliente" class="row d-none">
            <!-- Tarjeta Formulario de Registro de Cliente -->
            <div class="col-12 mb-4" id="card_client_create">
              <div class="card card-round shadow-sm border">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-user-plus text-primary me-2 fa-lg"></i>
                    <span class="fw-bold">Registrar Cliente</span>
                  </div>
                </div>
                <div class="card-body">
                  <form id="form_client_create" autocomplete="off">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="client_nombre">Nombre / Razón Social <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="client_nombre" name="nombre_cliente" placeholder="Ej: Juan Pérez / Inversiones SAS" required maxlength="255">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label for="client_tipo_doc">Tipo de Documento</label>
                          <select class="form-select" id="client_tipo_doc" name="tipo_documento">
                            <option value="CC">CC - Cédula de Ciudadanía</option>
                            <option value="NIT">NIT - Número de Identificación Tributaria</option>
                            <option value="CE">CE - Cédula de Extranjería</option>
                            <option value="PAS">PAS - Pasaporte</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label for="client_num_doc">Número de Documento / NIT <span class="text-danger">*</span></label>
                          <input type="text" class="form-control" id="client_num_doc" name="numero_documento" placeholder="Ej: 900123456-1" required maxlength="100">
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label for="client_telefono">Teléfono / Celular</label>
                          <input type="text" class="form-control" id="client_telefono" name="telefono_cliente" placeholder="Ej: 3001234567" maxlength="50">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label for="client_correo">Correo Electrónico</label>
                          <input type="email" class="form-control" id="client_correo" name="correo_cliente" placeholder="Ej: cliente@empresa.com" maxlength="255">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label for="client_direccion">Dirección</label>
                          <input type="text" class="form-control" id="client_direccion" name="direccion_cliente" placeholder="Ej: Calle 100 # 15 - 20" maxlength="255">
                        </div>
                      </div>
                    </div>

                    <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
                      <button type="button" class="btn btn-round btn-secondary" id="btn_reset_client">
                        <i class="fas fa-redo me-1"></i> Limpiar
                      </button>
                      <button type="button" class="btn btn-round btn-success" id="btn_save_client">
                        <i class="fas fa-save me-1"></i> Guardar Cliente
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>

            <!-- Tarjeta Listado de Clientes Registrados -->
            <div class="col-12" id="cliente_list_view">
              <div class="card card-round shadow-sm border">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-title mb-0 d-flex align-items-center">
                    <i class="fas fa-users text-primary me-2 fa-lg"></i>
                    <span class="fw-bold">Clientes Registrados</span>
                  </div>
                  <div class="card-tools">
                    <button class="btn btn-round btn-sm btn-outline-primary" id="btn_reload_clients">
                      <i class="fas fa-sync-alt me-1"></i> Actualizar
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="tbl_list_clientes" class="display table table-striped table-hover w-100">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nombre / Razón Social</th>
                          <th>Tipo Doc</th>
                          <th>N° Documento</th>
                          <th>Teléfono</th>
                          <th>Correo Electrónico</th>
                          <th>Dirección</th>
                          <th>Fecha Registro</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- End Cliente Screen -->

          <!-- Ini Crear Contraparte Sub-Screen -->
          <div class="col-12 d-none" id="counterparty_create_view">
            <div class="card card-round shadow-none border">
              <div class="card-body text-center py-5">
                <i class="fas fa-tools fa-2x text-muted mb-3 opacity-75"></i>
                <h4 class="fw-bold text-dark mb-1">Registrar Contraparte</h4>
                <p class="text-muted small mb-0">Módulo en desarrollo</p>
              </div>
            </div>
          </div>
          <!-- End Crear Contraparte Sub-Screen -->

          <!-- ======================================================== -->
          <!-- MODAL DE COMPARTIR FORMULARIO                            -->
          <!-- ======================================================== -->
          <div class="modal fade" id="modal_share_form" tabindex="-1" aria-labelledby="modalShareFormLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-info">
                  <h5 class="modal-title text-white fw-bold" id="modalShareFormLabel"><i class="fas fa-share-alt me-2"></i> Compartir Formulario de Registro</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <form id="form_share_counterparty">
                    <?= csrf_field() ?>
                    <input type="hidden" id="share_contraparte_type" name="contraparte_type" value="proveedor">

                    <div class="form-group mb-3">
                      <label class="form-label fw-bold">Tipo de Persona</label>
                      <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="person_type" id="share_person_type_juridica" value="juridica" checked autocomplete="off">
                        <label class="btn btn-outline-info fw-bold" for="share_person_type_juridica">Persona Jurídica</label>

                        <input type="radio" class="btn-check" name="person_type" id="share_person_type_natural" value="natural" autocomplete="off">
                        <label class="btn btn-outline-info fw-bold" for="share_person_type_natural">Persona Natural</label>
                      </div>
                    </div>

                    <div class="form-group mb-3">
                      <label for="share_numero_identificacion" class="form-label fw-bold">NIT o Cédula de Identificación *</label>
                      <input type="text" class="form-control" id="share_numero_identificacion" name="numero_identificacion" placeholder="Ej: 901234567-8" required>
                      <small class="text-muted">Este número quedará prellenado en el formulario y no podrá ser modificado por el destinatario.</small>
                    </div>
                  </form>

                  <!-- Área donde se muestra el enlace generado -->
                  <div class="d-none mt-4" id="div_generated_link">
                    <hr>
                    <label class="form-label fw-bold text-success"><i class="fas fa-link"></i> Enlace Generado</label>
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" id="input_generated_link" readonly>
                      <button class="btn btn-outline-info" type="button" id="btn_copy_link"><i class="fas fa-copy"></i> Copiar</button>
                    </div>
                    <div class="d-grid gap-2">
                      <a href="#" target="_blank" class="btn btn-success" id="btn_whatsapp_share">
                        <i class="fab fa-whatsapp"></i> Compartir por WhatsApp
                      </a>
                    </div>
                  </div>
                </div>
                <div class="modal-footer border-0 bg-light">
                  <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-info btn-round shadow-sm text-white" id="btn_generate_share_link">Generar Enlace</button>
                </div>
              </div>
            </div>
          </div>

          <!-- ======================================================== -->
          <!-- MODAL DE SUBIR POLÍTICA PDF                              -->
          <!-- ======================================================== -->
          <div class="modal fade" id="modal_upload_policy" tabindex="-1" aria-labelledby="modalUploadPolicyLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 bg-warning text-white">
                  <h5 class="modal-title fw-bold" id="modalUploadPolicyLabel"><i class="fas fa-file-upload me-2"></i> Subir Política de Tratamiento de Datos (PDF)</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                  <form id="form_upload_policy" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="form-group mb-3">
                      <label class="form-label fw-bold text-dark">Seleccionar Archivo PDF *</label>
                      <input type="file" class="form-control" id="policy_file" name="policy_file" accept=".pdf" required style="border: 1px solid #ced4da;">
                      <small class="text-muted d-block mt-2">Suba el archivo de políticas que se mostrará a los clientes y proveedores en el formulario externo antes del registro.</small>
                    </div>

                    <div class="text-end mt-4">
                      <button type="button" class="btn btn-secondary btn-round btn-border me-2" data-bs-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-warning btn-round text-white shadow-sm fw-bold" id="btn_submit_policy_file">Subir Archivo</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
      <!-- End Body -->
    </div>
    <!-- End Content -->

    <!-- Ini Footer -->
    <footer class="footer">
      <div class="container-fluid d-flex justify-content-between">
        <div>
          Desarrollado por
          <a target="_blank" href="https://gmsuministros.com/">GM Suministros</a>.
        </div>
      </div>
    </footer>
    <!-- End Footer -->

  </div>

  <!-- ======================================================== -->
  <!--   MODALES                                               -->
  <!-- ======================================================== -->

  <!-- Modal para Recomendar Artículo -->
  <div class="modal fade" id="modal_recommend_article" tabindex="-1" aria-labelledby="modalRecommendLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0" style="background: linear-gradient(135deg, #1572e8 0%, #0d47a1 100%);">
          <h5 class="modal-title text-white fw-bold" id="modalRecommendLabel"><i class="fas fa-share-alt me-2"></i>Recomendar un Artículo</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <form id="form_recommend_article">
            <div class="row">
              <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">URL del Artículo</label>
                <div class="input-group">
                  <span class="input-group-text bg-light"><i class="fas fa-link text-primary"></i></span>
                  <input type="url" class="form-control" placeholder="https://ejemplo.com/articulo" required>
                </div>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">Título</label>
                <input type="text" class="form-control" placeholder="Escribe el título del artículo" required>
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Categoría</label>
                <select class="form-select">
                  <option>Salud</option>
                  <option>Tecnología</option>
                  <option>Negocios</option>
                  <option>Innovación</option>
                  <option>Cultura de Empresa</option>
                </select>
              </div>
              <div class="col-md-12 mb-3">
                <label class="form-label fw-bold">¿Por qué lo recomiendas? (Breve resumen)</label>
                <textarea class="form-control" rows="3" placeholder="Comenta brevemente por qué tus compañeros deberían leerlo..." required></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-round shadow-sm" id="btn_save_recommendation">Compartir con el equipo</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Carga de Imágenes -->
  <div class="modal fade" id="modal_upload_image" tabindex="-1" aria-labelledby="modalUploadImageLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUploadImageLabel">Cargar Imagen para el Producto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Hidden input for Siigo Product ID -->
          <input type="hidden" id="siigo_product_id_upload">
          <input type="hidden" id="family_id_upload">

          <div id="drag_drop_area" class="border border-primary border-2 rounded p-5 text-center p-5 mb-3" style="border-style: dashed !important; cursor: pointer;">
            <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
            <h5>Arrastra una imagen aquí o haz clic para seleccionar</h5>
            <input type="file" id="file_input_image" class="d-none" accept="image/*">
          </div>

          <div id="image_preview_container" class="text-center d-none">
            <img id="image_preview" src="" alt="Vista previa" class="img-fluid rounded mb-3" style="max-height: 200px;">
            <p id="image_filename" class="text-muted"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-round btn-primary" id="btn_upload_image">Subir Imagen</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Carga de Documentos PDF -->
  <div class="modal fade" id="modal_upload_pdf" tabindex="-1" aria-labelledby="modalUploadPdfLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUploadPdfLabel">Cargar Documento PDF para la Familia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="siigo_family_id_upload">
          <input type="hidden" id="siigo_product_id_for_reload">
          <div class="form-group mb-3">
            <label for="pdf_document_name">Nombre del Documento</label>
            <input type="text" class="form-control" id="pdf_document_name" placeholder="Ej. Ficha Técnica">
          </div>
          <div class="form-group">
            <label for="file_input_pdf">Seleccionar archivo PDF</label>
            <input type="file" class="form-control" id="file_input_pdf" accept="application/pdf">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-round btn-primary" id="btn_save_upload_pdf">Subir Documento</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Carga de Imágenes de la Galería -->
  <div class="modal fade" id="modal_upload_picture" tabindex="-1" aria-labelledby="modalUploadPictureLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUploadPictureLabel">Cargar Imagen para la Familia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="picture_family_id_upload">
          <div class="form-group">
            <label for="file_input_picture">Seleccionar archivo de Imagen</label>
            <input type="file" class="form-control" id="file_input_picture" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-round btn-primary" id="btn_save_upload_picture">Subir Imagen</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Carga de Videos -->
  <div class="modal fade" id="modal_upload_video" tabindex="-1" aria-labelledby="modalUploadVideoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalUploadVideoLabel">Cargar Video para la Familia</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="video_family_id_upload">
          <div class="form-group">
            <label for="file_input_video">Seleccionar archivo de Video</label>
            <input type="file" class="form-control" id="file_input_video" accept="video/mp4,video/webm,video/ogg">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-round btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-round btn-primary" id="btn_save_upload_video">Subir Video</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Visor de PDF -->
  <div class="modal fade" id="modal_view_pdf" tabindex="-1" aria-labelledby="modalViewPdfLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalViewPdfLabel">Visor de Documento</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0" style="background-color: #525659;">
          <iframe id="pdf_viewer_iframe" src="" style="width: 100%; height: 80vh; border: none; display: block;"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Visor de Remisión en PDF -->
  <div class="modal fade" id="modal_view_remision" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header d-flex align-items-center justify-content-between">
          <h5 class="modal-title mb-0"><i class="fas fa-file-invoice text-primary me-2"></i>Vista de Remisión</h5>
          <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-sm btn-primary" id="btn_modal_print_remision">
              <i class="fas fa-print me-1"></i> Imprimir Remisión
            </button>
            <button type="button" class="btn-close ms-2" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
        </div>
        <div class="modal-body p-0" style="background-color: #525659;">
          <iframe id="remision_pdf_iframe" src="" style="width: 100%; height: 85vh; border: none; display: block;"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal para Cambiar PIN -->
  <div class="modal fade" id="modal_change_pin" tabindex="-1" aria-labelledby="modalChangePinLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0" style="background: linear-gradient(135deg, #1572e8 0%, #0d47a1 100%);">
          <h5 class="modal-title text-white fw-bold" id="modalChangePinLabel"><i class="fas fa-key me-2"></i>Cambiar PIN de Seguridad</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <form id="form_change_pin">
            <?= csrf_field() ?>
            <div class="form-group mb-3">
              <label for="pin_actual" class="form-label fw-bold">PIN Actual</label>
              <input type="password" class="form-control" id="pin_actual" name="pin_actual" maxlength="4" inputmode="numeric" pattern="[0-9]{4}" placeholder="••••" required>
            </div>
            <div class="form-group mb-3">
              <label for="pin_nuevo" class="form-label fw-bold">Nuevo PIN (4 dígitos)</label>
              <input type="password" class="form-control" id="pin_nuevo" name="pin_nuevo" maxlength="4" inputmode="numeric" pattern="[0-9]{4}" placeholder="••••" required>
            </div>
            <div class="form-group mb-3">
              <label for="pin_confirmar" class="form-label fw-bold">Confirmar Nuevo PIN</label>
              <input type="password" class="form-control" id="pin_confirmar" name="pin_confirmar" maxlength="4" inputmode="numeric" pattern="[0-9]{4}" placeholder="••••" required>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-round shadow-sm" id="btn_save_pin">Guardar PIN</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Crear Bodega -->
  <div class="modal fade" id="modal_create_warehouse" tabindex="-1" aria-labelledby="modalCreateWarehouseLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 bg-primary text-white">
          <h5 class="modal-title fw-bold" id="modalCreateWarehouseLabel"><i class="fas fa-warehouse me-2"></i>Registrar Nueva Bodega</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <form id="form_create_warehouse">
            <?= csrf_field() ?>
            <div class="form-group mb-3">
              <label for="in_warehouse_name" class="form-label fw-bold">Nombre de la Bodega *</label>
              <input type="text" class="form-control" id="in_warehouse_name" name="name" maxlength="55" placeholder="Ej: Bodega Principal Norte" required>
            </div>
            <div class="form-group mb-3">
              <label for="in_warehouse_adress" class="form-label fw-bold">Dirección *</label>
              <input type="text" class="form-control" id="in_warehouse_adress" name="adress" maxlength="55" placeholder="Ej: Calle 45 # 12-34" required>
            </div>
            <div class="form-group mb-3">
              <label for="in_warehouse_user" class="form-label fw-bold">Usuario Administrador *</label>
              <select class="form-select" id="in_warehouse_user" name="id_user_admin" required>
                <option value="" disabled selected>-- Seleccione el usuario administrador --</option>
                <?php if (!empty($warehouseUsers)): ?>
                  <?php foreach ($warehouseUsers as $wUser): ?>
                    <option value="<?= esc($wUser['id']) ?>"><?= esc($wUser['name']) ?> (ID: <?= esc($wUser['id']) ?> - <?= esc($wUser['email']) ?>)</option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <small class="form-text text-muted">Asigne el administrador que tendrá autorización para operar y gestionar esta bodega.</small>
            </div>
            <div class="form-group mb-3">
              <label for="in_warehouse_client" class="form-label fw-bold">Titular (Cliente)</label>
              <select class="form-select" id="in_warehouse_client" name="id_client">
                <option value="" selected>-- Sin titular / Opcional --</option>
                <?php if (!empty($clientsList)): ?>
                  <?php foreach ($clientsList as $cItem): ?>
                    <option value="<?= esc($cItem['id']) ?>"><?= esc($cItem['nombre_cliente']) ?> (ID: <?= esc($cItem['id']) ?><?= !empty($cItem['numero_documento']) ? ' - ' . esc($cItem['numero_documento']) : '' ?>)</option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
              <small class="form-text text-muted">Seleccione el cliente titular asociado a la bodega (si aplica).</small>
            </div>
            <div class="form-group mb-3">
              <label for="in_warehouse_state" class="form-label fw-bold">Estado</label>
              <select class="form-select" id="in_warehouse_state" name="state">
                <option value="ACTIVE" selected>Activo</option>
                <option value="INACTIVE">Inactivo</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-primary btn-round shadow-sm" id="btn_save_warehouse">
            <i class="fas fa-save"></i> Guardar Bodega
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Ingreso de Artículos a Bodega -->
  <div class="modal fade" id="modal_add_warehouse_item" tabindex="-1" aria-labelledby="modalAddWarehouseItemLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 bg-success text-white">
          <h5 class="modal-title fw-bold" id="modalAddWarehouseItemLabel">
            <i class="fas fa-boxes me-2"></i>Ingreso de Artículos a Bodega
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" style="min-height: 400px;">
          <form id="form_add_warehouse_item">
            <?= csrf_field() ?>
            <input type="hidden" id="in_item_warehouse_id" name="id_warehouse" value="">

            <!-- Artículos dinámicos -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-list-check text-success me-2"></i>Líneas de Artículos</h6>
                <small class="text-muted">Agregue los artículos e indique las cantidades para el ingreso a inventario.</small>
              </div>
              <button type="button" class="btn btn-outline-success btn-round px-3 fw-bold" id="btn_add_remision_warehouse_line">
                <i class="fas fa-plus me-1"></i> Agregar Línea
              </button>
            </div>

            <div class="table-responsive" style="overflow: visible !important;">
              <table class="table table-bordered table-hover align-middle mb-0" id="tbl_remision_warehouse_items">
                <thead class="bg-light">
                  <tr>
                    <th style="min-width: 360px;"><i class="fas fa-tag text-success me-1"></i> Referencia / Familia *</th>
                    <th style="width: 160px;"><i class="fas fa-barcode text-muted me-1"></i> Lote</th>
                    <th style="width: 170px;"><i class="fas fa-calendar-alt text-muted me-1"></i> F. Vencimiento</th>
                    <th style="width: 130px;" class="text-center"><i class="fas fa-cubes text-muted me-1"></i> Cantidad *</th>
                    <th style="width: 70px;" class="text-center"><i class="fas fa-cog text-muted"></i></th>
                  </tr>
                </thead>
                <tbody id="remision_warehouse_items_body">
                  <!-- Filas dinámicas -->
                </tbody>
              </table>
            </div>

          </form>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success btn-round shadow-sm fw-bold" id="btn_save_warehouse_item">
            <i class="fas fa-save me-1"></i> Guardar Ingreso
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Cargue Masivo de Productos a Bodega Principal -->
  <div class="modal fade" id="modal_mass_upload_warehouse" tabindex="-1" aria-labelledby="modalMassUploadWarehouseLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 bg-success text-white py-3">
          <h5 class="modal-title fw-bold d-flex align-items-center" id="modalMassUploadWarehouseLabel">
            <i class="fas fa-file-excel me-2"></i>Cargue Masivo de Productos a Bodega Principal
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <!-- Panel Paso 1: Descargar Plantilla -->
          <div class="card border border-success-subtle bg-light rounded-3 mb-4 shadow-none">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-success me-2 px-2 py-1">Paso 1</span>
                <h6 class="fw-bold mb-0 text-dark">Descargar Plantilla por Referencia de Productos</h6>
              </div>
              <p class="text-muted small mb-3">
                Descargue el formato en Excel que contiene el catálogo organizado por la <strong>Referencia</strong> de cada artículo perteneciente a las familias de productos activas en el sistema. Diligencie únicamente las casillas de <strong>Cantidad</strong>, <strong>Lote</strong> y <strong>Fecha de Vencimiento</strong> para los artículos a ingresar.
              </p>
              <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                <a href="<?= base_url('warehouse/download_batch_template') ?>" class="btn btn-sm btn-outline-success btn-round fw-bold px-3 shadow-sm" id="btn_download_batch_template">
                  <i class="fas fa-file-excel text-success me-1"></i> Descargar Plantilla Excel (.xlsx)
                </a>
                <span class="text-muted small">
                  <i class="fas fa-info-circle text-info me-1"></i> Deje en blanco los artículos que no ingresarán.
                </span>
              </div>
            </div>
          </div>

          <!-- Panel Paso 2: Subir Archivo Diligenciado -->
          <div class="card border border-primary-subtle bg-light rounded-3 shadow-none mb-0">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <span class="badge bg-primary me-2 px-2 py-1">Paso 2</span>
                <h6 class="fw-bold mb-0 text-dark">Cargar Archivo Diligenciado</h6>
              </div>
              <p class="text-muted small mb-3">
                Suba el archivo con los datos diligenciados. El sistema registrará automáticamente las existencias en la <strong>Bodega Principal</strong> y generará la respectiva <strong>Remisión de tipo INGRESO</strong> con consecutivo oficial.
              </p>

              <!-- Zona Drag & Drop como label interactivo nativo -->
              <label for="in_mass_upload_file" id="mass_upload_dropzone" class="border border-2 border-secondary border-opacity-25 rounded-3 p-4 text-center bg-white d-block mb-0" style="cursor: pointer; transition: all 0.2s ease;">
                <input type="file" id="in_mass_upload_file" accept=".xlsx, .xls, .csv" class="d-none">
                
                <div id="mass_upload_prompt">
                  <i class="fas fa-cloud-upload-alt fa-3x text-success mb-2"></i>
                  <p class="fw-bold text-dark mb-1">Arrastre su archivo Excel aquí o haga clic para seleccionarlo</p>
                  <span class="btn btn-sm btn-outline-success btn-round my-2 px-3 fw-bold">
                    <i class="fas fa-folder-open me-1"></i> Explorar Archivo
                  </span>
                  <div class="text-muted small">Formatos permitidos: .xlsx, .xls, .csv (Máx. 10 MB)</div>
                </div>

                <div id="mass_upload_preview" class="d-none align-items-center justify-content-between p-3 bg-light rounded-3 border">
                  <div class="d-flex align-items-center text-start">
                    <i class="fas fa-file-excel text-success fa-2x me-3"></i>
                    <div>
                      <h6 class="fw-bold text-dark mb-0 text-truncate" id="mass_upload_file_name" style="max-width: 380px;">archivo.xlsx</h6>
                      <span class="text-muted small" id="mass_upload_file_size">0 KB</span>
                    </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-outline-danger btn-round ms-2" id="btn_remove_mass_upload_file" title="Quitar archivo">
                    <i class="fas fa-times me-1"></i> Quitar
                  </button>
                </div>
              </label>

              <!-- Estado de procesamiento -->
              <div id="mass_upload_loading" class="d-none text-center py-3">
                <div class="spinner-border text-success mb-2" role="status">
                  <span class="visually-hidden">Procesando...</span>
                </div>
                <p class="fw-bold text-dark mb-0">Procesando archivo e ingresando productos a bodega principal...</p>
                <small class="text-muted">Esto puede tardar unos segundos según la cantidad de registros.</small>
              </div>

            </div>
          </div>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-success btn-round shadow-sm fw-bold px-4" id="btn_process_mass_upload" disabled>
            <i class="fas fa-upload me-1"></i> Procesar Cargue Masivo
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Transferencia entre Bodegas -->
  <div class="modal fade" id="modal_transfer_warehouse" tabindex="-1" aria-labelledby="modalTransferWarehouseLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 bg-warning text-dark">
          <h5 class="modal-title fw-bold" id="modalTransferWarehouseLabel"><i class="fas fa-exchange-alt me-2"></i>Transferencia de Stock entre Bodegas</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4">
          <form id="form_transfer_warehouse">
            <?= csrf_field() ?>
            <input type="hidden" id="transfer_origin_warehouse_id" name="id_warehouse_send" value="">

            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">Bodega Origen</label>
                <div class="p-2 border rounded bg-light d-flex align-items-center">
                  <i class="fas fa-warehouse text-primary me-2 fa-lg"></i>
                  <span id="transfer_origin_warehouse_name" class="fw-bold text-dark">Cargando...</span>
                </div>
              </div>
              <div class="col-md-6">
                <label for="transfer_dest_warehouse" class="form-label fw-bold">Bodega Destino *</label>
                <select class="form-select" id="transfer_dest_warehouse" name="id_warehouse_receives" required>
                  <option value="">Seleccione bodega destino...</option>
                </select>
              </div>
            </div>

            <hr class="my-3">

            <div class="mb-3">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label fw-bold mb-0"><i class="fas fa-boxes text-secondary me-1"></i> Artículos a Transferir</label>
                <button type="button" class="btn btn-outline-primary btn-sm btn-round" id="btn_add_transfer_line">
                  <i class="fas fa-plus me-1"></i> Agregar Línea
                </button>
              </div>

              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" id="tbl_transfer_lines">
                  <thead class="bg-light">
                    <tr>
                      <th>Artículo / Lote Disponible</th>
                      <th style="width: 140px;" class="text-center">Stock Disp.</th>
                      <th style="width: 150px;" class="text-center">Cantidad a Enviar</th>
                      <th style="width: 50px;" class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody id="transfer_lines_tbody">
                    <!-- Filas dinámicas -->
                  </tbody>
                </table>
              </div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i> Solo se pueden transferir artículos con saldo disponible en la bodega origen.</small>
            </div>
          </form>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-warning btn-round shadow-sm fw-bold text-dark" id="btn_submit_transfer">
            <i class="fas fa-exchange-alt me-1"></i> Confirmar Transferencia
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Ajuste de Inventario en Bodega -->
  <div class="modal fade" id="modal_adjust_warehouse_stock" tabindex="-1" aria-labelledby="modalAdjustWarehouseStockLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg">
        <div class="modal-header border-0 bg-dark text-white">
          <h5 class="modal-title fw-bold" id="modalAdjustWarehouseStockLabel">
            <i class="fas fa-sliders-h me-2"></i>Ajuste de Inventario en Bodega
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-4" style="min-height: 420px;">
          <form id="form_adjust_warehouse_stock">
            <?= csrf_field() ?>
            <input type="hidden" id="adjust_warehouse_id" name="id_warehouse" value="">

            <!-- Info de bodega y descripción obligatoria -->
            <div class="row mb-3">
              <div class="col-md-5">
                <label class="form-label fw-bold"><i class="fas fa-warehouse text-primary me-1"></i> Bodega a Ajustar</label>
                <div class="p-2 border rounded bg-light d-flex align-items-center">
                  <i class="fas fa-warehouse text-primary me-2 fa-lg"></i>
                  <span id="adjust_warehouse_name" class="fw-bold text-dark">Cargando...</span>
                </div>
              </div>
              <div class="col-md-7">
                <label for="adjust_observation" class="form-label fw-bold">
                  <i class="fas fa-comment-alt text-warning me-1"></i> Descripción / Justificación del Ajuste *
                </label>
                <input type="text" class="form-control" id="adjust_observation" name="observacion" placeholder="Indique el motivo o justificación del ajuste de inventario..." required maxlength="250">
              </div>
            </div>

            <hr class="my-3">

            <!-- Artículos dinámicos para ajustar saldos -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-list-check text-primary me-2"></i>Artículos para Ajuste</h6>
                <small class="text-muted">Seleccione los artículos existentes en la bodega. Ingrese un valor positivo para <strong>aumentar</strong> (ej: <code>5</code>) o negativo para <strong>disminuir</strong> (ej: <code>-3</code>).</small>
              </div>
              <button type="button" class="btn btn-outline-dark btn-round px-3 fw-bold" id="btn_add_adjust_warehouse_line">
                <i class="fas fa-plus me-1"></i> Agregar Línea
              </button>
            </div>

            <div class="table-responsive">
              <table class="table table-bordered table-hover align-middle mb-0" id="tbl_adjust_warehouse_items">
                <thead class="bg-light">
                  <tr>
                    <th><i class="fas fa-box text-primary me-1"></i> Artículo / Lote en Bodega *</th>
                    <th style="width: 140px;" class="text-center"><i class="fas fa-cubes text-muted me-1"></i> Saldo Actual</th>
                    <th style="width: 180px;" class="text-center"><i class="fas fa-exchange-alt text-muted me-1"></i> Cantidad (+ / -) *</th>
                    <th style="width: 140px;" class="text-center"><i class="fas fa-calculator text-muted me-1"></i> Nuevo Saldo</th>
                    <th style="width: 60px;" class="text-center"><i class="fas fa-cog text-muted"></i></th>
                  </tr>
                </thead>
                <tbody id="adjust_warehouse_items_body">
                  <!-- Filas dinámicas -->
                </tbody>
              </table>
            </div>

          </form>
        </div>
        <div class="modal-footer border-0 bg-light">
          <button type="button" class="btn btn-secondary btn-round btn-border" data-bs-dismiss="modal">Cancelar</button>
          <button type="button" class="btn btn-dark btn-round shadow-sm fw-bold text-white" id="btn_save_adjust_stock">
            <i class="fas fa-save me-1"></i> Guardar Ajuste
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- ======================================================== -->
  <!--   SCRIPTS                                               -->
  <!-- ======================================================== -->

  <!--   Core JS Files   -->
  <script src="<?= base_url('public/assets/js/core/jquery-3.7.1.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/core/popper.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/core/bootstrap.min.js') ?>"></script>

  <!-- jQuery Scrollbar -->
  <script src="<?= base_url('public/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') ?>"></script>

  <!-- Chart JS -->
  <script src="<?= base_url('public/assets/js/plugin/chart.js/chart.min.js') ?>"></script>

  <!-- jQuery Sparkline -->
  <script src="<?= base_url('public/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') ?>"></script>

  <!-- Chart Circle -->
  <script src="<?= base_url('public/assets/js/plugin/chart-circle/circles.min.js') ?>"></script>

  <!-- Datatables -->
  <script src="<?= base_url('public/assets/js/plugin/datatables/datatables.min.js') ?>"></script>

  <!-- Bootstrap Notify -->
  <script src="<?= base_url('public/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') ?>"></script>

  <!-- jQuery Vector Maps -->
  <script src="<?= base_url('public/assets/js/plugin/jsvectormap/jsvectormap.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/plugin/jsvectormap/world.js') ?>"></script>

  <!-- Google Maps Plugin -->
  <script src="<?= base_url('public/assets/js/plugin/gmaps/gmaps.js') ?>"></script>

  <!-- Sweet Alert -->
  <script src="<?= base_url('public/assets/js/plugin/sweetalert/sweetalert.min.js') ?>"></script>

  <!-- Kaiadmin JS -->
  <script src="<?= base_url('public/assets/js/kaiadmin.min.js') ?>"></script>

  <!-- Main -->
  <script>
    window.userCredentials = <?= json_encode($credentials ?? []) ?>;
    window.userId = <?= json_encode((int)($user_id ?? 0)) ?>;
    window.BASE_URL = '<?= base_url() ?>';
  </script>
  <script src="<?= base_url('public/assets/js/module/dashboard/global.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/global.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/init.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/init.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/events.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/events.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/utils.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/utils.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/counterparty.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/counterparty.js') ?>"></script>
</body>

</html>