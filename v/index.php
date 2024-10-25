<?php
  $tipos =  array('n' => 'Nacimiento', 'm' => 'Matrimonio', 'd' => 'Defunción');
  $tiposG =  array('n' => 'Kuatia Tekovereñói Ára Mboguapyha', 'm' => 'Kuatia Ñemenda Mboguapyha Rehegua', 'd' => 'Kuatia Ára Ñemano Mboguapyha Rehegua');
  $tiposE =  array('n' => 'Birth Certificate', 'm' => 'Marriage Certificate', 'd' => 'Certificate Of Death');
  $base_url =  'https://' . $_SERVER["HTTP_HOST"]  . '/v/';

  if (isset($_REQUEST['tipo']) && isset($_REQUEST['codigo'])) {
    $tipo =  $_REQUEST['tipo'];
    $codigo =  intval($_REQUEST['codigo']);
  } else {
    header('Content-Type: application/json');
    echo "{'La solicitud no es válida!'}";
    die();
  }

  if (isset($_POST['enviar']) && $_POST['enviar'] == true) {

    $seguridad =  $_REQUEST['certificadoForm'];
    if (isset($seguridad) && !is_null($seguridad)) {
      $errores = array();

      // Requerimos el codigo:
      if (empty($codigo)) {
          $errores[] = "La URL de consulta no es correcta";
      } else {
          $codigo = intval($codigo);
          // Queremos que el codigo solo tenga numeros
          if (!preg_match("/^[0-9]+/", $codigo)) {
              $errores[] = "Solo se permiten números como codigo de consulta";
          }
      }

      // Requerimos el codigo:
      if (empty($seguridad)) {
          $errores[] = "El codigo de seguridad es requerido";
      } else {
          $seguridad = $seguridad;
          // Queremos que el codigo solo tenga letras
          if (!preg_match("/^[a-zA-Z0-9]+/", $seguridad)) {
              $errores[] = "Solo se permiten letras y números en el codigo de seguridad";
          }
      }

      if (empty($errores)) {

        $url = 'https://rup.rec.gov.py/IGPersonas/public/impresion-certificado-acta/' . $codigo . '/' . $seguridad;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result);

      } else {

          $data = $errores;
      }

    } else {
      die('no envio certificado');
    }
  }

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Validación de Certificados - Registro del Estado Civil</title>
  <link rel="shortcut icon" type="image/png" href="<?php echo $base_url; ?>assets/img/favicon.png" />
  <link rel="shortcut icon" type="image/png" href="<?php echo $base_url; ?>assets/images/favicon.png" />
  <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>assets/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>assets/css/stylesheet-default.css" />
</head>

