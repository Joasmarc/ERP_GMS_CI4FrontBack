<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Formulario de Registro y Cumplimiento - GM Suministros</title>
  <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
  <link rel="icon" href="<?= base_url('public/assets/img/kaiadmin/favicon.ico') ?>" type="image/x-icon" />

  <!-- Fonts and icons -->
  <script src="<?= base_url('public/assets/js/plugin/webfont/webfont.min.js') ?>"></script>
  <script>
    WebFont.load({
      google: {
        families: ["Outfit:300,400,500,600,700"]
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

  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      color: #f1f5f9;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .main-container {
      max-width: 1000px;
      margin: 40px auto;
      width: 95%;
    }

    .glass-card {
      background: rgba(30, 41, 59, 0.7);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 24px;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
    }

    .header-logo {
      border-radius: 12px;
      height: 55px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .form-group-default {
      background-color: rgba(15, 23, 42, 0.6) !important;
      border: 1px solid rgba(255, 255, 255, 0.1) !important;
      border-radius: 10px !important;
      color: #f1f5f9 !important;
      transition: all 0.3s;
    }

    .form-group-default:focus-within {
      border-color: #3b82f6 !important;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25) !important;
    }

    .form-group-default label {
      color: #94a3b8 !important;
      font-weight: 700 !important;
      font-size: 11px !important;
      text-transform: uppercase !important;
    }

    .form-group-default input, 
    .form-group-default select, 
    .form-group-default textarea {
      color: #f8fafc !important;
      background: transparent !important;
      border: none !important;
    }

    .form-group-default input::placeholder {
      color: #64748b !important;
    }

    .btn-premium {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      color: white;
      border: none;
      font-weight: 600;
      padding: 14px 28px;
      border-radius: 12px;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-premium:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
      background: linear-gradient(135deg, #60a5fa 0%, #2563eb 100%);
      color: white;
    }

    .btn-premium:disabled {
      background: #475569;
      color: #94a3b8;
      box-shadow: none;
      cursor: not-allowed;
    }

    .step-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 30px;
      position: relative;
    }

    .step-indicator::before {
      content: '';
      position: absolute;
      top: 20px;
      left: 10%;
      right: 10%;
      height: 2px;
      background: rgba(255,255,255,0.1);
      z-index: 1;
    }

    .step-line-active {
      position: absolute;
      top: 20px;
      left: 10%;
      width: 0%;
      height: 2px;
      background: #3b82f6;
      z-index: 1;
      transition: width 0.5s ease;
    }

    .step-dot {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: #1e293b;
      border: 2px solid rgba(255,255,255,0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2;
      font-weight: 700;
      color: #94a3b8;
      transition: all 0.3s;
    }

    .step-dot.active {
      border-color: #3b82f6;
      background: #3b82f6;
      color: white;
      box-shadow: 0 0 15px rgba(59, 130, 246, 0.5);
    }

    .step-dot.completed {
      border-color: #10b981;
      background: #10b981;
      color: white;
    }

    .section-title {
      border-left: 4px solid #3b82f6;
      padding-left: 12px;
      margin-bottom: 25px;
      font-weight: 700;
      color: #f8fafc;
    }

    hr {
      border-color: rgba(255,255,255,0.1);
    }

    .form-check-label {
      color: #cbd5e1;
    }

    .table-dark-custom {
      background: rgba(15, 23, 42, 0.4);
      color: #e2e8f0;
      border-color: rgba(255,255,255,0.05);
    }

    .table-dark-custom th {
      background: rgba(15, 23, 42, 0.7);
      color: #94a3b8;
      border-color: rgba(255,255,255,0.08);
      font-weight: 600;
    }

    .table-dark-custom td {
      border-color: rgba(255,255,255,0.05);
    }
  </style>
</head>

<body>

  <div class="main-container">

    <!-- Encabezado de la página -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <div class="d-flex align-items-center gap-3">
        <img src="<?= base_url('public/assets/img/kaiadmin/banner_gms.jpg') ?>" alt="GM Suministros Logo" class="header-logo">
        <div>
          <h2 class="fw-bold mb-0 text-white" style="font-size: 1.6rem;">GM Suministros SAS</h2>
          <small class="text-muted">Registro Oficial de Contraparte y SARLAFT</small>
        </div>
      </div>
      <div class="badge bg-primary px-3 py-2 fs-6">
        <?= esc(ucfirst($contraparte_type)) ?>
      </div>
    </div>

    <!-- Indicador de pasos -->
    <div class="step-indicator">
      <div class="step-line-active" id="step_line"></div>
      <div class="step-dot active" id="dot_step1">1</div>
      <div class="step-dot" id="dot_step2">2</div>
      <div class="step-dot" id="dot_step3">3</div>
    </div>

    <!-- TARJETA PRINCIPAL -->
    <div class="card glass-card p-4 p-md-5">

      <!-- PASO 1: POLÍTICA DE PROTECCIÓN DE DATOS (PDF) -->
      <div id="step_policy_view" class="">
        <h3 class="fw-bold text-white mb-4"><i class="fas fa-file-signature text-info me-2"></i> Política de Protección de Datos Personales</h3>
        <p class="text-muted mb-4">Antes de completar el formulario de registro, por favor lea atentamente nuestra política de tratamiento y protección de datos personales. Debe aceptar los términos y condiciones para habilitar el formulario de registro.</p>
        
        <!-- Contenedor del PDF -->
        <div class="border rounded-4 overflow-hidden mb-4" style="background-color: #525659; border-color: rgba(255,255,255,0.1) !important;">
          <?php
            $pdfPath = 'public/assets/pdf/test.pdf';
            if (file_exists(ROOTPATH . 'public/assets/pdf/politica_privacidad.pdf')) {
                $pdfPath = 'public/assets/pdf/politica_privacidad.pdf';
            }
            $pdfUrl = base_url($pdfPath) . '?v=' . filemtime(ROOTPATH . $pdfPath);
          ?>
          <iframe src="<?= $pdfUrl ?>" style="width: 100%; height: 500px; border: none; display: block;"></iframe>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="chk_accept_policy" style="width: 20px; height: 20px; cursor: pointer;">
          <label class="form-check-label ms-2 mt-1" for="chk_accept_policy" style="cursor: pointer;">
            He leído, comprendido y acepto la Política de Protección de Tratamiento de Datos Personales.
          </label>
        </div>

        <div class="text-end">
          <button type="button" class="btn btn-premium btn-lg" id="btn_accept_policy" disabled>
            Aceptar y Continuar <i class="fas fa-arrow-right ms-2"></i>
          </button>
        </div>
      </div>

      <!-- PASO 2: FORMULARIO DE REGISTRO -->
      <div id="step_form_view" class="d-none">
        <form id="form_public_register" method="POST">
          <?= csrf_field() ?>
          
          <input type="hidden" name="contraparte_type" value="<?= esc($contraparte_type) ?>">
          <input type="hidden" name="person_type" value="<?= esc($person_type) ?>">

          <!-- ============================================== -->
          <!-- CASO JURÍDICO                                  -->
          <!-- ============================================== -->
          <?php if ($person_type === 'juridica'): ?>
            <div id="form_juridico_fields">
              <h3 class="section-title"><i class="fas fa-building text-primary me-2"></i> Datos de Persona Jurídica</h3>
              
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group form-group-default">
                    <label>Nombre del Proveedor (Razón Social o Nombre Completo) *</label>
                    <input type="text" class="form-control" name="nombre_completo" required placeholder="Ingrese razón social">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Tipo de Identificación *</label>
                    <select class="form-select" name="tipo_identificacion" required>
                      <option value="NIT">NIT</option>
                      <option value="Cédula">Cédula</option>
                      <option value="Pasaporte">Pasaporte</option>
                      <option value="Cédula Extranjería">Cédula Extranjería</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default bg-dark" style="opacity: 0.85;">
                    <label>Número de Identificación (Preestablecido)</label>
                    <input type="text" class="form-control" name="numero_identificacion" value="<?= esc($numero_identificacion) ?>" readonly style="cursor: not-allowed;">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
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
                    <input type="text" class="form-control" name="regimen_tributario" placeholder="Ej: Simplificado, Común">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
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
                    <input type="text" class="form-control" name="direccion_completa" placeholder="Dirección de correspondencia">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Email Principal *</label>
                    <input type="email" class="form-control" name="email_principal" required placeholder="correo@empresa.com">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Teléfono Principal *</label>
                    <input type="text" class="form-control" name="telefono_principal" required placeholder="Teléfono celular o fijo">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Teléfono Secundario</label>
                    <input type="text" class="form-control" name="telefono_secundario" placeholder="Teléfono alternativo">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group form-group-default">
                    <label>Página Web</label>
                    <input type="url" class="form-control" name="pagina_web" placeholder="https://ejemplo.com">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group form-group-default">
                    <label>LinkedIn / Redes Sociales</label>
                    <input type="text" class="form-control" name="linkedin_redes" placeholder="Perfil o canal comercial">
                  </div>
                </div>
              </div>

              <h4 class="section-title mt-5"><i class="fas fa-shield-virus text-warning me-2"></i> Cumplimiento Legal y Screening</h4>
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

              <div class="row mt-3">
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

              <h4 class="section-title mt-5"><i class="fas fa-chart-line text-info me-2"></i> Actividad Económica y Exposición</h4>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Sector Económico (Clasificación CIIU)</label>
                    <input type="text" class="form-control" name="sector_economico" placeholder="Ej: 4646 - Farmacéuticos">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Subsector Específico</label>
                    <input type="text" class="form-control" name="subsector_especifico" placeholder="Ej: Insumos de laboratorio">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Tipo de Producto/Servicio Suministrado</label>
                    <input type="text" class="form-control" name="tipo_producto_servicio" placeholder="Ej: Guantes, Equipos">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>¿Es PEP (Persona Expuesta Políticamente)?</label>
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
                    <input type="text" class="form-control" name="paises_opera" placeholder="Países de operación comercial">
                  </div>
                </div>
              </div>
            </div>

          <!-- ============================================== -->
          <!-- CASO NATURAL                                   -->
          <!-- ============================================== -->
          <?php else: ?>
            <div id="form_natural_fields">
              
              <h3 class="section-title"><i class="fas fa-user-circle text-primary me-2"></i> Identificación y Datos Personales</h3>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Tipo de Documento *</label>
                    <select class="form-select" name="tipo_identificacion" required>
                      <option value="Cédula de ciudadanía">Cédula de ciudadanía</option>
                      <option value="Cédula de extranjería">Cédula de extranjería</option>
                      <option value="Pasaporte">Pasaporte</option>
                      <option value="Permiso de residencia">Permiso de residencia</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group form-group-default bg-dark" style="opacity: 0.85;">
                    <label>Número de Identificación (Preestablecido)</label>
                    <input type="text" class="form-control" name="numero_identificacion" value="<?= esc($numero_identificacion) ?>" readonly style="cursor: not-allowed;">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Dígito Verificador (Cédula)</label>
                    <input type="text" class="form-control" name="digito_verificador" placeholder="Ej: 5">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
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

              <div class="row mt-3">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Ciudad Expedición</label>
                    <input type="text" class="form-control" name="ciudad_expedicion" placeholder="Ciudad expedición">
                  </div>
                </div>
                <div class="col-md-5">
                  <div class="form-group form-group-default">
                    <label>Nombre Completo *</label>
                    <input type="text" class="form-control" name="nombre_completo" required placeholder="Nombres completos">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group form-group-default">
                    <label>Primer Apellido *</label>
                    <input type="text" class="form-control" name="primer_apellido" required placeholder="Primer apellido">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group form-group-default">
                    <label>Segundo Apellido</label>
                    <input type="text" class="form-control" name="segundo_apellido" placeholder="Segundo apellido">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Fecha de Nacimiento *</label>
                    <input type="date" class="form-control" name="fecha_nacimiento" required>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group form-group-default">
                    <label>Depto Nacimiento</label>
                    <select class="form-select text-white" id="pub_dept_nacimiento" name="departamento_nacimiento">
                      <option value="">Seleccione Depto</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Lugar de Nacimiento (Ciudad)</label>
                    <select class="form-select text-white" id="pub_city_nacimiento" name="lugar_nacimiento_ciudad">
                      <option value="">Seleccione Ciudad</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group form-group-default">
                    <label>País de Nacimiento</label>
                    <input type="text" class="form-control" name="pais_nacimiento" placeholder="País nacimiento">
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

              <div class="row mt-3">
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

              <h3 class="section-title mt-5"><i class="fas fa-map-marked-alt text-info me-2"></i> Ubicación y Contacto</h3>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>País de Residencia *</label>
                    <input type="text" class="form-control" name="pais_residencia" required placeholder="Ej: Colombia">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Departamento / Región</label>
                    <select class="form-select" id="pub_dept_residencia" name="departamento_residencia">
                      <option value="">Seleccione Departamento</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Ciudad</label>
                    <select class="form-select" id="pub_city_residencia" name="ciudad_residencia">
                      <option value="">Seleccione Ciudad</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Dirección Completa *</label>
                    <input type="text" class="form-control" name="direccion_completa" required placeholder="Dirección residencial">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Número Exterior</label>
                    <input type="text" class="form-control" name="numero_exterior" placeholder="Ej: Calle 10 # 5-25">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Número Interior (opcional)</label>
                    <input type="text" class="form-control" name="numero_interior" placeholder="Ej: Apto 101">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Apartamento / Complemento</label>
                    <input type="text" class="form-control" name="apartamento_complemento" placeholder="Ej: Bloque 2">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Código Postal</label>
                    <input type="text" class="form-control" name="codigo_postal" placeholder="Ej: 050020">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Teléfono Residencial / Principal *</label>
                    <input type="text" class="form-control" name="telefono_principal" required placeholder="Teléfono de contacto">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Tiempo residiendo (meses)</label>
                    <input type="number" class="form-control" name="tiempo_residencia_meses" placeholder="Meses en dirección actual">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Dirección Anterior</label>
                    <input type="text" class="form-control" name="direccion_anterior" placeholder="Dirección previa (si aplica)">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Tiempo en Dirección Anterior</label>
                    <input type="text" class="form-control" name="tiempo_direccion_anterior" placeholder="Ej: 2 años">
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group form-group-default">
                    <label>Dirección Laboral</label>
                    <input type="text" class="form-control" name="direccion_laboral" placeholder="Dirección de la empresa de trabajo">
                  </div>
                </div>
              </div>

              <h3 class="section-title mt-5"><i class="fas fa-briefcase text-success me-2"></i> Información Laboral y Profesional</h3>
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Actividad Principal / Ocupación *</label>
                    <input type="text" class="form-control" name="actividad_principal" required placeholder="Ej: Independiente, Empleado">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Profesión *</label>
                    <input type="text" class="form-control" name="profesion" required placeholder="Ej: Abogado, Contador">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Área de Especialización *</label>
                    <input type="text" class="form-control" name="area_especializacion" required placeholder="Área técnica">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Sector Económico (CIIU) *</label>
                    <input type="text" class="form-control" name="sector_economico" required placeholder="Código CIIU">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Años de Experiencia *</label>
                    <input type="number" class="form-control" name="anos_experiencia" required placeholder="Ej: 5">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Certificaciones Profesionales</label>
                    <input type="text" class="form-control" name="certificaciones_profesionales" placeholder="Ej: Certificado AML, etc.">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Membresías Profesionales</label>
                    <input type="text" class="form-control" name="membresias_profesionales" placeholder="Ej: Junta de contadores">
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group form-group-default">
                    <label>Licencias Profesionales</label>
                    <input type="text" class="form-control" name="licencias_profesionales" placeholder="Ej: Licencia TP-923812">
                  </div>
                </div>
              </div>

              <!-- ANTECEDENTES LABORALES MÚLTIPLES -->
              <h5 class="fw-bold mt-4 mb-3 text-secondary"><i class="fas fa-history me-2"></i> Antecedentes Laborales (Últimos 5 Años)</h5>
              <div class="table-responsive">
                <table class="table table-bordered table-dark-custom" id="tbl_job_history">
                  <thead>
                    <tr>
                      <th>Empresa</th>
                      <th>Cargo</th>
                      <th>Período de Trabajo</th>
                      <th>Razón de Salida</th>
                      <th width="80" class="text-center">Acción</th>
                    </tr>
                  </thead>
                  <tbody id="job_history_body">
                    <tr>
                      <td><input type="text" class="form-control text-white" name="antecedentes_laborales[0][empresa]" placeholder="Empresa"></td>
                      <td><input type="text" class="form-control text-white" name="antecedentes_laborales[0][cargo]" placeholder="Cargo"></td>
                      <td><input type="text" class="form-control text-white" name="antecedentes_laborales[0][periodo]" placeholder="Ej: 2021 - 2024"></td>
                      <td><input type="text" class="form-control text-white" name="antecedentes_laborales[0][salida]" placeholder="Razón de salida"></td>
                      <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeJobRow(this)"><i class="fas fa-trash-alt"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <button type="button" class="btn btn-outline-info btn-sm rounded-pill mt-2" id="btn_add_job_row">
                  <i class="fas fa-plus"></i> Agregar Trabajo
                </button>
              </div>

              <h3 class="section-title mt-5 text-danger"><i class="fas fa-user-shield text-danger me-2"></i> Declaraciones SARLAFT y PEP</h3>
              <div class="row">
                <div class="col-md-5">
                  <div class="form-group form-group-default">
                    <label>¿Es Persona Expuesta Políticamente (PEP)? *</label>
                    <select class="form-select" name="es_pep" id="pub_es_pep" required>
                      <option value="No">No</option>
                      <option value="Sí">Sí</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-7">
                  <div class="form-group form-group-default">
                    <label>Descripción del vínculo PEP</label>
                    <input type="text" class="form-control" name="descripcion_vinculo_pep" placeholder="Ej: Cargo, Entidad, Período de desempeño">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>¿Tiene vínculos con interés criminal? *</label>
                    <select class="form-select" name="vinculos_criminales" required>
                      <option value="No">No</option>
                      <option value="Sí">Sí</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>¿Tiene antecedentes penales? *</label>
                    <select class="form-select" name="antecedentes_judiciales" required>
                      <option value="No">No</option>
                      <option value="Sí">Sí</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>Clasificación de Delitos</label>
                    <input type="text" class="form-control" name="antecedentes_penales" placeholder="Ej: Lavado, Terrorismo, Ninguno">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-12">
                  <div class="form-group form-group-default">
                    <label>Descripción de Antecedentes (Delito, Fecha, Estado)</label>
                    <input type="text" class="form-control" name="descripcion_antecedentes" placeholder="Detalle sentencia o cargos">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>¿Ha sido sancionado disciplinariamente? *</label>
                    <select class="form-select" name="sancionado_disciplinariamente" required>
                      <option value="No">No</option>
                      <option value="Sí">Sí</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group form-group-default">
                    <label>Descripción de Sanciones Disciplinarias</label>
                    <input type="text" class="form-control" name="descripcion_sanciones" placeholder="Detalles sancionatorios">
                  </div>
                </div>
              </div>

              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group form-group-default">
                    <label>¿Opera en país de alto riesgo GAFILAT? *</label>
                    <select class="form-select" name="opera_pais_alto_riesgo" required>
                      <option value="No">No</option>
                      <option value="Sí">Sí</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="form-group form-group-default">
                    <label>Justificación de Exposición Geográfica</label>
                    <input type="text" class="form-control" name="justificacion_exposicion_geografica" placeholder="Justifique su operación geográfica">
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <div class="text-end mt-5">
            <button type="submit" class="btn btn-premium btn-lg" id="btn_submit_registration">
              Enviar Formulario <i class="fas fa-paper-plane ms-2"></i>
            </button>
          </div>
        </form>
      </div>

      <!-- PASO 3: CONFIRMACIÓN DE ÉXITO -->
      <div id="step_success_view" class="d-none text-center py-5">
        <div class="text-success mb-4" style="font-size: 80px;">
          <i class="fas fa-check-circle animate__animated animate__bounceIn"></i>
        </div>
        <h2 class="fw-bold text-white mb-3">¡Formulario Recibido con Éxito!</h2>
        <p class="text-muted fs-5 mb-4" id="success_message_text">Tus datos han sido registrados en nuestro sistema de cumplimiento normativo SARLAFT. La invitación ha sido completada correctamente.</p>
        <button type="button" class="btn btn-premium btn-round px-5" onclick="window.close();">Cerrar Ventana</button>
      </div>

    </div>

    <!-- Pie de página -->
    <div class="text-center text-muted mt-5 mb-4">
      <small>© 2026 GM Suministros SAS - Todos los derechos reservados.</small>
    </div>

  </div>

  <!-- JS Core Files -->
  <script src="<?= base_url('public/assets/js/core/jquery-3.7.1.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/core/popper.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/core/bootstrap.min.js') ?>"></script>
  <script src="<?= base_url('public/assets/js/plugin/sweetalert/sweetalert.min.js') ?>"></script>

  <script>
    const SITE_URL = '<?= base_url() ?>';
    const token = '<?= esc($token) ?>';
    const personType = '<?= esc($person_type) ?>';
    let jobRowCount = 1;

    $(function () {
      // --- PASO 1 LÓGICA ---
      $('#chk_accept_policy').on('change', function () {
        $('#btn_accept_policy').prop('disabled', !$(this).is(':checked'));
      });

      $('#btn_accept_policy').on('click', function () {
        // Transición de paso 1 a paso 2
        $('#step_policy_view').addClass('d-none');
        $('#step_form_view').removeClass('d-none');

        // Actualizar indicadores
        $('#dot_step1').removeClass('active').addClass('completed');
        $('#dot_step2').addClass('active');
        $('#step_line').css('width', '50%');

        // Cargar departamentos si es persona natural
        if (personType === 'natural') {
          loadDepartmentsDropdown('#pub_dept_residencia');
          loadDepartmentsDropdown('#pub_dept_nacimiento');
        }
      });

      // --- DROP DOWNS ENCADENADOS (SOLO NATURAL) ---
      $('#pub_dept_residencia').on('change', function () {
        const deptId = $(this).val();
        loadCitiesDropdown(deptId, '#pub_city_residencia');
      });

      $('#pub_dept_nacimiento').on('change', function () {
        const deptId = $(this).val();
        loadCitiesDropdown(deptId, '#pub_city_nacimiento');
      });

      // --- MANEJO DE TRABAJOS (SOLO NATURAL) ---
      $('#btn_add_job_row').on('click', function () {
        const html = `
          <tr>
            <td><input type="text" class="form-control text-white" name="antecedentes_laborales[${jobRowCount}][empresa]" placeholder="Empresa"></td>
            <td><input type="text" class="form-control text-white" name="antecedentes_laborales[${jobRowCount}][cargo]" placeholder="Cargo"></td>
            <td><input type="text" class="form-control text-white" name="antecedentes_laborales[${jobRowCount}][periodo]" placeholder="Ej: 2021 - 2024"></td>
            <td><input type="text" class="form-control text-white" name="antecedentes_laborales[${jobRowCount}][salida]" placeholder="Razón de salida"></td>
            <td class="text-center">
              <button type="button" class="btn btn-danger btn-sm" onclick="removeJobRow(this)"><i class="fas fa-trash-alt"></i></button>
            </td>
          </tr>
        `;
        $('#job_history_body').append(html);
        jobRowCount++;
      });

      // --- ENVÍO DEL FORMULARIO ---
      $('#form_public_register').on('submit', function (e) {
        e.preventDefault();

        // Validar localmente campos requeridos
        if (!validateFormLocal()) {
          return;
        }

        const formData = $(this).serialize();
        $('#btn_submit_registration').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Procesando...');

        $.ajax({
          url: SITE_URL + '/register/submit/' + token,
          type: 'POST',
          data: formData,
          dataType: 'json',
          success: function (resp) {
            $('#btn_submit_registration').prop('disabled', false).html('Enviar Formulario <i class="fas fa-paper-plane ms-2"></i>');
            
            if (resp.status === 'success') {
              // Transición a paso 3 de éxito
              $('#step_form_view').addClass('d-none');
              $('#step_success_view').removeClass('d-none');
              
              $('#dot_step2').removeClass('active').addClass('completed');
              $('#dot_step3').addClass('active').addClass('completed');
              $('#step_line').css('width', '100%');
              
              swal("¡Completado!", resp.message, "success");
            } else if (resp.status === 'validation_error') {
              swal("Campos Obligatorios Faltantes", "Por favor complete los siguientes campos obligatorios:\n- " + resp.fields.join('\n- '), "warning");
            } else {
              swal("Error", resp.message || "No se pudo procesar el registro.", "error");
            }
          },
          error: function () {
            $('#btn_submit_registration').prop('disabled', false).html('Enviar Formulario <i class="fas fa-paper-plane ms-2"></i>');
            swal("Error", "Ocurrió un error crítico en el servidor. Por favor reintente.", "error");
          }
        });
      });

      // Restricciones de entrada en tiempo real (solo números para teléfonos y experiencia)
      $(document).on('input', 'input[name="telefono_principal"], input[name="telefono_secundario"], input[name="anos_experiencia"], input[name="tiempo_residencia_meses"]', function () {
          this.value = this.value.replace(/[^0-9]/g, '');
      });
    });

    // Remover fila de trabajo
    function removeJobRow(btn) {
      $(btn).closest('tr').remove();
    }

    // Cargar Departamentos de Colombia
    function loadDepartmentsDropdown(selector) {
      const dropdown = $(selector);
      $.ajax({
        url: SITE_URL + '/public/departments',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
          dropdown.empty();
          dropdown.append('<option value="">Seleccione Departamento</option>');
          const list = data || [];
          list.forEach(dept => {
            dropdown.append(`<option value="${dept.id}">${dept.name}</option>`);
          });
        }
      });
    }

    // Cargar Ciudades de Colombia
    function loadCitiesDropdown(deptId, selector) {
      const dropdown = $(selector);
      dropdown.empty();
      dropdown.append('<option value="">Cargando ciudades...</option>');

      if (!deptId) {
        dropdown.empty();
        dropdown.append('<option value="">Seleccione Ciudad</option>');
        return;
      }

      $.ajax({
        url: SITE_URL + '/public/cities/' + deptId,
        type: 'GET',
        dataType: 'json',
        success: function (data) {
          dropdown.empty();
          dropdown.append('<option value="">Seleccione Ciudad</option>');
          const list = data || [];
          list.forEach(city => {
            dropdown.append(`<option value="${city.name}">${city.name}</option>`);
          });
        }
      });
    }

    // Validación local del cliente
    function validateFormLocal() {
      let valid = true;
      let missingFields = [];

      if (personType === 'juridica') {
        const name = $('[name="nombre_completo"]').val();
        const typeDoc = $('[name="tipo_identificacion"]').val();
        const email = $('[name="email_principal"]').val();
        const phone = $('[name="telefono_principal"]').val();

        if (!name) { valid = false; missingFields.push('Nombre del Proveedor (Razón Social)'); }
        if (!typeDoc) { valid = false; missingFields.push('Tipo de Identificación'); }
        if (!email) { valid = false; missingFields.push('Email Principal'); }
        if (!phone) { valid = false; missingFields.push('Teléfono Principal'); }
      } else {
        const name = $('[name="nombre_completo"]').val();
        const lastname = $('[name="primer_apellido"]').val();
        const typeDoc = $('[name="tipo_identificacion"]').val();
        const bdate = $('[name="fecha_nacimiento"]').val();
        const country = $('[name="pais_residencia"]').val();
        const address = $('[name="direccion_completa"]').val();
        const phone = $('[name="telefono_principal"]').val();
        const job = $('[name="actividad_principal"]').val();
        const prof = $('[name="profesion"]').val();
        const spec = $('[name="area_especializacion"]').val();
        const ciiu = $('[name="sector_economico"]').val();
        const exp = $('[name="anos_experiencia"]').val();

        if (!name) { valid = false; missingFields.push('Nombre Completo'); }
        if (!lastname) { valid = false; missingFields.push('Primer Apellido'); }
        if (!typeDoc) { valid = false; missingFields.push('Tipo de Documento'); }
        if (!bdate) { valid = false; missingFields.push('Fecha de Nacimiento'); }
        if (!country) { valid = false; missingFields.push('País de Residencia'); }
        if (!address) { valid = false; missingFields.push('Dirección Completa'); }
        if (!phone) { valid = false; missingFields.push('Teléfono Residencial'); }
        if (!job) { valid = false; missingFields.push('Actividad Principal / Ocupación'); }
        if (!prof) { valid = false; missingFields.push('Profesión'); }
        if (!spec) { valid = false; missingFields.push('Área de Especialización'); }
        if (!ciiu) { valid = false; missingFields.push('Sector Económico (CIIU)'); }
        if (!exp) { valid = false; missingFields.push('Años de Experiencia'); }
      }

      if (!valid) {
        swal("Campos Obligatorios Vacíos", "Los siguientes campos son obligatorios:\n- " + missingFields.join('\n- '), "warning");
        return false;
      }

      // Validar formato de correos si es jurídica
      if (personType === 'juridica') {
          const email = $('[name="email_principal"]').val();
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (email && !emailRegex.test(email)) {
              swal("Formato Incorrecto", "El correo electrónico principal no tiene un formato válido.", "warning");
              return false;
          }
      }

      // Validar formato de teléfonos
      const phoneRegex = /^[0-9]+$/;
      const mainPhone = $('[name="telefono_principal"]').val();
      if (mainPhone && !phoneRegex.test(mainPhone)) {
          swal("Formato Incorrecto", "El teléfono principal debe contener únicamente números.", "warning");
          return false;
      }

      const secPhone = $('[name="telefono_secundario"]').val();
      if (secPhone && !phoneRegex.test(secPhone)) {
          swal("Formato Incorrecto", "El teléfono secundario debe contener únicamente números.", "warning");
          return false;
      }

      // Validar formato de páginas web (si aplica)
      const webUrl = $('[name="pagina_web"]').val();
      if (webUrl) {
          try {
              new URL(webUrl);
          } catch (_) {
              swal("Formato Incorrecto", "La dirección de la página web no es una URL válida (ej: https://ejemplo.com).", "warning");
              return false;
          }
      }

      return true;
    }
  </script>

</body>

</html>
