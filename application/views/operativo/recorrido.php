<script type="text/javascript">
    function subir(button, id) {
        var row = $(button).parents("tr:first");
        $.post('subir', {
            id: id
        }, function (result) {
            if (result === '1') {
                row.insertBefore(row.prev());
            } else {
                alert(result);
            }
        });
    }
    function bajar(button, id) {
        var row = $(button).parents("tr:first");
        $.post('bajar', {
            id: id
        }, function (result) {
            if (result === '1') {
                row.insertAfter(row.next());
            } else {
                alert(result);
            }
        });
    }
    function mapa(id) {
        var depto = <?= $values['id_departamento'] ?>;
        var tipo = <?= $values['id_tipo'] ?>;
        var carga = '<?= $values['id_carga'] ?>';
        var url = "<?= site_url() ?>/operativo/mapa?depto=" + depto + "&tipo=" + tipo + "&carga=" + carga + "&id=" + id;
        window.open(url, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=50, left=300, width=600, height=400");
    }
    function init() {
        $("#dep").select2({width: 'resolve'});
        $("#tipo").select2({width: 'resolve'});
        $("#carga").select2({width: 'resolve'});
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
                                <b>Departamento: </b><?= form_dropdown('dep', $departamentos, $values['id_departamento'], 'id="dep" onchange="this.form.submit()"') ?>
                                <b>Tipo: </b><?= form_dropdown('tipo', $tipo, $values['id_tipo'], 'id="tipo" onchange="this.form.submit()"') ?>
                                <b>Carga: </b><?= form_dropdown('carga', $carga, $values['id_carga'], 'id="carga" onchange="this.form.submit()"') ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-sort"> Orden recorrido</i>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="2">Mover</th>
                                        <th style="width: 25px;">Nro</th>
                                        <?php if ($values['id_tipo'] == 1) : ?>
                                            <th>Mercado</th>
                                        <?php else : ?>
                                            <th>Comercializadora</th>
                                        <?php endif; ?>
                                        <th>Direcci&oacute;n</th>
                                        <th>Entre Calles</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="task">
                                    <?php for ($i = 0; $i < count($upms); $i++): ?>
                                    <tr>
                                        <td style="width: 40px;">
                                            <button class="btn btn-link" onclick="subir(this, <?= $upms[$i]['id_informador'] ?>)">
                                                <i class="fa fa-arrow-up"></i>
                                            </button>
                                        </td>
                                        <td style="width: 40px;">
                                            <button class="btn btn-link" onclick="bajar(this, <?= $upms[$i]['id_informador'] ?>)">
                                                <i class="fa fa-arrow-down"></i>
                                            </button>
                                        </td>
                                        <td><?= $upms[$i]['recorrido_carga'] ?></td>
                                        <td><?= $upms[$i]['descripcion'] ?></td>
                                        <td><?= $upms[$i]['direccion'] ?></td>
                                        <td><?= $upms[$i]['entre_calles'] ?></td>
                                        <td style="width: 40px;">
                                            <button class="btn btn-link" onclick="mapa(<?= $upms[$i]['id_informador'] ?>)">
                                                <i class="fa fa-map-marker"></i>
                                            </button>
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