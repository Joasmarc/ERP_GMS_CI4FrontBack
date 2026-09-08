<?php

namespace App\Libraries;

/**
 * ExcelHelper
 *
 * Utilidad ligera y segura para:
 * 1. Generar archivos OpenXML (.xlsx) estándar de forma nativa mediante ZipArchive y XML.
 * 2. Parsear archivos .xlsx, .xls (XML Spreadsheet) y .csv subidos por los usuarios.
 */
class ExcelHelper
{
    /**
     * Generar plantilla .xlsx con encabezados y filas iniciales
     *
     * @param array $headers Arreglo asociativo o indexado de encabezados
     * @param array $rows Matriz con datos de filas prellenadas
     * @return string Contenido binario del archivo .xlsx
     */
    public static function generateXlsx(array $headers, array $rows): string
    {
        // Si ZipArchive está disponible, creamos un XLSX real
        if (class_exists(\ZipArchive::class)) {
            return self::buildXlsxArchive($headers, $rows);
        }

        // Fallback: Generar XML Spreadsheet 2003 compatible con cualquier versión de Excel
        return self::buildXmlSpreadsheet($headers, $rows);
    }

    /**
     * Construir archivo binario ZIP con el estándar OpenXML (.xlsx)
     */
    private static function buildXlsxArchive(array $headers, array $rows): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_tpl_');
        $zip = new \ZipArchive();

        if ($zip->open($tempFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return self::buildXmlSpreadsheet($headers, $rows);
        }

        // 1. [Content_Types].xml
        $contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
        $zip->addFromString('[Content_Types].xml', $contentTypes);

        // 2. _rels/.rels
        $rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
        $zip->addFromString('_rels/.rels', $rootRels);

        // 3. xl/_rels/workbook.xml.rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 4. xl/workbook.xml
        $workbook = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets>'
            . '<sheet name="Productos" sheetId="1" r:id="rId1"/>'
            . '</sheets>'
            . '</workbook>';
        $zip->addFromString('xl/workbook.xml', $workbook);

        // 5. xl/styles.xml (Estilos sencillos con formato de cabecera en negrita y fondo suave)
        $styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n"
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="2">'
            . '<font><sz val="11"/><name val="Calibri"/></font>'
            . '<font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>'
            . '</fonts>'
            . '<fills count="3">'
            . '<fill><patternFill patternType="none"/></fill>'
            . '<fill><patternFill patternType="gray125"/></fill>'
            . '<fill><patternFill patternType="solid"><fgColor rgb="FF2E7D32"/></patternFill></fill>' // Verde elegante
            . '</fills>'
            . '<borders count="1">'
            . '<border><left/><right/><top/><bottom/></border>'
            . '</borders>'
            . '<cellXfs count="2">'
            . '<xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/>'
            . '<xf numFmtId="0" fontId="1" fillId="2" borderId="0" xfId="0" applyFont="1" applyFill="1"/>'
            . '</cellXfs>'
            . '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styles);

        // 6. xl/worksheets/sheet1.xml
        $xmlWriter = new \XMLWriter();
        $xmlWriter->openMemory();
        $xmlWriter->startDocument('1.0', 'UTF-8');
        $xmlWriter->startElement('worksheet');
        $xmlWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');

        // Anchos de columna
        $xmlWriter->startElement('cols');
        $colWidths = [15, 45, 16, 18, 20, 24];
        foreach ($colWidths as $idx => $w) {
            $colNum = $idx + 1;
            $xmlWriter->startElement('col');
            $xmlWriter->writeAttribute('min', $colNum);
            $xmlWriter->writeAttribute('max', $colNum);
            $xmlWriter->writeAttribute('width', $w);
            $xmlWriter->writeAttribute('customWidth', '1');
            $xmlWriter->endElement();
        }
        $xmlWriter->endElement(); // cols

        $xmlWriter->startElement('sheetData');

        // Fila 1: Encabezados (con estilo s="1")
        $xmlWriter->startElement('row');
        $xmlWriter->writeAttribute('r', 1);
        foreach ($headers as $colIdx => $hText) {
            $cellRef = self::getColumnLetter($colIdx + 1) . '1';
            $xmlWriter->startElement('c');
            $xmlWriter->writeAttribute('r', $cellRef);
            $xmlWriter->writeAttribute('t', 'inlineStr');
            $xmlWriter->writeAttribute('s', 1);
            $xmlWriter->startElement('is');
            $xmlWriter->writeElement('t', (string)$hText);
            $xmlWriter->endElement(); // is
            $xmlWriter->endElement(); // c
        }
        $xmlWriter->endElement(); // row 1

        // Filas de datos
        $rowNum = 2;
        foreach ($rows as $rowData) {
            $xmlWriter->startElement('row');
            $xmlWriter->writeAttribute('r', $rowNum);

            foreach ($rowData as $colIdx => $val) {
                if ($val === null || $val === '') {
                    continue; // Celda vacía
                }
                $cellRef = self::getColumnLetter($colIdx + 1) . $rowNum;
                $xmlWriter->startElement('c');
                $xmlWriter->writeAttribute('r', $cellRef);

                if (is_numeric($val) && !preg_match('/^0[0-9]+/', (string)$val)) {
                    $xmlWriter->writeElement('v', (string)$val);
                } else {
                    $xmlWriter->writeAttribute('t', 'inlineStr');
                    $xmlWriter->startElement('is');
                    $xmlWriter->writeElement('t', (string)$val);
                    $xmlWriter->endElement(); // is
                }
                $xmlWriter->endElement(); // c
            }

            $xmlWriter->endElement(); // row
            $rowNum++;
        }

        $xmlWriter->endElement(); // sheetData
        $xmlWriter->endElement(); // worksheet
        $sheetXml = $xmlWriter->outputMemory();

        $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
        $zip->close();

        $content = file_get_contents($tempFile);
        @unlink($tempFile);

        return $content !== false ? $content : '';
    }

