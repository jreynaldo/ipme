<script type="text/javascript">
    function agricola() {
        var depto = $('input:radio[name=depto]:checked').val();
        if (typeof(depto) !== 'undefined') {
            window.location = '../pdf/catalogo?tipo=1&depto=' + depto;
        } else {
            alert('Debe seleccionar un departamento.');
        }
    }
    function manufacturado() {
        var depto = $('input:radio[name=depto]:checked').val();
        if (typeof(depto) !== 'undefined') {
            window.location = '../pdf/catalogo?tipo=2&depto=' + depto;
        } else {
            alert('Debe seleccionar un departamento.');
        }
    }
    function init() { };
</script>
<div id="page-wrapper">
    <div class="row">
        <div class="row" style="padding-left: 20px; padding-right: 20px; height: 75vh;">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="page-header"><?= $title ?></h2>
                </div>
                <div class="col-lg-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-location-arrow"></i> Seleccione Departamento
                        </div>
                        <div class="panel-body">
                            <?php $keys = array_keys($departamento);
                            for ($i = 0; $i < count($keys); $i++) : ?>
                            <div class="radio">
                                <label><input type="radio" name="depto" value="<?=$keys[$i]?>"><?=$departamento[$keys[$i]]?></label>
                            </div>
                            <?php endfor; ?>
                            <div>&nbsp;</div>
                            <div class="control-group">
                                <button class="btn btn-primary" type="button" onclick="agricola()">
                                    <i class="fa fa-apple"></i> Agricola
                                </button>
                                <button class="btn btn-primary" type="button" onclick="manufacturado()">
                                    <i class="fa fa-chain"></i> Manufacturado
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>