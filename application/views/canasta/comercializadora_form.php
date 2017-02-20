<script type="text/javascript">
    function depto_change() {
        $.post('ciudad', {
            id: $('#depto').val()
        }, function(result) {
            $('#ciudad').html(result);
        });
    }
</script>
<form id="form_comer">
    <div class="form-group">
        <label>Departamento:</label>
        <?= form_dropdown('depto', $departamentos, $id_departamento, 'id="depto" class="form-control" onchange="depto_change()"'); ?>
    </div>
    <div class="form-group">
        <label>Ciudad:</label>
        <?= form_dropdown('ciudad', $ciudades, $id_ciudad, 'id="ciudad" class="form-control"'); ?>
    </div>
    <div class="form-group">
        <label>Nit:</label>
        <input name="nit" type="text" id="nit" value="<?= $nit; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Regine:</label>
        <input name="regine" type="text" id="regine" value="<?= $regine; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Comercializadora:</label>
        <input name="descripcion" type="text" id="descripcion" value="<?= $descripcion; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Nombre Informante:</label>
        <input name="nombre_informante" type="text" id="nombre_informante" value="<?= $nombre_informante; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Direcci&oacute;n:</label>
        <input name="direccion" type="text" id="direccion" value="<?= $direccion; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Numero:</label>
        <input name="numero" type="text" id="numero" value="<?= $numero; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Entre Calles:</label>
        <input name="entre_calles" type="text" id="entre_calles" value="<?= $entre_calles; ?>" class="form-control"/>
    </div>
    <div>
        <label>Edificio:</label>
        <input name="edificio" type="text" id="edificio" value="<?= $edificio; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Piso:</label>
        <input name="piso" type="text" id="piso" value="<?= $piso; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Oficina:</label>
        <input name="oficina" type="text" id="oficina" value="<?= $oficina; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Zona:</label>
        <input name="zona" type="text" id="zona" value="<?= $zona; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Referencia:</label>
        <input name="referencia" type="text" id="referencia" value="<?= $referencia; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Telefono:</label>
        <input name="telefono" type="text" id="telefono" value="<?= $telefono; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Fax:</label>
        <input name="fax" type="text" id="fax" value="<?= $fax; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Casilla:</label>
        <input name="casilla" type="text" id="casilla" value="<?= $casilla; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>email:</label>
        <input name="e_mail" type="text" id="e_mail" value="<?= $e_mail; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Pagina WEB:</label>
        <input name="pagina_web" type="text" id="pagina_web" value="<?= $pagina_web; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Carga:</label>
        <input name="carga" type="text" id="carga" value="<?= $carga; ?>" class="form-control"/>
    </div>
        <?php if ($id != FALSE) : ?>
            <input name="id" type="hidden" value="<?= $id; ?>"/>
            <div class="form-group">
                <label>Justifique:</label>
                <input name="justificacion" type="text" id="justificacion" class="form-control"/>
            </div>
        <?php endif; ?>
</form>
<script type="text/javascript">
    $("#depto").select2({width: "resolve"});
    $("#ciudad").select2({width: "resolve"});
    $("#nit").keydown(function(event){return numero(event);});
    $("#regine").keydown(function(event){return numero(event);});
</script>