    /**
     * Fallback: XML Spreadsheet 2003 (.xls)
     */
    private static function buildXmlSpreadsheet(array $headers, array $rows): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n"
            . '<?mso-application progid="Excel.Sheet"?>' . "\n"
            . '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:o="urn:schemas-microsoft-com:office:office"' . "\n"
            . ' xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n"
            . ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n"
            . ' xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n"
            . '<Styles>' . "\n"
            . ' <Style ss:ID="HeaderStyle">' . "\n"
            . '  <Font ss:Bold="1" ss:Color="#FFFFFF"/>' . "\n"
            . '  <Interior ss:Color="#2E7D32" ss:Pattern="Solid"/>' . "\n"
            . ' </Style>' . "\n"
            . '</Styles>' . "\n"
            . '<Worksheet ss:Name="Productos">' . "\n"
            . '<Table>' . "\n";

        // Encabezados
        $xml .= '<Row ss:StyleID="HeaderStyle">' . "\n";
        foreach ($headers as $h) {
            $xml .= ' <Cell><Data ss:Type="String">' . htmlspecialchars((string)$h, ENT_XML1, 'UTF-8') . '</Data></Cell>' . "\n";
        }
        $xml .= '</Row>' . "\n";

        // Filas
        foreach ($rows as $r) {
            $xml .= '<Row>' . "\n";
            foreach ($r as $val) {
                $type = (is_numeric($val) && !preg_match('/^0[0-9]+/', (string)$val)) ? 'Number' : 'String';
                $safeVal = htmlspecialchars((string)$val, ENT_XML1, 'UTF-8');
                $xml .= ' <Cell><Data ss:Type="' . $type . '">' . $safeVal . '</Data></Cell>' . "\n";
            }
            $xml .= '</Row>' . "\n";
        }

        $xml .= '</Table>' . "\n"
            . '</Worksheet>' . "\n"
            . '</Workbook>';

