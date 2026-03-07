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
                class="navbar-brand"
                height="40"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
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
              <li class="nav-item">
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
              <li class="nav-item">
                <a href="#" id="btn_open_product_list">
                  <i class="fas fa-syringe"></i>
                  <p>Listar</p>
                  <!-- <span class="badge badge-secondary">1</span> -->
                </a>
              </li>
              <li class="nav-item">
                <a href="#" id="btn_open_product_create">
                  <i class="fas fa-plus"></i>
                  <p>Crear</p>
                </a>
              </li>
              <li class="nav-item">
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
              <li class="nav-item">
                <a href="#" class="module_bloq">
                  <i class="fas fa-user-friends"></i>
                  <p>Listar</p>
                  <!-- <span class="badge badge-secondary">1</span> -->
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="module_bloq">
                  <i class="fas fa-user-plus"></i>
                  <p>Crear</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="module_bloq">
                  <i class="fas fa-user-edit"></i>
                  <p>Modificar</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Facturas</h4>
              </li>
              <li class="nav-item">
                <a href="#" class="module_development">
                  <i class="fas fa-file-invoice-dollar"></i>
                  <p>Listar</p>
                  <!-- <span class="badge badge-secondary">1</span> -->
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="module_development">
                  <i class="fas fa-file-medical"></i>
                  <p>Crear</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" class="module_development">
                  <i class="fas fa-file-signature"></i>
                  <p>Modificar</p>
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
                  class="navbar-brand"
                  height="40"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
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
                              class="btn btn-xs btn-secondary btn-sm"
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
                    <button class="btn btn-success" onclick="guardarImplemento()">
                      <i class="fas fa-save"></i> Guardar Implemento
                    </button>
                    <button class="btn btn-secondary" onclick="limpiarFormulario()">
                      <i class="fas fa-redo"></i> Limpiar
                    </button>
                    <button class="btn btn-danger" onclick="cancelar()">
                      <i class="fas fa-times"></i> Cancelar
                    </button>
                  </div>
                </div>
              </div>
            </div>
            
            <div id="cont_product_list" class="row d-none">

              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Basic</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="tbl_list_productos" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Presentación</th>
                            <th>Marca</th>
                            <th>Observación</th>
                            <th>Img</th>
                            <th>Abrir</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Presentación</th>
                            <th>Marca</th>
                            <th>Observación</th>
                            <th>Img</th>
                            <th>Abrir</th>
                          </tr>
                        </tfoot>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row d-none" id="cont_detalle_producto">

                <div class="col-md-12 text-center">
                  <h1>Detalles</h1>
                </div>
  
                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Opciones</h4>
                    </div>
                    <div class="card-body">
                      
                      <button class="btn btn-success en-desarrollo">
                        <span class="btn-label">
                          <i class="fa fa-link"></i>
                        </span>
                        Compartir
                      </button>
  
                      <button class="btn btn-secondary en-desarrollo">
                        <span class="btn-label">
                          <i class="fa fa-edit"></i>
                        </span>
                        Modificar
                      </button>
  
                      <button class="btn btn-info en-desarrollo">
                        <span class="btn-label">
                          <i class="fas fa-sticky-note"></i>
                        </span>
                        Agregar Nota
                      </button>
  
                      <button class="btn btn-danger en-desarrollo">
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
    </script>
  </body>
</html>
