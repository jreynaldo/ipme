<script type="text/javascript">
    function clasificacion() {
        if ($('input:radio[name = inf]:checked').val() == 2) {
            if ($('input:radio[name = sec]:checked').val() >= 7 && $('input:radio[name = sec]:checked').val() <= 9) {
                $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cla" value="1">Nivel 1</label></div></div><div class="checkbox"><label><input type="checkbox" name="cla" value="2">Nivel 2</label></div></div><div class="checkbox"><label><input type="checkbox" name="cla" value="3">Nivel 3</label></div></div><div class="checkbox"><label><input type="checkbox" name="cla" value="4">Nivel 4</label></div></div><div class="checkbox"><label><input type="checkbox" name="cla" value="5">Nivel 5</label></div></div>');
                $('#pcuadro').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Indice</label></div><div>&nbsp;</div><div><button class="btn btn-primary" type="button" onclick="indice()"><i class="icon-download-alt"></i> Indice</button></div>');
            } else {
                if ($('input:radio[name = sec]:checked').val() >= 11 && $('input:radio[name = sec]:checked').val() <= 13) {
                    $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div class="checkbox"><label><input type="checkbox" name="cla" value="2">División</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="3">Grupo</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="4">Clase</label></div></div>');
                    $('#pcuadro').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Indice</label></div><div>&nbsp;</div><div><button class="btn btn-primary" type="button" onclick="indice()"><i class="icon-download-alt"></i> Indice</button></div>');
                } else {
                    if ($('input:radio[name = sec]:checked').val() > 3) {
                        $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cla" value="1" checked="checked" disabled="disabled">General</label></div></div>');
                    } else {
                        $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cla" value="1">Sector</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="2">Divisi&oacute;n</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="4">Grupo</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="6">Subgrupo</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="8">Producto</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="10" checked="checked" onclick="productos2()">Variedad</label></div>');
                    }
                    $('#pcuadro').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Indice</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="2"/>&nbsp;Variación Mensual</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="3"/>&nbsp;Variación Interanual</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="4"/>&nbsp;Incidencia Mensual</label></div><div>&nbsp;</div><div><button class="btn btn-primary" type="button" onclick="indice()"><i class="icon-download-alt"></i> Indice</button></div>');
                }
            }
        }
    }
    function productos() {
        var sec = $('input:radio[name = sec]:checked').val();
        var cla = $('input:radio[name = cla]:checked').val();
        if (sec !== undefined && cla !== undefined) {
            $('#productos').html('Por favor espere...');
            $.post('productos', {
                sector: sec,
                clasificacion: cla
            },
            function (result) {
                $('#productos').html(result);
            });
        }
    }
    function informacion() {
        switch ($('input:radio[name = inf]:checked').val()) {
            case '1':
                $('#psector').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Sector</div><div>&nbsp;</div><a href="#pclasificacion" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="radio"><label><input type="radio" name="sec" value="1" onchange="productos()">Agricolas</label></div><div class="radio"><label><input type="radio" name="sec" value="1.1" onchange="productos()">Agricolas Nacional</label></div><div class="radio"><label><input type="radio" name="sec" value="1.2" onchange="productos()">Agricolas Importado</label></div><div class="radio"><label><input type="radio" name="sec" value="2" onchange="productos()">Manufacturados</label></div><div class="radio"><label><input type="radio" name="sec" value="2.1" onchange="productos()">Manufacturados Nacional</label></div><div class="radio"><label><input type="radio" name="sec" value="2.2" onchange="productos()">Manufacturados Importado</label></div><div class="radio"><label><input type="radio" name="sec" value="0.2" onchange="productos()">Importado</label></div>');
                $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="radio"><label><input type="radio" name="cla" value="1" onchange="productos()">Secci&oacute;n</label></div><div class="radio"><label><input type="radio" name="cla" value="2" onchange="productos()">Divisi&oacute;n</label></div><div class="radio"><label><input type="radio" name="cla" value="4" onchange="productos()">Grupo</label></div><div class="radio"><label><input type="radio" name="cla" value="6" onchange="productos()">Subgrupo</label></div><div class="radio"><label><input type="radio" name="cla" value="8" onchange="productos()">Producto</label></div><div class="radio"><label><input type="radio" name="cla" value="10" checked="checked" onchange="productos()">Variedad</label></div>');
                $('#pperiodicidad').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Periodicidad</div><div>&nbsp;</div><a href="#pperiodo" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="radio"><label><input type="radio" name="per" value="1" onchange="periodo()">Mensual</label></div><div class="radio"><label><input type="radio" name="per" value="2" onchange="periodo()">Bimensual</label></div><div class="radio"><label><input type="radio" name="per" value="3" onchange="periodo()">Trimensual</label></div><div class="radio"><label><input type="radio" name="per" value="6" onchange="periodo()">Semestral</label></div><div class="radio"><label><input type="radio" name="per" value="12" onchange="periodo()">Anual</label></div>');
                $('#pcuadro').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Por ciudades</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="2"/>&nbsp;Nacional</label></div><div>&nbsp;</div><div><button class="btn btn-primary" type="button" onclick="excel2()"><i class="icon-download-alt"></i> Promedio</button></div>');
                $('#productos').html('Seleccione el sector');
                periodo();
                break;
            case '2':
                $('#psector').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Sector</div><div>&nbsp;</div><a href="#pclasificacion" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="radio"><label><input type="radio" name="sec" value="1" onchange="clasificacion()">Agricolas</label></div><div class="radio"><label><input type="radio" name="sec" value="2" onchange="clasificacion()">Manufacturados</label></div><div class="radio"><label><input type="radio" name="sec" value="3" onchange="clasificacion()">Importado</label></div><div class="radio"><label><input type="radio" name="sec" value="4" onchange="clasificacion()">General</label></div><div class="radio"><label><input type="radio" name="sec" value="5" onchange="clasificacion()">General Nacional/Importado</label></div><div class="radio"><label><input type="radio" name="sec" value="6" onchange="clasificacion()">General Agricola/Manufacturado</label></div><div class="radio"><label><input type="radio" name="sec" value="10" onchange="clasificacion()">Alimentos/No alimentos</label></div><div class="radio"><label><input type="radio" name="sec" value="7" onchange="clasificacion()">Clasificación según CCP Nacional</label></div><div class="radio"><label><input type="radio" name="sec" value="8" onchange="clasificacion()">Clasificación según CCP Importado</label></div><div class="radio"><label><input type="radio" name="sec" value="9" onchange="clasificacion()">Clasificación según CCP General</label></div><div class="radio"><label><input type="radio" name="sec" value="11" onchange="clasificacion()">Clasificación según CIIU Nacional</label></div><div class="radio"><label><input type="radio" name="sec" value="12" onchange="clasificacion()">Clasificación según CIIU Importado</label></div><div class="radio"><label><input type="radio" name="sec" value="13" onchange="clasificacion()">Clasificación según CIIU General</label></div>');
                $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cla" value="1">Sector</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="2">Divisi&oacute;n</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="4">Grupo</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="6">Subgrupo</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="8">Producto</label></div><div class="checkbox"><label><input type="checkbox" name="cla" value="10" checked="checked" onclick="productos2()">Variedad</label></div>');
                $('#pperiodicidad').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Periodicidad</div><div>&nbsp;</div><a href="#pperiodo" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="radio"><label><input type="radio" name="per" value="1" checked="checked">Mensual</label></div>');
                $('#pcuadro').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Indice</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="2"/>&nbsp;Variación Mensual</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="3"/>&nbsp;Variación Interanual</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="4"/>&nbsp;Incidencia Mensual</label></div><div>&nbsp;</div><div><button class="btn btn-primary" type="button" onclick="indice()"><i class="icon-download-alt"></i> Indice</button></div>');
                $('#productos').html('No aplica al tipo de información.');
                periodo();
                break;
            case '3':
                $('#psector').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Sector</div><div>&nbsp;</div><a href="#pclasificacion" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div>No aplica al tipo de información.');
                $('#pclasificacion').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div><div>&nbsp;</div><a href="#pproductos" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div>No aplica al tipo de información.');
                $('#pperiodicidad').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Periodicidad</div><div>&nbsp;</div><a href="#pperiodo" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div class="radio"><label><input type="radio" name="per" value="1" checked="checked">Mensual</label></div>');
                $('#pcuadro').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div><div>&nbsp;</div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Incidencia positiva</label></div><div class="checkbox"><label><input type="checkbox" name="cuadro" value="2"/>&nbsp;Incidencia negativa</label></div><div>&nbsp;</div><div><button class="btn btn-primary" type="button" onclick="incidencia()"><i class="icon-download-alt"></i> Incidencia</button></div>');
                $('#productos').html('No aplica al tipo de información.');
                periodo2();
                break;
        }
    }
    function select() {
        $('input:checkbox[name=codigo]').prop('checked', true);
    }
    function unselect() {
        $('input:checkbox[name=codigo]').prop('checked', false);
    }
    function periodo() {
        $('#pperiodo').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Inicio</div><div>&nbsp;</div><a href="#pcuadro" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesini" onchange="gesini()" name="gesini"></select></div><div>&nbsp;</div><div><div style="width: 60px; text-align: right; float: left;">Periodo: &nbsp;</div><select id="perini" name="perini"></select></div><div>&nbsp;</div><div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Fin</div><div>&nbsp;</div><div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()" name="gesfin"></select></div><div>&nbsp;</div><div><div style="width: 60px; text-align: right; float: left;">Periodo: &nbsp;</div><select id="perfin" name="perfin"></select></div><div>&nbsp;</div>');
        if ($('input:radio[name = per]:checked').val() != undefined) {
            $.post('anio', {
                gestion: 2014
            },
            function (result) {
                $("#gesini").html(result);
                $("#gesini").trigger('change');
            });
        }
    }
    function periodo2() {
        $('#pperiodo').html('<div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Periodo</div><div>&nbsp;</div><a href="#pcuadro" data-toggle="tab" class="btn btn-primary">Siguiente</a><div>&nbsp;</div><div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()" name="gesfin"></select></div><div>&nbsp;</div><div><div style="width: 60px; text-align: right; float: left;">Periodo: &nbsp;</div><select id="perfin" name="perfin"></select></div><div>&nbsp;</div>');
        $.post('anio', {
            gestion: 2014
        },
        function (result) {
            $("#gesfin").html(result);
            $("#gesfin").val($("#gesfin option").last().val()).trigger('change');
        });
    }
    function gesini() {
        var per = $('input:radio[name = per]:checked').val();
        if (per !== undefined) {
            $.post('periodo', {
                tipo: per,
                gestion: $("#gesini").val()
            },
            function (result) {
                $("#perini").html(result);
                $("#perini").trigger('change');
            });
        } else {
            alert('Debe seleccionar la periodicidad.');
        }
        $.post('anio', {
            gestion: $("#gesini").val()
        },
        function (result) {
            $("#gesfin").html(result);
            $("#gesfin").val($("#gesfin option").last().val()).trigger('change');
        });
    }
    function gesfin() {
        var per = $('input:radio[name = per]:checked').val();
        if (per !== undefined) {
            $.post('periodo', {
                tipo: per,
                gestion: $("#gesfin").val()
            },
            function (result) {
                $("#perfin").html(result);
                $("#perfin").val($("#perfin option").last().val()).trigger('change');
            });
        } else {
            alert('Debe seleccionar la periodicidad.');
        }
    }
    function excel() {
        var sec = $('input:radio[name = sec]:checked').val();
        if (sec !== undefined) {
            var inf = $('input:radio[name = inf]:checked').val();
            var cla = $('input:radio[name = cla]:checked').val();
            var cods = '{';
            $('input:checkbox[name=codigo]:checked').each(function(i, val) {
                cods = cods + $(val).val() + ",";
            });
            if (cods.length > 1) {
                cods = cods.substr(0, cods.length -1) + '}';
                var per = $('input:radio[name = per]:checked').val();
                if (per !== undefined) {
                    var gesini = $('#gesini').val();
                    var perini = $('#perini').val();
                    var gesfin = $('#gesfin').val();
                    var perfin = $('#perfin').val();
                    if (gesini !== null && perini !== null && gesfin !== null && perfin !== null) {
                        post('reporte', {
                           sector: sec,
                           informacion: inf,
                           clasificacion: cla,
                           codigos: cods,
                           periodicidad: per,
                           gesini: gesini,
                           perini: perini,
                           gesfin: gesfin,
                           perfin: perfin
                        });
                    } else {
                        alert('Debe seleccionar el periodo (gestion).');
                    }
                } else {
                    alert('Debe seleccionar la periodicidad.');
                }
            } else {
                alert('Debe seleccionar al menos un producto.');
            }
        } else {
            alert('Debe seleccionar el sector.');
        }
    }
    function excel2() {
        var sec = $('input:radio[name = sec]:checked').val();
        if (sec !== undefined) {
            var inf = $('input:radio[name = inf]:checked').val();
            var cla = $('input:radio[name = cla]:checked').val();
            var cods = '{';
            $('input:checkbox[name=codigo]:checked').each(function(i, val) {
                cods = cods + $(val).val() + ",";
            });
            if (cods.length > 1) {
                cods = cods.substr(0, cods.length -1) + '}';
                var cuadros = '';
                $('input:checkbox[name=cuadro]:checked').each(function(i, val) {
                    cuadros = cuadros + $(val).val() + ",";
                });
                if (cuadros.length > 1) {
                    cuadros = cuadros.substr(0, cuadros.length -1);
                    var per = $('input:radio[name = per]:checked').val();
                    if (per !== undefined) {
                        var gesini = $('#gesini').val();
                        var perini = $('#perini').val();
                        var gesfin = $('#gesfin').val();
                        var perfin = $('#perfin').val();
                        if (gesini !== null && perini !== null && gesfin !== null && perfin !== null) {
                            post('imputado', {
                               sector: sec,
                               informacion: inf,
                               clasificacion: cla,
                               codigos: cods,
                               periodicidad: per,
                               gesini: gesini,
                               perini: perini,
                               gesfin: gesfin,
                               perfin: perfin,
                               cuadro: cuadros
                            });
                        } else {
                            alert('Debe seleccionar el periodo (gestion).');
                        }
                    } else {
                        alert('Debe seleccionar la periodicidad.');
                    }
                } else {
                    alert('Debe seleccionar al menos un cuadro de salida.');
                }
            } else {
                alert('Debe seleccionar al menos un producto.');
            }
        } else {
            alert('Debe seleccionar el sector.');
        }
    }
    function indice() {
        var sec = $('input:radio[name = sec]:checked').val();
        if (sec !== undefined) {
            var cla = '';
            $('input:checkbox[name = cla]:checked').each(function(i, val) {
                cla = cla + $(val).val() + ',';
            });
            if (cla.length > 1) {
                cla = cla.substr(0, cla.length - 1);
                var cuadros = '';
                $('input:checkbox[name = cuadro]:checked').each(function(i, val) {
                    cuadros = cuadros + $(val).val() + ",";
                });
                if (cuadros.length > 1) {
                    cuadros = cuadros.substr(0, cuadros.length - 1);
                    var gesini = $('#gesini').val();
                    var perini = $('#perini').val();
                    var gesfin = $('#gesfin').val();
                    var perfin = $('#perfin').val();
                    if (gesini !== null && perini !== null && gesfin !== null && perfin !== null) {
                        post('indice', {
                           sector: sec,
                           clasificacion: cla,
                           gesini: gesini,
                           perini: perini,
                           gesfin: gesfin,
                           perfin: perfin,
                           cuadro: cuadros
                        });
                    } else {
                        alert('Debe seleccionar el periodo (gestion).');
                    }
                } else {
                    alert('Debe seleccionar al menos un cuadro de salida.');
                }
            } else {
                alert('Debe seleccionar la clasificacion.');
            }
        } else {
            alert('Debe seleccionar el sector.');
        }
    }
    function incidencia() {
        var cuadros = '';
        $('input:checkbox[name = cuadro]:checked').each(function(i, val) {
            cuadros = cuadros + $(val).val() + ",";
        });
        if (cuadros.length > 1) {
            cuadros = cuadros.substr(0, cuadros.length - 1);
            var ges = $('#gesfin').val();
            var per = $('#perfin').val();
            if (ges !== null && per !== null) {
                post('incidencia', {
                   ges: ges,
                   per: per,
                   cuadro: cuadros
                });
            } else {
                alert('Debe seleccionar el periodo (gestion).');
            }
        } else {
            alert('Debe seleccionar al menos un cuadro de salida.');
        }
    }
    function init() {
        productos();
        $("#gesini").select2({width: 'resolve'});
        $("#perini").select2({width: 'resolve'});
        $("#gesfin").select2({width: 'resolve'});
        $("#perfin").select2({width: 'resolve'});
    };
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div>&nbsp;</div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix"><?= $title; ?></h4>
                        </div>
                        <div class="panel-body">
                            <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                                <li id="linformacion" class="active"><a style="color: gray;" href="#pinformacion" data-toggle="tab">Informaci&oacute;n</a></li>
                                <li id="lsector"><a style="color: gray;" href="#psector" data-toggle="tab">Sector</a></li>
                                <li id="lclasificacion"><a style="color: gray;" href="#pclasificacion" data-toggle="tab">Clasificaci&oacute;n</a></li>
                                <li id="lproductos"><a style="color: gray;" href="#pproductos" data-toggle="tab">Productos</a></li>
                                <li id="lperiodicidad"><a style="color: gray;" href="#pperiodicidad" data-toggle="tab">Periodicidad</a></li>
                                <li id="lperiodo"><a style="color: gray;" href="#pperiodo" data-toggle="tab">Periodo</a></li>
                                <li id="lcuadro"><a style="color: gray;" href="#pcuadro" data-toggle="tab">Cuadros</a></li>
                            </ul>
                            <div id="my-tab-content" class="tab-content">
                                <div class="tab-pane active" id="pinformacion">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Informaci&oacute;n</div>
                                    <div>&nbsp;</div>
                                    <a href="#psector" onclick="$('#linformacion').prop('class', ''); $('#lsector').prop('class', 'active');" data-toggle="tab" class="btn btn-primary">Siguiente</a>
                                    <div>&nbsp;</div>
                                    <div class="radio">
                                        <label><input type="radio" name="inf" value="1" checked="checked" onclick="informacion()">Promedio</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="inf" value="2" onclick="informacion()">Indice</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="inf" value="3" onclick="informacion()">Incidencia</label>
                                    </div>
                                </div>
                                <div class="tab-pane" id="psector">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Sector</div>
                                    <div>&nbsp;</div>
                                    <a href="#pclasificacion" onclick="$('#lsector').prop('class', ''); $('#lclasificacion').prop('class', 'active');" data-toggle="tab" class="btn btn-primary">Siguiente</a>
                                    <div>&nbsp;</div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="1" onchange="clasificacion(); productos();">Agricolas</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="1.1" onchange="clasificacion(); productos();">Agricolas Nacional</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="1.2" onchange="clasificacion(); productos();">Agricolas Importado</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="2" onchange="clasificacion(); productos();">Manufacturados</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="2.1" onchange="clasificacion(); productos();">Manufacturados Nacional</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="2.2" onchange="clasificacion(); productos();">Manufacturados Importado</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="sec" value="0.2" onchange="clasificacion(); productos();">Importado</label>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pclasificacion">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Clasificar por</div>
                                    <div>&nbsp;</div>
                                    <a href="#pproductos" onclick="$('#lclasificacion').prop('class', ''); $('#lproductos').prop('class', 'active');" data-toggle="tab" class="btn btn-primary">Siguiente</a>
                                    <div>&nbsp;</div>
                                    <div class="radio">
                                        <label><input type="radio" name="cla" value="1" onchange="productos()">Secci&oacute;n</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="cla" value="2" onchange="productos()">Divisi&oacute;n</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="cla" value="4" onchange="productos()">Grupo</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="cla" value="6" onchange="productos()">Subgrupo</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="cla" value="8" onchange="productos()">Producto</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="cla" value="10" checked="checked" onchange="productos()">Variedad</label>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pproductos">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Productos</div>
                                    <div>&nbsp;</div>
                                    <a href="#pperiodicidad" onclick="$('#lproductos').prop('class', ''); $('#lperiodicidad').prop('class', 'active');" data-toggle="tab" class="btn btn-primary">Siguiente</a>
                                    <button class="btn btn-primary" type="button" onclick="select()">
                                        <i class="icon-check-sign"></i> Marcar Todo
                                    </button>
                                    <button class="btn btn-primary" type="button" onclick="unselect()">
                                        <i class="icon-check-empty"></i> Desmarcar Todo
                                    </button>
                                    <div>&nbsp;</div>
                                    <div id="productos">
                                        Debe seleccionar sector y clasificación.
                                    </div>
                                </div>
                                <div class="tab-pane" id="pperiodicidad">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Periodicidad</div>
                                    <div>&nbsp;</div>
                                    <a href="#pperiodo" onclick="$('#lperiodicidad').prop('class', ''); $('#lperiodo').prop('class', 'active');" data-toggle="tab" class="btn btn-primary">Siguiente</a>
                                    <div>&nbsp;</div>
                                    <div class="radio">
                                        <label><input type="radio" name="per" value="1" onchange="periodo()">Mensual</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="per" value="2" onchange="periodo()">Bimensual</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="per" value="3" onchange="periodo()">Trimensual</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="per" value="6" onchange="periodo()">Semestral</label>
                                    </div>
                                    <div class="radio">
                                        <label><input type="radio" name="per" value="12" onchange="periodo()">Anual</label>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pperiodo">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Inicio</div>
                                    <div>&nbsp;</div>
                                    <a href="#pcuadro" onclick="$('#lperiodo').prop('class', ''); $('#lcuadro').prop('class', 'active');" data-toggle="tab" class="btn btn-primary">Siguiente</a>
                                    <div>&nbsp;</div>
                                    <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesini" onchange="gesini()" name="gesini"></select></div>
                                    <div>&nbsp;</div>
                                    <div><div style="width: 60px; text-align: right; float: left;">Periodo: &nbsp;</div><select id="perini" name="perini"></select></div>
                                    <div>&nbsp;</div>
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Fin</div>
                                    <div>&nbsp;</div>
                                    <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()" name="gesfin"></select></div>
                                    <div>&nbsp;</div>
                                    <div><div style="width: 60px; text-align: right; float: left;">Periodo: &nbsp;</div><select id="perfin" name="perfin"></select></div>
                                    <div>&nbsp;</div>
                                </div>
                                <div class="tab-pane" id="pcuadro">
                                    <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Cuadros</div>
                                    <div>&nbsp;</div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="cuadro" value="1"/>&nbsp;Por ciudades</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" name="cuadro" value="2"/>&nbsp;Nacional</label>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div><button class="btn btn-primary" type="button" onclick="excel2()">
                                        <i class="icon-download-alt"></i> Promedio
                                    </button></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>