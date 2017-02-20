<script type="text/javascript">
    function tipo() {
        var tipo = $("#id_tipo").val();
        var neg = <?=$neg?>;
        var ind = String(window.location).indexOf('?') + 1;
        window.location = String(window.location).substr(0, ind) + 'neg=' + neg + '&tipo=' + tipo;
    }
    function guardar() {
        if (parseInt($('#id_tipo').val()) === 1) {
            $.post('guardar_variacion', $('#form1').serialize(), function(result) {
                if (result !== 'Ok') {
                    alert(result);
                } else {
                    window.location = '<?=site_url()?>';
                }
            });
        } else {
            $.post('guardar_variacion_mes', $('#form1').serialize(), function(result) {
                if (result !== 'Ok') {
                    alert(result);
                } else {
                    window.location = '<?=site_url()?>';
                }
            });
        }
    }
    function cargar(event, nro, gestion, mes, semana, origen, codigo) {
        this.nro = nro;
        this.gestion = gestion;
        this.mes = mes;
        this.semana = semana;
        this.origen = origen;
        this.codigo = codigo;
        $('#fileinput').trigger('click');
        event.preventDefault();
    }
    function load(files) {
        if (!files.length) {
            alert('No se encontró el archivo.');
        } else {
            var img = document.createElement('img');
            img.onload = function() {
                var MAX_WIDTH = 500;
                var MAX_HEIGHT = 500;
                var width = img.width;
                var height = img.height;

                if (width > height) {
                    if (width > MAX_WIDTH) {
                        height *= MAX_WIDTH / width;
                        width = MAX_WIDTH;
                    }
                } else {
                    if (height > MAX_HEIGHT) {
                        width *= MAX_HEIGHT / height;
                        height = MAX_HEIGHT;
                    }
                }
                var canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(img, 0, 0, width, height);
                var img1 = canvas.toDataURL("image/jpeg", 0.75);
                //var img1 = canvas.toDataURL({format: 'jpg', quality: 0.75});
                img = document.getElementById("img" + nro);
                img.src = img1;
                var index = img1.indexOf('base64') + 7;
                if (index > 6) {
                    img1 = img1.substr(index);
                } else {
                    img1 = null;
                }
                $.post('../imagen/upload_justificativo', {
                    gestion: gestion,
                    mes: mes,
                    semana: semana,
                    origen: origen,
                    codigo: codigo,
                    img: img1
                }, function (result) {
                    if (result === 'Ok') {
                        alert('Imagen enviada con éxito.');
                    } else {
                        alert(result);
                    }
                });
            };
            var reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            };
            reader.readAsDataURL(files[0]);
        }
    }
    function init() {
        $("#id_tipo").select2({width: 'resolve'});
        $("#id_tipo").val(<?=$tipo?>).trigger('change');
        $("#id_tipo").on('change', tipo);
    }
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div>&nbsp;</div>
                <div class="col-lg-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="clearfix"><i class="fa fa-edit"></i> <?= $title ?></h4>
                        </div>
                        <div class="panel-body">
                            <form id="formFile">
                                <div style="display:none">
                                    <input id="fileinput" type="file" accept="image/*" onchange="load(this.files)"/>
                                </div>
                            </form>
                            <div class="form-group">
                                <label class="control-label">Tipo:</label>
                                <select class="form-control" id="id_tipo">
                                    <option value="1" selected="selected">Semanal</option>
                                    <option value="2">Mensual</option>
                                </select>
                            </div>
                            <form id="form1">
                                <table class="table table-striped table-bordered table-hover table-responsive">
                                    <tr>
                                        <th>A&ntilde;o</th>
                                        <th>Mes</th>
                                        <th>Sem</th>
                                        <th>Origen</th>
                                        <th>C&oacute;digo</th>
                                        <th>Especificaci&oacute;n</th>
                                        <th>Variaci&oacute;n</th>
                                        <th>Justificaci&oacute;n</th>
                                        <th>Imagen</th>
                                    </tr>
                                    <?php for($i = 0; $i < count($variaciones); $i++) : ?>
                                    <tr>
                                        <td><?=$variaciones[$i]['gestion']?></td>
                                        <td><?=$variaciones[$i]['mes']?></td>
                                        <td><?=$variaciones[$i]['semana']?></td>
                                        <td><?=$variaciones[$i]['origen']?><input type="hidden" name="origen[]" value="<?=$variaciones[$i]['origen']?>"/></td>
                                        <td><?=$variaciones[$i]['codigo']?><input type="hidden" name="codigo[]" value="<?=$variaciones[$i]['codigo']?>"/></td>
                                        <td><?=$variaciones[$i]['especificacion']?></td>
                                        <td style="text-align: right"><?=$variaciones[$i]['variacion']?>%</td>
                                        <td><textarea cols="40" rows="2" name="justificacion[]"><?=$variaciones[$i]['justificacion']?></textarea></td>
                                        <td>
                                            <a href="<?=site_url()?>/Imagen/justificativo?gestion=<?=$variaciones[$i]['gestion']?>&semana=<?=$variaciones[$i]['semana']?>&origen=<?=$variaciones[$i]['origen']?>&codigo=<?=$variaciones[$i]['codigo']?>" target="_blank">
                                                <image id="img<?=$i?>" width="70px" src="<?=site_url()?>/Imagen/justificativo?gestion=<?=$variaciones[$i]['gestion']?>&semana=<?=$variaciones[$i]['semana']?>&origen=<?=$variaciones[$i]['origen']?>&codigo=<?=$variaciones[$i]['codigo']?>"/>
                                            </a>
                                            <button title="Cargar" class="btn btn-circle btn-bordered btn-info" onclick="cargar(event,<?=$i?>,<?=$variaciones[$i]['gestion']?>,<?=$variaciones[$i]['mes']?>,<?=$variaciones[$i]['semana']?>,'<?=$variaciones[$i]['origen']?>','<?=$variaciones[$i]['codigo']?>')"><i class="fa fa-upload"></i></button>
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </table>
                                <label>Observaciones Generales:</label>
                                <textarea style="width: 100%" rows="5" name="observacion"><?=$observacion?></textarea>
                                <div style="padding-top: 10px;">
                                    <button class="btn btn-primary" type="button" onclick="guardar()">
                                        <i class="fa fa-save"></i> Guardar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>