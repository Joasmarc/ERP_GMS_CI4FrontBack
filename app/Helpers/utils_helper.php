<?php

if (!function_exists('pad_right_zeros')) {
    // Rellenar ceros a la derecha
    function pad_right_zeros(string $inputString): string
    {
        $targetLength = 10;

        // 1.0 Inicializar interfaz y formatear cadena - Iniciar variable de interfaz
        $UTILS = [];
        // 1.1 Formatear cadena
        $UTILS['FORMATTED'] = str_pad($inputString, $targetLength, '0', STR_PAD_RIGHT);

        // 2.0 Retornar resultado
        return $UTILS['FORMATTED'];
    }
}
