<script type="text/javascript">
    function productos() {
        $('#productos').html('Por favor espere...');
        $.post('productos_agricolas', {},
        function (result) {
            $('#productos').html(result);
        });
    }
    function post(url, params) {
        var form = document.createElement("form");
        form.setAttribute("method", "post");
        form.setAttribute("action", url);
        for(var key in params) {
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            form.appendChild(hiddenField);
        }
        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
    function excel() {
        var cods = '';
        $('input:checkbox[name=codigo]:checked').each(function(i, val) {
            cods = cods + "'" + $(val).val() + "',";
            //cods = cods + $(val).val().replace("''", "'").replace("''", "'") + ",";
        });
        if (cods.length > 0) {
            cods = cods.substr(0, cods.length -1);
            post("semanal_rep", {
                gesini: $("#gesini").val(),
                mesini: $("#mesini").val(),
                gesfin: $("#gesfin").val(),
                mesfin: $("#mesfin").val(),
                cod: cods
            });
        } else {
            alert('Debe seleccionar al menos un producto.');
        }
    }
    function select() {
        $('input:checkbox[name=codigo]').prop('checked', true);
    }
    function unselect() {
        $('input:checkbox[name=codigo]').prop('checked', false);
    }
    function imputar(button) {
        button.disabled = true;
        $('#progressBar').val(5);
        $('#progressLabel').html('Calculando...');
        $.post('imputar_sem', {}, function (result) {
            if (result === 'Ok') {
                $('#progressBar').val(100);
                alert('Imputación concluída.');
            } else {
                alert(result);
            }
            button.disabled = false;
        });
    }
    function init() {
        productos();
        $("#gesini").select2({width: 'resolve'});
        $("#semini").select2({width: 'resolve'});
        $("#gesfin").select2({width: 'resolve'});
        $("#semfin").select2({width: 'resolve'});
        $.post('anio', {
            gestion: 2014
        },
        function (result) {
            $("#gesini").html(result);
            $("#gesini").trigger('change');
        });
    };
    function gesini() {
        $.post('semana', {
            gestion: $("#gesini").val()
        },
        function (result) {
            $("#semini").html(result);
            $("#semini").trigger('change');
        });
        $.post('anio', {
            gestion: $("#gesini").val()
        },
        function (result) {
            $("#gesfin").html(result);
            $("#gesfin").val($("#gesfin option").last().val()).trigger('change');
        });
    }
    function gesfin() {
        $.post('semana', {
            gestion: $("#gesfin").val()
        },
        function (result) {
            $("#semfin").html(result);
            $("#semfin").val($("#semfin option").last().val()).trigger('change');
        });
    }
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div>&nbsp;</div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix"><?= $title; ?></h4>
                        </div>
                        <div class="panel-body">
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Inicio</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesini" onchange="gesini()" name="gesini" style="width: 80px;"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Semana: &nbsp;</div><select id="semini" name="semini" style="width: 200px;"></select></div>
                            <div>&nbsp;</div>
                            <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Fin</div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">A&ntilde;o: &nbsp;</div><select id="gesfin" onchange="gesfin()" name="gesfin" style="width: 80px;"></select></div>
                            <div>&nbsp;</div>
                            <div><div style="width: 60px; text-align: right; float: left;">Semana: &nbsp;</div><select id="semfin" name="semfin" style="width: 200px;"></select></div>
                            <div>&nbsp;</div>
                            <div><button class="btn btn-primary" type="button" onclick="excel()">
                                <i class="icon-download-alt"></i> Excel
                            </button></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix">Marque los productos</h4>
                        </div>
                        <div class="panel-body">
                            <button class="btn btn-primary" type="button" onclick="productos()">
                                <i class="icon-refresh"></i> Actualizar
                            </button>
                            <button class="btn btn-primary" type="button" onclick="select()">
                                <i class="icon-check-sign"></i> Marcar Todo
                            </button>
                            <button class="btn btn-primary" type="button" onclick="unselect()">
                                <i class="icon-check-empty"></i> Desmarcar Todo
                            </button>
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