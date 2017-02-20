    <Worksheet ss:Name="Alignment">
        <Table>
            <Row ss:Index="1">
                <Cell ss:Index="1">
                    <Data ss:Type="String">Cotizaciones Productos Manufacturados</Data>
                </Cell>
            </Row>
            <Row ss:Index="4">
                <Cell ss:Index="1">
                    <Data ss:Type="String">Departamento</Data>
                </Cell>
                <Cell ss:Index="2">
                    <Data ss:Type="String">Comercializadora</Data>
                </Cell>
                <Cell ss:Index="3">
                    <Data ss:Type="String">Id Producto</Data>
                </Cell>
                <Cell ss:Index="4">
                    <Data ss:Type="String">Año</Data>
                </Cell>
                <Cell ss:Index="5">
                    <Data ss:Type="String">Mes</Data>
                </Cell>
                <Cell ss:Index="6">
                    <Data ss:Type="String">Codigo</Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String">Producto</Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String">Especificacion</Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String">Unidad/Tall/Peso</Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String">Marca</Data>
                </Cell>
                <Cell ss:Index="11">
                    <Data ss:Type="String">Modelo</Data>
                </Cell>
                <Cell ss:Index="12">
                    <Data ss:Type="String">Cantidad</Data>
                </Cell>
                <Cell ss:Index="13">
                    <Data ss:Type="String">Unidad</Data>
                </Cell>
                <Cell ss:Index="14">
                    <Data ss:Type="String">Envase</Data>
                </Cell>
                <Cell ss:Index="15">
                    <Data ss:Type="String">Origen</Data>
                </Cell>
                <Cell ss:Index="16">
                    <Data ss:Type="String">Precio</Data>
                </Cell>
                <Cell ss:Index="17">
                    <Data ss:Type="String">Justificación</Data>
                </Cell>
                <Cell ss:Index="18">
                    <Data ss:Type="String">Variación</Data>
                </Cell>
                <Cell ss:Index="19">
                    <Data ss:Type="String">Situación</Data>
                </Cell>
                <Cell ss:Index="20">
                    <Data ss:Type="String">Observaciones</Data>
                </Cell>
            </Row>
            <?php for ($i = 0; $i < count($reporte); $i++) : ?>
            <Row ss:Index="<?= $i + 5; ?>">
                <Cell ss:Index="1">
                    <Data ss:Type="String"><?= $reporte[$i]['departamento']; ?></Data>
                </Cell>
                <Cell ss:Index="2">
                    <Data ss:Type="String"><?= $reporte[$i]['comercializadora']; ?></Data>
                </Cell>
                <Cell ss:Index="3">
                    <Data ss:Type="Number"><?= $reporte[$i]['id_producto']; ?></Data>
                </Cell>
                <Cell ss:Index="4">
                    <Data ss:Type="Number"><?= $reporte[$i]['gestion'] ?></Data>
                </Cell>
                <Cell ss:Index="5">
                    <Data ss:Type="Number"><?= $reporte[$i]['mes'] ?></Data>
                </Cell>
                <Cell ss:Index="6">
                    <Data ss:Type="String"><?= $reporte[$i]['codigo'] ?></Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String"><?= $reporte[$i]['producto'] ?></Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String"><?= $reporte[$i]['especificacion'] ?></Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad_talla_peso'] ?></Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String"><?= $reporte[$i]['marca'] ?></Data>
                </Cell>
                <Cell ss:Index="11">
                    <Data ss:Type="String"><?= $reporte[$i]['modelo'] ?></Data>
                </Cell>
                <Cell ss:Index="12">
                    <Data ss:Type="String"><?= $reporte[$i]['cantidad'] ?></Data>
                </Cell>
                <Cell ss:Index="13">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad'] ?></Data>
                </Cell>
                <Cell ss:Index="14">
                    <Data ss:Type="String"><?= $reporte[$i]['envase'] ?></Data>
                </Cell>
                <Cell ss:Index="15">
                    <Data ss:Type="String"><?= $reporte[$i]['origen'] ?></Data>
                </Cell>
                <?php if ($reporte[$i]['precio'] != '') : ?>
                <Cell ss:Index="16">
                    <Data ss:Type="Number"><?= $reporte[$i]['precio'] ?></Data>
                </Cell>
                <?php endif;?>
                <Cell ss:Index="17">
                    <Data ss:Type="String"><?= $reporte[$i]['justificacion'] ?></Data>
                </Cell>
                <Cell ss:Index="18">
                    <Data ss:Type="String"><?= $reporte[$i]['variacion'] ?></Data>
                </Cell>
                <Cell ss:Index="19">
                    <Data ss:Type="String"><?= $reporte[$i]['situacion'] ?></Data>
                </Cell>
                <Cell ss:Index="20">
                    <Data ss:Type="String"><?= $reporte[$i]['observaciones'] ?></Data>
                </Cell>
            </Row>
            <?php endfor; ?>
        </Table>
    </Worksheet>
</Workbook>