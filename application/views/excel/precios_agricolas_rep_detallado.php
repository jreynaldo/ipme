    <Worksheet ss:Name="Alignment">
        <Table>
            <Row ss:Index="1">
                <Cell ss:Index="1">
                    <Data ss:Type="String">Cotizaciones Productos Agrícolas</Data>
                </Cell>
            </Row>
            <Row ss:Index="4">
                <Cell ss:Index="1">
                    <Data ss:Type="String">Departamento</Data>
                </Cell>
                <Cell ss:Index="2">
                    <Data ss:Type="String">Mercado</Data>
                </Cell>
                <Cell ss:Index="3">
                    <Data ss:Type="String">Id_producto</Data>
                </Cell>
                <Cell ss:Index="4">
                    <Data ss:Type="String">Año</Data>
                </Cell>
                <Cell ss:Index="5">
                    <Data ss:Type="String">Mes</Data>
                </Cell>
                <Cell ss:Index="6">
                    <Data ss:Type="String">Semana</Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String">Codigo</Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String">Producto</Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String">Especificacion</Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String">Unidad</Data>
                </Cell>
                <Cell ss:Index="11">
                    <Data ss:Type="String">Equivalencia</Data>
                </Cell>
                <Cell ss:Index="12">
                    <Data ss:Type="String">Pre1</Data>
                </Cell>
                <Cell ss:Index="13">
                    <Data ss:Type="String">Pre2</Data>
                </Cell>
                <Cell ss:Index="14">
                    <Data ss:Type="String">Pre3</Data>
                </Cell>
                <Cell ss:Index="15">
                    <Data ss:Type="String">Pre4</Data>
                </Cell>
                <Cell ss:Index="16">
                    <Data ss:Type="String">Pre5</Data>
                </Cell>
                <Cell ss:Index="17">
                    <Data ss:Type="String">Pre6</Data>
                </Cell>
                <Cell ss:Index="18">
                    <Data ss:Type="String">Jus1</Data>
                </Cell>
                <Cell ss:Index="19">
                    <Data ss:Type="String">Jus2</Data>
                </Cell>
                <Cell ss:Index="20">
                    <Data ss:Type="String">Jus3</Data>
                </Cell>
                <Cell ss:Index="21">
                    <Data ss:Type="String">Jus4</Data>
                </Cell>
                <Cell ss:Index="22">
                    <Data ss:Type="String">Jus5</Data>
                </Cell>
                <Cell ss:Index="23">
                    <Data ss:Type="String">Jus6</Data>
                </Cell>
                <Cell ss:Index="24">
                    <Data ss:Type="String">Proc1</Data>
                </Cell>
                <Cell ss:Index="25">
                    <Data ss:Type="String">Proc2</Data>
                </Cell>
                <Cell ss:Index="26">
                    <Data ss:Type="String">Proc3</Data>
                </Cell>
                <Cell ss:Index="27">
                    <Data ss:Type="String">Proc4</Data>
                </Cell>
                <Cell ss:Index="28">
                    <Data ss:Type="String">Proc5</Data>
                </Cell>
                <Cell ss:Index="29">
                    <Data ss:Type="String">Proc6</Data>
                </Cell>
                <Cell ss:Index="30">
                    <Data ss:Type="String">Variación</Data>
                </Cell>
                <Cell ss:Index="31">
                    <Data ss:Type="String">Situación</Data>
                </Cell>
                <Cell ss:Index="32">
                    <Data ss:Type="String">Observaciones</Data>
                </Cell>
            </Row>
            <?php for ($i = 0; $i < count($reporte); $i++) : ?>
            <Row ss:Index="<?= $i + 5; ?>">
                <Cell ss:Index="1">
                    <Data ss:Type="String"><?= $reporte[$i]['departamento']; ?></Data>
                </Cell>
                <Cell ss:Index="2">
                    <Data ss:Type="String"><?= $reporte[$i]['mercado']; ?></Data>
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
                    <Data ss:Type="Number"><?= $reporte[$i]['semana'] ?></Data>
                </Cell>
                <Cell ss:Index="7">
                    <Data ss:Type="String"><?= $reporte[$i]['codigo'] ?></Data>
                </Cell>
                <Cell ss:Index="8">
                    <Data ss:Type="String"><?= $reporte[$i]['producto'] ?></Data>
                </Cell>
                <Cell ss:Index="9">
                    <Data ss:Type="String"><?= $reporte[$i]['especificacion'] ?></Data>
                </Cell>
                <Cell ss:Index="10">
                    <Data ss:Type="String"><?= $reporte[$i]['unidad'] ?></Data>
                </Cell>
                <Cell ss:Index="11">
                    <Data ss:Type="String"><?= $reporte[$i]['equivalencia'] ?></Data>
                </Cell>
                <?php if ($reporte[$i]['pre1'] != '') : ?>
                <Cell ss:Index="12">
                    <Data ss:Type="Number"><?= $reporte[$i]['pre1'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['pre2'] != '') :?>
                <Cell ss:Index="13">
                    <Data ss:Type="Number"><?= $reporte[$i]['pre2'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['pre3'] != '') :?>
                <Cell ss:Index="14">
                    <Data ss:Type="Number"><?= $reporte[$i]['pre3'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['pre4'] != '') :?>
                <Cell ss:Index="15">
                    <Data ss:Type="Number"><?= $reporte[$i]['pre4'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['pre5'] != '') :?>
                <Cell ss:Index="16">
                    <Data ss:Type="Number"><?= $reporte[$i]['pre5'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['pre6'] != '') :?>
                <Cell ss:Index="17">
                    <Data ss:Type="Number"><?= $reporte[$i]['pre6'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['jus1'] != '') :?>
                <Cell ss:Index="18">
                    <Data ss:Type="String"><?= $reporte[$i]['jus1'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['jus2'] != '') :?>
                <Cell ss:Index="19">
                    <Data ss:Type="String"><?= $reporte[$i]['jus2'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['jus3'] != '') :?>
                <Cell ss:Index="20">
                    <Data ss:Type="String"><?= $reporte[$i]['jus3'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['jus4'] != '') :?>
                <Cell ss:Index="21">
                    <Data ss:Type="String"><?= $reporte[$i]['jus4'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['jus5'] != '') :?>
                <Cell ss:Index="22">
                    <Data ss:Type="String"><?= $reporte[$i]['jus5'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['jus6'] != '') :?>
                <Cell ss:Index="23">
                    <Data ss:Type="String"><?= $reporte[$i]['jus6'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['proc1'] != '') :?>
                <Cell ss:Index="24">
                    <Data ss:Type="String"><?= $reporte[$i]['proc1'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['proc2'] != '') :?>
                <Cell ss:Index="25">
                    <Data ss:Type="String"><?= $reporte[$i]['proc2'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['proc3'] != '') :?>
                <Cell ss:Index="26">
                    <Data ss:Type="String"><?= $reporte[$i]['proc3'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['proc4'] != '') :?>
                <Cell ss:Index="27">
                    <Data ss:Type="String"><?= $reporte[$i]['proc4'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['proc5'] != '') :?>
                <Cell ss:Index="28">
                    <Data ss:Type="String"><?= $reporte[$i]['proc5'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['proc6'] != '') :?>
                <Cell ss:Index="29">
                    <Data ss:Type="String"><?= $reporte[$i]['proc6'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['variacion'] != '') :?>
                <Cell ss:Index="30">
                    <Data ss:Type="String"><?= $reporte[$i]['variacion'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['situacion'] != '') :?>
                <Cell ss:Index="31">
                    <Data ss:Type="String"><?= $reporte[$i]['situacion'] ?></Data>
                </Cell>
                <?php endif; if ($reporte[$i]['observaciones'] != '') :?>
                <Cell ss:Index="32">
                    <Data ss:Type="String"><?= $reporte[$i]['observaciones'] ?></Data>
                </Cell>
                <?php endif;?>
            </Row>
            <?php endfor; ?>
        </Table>
    </Worksheet>
</Workbook>