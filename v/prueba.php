<?php
    $codigo = $_REQUEST['codigo'];
    $clave = $_REQUEST['clave'];

    $url = 'https://prueba-rup.rec.gov.py/IGPersonas/public/impresion-certificado-acta/' . $codigo . '/' . $clave;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);

    header('Content-Type: application/json');
    echo $result;
    // $obj = json_decode($result);

    // echo $obj->datosInscripto->nombres;
    // var_dump($obj);


