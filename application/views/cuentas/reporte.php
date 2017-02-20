<script type="text/javascript">
    function productos() {
        $('#productos').html('Por favor espere...');
        $.post('productos', {
            sector: $('#sector').val(),
            clasificacion: $('#clasificacion').val()
        },
        function (result) {
            $('#productos').html(result);
            select();
        });
    }
    function select() {
        $('input:checkbox[name=codigo]').prop('checked', true);
    }
    function unselect() {
        $('input:checkbox[name=codigo]').prop('checked', false);
    }
    function promedio() {
        var sec = $('#sector').val();
        var cla = $('#clasificacion').val();
        var cods = '{';
        $('input:checkbox[name=codigo]:checked').each(function(i, val) {
            cods = cods + $(val).val() + ",";
        });
        if (cods.length > 1) {
            cods = cods.substr(0, cods.length -1) + '}';
            var gesini = $('#gesini').val();
            var perini = $('#perini').val();
            var gesfin = $('#gesfin').val();
            var perfin = $('#perfin').val();
            if (gesini !== null && perini !== null && gesfin !== null && perfin !== null) {
                post('imputado', {
                   informacion: 1,
                   sector: sec,
                   clasificacion: cla,
                   codigos: cods,
                   periodicidad: 1,
                   gesini: gesini,
                   perini: perini,
                   gesfin: gesfin,
                   perfin: perfin
                });
            }
        } else {
            alert('Debe seleccionar al menos un producto.');
        }
    }
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
        productos();
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
                                <select id="sector" onclick="productos()">
                                    <option value="1">Agricola</option>
                                </select>
                            </div>
                            <div style="display: none;">&nbsp;</div>
                            <div style="display: none;">
                                <div style="width: 100px; text-align: right; float: left;">Clasificaci&oacute;n: &nbsp;</div>
                                <select id="clasificacion" onclick="productos()">
                                    <option value="1">Secci&oacute;n</option>
                                    <option value="2">Divisi&oacute;n</option>
                                    <option value="4">Grupo</option>
                                    <option value="6">Subgrupo</option>
                                    <option value="8" selected>Producto</option>
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
                                <button class="btn btn-primary" type="button" onclick="promedio()">
                                    <i class="fa fa-calculator"></i> Promedio
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-apple"></i> Marque los Productos
                        </div>
                        <div class="panel-body">
                            <button class="btn btn-primary" type="button" onclick="productos()">
                                <i class="fa fa-refresh"></i> Actualizar
                            </button>
                            <button class="btn btn-primary" type="button" onclick="select()">
                                <i class="fa fa-check-square-o"></i> Marcar Todo
                            </button>
                            <button class="btn btn-primary" type="button" onclick="unselect()">
                                <i class="fa fa-square-o"></i> Desmarcar Todo
                            </button>
                            <!--&nbsp;Buscar:<input id="search" type="text" onkeyup="oTable.search($(this).val()).draw();"/>-->
                            <br/><br/>
                            <div id="productos">
                                Por favor espere...
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>