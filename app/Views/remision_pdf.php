<?php
$dispatch = (isset($dispatch) && is_array($dispatch)) ? $dispatch : [];
$items = (isset($items) && is_array($items)) ? $items : [];
$rawType = strtoupper(trim($dispatch['type'] ?? 'REMISION'));
$typeTitles = [
    'REMISION' => 'REMISIÓN',
    'EXTERNO'  => 'TRASLADO EXTERNO',
    'INTERNO'  => 'TRASLADO INTERNO',
    'INGRESO'  => 'INGRESO A BODEGA',
    'AJUSTE'   => 'AJUSTE DE INVENTARIO'
];
$docTitle = $typeTitles[$rawType] ?? 'REMISIÓN';

// Preparación y paginación: estrictamente 15 referencias por hoja
$itemsList = is_array($items) ? array_values($items) : [];
$itemsPerPage = 15;
$totalItems = count($itemsList);
$totalPages = max(1, (int)ceil($totalItems / $itemsPerPage));

// Cálculo del total acumulado de insumos de toda la remisión
$totalGlobalCantidad = 0;
foreach ($itemsList as $it) {
    $q = isset($it['quiantity']) ? (int)$it['quiantity'] : (isset($it['quantity']) ? (int)$it['quantity'] : 0);
    $totalGlobalCantidad += $q;
}

// Consecutivo formateado
$cityCode = !empty($dispatch['city_code']) ? $dispatch['city_code'] : (!empty($dispatch['city_name']) ? substr($dispatch['city_name'], 0, 3) : 'GM');
$consecutivoFormatted = 'N° ' . esc($cityCode) . '-' . str_pad(esc($dispatch['sequence'] ?? '1'), 3, '0', STR_PAD_LEFT);

