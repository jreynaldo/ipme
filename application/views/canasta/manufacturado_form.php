<form id="form_menu">
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
        <div class="input-group">
            <input name="producto" type="text" id="producto" value="<?= $producto; ?>" class="form-control"/>
            <span class="input-group-btn">
                <a class="btn btn-primary" onclick="info(<?= $id; ?>)">Cotizaci&oacute;n</a>
            </span>
        </div>
    </div>
    <div class="form-group">
        <label>Especificacion:</label>
        <input name="especificacion" type="text" id="especificacion" value="<?= $especificacion; ?>" class="form-control"/>
    </div>
    <?php if ($id != FALSE) : ?>
        <div class="form-group">
            <label>Base de cotizacion:</label>
            <label class="form-control">&nbsp;<?= $cantidad_inicial.' '.$unidad_inicial; ?></label>
        </div>
        <div class="form-group">
            <label>Factor de ajuste:</label>
            <input name="factor_ajuste" type="text" id="factor_ajuste" value="<?= $factor_ajuste; ?>" class="form-control"/>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <label>Cantidad a Cotizar:</label>
        <input name="cantidad_a_cotizar" type="text" id="cantidad_a_cotizar" value="<?= $cantidad_a_cotizar; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Unidad a cotizar:</label>
        <input name="unidad_a_cotizar" type="text" id="unidad_a_cotizar" value="<?= $unidad_a_cotizar; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Cant. equivalente:</label>
        <input name="cantidad_equivalente" type="text" id="cantidad_equivalente" value="<?= $cantidad_equivalente; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Unid. convencional:</label>
        <?php $unidades = Array('LIBRA(S)' => 'LIBRA(S)', 'CUARTILLA(S)' => 'CUARTILLA(S)', 'ARROBA(S)' => 'ARROBA(S)', 'QUINTAL(ES)' => 'QUINTAL(ES)', 'MILIGRAMO(S)' => 'MILIGRAMO(S)', 'GRAMO(S)' => 'GRAMO(S)', 'KILOGRAMO(S)' => 'KILOGRAMO(S)', 'TONELADA METRICA' => 'TONELADA METRICA', 'MILILITRO(S)' => 'MILILITRO(S)', 'LITRO(S)' => 'LITRO(S)', 'UNIDAD(ES)' => 'UNIDAD(ES)', 'PAR(ES)' => 'PAR(ES)', 'ROLLO(S)' => 'ROLLO(S)', 'PAQUETITO(S)' => 'PAQUETITO(S)', 'CAJITA(S)' => 'CAJITA(S)', 'METRO(S)' => 'METRO(S)', 'METRO(S)_CUADRADO(S)' => 'METRO(S)_CUADRADO(S)', 'PIE(S) CUADRADO(S)' => 'PIE(S) CUADRADO(S)');
            echo form_dropdown('unidad_convencional', $unidades, $unidad_convencional, 'id="unidad_convencional" class="form-control"');
        ?>
    </div>
    <div class="form-group">
        <label>Tam/Talla/Peso:</label>
        <input name="unidad_talla_peso" type="text" id="unidad_talla_peso" value="<?= $unidad_talla_peso; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Marca:</label>
        <input name="marca" type="text" id="marca" value="<?= $marca; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Modelo:</label>
        <input name="modelo" type="text" id="modelo" value="<?= $modelo; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Envase:</label>
        <input name="envase" type="text" id="envase" value="<?= $envase; ?>" class="form-control"/>
    </div>
    <div class="form-group">
        <label>Origen:</label>
        <?php $origens = Array('NACIONAL' => 'NACIONAL', 'IMPORTADO' => 'IMPORTADO');
            echo form_dropdown('origen', $origens, $origen, 'id="origen" class="form-control"');
        ?>
    </div>
    <div class="form-group">
        <label>Procedencia:</label>
        <input name="procedencia" type="text" id="procedencia" value="<?= $procedencia; ?>" class="form-control"/>
    </div>
    <?php if ($id != FALSE) : ?>
        <input name="id" type="hidden" value="<?= $id; ?>"/>
        <div class="form-group">
            <label>Justifique:</label>
            <input name="justificacion" type="text" id="justificacion" class="form-control"/>
        </div>
    <?php else : ?>
        <input name="id_informador" type="hidden" value="<?= $id_informador; ?>"/>
    <?php endif; ?>
    <div>
        <div style="margin-right: 10px; float: left;">
            <div><b>Imagen Producto</b></div>
            <div>
                <img id="img1" src="<?=site_url()?>/Imagen/temp1?id=<?=$id?>" onClick="image(this.id)" style="width: 150px; height: 150px;"/>
            </div>
        </div>
        <div>
            <div><b>Imagen Envase al Por Mayor</b></div>
            <div>
                <img id="img2" src="<?=site_url()?>/Imagen/temp2?id=<?=$id?>" onClick="image(this.id)" style="width: 150px; height: 150px;"/>
            </div>
        </div>
    </div>
</form>
<script type="text/javascript">
    $("#codigo").keydown(function(event){return numero(event);});
    $("#factor_ajuste").keydown(function(event){return decimal(event);});
    $("#cantidad_a_cotizar").keydown(function(event){return decimal(event);});
    $("#cantidad_equivalente").keydown(function(event){return decimal(event);});
</script>