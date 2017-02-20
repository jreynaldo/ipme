<script type="text/javascript">
    function filtrar() {
        $('#comercializadoras').html('Cargando ...');
        $.post('tabla_comercializadoras', {
            comercializadora: $('#comercializadora').val(),
            producto: $('#producto_find').val()
        }, function (result) {
            $('#comercializadoras').html(result);
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
            title: 'Comercializadora',
            message: $('<div>Cargando ...</div>').load('comercializadora_form'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('insert_comercializadora', $('#form_comer').serialize(), function (result) {
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
            title: 'Comercializadora',
            message: $('<div>Cargando ...</div>').load('comercializadora_form', {id: id}),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('update_comercializadora', $('#form_comer').serialize(), function (result) {
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
            title: 'Comercializadora',
            message: $('<div><label style="float: left">Justificacion:</label><div class="controls"><input id="justificacion"/></div></div>'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    $.post('discard_comercializadora', {
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
            title: 'Producto Manufacturado',
            message: $('<div>Cargando ...</div>').load('manufacturado_form', {id_informador: id_informador}),
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
                    var vars = ['#producto','#especificacion','#cantidad_a_cotizar','#unidad_a_cotizar',
                        '#cantidad_equivalente'];
                    var mesg = ['Debe introducir el producto a cotizar.\n','Debe introducir la especificación del producto.\n',
                        'Debe especificar la cantidad a cotizar.\n','Debe especificar la unidad a cotizar.\n',
                        'Debe especificar la cantidad equivalente.\n'];
                    for (var i = 0; i < vars.length; i++) {
                        if ($(vars[i]).val().length === 0) {
                            flag = false;
                            mensaje += mesg[i];
                        }
                    }
                    if (flag) {
                        $.post('insert_prod_man', $('#form_menu').serialize(), function (result) {
                            if (result === 'Ok') {
                                window.location = 'sol_comercializadoras';
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
            title: 'Producto Manufacturado',
            message: $('<div>Cargando ...</div>').load('manufacturado_form', {id: id_prod}),
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
                    var vars = ['#producto','#especificacion','#cantidad_a_cotizar','#unidad_a_cotizar',
                        '#cantidad_equivalente','#justificacion'];
                    var mesg = ['Debe introducir el producto a cotizar.\n','Debe introducir la especificación del producto.\n',
                        'Debe especificar la cantidad a cotizar.\n','Debe especificar la unidad a cotizar.\n',
                        'Debe especificar la cantidad equivalente.\n','Debe justificar el cambio.\n'];
                    for (var i = 0; i < vars.length; i++) {
                        if ($(vars[i]).val().length === 0) {
                            flag = false;
                            mensaje += mesg[i];
                        }
                    }
                    if (flag) {
                        $.post('update_prod_man', $('#form_menu').serialize(), function (result) {
                            if (result === 'Ok') {
                                window.location = 'sol_comercializadoras';
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
            title: 'Producto Manufacturado',
            message: $('<div><label style="float: left">Justificacion:</label><div class="controls"><input id="justificacion"/></div></div>'),
            buttons: [{
                label: 'Aceptar',
                cssClass: 'btn-primary',
                action: function(dialog) {
                    var $button = this;
                    $button.disable();
                    if ($('#justificacion').val().length === 0) {
                        alert('Debe introducir una justificación.');
                        $button.enable();
                    } else {
                        $.post('discard_prod_man', {
                            id: id_prod,
                            justificacion: $('#justificacion').val()
                        }, function (result) {
                            if (result === 'Ok') {
                                window.location = 'sol_comercializadoras';
                            } else {
                                $button.enable();
                                alert(result);
                            }
                        });
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
    function selupm(id_prod, id_depto, id_upm) {
        BootstrapDialog.show({
            title: 'Comercializadora',
            message: $('<div>Cargando ...</div>').load('informantes?id_tipo=2&id_prod=' + id_prod + '&id_depto=' + id_depto + '&id_upm=' + id_upm),
            buttons: [{
                id: 'btnCancelUpm',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function fusionar(button, id_prod, id_informador) {
        button.disabled = true;
        $.post('fusion_prod_man', {
            id: id_prod,
            id_informador: id_informador,
            justificacion: ''
        }, function (result) {
            if (result === 'Ok') {
                window.location = 'sol_comercializadoras';
                $('#btnCancelUpm').trigger('click');
            } else {
                button.disabled = false;
                alert(result);
            }
        });
    }
    function imagen(id_prod) {
        window.location.href = 'image?id=' + id_prod + '&orig=comercializadoras';
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
    function info(id) {
        BootstrapDialog.show({
            title: 'Cotizaci&oacute;n',
            message: $('<div>Cargando...</div>').load('cotizacion', {id: id}),
            buttons: [{
                id: 'btnCancelClas',
                label: 'Cancelar',
                action: function(dialog) {
                    dialog.close();
                }
            }]
        });
    }
    function init() {
        $("#mun").select2({width: 'resolve'});
        <?php if (isset($id)) {
            echo 'editprod('.$id.');';
        } ?>
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
                                    <td style="font-weight: bold; text-align: right">Buscar por Comercializadora:</td>
                                    <td><input type="text" id="comercializadora"/></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; text-align: right">Buscar por Producto:</td>
                                    <td><input type="text" id="producto_find"/></td>
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
                                <button class="btn btn-primary" onclick="addinf()"><i class="fa fa-plus"></i> Agregar Comercializadora</button>
                                <a href="<?= site_url() ?>/canasta/sol_comercializadoras" class="btn btn-primary"><i class="fa fa-list"></i> Solicitudes</a>
                            </div>
                            <div id="comercializadoras">
                                <table class="table table-advance table-bordered tbl">
                                    <thead>
                                        <tr>
                                            <th style="width: 15px;"></th>
                                            <th>Departamento</th>
                                            <th>Nombre</th>
                                            <th>Direcci&oacute;n</th>
                                            <th>Entre Calles</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr></tr>
                                        <?php for ($i = 0; $i < count($comercializadoras); $i++): ?>
                                        <tr>
                                            <td><a id="exp<?=$i?>" onclick="expand(<?=$i?>)" href="#">+</a></td>
                                            <td><?= $comercializadoras[$i]['departamento'] ?></td>
                                            <td><?= $comercializadoras[$i]['descripcion'] ?></td>
                                            <td><?= $comercializadoras[$i]['direccion'] ?></td>
                                            <td><?= $comercializadoras[$i]['entre_calles'] ?></td>
                                            <td><button title="Editar" class="btn btn-primary" onclick="editinf(<?=$comercializadoras[$i]['id_informador']?>)"><i class="fa fa-edit"></i></button></td>
                                            <td><button title="Agregar Producto" class="btn btn-primary" onclick="addprod(<?=$comercializadoras[$i]['id_informador']?>)"><i class="fa fa-plus"></i></button></td>
                                            <td><button title="Descartar" class="btn btn-primary" onclick="delinf(<?=$comercializadoras[$i]['id_informador']?>)"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td colspan="7">
                                                <table id="tab<?=$i?>" style="display: none;">
                                                    <tr style="font-weight: bold">
                                                        <td style="color: blue">Codigo</td>
                                                        <td style="color: blue">Producto</td>
                                                        <td style="color: blue">Especificaci&oacute;n</td>
                                                        <td style="color: blue">Tama/Talla/Peso</td>
                                                        <td style="color: blue">Marca</td>
                                                        <td style="color: blue">Modelo</td>
                                                        <td style="color: blue">Unidad</td>
                                                        <td style="color: blue">Equivalencia</td>
                                                        <td style="color: blue">Envase</td>
                                                        <td style="color: blue">Origen</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                    <?php for ($j = 0; $j < count($comercializadoras[$i]['productos']); $j++): ?>
                                                    <tr>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['codigo']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['producto']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['especificacion']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['unidad_talla_peso']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['marca']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['modelo']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['unidad']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['equivalencia']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['envase']?></td>
                                                        <td style="color: #0090FF"><?=$comercializadoras[$i]['productos'][$j]['origen']?></td>
                                                        <td><button title="Editar" class="btn btn-bordered btn-default" onclick="editprod(<?=$comercializadoras[$i]['productos'][$j]['id_producto']?>)"><i class="fa fa-edit"/></button></td>
                                                        <td><button title="Fusionar" class="btn btn-bordered btn-default" onclick="selupm(<?=$comercializadoras[$i]['productos'][$j]['id_producto'].','.$comercializadoras[$i]['id_departamento'].','.$comercializadoras[$i]['id_informador']?>)"><i class="fa fa-random"/></button></td>
                                                        <td><button title="Descartar" class="btn btn-bordered btn-default" onclick="delprod(<?=$comercializadoras[$i]['productos'][$j]['id_producto']?>)"><i class="fa fa-trash"/></button></td>
                                                        <td><button title="Imagen" class="btn btn-bordered btn-default" onclick="imagen(<?=$comercializadoras[$i]['productos'][$j]['id_producto']?>)"><i class="fa fa-picture-o"/></button></td>
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