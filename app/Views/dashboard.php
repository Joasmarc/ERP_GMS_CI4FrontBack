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
  <link rel="stylesheet" href="<?= base_url('public/assets/css/module/main.css') ?>" />
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
                      <button class="btn btn-round btn-primary" onclick="$('#remisiones_list_view').addClass('d-none'); $('#remisiones_create_view').removeClass('d-none');">
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
                    <button class="btn btn-sm btn-secondary btn-round" onclick="$('#remisiones_create_view').addClass('d-none'); $('#remisiones_list_view').removeClass('d-none');">
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
                  <button class="btn btn-round btn-secondary" onclick="document.getElementById('form_remision_create').reset()">
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
          <!--   PROVEEDORES Y CLIENTES (CONTRAPARTE) SCREENS           -->
          <!-- ======================================================== -->

          <!-- Ini Proveedor Screen -->
          <div id="cont_proveedor" class="row d-none">
            <div class="col-md-12" id="proveedor_list_view">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Listado de Proveedores</h4>
                  <div class="card-tools">
                    <button class="btn btn-round btn-secondary me-2" onclick="openUploadPolicyModal()">
                      <i class="fas fa-file-upload"></i> Subir Política PDF
                    </button>
                    <button class="btn btn-round btn-info me-2" onclick="openShareModal('proveedor')">
                      <i class="fas fa-share-alt"></i> Compartir Formulario
                    </button>
                    <button class="btn btn-round btn-primary" onclick="openCreateForm('proveedor')">
                      <i class="fa fa-plus"></i> Registrar Proveedor
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="tbl_list_proveedores" class="display table table-striped table-hover w-100">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Razón Social / Nombre</th>
                          <th>NIT / Cédula</th>
                          <th>Tipo Persona</th>
                          <th>Email Principal</th>
                          <th>Teléfono Principal</th>
                          <th>Régimen</th>
                          <th class="text-center">Acciones</th>
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
          <!-- End Proveedor Screen -->

          <!-- Ini Cliente Screen -->
          <div id="cont_cliente" class="row d-none">
            <div class="col-md-12" id="cliente_list_view">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Listado de Clientes</h4>
                  <div class="card-tools">
                    <button class="btn btn-round btn-secondary me-2" onclick="openUploadPolicyModal()">
                      <i class="fas fa-file-upload"></i> Subir Política PDF
                    </button>
                    <button class="btn btn-round btn-info me-2" onclick="openShareModal('cliente')">
                      <i class="fas fa-share-alt"></i> Compartir Formulario
                    </button>
                    <button class="btn btn-round btn-primary" onclick="openCreateForm('cliente')">
                      <i class="fa fa-plus"></i> Registrar Cliente
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="tbl_list_clientes" class="display table table-striped table-hover w-100">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Razón Social / Nombre</th>
                          <th>NIT / Cédula</th>
                          <th>Tipo Persona</th>
                          <th>Email Principal</th>
                          <th>Teléfono Principal</th>
                          <th>Régimen</th>
                          <th class="text-center">Acciones</th>
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
          <div class="col-md-12 d-none" id="counterparty_create_view">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0" id="counterparty_form_title"><i class="fas fa-user-plus"></i> Registrar Contraparte</h4>
                <button class="btn btn-sm btn-secondary btn-round" id="btn_back_to_counterparty_list">
                  <i class="fas fa-arrow-left"></i> Volver
                </button>
              </div>
              <div class="card-body">
                <form id="form_counterparty_create" method="POST">
                  <?= csrf_field() ?>
                  <input type="hidden" id="form_contraparte_type" name="contraparte_type" value="proveedor">
                  <input type="hidden" id="form_counterparty_id" name="id" value="">

                  <style>
                    .form-group-default label, label.form-label, label {
                      font-weight: 700 !important; /* Force all label titles to be bold */
                      color: #111827 !important; /* Make labels darker (almost black) for readability */
                    }
                  </style>

                  <!-- Selección del Tipo de Persona -->
                  <div class="row mb-4">
                    <div class="col-md-5">
                      <div>
                        <label class="d-block mb-2 text-muted text-uppercase fw-bold" style="font-size: 11px;">Tipo de Persona</label>
                        <div class="btn-group w-100" role="group">
                          <input type="radio" class="btn-check" name="person_type" id="form_person_type_juridica" value="juridica" checked autocomplete="off">
                          <label class="btn btn-outline-primary fw-bold" for="form_person_type_juridica">Persona Jurídica</label>

                          <input type="radio" class="btn-check" name="person_type" id="form_person_type_natural" value="natural" autocomplete="off">
                          <label class="btn btn-outline-primary fw-bold" for="form_person_type_natural">Persona Natural</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ========================================== -->
                  <!-- SECCIÓN DATOS JURÍDICOS                   -->
                  <!-- ========================================== -->
                  <div id="sec_juridica">
                    <h4 class="fw-bold text-primary mb-3"><i class="fas fa-building"></i> Datos de Persona Jurídica</h4>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label>Nombre del Proveedor (Razón Social o Nombre Completo) *</label>
                          <input type="text" class="form-control" name="nombre_completo" id="jur_nombre_completo" required placeholder="Nombre o Razón social">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Tipo de Identificación *</label>
                          <select class="form-select" name="tipo_identificacion" id="jur_tipo_identificacion">
                            <option value="NIT">NIT</option>
                            <option value="Cédula">Cédula</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Cédula Extranjería">Cédula Extranjería</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Número de Identificación *</label>
                          <input type="text" class="form-control" name="numero_identificacion" id="jur_numero_identificacion" required placeholder="Ej: 901234567-8">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>País de Identificación</label>
                          <input type="text" class="form-control" name="pais_identificacion" placeholder="Ej: Colombia">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Fecha Expedición Identificación</label>
                          <input type="date" class="form-control" name="fecha_expedicion_identificacion">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Fecha Vencimiento Identificación</label>
                          <input type="date" class="form-control" name="fecha_vencimiento_identificacion">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Régimen Tributario</label>
                          <input type="text" class="form-control" name="regimen_tributario" placeholder="Ej: Común, Simplificado">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>País de Residencia</label>
                          <input type="text" class="form-control" name="pais_residencia" placeholder="Ej: Colombia">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>País de Origen</label>
                          <input type="text" class="form-control" name="pais_origen" placeholder="Ej: Colombia">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Dirección Completa</label>
                          <input type="text" class="form-control" name="direccion_completa" placeholder="Dirección de la empresa">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Email Principal *</label>
                          <input type="email" class="form-control" name="email_principal" id="jur_email_principal" required placeholder="correo@empresa.com">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Teléfono Principal *</label>
                          <input type="text" class="form-control" name="telefono_principal" id="jur_telefono_principal" required placeholder="Teléfono de contacto">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Teléfono Secundario</label>
                          <input type="text" class="form-control" name="telefono_secundario" placeholder="Teléfono alternativo">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label>Página Web</label>
                          <input type="url" class="form-control" name="pagina_web" placeholder="https://ejemplo.com">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label>LinkedIn / Redes Sociales</label>
                          <input type="text" class="form-control" name="linkedin_redes" placeholder="Perfil o enlace">
                        </div>
                      </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3 text-secondary">Cumplimiento y screening</h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Estado Tributario</label>
                          <select class="form-select" name="estado_tributario">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                            <option value="Verificado">Verificado</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Tiene antecedentes judiciales?</label>
                          <select class="form-select" name="antecedentes_judiciales">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Tiene antecedentes disciplinarios?</label>
                          <select class="form-select" name="antecedentes_disciplinarios">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Está reportado en listas OFAC?</label>
                          <select class="form-select" name="reportado_ofac">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                            <option value="Pendiente">Pendiente</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Está reportado en listas ONU?</label>
                          <select class="form-select" name="reportado_onu">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                            <option value="Pendiente">Pendiente</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Está reportado en listas locales?</label>
                          <select class="form-select" name="reportado_locales">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                            <option value="Pendiente">Pendiente</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Fecha último screening realizado</label>
                          <input type="datetime-local" class="form-control" name="fecha_ultimo_screening">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group form-group-default">
                          <label>Resultado último screening</label>
                          <input type="text" class="form-control" name="resultado_ultimo_screening" placeholder="Descripción de los hallazgos">
                        </div>
                      </div>
                    </div>

                    <h5 class="fw-bold mt-4 mb-3 text-secondary">Clasificación de Riesgos y Actividad</h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Sector Económico (Clasificación CIIU)</label>
                          <input type="text" class="form-control" name="sector_economico" placeholder="Ej: 4646 - Comercio de productos farmacéuticos">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Subsector Específico</label>
                          <input type="text" class="form-control" name="subsector_especifico" placeholder="Ej: Insumos hospitalarios">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Tipo de Producto/Servicio suministrado</label>
                          <input type="text" class="form-control" name="tipo_producto_servicio" placeholder="Ej: Jeringas, Gasas, Equipos">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Nivel de Riesgo Inicial</label>
                          <select class="form-select" name="nivel_riesgo_inicial">
                            <option value="Bajo">Bajo</option>
                            <option value="Medio">Medio</option>
                            <option value="Alto">Alto</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-9">
                        <div class="form-group form-group-default">
                          <label>Justificación de Riesgo</label>
                          <input type="text" class="form-control" name="justificacion_riesgo" placeholder="Justificación del nivel de riesgo asignado">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>¿Es PEP?</label>
                          <select class="form-select" name="es_pep">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>¿Tiene conexión con PEP?</label>
                          <select class="form-select" name="conexion_pep">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>¿Opera en país de alto riesgo?</label>
                          <select class="form-select" name="opera_pais_alto_riesgo">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Países donde opera</label>
                          <input type="text" class="form-control" name="paises_opera" placeholder="Ej: Colombia, Panamá">
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ========================================== -->
                  <!-- SECCIÓN DATOS NATURALES                   -->
                  <!-- ========================================== -->
                  <div id="sec_natural" class="d-none">
                    <h4 class="fw-bold text-primary mb-3"><i class="fas fa-user"></i> Datos de Persona Natural</h4>
                    
                    <h5 class="fw-bold text-secondary mb-3">Identificación y Datos Personales</h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Tipo de Documento *</label>
                          <select class="form-select" name="tipo_identificacion" id="nat_tipo_identificacion">
                            <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                            <option value="Cédula de extranjería">Cédula de extranjería</option>
                            <option value="Pasaporte">Pasaporte</option>
                            <option value="Permiso de residencia">Permiso de residencia</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group form-group-default">
                          <label>Número de Documento *</label>
                          <input type="text" class="form-control" name="numero_identificacion" id="nat_numero_identificacion" placeholder="Número de documento">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Dígito Verificador</label>
                          <input type="text" class="form-control" name="digito_verificador" placeholder="Opcional">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>País Emisor del Documento</label>
                          <input type="text" class="form-control" name="pais_identificacion" placeholder="Ej: Colombia">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Fecha de Expedición</label>
                          <input type="date" class="form-control" name="fecha_expedicion_identificacion">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Fecha de Vencimiento</label>
                          <input type="date" class="form-control" name="fecha_vencimiento_identificacion">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Departamento Expedición</label>
                          <input type="text" class="form-control" name="departamento_expedicion" placeholder="Depto expedición">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Ciudad Expedición</label>
                          <input type="text" class="form-control" name="ciudad_expedicion" placeholder="Ciudad expedición">
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group form-group-default">
                          <label>Nombre Completo *</label>
                          <input type="text" class="form-control" name="nombre_completo" id="nat_nombre_completo" placeholder="Nombres">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group form-group-default">
                          <label>Primer Apellido *</label>
                          <input type="text" class="form-control" name="primer_apellido" id="nat_primer_apellido" placeholder="Primer apellido">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group form-group-default">
                          <label>Segundo Apellido</label>
                          <input type="text" class="form-control" name="segundo_apellido" placeholder="Segundo apellido">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Fecha de Nacimiento *</label>
                          <input type="date" class="form-control" name="fecha_nacimiento" id="nat_fecha_nacimiento">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group form-group-default">
                          <label>Depto Nacimiento</label>
                          <select class="form-select" id="form_dept_nacimiento" name="departamento_nacimiento">
                            <option value="">Seleccione Depto</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Lugar de Nacimiento (Ciudad)</label>
                          <select class="form-select" id="form_city_nacimiento" name="lugar_nacimiento_ciudad">
                            <option value="">Seleccione Ciudad</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group form-group-default">
                          <label>País de Nacimiento</label>
                          <input type="text" class="form-control" name="pais_nacimiento" placeholder="País">
                        </div>
                      </div>
                      <div class="col-md-2">
                        <div class="form-group form-group-default">
                          <label>Sexo</label>
                          <select class="form-select" name="sexo">
                            <option value="">Seleccione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Estado Civil</label>
                          <select class="form-select" name="estado_civil">
                            <option value="">Seleccione</option>
                            <option value="Soltero">Soltero</option>
                            <option value="Casado">Casado</option>
                            <option value="Unión libre">Unión libre</option>
                            <option value="Separado">Separado</option>
                            <option value="Divorciado">Divorciado</option>
                            <option value="Viudo">Viudo</option>
                            <option value="Otro">Otro</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <h5 class="fw-bold text-secondary mt-4 mb-3">Ubicación y Datos de Residencia</h5>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>País de Residencia *</label>
                          <input type="text" class="form-control" name="pais_residencia" id="nat_pais_residencia" placeholder="Ej: Colombia">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Departamento de Residencia</label>
                          <select class="form-select" id="form_dept_residencia" name="departamento_residencia">
                            <option value="">Seleccione Departamento</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Ciudad de Residencia</label>
                          <select class="form-select" id="form_city_residencia" name="ciudad_residencia">
                            <option value="">Seleccione Ciudad</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Dirección Completa *</label>
                          <input type="text" class="form-control" name="direccion_completa" id="nat_direccion_completa" placeholder="Dirección completa">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Número Exterior</label>
                          <input type="text" class="form-control" name="numero_exterior" placeholder="Ej: 45A-23">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Número Interior (Opcional)</label>
                          <input type="text" class="form-control" name="numero_interior" placeholder="Ej: Apto 302">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Apartamento/Complemento</label>
                          <input type="text" class="form-control" name="apartamento_complemento" placeholder="Ej: Torre B">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Código Postal</label>
                          <input type="text" class="form-control" name="codigo_postal" placeholder="Ej: 050012">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Teléfono Residencial *</label>
                          <input type="text" class="form-control" name="telefono_principal" id="nat_telefono_principal" placeholder="Teléfono de casa/celular">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Tiempo Residiendo (meses)</label>
                          <input type="number" class="form-control" name="tiempo_residencia_meses" placeholder="Cantidad de meses">
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Dirección Anterior</label>
                          <input type="text" class="form-control" name="direccion_anterior" placeholder="Dirección anterior">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Tiempo Dirección Anterior</label>
                          <input type="text" class="form-control" name="tiempo_direccion_anterior" placeholder="Ej: 12 meses">
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group form-group-default">
                          <label>Dirección Laboral</label>
                          <input type="text" class="form-control" name="direccion_laboral" placeholder="Dirección de la empresa donde labora">
                        </div>
                      </div>
                    </div>

                    <h5 class="fw-bold text-secondary mt-4 mb-3">Datos Laborales y Profesionales</h5>
                    <div class="row">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Actividad Principal / Ocupación *</label>
                          <input type="text" class="form-control" name="actividad_principal" id="nat_actividad_principal" placeholder="Ej: Empleado, Independiente">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Profesión *</label>
                          <input type="text" class="form-control" name="profesion" id="nat_profesion" placeholder="Ej: Médico, Ingeniero">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Área de Especialización *</label>
                          <input type="text" class="form-control" name="area_especializacion" id="nat_area_especializacion" placeholder="Ej: Cardiología, Logística">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Sector Económico (CIIU) *</label>
                          <input type="text" class="form-control" name="sector_economico" id="nat_sector_economico" placeholder="Código o Nombre">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Años de Experiencia *</label>
                          <input type="number" class="form-control" name="anos_experiencia" id="nat_anos_experiencia" placeholder="Años de experiencia">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Certificaciones Profesionales</label>
                          <input type="text" class="form-control" name="certificaciones_profesionales" placeholder="Ej: ISO 9001, PMP">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Membresías Profesionales</label>
                          <input type="text" class="form-control" name="membresias_profesionales" placeholder="Ej: Colegio de Médicos">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group form-group-default">
                          <label>Licencias Profesionales</label>
                          <input type="text" class="form-control" name="licencias_profesionales" placeholder="Ej: Licencia Médica 1234">
                        </div>
                      </div>
                    </div>

                    <h5 class="fw-bold text-danger mt-4 mb-3"><i class="fas fa-shield-alt"></i> Cumplimiento Legal y PEP (Políticamente Expuesto)</h5>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Es Persona Expuesta Políticamente (PEP)? *</label>
                          <select class="form-select" name="es_pep" id="nat_es_pep">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group form-group-default">
                          <label>Tipo de Vínculo PEP (Si aplica)</label>
                          <select class="form-select" name="tipo_pep">
                            <option value="">Ninguno</option>
                            <option value="Funcionario público actual">Funcionario público actual</option>
                            <option value="Funcionario público ex (últimos 5 años)">Funcionario público ex (últimos 5 años)</option>
                            <option value="Familia de PEP">Familia de PEP</option>
                            <option value="Allegado de PEP">Allegado de PEP</option>
                            <option value="Accionista/Directivo de empresa vinculada a PEP">Accionista/Directivo de empresa vinculada a PEP</option>
                          </select>
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-12">
                        <div class="form-group form-group-default">
                          <label>Descripción de Vínculo PEP (Cargo, Entidad, Período)</label>
                          <input type="text" class="form-control" name="descripcion_vinculo_pep" placeholder="Ej: Cargo actual/anterior, Entidad, Período de desempeño">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Vínculos personajes de interés criminal?</label>
                          <select class="form-select" name="vinculos_criminales">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Tiene antecedentes penales?</label>
                          <select class="form-select" name="antecedentes_judiciales">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>Tipos de Delito (Si aplica)</label>
                          <input type="text" class="form-control" name="antecedentes_penales" placeholder="Ej: Delitos financieros, Lavado de activos, etc.">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-12">
                        <div class="form-group form-group-default">
                          <label>Descripción de antecedentes (Tipo de delito, Fecha, Sentencia/Estado)</label>
                          <input type="text" class="form-control" name="descripcion_antecedentes" placeholder="Descripción detallada de antecedentes penales">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Sancionado disciplinariamente?</label>
                          <select class="form-select" name="sancionado_disciplinariamente">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group form-group-default">
                          <label>Descripción de Sanciones Disciplinarias</label>
                          <input type="text" class="form-control" name="descripcion_sanciones" placeholder="Detalle de sanciones disciplinarias">
                        </div>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-md-4">
                        <div class="form-group form-group-default">
                          <label>¿Opera en país de alto riesgo GAFILAT?</label>
                          <select class="form-select" name="opera_pais_alto_riesgo">
                            <option value="No">No</option>
                            <option value="Sí">Sí</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="form-group form-group-default">
                          <label>Justificación de exposición geográfica</label>
                          <input type="text" class="form-control" name="justificacion_exposicion_geografica" placeholder="Justificación de la exposición geográfica">
                        </div>
                      </div>
                    </div>
                  </div>

                </form>
              </div>
              <div class="card-action text-end">
                <button class="btn btn-round btn-secondary" onclick="document.getElementById('form_counterparty_create').reset()">
                  <i class="fas fa-redo"></i> Limpiar
                </button>
                <button class="btn btn-round btn-success" id="btn_save_counterparty">
                  <i class="fas fa-save"></i> Guardar Registro
                </button>
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
          <button type="button" class="btn btn-primary btn-round shadow-sm" onclick="saveRecommendation()">Compartir con el equipo</button>
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
        <div class="modal-header">
          <h5 class="modal-title">Vista de Remisión</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
    window.BASE_URL = '<?= base_url() ?>';
  </script>
  <script src="<?= base_url('public/assets/js/module/dashboard/global.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/global.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/init.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/init.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/events.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/events.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/utils.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/utils.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/module/dashboard/counterparty.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/dashboard/counterparty.js') ?>"></script>


</body>

</html>