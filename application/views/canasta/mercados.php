<script type="text/javascript">
    function filtrar() {
        $('#mercados').html('Cargando ...');
        $.post('tabla_mercados', {
            mercado: $('#mercado').val(),
            producto: $('#prod').val()
        }, function (result) {
            $('#mercados').html(result);
        });
    }
    function clasificador() {
        BootstrapDialog.show({
            title: 'Clasificador',
            message: $('<div></div>').load('clasificador'),
            buttons: [{
                id: 'btnCancelClas',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function codif(cod) {
        $('#codigo').val(cod);
        $('#btnCancelClas').trigger('click');
    }
    function addinf() {
        BootstrapDialog.show({
            title: 'Mercado',
            message: $('<div></div>').load('mercado_form'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('insert_mercado', $('#form_merc').serialize(), function (result) {
                        if (result === 'Ok') {
                            location.reload();
                        } else {
                            $button.enable();
                            alert(result);
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
    function editinf(id) {
        BootstrapDialog.show({
            title: 'Mercado',
            message: $('<div"></div>').load('mercado_form', {id: id}),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('update_mercado', $('#form_merc').serialize(), function (result) {
                        if (result === 'Ok') {
                            location.reload();
                        } else {
                            $button.enable();
                            alert(result);
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
    function delinf(id) {
        BootstrapDialog.show({
            title: 'Mercado',
            message: $('<div><label style="float: left">Justificacion:</label><div class="controls"><input id="justificacion"/></div></div>'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('discard_mercado', {
                        id: id,
                        justificacion: $('#justificacion').val()
                    }, function (result) {
                        if (result === 'Ok') {
                            location.reload();
                        } else {
                            $button.enable();
                            alert(result);
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
    function addprod(id_informador) {
        BootstrapDialog.show({
            title: 'Producto Agricola',
            message: $('<div></div>').load('agricola_form', {id_informador: id_informador}),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    var flag = true; var mensaje = '';
                    if ($('#codigo').val().length !== 10) {
                        flag = false;
                        mensaje += 'El codigo debe tener 10 dígitos.\n';
                    }
                    var vars = ['#especificacion','#cantidad_a_cotizar','#unidad_a_cotizar','#cantidad_equivalente'];
                    var mesg = ['Debe introducir la especificación del producto.\n','Debe especificar la cantidad a cotizar.\n',
                        'Debe especificar la unidad a cotizar.\n','Debe especificar la cantidad equivalente.\n'];
                    for (var i = 0; i < vars.length; i++) {
                        if ($(vars[i]).val().length === 0) {
                            flag = false;
                            mensaje += mesg[i];
                        }
                    }
                    if (flag) {
                        $.post('insert_prod_agri', $('#form_agric').serialize(), function (result) {
                            if (result === 'Ok') {
                                window.location = 'sol_mercados';
                            } else {
                                $button.enable();
                                alert(result);
                            }
                        });
                    } else {
                        alert(mensaje);
                        $button.enable();
                    }
                }
            }, {
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function editprod(id_prod) {
        BootstrapDialog.show({
            title: 'Producto Agricola',
            message: $('<div></div>').load('agricola_form', {id: id_prod}),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    var flag = true; var mensaje = '';
                    if ($('#codigo').val().length !== 10) {
                        flag = false;
                        mensaje += 'El codigo debe tener 10 dígitos.\n';
                    }
                    var vars = ['#especificacion','#cantidad_a_cotizar','#unidad_a_cotizar','#cantidad_equivalente'];
                    var mesg = ['Debe introducir la especificación del producto.\n','Debe especificar la cantidad a cotizar.\n',
                        'Debe especificar la unidad a cotizar.\n','Debe especificar la cantidad equivalente.\n'];
                    for (var i = 0; i < vars.length; i++) {
                        if ($(vars[i]).val().length === 0) {
                            flag = false;
                            mensaje += mesg[i];
                        }
                    }
                    if (flag) {
                        $.post('update_prod_agri', $('#form_agric').serialize(), function (result) {
                            if (result === 'Ok') {
                                window.location = 'sol_mercados';
                            } else {
                                $button.enable();
                                alert(result);
                            }
                        });
                    } else {
                        alert(mensaje);
                        $button.enable();
                    }
                }
            }, {
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function delprod(id_prod) {
        BootstrapDialog.show({
            title: 'Producto Agricola',
            message: $('<div><label style="float: left">Justificacion:</label><div class="controls"><input id="justificacion"/></div></div>'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('discard_prod_agri', {
                        id: id_prod,
                        justificacion: $('#justificacion').val()
                    }, function (result) {
                        if (result === 'Ok') {
                            window.location = 'sol_mercados';
                        } else {
                            $button.enable();
                            alert(result);
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
    function selupm(id_prod, id_depto, id_upm) {
        BootstrapDialog.show({
            title: 'Producto Agricola',
            message: $('<div></div>').load('informantes?id_tipo=1&id_prod=' + id_prod + '&id_depto=' + id_depto + '&id_upm=' + id_upm),
            buttons: [{
                id: 'btnCancelUpm',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function fusionar(button,id_prod, id_upm) {
        button.disabled = true;
        $.post('fusion_prod_agri', {
            id: id_prod,
            id_upm: id_upm,
            justificacion: ''
        }, function (result) {
            if (result === 'Ok') {
                window.location = 'sol_mercados';
                $('#btnCancelUpm').trigger('click');
            } else {
                button.disabled = false;
                alert(result);
            }
        });
    }
    function expand(i) {
        if ($('#exp' + i).html() === '+') {
            document.getElementById('tab' + i).style.display = 'block';
            $('#exp' + i).html('-');
        } else {
            document.getElementById('tab' + i).style.display = 'none';
            $('#exp' + i).html('+');
        }
    }
    function imagen(id_prod) {
        window.location.href = "image?id=" + id_prod + '&orig=mercados';
    }
    function init() {
        $("#mun").select2({width: 'resolve'});
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
                            <h4 class="clearfix">Filtro</h4>
                        </div>
                        <div class="panel-body">
                            <table>
                                <tr>
                                    <td style="font-weight: bold; text-align: right">Buscar por Mercado:</td>
                                    <td><input type="text" id="mercado"/></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; text-align: right">Buscar por Producto:</td>
                                    <td><input type="text" id="prod"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: right">
                                        <button class="btn btn-primary" type="button" onclick="filtrar()">
                                            <i class="icon-search"></i> Filtrar
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix"><?= $title ?></h4>
                        </div>
                        <div class="panel-body">
                            <div style="margin-bottom: 10px">
                                <button class="btn btn-primary" onclick="addinf()"><i class="fa fa-plus"></i> Agregar Mercado</button>
                                <a href="<?=site_url()?>/canasta/sol_mercados" class="btn btn-primary"><i class="fa fa-list"></i> Solicitudes</a>
                            </div>
                            <div id="mercados">
                                <table class="table table-striped table-bordered table-hover dataTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 15px;"></th>
                                            <th>Departamento</th>
                                            <th>Mercado</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                        <?php for ($i = 0; $i < count($mercados); $i++): ?>
                                        <tr>
                                            <td><a id="exp<?=$i?>" onclick="expand(<?=$i?>)" href="#">+</td>
                                            <td><?= $mercados[$i]['departamento'] ?></td>
                                            <td><?= $mercados[$i]['descripcion'] ?></td>
                                            <td style="text-align: right"><button class="btn btn-primary" onclick="editinf(<?=$mercados[$i]['id_informador']?>)"><i class="fa fa-edit"></i> Editar</button></td>
                                            <td style="text-align: right"><button class="btn btn-primary" onclick="addprod(<?=$mercados[$i]['id_informador']?>)"><i class="fa fa-plus"></i> Agregar Producto</button></td>
                                            <td style="text-align: right"><button class="btn btn-primary" onclick="delinf(<?=$mercados[$i]['id_informador']?>)"><i class="fa fa-trash"></i> Descartar</button></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="5">
                                                <table id="tab<?=$i?>" style="display: none;">
                                                    <tr style="font-weight: bold">
                                                        <td style="color: blue">Codigo</td>
                                                        <td style="color: blue">Producto</td>
                                                        <td style="color: blue">Especificacion</td>
                                                        <td style="color: blue">Unidad</td>
                                                        <td style="color: blue">Equivalencia</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <?php for ($j = 0; $j < count($mercados[$i]['productos']); $j++): ?>
                                                    <tr>
                                                        <td style="color: #0090FF"><?=$mercados[$i]['productos'][$j]['codigo']?></td>
                                                        <td style="color: #0090FF"><?=$mercados[$i]['productos'][$j]['producto']?></td>
                                                        <td style="color: #0090FF"><?=$mercados[$i]['productos'][$j]['especificacion']?></td>
                                                        <td style="color: #0090FF"><?=$mercados[$i]['productos'][$j]['unidad']?></td>
                                                        <td style="color: #0090FF"><?=$mercados[$i]['productos'][$j]['equivalencia']?></td>
                                                        <td><button title="Editar" class="btn btn-bordered btn-default" onclick="editprod(<?=$mercados[$i]['productos'][$j]['id_producto']?>)"><i class="fa fa-edit"/></button></td>
                                                        <td><button title="Fusionar" class="btn btn-bordered btn-default" onclick="selupm(<?=$mercados[$i]['productos'][$j]['id_producto'].','.$mercados[$i]['id_departamento'].','.$mercados[$i]['id_informador']?>)"><i class="fa fa-random"/></button></td>
                                                        <td><button title="Descartar" class="btn btn-bordered btn-default" onclick="delprod(<?=$mercados[$i]['productos'][$j]['id_producto']?>)"><i class="fa fa-trash"/></button></td>
                                                        <td><button title="Imagen" class="btn btn-bordered btn-default" onclick="imagen(<?=$mercados[$i]['productos'][$j]['id_producto']?>)"><i class="fa fa-picture-o"/></button></td>
                                                    </tr>
                                                    <?php endfor; ?>
                                                </table>
                                            </td>
                                        </tr>
                                        <?php endfor; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>