<script type="text/javascript">
    function depto_change() {
        $.post('ciudad', {
            id: $('#depto').val()
        }, function(result) {
            $('#ciudad').html(result);
        });
    }
</script>
<form id="form_merc" role="form">
    <div class="form-group">
        <label>Departamento:</label>
        <?= form_dropdown('depto', $departamentos, $id_departamento, 'id="depto" class="form-control" onchange="depto_change()"'); ?>
    </div>
    <div class="form-group">
        <label>Ciudad:</label>
        <?= form_dropdown('ciudad', $ciudades, $id_ciudad, 'id="ciudad" class="form-control"'); ?>
    </div>
    <div class="form-group">
        <label>Mercado:</label>
        <input name="mercado" type="text" id="mercado" value="<?= $mercado ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Zona:</label>
        <input name="zona" type="text" id="zona" value="<?= $zona ?>" class="form-control"/>
    </div>
    <?php if ($id != FALSE) : ?>
        <input name="id" type="hidden" value="<?= $id ?>"/>
        <div class="form-group">
            <label>Justifique:</label>
            <input name="justificacion" type="text" id="justificacion" class="form-control"/>
        </div>
    <?php endif; ?>
</form>
<script type="text/javascript">
    $("#depto").select2({width: "resolve"});
    $("#ciudad").select2({width: "resolve"});
</script>