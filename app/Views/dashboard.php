<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Gm Suministros - SAS</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="<?= base_url('public/assets/img/kaiadmin/favicon.ico') ?>"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="<?= base_url('public/assets/js/plugin/webfont/webfont.min.js') ?>"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["<?= base_url('public/assets/css/fonts.min.css') ?>"],
        },
        active: function () {
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
    <div class="wrapper">

      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo">
              <img
                src="<?= base_url('public/assets/img/kaiadmin/banner_gms.jpg') ?>"
                alt="navbar brand"
                class="navbar-brand rounded"
                height="40"
              />
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
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Productos</h4>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_product_list">
                  <i class="fas fa-syringe"></i>
                  <p>Listar</p>
                  <!-- <span class="badge badge-secondary">1</span> -->
                </a>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_product_modify">
                  <i class="fas fa-pencil-ruler"></i>
                  <p>Modificar</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Usuarios</h4>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" class="module_bloq">
                  <i class="fas fa-user-friends"></i>
                  <p>Listar</p>
                  <!-- <span class="badge badge-secondary">1</span> -->
                </a>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" class="module_bloq">
                  <i class="fas fa-user-plus"></i>
                  <p>Crear</p>
                </a>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" class="module_bloq">
                  <i class="fas fa-user-edit"></i>
                  <p>Modificar</p>
                </a>
              </li>
              <li class="nav-section">
                <h4 class="text-section">Clientes</h4>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_client_list">
                  <i class="fas fa-user-lock"></i>
                  <p>Listar</p>
                </a>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_client_create">
                  <i class="fas fa-user-plus"></i>
                  <p>Crear</p>
                </a>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Actividades</h4>
              </li>
              <li class="nav-item" style="background: transparent !important;">
                <a href="#" id="btn_open_activities">
                  <i class="fas fa-tasks"></i>
                  <p>Tablero</p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="<?= base_url('public/assets/img/kaiadmin/banner_gms.jpg') ?>"
                  alt="navbar brand"
                  class="navbar-brand rounded"
                  height="40"
                />
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
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Search ..."
                          class="form-control"
                        />
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
                    aria-expanded="false"
                  >
                    <i class="fa fa-bell"></i>
                    <span class="notification">1</span>
                  </a>
                  <ul
                    class="dropdown-menu notif-box animated fadeIn"
                    aria-labelledby="notifDropdown"
                  >
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
                      <a class="see-all" href="javascript:void(0);"
                        >Ver todas las notificaciones<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="<?= session()->get('gender') === 'male' ? base_url('public/assets/img/avatar-male.jpg') : base_url('public/assets/img/avatar-female.png') ?>"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
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
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>Jose</h4>
                            <p class="text-muted">jose@gmsuministros.com</p>
                            <a
                              href="#"
                              class="btn btn-round btn-xs btn-secondary btn-sm"
                              >Ver Perfil</a
                            >
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

            <!-- SCREENS INI -->
            <div id='cont_loading' class="row d-none">
              <div class="col-md-12 d-flex justify-content-center">
                <!-- loading -->
                <div class="loader_spinner"></div>
              </div>
            </div>

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
                        <label for="in_marca_producto">Marca</label>
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
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Imagenes</h4>
                    </div>
                    <div class="card-body">
                      <div id="cont_imagenes_producto"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-7">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Videos</h4>
                    </div>
                    <div class="card-body">
                      <div id="cont_videos_producto"></div>
                    </div>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Documentos</h4>
                    </div>
                    <div class="card-body">
                      <ul class="nav nav-pills nav-secondary" id="pills-tab" role="tablist">
                      </ul>
                      <div class="tab-content mt-2 mb-3" id="pills-tabContent">
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-12 d-none" id="cont_variantes_wrapper">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Variantes Disponibles</h4>
                    </div>
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-hover">
                          <thead>
                            <tr>
                              <th>ID</th>
                              <th>Presentación</th>
                              <th>Categoría</th>
                              <th>Marca</th>
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

            <!-- SCREENS END -->
          </div>
        </div>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <div>
              Desarrollado por
              <a target="_blank" href="https://gmsuministros.com/">GM Suministros</a>.
            </div>
          </div>
        </footer>
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
    <script src="<?= base_url('public/assets/js/module/main.js') ?>"></script>

    <!-- 1.0 Scripts personalizados para implementos médicos -->
    <script>
      // 1.1 Función para guardar implemento
      function guardarImplemento() {
        // 1.2 Obtener valores del formulario
        const nombreProducto = document.getElementById('nombreProducto').value;
        const capacidad = document.getElementById('capacidad').value;
        const dimension = document.getElementById('dimension').value;
        const modelo = document.getElementById('modelo').value;
        const descripcion = document.getElementById('descripcion').value;
        const cantidad = document.getElementById('cantidad').value;

        // 1.3 Validar campos requeridos
        if (!nombreProducto || !capacidad || !dimension || !modelo) {
          swal('Validación', 'Por favor complete todos los campos requeridos', 'warning');
          return;
        }

        // 1.4 Mostrar mensaje de éxito
        swal('Éxito', 'Implemento médico guardado correctamente', 'success');
        
        // 1.5 Limpiar formulario después de guardar
        limpiarFormulario();
      }

      // 2.0 Función para limpiar formulario
      function limpiarFormulario() {
        // 2.1 Restablecer todos los campos
        document.getElementById('nombreProducto').value = '';
        document.getElementById('capacidad').value = '';
        document.getElementById('dimension').value = '';
        document.getElementById('modelo').value = '';
        document.getElementById('descripcion').value = '';
        document.getElementById('cantidad').value = '';
        
        // 2.2 Enfocar el primer campo
        document.getElementById('nombreProducto').focus();
      }

      // 3.0 Función para cancelar
      function cancelar() {
        // 3.1 Confirmar antes de cancelar
        swal({
          title: '¿Cancelar?',
          text: 'Los datos no guardados se perderán',
          icon: 'warning',
          buttons: true,
          dangerMode: true,
        }).then((willCancel) => {
          if (willCancel) {
            // 3.2 Limpiar formulario si confirma
            limpiarFormulario();
            swal('Cancelado', 'Formulario limpiado', 'info');
          }
        });
      }

      function guardarCliente() {
        const formData = new FormData(document.getElementById('form_client_create'));
        const nombreCliente = formData.get('nombre_cliente');
        const numeroDocumento = formData.get('numero_documento');
        
        if (!nombreCliente || !numeroDocumento) {
          swal('Validación', 'Por favor complete todos los campos requeridos', 'warning');
          return;
        }

        // Bloquear boton temporalmente
        const btn = document.querySelector('#cont_client_create .btn-success');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';

        $.ajax({
            url: SITE_URL + '/client/save', // Endpoint en backend
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                if(typeof resp === 'string') {
                    try { resp = JSON.parse(resp); } catch(e) {}
                }
                
                if (resp.status === 'success') {
                    swal('Éxito', 'Cliente guardado correctamente en la tabla', 'success');
                    document.getElementById('form_client_create').reset();
                } else {
                    swal('Error', resp.message || 'Error al guardar el cliente', 'error');
                }
            },
            error: function() {
                swal('Error', 'Hubo un problema de conexión intentando guardar en base de datos', 'error');
            },
            complete: function() {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
      }
    </script>
  </body>
</html>
