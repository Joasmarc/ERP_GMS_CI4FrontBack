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
          </ul>
        </div>
      </div>
    </div>
    <!-- End Sidebar -->

    <!-- Ini Content -->
    <div class="main-panel">
      <!-- Header -->
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

      <!-- Body -->
      <div class="container">
        <div class="page-inner">
          <div class="page-header">
            <h4 class="page-title">Dashboard</h4>
            <ul class="breadcrumbs">
              <li class="nav-home">
                <a href="#">
                  <i class="icon-home"></i>
                </a>
              </li>
              <li class="separator">
                <i class="icon-arrow-right"></i>
              </li>
              <li class="nav-item">
                <div id="nav_first">Inicio</div>
              </li>
              <li class="separator">
                <i class="icon-arrow-right"></i>
              </li>
              <li class="nav-item">
                <a id="nav_second">Dashboard</a>
              </li>
            </ul>
          </div>

          <!-- Ini Screens -->

          <!-- Loading Screen -->
          <div id='cont_loading' class="row d-none">
            <div class="col-md-12 d-flex justify-content-center">
              <div class="loader_spinner"></div>
            </div>
          </div>

          <!-- Dashboard Screen -->
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

          <!-- Product Create Screen -->
          <div id="cont_product_create" class="row d-none">
            <div class="col-md-12">
              <div class="card">
                <!-- 1.1 Encabezado de la tarjeta -->
                <div class="card-header">
                  <div class="card-title">
                    <i class="fas fa-syringe"></i> Crear Implemento Médico
                  </div>
                </div>

                <!-- 1.2 Cuerpo de la tarjeta -->
                <div class="card-body">
                  <!-- 1.3 Formulario para crear implemento -->
                  <form action="#" method="POST">
                    <div class="row">
                      <!-- 2.0 Nombre de Producto -->
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="nombreProducto">Nombre de Producto</label>
                          <input
                            type="text"
                            class="form-control"
                            id="nombreProducto"
                            name="nombre_producto"
                            placeholder="Ej: Jeringa de 10ml"
                            required>
                          <small class="form-text text-muted">Ingrese el nombre del implemento médico</small>
                        </div>
                      </div>

                      <!-- 2.1 Capacidad -->
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="capacidad">Capacidad</label>
                          <input
                            type="text"
                            class="form-control"
                            id="capacidad"
                            name="capacidad"
                            placeholder="Ej: 10ml, 500cc, 1L"
                            required>
                          <small class="form-text text-muted">Especifique la capacidad o volumen</small>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <!-- 2.2 Dimensión -->
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="dimension">Dimensión</label>
                          <input
                            type="text"
                            class="form-control"
                            id="dimension"
                            name="dimension"
                            placeholder="Ej: 25cm x 15cm x 10cm"
                            required>
                          <small class="form-text text-muted">Ingrese las dimensiones del producto</small>
                        </div>
                      </div>

                      <!-- 2.3 Modelo -->
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="modelo">Modelo</label>
                          <input
                            type="text"
                            class="form-control"
                            id="modelo"
                            name="modelo"
                            placeholder="Ej: JRS-100, MED-2024"
                            required>
                          <small class="form-text text-muted">Código o modelo del producto</small>
                        </div>
                      </div>
                    </div>

                    <!-- 2.4 Descripción adicional -->
                    <div class="form-group">
                      <label for="descripcion">Descripción</label>
                      <textarea
                        class="form-control"
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                        placeholder="Descripción detallada del implemento médico..."></textarea>
                    </div>

                    <!-- 2.5 Cantidad -->
                    <div class="form-group">
                      <label for="cantidad">Cantidad en Inventario</label>
                      <input
                        type="number"
                        class="form-control"
                        id="cantidad"
                        name="cantidad"
                        placeholder="0"
                        min="0">
                    </div>
                  </form>
                </div>

                <!-- 1.4 Botones de acción -->
                <div class="card-action">
                  <button class="btn btn-round btn-success" onclick="guardarImplemento()">
                    <i class="fas fa-save"></i> Guardar Implemento
                  </button>
                  <button class="btn btn-round btn-secondary" onclick="limpiarFormulario()">
                    <i class="fas fa-redo"></i> Limpiar
                  </button>
                  <button class="btn btn-round btn-danger" onclick="cancelar()">
                    <i class="fas fa-times"></i> Cancelar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Product List Screen -->
          <div id="cont_product_list" class="row d-none">

            <div class="col-md-12" id="wrapper_catalog">
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

            <div class="row d-none" id="cont_detalle_producto">

              <div class="col-md-12 d-flex align-items-center mb-4">
                <button class="btn btn-secondary btn-round me-3" id="btn_back_to_catalog">
                  <i class="fas fa-arrow-left"></i> Volver al Catálogo
                </button>
                <h1 class="m-0 flex-grow-1 text-center" style="margin-left: -150px !important;">Detalles</h1>
              </div>

              <div class="col-md-12 d-none">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Opciones</h4>
                  </div>
                  <div class="card-body">

                    <button class="btn btn-round btn-success en-desarrollo">
                      <span class="btn-label">
                        <i class="fa fa-link"></i>
                      </span>
                      Compartir
                    </button>

                    <button id="btn_product_edit" class="btn btn-round btn-secondary en-desarrollo">
                      <span class="btn-label">
                        <i class="fa fa-edit"></i>
                      </span>
                      Modificar
                    </button>

                    <button class="btn btn-round btn-info en-desarrollo">
                      <span class="btn-label">
                        <i class="fas fa-sticky-note"></i>
                      </span>
                      Agregar Nota
                    </button>

                    <button class="btn btn-round btn-danger en-desarrollo">
                      <span class="btn-label">
                        <i class="fas fa-ban"></i>
                      </span>
                      Inactivar
                    </button>

                  </div>
                </div>
              </div>

              <div class="col-md-4">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">General</h4>
                  </div>
                  <div class="card-body">
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

              <div class="col-md-7">
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

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Imagenes</h4>
                    </div>
                    <div class="card-body">
                      <div id="cont_imagenes_producto"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Videos</h4>
                    </div>
                    <div class="card-body">
                      <div id="cont_videos_producto"></div>
                    </div>
                  </div>
                </div>
              </div>

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

          </div>

          <!-- Client Create Screen -->
          <div id="cont_client_create" class="row d-none">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <div class="card-title">
                    <i class="fas fa-user-plus"></i> Crear Cliente
                  </div>
                </div>
                <div class="card-body">
                  <form action="#" method="POST" id="form_client_create">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="nombreCliente">Nombre / Razón Social</label>
                          <input type="text" class="form-control" id="nombreCliente" name="nombre_cliente" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="tipoDocumento">Tipo de Documento</label>
                          <select class="form-select" id="tipoDocumento" name="tipo_documento">
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">Cédula de Extranjería</option>
                            <option value="PAS">Pasaporte</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="numeroDocumento">Número de Documento</label>
                          <input type="text" class="form-control" id="numeroDocumento" name="numero_documento" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="telefonoCliente">Teléfono / Celular</label>
                          <input type="text" class="form-control" id="telefonoCliente" name="telefono_cliente" required>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="correoCliente">Correo Electrónico</label>
                          <input type="email" class="form-control" id="correoCliente" name="correo_cliente" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group form-group-default">
                          <label for="direccionCliente">Dirección y Ciudad</label>
                          <input type="text" class="form-control" id="direccionCliente" name="direccion_cliente" required>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="card-action">
                  <button class="btn btn-round btn-success" onclick="guardarCliente()">
                    <i class="fas fa-save"></i> Guardar Cliente
                  </button>
                  <button class="btn btn-round btn-secondary" onclick="document.getElementById('form_client_create').reset()">
                    <i class="fas fa-redo"></i> Limpiar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Client List Screen -->
          <div id="cont_client_list" class="row d-none">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Listado de Clientes</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table id="tbl_list_clientes" class="display table table-striped table-hover">
                      <thead>
                        <tr>
                          <th>ID</th>
                          <th>Nombre / Razón Social</th>
                          <th>Tipo Doc</th>
                          <th>Número Doc</th>
                          <th>Teléfono</th>
                          <th>Correo</th>
                          <th>Dirección</th>
                          <th class="text-center">Abrir</th>
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

          <!-- Client Details Screen -->
          <div id="cont_detalle_cliente" class="row d-none">
            <div class="col-md-12 text-center">
              <h1>Detalles del Cliente</h1>
              <h3 id="detalle_cliente_nombre" class="text-primary"></h3>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Añadir Evaluación / Comentario</h4>
                </div>
                <div class="card-body">
                  <form id="form_add_comment">
                    <input type="hidden" id="detalle_client_id" name="client_id">
                    <div class="form-group">
                      <label>Comentario de Progreso</label>
                      <textarea class="form-control" id="detalle_comment" name="comment" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-round btn-primary mt-3">Guardar Progreso</button>
                  </form>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h4 class="card-title">Historial de Progreso</h4>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-bordered" id="list_client_comments">
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <!-- Remisiones Screen -->
          <div id="cont_remisiones" class="row d-none">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h4 class="card-title mb-0">Remisiones</h4>
                  <div class="card-tools">
                    <?php if (isset($credentials[11]) && $credentials[11] === '1'): ?>
                      <button class="btn btn-round btn-primary">
                        <span class="btn-label">
                          <i class="fa fa-plus"></i>
                        </span>
                        Crear
                      </button>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="card-body">
                  <!-- Contenido de remisiones -->
                </div>
              </div>
            </div>
          </div>

          <!-- Activity Center View -->
          <style>
            .timeline-modern {
              position: relative;
              padding-left: 2rem;
              margin: 1.5rem 0;
            }

            .timeline-modern::before {
              content: '';
              position: absolute;
              top: 0;
              bottom: 0;
              left: 0.75rem;
              width: 3px;
              background: linear-gradient(180deg, #e9ecef 0%, #e9ecef 80%, rgba(233, 236, 239, 0) 100%);
            }

            .timeline-modern-item {
              position: relative;
              margin-bottom: 2rem;
              opacity: 0;
              transform: translateY(20px);
              animation: fadeInUp 0.5s ease forwards;
            }

            @keyframes fadeInUp {
              to {
                opacity: 1;
                transform: translateY(0);
              }
            }

            .timeline-modern-icon {
              position: absolute;
              left: -2.6rem;
              top: 0.2rem;
              width: 40px;
              height: 40px;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              color: white;
              box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
              font-size: 1.2rem;
              z-index: 2;
              border: 3px solid #fff;
            }

            .timeline-modern-content {
              background: #fff;
              border-radius: 0.75rem;
              padding: 1.5rem;
              box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
              border: 1px solid rgba(0, 0, 0, 0.04);
              transition: transform 0.3s ease, box-shadow 0.3s ease;
              position: relative;
            }

            .timeline-modern-content:hover {
              transform: translateY(-3px);
              box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
            }

            .timeline-modern-content::before {
              content: "";
              position: absolute;
              top: 15px;
              left: -8px;
              border-style: solid;
              border-width: 8px 8px 8px 0;
              border-color: transparent #fff transparent transparent;
            }

            .timeline-time {
              font-size: 0.85rem;
              color: #888;
              margin-bottom: 0.8rem;
              display: flex;
              align-items: center;
              gap: 0.4rem;
            }

            .timeline-user {
              font-weight: 700;
              color: #1a2035;
              font-size: 1.1rem;
            }

            .timeline-badge-role {
              font-size: 0.7rem;
              padding: 0.2rem 0.5rem;
              border-radius: 20px;
              margin-left: 0.5rem;
              font-weight: 600;
            }
          </style>

          <!-- Activity Center Screen -->
          <div id="cont_activity_center" class="row d-none">
            <div class="col-md-12">
              <div class="card card-round">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-title text-primary"><i class="fas fa-satellite-dish me-2"></i>Centro de Actividades (Vista Administrador)</div>
                  <span class="badge badge-success">En Vivo <span class="spinner-grow spinner-grow-sm text-success ms-1" role="status" aria-hidden="true" style="width: 10px; height: 10px;"></span></span>
                </div>
                <div class="card-body bg-light rounded-bottom">
                  <p class="text-muted mb-4">Monitor de eventos de sistema. Datos de prueba cargados automáticamente.</p>

                  <div class="timeline-modern" id="admin_activity_timeline">
                    <!-- Renderizado por JS -->
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RESOLVER SCREEN FUERA DE ETIQUETAS -->

        <!-- Activities Screen -->
        <div id="cont_activities" class="row d-none">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title"><i class="fas fa-tasks"></i> Tablero de Actividades</h4>
                <button class="btn btn-primary btn-round btn-sm module_development" id="btn_add_task">
                  <i class="fas fa-plus"></i> Nueva Tarea
                </button>
              </div>
              <div class="card-body bg-light" style="border-radius: 0 0 10px 10px;">
                <div class="row">
                  <!-- Columna Pendientes -->
                  <div class="col-md-4">
                    <div class="card mb-3 border-0 shadow-sm" style="background-color: #f4f6f9;">
                      <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title text-secondary font-weight-bold"><i class="fas fa-clipboard-list text-warning me-2"></i> Pendientes <span class="badge badge-count float-end">2</span></h5>
                      </div>
                      <div class="card-body" style="min-height: 400px;">
                        <!-- Tarea ejemplo -->
                        <div class="card mb-3 shadow-sm card-task border-left-warning" style="border-left: 4px solid #ffad46; cursor: grab;">
                          <div class="card-body p-3">
                            <h6 class="font-weight-bold text-dark">Revisar inventario general</h6>
                            <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">Hacer conteo físico de los productos en almacén principal y verificar inconsistencias.</p>
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="badge badge-warning">Alta</span>
                              <small class="text-muted"><i class="far fa-clock"></i> 10 Oct</small>
                            </div>
                          </div>
                        </div>

                        <!-- Tarea ejemplo -->
                        <div class="card mb-3 shadow-sm card-task" style="border-left: 4px solid #48abf7; cursor: grab;">
                          <div class="card-body p-3">
                            <h6 class="font-weight-bold text-dark">Llamar a proveedores</h6>
                            <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">Confirmar la entrega del pedido #405 para implementos de cirugía.</p>
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="badge badge-info">Media</span>
                              <small class="text-muted"><i class="far fa-clock"></i> 12 Oct</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Columna En Progreso -->
                  <div class="col-md-4">
                    <div class="card mb-3 border-0 shadow-sm" style="background-color: #f4f6f9;">
                      <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title text-secondary font-weight-bold"><i class="fas fa-spinner text-primary me-2"></i> En Progreso <span class="badge badge-count float-end">1</span></h5>
                      </div>
                      <div class="card-body" style="min-height: 400px;">
                        <!-- Tarea ejemplo -->
                        <div class="card mb-3 shadow-sm card-task" style="border-left: 4px solid #f25961; cursor: grab;">
                          <div class="card-body p-3">
                            <h6 class="font-weight-bold text-dark">Actualización de precios</h6>
                            <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">Actualizar los precios en el sistema principal según la última TRM.</p>
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="badge badge-danger">Crítica</span>
                              <div class="avatar-sm">
                                <img src="<?= base_url('public/assets/img/avatar-male.jpg') ?>" alt="..." class="avatar-img rounded-circle border border-white">
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Columna Completado -->
                  <div class="col-md-4">
                    <div class="card mb-3 border-0 shadow-sm" style="background-color: #f4f6f9;">
                      <div class="card-header bg-transparent border-0 pb-0">
                        <h5 class="card-title text-secondary font-weight-bold"><i class="fas fa-check-circle text-success me-2"></i> Completado <span class="badge badge-count float-end">1</span></h5>
                      </div>
                      <div class="card-body" style="min-height: 400px;">
                        <!-- Tarea ejemplo -->
                        <div class="card mb-3 shadow-sm card-task" style="border-left: 4px solid #31ce36; opacity: 0.7; cursor: grab;">
                          <div class="card-body p-3">
                            <h6 class="font-weight-bold text-dark text-decoration-line-through">Facturación mensual</h6>
                            <p class="text-muted small mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">Generar facturas de cierre de mes de Septiembre.</p>
                            <div class="d-flex justify-content-between align-items-center">
                              <span class="badge badge-success">Completado</span>
                              <small class="text-success"><i class="fas fa-check-double"></i> Listo</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- News Wall Screen -->
        <div id="cont_news_wall" class="row d-none">
          <div class="col-md-12">
            <div class="card card-round border-0 shadow-sm">
              <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1572e8 0%, #0d47a1 100%); border-radius: 10px 10px 0 0;">
                <div class="card-title text-white fw-bold"><i class="fas fa-newspaper me-2"></i>Mural de Noticias y Recomendaciones</div>
                <button class="btn btn-light btn-round btn-sm text-primary fw-bold shadow-sm" onclick="$('#modal_recommend_article').modal('show')">
                  <i class="fas fa-plus"></i> Compartir Artículo
                </button>
              </div>
              <div class="card-body bg-light rounded-bottom p-4">
                <div class="text-center mb-5 mt-3">
                  <h2 class="fw-bold text-dark mb-2">Mantente Informado</h2>
                  <p class="text-muted">Descubre los últimos artículos, tendencias y recomendaciones compartidas por el equipo de GM Suministros.</p>
                </div>

                <div class="row" id="news_wall_grid">
                  <!-- Rendered by JS -->
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- End Screens -->
      </div>
    </div>

    <!-- Footers -->
    <footer class="footer">
      <div class="container-fluid d-flex justify-content-between">
        <div>
          Desarrollado por
          <a target="_blank" href="https://gmsuministros.com/">GM Suministros</a>.
        </div>
      </div>
    </footer>
  </div>
  <!-- End Content -->
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
  </script>
  <script src="<?= base_url('public/assets/js/module/main.js') ?>?v=<?= filemtime(ROOTPATH . 'public/assets/js/module/main.js') ?>"></script>


</body>

</html>