<script type="text/javascript">
    function imputar(button) {
        button.disabled = true;
        $('#progressBar').val(10);
        $('#progressLabel').html('Promediando');
        $.post('promediar', {},
        function (result) {
            if (result === 'Ok') {
                $('#progressBar').val(30);
                $('#progressLabel').html('Imputando productos agricolas');
                $.post('imputar_agricolas', {},
                function (result) {
                    if (result === 'Ok') {
                        $('#progressBar').val(65);
                        $('#progressLabel').html('Imputando productos manufacturados');
                        $.post('imputar_manufacturados', {},
                        function (result) {
                            if (result === 'Ok') {
                                $('#progressBar').val(100);
                                $('#progressLabel').html('');
                                button.disabled = false;
                                alert('Imputación concluída.');
                            } else {
                                alert(result);
                            }
                        });
                    } else {
                        alert(result);
                    }
                });
            } else {
                alert(result);
            }
        });
    }
    function imputar2(button) {
        button.disabled = true;
        $('#progressBar').val(10);
        $('#progressLabel').html('Promediando');
        $.post('promediar', {},
        function (result) {
            if (result === 'Ok') {
                $('#progressBar').val(30);
                $('#progressLabel').html('Imputando productos agricolas');
                $.post('imputar_agricolas2', {},
                function (result) {
                    if (result === 'Ok') {
                        $('#progressBar').val(65);
                        $('#progressLabel').html('Imputando productos manufacturados');
                        $.post('imputar_manufacturados2', {},
                        function (result) {
                            if (result === 'Ok') {
                                $('#progressBar').val(100);
                                $('#progressLabel').html('');
                                button.disabled = false;
                                alert('Imputación concluída.');
                            } else {
                                alert(result);
                            }
                        });
                    } else {
                        alert(result);
                    }
                });
            } else {
                alert(result);
            }
        });
    }
    function encadenados(button) {
        button.disabled = true;
        $('#progressBar2').val(10);
        $('#progressLabel2').html('Calculando');
        $.post('encadenados', {},
        function (result) {
            if (result === 'Ok') {
                $('#progressBar2').val(100);
                $('#progressLabel2').html('');
                button.disabled = false;
                alert('Cálculo concluído.');
            } else {
                alert(result);
            }
        });
    }
    function init() { }
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
                            <div>
                                <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Imputaci&oacute;n</div>
                                <div>&nbsp;</div>
                                <div><progress id="progressBar" value="0" max="100"></progress></div>
                                <div id="progressLabel">Ultima imputaci&oacute;n: <?=$imputacion?></div>
                                <div>&nbsp;</div>
                                <div>
                                    <button class="btn btn-primary" type="button" onclick="imputar(this)">
                                        <i class="icon-magic"></i> Calcular Imputaci&oacute;n
                                    </button>
                                </div>
                                <div>&nbsp;</div>
                                <div>
                                    <button class="btn btn-primary" type="button" onclick="imputar2(this)">
                                        <i class="icon-magic"></i> Calcular Imputaci&oacute;n Con Eliminaci&oacute;n de At&iacute;picos
                                    </button>
                                </div>
                            </div>
                            <div>&nbsp;</div>
                            <div>
                                <div style="background-color: #b6d1f2; color: white; font-size: medium; padding: 5px">&nbsp;Encadenados</div>
                                <div>&nbsp;</div>
                                <div><progress id="progressBar2" value="0" max="100"></progress></div>
                                <div id="progressLabel2">Ultimo c&aacute;lculo: <?=$encadenados?></div>
                                <div>&nbsp;</div>
                                <div>
                                    <button class="btn btn-primary" type="button" onclick="encadenados(this)">
                                        <i class="icon-magic"></i> Calcular Encadenados
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>