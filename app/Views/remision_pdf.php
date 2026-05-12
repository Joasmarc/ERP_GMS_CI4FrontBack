<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formato de Remisión - Grupo Monzant SAS</title>
    <!-- Tailwind CSS para el diseño -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1E3A8A', // Azul corporativo (basado en el logo)
                        secondary: '#166534', // Verde corporativo
                        'accent-blue': '#EFF6FF', // Azul muy suave para celdas
                        'accent-green': '#F0FDF4', // Verde muy suave para celdas
                    }
                }
            }
        }
    </script>
    <style>
        /* Tipografía estándar y limpia para documentos contables/administrativos */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f3f4f6;
        }

        /* Optimizaciones estrictas para impresión (Tamaño Carta) */
        @media print {
            body {
                background-color: white !important;
                padding: 0 !important;
            }

            .print-shadow-none {
                box-shadow: none !important;
                border: none !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                margin: 1cm;
                size: letter;
            }
        }
    </style>
</head>

<body class="p-4 md:p-8 text-gray-900">

    <!-- Contenedor Principal (Hoja) -->
    <div class="max-w-4xl mx-auto bg-white p-8 shadow-lg print-shadow-none border border-gray-300">

        <!-- ENCABEZADO DE LA EMPRESA Y DOCUMENTO -->
        <header class="flex justify-between items-start mb-6">
            <!-- Logo -->
            <div class="w-1/3 flex justify-start items-start relative">
                <!-- Se usa absolute para que el logo pueda ser más grande sin empujar el contenido hacia abajo -->
                <img src="<?= base_url('public/assets/img/logo_gms.png') ?>" alt="Grupo Monzant SAS Logo" class="max-w-[260px] max-h-[120px] object-contain absolute top-[-10px] left-0"
                    onerror="this.src='https://placehold.co/260x120/1E3A8A/ffffff?text=LOGO+EMPRESA&font=roboto'">
            </div>

            <!-- Datos de la Empresa -->
            <div class="w-1/3 text-center text-xs space-y-1 text-gray-700">
                <h1 class="text-base font-bold uppercase tracking-wide text-primary">GRUPO MONZANT SAS</h1>
                <p class="font-bold">NIT: 901949163</p>
                <p>Cali, Valle del Cauca, Colombia</p>
                <p>Tel: 3112238004</p>
                <p>Email: administracion@gmsuministros.com</p>
            </div>

            <!-- Tipo de Documento y Consecutivo -->
            <div class="w-1/3 flex justify-end">
                <div class="border-2 border-secondary rounded-md overflow-hidden text-center w-48 shadow-sm">
                    <div
                        class="bg-secondary text-white font-bold py-1.5 text-sm border-b-2 border-secondary tracking-wider">
                        REMISIÓN
                    </div>
                    <div class="text-red-600 font-bold text-xl py-2 tracking-widest bg-white">
                        N° <?= esc(!empty($dispatch['city_code']) ? $dispatch['city_code'] : (isset($dispatch['city_name']) ? substr($dispatch['city_name'], 0, 3) : 'GM')) ?>-<?= str_pad(esc($dispatch['sequence']), 3, '0', STR_PAD_LEFT) ?>
                    </div>
                </div>
            </div>
        </header>

        <!-- DATOS DEL CLIENTE / INSTITUCIÓN (Estilo Cuadrícula Colombiana) -->
        <section class="border border-primary rounded-sm mb-4 text-xs">
            <!-- Fila 1 -->
            <div class="grid grid-cols-12 border-b border-primary">
                <div
                    class="col-span-3 bg-accent-blue text-primary font-bold p-1 border-r border-primary flex items-center">
                    CLIENTE / INSTITUCIÓN:</div>
                <div class="col-span-5 p-1 border-r border-primary flex items-center"><?= esc($dispatch['client']) ?></div> <!-- Espacio Cliente -->
                <div
                    class="col-span-1 bg-accent-blue text-primary font-bold p-1 border-r border-primary flex items-center">
                    NIT/CC:</div>
                <div class="col-span-3 p-1 flex items-center"><?= esc($dispatch['nit']) ?></div> <!-- Espacio NIT -->
            </div>
            <!-- Fila 2 -->
            <div class="grid grid-cols-12 border-b border-primary">
                <div
                    class="col-span-3 bg-accent-blue text-primary font-bold p-1 border-r border-primary flex items-center">
                    DIRECCIÓN DE ENTREGA:</div>
                <div class="col-span-5 p-1 border-r border-primary flex items-center"><?= esc($dispatch['adress']) ?></div> <!-- Espacio Dirección -->
                <div
                    class="col-span-1 bg-accent-blue text-primary font-bold p-1 border-r border-primary flex items-center">
                    CIUDAD:</div>
                <div class="col-span-3 p-1 flex items-center"><?= esc($dispatch['city_name'] ?? '') ?></div> <!-- Espacio Ciudad -->
            </div>
            <!-- Fila 3 -->
            <div class="grid grid-cols-12">
                <div
                    class="col-span-3 bg-accent-blue text-primary font-bold p-1 border-r border-primary flex items-center">
                    FECHA DE ELABORACIÓN:</div>
                <div class="col-span-3 p-1 border-r border-primary flex items-center"><?= esc(date('Y-m-d', strtotime($dispatch['created_at']))) ?></div> <!-- Espacio Fecha -->
                <div
                    class="col-span-3 bg-accent-blue text-primary font-bold p-1 border-r border-primary flex items-center">
                    CÓDIGO DE TRASLADO / ORDEN:</div>
                <div class="col-span-3 p-1 flex items-center"><?= esc($dispatch['transfer_code']) ?></div> <!-- Espacio Orden -->
            </div>
        </section>

        <!-- TABLA DE INSUMOS -->
        <section class="mb-4">
            <table class="w-full text-xs text-left border border-primary border-collapse">
                <thead>
                    <tr class="bg-primary text-white border-b-2 border-primary">
                        <th class="py-1 px-2 border-r border-primary/30 font-bold text-center w-10">ITEM</th>
                        <th class="py-1 px-2 border-r border-primary/30 font-bold w-28">REFERENCIA</th>
                        <th class="py-1 px-2 border-r border-primary/30 font-bold">DESCRIPCIÓN DE INSUMOS</th>
                        <th class="py-1 px-2 border-r border-primary/30 font-bold text-center w-24">LOTE</th>
                        <th class="py-1 px-2 border-r border-primary/30 font-bold text-center w-24">F. VENC.</th>
                        <th class="py-1 px-2 font-bold text-center w-20">CANTIDAD</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $totalCantidad = 0;
                        $rows = count($items);
                        $minRows = 12;
                        $iterRows = max($rows, $minRows);

                        for ($i = 0; $i < $iterRows; $i++):
                            $hasData = isset($items[$i]);
                            if ($hasData) {
                                $totalCantidad += $items[$i]['quiantity'];
                            }
                    ?>
                        <tr class="border-b border-gray-300">
                            <td class="py-2.5 px-2 border-r border-primary text-center text-gray-400"><?= $hasData ? ($i+1) : '' ?></td>
                            <td class="py-2.5 px-2 border-r border-primary"><?= $hasData ? esc($items[$i]['reference']) : '' ?></td>
                            <td class="py-2.5 px-2 border-r border-primary"><?= $hasData ? esc($items[$i]['description']) : '' ?></td>
                            <td class="py-2.5 px-2 border-r border-primary text-center"><?= $hasData ? esc($items[$i]['batch']) : '' ?></td>
                            <td class="py-2.5 px-2 border-r border-primary text-center"><?= $hasData && $items[$i]['expiration_date'] ? esc(date('Y-m-d', strtotime($items[$i]['expiration_date']))) : '' ?></td>
                            <td class="py-2.5 px-2 text-center font-medium"><?= $hasData ? esc($items[$i]['quiantity']) : '' ?></td>
                        </tr>
                    <?php endfor; ?>
                    
                    <!-- Fila de Total -->
                    <tr class="bg-accent-green border-t-2 border-secondary text-secondary">
                        <td colspan="5" class="py-1.5 px-3 border-r border-primary font-bold text-right uppercase">Total
                            Cantidad de Insumos:</td>
                        <td class="py-1.5 px-2 text-center font-bold text-base"><?= esc($totalCantidad) ?></td>
                    </tr>
                </tbody>
            </table>
        </section>

        <!-- SECCIÓN DE OBSERVACIONES Y FIRMAS -->
        <footer class="text-xs">
            <!-- Observaciones -->
            <div class="border border-primary rounded-sm mb-4">
                <div class="bg-accent-blue text-primary font-bold p-1 border-b border-primary">OBSERVACIONES:</div>
                <div class="h-10 p-2 text-gray-700">
                    <?= esc($dispatch['observation']) ?>
                </div>
            </div>

            <!-- Zona de Firmas -->
            <div class="grid grid-cols-2 gap-6">
                <!-- Entrega -->
                <div
                    class="border border-primary rounded-sm p-2 flex flex-col justify-between h-24 relative bg-accent-blue">
                    <div class="font-bold text-primary mb-2">ENTREGADO POR / DESPACHADOR:</div>
                    <div class="mt-auto border-t border-primary pt-1">
                        <p class="font-semibold text-primary">Nombre: <?= esc($dispatch['dispatcher']) ?></p>
                        <p class="font-semibold text-primary mt-1">C.C. / NIT:</p>
                    </div>
                </div>
                <!-- Recibe -->
                <div
                    class="border border-secondary rounded-sm p-2 flex flex-col justify-between h-24 relative bg-accent-green">
                    <div class="font-bold text-secondary mb-2">RECIBIDO POR (Cliente / Institución):</div>
                    <div class="mt-auto border-t border-secondary pt-1">
                        <p class="font-semibold text-secondary">Nombre y Firma:</p>
                        <p class="font-semibold text-secondary mt-1">C.C. / Fecha / Sello:</p>
                    </div>
                </div>
            </div>

            <!-- Pie de página legal/comercial -->
            <div class="text-center mt-4 text-[10px] text-gray-500 font-medium">
                <p>Favor revisar la mercancía/insumos antes de firmar. El presente documento es constancia de entrega y
                    no constituye una factura de venta.</p>
                <p>Formato Controlado - Grupo Monzant SAS - V1</p>
                <div class="mt-2 text-center">
                    <button class="px-4 py-2 bg-blue-600 text-white rounded print:hidden" onclick="window.print()">Imprimir Remisión</button>
                </div>
            </div>
        </footer>

    </div>

</body>

</html>
