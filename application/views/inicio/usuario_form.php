<form id="form_usuario">
    <div class="form-group">
        <label>Usuario:</label>
        <input name="login" type="text" id="login" value="<?= $login; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Estado:</label>
        <?= form_dropdown('activo', $estado, $activo, 'id="activo" class="form-control"'); ?>
    </div>
    <div class="form-group">
        <label>CI:</label>
        <input name="carnet" type="text" id="carnet" value="<?= $carnet; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Nombre:</label>
        <input name="nombre" type="text" id="nombre" value="<?= $nombre; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Apellido paterno:</label>
        <input name="paterno" type="text" id="paterno" value="<?= $paterno; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Apellido Materno:</label>
        <input name="materno" type="text" id="materno" value="<?= $materno; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Direcci&oacute;n:</label>
        <input name="direccion" type="text" id="direccion" value="<?= $direccion; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Tel&eacute;fono:</label>
        <input name="telefono" type="text" id="telefono" value="<?= $telefono; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Departamento:</label>
        <?= form_dropdown('id_departamento', $departamentos, $id_departamento, 'id="id_departamento" class="form-control"'); ?>
    </div>
    <div class="form-group">
        <label>Grupo:</label>
        <?= form_dropdown('id_grupo', $grupos, $id_grupo, 'id="id_grupo" class="form-control"'); ?>
    </div>
    <div class="form-group">
        <label>Serie Movil:</label>
        <input name="serie" type="text" id="serie" value="<?= $serie; ?>" class="form-control"/>
    </div>
    <input name="id_proyecto" type="hidden" value="<?= $id_proyecto; ?>"/>
    <?php if ($id !== FALSE) : ?>
        <input name="id_usuario" type="hidden" id="id_usuario" value="<?= $id; ?>"/>
    <?php endif; ?>
</form>
<script type="text/javascript">
    $("#id_departamento").select2({width: "resolve"});
    $("#id_grupo").select2({width: "resolve"});
    $("#carnet").keydown(function(event){return numero(event);});
    $("#telefono").keydown(function(event){return numero(event);});
</script>