        return $xml;
    }

    /**
     * Parsear archivo subido (.xlsx, .xls o .csv)
     *
     * @param string $filePath Ruta absoluta al archivo temporal
     * @param string $originalName Nombre original del archivo para detección
     * @return array Arreglo con filas leídas, cada fila indexada numéricamente [0, 1, 2, ...]
     */
    public static function parseFile(string $filePath, string $originalName = ''): array
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException('El archivo subido no existe o no se puede leer.');
        }

        // Detectar si es un archivo ZIP (XLSX)
        $handle = fopen($filePath, 'rb');
        $header = fread($handle, 4);
        fclose($handle);

        $isZip = ($header === "PK\x03\x04");

        if ($isZip && class_exists(\ZipArchive::class)) {
            return self::parseXlsx($filePath);
        }

        // Revisar si es XML Spreadsheet 2003
        $sample = file_get_contents($filePath, false, null, 0, 500);
        if (strpos($sample, 'urn:schemas-microsoft-com:office:spreadsheet') !== false) {
            return self::parseXmlSpreadsheet($filePath);
        }

        // En otro caso, intentar parsear como CSV
        return self::parseCsv($filePath);
    }

    /**
     * Parsear archivo .xlsx extrayendo de sheet1.xml y sharedStrings.xml
     */
    private static function parseXlsx(string $filePath): array
    {
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            throw new \RuntimeException('No fue posible abrir el archivo XLSX como un archivo comprimido válido.');
        }

        // 1. Cargar sharedStrings.xml si existe
        $sharedStrings = [];
        $sharedXmlContent = $zip->getFromName('xl/sharedStrings.xml');
        if ($sharedXmlContent !== false) {
            $xml = @simplexml_load_string($sharedXmlContent);
            if ($xml) {
                foreach ($xml->si as $si) {
                    if (isset($si->t)) {
                        $sharedStrings[] = (string)$si->t;
                    } elseif (isset($si->r)) {
                        // String enriquecido compuesto por múltiples etiquetas <r><t>
                        $fullText = '';
                        foreach ($si->r as $r) {
                            $fullText .= (string)$r->t;
                        }
                        $sharedStrings[] = $fullText;
                    } else {
                        $sharedStrings[] = '';
                    }
                }
            }
        }

        // 2. Cargar sheet1.xml
        $sheetXmlContent = $zip->getFromName('xl/worksheets/sheet1.xml');
        if ($sheetXmlContent === false) {
            // Intentar buscar la primera hoja disponible
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $stat = $zip->statIndex($i);
                if (strpos($stat['name'], 'xl/worksheets/sheet') === 0 && substr($stat['name'], -4) === '.xml') {
                    $sheetXmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
        }

        $zip->close();

        if ($sheetXmlContent === false) {
            throw new \RuntimeException('No se encontró ninguna hoja de cálculo dentro del archivo XLSX.');
        }

        $xml = @simplexml_load_string($sheetXmlContent);
        if (!$xml || !isset($xml->sheetData)) {
            throw new \RuntimeException('El contenido de la hoja de cálculo XLSX tiene un formato inválido.');
        }

        $rows = [];
        foreach ($xml->sheetData->row as $rowElement) {
            $rowNum = (int)$rowElement['r'];
            $rowData = [];

            foreach ($rowElement->c as $cell) {
                $ref = (string)$cell['r'];
                $colLetters = preg_replace('/[0-9]/', '', $ref);
                $colIndex = self::getColumnNumber($colLetters) - 1;

                $type = isset($cell['t']) ? (string)$cell['t'] : '';
                $val = '';

                if ($type === 's') {
                    // Shared string index
                    $idx = (int)$cell->v;
                    $val = $sharedStrings[$idx] ?? '';
                } elseif ($type === 'inlineStr' && isset($cell->is->t)) {
                    $val = (string)$cell->is->t;
                } elseif ($type === 'inlineStr' && isset($cell->is->r)) {
                    $fullText = '';
                    foreach ($cell->is->r as $r) {
                        $fullText .= (string)$r->t;
                    }
                    $val = $fullText;
                } elseif (isset($cell->v)) {
                    $val = (string)$cell->v;
                }

                $rowData[$colIndex] = trim($val);
            }

            if (!empty($rowData)) {
                // Rellenar índices faltantes hasta la columna máxima
                $maxIndex = max(array_keys($rowData));
                $normalizedRow = [];
                for ($k = 0; $k <= $maxIndex; $k++) {
                    $normalizedRow[$k] = $rowData[$k] ?? '';
                }
                $rows[$rowNum] = $normalizedRow;
            }
        }

        // Ordenar por número de fila
        ksort($rows);
        return array_values($rows);
    }

    /**
     * Parsear XML Spreadsheet 2003
     */
    private static function parseXmlSpreadsheet(string $filePath): array
    {
        $content = file_get_contents($filePath);
        $xml = @simplexml_load_string($content);
        if (!$xml) {
            throw new \RuntimeException('No se pudo leer el archivo XML de Excel.');
        }

        $rows = [];
        // Registrar namespace para XPath
        $xml->registerXPathNamespace('ss', 'urn:schemas-microsoft-com:office:spreadsheet');
        $worksheet = $xml->xpath('//ss:Worksheet[1]//ss:Table//ss:Row');

        if ($worksheet) {
            foreach ($worksheet as $rowElement) {
                $rowData = [];
                $cells = $rowElement->xpath('ss:Cell');
                $colIndex = 0;
                foreach ($cells as $cell) {
                    $attrs = $cell->attributes('urn:schemas-microsoft-com:office:spreadsheet');
                    if (isset($attrs['Index'])) {
                        $colIndex = (int)$attrs['Index'] - 1;
                    }
                    $data = $cell->xpath('ss:Data');
                    $val = isset($data[0]) ? (string)$data[0] : '';
                    $rowData[$colIndex] = trim($val);
                    $colIndex++;
                }

                if (!empty($rowData)) {
                    $maxIdx = max(array_keys($rowData));
                    $normalized = [];
                    for ($k = 0; $k <= $maxIdx; $k++) {
                        $normalized[$k] = $rowData[$k] ?? '';
                    }
                    $rows[] = $normalized;
                }
            }
        }

        return $rows;
    }

    /**
     * Parsear archivo CSV (soporta UTF-8 BOM, comas o punto y coma)
     */
    private static function parseCsv(string $filePath): array
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new \RuntimeException('No se pudo abrir el archivo CSV.');
        }

        // Leer primera línea para detectar delimitador y limpiar BOM
        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return [];
        }

        // Remover BOM si está presente
        if (strpos($firstLine, "\xEF\xBB\xBF") === 0) {
            $firstLine = substr($firstLine, 3);
        }

        $commaCount = substr_count($firstLine, ',');
        $semiCount = substr_count($firstLine, ';');
        $delimiter = ($semiCount > $commaCount) ? ';' : ',';

        rewind($handle);

        $rows = [];
        $isFirst = true;

        while (($data = fgetcsv($handle, 4096, $delimiter)) !== false) {
            if ($isFirst) {
                // Limpiar BOM en el primer elemento de la primera fila
                if (isset($data[0]) && strpos($data[0], "\xEF\xBB\xBF") === 0) {
                    $data[0] = substr($data[0], 3);
                }
                $isFirst = false;
            }

            $trimmed = array_map('trim', $data);
            // Ignorar filas totalmente vacías
            if (count(array_filter($trimmed, fn($v) => $v !== '')) > 0) {
                $rows[] = $trimmed;
            }
        }

        fclose($handle);
        return $rows;
    }

    /**
     * Convertir número de columna a letra (1 -> A, 2 -> B, ..., 26 -> Z, 27 -> AA)
     */
    public static function getColumnLetter(int $colNumber): string
    {
        $letter = '';
        while ($colNumber > 0) {
            $modulo = ($colNumber - 1) % 26;
            $letter = chr(65 + $modulo) . $letter;
            $colNumber = (int)(($colNumber - $modulo) / 26);
        }
        return $letter;
    }

    /**
     * Convertir letra de columna a número (A -> 1, B -> 2, ..., Z -> 26, AA -> 27)
     */
    public static function getColumnNumber(string $colLetter): int
    {
        $colLetter = strtoupper(trim($colLetter));
        $len = strlen($colLetter);
        $num = 0;
        for ($i = 0; $i < $len; $i++) {
            $num = $num * 26 + (ord($colLetter[$i]) - 64);
        }
        return $num;
    }
}
