<script type="text/javascript">
    function indice() {
        var sec = $('#sector').val();
        var cla = $('#clasificacion').val();
        var gesini = $('#gesini').val();
        var perini = $('#perini').val();
        var gesfin = $('#gesfin').val();
        var perfin = $('#perfin').val();
        post('indice', {
           sector: sec,
           clasificacion: cla,
           gesini: gesini,
           perini: perini,
           gesfin: gesfin,
           perfin: perfin
        });
    }
    function sector() {
        if ($('#sector').val() === '4') {
            $('#clasificacion').html('<option value="1">Secci&oacute;n</option>');
        } else {
            $('#clasificacion').html('<option value="1">Secci&oacute;n</option><option value="2">Divisi&oacute;n</option><option value="4">Grupo</option>');
            $('#clasificacion').val($('#clasificacion option:last').val());
        }
        $('#clasificacion').trigger('change');
    }
    function gesini() {
        if (parseInt($('#gesini').val()) === 2014) {
            $('#perini').html(months(2014, 8));
        } else {
            $('#perini').html(months(parseInt($('#gesini').val()), 1));
        }
        $('#gesfin').html(years(parseInt($('#gesini').val())));
        $('#gesfin').val($('#gesfin option:last').val());
        $("#gesfin").trigger('change');
        $('#perini').val($('#perini option:first').val());
        $("#perini").trigger('change');
    }
    function perini() {
        if ($('#gesini').val() === $('#gesfin').val()) {
            $('#perfin').html(months(parseInt($('#gesini').val()), parseInt($('#perini').val())));
        } else {
            $('#perfin').html(months(parseInt($('#gesfin').val()), 1));
        }
        $('#perfin').val($('#perfin option:last').val());
        $("#perfin").trigger('change');
    }
    function gesfin() {
        if ($('#gesini').val() === $('#gesfin').val()) {
            $('#perfin').html(months(parseInt($('#gesini').val()), parseInt($('#perini').val())));
        } else {
            $('#perfin').html(months(parseInt($('#gesfin').val()), 1));
        }
        $('#perfin').val($('#perfin option:last').val());
        $("#perfin").trigger('change');
    }
    function init() {
        aprobado = '<?=$aprobado?>';
        $('#sector').select2({width: 'resolve'});
        
        $('#clasificacion').val($('#clasificacion option:last').val());
        $('#clasificacion').select2({width: 'resolve'});
        
        $('#gesini').html(years(2014));
        $("#gesini").trigger('change');
        
        $('#gesini').select2({width: 'resolve'});
        $('#perini').select2({width: 'resolve'});
        $('#gesfin').select2({width: 'resolve'});
        $('#perfin').select2({width: 'resolve'});
    };
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
                            <i class="fa fa-calendar"></i> Seleccione Opci&oacute;n
                        </div>
                        <div class="panel-body">
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Opci&oacute;n</div>
                            <div>&nbsp;</div>
                            <div>
                                <div style="width: 100px; text-align: right; float: left;">Sector: &nbsp;</div>
                                <select id="sector" onchange="sector()" onclick="productos()">
                                    <option value="4">Todos</option>
                                    <option value="1">Agricola</option>
                                    <option value="2">Manufacturado</option>
                                    <option value="3">Importado</option>
                                </select>
                            </div>
                            <div>&nbsp;</div>
                            <div>
                                <div style="width: 100px; text-align: right; float: left;">Clasificaci&oacute;n: &nbsp;</div>
                                <select id="clasificacion" onclick="productos()">
                                    <option value="1">Secci&oacute;n</option>
                                </select>
                            </div>
                            <div>&nbsp;</div>
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Inicio</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesini" onchange="gesini()"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="perini" onchange="perini()"></select></div>
                            <div>&nbsp;</div>
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Fin</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Mes: &nbsp;</div><select id="perfin"></select></div>
                            <div>&nbsp;</div>
                            <div>
                                <button class="btn btn-primary" type="button" onclick="indice()">
                                    <i class="fa fa-calculator"></i> Indice
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>