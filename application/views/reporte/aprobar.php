<script type="text/javascript">
    function gesini() {
        if ($('#gesini').val() > year) {
            $('#perini').html(months($('#gesini').val(), 1));
        } else {
            $('#perini').html(months(year, month));
        }
    }
    function guardar() {
        $.post('guardar', {
            gestion: $('#gesini').val(),
            periodo: $('#perini').val()
        }, function (result) {
            if (result === 'Ok') {
                post('aprobar', {});
            } else {
                alert(result);
            }
        });
    }
    function init() {
        var aprobado = '<?=$aprobado?>';
        year = aprobado.substr(0, 4);
        month = aprobado.substr(5);
        $('#gesini').html(years(year));
        $('#gesini').select2({width: 'resolve'});
        $('#perini').html(months(year, month));
        $('#perini').select2({width: 'resolve'});
        $('#perini').val($('#perini option:first').next().val());
        $('#perini').trigger('change');
    }
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header"><?= $title ?></h2>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-calendar"></i> Seleccione Periodo
                        </div>
                        <div class="panel-body">
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Inicio</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesini" onchange="gesini()"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="perini"></select></div>
                            <div>&nbsp;</div>
                            <div>
                                <button class="btn btn-primary" type="button" onclick="guardar()">
                                    <i class="fa fa-check"></i> Aceptar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="clear: both;"></div>