<body>
  <div id="main" dir="ltr" class="container">
    <div class="headerContainer">
      <div class="headerImageArea"><a href="http://registrocivil.gov.py/" target="_blank" class="headerImageLink">
            <img src="<?php echo $base_url; ?>assets/img/headerrup.png" title="Registro del Estado Civil" class="headerImage" /></a>
      </div>
    </div>

    <?php if (isset($data) && !isset($data->datos_validos)): ?>
      <?php if (isset($data->messages)): ?>
        <div class="row">
          <div class="col-6">
            <div class="alert alert-light text-center" role="alert">

                <em><?php echo $data->messages[0]; ?></em> - <strong><?php echo $data->messages[0]; ?></strong> - <em>Incorrect print number and/or password</em>

            </div>
          </div>
        </div>
      <?php else: ?> 

        <div class="row">
          <div class="col-6">
            <div class="alert alert-light text-center" role="alert">

                <em>Ko kuatia tekovereñói ha'e kuatiatee</em> - <strong>Este certificado es un documento válido</strong> - <em>This certificate is a valid document</em>

            </div>
          </div>
        </div>

        <p style="line-height:1">&nbsp;</p>

        <?php if ($data->datosGenerales->tipoActaEnum == 'NACIMIENTO') { ?>
        <div id="datosInscripto" width="100%">
          <table border="1" style-border="dotted;" width="100%">
            <tr>
              <td align="center" style="padding-top:4px;">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Oñembokuatiáva Rekovehai</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Datos del Inscripto</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Information of the registered</i></span>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td colspan="2" align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosInscripto->nombres; ?> <?php echo $data->datosInscripto->primerApellido; ?> <?php echo $data->datosInscripto->segundoApellido; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Téra ha Terajoapy</i> - <b>Nombres y Apellidos</b> - <i>Given Names and Surnames</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosInscripto->fechaNacimiento; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tekovereñói Ára</i> - <b>Fecha de Nacimiento</b> - <i>Date of Birth</i></span>
                      </td>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosInscripto->sexo; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Meña</i> - <b>Sexo</b> – <i>Gender</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosInscripto->distritoNacimiento; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tekovereñói Renda</i> - <b>Lugar de Nacimiento</b> - <i>Place of Birth</i></span>
                      </td>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosInscripto->departamentoNacimiento; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i> District / Department</i></span>
                      </td>

                      <tr>

                  </table>
                </div>
              </td>
              </tr>
              <tr>
              </tr>
          </table>
        </div>

        <p style="line-height:0.1">&nbsp;</p>

        <div id="datosFiliacion" width="100%">
          <table border="1" width="100%">
            <tr>
              <td style="padding-top:4px">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Sy ha Túva Rekovehai</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Datos de la Filiación</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Filiation Data</i></span>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td align="center" style="padding:8px 0;"  width="50%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosFiliacion->datosPadre->nombres) ? $data->datosFiliacion->datosPadre->nombres . ' ' . $data->datosFiliacion->datosPadre->primerApellido . ' ' . $data->datosFiliacion->datosPadre->segundoApellido : '----------- -----------'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Túva Réra ha Reraguapy</i> - <b>Nombres y Apellidos del Padre</b> - <i>Father’s Given Names and Surnames</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:50%" width="50%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosFiliacion->datosPadre->numeroDocumentoIdentidad) ?  $data->datosFiliacion->datosPadre->tipoDocumentoIdentidad . ' - ' . $data->datosFiliacion->datosPadre->numeroDocumentoIdentidad : '----------- - -----------'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Kuatiatee Papapy</i> – <b>Documento de Identidad</b> - I.D Number</span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0; width:50%" width="50%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosFiliacion->datosMadre->nombres) ? $data->datosFiliacion->datosMadre->nombres . ' ' . $data->datosFiliacion->datosMadre->primerApellido . ' ' .$data->datosFiliacion->datosMadre->segundoApellido : '----------- -----------'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Sy Réra ha Reraguapy</i> - <b>Nombres y Apellidos de la Madre</b> - <i>Mother’s Given Names and Surnames</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:50%" width="50%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosFiliacion->datosMadre->numeroDocumentoIdentidad) ? $data->datosFiliacion->datosMadre->tipoDocumentoIdentidad . ' - ' . $data->datosFiliacion->datosMadre->numeroDocumentoIdentidad : '----------- - -----------'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Kuatiatee Papapy</i> – <b>Documento de Identidad</b> - <i>I.D Number</i></span>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>

        <?php } elseif ($data->datosGenerales->tipoActaEnum == 'MATRIMONIO') { ?> 
        <div id="datosInscripto" width="100%">
          <table border="1" style-border="dotted;" width="100%">
            <tr>
              <td align="center" style="padding-top:4px;">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Omendáva Rekovehai</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Datos de los Contrayentes</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Spouses Information</i></span>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td align="center" style="padding:8px 0">
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Ména Rekovehai</i> - <b>Datos del Esposo</b> - <i>Husband's Information</i></span>
                      </td>
                      <td align="center" style="padding:8px 0">
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tembireko Rekovehai</i> - <b>Datos de la Esposa</b> – <i>Wife's Information</i></span>
                      </td>
                    </tr>

                    <tr>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovio->nombres; ?> <?php echo $data->datosContrayentes->datosNovio->primerApellido; ?> <?php echo $data->datosContrayentes->datosNovio->segundoApellido; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Téra ha Terajoapy</i> - <b>Nombres y Apellidos</b> - <i>Given Names and Surnames</i></span>
                      </td>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovia->nombres; ?> <?php echo $data->datosContrayentes->datosNovia->primerApellido; ?> <?php echo $data->datosContrayentes->datosNovia->segundoApellido; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Téra ha Terajoapy</i> - <b>Nombres y Apellidos</b> - <i>Given Names and Surnames</i></span>
                      </td>
                    </tr>

                    <tr>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovio->fechaNacimiento; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tekovereñói Ára</i> - <b>Fecha de Nacimiento</b> - <i>Date of Birth</i></span>
                      </td>
                      <td align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovia->fechaNacimiento; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tekovereñói Ára</i> - <b>Fecha de Nacimiento</b> - <i>Date of Birth</i></span>
                      </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:8px 0">
                          <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovio->distritoNacimiento; ?></b></span>
                          <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tekovereñói Renda</i> - <b>Lugar de Nacimiento</b> - <i>Place of Birth</i></span>
                        </td>
                        <td align="center" style="padding:8px 0">
                          <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovia->distritoNacimiento; ?></b></span>
                          <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Tekovereñói Renda</i> - <b>Lugar de Nacimiento</b> - <i>Place of Birth</i></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:8px 0">
                          <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovio->departamentoNacimiento; ?></b></span>
                          <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i> District / Department</i></span>
                        </td>
                        <td align="center" style="padding:8px 0">
                          <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovia->departamentoNacimiento; ?></b></span>
                          <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i> District / Department</i></span>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:8px 0">
                          <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovio->tipoDocumentoIdentidad; ?> - <?php echo $data->datosContrayentes->datosNovio->numeroDocumentoIdentidad; ?></b></span>
                          <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Kuatiatee Papapy</i> – <b>Documento de Identidad</b> - <i>I.D Number</i></span>
                        </td>
                        <td align="center" style="padding:8px 0">
                          <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosContrayentes->datosNovia->tipoDocumentoIdentidad; ?> - <?php echo $data->datosContrayentes->datosNovia->numeroDocumentoIdentidad; ?></b></span>
                          <span style="color:black;font-style: italic;font-size: 6pt; text-align:center;"><i>Kuatiatee Papapy</i> – <b>Documento de Identidad</b> - <i>I.D Number</i></span>
                        </td>
                    </tr>

                  </table>
                </div>
              </td>
              </tr>
              <tr>
              </tr>
          </table>
        </div>

        <?php } elseif ($data->datosGenerales->tipoActaEnum == 'DEFUNCION') { ?> 
          <div id="datosInscripto" width="100%">
          <table border="1" width="100%">
            <tr>
              <td align="center" style="padding-top:4px">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Omanóva Rekovekuehai</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Datos del Difunto</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Information of the Deceased</i></span>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosDifunto->nombres; ?> <?php echo $data->datosDifunto->primerApellido; ?> <?php echo $data->datosDifunto->segundoApellido; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Téra ha Terajoapy</i> - <b>Nombres y Apellidos</b> - <i>Given Names and Surnames</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->distritoNacimiento) ? $data->datosDifunto->distritoNacimiento . ' / ' . $data->datosDifunto->departamentoNacimiento : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Tekovereñói Renda</i> - <b>Lugar de Nacimiento</b> - <i>Place of Birth</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->fechaNacimiento) ? $data->datosDifunto->fechaNacimiento : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Tekovereñói Ára</i> - <b>Fecha de Nacimiento</b> - <i>Date of Birth</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->numeroDocumentoIdentidad) ? $data->datosDifunto->numeroDocumentoIdentidad : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Kuatiatee Papapy</i> – <b>Documento de Identidad</b> - <i>I.D Number</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->estadoCivil) ? $data->datosDifunto->estadoCivil : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Tapicha Rekotee</i> - <b>Estado Civil</b> - <i>Civil Status</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->nacionalidad) ? $data->datosDifunto->nacionalidad : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Tet&atilde;reko</i> - <b>Nacionalidad</b> - <i>Nationality</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->domicilio) ? $data->datosDifunto->domicilio : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Óga Renda</i> – <b>Domicilio</b> - <i>Home Address</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo $data->datosDifunto->distritoDomicilio; ?> / <?php echo $data->datosDifunto->departamentoDomicilio; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i> District / Department</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->sexo) ? $data->datosDifunto->sexo : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Meña</i> - <b>Sexo</b> – <i>Gender</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->distritoDefuncion) ? $data->datosDifunto->distritoDefuncion : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Ñemanoha Renda</i> – <b>Lugar de Fallecimiento</b> - <i>Place of Death</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->departamentoDefuncion) ? $data->datosDifunto->departamentoDefuncion : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i> District / Department</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosDifunto->fechaDefuncion) ? $data->datosDifunto->fechaDefuncion : 'N/D'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Ñemanoha Ára</i> - <b>Fecha de Fallecimiento</b> – <i>Date of Death</i></span>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>

        <?php } ?>

        <p style="line-height:0.1">&nbsp;</p>

        <div id="datosInscripcion" width="100%">
          <table border="1" width="100%">
            <tr>
              <td align="center" style="padding-top:4px">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Marandu Ñembokuatia Rehegua</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Datos de la Inscripción</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Registration Data</i></span>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td>
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><?php echo ($data->datosInscripcion->oficinaRegistral) ? $data->datosInscripcion->oficinaRegistral . ' N° ' . $data->datosInscripcion->numeroOficinaRegistral: '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Ñembokuatiaha Róga</i> - <b>Oficina Registral</b> - <i>Registry Office</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><?php echo ($data->datosInscripcion->distrito) ? $data->datosInscripcion->distrito . ' / ' . $data->datosInscripcion->departamento : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i>District / Department</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><?php echo ($data->datosInscripcion->fechaInscripcion) ? $data->datosInscripcion->fechaInscripcion : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Ára Oñembokuatiaha</i> - <b>Fecha de Inscripción</b> - <i>Date of Registration</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><?php echo ($data->datosInscripcion->numeroLibro) ? $data->datosInscripcion->numeroLibro : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Aranduka Papapy</i> - <b>Numero de Libro</b> - <i>Book Number</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><?php echo ($data->datosInscripcion->numeroActa) ? $data->datosInscripcion->numeroActa : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Kuatia Ñemboguapyha Papapy</i> - <b>Acta Numero</b> – <i>File Number</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><?php echo ($data->datosInscripcion->numeroFolio) ?  $data->datosInscripcion->numeroFolio : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Kuatiarogue Papapy</i> – <b>Numero Folio</b> - <i>Folio Number</i></span>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>

        <p style="line-height:0.1">&nbsp;</p>

        <div id="notasMarginales" width="100%">
          <table border="1" width="100%">
            <tr>
              <td align="center" style="padding-top:4px">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Jehaipy Jesarekor&atilde;</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Notas Marginales</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Marginal Notes</i></span>
                  </p>
                </div>
              </td>
            </tr>

            <tr>
              <td align="center">
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td align="center" style="padding:8px 0">
                        <?php echo ($data->notasMarginales) ?  $data->notasMarginales : '----';  ?>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>

        <p style="line-height:0.1">&nbsp;</p>

        <div id="datosExpedicion" width="100%">
          <table border="1" width="100%">
            <tr>
              <td align="center" style="padding-top:4px">
                <div style="text-align: center;">
                  <p>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"><i>Kuatia Oñeguenoheha Rehegua</i> - </span>
                    <span style="font-size: 10pt;font-weight: bold;margin-top:0;margin-bottom:0;"><b>Datos de Expedición</b></span>
                    <span style="font-size: 10pt;margin-top:0;margin-bottom:0;"> - <i>Issued Data</i></span>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td align="center">
                <div>
                  <table border="0" width="100%">
                    <tr>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosExpedicion->oficinaRegistral) ? $data->datosExpedicion->oficinaRegistral . ' N° ' . $data->datosExpedicion->numeroOficinaRegistral : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Ñembokuatiaha Róga</i> - <b>Oficina Registral</b> - <i>Registry Office</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosExpedicion->distrito) ? $data->datosExpedicion->distrito . ' / ' . $data->datosExpedicion->departamento : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Táva/Tavusu</i> - <b>Distrito / Departamento</b> - <i>District / Department</i></span>
                      </td>
                      <td align="center" style="padding:8px 0; width:33.33%">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosExpedicion->fecha) ? $data->datosExpedicion->fecha : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Ñeme’eha Ára</i> - <b>Fecha de Emisión</b> - <i>Date of Issue</i></span>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3" align="center" style="padding:8px 0">
                        <span style="display:block; text-align:center; margin:0 auto; font-weight: bold;font-size: 10pt;"><b><?php echo ($data->datosExpedicion->oficial) ? $data->datosExpedicion->oficial : '--'; ?></b></span>
                        <span style="color:black;font-style: italic;font-size: 6pt;"><i>Mba'apohára ipokatúva</i> - <span style="font-weight: bold;"><b><i>Funcionario Autorizado</i></b> </span> - <i>Authorized Employee</i></span>
                      </td>
                    </tr>
                  </table>
                </div>
              </td>
            </tr>
          </table>
        </div>
      <?php endif; ?>   
    <?php else: ?>

      <div class="row">

        <div id="mainContainer" class="mainContainer col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-8 col-lg-offset-2">
          <form id="basicPasswordForm" name="basicPasswordForm" method="post" action="" class="validacionForm form-horizontal" enctype="application/x-www-form-urlencoded">
            <div class="validacionContainer col-xs-12 col-lg-8 col-lg-offset-2">
              <div class="validacionPromptArea text-center">

                <span class="validacionPromptText">
                  <small style="font-size: 11px;"><em>Kuatiatee <?php echo $tiposG[$tipo]; ?></em></small><br>
                  <strong>Validar Certificado de Acta de <?php echo $tipos[$tipo]; ?></strong><br>
                  <small style="font-size: 11px;"><em>Validate <?php echo $tiposE[$tipo]; ?></em></small>
                </span>
              </div>

              <div class="validacionContent" style="text-align:center;">
                <?php if (isset($data->datos_validos) && $data->datos_validos ==  false): ?>
                <div class="alert alert-danger" role="alert">
                  El certificado o los datos ingresados no son válidos! <br>
                  <small>Verifique si ingreso correctamente la URL y/o el código de SEGURIDAD</small>
                </div>
                <?php endif;  ?>
                <div class="validacionFormFields">
                  <div class="validacionUsernameField form-group" style="text-align:center;">
                    <label for="certificadoForm" class="validacionUsernameText">Introduzca código de Seguridad:</label>
                    <input id="certificadoForm" type="text" name="certificadoForm" v-model="seguridad" class="validacionUsernameInputText form-control col-sm-8 col-md-8 col-lg-8 text-center" />
                  </div>
                </div>

                <div class="validacionFormButtons row text-center col-lg-8 col-lg-offset-2">
                  <input type="hidden" name="tipo" value="<?php echo $tipo; ?>">
                  <input type="hidden" name="codigo" value="<?php echo $codigo; ?>">
                  <input type="hidden" name="enviar" value="true">
                  <input id="basicPasswordForm-submitButton" type="submit" name="basicPasswordForm-submitButton" value="Enviar" class="validacionSubmitButton btn btn-default col-sm-5 col-xs-12" />
                  <!-- <input id="basicPasswordForm-cancelButton" type="submit" name="basicPasswordForm-cancelButton" value="Cancelar" class="validacionCancelButton btn btn-default col-sm-5 col-xs-12" /> -->
                </div>
              </div>
            </div>

          </form>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <p style="line-height:4.0">&nbsp;</p>
  <div class="footerContainer col-xs-12">
    <div class="poweredByArea col-xs-12">&copy; 2018 <?php echo (date("Y") > '2018') ? ' - ' . date("Y") : ''; ?> <span class="poweredByText"> <a href="http://registrocivil.gov.py" target="_blank">Registro del Estado Civil</a></span>
    </div>
  </div>
  <p style="line-height:4.0">&nbsp;</p>


</body>

</html>
