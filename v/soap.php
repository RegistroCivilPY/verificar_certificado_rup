<?php
  // if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
  // {
  //   die('ajax');
  // }
  // die('no ajax');

  $errores = array();
  if(isset($_REQUEST['enviar'])) {
      // Requerimos el codigo:
      if (empty($_REQUEST["codigo"])) {
          $errores[] = "La URL de consulta no es correcta";
      } else {
          $codigo = intval($_REQUEST["codigo"]);
          // Queremos que el codigo solo tenga numeros
          if (!preg_match("/^[0-9]+/", $codigo)) {
              $errores[] = "Solo se permiten números como codigo de consulta";
          }
      }
      // Requerimos el codigo:
      if (empty($_REQUEST["seguridad"])) {
          $errores[] = "El codigo de seguridad es requerido";
      } else {
          $seguridad = $_REQUEST["seguridad"];
          // Queremos que el codigo solo tenga letras
          if (!preg_match("/^[a-zA-Z0-9]+/", $seguridad)) {
              $errores[] = "Solo se permiten letras y números en el codigo de seguridad";
          }
      }

      if(empty($errores)) {

        if (extension_loaded('soap')) {
          $client = new SoapClient("http://10.0.1.211:7010/CertificadosRUP/RegistroCivilService?wsdl");
          // $client = new SoapClient("http://10.0.1.211:7003/Certificados/ws?wsdl");
          $params = array(
            "arg0" => $codigo,
            "arg1" => $seguridad
          );
          $response = $client->__soapCall("verificarImpresionCertificadoNacimiento", array($params));
          // echo $response;
          // var_dump($response);
          $data =  $response->return;
        }

      } else {
          $data = $errores;
      }
  }


  header('Content-Type: application/json');
  echo json_encode($data);
?>
