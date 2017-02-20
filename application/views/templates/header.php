<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Indice de Precios al por Mayor</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?= base_url() ?>bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Dialog CSS -->
    <link href="<?= base_url() ?>bower_components/bootstrap/dist/css/bootstrap-dialog.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?= base_url() ?>bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?= base_url() ?>bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="<?= base_url() ?>bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?= base_url() ?>dist/css/sb-admin-2.css" rel="stylesheet">
    
    <!-- Select2 -->
    <link href="<?= base_url() ?>dist/css/select2.css" rel="stylesheet">
    <link href="<?= base_url() ?>dist/css/select2-bootstrap.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?= base_url() ?>bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script type="text/javascript">
        function login() {
            BootstrapDialog.show({
                title: 'Iniciar Sessi&oacute;n',
                message: $('<div></div>').load('<?= site_url() ?>/inicio/login_form'),
                onshown: function() {
                    $('#login').focus();
                },
                buttons: [{
                    label: 'Aceptar',
                    hotkey: 13,
                    cssClass: 'btn-primary',
                    action: function() {
                        var $button = this;
                        $button.disable();
                        $.post('<?= site_url() ?>/Inicio/login', {
                            login: $('#login').val(),
                            pass: $('#pass').val()
                        }, function(result) {
                            if (result === 'Ok') {
                                window.location.href = window.location.href.split("#")[0];
                            } else {
                                alert(result);
                                $button.enable();
                            }
                        });
                    }
                }, {
                    label: 'Cancelar',
                    action: function(dialog) {
                        dialog.close();
                    }
                }]
            });
        }
    </script>
    
    <link rel="shortcut icon" href="<?= base_url() ?>/img/favicon.ico" />
</head>

<body onload="init()">

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <img class="navbar-brand" style="padding-right: 0px; padding-top: 8px; padding-bottom: 8px; padding-left: 8px" src="<?= base_url() ?>/img/ine2.png"/>
                <a class="navbar-brand" href="<?= site_url() ?>">Indice de Precios al por Mayor</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-envelope fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-messages">
                        <li>
                            <a href="#">
                                <div>
                                    <strong>Administrador</strong>
                                    <span class="pull-right text-muted">
                                        <em>Bienvenido!</em>
                                    </span>
                                </div>
                                <div>Bienvenido al sistema del IPM</div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Ver todos los mensajes</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-messages -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-alerts">
                        <li>
                            <a href="#">
                                <div>
                                    <i class="fa fa-envelope fa-fw"></i> Mensaje enviado
                                    <span class="pull-right text-muted small">4 minutos atrás</span>
                                </div>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a class="text-center" href="#">
                                <strong>Ver todas las alertas</strong>
                                <i class="fa fa-angle-right"></i>
                            </a>
                        </li>
                    </ul>
                    <!-- /.dropdown-alerts -->
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li>
                            <?php if ($activo == true) {
                                echo '<a href="'.site_url().'/Inicio/usuario"><i class="fa fa-user fa-fw"></i> '.$nombre.'</a>';
                            } else {
                                echo '<a href="#" onclick="login()"><i class="fa fa-user fa-fw"></i> Iniciar sesión</a>';
                            } ?>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Opciones</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?= site_url() ?>/Inicio/cerrar"><i class="fa fa-sign-out fa-fw"></i> Salir</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <form class="input-group custom-search-form" method="POST" action="<?= base_url() ?>index.php/inicio/buscar">
                                <input name="patron" type="text" class="form-control" placeholder="Producto">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </form>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="<?= site_url() ?>/inicio/index"><i class="fa fa-book fa-fw"></i> Inicio</a>
                        </li>
                        <?php if (in_array('operativo', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-pencil fa-fw"></i> Operativo<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= site_url() ?>/operativo/asignar_mercados">Asig. Mercados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/asignar_comercializadoras">Asig. Comercializadoras</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/recorrido">Recorrido</a>
                                </li>
                                <?php if (in_array('revision', $permisos)) : ?>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/revision">Revisi&oacute;n</a>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/codigo">Codigo Activaci&oacute;n</a>
                                </li>
                                <?php if (in_array('upload', $permisos)) : ?>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/consolidacion_manual">Consolidaci&oacute;n</a>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/variacion?neg=0">Variaciones Positivas</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/variacion?neg=1">Variaciones Negativas</a>
                                </li>
                            </ul>
                            <!-- /.nav-second-level -->
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('mapa', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-globe fa-fw"></i> Mapas<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= site_url() ?>/mapa">Asig. Mercados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/mapa?t=2">Asig. Comercializadoras</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('reporte', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-info fa-fw"></i> Reportes<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php if (in_array('pendiente', $permisos)) : ?>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/pendientes">Pendientes</a>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/avance_mercados">Asignacion Agricolas</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/avance_comercializadoras">Asignacion Manufacturados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/agricolas_avance">Avance Agricolas</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/manufacturados_avance">Avance Manufacturados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/precios_agricolas">Agricolas</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/precios_manufacturados">Manufacturados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/monitoreo">Monitoreo</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/mayor_incidencia">Prod. Mayor Incidencia</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/catalogo">Catalogo</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/excel/directorio_mercados">Directorio Mercados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/excel/directorio_comercializadoras">Directorio Comercializadoras</a>
                                </li>
                                <?php if (in_array('justificacion', $permisos)) : ?>
                                <li>
                                    <a href="<?= site_url() ?>/operativo/justificacion">Justificacion</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('indice', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-calculator fa-fw"></i> Indice<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= site_url() ?>/reporte/calcular">Calcular</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/varios">Reporte</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/semanal">Semanal</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/encadenado">Encadenado</a>
                                </li>
                                <?php if (in_array('periodo', $permisos)) : ?>
                                <li>
                                    <a href="<?= site_url() ?>/reporte/aprobar">Aprobar</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('cuentas', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-signal fa-fw"></i> Cuentas Nacionales<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= site_url() ?>/cuentas/reporte">Promedio</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/cuentas/ind_reporte">Indice</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('documento', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-book fa-fw"></i> Documentos<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= base_url() ?>/docs/Directorio.docx">Directorio</a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>/docs/Ponderadores.xlsx">Ponderadores</a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>/docs/Clasificador.xlsx">Clasificador</a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>/docs/Manual.pdf">Manual</a>
                                </li>
                                <li>
                                    <a href="<?= base_url() ?>/docs/Normativa.pdf">Normativa</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('informante', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-male fa-fw"></i> Informante<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= site_url() ?>/canasta/pendientes">Pendientes</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/canasta/mercados">Mercados</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/canasta/comercializadoras">Comercializadoras</a>
                                </li>
                                <?php if (in_array('aprobar', $permisos)) : ?>
                                <li>
                                    <a href="<?= site_url() ?>/canasta/aprobar_agricolas">Aprobar Agricolas</a>
                                </li>
                                <li>
                                    <a href="<?= site_url() ?>/canasta/aprobar_manufacturados">Aprobar Manufacturados</a>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('usuario', $permisos)) : ?>
                        <li>
                            <a href="#"><i class="fa fa-user fa-fw"></i> Usuarios<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="<?= site_url() ?>/inicio/administrar_usuario">Administrar Usuarios</a>
                                </li>
                            </ul>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>