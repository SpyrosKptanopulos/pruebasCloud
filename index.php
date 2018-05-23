<?php
include 'php_function/PureCloudClass.php';
$clientID = "6ffdb5d0-1ce1-4f47-8f31-137e761ab70b";
$secretID = "2K8JYGbUMa2_okrjfTEk8Pd91lDBtW_c7u86Y50GKLI";
$pureCloudClass = new PureCloudClass;
$token = $pureCloudClass->getToken($clientID, $secretID);
$getQueue = $pureCloudClass->getQueues($token);

?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Gestor de Campañas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" integrity="sha512-dTfge/zgoMYpP7QbHy4gWMEGsbsdZeCXz7irItjcC3sPUFtf0kuFbDz/ixG7ArTxmDjLXDmezHubeNikyKGVyQ=="
            crossorigin="anonymous">
        <link rel="stylesheet" href="css/main.css">
        <!--<link rel="stylesheet" href="css/editing.css">
        <link rel="stylesheet" href="css/reset.css">
        <link rel="stylesheet" href="css/styles.css">-->
        <script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/bootstrap-filestyle.min.js">
        </script>
        <script type="text/javascript" src="js/main.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#submit').bind("click", function () {
                    var imgVal = $('#fileToUpload').val();
                    if (imgVal == '') {
                        $("#getCode").html("<p>Tiene que cargar una lista de contacto.</p>");
                        $("#TitleErr").html('<h4 id="TitleErr" class="modal-title">Error!</h4>');
                        $("#myModal").modal('show');
                        //alert("");
                        return false;
                    }
                });
            });
        </script>
    </head>

    <body data-spy="scroll" data-target="#mobile-bar" data-offset="50">
        <header class="fixed">
            <!-- menu -->
            <div class="row">

                <!-- menu-bar -->
                <nav class="navbar navbar-default">

                    <div class="total-width">

                        <div class="navbar-header">
                            <a href="#">
                                <img class="logo-menu" src="img/logo-movigoo@2x.png" alt="">
                            </a>
                        </div>
                    </div>

                </nav>
                <!-- menu-bar -->
            </div>
            <!--end menu -->
        </header>
        <div id="contact" class="footer wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <form id="myform" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="name1" class="col-sm-2 control-label">Nombre Campaña:</label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control inputstl" id="nomCampaña" name="nomCampaña" placeholder="Nombre de la Campaña" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gender1" class="col-sm-2 control-label">Modo de marcado:</label>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-lg" name="modMarcado" id="modMarcado" required>
                                        <option value='' selected disabled hidden>Modo Discado</option>
                                        <option value='agentless'>Marcado sin agente </option>
                                        <option value='preview'> Marcado de vista previa</option>
                                        <option value='power'>Marcado Inteligente </option>
                                        <option value='predictive'> Marcado Predictivo</option>
                                        <option value='progressive'>Marcado Progresivo </option>
                                    </select>

                                </div>
                            </div>
                            <div class="form-group hidden-xs hidden-md hidden-lg hidden-sm">
                                <label for="gender1" class="col-sm-2 control-label">Modo de marcado:</label>
                                <div class="col-sm-5">
                                    <div class="onoffswitch">
                                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="myonoffswitch" checked>
                                        <label class="onoffswitch-label" for="myonoffswitch">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address1" class="col-sm-2 control-label">Selecciona Cola:</label>
                                <div class="col-sm-5">
                                    <select class="form-control form-control-lg" name="Queue" id="Queue" required>
                                        <option value="" selected disabled hidden>Cola</option>
                                        <?php
                                            for ($i = 0; $i < count($getQueue); $i++) {
                                                for ($k = 0; $k < count($getQueue[$i]->entities); $k++) {
                                                    # code...
                                                    echo '<option value=' . $getQueue[$i]->entities[$k]->id . '>' . $getQueue[$i]->entities[$k]->name . '</option>';

                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address1" class="col-sm-2 control-label">Cargar Lista de Contacto:</label>
                                <div class="col-sm-5">
                                    <input type="file" name="fileToUpload" id="fileToUpload" accept=".csv">
                                </div>
                            </div>
                            <div id="wrapper" class="form-group">
                                <input type="submit" value="Crear Campaña" name="submit" id="submit">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 id="TitleErr" class="modal-title"></h4>
                    </div>
                    <div id="getCode" class="modal-body">
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>

            </div>
        </div>
        <div class="statusMsg" name="statusMsg" id="statusMsg"></div>
        <div id="loading"></div>
        <script>
            $('#fileToUpload').filestyle({
                buttonName: 'btn-success',
                buttonText: ' Subir Archivo'
            });
            $('#loading').hide();
        </script>
    </body>

    </html>