<script type="text/javascript">
    function select(carga) {
        $('input:checkbox[name = com' + carga + ']').prop('checked', true);
    }
    function unselect() {
        $('input:checkbox[name ^= merc]').prop('checked', false);
    }
    function asignar(button) {
        button.disabled = true;
        var cot = $('input:radio[name = cot]:checked').val();
        if (cot === undefined) {
            alert('Debe seleccionar un cotizador.');
            button.disabled = false;
        } else {
            var upms = '{';
            $('input:checkbox[name ^= merc]:checked').each(function(i, val) {
                upms = upms + $(val).val() + ',';
            });
            if (upms.length > 1) {
                upms = upms.substr(0, upms.length - 1);
            }
            upms = upms + '}';
            if (upms === '{}') {
                alert('Debe seleccionar al menos una comercializadora.');
                button.disabled = false;
            } else {
                $.post('guardar_mercados', {
                    cot: cot,
                    upms: upms
                }, function (result) {
                    if (result === '') {
                        window.location = window.location.href;
                    } else {
                        alert(result);
                        button.disabled = false;
                    }
                });
            }
        }
    }
    function reasignar(button) {
        button.disabled = true;
        var cot = $('input:radio[name = cot]:checked').val();
        if (cot === undefined) {
            alert('Debe seleccionar un cotizador.');
            button.disabled = false;
        } else {
            var upms = '{';
            $('input:checkbox[name ^= asig]:checked').each(function(i, val){
                upms = upms + $(val).val() + ',';
            });
            if (upms.length > 1) {
                upms = upms.substr(0, upms.length - 1);
            }
            upms = upms + '}';
            if (upms === '{}') {
                alert('Debe seleccionar al menos una comercializadora.');
                button.disabled = false;
            } else {
                $.post('guardar_mercados', {
                    cot: cot,
                    upms: upms
                }, function (result) {
                    if (result === '') {
                        window.location = window.location.href;
                    } else {
                        alert(result);
                        button.disabled = false;
                    }
                });
            }
        }
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
                            <h4 class="clearfix"><?= $title ?></h4>
                        </div>
                        <div class="panel-body">
                            <form method="Get" class="form-horizontal">
                                <b>Departamento: </b><?= form_dropdown('dep', $departamentos, $values['id_departamento'], 'id="mun" onchange="this.form.submit()"') ?>
                                &nbsp;<b>Semana: </b><?= $values['week'].' <b>Año: </b>'.$values['anio'] ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-user fa-fw"></i>Seleccione Cotizador
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 18px;">Sel</th>
                                        <th>Cotizador</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($cotizadores); $i++): ?>
                                    <tr>
                                        <td><input type="radio" name="cot" class="cotManual" value="<?= $cotizadores[$i]['id_usuario'] ?>"/></td>
                                        <td><?= $cotizadores[$i]['cotizador'] ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-male fa-fw"></i>Seleccione los Mercados
                        </div>
                        <div class="panel-body">
                            <button class="btn btn-primary" type="button" onclick="unselect()">
                                <i class="icon-check-empty"></i> Desmarcar Todo
                            </button>
                            <button class="btn btn-primary" type="button" onclick="asignar(this)">
                                <i class="icon-ok"></i> Asignar
                            </button>
                            <p/>
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 18px;">Sel</th>
                                        <th>Mercado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($mercados); $i++): ?>
                                    <tr>
                                        <td><input type="checkbox" name="merc" class="cotManual" value="<?= $mercados[$i]['id_informador'] ?>"/></td>
                                        <td><?= $mercados[$i]['descripcion'] ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-list"></i> Asignacion Creada
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 18px;">Sel</th>
                                        <th>Cotizador</th>
                                        <th>Mercado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($i = 0; $i < count($asignacion); $i++): ?>
                                    <tr>
                                        <?php if ($asignacion[$i]['exportado'] === 't'): ?>
                                            <td><input type="checkbox" name="asig" class="cotManual" disabled="true" value="<?= $asignacion[$i]['id_informador'] ?>"/></td>
                                        <?php else: ?>
                                            <td><input type="checkbox" name="asig" class="cotManual" value="<?= $asignacion[$i]['id_informador'] ?>"/></td>
                                        <?php endif; ?>
                                        <td><?= $asignacion[$i]['cotizador'] ?></td>
                                        <td><?= $asignacion[$i]['descripcion'] ?></td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                            <button class="btn btn-primary" type="button" onclick="reasignar(this)">
                                <i class="icon-refresh"></i> Reasignar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>