// Fecha de elaboración formateada
$fechaElab = '';
if (!empty($dispatch['created_at'])) {
    $ts = strtotime($dispatch['created_at']);
    $fechaElab = $ts ? date('Y-m-d', $ts) : esc($dispatch['created_at']);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($docTitle) ?> - Grupo Monzant SAS</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        :root {
            --primary: #1E3A8A;
            --secondary: #166534;
            --accent-blue: #EFF6FF;
            --accent-green: #F0FDF4;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --border-blue: #1E3A8A;
            --border-green: #166534;
            --border-light: #d1d5db;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            background-color: #525659;
            color: var(--text-main);
            padding: 20px 10px;
            font-size: 11px;
            line-height: 1.35;
        }

        /* Barra de acciones superior para visualización en pantalla */
        .print-toolbar {
            max-width: 840px;
            margin: 0 auto 14px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #ffffff;
            padding: 10px 18px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .print-toolbar .doc-info {
            font-weight: 600;
            color: var(--primary);
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #2563eb;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s ease, transform 0.1s ease;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.3);
        }

        .btn-print-action:hover {
            background: #1d4ed8;
            transform: translateY(-1px);
        }

        .btn-print-action:active {
            transform: translateY(0);
        }

        /* Contenedor Principal (Hoja Carta) */
        .sheet {
            max-width: 840px;
            margin: 0 auto 24px auto;
            background: #ffffff;
            padding: 26px 32px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            border-radius: 4px;
            position: relative;
        }

        .sheet:last-child {
            margin-bottom: 24px;
        }

        /* Encabezado del Documento */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            gap: 15px;
        }

        .header-logo-col {
            flex: 0 0 230px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
        }

        .header-logo-col img {
            max-width: 220px;
            max-height: 80px;
            width: auto;
            height: auto;
            object-fit: contain;
            display: block;
        }

        .header-company-col {
            flex: 1;
            text-align: center;
            font-size: 11px;
            color: #374151;
            line-height: 1.4;
        }

        .header-company-col h1 {
            font-size: 14.5px;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .header-company-col .nit {
            font-weight: 700;
            color: #111827;
        }

        .header-badge-col {
            flex: 0 0 190px;
            display: flex;
            justify-content: flex-end;
        }

        .badge-remision {
            border: 2px solid var(--border-green);
            border-radius: 6px;
            overflow: hidden;
            text-align: center;
            width: 180px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            background: #ffffff;
        }

        .badge-remision-title {
            background-color: var(--secondary);
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
            padding: 5px 0;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .badge-remision-number {
            color: #dc2626;
            font-weight: 700;
            font-size: 16px;
            padding: 4px 0;
            letter-spacing: 1px;
            background: #ffffff;
        }

        .badge-remision-page {
            background-color: var(--accent-blue);
            color: var(--primary);
            font-weight: 700;
            font-size: 10.5px;
            padding: 3px 0;
            letter-spacing: 1px;
            border-top: 1px solid var(--border-green);
            text-transform: uppercase;
        }

        /* Cuadrícula de Datos del Cliente */
        .customer-card {
            border: 1px solid var(--border-blue);
            border-radius: 3px;
            margin-bottom: 10px;
            overflow: hidden;
            font-size: 10.5px;
        }

        .grid-row {
            display: grid;
            border-bottom: 1px solid var(--border-blue);
        }

        .grid-row:last-child {
            border-bottom: none;
        }

        .row-four-cols {
            grid-template-columns: 170px 1fr 75px 170px;
        }

        .row-two-cols {
            grid-template-columns: 170px 1fr;
        }

        .cell-label {
            background-color: var(--accent-blue);
            color: var(--primary);
            font-weight: 700;
            padding: 4px 8px;
            border-right: 1px solid var(--border-blue);
            display: flex;
            align-items: center;
        }

        .cell-value {
            padding: 4px 8px;
            display: flex;
            align-items: center;
            color: #111827;
            word-break: break-word;
        }

        .border-r {
            border-right: 1px solid var(--border-blue);
        }

        /* Tabla de Insumos */
        .table-container {
            margin-bottom: 10px;
            overflow-x: auto;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10.5px;
            border: 1px solid var(--border-blue);
            background: #ffffff;
            table-layout: fixed;
        }

        .items-table thead tr {
            background-color: var(--primary);
            color: #ffffff;
        }

        .items-table th {
            padding: 4.5px 5px;
            font-weight: 700;
            border-right: 1px solid rgba(255, 255, 255, 0.25);
            font-size: 10px;
            letter-spacing: 0.3px;
        }

        .items-table th:last-child {
            border-right: none;
        }

        .items-table td {
            padding: 3.5px 5px;
            border-right: 1px solid var(--border-blue);
            border-bottom: 1px solid var(--border-light);
            color: #1f2937;
            height: 18.5px;
            line-height: 1.25;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .items-table td:last-child {
            border-right: none;
        }

        .items-table tr.total-row {
            background-color: var(--accent-green);
            color: var(--secondary);
            font-weight: 700;
            border-top: 2px solid var(--border-green);
        }

        .items-table tr.total-row td {
            border-bottom: none;
            padding: 4.5px 8px;
            white-space: normal;
        }

        .col-item { width: 42px; text-align: center; }
        .col-ref { width: 115px; text-align: left; }
        .col-desc { text-align: left; }
        .col-lote { width: 95px; text-align: center; }
        .col-venc { width: 90px; text-align: center; }
        .col-cant { width: 75px; text-align: center; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-muted { color: #9ca3af; }

        /* Observaciones */
        .obs-card {
            border: 1px solid var(--border-blue);
            border-radius: 3px;
            margin-bottom: 10px;
            font-size: 10.5px;
            overflow: hidden;
        }

        .obs-header {
            background-color: var(--accent-blue);
            color: var(--primary);
            font-weight: 700;
            padding: 3px 8px;
            border-bottom: 1px solid var(--border-blue);
            font-size: 10px;
        }

        .obs-body {
            min-height: 28px;
            max-height: 48px;
            overflow: hidden;
            padding: 4px 8px;
            color: #374151;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* Zona de Firmas */
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 10px;
            font-size: 10.5px;
        }

        .sig-box {
            border-radius: 3px;
            padding: 6px 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 78px;
            box-sizing: border-box;
        }

        .sig-box-delivery {
            border: 1px solid var(--border-blue);
            background-color: var(--accent-blue);
        }

        .sig-box-delivery .sig-title {
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .sig-box-delivery .sig-footer {
            border-top: 1px solid var(--border-blue);
            padding-top: 3px;
            margin-top: auto;
            color: var(--primary);
        }

        .sig-box-received {
            border: 1px solid var(--border-green);
            background-color: var(--accent-green);
        }

        .sig-box-received .sig-title {
            font-weight: 700;
            color: var(--secondary);
            margin-bottom: 4px;
        }

        .sig-box-received .sig-footer {
            border-top: 1px solid var(--border-green);
            padding-top: 3px;
            margin-top: auto;
            color: var(--secondary);
        }

        /* Pie de Página Legal */
        .doc-footer {
            text-align: center;
            font-size: 9px;
            color: #6b7280;
            line-height: 1.35;
            padding-top: 4px;
        }

        .doc-footer p {
            margin-bottom: 2px;
        }

        /* Ocultar barra de depuración de CodeIgniter si está presente */
        #debug-bar,
        #debug-icon,
        .ci-debug-toolbar,
        [id*="debugbar"] {
            display: none !important;
            visibility: hidden !important;
        }

        /* Reglas Estrictas de Impresión (Tamaño Carta) */
        @media print {
            html, body {
                background: #ffffff !important;
                padding: 0 !important;
                margin: 0 !important;
                font-size: 10pt !important;
            }

            .print-toolbar,
            .no-print,
            #debug-bar,
            #debug-icon,
            .ci-debug-toolbar,
            [id*="debugbar"] {
                display: none !important;
            }

            .sheet {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                margin: 0 !important;
                border-radius: 0 !important;
                page-break-after: always;
                break-after: page;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .sheet:last-child {
                page-break-after: auto;
                break-after: auto;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: letter portrait;
                margin: 8mm 10mm;
            }

            .customer-card,
            .table-container,
            .obs-card,
            .signatures-grid,
            .doc-header {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>

    <!-- BARRA SUPERIOR PARA VISUALIZACIÓN E IMPRESIÓN (OCULTA AL IMPRIMIR) -->
    <div class="print-toolbar no-print">
        <div class="doc-info">
            <span>📄 <?= esc($docTitle) ?> <?= $consecutivoFormatted ?></span>
            <?php if ($totalPages > 1): ?>
                <span style="font-size: 11px; font-weight: normal; color: #4b5563; margin-left: 8px;">(<?= $totalPages ?> Páginas &bull; 15 referencias por hoja)</span>
            <?php endif; ?>
        </div>
        <button id="btn_print_remision" class="btn-print-action" type="button">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Imprimir <?= esc($docTitle) ?>
        </button>
    </div>

    <!-- CICLO DE PÁGINAS (ESTRICTAMENTE 15 REFERENCIAS POR HOJA) -->
    <?php for ($pageIndex = 0; $pageIndex < $totalPages; $pageIndex++): 
        $pageStart = $pageIndex * $itemsPerPage;
        $pageItems = array_slice($itemsList, $pageStart, $itemsPerPage);
        $pageQty = 0;
        foreach ($pageItems as $pit) {
            $q = isset($pit['quiantity']) ? (int)$pit['quiantity'] : (isset($pit['quantity']) ? (int)$pit['quantity'] : 0);
            $pageQty += $q;
        }
        $currentPageNumber = $pageIndex + 1;
    ?>
    <!-- HOJA <?= $currentPageNumber ?> DE <?= $totalPages ?> -->
    <div class="sheet">

        <!-- ENCABEZADO DE LA EMPRESA Y DOCUMENTO -->
        <header class="doc-header">
            <!-- Logo Corporativo -->
            <div class="header-logo-col">
                <img src="<?= base_url('public/assets/img/logo_gms.png') ?>" alt="Grupo Monzant SAS Logo"
                    onerror="this.onerror=null; this.src='https://placehold.co/240x95/1E3A8A/ffffff?text=GRUPO+MONZANT+SAS&font=roboto';">
            </div>

            <!-- Datos de la Empresa -->
            <div class="header-company-col">
                <h1>GRUPO MONZANT SAS</h1>
                <p class="nit">NIT: 901949163</p>
                <p>Cali, Valle del Cauca, Colombia</p>
                <p>Tel: 3112238004</p>
                <p>Email: administracion@gmsuministros.com</p>
            </div>

            <!-- Consecutivo de Remisión y Enumerador de Página -->
            <div class="header-badge-col">
                <div class="badge-remision">
                    <div class="badge-remision-title">
                        <?= esc($docTitle) ?>
                    </div>
                    <div class="badge-remision-number">
                        <?= $consecutivoFormatted ?>
                    </div>
                    <div class="badge-remision-page">
                        PÁGINA <?= $currentPageNumber ?> DE <?= $totalPages ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- DATOS DEL CLIENTE / INSTITUCIÓN O ADMINISTRADOR -->
        <section class="customer-card">
            <!-- Fila 1: Cliente / Administrador y NIT/CC -->
            <div class="grid-row row-four-cols">
                <div class="cell-label"><?= $rawType === 'INGRESO' ? 'ADMINISTRADOR / BODEGA:' : ($rawType === 'INTERNO' ? 'RESPONSABLE / BODEGA DESTINO:' : 'CLIENTE / INSTITUCIÓN:') ?></div>
                <div class="cell-value border-r"><?= esc($dispatch['client'] ?? '') ?></div>
                <div class="cell-label">NIT/CC:</div>
                <div class="cell-value"><?= esc($dispatch['nit'] ?? '') ?></div>
            </div>
            <!-- Fila 2: Dirección y Ciudad -->
            <div class="grid-row row-four-cols">
                <div class="cell-label"><?= $rawType === 'INGRESO' ? 'DIRECCIÓN:' : 'DIRECCIÓN DE ENTREGA:' ?></div>
                <div class="cell-value border-r"><?= esc($dispatch['adress'] ?? '') ?></div>
                <div class="cell-label">CIUDAD:</div>
                <div class="cell-value"><?= esc($dispatch['city_name'] ?? '') ?></div>
            </div>
            <!-- Fila 3: Fecha de Elaboración -->
            <div class="grid-row row-two-cols">
                <div class="cell-label">FECHA DE ELABORACIÓN:</div>
                <div class="cell-value"><?= esc($fechaElab) ?></div>
            </div>
        </section>

        <!-- TABLA DE INSUMOS (EXACTAMENTE 15 REFERENCIAS POR PÁGINA) -->
        <section class="table-container">
            <table class="items-table">
                <thead>
                    <tr>
                        <th class="col-item">ITEM</th>
                        <th class="col-ref">REFERENCIA</th>
                        <th class="col-desc">DESCRIPCIÓN DE INSUMOS</th>
                        <th class="col-lote">LOTE</th>
                        <th class="col-venc">F. VENC.</th>
                        <th class="col-cant">CANTIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    for ($r = 0; $r < $itemsPerPage; $r++):
                        $hasData = isset($pageItems[$r]);
                        $itemNumber = $pageStart + $r + 1;
                        $qty = 0;
                        $ref = '';
                        $desc = '';
                        $batch = '';
                        $fVenc = '';

                        if ($hasData) {
                            $item = $pageItems[$r];
                            $qty = isset($item['quiantity']) ? (int)$item['quiantity'] : (isset($item['quantity']) ? (int)$item['quantity'] : 0);
                            $ref = $item['reference'] ?? '';
                            $desc = $item['description'] ?? '';
                            $batch = $item['batch'] ?? '';
                            if (!empty($item['expiration_date']) && $item['expiration_date'] !== '0000-00-00') {
                                $fVenc = date('Y-m-d', strtotime($item['expiration_date']));
                            }

                            // En todas las remisiones todas las líneas (referencia) eliminar lo que dice el estado
                            $desc = preg_replace('/\s*\[\s*Estado\s*:[^\]]*\]/i', '', $desc);

                            // En las referencias no usar [] sino '-'
                            $ref = trim(str_replace(['[', ']'], '', $ref));
                            if (!empty($ref)) {
                                $desc = preg_replace('/\s*\[\s*' . preg_quote($ref, '/') . '\s*\]/i', ' - ' . $ref, $desc);
                            }
                            $desc = preg_replace('/\s*\[(?!Lote\b)([^\]]+)\]/i', ' - $1', $desc);
                            $desc = trim($desc);
                        }
                    ?>
                        <tr>
                            <td class="col-item <?= !$hasData ? 'text-muted' : '' ?>"><?= $hasData ? $itemNumber : '&nbsp;' ?></td>
                            <td class="col-ref"><?= $hasData ? esc($ref) : '&nbsp;' ?></td>
                            <td class="col-desc"><?= $hasData ? esc($desc) : '&nbsp;' ?></td>
                            <td class="col-lote"><?= $hasData ? esc($batch) : '&nbsp;' ?></td>
                            <td class="col-venc"><?= $hasData ? esc($fVenc) : '&nbsp;' ?></td>
                            <td class="col-cant" style="font-weight: 500;"><?= ($hasData && $qty !== 0) ? esc((string)$qty) : '&nbsp;' ?></td>
                        </tr>
                    <?php endfor; ?>

                    <!-- Fila de Total -->
                    <tr class="total-row">
                        <td colspan="5" class="text-right" style="padding-right: 12px; text-transform: uppercase;">
                            <?php if ($totalPages > 1): ?>
                                <span>CANTIDAD PÁGINA <?= $currentPageNumber ?>: <strong><?= esc((string)$pageQty) ?></strong></span>
                                <span style="margin: 0 10px; opacity: 0.6;">|</span>
                                <span>TOTAL CANTIDAD REMISIÓN:</span>
                            <?php else: ?>
                                TOTAL CANTIDAD DE INSUMOS:
                            <?php endif; ?>
                        </td>
                        <td class="col-cant" style="font-size: 12px;">
                            <?= esc((string)$totalGlobalCantidad) ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- OBSERVACIONES -->
        <section class="obs-card">
            <div class="obs-header">OBSERVACIONES:</div>
            <div class="obs-body"><?= esc($dispatch['observation'] ?? '') ?></div>
        </section>

        <!-- ZONA DE FIRMAS -->
        <section class="signatures-grid">
            <!-- Entregado Por -->
            <div class="sig-box sig-box-delivery">
                <div class="sig-title"><?= $rawType === 'INGRESO' ? 'ENTREGADO POR / PROVEEDOR:' : 'ENTREGADO POR / DESPACHADOR:' ?></div>
                <div class="sig-footer">
                    <p style="font-weight: 600;">Nombre: <?= esc($dispatch['dispatcher_name'] ?? $dispatch['dispatcher'] ?? '') ?></p>
                    <p style="font-weight: 600; margin-top: 2px;">C.C. / NIT: <?= esc(!empty($dispatch['dispatcher_dni']) ? $dispatch['dispatcher_dni'] : '') ?></p>
                </div>
            </div>
            <!-- Recibido Por -->
            <div class="sig-box sig-box-received">
                <div class="sig-title"><?= $rawType === 'INGRESO' ? 'RECIBIDO POR (Administrador / Bodega):' : ($rawType === 'INTERNO' ? 'RECIBIDO POR (Responsable Bodega):' : 'RECIBIDO POR (Cliente / Institución):') ?></div>
                <div class="sig-footer">
                    <p style="font-weight: 600;">Nombre y Firma: <?= esc($dispatch['client'] ?? '') ?></p>
                    <p style="font-weight: 600; margin-top: 2px;">C.C. / NIT: <?= esc($dispatch['nit'] ?? '') ?></p>
                </div>
            </div>
        </section>

        <!-- PIE DE PÁGINA -->
        <footer class="doc-footer">
            <p>Favor revisar la mercancía/insumos antes de firmar. El presente documento es constancia de entrega y no constituye una factura de venta.</p>
            <p>Formato Controlado - Grupo Monzant SAS - V1 &bull; Página <?= $currentPageNumber ?> de <?= $totalPages ?></p>
        </footer>

    </div>
    <?php endfor; ?>

    <script>
        document.getElementById('btn_print_remision')?.addEventListener('click', function () {
            window.print();
        });
    </script>
</body>

</html>
