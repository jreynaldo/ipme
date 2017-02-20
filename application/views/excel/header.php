<?xml version="1.0"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook
    xmlns="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:o="urn:schemas-microsoft-com:office:office"
    xmlns:x="urn:schemas-microsoft-com:office:excel"
    xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
    xmlns:html="http://www.w3.org/TR/REC-html40">
    
    <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
        <Title><?= $title ?></Title>
        <Author><?= $author ?></Author>
        <Created><?= $date ?></Created>
        <Manager>INE</Manager>
        <Company>INE</Company>
        <Version>1</Version>
    </DocumentProperties>
    
    <ExcelWorkbook xmlns="urn:schemas-microsoft-com:office:excel"/>
    
    <Styles>
        <Style ss:ID="Default" ss:Name="Normal">
            <Alignment ss:Vertical="Bottom"/>
            <NumberFormat/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Red" ss:Name="Red">
            <Font ss:Color="#8F0000"/>
            <Alignment ss:Vertical="Bottom"/>
            <NumberFormat/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Titulo" ss:Name="Titulo">
            <Font ss:Bold="1" ss:Size="12"/>
            <Alignment ss:Horizontal="Center" ss:WrapText="1"/>
            <NumberFormat/>
        </Style>
        
        <Style ss:ID="Header" ss:Name="Header">
            <Font ss:Bold="1"/>
            <Alignment ss:Horizontal="Center" ss:WrapText="1"/>
            <NumberFormat/>
        </Style>
        
        <Style ss:ID="HeaderBorder" ss:Name="HeaderBorder">
            <Font ss:Bold="1" ss:Size="9"/>
            <Alignment ss:Horizontal="Center" ss:WrapText="1"/>
            <NumberFormat/>
            <Borders>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="CellBorder" ss:Name="CellBorder">
            <Font ss:Size="9"/>
            <Alignment ss:Horizontal="Justify" ss:WrapText="1"/>
            <NumberFormat/>
            <Borders>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="Right" ss:Name="Right">
            <Alignment ss:Vertical="Bottom"/>
            <NumberFormat/>
            <Borders>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="Left" ss:Name="Left">
            <Alignment ss:Vertical="Bottom"/>
            <NumberFormat/>
            <Borders>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="Week" ss:Name="Week">
            <Font ss:Bold="1"/>
            <Alignment ss:Horizontal="Center"/>
            <NumberFormat/>
            <Protection/>
            <Borders>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="BottomLeft" ss:Name="BottomLeft">
            <Font ss:Bold="1"/>
            <Alignment ss:Horizontal="Center"/>
            <NumberFormat/>
            <Protection/>
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="Bottom" ss:Name="Bottom">
            <Font ss:Bold="1"/>
            <Alignment ss:Horizontal="Center"/>
            <NumberFormat/>
            <Protection/>
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="BottomRight" ss:Name="BottomRight">
            <Font ss:Bold="1"/>
            <Alignment ss:Horizontal="Center"/>
            <NumberFormat/>
            <Protection/>
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>
        
        <Style ss:ID="PrecioGray" ss:Name="PrecioGray">
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
            <Interior ss:Color="#dddddd" ss:Pattern="Solid"/>
        </Style>
        
        <Style ss:ID="Precio" ss:Name="Precio">
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio20" ss:Name="Precio20">
            <Font ss:Color="#0000ff"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio21" ss:Name="Precio21">
            <Font ss:Color="#000066"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio30" ss:Name="Precio30">
            <Font ss:Color="#00ff00"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio31" ss:Name="Precio31">
            <Font ss:Color="#55dd55"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio40" ss:Name="Precio40">
            <Font ss:Color="#00ff00"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio41" ss:Name="Precio41">
            <Font ss:Color="#55dd55"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio50" ss:Name="Precio50">
            <Font ss:Color="#ff0000"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="Precio51" ss:Name="Precio51">
            <Font ss:Color="#dd5555"/>
            <NumberFormat ss:Format="#.00"/>
            <Protection/>
        </Style>
        
        <Style ss:ID="db_header">
            <Font ss:Color="#0000ff" ss:Bold="1" x:Family="Swiss"/>
            <NumberFormat/>
            <Protection/>
            <Borders>
                <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
                <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
            </Borders>
        </Style>

        <Style ss:ID="db_datetime">
            <NumberFormat ss:Format="mm/dd/yy\ hh:mm:ss"/>
            <Protection/>
        </Style>

        <Style ss:ID="db_date">
            <NumberFormat ss:Format="mm/dd/yy"/>
            <Protection/>
        </Style>

        <Style ss:ID="db_time">
            <NumberFormat ss:Format="hh:mm:ss"/>
            <Protection/>
        </Style>

        <Style ss:ID="left_rotate60_big">
            <Alignment ss:Horizontal="Left" ss:Rotate="60"/>
            <Font ss:Size="18" ss:Color="Automatic"/>
            <NumberFormat/>
            <Protection/>
        </Style>

        <Style ss:ID="verticaltext_left">
            <Alignment  ss:Horizontal="Left" ss:VerticalText="1"/>
            <NumberFormat/>
            <Protection/>
        </Style>

        <Style ss:ID="wraptext_top" >
            <Alignment ss:Vertical="Top" ss:WrapText="1"/>
            <NumberFormat/>
            <Protection/>
        </Style>

        <Style ss:ID="my style" >
            <Font ss:Color="#FFFFFF" ss:Bold="1" ss:Italic="1" ss:Underline="DoubleAccounting"/>
            <Interior ss:Color="#000000" ss:Pattern="Solid"/>
            <NumberFormat ss:Format="mm/dd/yy\ hh:mm:ss"/>
            <Protection/>
        </Style>

        <Style ss:ID="formatErrorsHeader" >
            <Font ss:Color="Automatic" ss:Bold="1"/>
            <Interior ss:Color="#FF0000" ss:Pattern="Solid"/>
            <NumberFormat/>
            <Protection/>
        </Style>
    </Styles>