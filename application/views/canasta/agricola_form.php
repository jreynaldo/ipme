<form id="form_agric">
    <div class="form-group">
        <label>Codigo:</label>
        <div class="input-group">
            <input name="codigo" type="text" id="codigo" value="<?= $codigo; ?>" class="form-control"/>
            <span class="input-group-btn">
                <a class="btn btn-primary" onclick="clasificador()">Clasificador</a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label>Producto:</label>
        <?= form_dropdown('producto', $cpca, $producto, 'id="producto" class="form-control"') ?>
    </div>
    <div class="form-group">
        <label>Especificacion:</label>
        <input name="especificacion" type="text" id="especificacion" value="<?= $especificacion ?>" class="form-control"/>
    </div>
    <?php if ($id != FALSE) : ?>
        <div class="form-group">
            <label>Unidad Inicial:</label>
            <label class="form-control" class="form-control">&nbsp;<?= $cantidad_inicial.' '.$unidad_inicial ?></label>
        </div>
        <div class="form-group">
            <label>Factor de ajuste:</label>
            <input name="factor_ajuste" type="text" id="factor_ajuste" value="<?= $factor_ajuste ?>" class="form-control"/>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label>Cantidad a Cotizar:</label>
        <input name="cantidad_a_cotizar" type="text" id="cantidad_a_cotizar" value="<?= $cantidad_a_cotizar ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Unidad a cotizar:</label>
        <input name="unidad_a_cotizar" type="text" id="unidad_a_cotizar" value="<?= $unidad_a_cotizar ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Cant. equivalente:</label>
        <input name="cantidad_equivalente" type="text" id="cantidad_equivalente" value="<?= $cantidad_equivalente ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Unid. convencional:</label>
        <?php $unidades = Array('LIBRA(S)' => 'LIBRA(S)', 'CUARTILLA(S)' => 'CUARTILLA(S)', 'ARROBA(S)' => 'ARROBA(S)', 'QUINTAL(ES)' => 'QUINTAL(ES)', 'MILIGRAMO(S)' => 'MILIGRAMO(S)', 'GRAMO(S)' => 'GRAMO(S)', 'KILOGRAMO(S)' => 'KILOGRAMO(S)', 'MILILITRO(S)' => 'MILILITRO(S)', 'LITRO(S)' => 'LITRO(S)', 'UNIDAD(ES)' => 'UNIDAD(ES)', 'PAR(ES)' => 'PAR(ES)');
        echo form_dropdown('unidad_convencional', $unidades, $unidad_convencional, 'id="unidad_convencional" class="form-control"'); ?>
    </div>
    <div class="form-group">
        <label>Origen:</label>
        <?php $origens = Array('NACIONAL' => 'NACIONAL', 'IMPORTADO' => 'IMPORTADO');
        echo form_dropdown('origen', $origens, $origen, 'id="origen" class="form-control"'); ?>
    </div>
    <?php if ($id != FALSE) : ?>
        <div class="form-group">
            <label>Justifique:</label>
            <input name="justificacion" type="text" id="justificacion" class="form-control"/>
            <input name="id" type="hidden" value="<?= $id ?>"/>
        </div>
    <?php endif; ?>
    <input name="id_informador" type="hidden" value="<?= $id_informador ?>"/>
</form>
<script type="text/javascript">
    $("#producto").select2({width: "resolve"});
    $("#codigo").keydown(function(event){return numero(event);});
    $("#factor_ajuste").keydown(function(event){return decimal(event);});
    $("#cantidad_a_cotizar").keydown(function(event){return decimal(event);});
    $("#cantidad_equivalente").keydown(function(event){return decimal(event);});
</script>