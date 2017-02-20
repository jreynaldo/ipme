    <Worksheet ss:Name="Alignment">
        <Table>
            <Column ss:Index="1" ss:Width="80"/>
            <Column ss:Index="2" ss:Width="140"/>
            <Column ss:Index="3" ss:Width="70"/>
            <Column ss:Index="4" ss:Width="170"/>
            <Row ss:Index="2">
                <Cell ss:Index="1" ss:MergeAcross="3" ss:StyleID="Titulo">
                    <Data ss:Type="String">IPM - DIRECTORIO DE MERCADOS</Data>
                </Cell>
            </Row>
            <Row ss:Index="4" ss:AutoFitHeight="1">
                <Cell ss:Index="1" ss:StyleID="HeaderBorder">
                    <Data ss:Type="String">NOMBRE DEPARTAMENTO</Data>
                </Cell>
                <Cell ss:Index="2" ss:StyleID="HeaderBorder">
                    <Data ss:Type="String">RAZON SOCIAL</Data>
                </Cell>
                <Cell ss:Index="3" ss:StyleID="HeaderBorder">
                    <Data ss:Type="String">ZONA</Data>
                </Cell>
                <Cell ss:Index="4" ss:StyleID="HeaderBorder">
                    <Data ss:Type="String">DIRECCIÓN</Data>
                </Cell>
            </Row>
            <?php for ($i = 0; $i < count($reporte); $i++) : ?>
            <Row ss:Index="<?= $i + 5; ?>" ss:AutoFitHeight="1">
                <Cell ss:Index="1" ss:StyleID="CellBorder">
                    <Data ss:Type="String"><?= $reporte[$i]['departamento']; ?></Data>
                </Cell>
                <Cell ss:Index="2" ss:StyleID="CellBorder">
                    <Data ss:Type="String"><?= $reporte[$i]['descripcion']; ?></Data>
                </Cell>
                <Cell ss:Index="3" ss:StyleID="CellBorder">
                    <Data ss:Type="String"><?= $reporte[$i]['zona']; ?></Data>
                </Cell>
                <Cell ss:Index="4" ss:StyleID="CellBorder">
                    <Data ss:Type="String"><?= $reporte[$i]['direccion'] ?></Data>
                </Cell>
            </Row>
            <?php endfor; ?>
        </Table>
    </Worksheet>
</Workbook>