<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Kaiadmin - Bootstrap 5 Admin Dashboard</title>
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
                src="<?= base_url('public/assets/img/kaiadmin/logo_light.svg') ?>"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
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
                  src="<?= base_url('public/assets/img/kaiadmin/logo_light.svg') ?>"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
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
                        src="<?= base_url('public/assets/img/profile.jpg') ?>"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hola,</span>
                      <span class="fw-bold">Jose</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="<?= base_url('public/assets/img/profile.jpg') ?>"
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
                        <a class="dropdown-item" href="#">Salir</a>
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
              <!-- x.0 Card de formularios de ejemplo -->
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <div class="card-title">Form Elements</div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <label for="email2">Email Address</label>
                          <input type="email" class="form-control" id="email2" placeholder="Enter Email">
                          <small id="emailHelp2" class="form-text text-muted">We'll never share your email with anyone
                            else.</small>
                        </div>
                        <div class="form-group">
                          <label for="password">Password</label>
                          <input type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <div class="form-group form-inline">
                          <label for="inlineinput" class="col-md-3 col-form-label">Inline Input</label>
                          <div class="col-md-9 p-0">
                            <input type="text" class="form-control input-full" id="inlineinput" placeholder="Enter Input">
                          </div>
                        </div>
                        <div class="form-group has-success">
                          <label for="successInput">Success Input</label>
                          <input type="text" id="successInput" value="Success" class="form-control">
                        </div>
                        <div class="form-group has-error has-feedback">
                          <label for="errorInput">Error Input</label>
                          <input type="text" id="errorInput" value="Error" class="form-control">
                          <small id="emailHelp" class="form-text text-muted">Please provide a valid informations.</small>
                        </div>
                        <div class="form-group">
                          <label for="disableinput">Disable Input</label>
                          <input type="text" class="form-control" id="disableinput" placeholder="Enter Input" disabled="">
                        </div>
                        <div class="form-group">
                          <label>Gender</label><br>
                          <div class="d-flex">
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                              <label class="form-check-label" for="flexRadioDefault1">
                                Male
                              </label>
                            </div>
                            <div class="form-check">
                              <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked="">
                              <label class="form-check-label" for="flexRadioDefault2">
                                Female
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label"> Static </label>
                          <p class="form-control-static">hello@example.com</p>
                        </div>
                        <div class="form-group">
                          <label for="exampleFormControlSelect1">Example select</label>
                          <select class="form-select" id="exampleFormControlSelect1">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="exampleFormControlSelect2">Example multiple select</label>
                          <select multiple="" class="form-control" id="exampleFormControlSelect2">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="exampleFormControlFile1">Example file input</label>
                          <input type="file" class="form-control-file" id="exampleFormControlFile1">
                        </div>
                        <div class="form-group">
                          <label for="comment">Comment</label>
                          <textarea class="form-control" id="comment" rows="5">                          </textarea>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                          <label class="form-check-label" for="flexCheckDefault">
                            Agree with terms and conditions
                          </label>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-4">
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">@</span>
                            <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">
                            <span class="input-group-text" id="basic-addon2">@example.com</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="basic-url">Your vanity URL</label>
                          <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon3">https://example.com/users/</span>
                            <input type="text" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group mb-3">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)">
                            <span class="input-group-text">.00</span>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                            <span class="input-group-text">With textarea</span>
                            <textarea class="form-control" aria-label="With textarea"></textarea>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                            <button class="btn btn-black btn-border" type="button">
                              Button
                            </button>
                            <input type="text" class="form-control" placeholder="" aria-label="" aria-describedby="basic-addon1">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-group">
                            <input type="text" class="form-control" aria-label="Text input with dropdown button">
                            <div class="input-group-append">
                              <button class="btn btn-primary btn-border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Dropdown
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                                <div role="separator" class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#">Separated link</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-icon">
                            <input type="text" class="form-control" placeholder="Search for...">
                            <span class="input-icon-addon">
                              <i class="fa fa-search"></i>
                            </span>
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="input-icon">
                            <span class="input-icon-addon">
                              <i class="fa fa-user"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Username">
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Image Check</label>
                          <div class="row">
                            <div class="col-6 col-sm-4">
                              <label class="imagecheck mb-4">
                                <input name="imagecheck" type="checkbox" value="1" class="imagecheck-input">
                                <figure class="imagecheck-figure">
                                  <img src="../assets/img/examples/product1.jpg" alt="title" class="imagecheck-image">
                                </figure>
                              </label>
                            </div>
                            <div class="col-6 col-sm-4">
                              <label class="imagecheck mb-4">
                                <input name="imagecheck" type="checkbox" value="2" class="imagecheck-input" checked="">
                                <figure class="imagecheck-figure">
                                  <img src="../assets/img/examples/product4.jpg" alt="title" class="imagecheck-image">
                                </figure>
                              </label>
                            </div>
                            <div class="col-6 col-sm-4">
                              <label class="imagecheck mb-4">
                                <input name="imagecheck" type="checkbox" value="3" class="imagecheck-input">
                                <figure class="imagecheck-figure">
                                  <img src="../assets/img/examples/product3.jpg" alt="title" class="imagecheck-image">
                                </figure>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Color Input</label>
                          <div class="row gutters-xs">
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="dark" class="colorinput-input">
                                <span class="colorinput-color bg-black"></span>
                              </label>
                            </div>
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="primary" class="colorinput-input">
                                <span class="colorinput-color bg-primary"></span>
                              </label>
                            </div>
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="secondary" class="colorinput-input">
                                <span class="colorinput-color bg-secondary"></span>
                              </label>
                            </div>
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="info" class="colorinput-input">
                                <span class="colorinput-color bg-info"></span>
                              </label>
                            </div>
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="success" class="colorinput-input">
                                <span class="colorinput-color bg-success"></span>
                              </label>
                            </div>
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="danger" class="colorinput-input">
                                <span class="colorinput-color bg-danger"></span>
                              </label>
                            </div>
                            <div class="col-auto">
                              <label class="colorinput">
                                <input name="color" type="checkbox" value="warning" class="colorinput-input">
                                <span class="colorinput-color bg-warning"></span>
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Size</label>
                          <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                              <input type="radio" name="value" value="50" class="selectgroup-input" checked="">
                              <span class="selectgroup-button">S</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="value" value="100" class="selectgroup-input">
                              <span class="selectgroup-button">M</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="value" value="150" class="selectgroup-input">
                              <span class="selectgroup-button">L</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="value" value="200" class="selectgroup-input">
                              <span class="selectgroup-button">XL</span>
                            </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Icons input</label>
                          <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                              <input type="radio" name="transportation" value="2" class="selectgroup-input">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="icon-screen-smartphone"></i></span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="transportation" value="1" class="selectgroup-input" checked="">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="icon-screen-tablet"></i></span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="transportation" value="6" class="selectgroup-input">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="icon-screen-desktop"></i></span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="transportation" value="6" class="selectgroup-input">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-times"></i></span>
                            </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label d-block">Icon input</label>
                          <div class="selectgroup selectgroup-secondary selectgroup-pills">
                            <label class="selectgroup-item">
                              <input type="radio" name="icon-input" value="1" class="selectgroup-input" checked="">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-sun"></i></span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="icon-input" value="2" class="selectgroup-input">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-moon"></i></span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="icon-input" value="3" class="selectgroup-input">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-tint"></i></span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="radio" name="icon-input" value="4" class="selectgroup-input">
                              <span class="selectgroup-button selectgroup-button-icon"><i class="fa fa-cloud"></i></span>
                            </label>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="form-label">Your skills</label>
                          <div class="selectgroup selectgroup-pills">
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="HTML" class="selectgroup-input" checked="">
                              <span class="selectgroup-button">HTML</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="CSS" class="selectgroup-input">
                              <span class="selectgroup-button">CSS</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="PHP" class="selectgroup-input">
                              <span class="selectgroup-button">PHP</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="JavaScript" class="selectgroup-input">
                              <span class="selectgroup-button">JavaScript</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="Ruby" class="selectgroup-input">
                              <span class="selectgroup-button">Ruby</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="Ruby" class="selectgroup-input">
                              <span class="selectgroup-button">Ruby</span>
                            </label>
                            <label class="selectgroup-item">
                              <input type="checkbox" name="value" value="C++" class="selectgroup-input">
                              <span class="selectgroup-button">C++</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6 col-lg-4">
                        <label class="mb-3"><b>Form Group Default</b></label>
                        <div class="form-group form-group-default">
                          <label>Input</label>
                          <input id="Name" type="text" class="form-control" placeholder="Fill Name">
                        </div>
                        <div class="form-group form-group-default">
                          <label>Select</label>
                          <select class="form-select" id="formGroupDefaultSelect">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                        <label class="mt-3 mb-3"><b>Form Floating Label</b></label>
                        <div class="form-floating form-floating-custom mb-3">
                          <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                          <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating form-floating-custom mb-3">
                          <select class="form-select" id="selectFloatingLabel" required="">
                            <option selected="">1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                          <label for="selectFloatingLabel">Select</label>
                        </div>

                        <div class="form-group">
                          <label for="largeInput">Large Input</label>
                          <input type="text" class="form-control form-control-lg" id="largeInput" placeholder="Large Input">
                        </div>
                        <div class="form-group">
                          <label for="largeInput">Default Input</label>
                          <input type="text" class="form-control form-control" id="defaultInput" placeholder="Default Input">
                        </div>
                        <div class="form-group">
                          <label for="smallInput">Small Input</label>
                          <input type="text" class="form-control form-control-sm" id="smallInput" placeholder="Small Input">
                        </div>
                        <div class="form-group">
                          <label for="largeSelect">Large Select</label>
                          <select class="form-select form-control-lg" id="largeSelect">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="defaultSelect">Default Select</label>
                          <select class="form-select form-control" id="defaultSelect">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                        <div class="form-group">
                          <label for="smallSelect">Small Select</label>
                          <select class="form-select form-control-sm" id="smallSelect">
                            <option>1</option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-action">
                    <button class="btn btn-success">Submit</button>
                    <button class="btn btn-danger">Cancel</button>
                  </div>
                </div>
              </div>

              <!-- x.0 Card de datatables de ejemplo -->
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Basic</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                          </tr>
                          <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                          </tr>
                          <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                          </tr>
                          <tr>
                            <td>Cedric Kelly</td>
                            <td>Senior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2012/03/29</td>
                            <td>$433,060</td>
                          </tr>
                          <tr>
                            <td>Airi Satou</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>33</td>
                            <td>2008/11/28</td>
                            <td>$162,700</td>
                          </tr>
                          <tr>
                            <td>Brielle Williamson</td>
                            <td>Integration Specialist</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2012/12/02</td>
                            <td>$372,000</td>
                          </tr>
                          <tr>
                            <td>Herrod Chandler</td>
                            <td>Sales Assistant</td>
                            <td>San Francisco</td>
                            <td>59</td>
                            <td>2012/08/06</td>
                            <td>$137,500</td>
                          </tr>
                          <tr>
                            <td>Rhona Davidson</td>
                            <td>Integration Specialist</td>
                            <td>Tokyo</td>
                            <td>55</td>
                            <td>2010/10/14</td>
                            <td>$327,900</td>
                          </tr>
                          <tr>
                            <td>Colleen Hurst</td>
                            <td>Javascript Developer</td>
                            <td>San Francisco</td>
                            <td>39</td>
                            <td>2009/09/15</td>
                            <td>$205,500</td>
                          </tr>
                          <tr>
                            <td>Sonya Frost</td>
                            <td>Software Engineer</td>
                            <td>Edinburgh</td>
                            <td>23</td>
                            <td>2008/12/13</td>
                            <td>$103,600</td>
                          </tr>
                          <tr>
                            <td>Jena Gaines</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>30</td>
                            <td>2008/12/19</td>
                            <td>$90,560</td>
                          </tr>
                          <tr>
                            <td>Quinn Flynn</td>
                            <td>Support Lead</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2013/03/03</td>
                            <td>$342,000</td>
                          </tr>
                          <tr>
                            <td>Charde Marshall</td>
                            <td>Regional Director</td>
                            <td>San Francisco</td>
                            <td>36</td>
                            <td>2008/10/16</td>
                            <td>$470,600</td>
                          </tr>
                          <tr>
                            <td>Haley Kennedy</td>
                            <td>Senior Marketing Designer</td>
                            <td>London</td>
                            <td>43</td>
                            <td>2012/12/18</td>
                            <td>$313,500</td>
                          </tr>
                          <tr>
                            <td>Tatyana Fitzpatrick</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>19</td>
                            <td>2010/03/17</td>
                            <td>$385,750</td>
                          </tr>
                          <tr>
                            <td>Michael Silva</td>
                            <td>Marketing Designer</td>
                            <td>London</td>
                            <td>66</td>
                            <td>2012/11/27</td>
                            <td>$198,500</td>
                          </tr>
                          <tr>
                            <td>Paul Byrd</td>
                            <td>Chief Financial Officer (CFO)</td>
                            <td>New York</td>
                            <td>64</td>
                            <td>2010/06/09</td>
                            <td>$725,000</td>
                          </tr>
                          <tr>
                            <td>Gloria Little</td>
                            <td>Systems Administrator</td>
                            <td>New York</td>
                            <td>59</td>
                            <td>2009/04/10</td>
                            <td>$237,500</td>
                          </tr>
                          <tr>
                            <td>Bradley Greer</td>
                            <td>Software Engineer</td>
                            <td>London</td>
                            <td>41</td>
                            <td>2012/10/13</td>
                            <td>$132,000</td>
                          </tr>
                          <tr>
                            <td>Dai Rios</td>
                            <td>Personnel Lead</td>
                            <td>Edinburgh</td>
                            <td>35</td>
                            <td>2012/09/26</td>
                            <td>$217,500</td>
                          </tr>
                          <tr>
                            <td>Jenette Caldwell</td>
                            <td>Development Lead</td>
                            <td>New York</td>
                            <td>30</td>
                            <td>2011/09/03</td>
                            <td>$345,000</td>
                          </tr>
                          <tr>
                            <td>Yuri Berry</td>
                            <td>Chief Marketing Officer (CMO)</td>
                            <td>New York</td>
                            <td>40</td>
                            <td>2009/06/25</td>
                            <td>$675,000</td>
                          </tr>
                          <tr>
                            <td>Caesar Vance</td>
                            <td>Pre-Sales Support</td>
                            <td>New York</td>
                            <td>21</td>
                            <td>2011/12/12</td>
                            <td>$106,450</td>
                          </tr>
                          <tr>
                            <td>Doris Wilder</td>
                            <td>Sales Assistant</td>
                            <td>Sidney</td>
                            <td>23</td>
                            <td>2010/09/20</td>
                            <td>$85,600</td>
                          </tr>
                          <tr>
                            <td>Angelica Ramos</td>
                            <td>Chief Executive Officer (CEO)</td>
                            <td>London</td>
                            <td>47</td>
                            <td>2009/10/09</td>
                            <td>$1,200,000</td>
                          </tr>
                          <tr>
                            <td>Gavin Joyce</td>
                            <td>Developer</td>
                            <td>Edinburgh</td>
                            <td>42</td>
                            <td>2010/12/22</td>
                            <td>$92,575</td>
                          </tr>
                          <tr>
                            <td>Jennifer Chang</td>
                            <td>Regional Director</td>
                            <td>Singapore</td>
                            <td>28</td>
                            <td>2010/11/14</td>
                            <td>$357,650</td>
                          </tr>
                          <tr>
                            <td>Brenden Wagner</td>
                            <td>Software Engineer</td>
                            <td>San Francisco</td>
                            <td>28</td>
                            <td>2011/06/07</td>
                            <td>$206,850</td>
                          </tr>
                          <tr>
                            <td>Fiona Green</td>
                            <td>Chief Operating Officer (COO)</td>
                            <td>San Francisco</td>
                            <td>48</td>
                            <td>2010/03/11</td>
                            <td>$850,000</td>
                          </tr>
                          <tr>
                            <td>Shou Itou</td>
                            <td>Regional Marketing</td>
                            <td>Tokyo</td>
                            <td>20</td>
                            <td>2011/08/14</td>
                            <td>$163,000</td>
                          </tr>
                          <tr>
                            <td>Michelle House</td>
                            <td>Integration Specialist</td>
                            <td>Sidney</td>
                            <td>37</td>
                            <td>2011/06/02</td>
                            <td>$95,400</td>
                          </tr>
                          <tr>
                            <td>Suki Burks</td>
                            <td>Developer</td>
                            <td>London</td>
                            <td>53</td>
                            <td>2009/10/22</td>
                            <td>$114,500</td>
                          </tr>
                          <tr>
                            <td>Prescott Bartlett</td>
                            <td>Technical Author</td>
                            <td>London</td>
                            <td>27</td>
                            <td>2011/05/07</td>
                            <td>$145,000</td>
                          </tr>
                          <tr>
                            <td>Gavin Cortez</td>
                            <td>Team Leader</td>
                            <td>San Francisco</td>
                            <td>22</td>
                            <td>2008/10/26</td>
                            <td>$235,500</td>
                          </tr>
                          <tr>
                            <td>Martena Mccray</td>
                            <td>Post-Sales support</td>
                            <td>Edinburgh</td>
                            <td>46</td>
                            <td>2011/03/09</td>
                            <td>$324,050</td>
                          </tr>
                          <tr>
                            <td>Unity Butler</td>
                            <td>Marketing Designer</td>
                            <td>San Francisco</td>
                            <td>47</td>
                            <td>2009/12/09</td>
                            <td>$85,675</td>
                          </tr>
                          <tr>
                            <td>Howard Hatfield</td>
                            <td>Office Manager</td>
                            <td>San Francisco</td>
                            <td>51</td>
                            <td>2008/12/16</td>
                            <td>$164,500</td>
                          </tr>
                          <tr>
                            <td>Hope Fuentes</td>
                            <td>Secretary</td>
                            <td>San Francisco</td>
                            <td>41</td>
                            <td>2010/02/12</td>
                            <td>$109,850</td>
                          </tr>
                          <tr>
                            <td>Vivian Harrell</td>
                            <td>Financial Controller</td>
                            <td>San Francisco</td>
                            <td>62</td>
                            <td>2009/02/14</td>
                            <td>$452,500</td>
                          </tr>
                          <tr>
                            <td>Timothy Mooney</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>37</td>
                            <td>2008/12/11</td>
                            <td>$136,200</td>
                          </tr>
                          <tr>
                            <td>Jackson Bradshaw</td>
                            <td>Director</td>
                            <td>New York</td>
                            <td>65</td>
                            <td>2008/09/26</td>
                            <td>$645,750</td>
                          </tr>
                          <tr>
                            <td>Olivia Liang</td>
                            <td>Support Engineer</td>
                            <td>Singapore</td>
                            <td>64</td>
                            <td>2011/02/03</td>
                            <td>$234,500</td>
                          </tr>
                          <tr>
                            <td>Bruno Nash</td>
                            <td>Software Engineer</td>
                            <td>London</td>
                            <td>38</td>
                            <td>2011/05/03</td>
                            <td>$163,500</td>
                          </tr>
                          <tr>
                            <td>Sakura Yamamoto</td>
                            <td>Support Engineer</td>
                            <td>Tokyo</td>
                            <td>37</td>
                            <td>2009/08/19</td>
                            <td>$139,575</td>
                          </tr>
                          <tr>
                            <td>Thor Walton</td>
                            <td>Developer</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2013/08/11</td>
                            <td>$98,540</td>
                          </tr>
                          <tr>
                            <td>Finn Camacho</td>
                            <td>Support Engineer</td>
                            <td>San Francisco</td>
                            <td>47</td>
                            <td>2009/07/07</td>
                            <td>$87,500</td>
                          </tr>
                          <tr>
                            <td>Serge Baldwin</td>
                            <td>Data Coordinator</td>
                            <td>Singapore</td>
                            <td>64</td>
                            <td>2012/04/09</td>
                            <td>$138,575</td>
                          </tr>
                          <tr>
                            <td>Zenaida Frank</td>
                            <td>Software Engineer</td>
                            <td>New York</td>
                            <td>63</td>
                            <td>2010/01/04</td>
                            <td>$125,250</td>
                          </tr>
                          <tr>
                            <td>Zorita Serrano</td>
                            <td>Software Engineer</td>
                            <td>San Francisco</td>
                            <td>56</td>
                            <td>2012/06/01</td>
                            <td>$115,000</td>
                          </tr>
                          <tr>
                            <td>Jennifer Acosta</td>
                            <td>Junior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>43</td>
                            <td>2013/02/01</td>
                            <td>$75,650</td>
                          </tr>
                          <tr>
                            <td>Cara Stevens</td>
                            <td>Sales Assistant</td>
                            <td>New York</td>
                            <td>46</td>
                            <td>2011/12/06</td>
                            <td>$145,600</td>
                          </tr>
                          <tr>
                            <td>Hermione Butler</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>47</td>
                            <td>2011/03/21</td>
                            <td>$356,250</td>
                          </tr>
                          <tr>
                            <td>Lael Greer</td>
                            <td>Systems Administrator</td>
                            <td>London</td>
                            <td>21</td>
                            <td>2009/02/27</td>
                            <td>$103,500</td>
                          </tr>
                          <tr>
                            <td>Jonas Alexander</td>
                            <td>Developer</td>
                            <td>San Francisco</td>
                            <td>30</td>
                            <td>2010/07/14</td>
                            <td>$86,500</td>
                          </tr>
                          <tr>
                            <td>Shad Decker</td>
                            <td>Regional Director</td>
                            <td>Edinburgh</td>
                            <td>51</td>
                            <td>2008/11/13</td>
                            <td>$183,000</td>
                          </tr>
                          <tr>
                            <td>Michael Bruce</td>
                            <td>Javascript Developer</td>
                            <td>Singapore</td>
                            <td>29</td>
                            <td>2011/06/27</td>
                            <td>$183,000</td>
                          </tr>
                          <tr>
                            <td>Donna Snider</td>
                            <td>Customer Support</td>
                            <td>New York</td>
                            <td>27</td>
                            <td>2011/01/25</td>
                            <td>$112,000</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
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
              <!-- x.0 Card de datatables de ejemplo -->
              <div class="col-md-12">
                <div class="card">
                  <div class="card-header">
                    <h4 class="card-title">Basic</h4>
                  </div>
                  <div class="card-body">
                    <div class="table-responsive">
                      <table id="basic-datatables" class="display table table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                          </tr>
                        </thead>
                        <tfoot>
                          <tr>
                            <th>Name</th>
                            <th>Position</th>
                            <th>Office</th>
                            <th>Age</th>
                            <th>Start date</th>
                            <th>Salary</th>
                          </tr>
                        </tfoot>
                        <tbody>
                          <tr>
                            <td>Tiger Nixon</td>
                            <td>System Architect</td>
                            <td>Edinburgh</td>
                            <td>61</td>
                            <td>2011/04/25</td>
                            <td>$320,800</td>
                          </tr>
                          <tr>
                            <td>Garrett Winters</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>63</td>
                            <td>2011/07/25</td>
                            <td>$170,750</td>
                          </tr>
                          <tr>
                            <td>Ashton Cox</td>
                            <td>Junior Technical Author</td>
                            <td>San Francisco</td>
                            <td>66</td>
                            <td>2009/01/12</td>
                            <td>$86,000</td>
                          </tr>
                          <tr>
                            <td>Cedric Kelly</td>
                            <td>Senior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2012/03/29</td>
                            <td>$433,060</td>
                          </tr>
                          <tr>
                            <td>Airi Satou</td>
                            <td>Accountant</td>
                            <td>Tokyo</td>
                            <td>33</td>
                            <td>2008/11/28</td>
                            <td>$162,700</td>
                          </tr>
                          <tr>
                            <td>Brielle Williamson</td>
                            <td>Integration Specialist</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2012/12/02</td>
                            <td>$372,000</td>
                          </tr>
                          <tr>
                            <td>Herrod Chandler</td>
                            <td>Sales Assistant</td>
                            <td>San Francisco</td>
                            <td>59</td>
                            <td>2012/08/06</td>
                            <td>$137,500</td>
                          </tr>
                          <tr>
                            <td>Rhona Davidson</td>
                            <td>Integration Specialist</td>
                            <td>Tokyo</td>
                            <td>55</td>
                            <td>2010/10/14</td>
                            <td>$327,900</td>
                          </tr>
                          <tr>
                            <td>Colleen Hurst</td>
                            <td>Javascript Developer</td>
                            <td>San Francisco</td>
                            <td>39</td>
                            <td>2009/09/15</td>
                            <td>$205,500</td>
                          </tr>
                          <tr>
                            <td>Sonya Frost</td>
                            <td>Software Engineer</td>
                            <td>Edinburgh</td>
                            <td>23</td>
                            <td>2008/12/13</td>
                            <td>$103,600</td>
                          </tr>
                          <tr>
                            <td>Jena Gaines</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>30</td>
                            <td>2008/12/19</td>
                            <td>$90,560</td>
                          </tr>
                          <tr>
                            <td>Quinn Flynn</td>
                            <td>Support Lead</td>
                            <td>Edinburgh</td>
                            <td>22</td>
                            <td>2013/03/03</td>
                            <td>$342,000</td>
                          </tr>
                          <tr>
                            <td>Charde Marshall</td>
                            <td>Regional Director</td>
                            <td>San Francisco</td>
                            <td>36</td>
                            <td>2008/10/16</td>
                            <td>$470,600</td>
                          </tr>
                          <tr>
                            <td>Haley Kennedy</td>
                            <td>Senior Marketing Designer</td>
                            <td>London</td>
                            <td>43</td>
                            <td>2012/12/18</td>
                            <td>$313,500</td>
                          </tr>
                          <tr>
                            <td>Tatyana Fitzpatrick</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>19</td>
                            <td>2010/03/17</td>
                            <td>$385,750</td>
                          </tr>
                          <tr>
                            <td>Michael Silva</td>
                            <td>Marketing Designer</td>
                            <td>London</td>
                            <td>66</td>
                            <td>2012/11/27</td>
                            <td>$198,500</td>
                          </tr>
                          <tr>
                            <td>Paul Byrd</td>
                            <td>Chief Financial Officer (CFO)</td>
                            <td>New York</td>
                            <td>64</td>
                            <td>2010/06/09</td>
                            <td>$725,000</td>
                          </tr>
                          <tr>
                            <td>Gloria Little</td>
                            <td>Systems Administrator</td>
                            <td>New York</td>
                            <td>59</td>
                            <td>2009/04/10</td>
                            <td>$237,500</td>
                          </tr>
                          <tr>
                            <td>Bradley Greer</td>
                            <td>Software Engineer</td>
                            <td>London</td>
                            <td>41</td>
                            <td>2012/10/13</td>
                            <td>$132,000</td>
                          </tr>
                          <tr>
                            <td>Dai Rios</td>
                            <td>Personnel Lead</td>
                            <td>Edinburgh</td>
                            <td>35</td>
                            <td>2012/09/26</td>
                            <td>$217,500</td>
                          </tr>
                          <tr>
                            <td>Jenette Caldwell</td>
                            <td>Development Lead</td>
                            <td>New York</td>
                            <td>30</td>
                            <td>2011/09/03</td>
                            <td>$345,000</td>
                          </tr>
                          <tr>
                            <td>Yuri Berry</td>
                            <td>Chief Marketing Officer (CMO)</td>
                            <td>New York</td>
                            <td>40</td>
                            <td>2009/06/25</td>
                            <td>$675,000</td>
                          </tr>
                          <tr>
                            <td>Caesar Vance</td>
                            <td>Pre-Sales Support</td>
                            <td>New York</td>
                            <td>21</td>
                            <td>2011/12/12</td>
                            <td>$106,450</td>
                          </tr>
                          <tr>
                            <td>Doris Wilder</td>
                            <td>Sales Assistant</td>
                            <td>Sidney</td>
                            <td>23</td>
                            <td>2010/09/20</td>
                            <td>$85,600</td>
                          </tr>
                          <tr>
                            <td>Angelica Ramos</td>
                            <td>Chief Executive Officer (CEO)</td>
                            <td>London</td>
                            <td>47</td>
                            <td>2009/10/09</td>
                            <td>$1,200,000</td>
                          </tr>
                          <tr>
                            <td>Gavin Joyce</td>
                            <td>Developer</td>
                            <td>Edinburgh</td>
                            <td>42</td>
                            <td>2010/12/22</td>
                            <td>$92,575</td>
                          </tr>
                          <tr>
                            <td>Jennifer Chang</td>
                            <td>Regional Director</td>
                            <td>Singapore</td>
                            <td>28</td>
                            <td>2010/11/14</td>
                            <td>$357,650</td>
                          </tr>
                          <tr>
                            <td>Brenden Wagner</td>
                            <td>Software Engineer</td>
                            <td>San Francisco</td>
                            <td>28</td>
                            <td>2011/06/07</td>
                            <td>$206,850</td>
                          </tr>
                          <tr>
                            <td>Fiona Green</td>
                            <td>Chief Operating Officer (COO)</td>
                            <td>San Francisco</td>
                            <td>48</td>
                            <td>2010/03/11</td>
                            <td>$850,000</td>
                          </tr>
                          <tr>
                            <td>Shou Itou</td>
                            <td>Regional Marketing</td>
                            <td>Tokyo</td>
                            <td>20</td>
                            <td>2011/08/14</td>
                            <td>$163,000</td>
                          </tr>
                          <tr>
                            <td>Michelle House</td>
                            <td>Integration Specialist</td>
                            <td>Sidney</td>
                            <td>37</td>
                            <td>2011/06/02</td>
                            <td>$95,400</td>
                          </tr>
                          <tr>
                            <td>Suki Burks</td>
                            <td>Developer</td>
                            <td>London</td>
                            <td>53</td>
                            <td>2009/10/22</td>
                            <td>$114,500</td>
                          </tr>
                          <tr>
                            <td>Prescott Bartlett</td>
                            <td>Technical Author</td>
                            <td>London</td>
                            <td>27</td>
                            <td>2011/05/07</td>
                            <td>$145,000</td>
                          </tr>
                          <tr>
                            <td>Gavin Cortez</td>
                            <td>Team Leader</td>
                            <td>San Francisco</td>
                            <td>22</td>
                            <td>2008/10/26</td>
                            <td>$235,500</td>
                          </tr>
                          <tr>
                            <td>Martena Mccray</td>
                            <td>Post-Sales support</td>
                            <td>Edinburgh</td>
                            <td>46</td>
                            <td>2011/03/09</td>
                            <td>$324,050</td>
                          </tr>
                          <tr>
                            <td>Unity Butler</td>
                            <td>Marketing Designer</td>
                            <td>San Francisco</td>
                            <td>47</td>
                            <td>2009/12/09</td>
                            <td>$85,675</td>
                          </tr>
                          <tr>
                            <td>Howard Hatfield</td>
                            <td>Office Manager</td>
                            <td>San Francisco</td>
                            <td>51</td>
                            <td>2008/12/16</td>
                            <td>$164,500</td>
                          </tr>
                          <tr>
                            <td>Hope Fuentes</td>
                            <td>Secretary</td>
                            <td>San Francisco</td>
                            <td>41</td>
                            <td>2010/02/12</td>
                            <td>$109,850</td>
                          </tr>
                          <tr>
                            <td>Vivian Harrell</td>
                            <td>Financial Controller</td>
                            <td>San Francisco</td>
                            <td>62</td>
                            <td>2009/02/14</td>
                            <td>$452,500</td>
                          </tr>
                          <tr>
                            <td>Timothy Mooney</td>
                            <td>Office Manager</td>
                            <td>London</td>
                            <td>37</td>
                            <td>2008/12/11</td>
                            <td>$136,200</td>
                          </tr>
                          <tr>
                            <td>Jackson Bradshaw</td>
                            <td>Director</td>
                            <td>New York</td>
                            <td>65</td>
                            <td>2008/09/26</td>
                            <td>$645,750</td>
                          </tr>
                          <tr>
                            <td>Olivia Liang</td>
                            <td>Support Engineer</td>
                            <td>Singapore</td>
                            <td>64</td>
                            <td>2011/02/03</td>
                            <td>$234,500</td>
                          </tr>
                          <tr>
                            <td>Bruno Nash</td>
                            <td>Software Engineer</td>
                            <td>London</td>
                            <td>38</td>
                            <td>2011/05/03</td>
                            <td>$163,500</td>
                          </tr>
                          <tr>
                            <td>Sakura Yamamoto</td>
                            <td>Support Engineer</td>
                            <td>Tokyo</td>
                            <td>37</td>
                            <td>2009/08/19</td>
                            <td>$139,575</td>
                          </tr>
                          <tr>
                            <td>Thor Walton</td>
                            <td>Developer</td>
                            <td>New York</td>
                            <td>61</td>
                            <td>2013/08/11</td>
                            <td>$98,540</td>
                          </tr>
                          <tr>
                            <td>Finn Camacho</td>
                            <td>Support Engineer</td>
                            <td>San Francisco</td>
                            <td>47</td>
                            <td>2009/07/07</td>
                            <td>$87,500</td>
                          </tr>
                          <tr>
                            <td>Serge Baldwin</td>
                            <td>Data Coordinator</td>
                            <td>Singapore</td>
                            <td>64</td>
                            <td>2012/04/09</td>
                            <td>$138,575</td>
                          </tr>
                          <tr>
                            <td>Zenaida Frank</td>
                            <td>Software Engineer</td>
                            <td>New York</td>
                            <td>63</td>
                            <td>2010/01/04</td>
                            <td>$125,250</td>
                          </tr>
                          <tr>
                            <td>Zorita Serrano</td>
                            <td>Software Engineer</td>
                            <td>San Francisco</td>
                            <td>56</td>
                            <td>2012/06/01</td>
                            <td>$115,000</td>
                          </tr>
                          <tr>
                            <td>Jennifer Acosta</td>
                            <td>Junior Javascript Developer</td>
                            <td>Edinburgh</td>
                            <td>43</td>
                            <td>2013/02/01</td>
                            <td>$75,650</td>
                          </tr>
                          <tr>
                            <td>Cara Stevens</td>
                            <td>Sales Assistant</td>
                            <td>New York</td>
                            <td>46</td>
                            <td>2011/12/06</td>
                            <td>$145,600</td>
                          </tr>
                          <tr>
                            <td>Hermione Butler</td>
                            <td>Regional Director</td>
                            <td>London</td>
                            <td>47</td>
                            <td>2011/03/21</td>
                            <td>$356,250</td>
                          </tr>
                          <tr>
                            <td>Lael Greer</td>
                            <td>Systems Administrator</td>
                            <td>London</td>
                            <td>21</td>
                            <td>2009/02/27</td>
                            <td>$103,500</td>
                          </tr>
                          <tr>
                            <td>Jonas Alexander</td>
                            <td>Developer</td>
                            <td>San Francisco</td>
                            <td>30</td>
                            <td>2010/07/14</td>
                            <td>$86,500</td>
                          </tr>
                          <tr>
                            <td>Shad Decker</td>
                            <td>Regional Director</td>
                            <td>Edinburgh</td>
                            <td>51</td>
                            <td>2008/11/13</td>
                            <td>$183,000</td>
                          </tr>
                          <tr>
                            <td>Michael Bruce</td>
                            <td>Javascript Developer</td>
                            <td>Singapore</td>
                            <td>29</td>
                            <td>2011/06/27</td>
                            <td>$183,000</td>
                          </tr>
                          <tr>
                            <td>Donna Snider</td>
                            <td>Customer Support</td>
                            <td>New York</td>
                            <td>27</td>
                            <td>2011/01/25</td>
                            <td>$112,000</td>
                          </tr>
                        </tbody>
                      </table>
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
