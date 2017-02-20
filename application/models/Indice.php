<?php

/**
 * Description of Indice
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Indice extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_variable($variable) {
        $consulta = "SELECT valor FROM variable WHERE var = ?";
        $query = $this->db->query($consulta, $variable);
        return $query->row_array()['valor'];
    }
    
    public function set_variable($variable, $valor) {
        $consulta = "UPDATE variable SET valor = ? WHERE var = ?";
        $this->db->query($consulta, Array($valor, $variable));
        if ($this->db->affected_rows() == 1) {
            echo 'Ok';
        } else {
            echo 'Error inesperado.';
        }
    }
    
    public function get_etiquetas() {
        $consulta = "SELECT codigo, especificacion || ' (' || codigo || ')' especificacion
            FROM cat_clasificador
            WHERE codigo IN(SELECT codigo FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(codigo, 1, 8) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(codigo, 1, 6) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(codigo, 1, 4) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(codigo, 1, 2) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN('A', 'C')
            ORDER BY id_producto";
        $query = $this->db->query($consulta);
        $result = $query->result_array();
        $etiqueta = Array('periodo' => 'Periodo', 'Indice' => 'Indice', 'Incidencia' => 'Incidencia', 'agricola' => 'Agricola', 'manufacturado' => 'Manufacturado', 'nacional' => 'Nacional', 'importado' => 'Importado', 
            'general' => 'General', 'agricola_nacional' => 'Agricola Nacional', 'agricola_importado' => 'Agricola Importado', 'manufacturado_nacional' => 'Manufacturado Nacional', 'manufacturado_importado' => 'Manufacturado Importado',
            'alimentos' => 'Alimentos', 'no_alimentos' => 'No alimentos');
        foreach ($result as $row) {
            $etiqueta[$row['codigo']] = $row['especificacion'];
        }
        return $etiqueta;
    }
    
    public function get_etiquetas_cpc() {
        $consulta = "SELECT codigo, descripcion especificacion
            FROM cat_clasificador_cpc
            WHERE codigo IN(SELECT cpc FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(cpc, 1, 4) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(cpc, 1, 3) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(cpc, 1, 2) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(cpc, 1, 1) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))";
        $query = $this->db->query($consulta);
        $result = $query->result_array();
        $etiqueta = Array('periodo' => 'Periodo', 'Indice' => 'Indice', 'agricola' => 'Agricola', 'manufacturado' => 'Manufacturado', 'nacional' => 'Nacional', 'importado' => 'Importado', 'general' => 'General');
        foreach ($result as $row) {
            $etiqueta[$row['codigo']] = $row['especificacion'];
        }
        return $etiqueta;
    }
    
    public function get_etiquetas_ciiu() {
        $consulta = "SELECT codigo, descripcion especificacion
            FROM cat_clasificador_ciiu
            WHERE codigo IN(SELECT codigo_ciiu FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(codigo_ciiu, 1, 3) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))
            OR codigo IN(SELECT substring(codigo_ciiu, 1, 2) FROM seg_producto WHERE apiestado NOT IN('ANULADO', 'DESCARTADO'))";
        $query = $this->db->query($consulta);
        $result = $query->result_array();
        $etiqueta = Array('periodo' => 'Periodo', 'Indice' => 'Indice', 'agricola' => 'Agricola', 'manufacturado' => 'Manufacturado', 'nacional' => 'Nacional', 'importado' => 'Importado', 'general' => 'General');
        foreach ($result as $row) {
            $etiqueta[$row['codigo']] = $row['especificacion'];
        }
        return $etiqueta;
    }
    
    public function promediar() {
        $consulta = "SELECT if_promediar()";
        $query = $this->db->query($consulta);
        return $query->row_array()['if_promediar'];
    }

    public function imputar_agricolas() {
        $consulta = "SELECT if_imputar_agricolas()";
        $query = $this->db->query($consulta);
        return $query->row_array()['if_imputar_agricolas'];
    }
    
    public function imputar_agricolas2() {
        $consulta = "SELECT if_imputar_agricolas2()";
        $query = $this->db->query($consulta);
        return $query->row_array()['if_imputar_agricolas2'];
    }
    
    public function imputar_manufacturados() {
        $consulta = "SELECT if_imputar_manufacturados()";
        $query = $this->db->query($consulta);
        return $query->row_array()['if_imputar_manufacturados'];
    }
    
    public function imputar_manufacturados2() {
        $consulta = "SELECT if_imputar_manufacturados2()";
        $query = $this->db->query($consulta);
        return $query->row_array()['if_imputar_manufacturados2'];
    }
    
    public function encadenados() {
        $consulta = "SELECT if_encadenados()";
        $query = $this->db->query($consulta);
        return $query->row_array()['if_encadenados'];
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_imputado($sector, $informacion, $clasificacion, $codigos, $periodicidad, $perini, $perfin) {
        if ($periodicidad == 1) {
            $consulta = "SELECT string_agg('\"' || DATE_PART('YEAR', generate_series)::Int || '_' || TO_CHAR(generate_series, 'TMMONTH') || '\" Numeric', ',')
                FROM generate_series('2014-07-04', now(), '1 MONTH'::Interval)
                WHERE TO_CHAR(generate_series, 'YYYY-MM') >= ?
                AND TO_CHAR(generate_series, 'YYYY-MM') <= ?";
            $query = $this->db->query($consulta, Array($perini, $perfin));
        } else {
            $consulta = "SELECT string_agg('\"' || periodo || '\" Numeric', ',')
                FROM (SELECT distinct DATE_PART('YEAR', generate_series)::Int || '_' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') periodo
                FROM generate_series('2014-07-04', now(), '1 MONTH'::Interval)
                WHERE DATE_PART('YEAR', generate_series)::Int || '-' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') >= ?
                AND DATE_PART('YEAR', generate_series)::Int || '-' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') <= ?
                ORDER BY periodo) a";
            $query = $this->db->query($consulta, Array($periodicidad, $periodicidad, $perini, $periodicidad, $perfin));
        }
        $def = $query->row_array()['string_agg'];
        $consulta = "SELECT *
            FROM ar_imputado(?, ?, ?, ?, ?, ?)
            AS (ciudad Text, origen Text, codigo Text, especificacion Text, unidad_final Text, $def)";
        $query = $this->db->query($consulta, Array($sector, $clasificacion, $codigos, $periodicidad, $perini, $perfin));
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_imputado_nac($sector, $informacion, $clasificacion, $codigos, $periodicidad, $perini, $perfin) {
        if ($periodicidad == 1) {
            $consulta = "SELECT string_agg('\"' || DATE_PART('YEAR', generate_series)::Int || '_' || TO_CHAR(generate_series, 'TMMONTH') || '\" Numeric', ',')
                FROM generate_series('2014-07-04', now(), '1 MONTH'::Interval)
                WHERE TO_CHAR(generate_series, 'YYYY-MM') >= ?
                AND TO_CHAR(generate_series, 'YYYY-MM') <= ?";
            $query = $this->db->query($consulta, Array($perini, $perfin));
            
            $consulta2 = "SELECT string_agg('geom_mean(\"' || DATE_PART('YEAR', generate_series)::Int || '_' || TO_CHAR(generate_series, 'TMMONTH') || '\") AS \"' || DATE_PART('YEAR', generate_series)::Int || '_' || TO_CHAR(generate_series, 'TMMONTH') || '\"', ',')
                FROM generate_series('2014-07-04', now(), '1 MONTH'::Interval)
                WHERE TO_CHAR(generate_series, 'YYYY-MM') >= ?
                AND TO_CHAR(generate_series, 'YYYY-MM') <= ?";
            $query2 = $this->db->query($consulta2, Array($perini, $perfin));
        } else {
            $consulta = "SELECT string_agg('\"' || periodo || '\" Numeric', ',')
                FROM (SELECT distinct DATE_PART('YEAR', generate_series)::Int || '_' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') periodo
                FROM generate_series('2014-07-04', now(), '1 MONTH'::Interval)
                WHERE DATE_PART('YEAR', generate_series)::Int || '-' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') >= ?
                AND DATE_PART('YEAR', generate_series)::Int || '-' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') <= ?
                ORDER BY periodo) a";
            $query = $this->db->query($consulta, Array($periodicidad, $periodicidad, $perini, $periodicidad, $perfin));
            
            $consulta2 = "SELECT string_agg('geom_mean(\"' || periodo || '\") AS \"' || periodo || '\"', ',')
                FROM (SELECT distinct DATE_PART('YEAR', generate_series)::Int || '_' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') periodo
                FROM generate_series('2014-07-04', now(), '1 MONTH'::Interval)
                WHERE DATE_PART('YEAR', generate_series)::Int || '-' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') >= ?
                AND DATE_PART('YEAR', generate_series)::Int || '-' || lpad(ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::VarChar, 2, '0') <= ?
                ORDER BY periodo) a";
            $query2 = $this->db->query($consulta2, Array($periodicidad, $periodicidad, $perini, $periodicidad, $perfin));
        }
        $def = $query->row_array()['string_agg'];
        $lab = $query2->row_array()['string_agg'];
        $consulta = "SELECT origen, codigo, especificacion, unidad_final, $lab
            FROM ar_imputado(?, ?, ?, ?, ?, ?)
            AS ct(departamento Text, origen Text, codigo Text, especificacion Text, unidad_final Text, $def)
            GROUP BY origen, codigo, especificacion, unidad_final
            ORDER BY codigo, origen, especificacion";
        $query = $this->db->query($consulta, Array($sector, $clasificacion, $codigos, $periodicidad, $perini, $perfin));
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_reporte_productos_agricolas_mercados($cod, $gesini, $semini, $gesfin, $semfin, $id_depto = '%') {
        $consulta = "SELECT string_agg('\"' || CASE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016_01_53' THEN '2015_12_53' ELSE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\" Numeric', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $columns = $query->row_array()['string_agg'];
        $consulta = "SELECT string_agg('geom_mean(\"' || CASE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016_01_53' THEN '2015_12_53' ELSE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\" * p.factor) \"' || CASE to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016 ENERO 53' THEN '2015 DICIEMBRE 53' ELSE to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\"', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $labels = $query->row_array()['string_agg'];
        $consulta = "SELECT p.codigo, d.ciudad, i.descripcion mercado, p.producto, min(p.especificacion) especificacion, p.unidad_final unidad, $labels, d.id_departamento
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT id_producto, gestion || ''_'' || lpad(mes::VarChar, 2, ''0'') || ''_'' || lpad(semana::VarChar, 2, ''0''), cotizacion
                FROM cotizacion
                WHERE substring(codigo,1, 2) IN(''01'', ''02'', ''03'', ''04'')
                ORDER BY id_producto, gestion, mes, semana',
                'SELECT CASE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') WHEN ''2016_01_53'' THEN ''2015_12_53'' ELSE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') END
                FROM generate_series(''2014-07-04''::TimeStamp, now(), ''1 WEEK'')
                WHERE generate_series <> ''2016-01-01''::Timestamp') AS ct(id_producto Int, $columns)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = ct.id_producto
            AND p.id_boleta = 1 AND p.codigo IN($cod) AND d.id_departamento::Text LIKE '$id_depto'
            GROUP BY p.id_producto, d.id_departamento, p.codigo, d.ciudad, i.descripcion, p.producto, p.unidad_final
            UNION
            SELECT p.codigo, d.ciudad, i.descripcion mercado, p.producto, min(p.especificacion) especificacion, p.unidad_final unidad, $labels, d.id_departamento
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT p.id_producto, a.gestion || ''_'' || lpad(a.mes::Text, 2, ''0'') || ''_'' || lpad(a.semana::Text, 2, ''0''), geom_mean(respuesta::Numeric)
                FROM seg_asignacion a, seg_producto p, enc_informante i, enc_encuesta e
                WHERE a.id_asignacion = i.id_asignacion
                AND p.id_producto = i.id
                AND i.id_asignacion = e.id_asignacion
                AND i.correlativo = e.correlativo
                AND substring(p.codigo, 1, 2) NOT IN(''01'', ''02'', ''03'', ''04'')
                AND p.id_boleta = 1
                AND e.id_pregunta = 6
                GROUP BY p.id_producto, a.gestion, a.mes, a.semana, i.id_asignacion, i.correlativo
                ORDER BY p.id_producto, a.gestion, a.semana',
                'SELECT CASE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') WHEN ''2016_01_53'' THEN ''2015_12_53'' ELSE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') END
                FROM generate_series(''2014-07-04''::TimeStamp, now(), ''1 WEEK'')
                WHERE generate_series <> ''2016-01-01''::Timestamp') AS ct(id_producto Int, $columns)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = ct.id_producto
            AND p.id_boleta = 1 AND p.codigo IN($cod) AND d.id_departamento::Text LIKE '$id_depto'
            GROUP BY p.id_producto, d.id_departamento, p.codigo, d.ciudad, i.descripcion, p.producto, p.unidad_final
            ORDER BY id_departamento, codigo, mercado";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_reporte_productos_agricolas_departamental($cod, $gesini, $semini, $gesfin, $semfin, $id_depto = '%') {
        $consulta = "SELECT string_agg('\"' || CASE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016_01_53' THEN '2015_12_53' ELSE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\" Numeric', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $columns = $query->row_array()['string_agg'];
        $consulta = "SELECT string_agg('geom_mean(\"' || CASE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016_01_53' THEN '2015_12_53' ELSE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\" * p.factor) \"' || CASE to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016 ENERO 53' THEN '2015 DICIEMBRE 53' ELSE to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\"', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $labels = $query->row_array()['string_agg'];
        $consulta = "SELECT p.codigo, d.ciudad, p.producto, min(p.especificacion) especificacion, p.unidad_final unidad, $labels, d.id_departamento
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT id_producto, gestion || ''_'' || lpad(mes::VarChar, 2, ''0'') || ''_'' || lpad(semana::VarChar, 2, ''0''), cotizacion
                FROM cotizacion
                WHERE substring(codigo,1, 2) IN(''01'', ''02'', ''03'', ''04'')
                ORDER BY id_producto, gestion, mes',
                'SELECT CASE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') WHEN ''2016_01_53'' THEN ''2015_12_53'' ELSE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') END
                FROM generate_series(''2014-07-04''::TimeStamp, now(), ''1 WEEK'')
                WHERE generate_series <> ''2016-01-01''::Timestamp') AS ct(id_producto Int, $columns)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = ct.id_producto
            AND p.id_boleta = 1 AND p.codigo IN($cod) AND d.id_departamento::Text LIKE '$id_depto'
            GROUP BY d.id_departamento, p.codigo, d.ciudad, p.producto, p.unidad_final
            UNION
            SELECT p.codigo, d.ciudad, p.producto, min(p.especificacion) especificacion, p.unidad_final unidad, $labels, d.id_departamento
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT p.id_producto, a.gestion || ''_'' || lpad(a.mes::Text, 2, ''0'') || ''_'' || lpad(a.semana::Text, 2, ''0''), geom_mean(respuesta::Numeric)
                FROM seg_asignacion a, seg_producto p, enc_informante i, enc_encuesta e
                WHERE a.id_asignacion = i.id_asignacion
                AND p.id_producto = i.id
                AND i.id_asignacion = e.id_asignacion
                AND i.correlativo = e.correlativo
                AND substring(p.codigo, 1, 2) NOT IN(''01'', ''02'', ''03'', ''04'')
                AND p.id_boleta = 1
                AND e.id_pregunta = 6
                GROUP BY p.id_producto, a.gestion, a.mes, a.semana, i.id_asignacion, i.correlativo
                ORDER BY p.id_producto, a.gestion, a.semana',
                'SELECT CASE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') WHEN ''2016_01_53'' THEN ''2015_12_53'' ELSE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') END
                FROM generate_series(''2014-07-04''::TimeStamp, now(), ''1 WEEK'')
                WHERE generate_series <> ''2016-01-01''::Timestamp') AS ct(id_producto Int, $columns)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = ct.id_producto
            AND p.id_boleta = 1 AND p.codigo IN($cod) AND d.id_departamento::Text LIKE '$id_depto'
            GROUP BY d.id_departamento, p.codigo, d.ciudad, p.producto, p.unidad_final
            ORDER BY id_departamento, codigo";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_reporte_productos_agricolas_nacional($cod, $gesini, $semini, $gesfin, $semfin) {
        $consulta = "SELECT string_agg('\"' || CASE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016_01_53' THEN '2015_12_53' ELSE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\" Numeric', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $columns = $query->row_array()['string_agg'];
        $consulta = "SELECT string_agg('geom_mean(\"' || CASE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016_01_53' THEN '2015_12_53' ELSE to_char(generate_series, 'YYYY_MM_') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\" * p.factor) \"' || to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') || '\"', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $labels = $query->row_array()['string_agg'];
        $consulta = "SELECT string_agg('geom_mean(\"' || to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') || '\") \"' || CASE to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') WHEN '2016 ENERO 53' THEN '2015 DICIEMBRE 53' ELSE to_char(generate_series, 'YYYY TMMONTH ') || lpad(date_part('WEEK', generate_series)::VarChar, 2, '0') END || '\"', ',')
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 WEEK')
            WHERE generate_series <> '2016-01-01'::Timestamp";
        $query = $this->db->query($consulta);
        $labels2 = $query->row_array()['string_agg'];
        $consulta = "SELECT codigo, producto, min(especificacion) especificacion, unidad, $labels2
            FROM (SELECT p.codigo, d.descripcion departamento, p.producto, min(p.especificacion) especificacion, p.unidad_final unidad, $labels
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT id_producto, gestion || ''_'' || lpad(mes::VarChar, 2, ''0'') || ''_'' || lpad(semana::VarChar, 2, ''0''), cotizacion
                FROM cotizacion
                WHERE substring(codigo,1, 2) IN(''01'', ''02'', ''03'', ''04'')
                ORDER BY id_producto, gestion, mes',
                'SELECT CASE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') WHEN ''2016_01_53'' THEN ''2015_12_53'' ELSE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') END
                FROM generate_series(''2014-07-04''::TimeStamp, now(), ''1 WEEK'')
                WHERE generate_series <> ''2016-01-01''::Timestamp') AS ct(id_producto Int, $columns)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = ct.id_producto
            AND p.id_boleta = 1 AND p.codigo IN($cod) 
            GROUP BY d.id_departamento, p.codigo, d.descripcion, p.producto, p.unidad_final) d
            GROUP BY codigo, producto, unidad
            UNION
            SELECT codigo, producto, min(especificacion) especificacion, unidad, $labels2
            FROM (SELECT p.codigo, d.descripcion departamento, p.producto, min(p.especificacion) especificacion, p.unidad_final unidad, $labels
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT p.id_producto, a.gestion || ''_'' || lpad(a.mes::Text, 2, ''0'') || ''_'' || lpad(a.semana::Text, 2, ''0''), geom_mean(respuesta::Numeric)
                FROM seg_asignacion a, seg_producto p, enc_informante i, enc_encuesta e
                WHERE a.id_asignacion = i.id_asignacion
                AND p.id_producto = i.id
                AND i.id_asignacion = e.id_asignacion
                AND i.correlativo = e.correlativo
                AND substring(p.codigo, 1, 2) NOT IN(''01'', ''02'', ''03'', ''04'')
                AND p.id_boleta = 1
                AND e.id_pregunta = 6
                GROUP BY p.id_producto, a.gestion, a.mes, a.semana, i.id_asignacion, i.correlativo
                ORDER BY p.id_producto, a.gestion, a.semana',
                'SELECT CASE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') WHEN ''2016_01_53'' THEN ''2015_12_53'' ELSE to_char(generate_series, ''YYYY_MM_'') || lpad(date_part(''WEEK'', generate_series)::VarChar, 2, ''0'') END
                FROM generate_series(''2014-07-04''::TimeStamp, now(), ''1 WEEK'')
                WHERE generate_series <> ''2016-01-01''::Timestamp') AS ct(id_producto Int, $columns)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = ct.id_producto
            AND p.id_boleta = 1 AND p.codigo IN($cod)
            GROUP BY d.id_departamento, p.codigo, d.descripcion, p.producto, p.unidad_final) d
            GROUP BY codigo, producto, unidad
            ORDER BY codigo, producto";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_indice($sector, $clasificacion, $perini, $perfin) {
        if ($sector == 1) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(1, 10) AS p(periodo VarChar, "0101010001" Numeric,"0101030001" Numeric,"0101030002" Numeric,"0101040001" Numeric,"0101050101" Numeric,"0101050201" Numeric,"0102010101" Numeric,"0102010201" Numeric,"0102010301" Numeric,"0102010401" Numeric,"0102019901" Numeric,"0102019902" Numeric,"0102020101" Numeric,"0102020102" Numeric,"0102020201" Numeric,"0102020301" Numeric,"0102029901" Numeric,"0102030101" Numeric,"0102030201" Numeric,"0102030301" Numeric,"0102039901" Numeric,"0102039902" Numeric,"0102039903" Numeric,"0102040101" Numeric,"0102040201" Numeric,"0102040301" Numeric,"0102040401" Numeric,"0102040402" Numeric,"0102040501" Numeric,"0102049901" Numeric,"0102049902" Numeric,"0103010101" Numeric,"0103010102" Numeric,"0103010301" Numeric,"0103020101" Numeric,"0103020201" Numeric,"0103020301" Numeric,"0103020401" Numeric,"0104010101" Numeric,"0104010201" Numeric,"0104010301" Numeric,"0104010401" Numeric,"0104010501" Numeric,"0104020001" Numeric,"0104030101" Numeric,"0104030201" Numeric,"0104040101" Numeric,"0104040201" Numeric,"0104040301" Numeric,"0104040401" Numeric,"0104040402" Numeric,"0104040601" Numeric,"0104040701" Numeric,"0104040901" Numeric,"0105010001" Numeric,"0105020001" Numeric,"0105030101" Numeric,"0105030201" Numeric,"0201010001" Numeric,"0303020101" Numeric,"0403010101" Numeric,"0403010201" Numeric,"0403010301" Numeric,"0403010401" Numeric,"0403010501" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(1, 8) AS p(periodo VarChar, "01010100" Numeric,"01010300" Numeric,"01010400" Numeric,"01010501" Numeric,"01010502" Numeric,"01020101" Numeric,"01020102" Numeric,"01020103" Numeric,"01020104" Numeric,"01020199" Numeric,"01020201" Numeric,"01020202" Numeric,"01020203" Numeric,"01020299" Numeric,"01020301" Numeric,"01020302" Numeric,"01020303" Numeric,"01020399" Numeric,"01020401" Numeric,"01020402" Numeric,"01020403" Numeric,"01020404" Numeric,"01020405" Numeric,"01020499" Numeric,"01030101" Numeric,"01030103" Numeric,"01030201" Numeric,"01030202" Numeric,"01030203" Numeric,"01030204" Numeric,"01040101" Numeric,"01040102" Numeric,"01040103" Numeric,"01040104" Numeric,"01040105" Numeric,"01040200" Numeric,"01040301" Numeric,"01040302" Numeric,"01040401" Numeric,"01040402" Numeric,"01040403" Numeric,"01040404" Numeric,"01040406" Numeric,"01040407" Numeric,"01040409" Numeric,"01050100" Numeric,"01050200" Numeric,"01050301" Numeric,"01050302" Numeric,"02010100" Numeric,"03030201" Numeric,"04030101" Numeric,"04030102" Numeric,"04030103" Numeric,"04030104" Numeric,"04030105" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(1, 6) AS p(periodo VarChar, "010101" Numeric, "010103" Numeric, "010104" Numeric, "010105" Numeric, "010201" Numeric, "010202" Numeric, "010203" Numeric, "010204" Numeric, "010301" Numeric, "010302" Numeric, "010401" Numeric, "010402" Numeric, "010403" Numeric, "010404" Numeric, "010501" Numeric, "010502" Numeric, "010503" Numeric, "020101" Numeric, "030302" Numeric, "040301" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(1, 4) AS p(periodo VarChar, "0101" Numeric,"0102" Numeric,"0103" Numeric,"0104" Numeric,"0105" Numeric,"0201" Numeric,"0303" Numeric,"0403" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(1, 2) AS p(periodo VarChar, "01" Numeric,"02" Numeric,"03" Numeric,"04" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(1, 1) AS p(periodo Text, "Indice" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 2) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(2, 10) AS p(periodo VarChar, "0701010101" Numeric,"0701010201" Numeric,"0701020101" Numeric,"0701030101" Numeric,"0701040101" Numeric,"0702010101" Numeric,"0702010102" Numeric,"0702010103" Numeric,"0702010104" Numeric,"0702010105" Numeric,"0801010001" Numeric,"0801020101" Numeric,"0802010101" Numeric,"0802010201" Numeric,"0802010202" Numeric,"0802010301" Numeric,"0803010001" Numeric,"0803010002" Numeric,"0899990101" Numeric,"0899990201" Numeric,"0899990202" Numeric,"0899990203" Numeric,"0899990301" Numeric,"0899990501" Numeric,"0899990601" Numeric,"0899999901" Numeric,"0901010101" Numeric,"0901010201" Numeric,"0901010301" Numeric,"0902010101" Numeric,"0902019901" Numeric,"0902019902" Numeric,"0902019903" Numeric,"0903010001" Numeric,"0903020001" Numeric,"0903990101" Numeric,"0903990102" Numeric,"0903990201" Numeric,"0903990202" Numeric,"0903990203" Numeric,"0903990204" Numeric,"0903999901" Numeric,"0903999903" Numeric,"0904010101" Numeric,"0906010001" Numeric,"1001010101" Numeric,"1001019901" Numeric,"1102010001" Numeric,"1102020001" Numeric,"1102030001" Numeric,"1102040101" Numeric,"1102040201" Numeric,"1103010101" Numeric,"1201010001" Numeric,"1201010002" Numeric,"1202010101" Numeric,"1202010301" Numeric,"1202010302" Numeric,"1202010401" Numeric,"1203010101" Numeric,"1203010102" Numeric,"1203010401" Numeric,"1203010501" Numeric,"1203010601" Numeric,"1203010602" Numeric,"1204010101" Numeric,"1204010201" Numeric,"1204019901" Numeric,"1205010101" Numeric,"1205010102" Numeric,"1205010103" Numeric,"1205010104" Numeric,"1205010105" Numeric,"1205010106" Numeric,"1205010108" Numeric,"1205010109" Numeric,"1205010201" Numeric,"1205010202" Numeric,"1205010203" Numeric,"1205010301" Numeric,"1205010401" Numeric,"1205010501" Numeric,"1205019901" Numeric,"1205019902" Numeric,"1205019903" Numeric,"1301010101" Numeric,"1301010301" Numeric,"1301020101" Numeric,"1301029901" Numeric,"1302010101" Numeric,"1303010101" Numeric,"1303010201" Numeric,"1303019901" Numeric,"1303019902" Numeric,"1303019903" Numeric,"1304010101" Numeric,"1304010201" Numeric,"1402010201" Numeric,"1405010101" Numeric,"1405010102" Numeric,"1405010201" Numeric,"1405010401" Numeric,"1405010402" Numeric,"1405010403" Numeric,"1405010601" Numeric,"1405010602" Numeric,"1405010603" Numeric,"1405010901" Numeric,"1405020201" Numeric,"1405020203" Numeric,"1405020204" Numeric,"1405020205" Numeric,"1405020206" Numeric,"1405020207" Numeric,"1405020301" Numeric,"1405020401" Numeric,"1405020402" Numeric,"1405020404" Numeric,"1405020405" Numeric,"1405020601" Numeric,"1405020602" Numeric,"1406010101" Numeric,"1406010102" Numeric,"1406010601" Numeric,"1406010701" Numeric,"1406019901" Numeric,"1407030101" Numeric,"1407030102" Numeric,"1407030103" Numeric,"1407030201" Numeric,"1603010102" Numeric,"1603010103" Numeric,"1603010104" Numeric,"1603010105" Numeric,"1603020102" Numeric,"1603020105" Numeric,"1604010301" Numeric,"1604019901" Numeric,"1702010101" Numeric,"1801040101" Numeric,"1801049901" Numeric,"1802010301" Numeric,"1802010401" Numeric,"1802010402" Numeric,"1803010101" Numeric,"1803010102" Numeric,"1803010201" Numeric,"1803010301" Numeric,"1804010101" Numeric,"1804010102" Numeric,"1804010103" Numeric,"1804010104" Numeric,"1804010105" Numeric,"1804010106" Numeric,"1804010107" Numeric,"1804010108" Numeric,"1804010109" Numeric,"1804010110" Numeric,"1804010111" Numeric,"1804010112" Numeric,"1804010114" Numeric,"1804010115" Numeric,"1804010116" Numeric,"1804010201" Numeric,"1804019902" Numeric,"1804019903" Numeric,"1804019904" Numeric,"1805010101" Numeric,"1805010102" Numeric,"1805010103" Numeric,"1805010104" Numeric,"1805010105" Numeric,"1805010106" Numeric,"1805010107" Numeric,"1805019901" Numeric,"1805019902" Numeric,"1805019903" Numeric,"1805019904" Numeric,"1805019905" Numeric,"1805019911" Numeric,"1805019912" Numeric,"1806019902" Numeric,"1807010101" Numeric,"1807010102" Numeric,"1807010103" Numeric,"1807010201" Numeric,"1807010202" Numeric,"1807019901" Numeric,"1807019902" Numeric,"1807019903" Numeric,"1807019904" Numeric,"1807019905" Numeric,"1807019906" Numeric,"1807019907" Numeric,"1901010501" Numeric,"1901010502" Numeric,"1902010301" Numeric,"1903020201" Numeric,"1903020401" Numeric,"1903020402" Numeric,"1903020403" Numeric,"1903020404" Numeric,"1903020405" Numeric,"1903020407" Numeric,"1903020408" Numeric,"1904010201" Numeric,"2002010501" Numeric,"2002010502" Numeric,"2002010503" Numeric,"2002019901" Numeric,"2204010307" Numeric,"2204010308" Numeric,"2204010309" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(2, 8) AS p(periodo VarChar, "07010101" Numeric,"07010102" Numeric,"07010201" Numeric,"07010301" Numeric,"07010401" Numeric,"07020101" Numeric,"08010100" Numeric,"08010201" Numeric,"08020101" Numeric,"08020102" Numeric,"08020103" Numeric,"08030100" Numeric,"08999901" Numeric,"08999902" Numeric,"08999903" Numeric,"08999905" Numeric,"08999906" Numeric,"08999999" Numeric,"09010101" Numeric,"09010102" Numeric,"09010103" Numeric,"09020101" Numeric,"09020199" Numeric,"09030100" Numeric,"09030200" Numeric,"09039901" Numeric,"09039902" Numeric,"09039999" Numeric,"09040101" Numeric,"09060100" Numeric,"10010101" Numeric,"10010199" Numeric,"11020100" Numeric,"11020200" Numeric,"11020300" Numeric,"11020401" Numeric,"11020402" Numeric,"11030101" Numeric,"12010100" Numeric,"12020101" Numeric,"12020103" Numeric,"12020104" Numeric,"12030101" Numeric,"12030104" Numeric,"12030105" Numeric,"12030106" Numeric,"12040101" Numeric,"12040102" Numeric,"12040199" Numeric,"12050101" Numeric,"12050102" Numeric,"12050103" Numeric,"12050104" Numeric,"12050105" Numeric,"12050199" Numeric,"13010101" Numeric,"13010103" Numeric,"13010201" Numeric,"13010299" Numeric,"13020101" Numeric,"13030101" Numeric,"13030102" Numeric,"13030199" Numeric,"13040101" Numeric,"13040102" Numeric,"14020102" Numeric,"14050101" Numeric,"14050102" Numeric,"14050104" Numeric,"14050106" Numeric,"14050109" Numeric,"14050202" Numeric,"14050203" Numeric,"14050204" Numeric,"14050206" Numeric,"14060101" Numeric,"14060106" Numeric,"14060107" Numeric,"14060199" Numeric,"14070301" Numeric,"14070302" Numeric,"16030101" Numeric,"16030201" Numeric,"16040103" Numeric,"16040199" Numeric,"17020101" Numeric,"18010401" Numeric,"18010499" Numeric,"18020103" Numeric,"18020104" Numeric,"18030101" Numeric,"18030102" Numeric,"18030103" Numeric,"18040101" Numeric,"18040102" Numeric,"18040199" Numeric,"18050101" Numeric,"18050199" Numeric,"18060199" Numeric,"18070101" Numeric,"18070102" Numeric,"18070199" Numeric,"19010105" Numeric,"19020103" Numeric,"19030202" Numeric,"19030204" Numeric,"19040102" Numeric,"20020105" Numeric,"20020199" Numeric,"22040103" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(2, 6) AS p(periodo VarChar, "070101" Numeric, "070102" Numeric, "070103" Numeric, "070104" Numeric, "070201" Numeric, "080101" Numeric, "080102" Numeric, "080201" Numeric, "080301" Numeric, "089999" Numeric, "090101" Numeric, "090201" Numeric, "090301" Numeric, "090302" Numeric, "090399" Numeric, "090401" Numeric, "090601" Numeric, "100101" Numeric, "110201" Numeric, "110202" Numeric, "110203" Numeric, "110204" Numeric, "110301" Numeric, "120101" Numeric, "120201" Numeric, "120301" Numeric, "120401" Numeric, "120501" Numeric, "130101" Numeric, "130102" Numeric, "130201" Numeric, "130301" Numeric, "130401" Numeric, "140201" Numeric, "140501" Numeric, "140502" Numeric, "140601" Numeric, "140703" Numeric, "160301" Numeric, "160302" Numeric, "160401" Numeric, "170201" Numeric, "180104" Numeric, "180201" Numeric, "180301" Numeric, "180401" Numeric, "180501" Numeric, "180601" Numeric, "180701" Numeric, "190101" Numeric, "190201" Numeric, "190302" Numeric, "190401" Numeric, "200201" Numeric, "220401" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(2, 4) AS p(periodo VarChar, "0701" Numeric,"0702" Numeric,"0801" Numeric,"0802" Numeric,"0803" Numeric,"0899" Numeric,"0901" Numeric,"0902" Numeric,"0903" Numeric,"0904" Numeric,"0906" Numeric,"1001" Numeric,"1102" Numeric,"1103" Numeric,"1201" Numeric,"1202" Numeric,"1203" Numeric,"1204" Numeric,"1205" Numeric,"1301" Numeric,"1302" Numeric,"1303" Numeric,"1304" Numeric,"1402" Numeric,"1405" Numeric,"1406" Numeric,"1407" Numeric,"1603" Numeric,"1604" Numeric,"1702" Numeric,"1801" Numeric,"1802" Numeric,"1803" Numeric,"1804" Numeric,"1805" Numeric,"1806" Numeric,"1807" Numeric,"1901" Numeric,"1902" Numeric,"1903" Numeric,"1904" Numeric,"2002" Numeric,"2204" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(2, 2) AS p(periodo VarChar, "07" Numeric,"08" Numeric,"09" Numeric,"10" Numeric,"11" Numeric,"12" Numeric,"13" Numeric,"14" Numeric,"16" Numeric,"17" Numeric,"18" Numeric,"19" Numeric,"20" Numeric,"22" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(2, 1) AS p(periodo Text, "Indice" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 3) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(3, 10) AS p(periodo VarChar, "0102010402" Numeric, "0102020101" Numeric, "0102020301" Numeric, "0102040101" Numeric, "0102040401" Numeric, "0103010102" Numeric, "0103020401" Numeric, "0104010301" Numeric, "0104020001" Numeric, "0104040801" Numeric, "0303020101" Numeric, "0403010301" Numeric, "0701020301" Numeric, "0701040101" Numeric, "0702010101" Numeric, "0702010102" Numeric, "0702010103" Numeric, "0702019901" Numeric, "0702019902" Numeric, "0702019903" Numeric, "0801020101" Numeric, "0802010202" Numeric, "0802010301" Numeric, "0803010001" Numeric, "0803010002" Numeric, "0899990102" Numeric, "0899990103" Numeric, "0899990301" Numeric, "0899990401" Numeric, "0899990501" Numeric, "0901010101" Numeric, "0903020001" Numeric, "0903990202" Numeric, "0903990203" Numeric, "0903990204" Numeric, "0903999902" Numeric, "0904010101" Numeric, "1001010101" Numeric, "1102010001" Numeric, "1102020001" Numeric, "1201010001" Numeric, "1202010101" Numeric, "1202010301" Numeric, "1202010302" Numeric, "1202010401" Numeric, "1203010101" Numeric, "1203010501" Numeric, "1203010601" Numeric, "1203010602" Numeric, "1204010101" Numeric, "1204019901" Numeric, "1205010101" Numeric, "1205010102" Numeric, "1205010107" Numeric, "1205010109" Numeric, "1205010202" Numeric, "1205010204" Numeric, "1205010301" Numeric, "1205019902" Numeric, "1205019903" Numeric, "1301010101" Numeric, "1301020101" Numeric, "1302010201" Numeric, "1302010401" Numeric, "1302019901" Numeric, "1303019901" Numeric, "1303019902" Numeric, "1304010101" Numeric, "1403010201" Numeric, "1403010301" Numeric, "1403010601" Numeric, "1405010101" Numeric, "1405010401" Numeric, "1405010403" Numeric, "1405010602" Numeric, "1405020201" Numeric, "1405020202" Numeric, "1405020203" Numeric, "1405020204" Numeric, "1405020205" Numeric, "1405020206" Numeric, "1405020301" Numeric, "1405020401" Numeric, "1405020402" Numeric, "1405020403" Numeric, "1405020405" Numeric, "1405020601" Numeric, "1405020602" Numeric, "1406010101" Numeric, "1406010201" Numeric, "1406010401" Numeric, "1406010702" Numeric, "1407029901" Numeric, "1407030101" Numeric, "1407030102" Numeric, "1407030103" Numeric, "1407030201" Numeric, "1407039901" Numeric, "1501010101" Numeric, "1502010102" Numeric, "1603010101" Numeric, "1603020101" Numeric, "1603020102" Numeric, "1603020103" Numeric, "1603020104" Numeric, "1603020105" Numeric, "1702010101" Numeric, "1702010201" Numeric, "1702010401" Numeric, "1702010402" Numeric, "1802010201" Numeric, "1802010202" Numeric, "1802010302" Numeric, "1802010401" Numeric, "1802010402" Numeric, "1802019901" Numeric, "1803010101" Numeric, "1803010102" Numeric, "1803010201" Numeric, "1803010301" Numeric, "1803010401" Numeric, "1804010101" Numeric, "1804010102" Numeric, "1804010103" Numeric, "1804010105" Numeric, "1804010106" Numeric, "1804010113" Numeric, "1804010117" Numeric, "1804010202" Numeric, "1804010203" Numeric, "1804010204" Numeric, "1804019901" Numeric, "1804019902" Numeric, "1804019903" Numeric, "1805010102" Numeric, "1805010103" Numeric, "1805010105" Numeric, "1805010106" Numeric, "1805010107" Numeric, "1805010108" Numeric, "1805010109" Numeric, "1805010301" Numeric, "1805019901" Numeric, "1805019902" Numeric, "1805019903" Numeric, "1805019904" Numeric, "1805019905" Numeric, "1805019906" Numeric, "1805019907" Numeric, "1805019908" Numeric, "1805019909" Numeric, "1805019910" Numeric, "1806010401" Numeric, "1806010601" Numeric, "1806019901" Numeric, "1807010101" Numeric, "1807010104" Numeric, "1807010105" Numeric, "1807010106" Numeric, "1807010107" Numeric, "1807010203" Numeric, "1808019908" Numeric, "1808019909" Numeric, "1808019910" Numeric, "1901010101" Numeric, "1901010501" Numeric, "1901010502" Numeric, "1901010503" Numeric, "1902010201" Numeric, "1902010301" Numeric, "1903020405" Numeric, "1903020406" Numeric, "1903020408" Numeric, "1904010101" Numeric, "1904010102" Numeric, "1904010201" Numeric, "2105010401" Numeric, "2105010402" Numeric, "2105010501" Numeric, "2106010401" Numeric, "2106010501" Numeric, "2204010201" Numeric, "2204010202" Numeric, "2204010203" Numeric, "2204010204" Numeric, "2204010301" Numeric, "2204010302" Numeric, "2204010303" Numeric, "2204010304" Numeric, "2204010305" Numeric, "2204010306" Numeric, "2204010307" Numeric, "2204010310" Numeric, "2204010601" Numeric, "2204010602" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(3, 8) AS p(periodo VarChar, "01020104" Numeric, "01020201" Numeric, "01020203" Numeric, "01020401" Numeric, "01020404" Numeric, "01030101" Numeric, "01030204" Numeric, "01040103" Numeric, "01040200" Numeric, "01040408" Numeric, "03030201" Numeric, "04030103" Numeric, "07010203" Numeric, "07010401" Numeric, "07020101" Numeric, "07020199" Numeric, "08010201" Numeric, "08020102" Numeric, "08020103" Numeric, "08030100" Numeric, "08999901" Numeric, "08999903" Numeric, "08999904" Numeric, "08999905" Numeric, "09010101" Numeric, "09030200" Numeric, "09039902" Numeric, "09039999" Numeric, "09040101" Numeric, "10010101" Numeric, "11020100" Numeric, "11020200" Numeric, "12010100" Numeric, "12020101" Numeric, "12020103" Numeric, "12020104" Numeric, "12030101" Numeric, "12030105" Numeric, "12030106" Numeric, "12040101" Numeric, "12040199" Numeric, "12050101" Numeric, "12050102" Numeric, "12050103" Numeric, "12050199" Numeric, "13010101" Numeric, "13010201" Numeric, "13020102" Numeric, "13020104" Numeric, "13020199" Numeric, "13030199" Numeric, "13040101" Numeric, "14030102" Numeric, "14030103" Numeric, "14030106" Numeric, "14050101" Numeric, "14050104" Numeric, "14050106" Numeric, "14050202" Numeric, "14050203" Numeric, "14050204" Numeric, "14050206" Numeric, "14060101" Numeric, "14060102" Numeric, "14060104" Numeric, "14060107" Numeric, "14070299" Numeric, "14070301" Numeric, "14070302" Numeric, "14070399" Numeric, "15010101" Numeric, "15020101" Numeric, "16030101" Numeric, "16030201" Numeric, "17020101" Numeric, "17020102" Numeric, "17020104" Numeric, "18020102" Numeric, "18020103" Numeric, "18020104" Numeric, "18020199" Numeric, "18030101" Numeric, "18030102" Numeric, "18030103" Numeric, "18030104" Numeric, "18040101" Numeric, "18040102" Numeric, "18040199" Numeric, "18050101" Numeric, "18050103" Numeric, "18050199" Numeric, "18060104" Numeric, "18060106" Numeric, "18060199" Numeric, "18070101" Numeric, "18070102" Numeric, "18080199" Numeric, "19010101" Numeric, "19010105" Numeric, "19020102" Numeric, "19020103" Numeric, "19030204" Numeric, "19040101" Numeric, "19040102" Numeric, "21050104" Numeric, "21050105" Numeric, "21060104" Numeric, "21060105" Numeric, "22040102" Numeric, "22040103" Numeric, "22040106" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(3, 6) AS p(periodo VarChar, "010201" Numeric, "010202" Numeric, "010204" Numeric, "010301" Numeric, "010302" Numeric, "010401" Numeric, "010402" Numeric, "010404" Numeric, "030302" Numeric, "040301" Numeric, "070102" Numeric, "070104" Numeric, "070201" Numeric, "080102" Numeric, "080201" Numeric, "080301" Numeric, "089999" Numeric, "090101" Numeric, "090302" Numeric, "090399" Numeric, "090401" Numeric, "100101" Numeric, "110201" Numeric, "110202" Numeric, "120101" Numeric, "120201" Numeric, "120301" Numeric, "120401" Numeric, "120501" Numeric, "130101" Numeric, "130102" Numeric, "130201" Numeric, "130301" Numeric, "130401" Numeric, "140301" Numeric, "140501" Numeric, "140502" Numeric, "140601" Numeric, "140702" Numeric, "140703" Numeric, "150101" Numeric, "150201" Numeric, "160301" Numeric, "160302" Numeric, "170201" Numeric, "180201" Numeric, "180301" Numeric, "180401" Numeric, "180501" Numeric, "180601" Numeric, "180701" Numeric, "180801" Numeric, "190101" Numeric, "190201" Numeric, "190302" Numeric, "190401" Numeric, "210501" Numeric, "210601" Numeric, "220401" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(3, 4) AS p(periodo VarChar, "0102" Numeric, "0103" Numeric, "0104" Numeric, "0303" Numeric, "0403" Numeric, "0701" Numeric, "0702" Numeric, "0801" Numeric, "0802" Numeric, "0803" Numeric, "0899" Numeric, "0901" Numeric, "0903" Numeric, "0904" Numeric, "1001" Numeric, "1102" Numeric, "1201" Numeric, "1202" Numeric, "1203" Numeric, "1204" Numeric, "1205" Numeric, "1301" Numeric, "1302" Numeric, "1303" Numeric, "1304" Numeric, "1403" Numeric, "1405" Numeric, "1406" Numeric, "1407" Numeric, "1501" Numeric, "1502" Numeric, "1603" Numeric, "1702" Numeric, "1802" Numeric, "1803" Numeric, "1804" Numeric, "1805" Numeric, "1806" Numeric, "1807" Numeric, "1808" Numeric, "1901" Numeric, "1902" Numeric, "1903" Numeric, "1904" Numeric, "2105" Numeric, "2106" Numeric, "2204" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(3, 2) AS p(periodo VarChar, "01" Numeric, "03" Numeric, "04" Numeric, "07" Numeric, "08" Numeric, "09" Numeric, "10" Numeric, "11" Numeric, "12" Numeric, "13" Numeric, "14" Numeric, "15" Numeric, "16" Numeric, "17" Numeric, "18" Numeric, "19" Numeric, "21" Numeric, "22" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT p.*, g.indice general
                        FROM if_indice(3, 1) AS p(periodo Text, "Indice" Numeric), if_indicev(4, 1) g
                        WHERE p.periodo = g.periodo
                        AND p.periodo >= \''.$perini.'\' AND p.periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 4) {
            $consulta = 'SELECT a.periodo, a."Indice" agricola, m."Indice" manufacturado, i."Indice" importado, a."Indice" * 0.0662860495139841 + m."Indice" * 0.652194863130863 + i."Indice" * 0.281519087355153 general
                FROM if_indice(1, 1) AS a(periodo Text, "Indice" Numeric),
                    if_indice(2, 1) AS m(periodo Text, "Indice" Numeric),
                    if_indice(3, 1) AS i(periodo Text, "Indice" Numeric)
                WHERE a.periodo = m.periodo AND m.periodo = i.periodo
                AND a.periodo >= \''.$perini.'\' AND a.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 5) {
            $consulta = 'SELECT a.periodo, a."Indice" * 0.0922586088890994 + m."Indice" * 0.907741391110901 nacional, i."Indice" importado, a."Indice" * 0.0662860495139841 + m."Indice" * 0.652194863130863 + i."Indice" * 0.281519087355153 general
                FROM if_indice(1, 1) AS a(periodo Text, "Indice" Numeric),
                    if_indice(2, 1) AS m(periodo Text, "Indice" Numeric),
                    if_indice(3, 1) AS i(periodo Text, "Indice" Numeric)
                WHERE a.periodo = m.periodo AND m.periodo = i.periodo
                AND a.periodo >= \''.$perini.'\' AND a.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 6) {
            $consulta = 'SELECT ig.periodo, ian.indice agricola_nacional, iai.indice agricola_importado, iag.indice agricola, imn.indice manufacturado_nacional, imi.indice manufacturado_importado, img.indice manufacturado, ig.indice general
                FROM if_indice2(1, 1) AS ian(periodo Text, indice Numeric),
                if_indice2(5, 1) AS iai(periodo Text, indice Numeric),
                if_indice2(6, 1) AS iag(periodo Text, indice Numeric),
                if_indice2(2, 1) AS imn(periodo Text, indice Numeric),
                if_indice2(7, 1) AS imi(periodo Text, indice Numeric),
                if_indice2(8, 1) AS img(periodo Text, indice Numeric),
                if_indice2(4, 1) AS ig(periodo Text, indice Numeric)
                WHERE ig.periodo = ian.periodo
                AND ig.periodo = iai.periodo
                AND ig.periodo = iag.periodo
                AND ig.periodo = imn.periodo
                AND ig.periodo = imi.periodo
                AND ig.periodo = img.periodo
                AND ig.periodo >= \''.$perini.'\' AND ig.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 7) {
            switch ($clasificacion) {
                case 5:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(7, 5)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 5)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(cpc, 1, 5)') AS a(periodo Text, \"01111\" Numeric, \"01121\" Numeric, \"01122\" Numeric, \"01190\" Numeric, \"01213\" Numeric, \"01214\" Numeric, \"01219\" Numeric, \"01231\" Numeric, \"01232\" Numeric, \"01234\" Numeric, \"01235\" Numeric, \"01241\" Numeric, \"01242\" Numeric, \"01249\" Numeric, \"01251\" Numeric, \"01252\" Numeric, \"01253\" Numeric, \"01311\" Numeric, \"01312\" Numeric, \"01313\" Numeric, \"01316\" Numeric, \"01317\" Numeric, \"01318\" Numeric, \"01319\" Numeric, \"01321\" Numeric, \"01322\" Numeric, \"01323\" Numeric, \"01324\" Numeric, \"01330\" Numeric, \"01359\" Numeric, \"01411\" Numeric, \"01444\" Numeric, \"01445\" Numeric, \"01449\" Numeric, \"01510\" Numeric, \"01591\" Numeric, \"01592\" Numeric, \"01599\" Numeric, \"01690\" Numeric, \"01961\" Numeric, \"02310\" Numeric, \"04120\" Numeric, \"21111\" Numeric, \"21117\" Numeric, \"21121\" Numeric, \"21133\" Numeric, \"21151\" Numeric, \"21174\" Numeric, \"21429\" Numeric, \"21439\" Numeric, \"21494\" Numeric, \"21499\" Numeric, \"21541\" Numeric, \"21543\" Numeric, \"21549\" Numeric, \"21550\" Numeric, \"21590\" Numeric, \"21710\" Numeric, \"22110\" Numeric, \"22120\" Numeric, \"22211\" Numeric, \"22221\" Numeric, \"22230\" Numeric, \"22241\" Numeric, \"22251\" Numeric, \"22259\" Numeric, \"22270\" Numeric, \"22290\" Numeric, \"23110\" Numeric, \"23120\" Numeric, \"23130\" Numeric, \"23140\" Numeric, \"23162\" Numeric, \"23311\" Numeric, \"23319\" Numeric, \"23410\" Numeric, \"23430\" Numeric, \"23490\" Numeric, \"23511\" Numeric, \"23520\" Numeric, \"23630\" Numeric, \"23660\" Numeric, \"23670\" Numeric, \"23710\" Numeric, \"23911\" Numeric, \"23913\" Numeric, \"23914\" Numeric, \"23991\" Numeric, \"23992\" Numeric, \"23994\" Numeric, \"23996\" Numeric, \"23999\" Numeric, \"24139\" Numeric, \"24211\" Numeric, \"24310\" Numeric, \"24410\" Numeric, \"24490\" Numeric, \"25020\" Numeric, \"26320\" Numeric, \"27110\" Numeric, \"27160\" Numeric, \"27180\" Numeric, \"27320\" Numeric, \"28210\" Numeric, \"28221\" Numeric, \"28223\" Numeric, \"28226\" Numeric, \"28229\" Numeric, \"28232\" Numeric, \"28233\" Numeric, \"28234\" Numeric, \"28236\" Numeric, \"29310\" Numeric, \"29420\" Numeric, \"32193\" Numeric, \"32220\" Numeric, \"32300\" Numeric, \"326\" Numeric, \"33380\" Numeric, \"34131\" Numeric, \"34661\" Numeric, \"34662\" Numeric, \"35110\" Numeric, \"35250\" Numeric, \"35260\" Numeric, \"35270\" Numeric, \"35321\" Numeric, \"35322\" Numeric, \"35323\" Numeric, \"35332\" Numeric, \"36270\" Numeric, \"36940\" Numeric, \"36990\" Numeric, \"37191\" Numeric, \"37221\" Numeric, \"37350\" Numeric, \"37440\" Numeric, \"37540\" Numeric, \"38911\" Numeric, \"41263\" Numeric, \"42999\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 4:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(7, 4)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 4)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(cpc, 1, 4)') AS a(periodo Text, \"0111\" Numeric, \"0112\" Numeric, \"0119\" Numeric, \"0121\" Numeric, \"0123\" Numeric, \"0124\" Numeric, \"0125\" Numeric, \"0131\" Numeric, \"0132\" Numeric, \"0133\" Numeric, \"0135\" Numeric, \"0141\" Numeric, \"0144\" Numeric, \"0151\" Numeric, \"0159\" Numeric, \"0169\" Numeric, \"0196\" Numeric, \"0231\" Numeric, \"0412\" Numeric, \"2111\" Numeric, \"2112\" Numeric, \"2113\" Numeric, \"2115\" Numeric, \"2117\" Numeric, \"2142\" Numeric, \"2143\" Numeric, \"2149\" Numeric, \"2154\" Numeric, \"2155\" Numeric, \"2159\" Numeric, \"2171\" Numeric, \"2211\" Numeric, \"2212\" Numeric, \"2221\" Numeric, \"2222\" Numeric, \"2223\" Numeric, \"2224\" Numeric, \"2225\" Numeric, \"2227\" Numeric, \"2229\" Numeric, \"2311\" Numeric, \"2312\" Numeric, \"2313\" Numeric, \"2314\" Numeric, \"2316\" Numeric, \"2331\" Numeric, \"2341\" Numeric, \"2343\" Numeric, \"2349\" Numeric, \"2351\" Numeric, \"2352\" Numeric, \"2363\" Numeric, \"2366\" Numeric, \"2367\" Numeric, \"2371\" Numeric, \"2391\" Numeric, \"2399\" Numeric, \"2413\" Numeric, \"2421\" Numeric, \"2431\" Numeric, \"2441\" Numeric, \"2449\" Numeric, \"2502\" Numeric, \"2632\" Numeric, \"2711\" Numeric, \"2716\" Numeric, \"2718\" Numeric, \"2732\" Numeric, \"2821\" Numeric, \"2822\" Numeric, \"2823\" Numeric, \"2931\" Numeric, \"2942\" Numeric, \"3219\" Numeric, \"3222\" Numeric, \"3230\" Numeric, \"326\" Numeric, \"3338\" Numeric, \"3413\" Numeric, \"3466\" Numeric, \"3511\" Numeric, \"3525\" Numeric, \"3526\" Numeric, \"3527\" Numeric, \"3532\" Numeric, \"3533\" Numeric, \"3627\" Numeric, \"3694\" Numeric, \"3699\" Numeric, \"3719\" Numeric, \"3722\" Numeric, \"3735\" Numeric, \"3744\" Numeric, \"3754\" Numeric, \"3891\" Numeric, \"4126\" Numeric, \"4299\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 3:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(7, 3)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 3)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(cpc, 1, 3)') AS a(periodo Text, \"011\" Numeric, \"012\" Numeric, \"013\" Numeric, \"014\" Numeric, \"015\" Numeric, \"016\" Numeric, \"019\" Numeric, \"023\" Numeric, \"041\" Numeric, \"211\" Numeric, \"214\" Numeric, \"215\" Numeric, \"217\" Numeric, \"221\" Numeric, \"222\" Numeric, \"231\" Numeric, \"233\" Numeric, \"234\" Numeric, \"235\" Numeric, \"236\" Numeric, \"237\" Numeric, \"239\" Numeric, \"241\" Numeric, \"242\" Numeric, \"243\" Numeric, \"244\" Numeric, \"250\" Numeric, \"263\" Numeric, \"271\" Numeric, \"273\" Numeric, \"282\" Numeric, \"293\" Numeric, \"294\" Numeric, \"321\" Numeric, \"322\" Numeric, \"323\" Numeric, \"326\" Numeric, \"333\" Numeric, \"341\" Numeric, \"346\" Numeric, \"351\" Numeric, \"352\" Numeric, \"353\" Numeric, \"362\" Numeric, \"369\" Numeric, \"371\" Numeric, \"372\" Numeric, \"373\" Numeric, \"374\" Numeric, \"375\" Numeric, \"389\" Numeric, \"412\" Numeric, \"429\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 2:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(7, 2)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 2)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(cpc, 1, 2)') AS a(periodo Text, \"01\" Numeric, \"02\" Numeric, \"04\" Numeric, \"21\" Numeric, \"22\" Numeric, \"23\" Numeric, \"24\" Numeric, \"25\" Numeric, \"26\" Numeric, \"27\" Numeric, \"28\" Numeric, \"29\" Numeric, \"32\" Numeric, \"33\" Numeric, \"34\" Numeric, \"35\" Numeric, \"36\" Numeric, \"37\" Numeric, \"38\" Numeric, \"41\" Numeric, \"42\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 1:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(7, 1)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 1)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(cpc, 1, 1)') AS a(periodo Text, \"0\" Numeric, \"2\" Numeric, \"3\" Numeric, \"4\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
            }
        }
        
        if ($sector == 8) {
            switch ($clasificacion) {
                case 5:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(8, 5)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 5)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(cpc, 1, 5)') AS a(periodo Text, \"01231\" Numeric, \"01234\" Numeric, \"01249\" Numeric, \"01252\" Numeric, \"01253\" Numeric, \"01322\" Numeric, \"01330\" Numeric, \"01351\" Numeric, \"01510\" Numeric, \"01591\" Numeric, \"02310\" Numeric, \"04120\" Numeric, \"21121\" Numeric, \"21174\" Numeric, \"21179\" Numeric, \"21223\" Numeric, \"21439\" Numeric, \"21494\" Numeric, \"21499\" Numeric, \"21521\" Numeric, \"21541\" Numeric, \"21543\" Numeric, \"22120\" Numeric, \"22211\" Numeric, \"22219\" Numeric, \"22221\" Numeric, \"22222\" Numeric, \"22230\" Numeric, \"22241\" Numeric, \"22251\" Numeric, \"22290\" Numeric, \"23110\" Numeric, \"23140\" Numeric, \"23162\" Numeric, \"23311\" Numeric, \"23410\" Numeric, \"23430\" Numeric, \"23520\" Numeric, \"23630\" Numeric, \"23660\" Numeric, \"23670\" Numeric, \"23710\" Numeric, \"23911\" Numeric, \"23914\" Numeric, \"23992\" Numeric, \"23996\" Numeric, \"23999\" Numeric, \"24131\" Numeric, \"24139\" Numeric, \"24211\" Numeric, \"24310\" Numeric, \"25020\" Numeric, \"26520\" Numeric, \"26610\" Numeric, \"26710\" Numeric, \"27110\" Numeric, \"27120\" Numeric, \"27140\" Numeric, \"27180\" Numeric, \"28210\" Numeric, \"28223\" Numeric, \"28226\" Numeric, \"28231\" Numeric, \"28232\" Numeric, \"28233\" Numeric, \"28234\" Numeric, \"28236\" Numeric, \"28237\" Numeric, \"29290\" Numeric, \"29310\" Numeric, \"29320\" Numeric, \"29420\" Numeric, \"31210\" Numeric, \"31430\" Numeric, \"32193\" Numeric, \"32220\" Numeric, \"33330\" Numeric, \"33380\" Numeric, \"34550\" Numeric, \"34661\" Numeric, \"34662\" Numeric, \"34669\" Numeric, \"35110\" Numeric, \"35250\" Numeric, \"35260\" Numeric, \"35270\" Numeric, \"35321\" Numeric, \"35322\" Numeric, \"35323\" Numeric, \"35331\" Numeric, \"35332\" Numeric, \"35333\" Numeric, \"36111\" Numeric, \"36270\" Numeric, \"36940\" Numeric, \"36971\" Numeric, \"37116\" Numeric, \"37191\" Numeric, \"37210\" Numeric, \"37221\" Numeric, \"37350\" Numeric, \"37440\" Numeric, \"38911\" Numeric, \"38993\" Numeric, \"38999\" Numeric, \"46410\" Numeric, \"46539\" Numeric, \"47530\" Numeric, \"47620\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 4:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(8, 4)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 4)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(cpc, 1, 4)') AS a(periodo Text, \"0123\" Numeric, \"0124\" Numeric, \"0125\" Numeric, \"0132\" Numeric, \"0133\" Numeric, \"0135\" Numeric, \"0151\" Numeric, \"0159\" Numeric, \"0231\" Numeric, \"0412\" Numeric, \"2112\" Numeric, \"2117\" Numeric, \"2122\" Numeric, \"2143\" Numeric, \"2149\" Numeric, \"2152\" Numeric, \"2154\" Numeric, \"2212\" Numeric, \"2221\" Numeric, \"2222\" Numeric, \"2223\" Numeric, \"2224\" Numeric, \"2225\" Numeric, \"2229\" Numeric, \"2311\" Numeric, \"2314\" Numeric, \"2316\" Numeric, \"2331\" Numeric, \"2341\" Numeric, \"2343\" Numeric, \"2352\" Numeric, \"2363\" Numeric, \"2366\" Numeric, \"2367\" Numeric, \"2371\" Numeric, \"2391\" Numeric, \"2399\" Numeric, \"2413\" Numeric, \"2421\" Numeric, \"2431\" Numeric, \"2502\" Numeric, \"2652\" Numeric, \"2661\" Numeric, \"2671\" Numeric, \"2711\" Numeric, \"2712\" Numeric, \"2714\" Numeric, \"2718\" Numeric, \"2821\" Numeric, \"2822\" Numeric, \"2823\" Numeric, \"2929\" Numeric, \"2931\" Numeric, \"2932\" Numeric, \"2942\" Numeric, \"3121\" Numeric, \"3143\" Numeric, \"3219\" Numeric, \"3222\" Numeric, \"3333\" Numeric, \"3338\" Numeric, \"3455\" Numeric, \"3466\" Numeric, \"3511\" Numeric, \"3525\" Numeric, \"3526\" Numeric, \"3527\" Numeric, \"3532\" Numeric, \"3533\" Numeric, \"3611\" Numeric, \"3627\" Numeric, \"3694\" Numeric, \"3697\" Numeric, \"3711\" Numeric, \"3719\" Numeric, \"3721\" Numeric, \"3722\" Numeric, \"3735\" Numeric, \"3744\" Numeric, \"3891\" Numeric, \"3899\" Numeric, \"4641\" Numeric, \"4653\" Numeric, \"4753\" Numeric, \"4762\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 3:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(8, 3)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 3)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(cpc, 1, 3)') AS a(periodo Text, \"012\" Numeric, \"013\" Numeric, \"015\" Numeric, \"023\" Numeric, \"041\" Numeric, \"211\" Numeric, \"212\" Numeric, \"214\" Numeric, \"215\" Numeric, \"221\" Numeric, \"222\" Numeric, \"231\" Numeric, \"233\" Numeric, \"234\" Numeric, \"235\" Numeric, \"236\" Numeric, \"237\" Numeric, \"239\" Numeric, \"241\" Numeric, \"242\" Numeric, \"243\" Numeric, \"250\" Numeric, \"265\" Numeric, \"266\" Numeric, \"267\" Numeric, \"271\" Numeric, \"282\" Numeric, \"292\" Numeric, \"293\" Numeric, \"294\" Numeric, \"312\" Numeric, \"314\" Numeric, \"321\" Numeric, \"322\" Numeric, \"333\" Numeric, \"345\" Numeric, \"346\" Numeric, \"351\" Numeric, \"352\" Numeric, \"353\" Numeric, \"361\" Numeric, \"362\" Numeric, \"369\" Numeric, \"371\" Numeric, \"372\" Numeric, \"373\" Numeric, \"374\" Numeric, \"389\" Numeric, \"464\" Numeric, \"465\" Numeric, \"475\" Numeric, \"476\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 2:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(8, 2)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 2)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(cpc, 1, 2)') AS a(periodo Text, \"01\" Numeric, \"02\" Numeric, \"04\" Numeric, \"21\" Numeric, \"22\" Numeric, \"23\" Numeric, \"24\" Numeric, \"25\" Numeric, \"26\" Numeric, \"27\" Numeric, \"28\" Numeric, \"29\" Numeric, \"31\" Numeric, \"32\" Numeric, \"33\" Numeric, \"34\" Numeric, \"35\" Numeric, \"36\" Numeric, \"37\" Numeric, \"38\" Numeric, \"46\" Numeric, \"47\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 1:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(8, 1)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 1)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(cpc, 1, 1)') AS a(periodo Text, \"0\" Numeric, \"2\" Numeric, \"3\" Numeric, \"4\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
            }
        }
        
        if ($sector == 9) {
            switch ($clasificacion) {
                case 5:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(9, 5)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 5)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(cpc, 1, 5)') AS a(periodo Text, \"01111\" Numeric, \"01121\" Numeric, \"01122\" Numeric, \"01190\" Numeric, \"01213\" Numeric, \"01214\" Numeric, \"01219\" Numeric, \"01231\" Numeric, \"01232\" Numeric, \"01234\" Numeric, \"01235\" Numeric, \"01241\" Numeric, \"01242\" Numeric, \"01249\" Numeric, \"01251\" Numeric, \"01252\" Numeric, \"01253\" Numeric, \"01311\" Numeric, \"01312\" Numeric, \"01313\" Numeric, \"01316\" Numeric, \"01317\" Numeric, \"01318\" Numeric, \"01319\" Numeric, \"01321\" Numeric, \"01322\" Numeric, \"01323\" Numeric, \"01324\" Numeric, \"01330\" Numeric, \"01351\" Numeric, \"01359\" Numeric, \"01411\" Numeric, \"01444\" Numeric, \"01445\" Numeric, \"01449\" Numeric, \"01510\" Numeric, \"01591\" Numeric, \"01592\" Numeric, \"01599\" Numeric, \"01690\" Numeric, \"01961\" Numeric, \"02310\" Numeric, \"04120\" Numeric, \"21111\" Numeric, \"21117\" Numeric, \"21121\" Numeric, \"21133\" Numeric, \"21151\" Numeric, \"21174\" Numeric, \"21179\" Numeric, \"21223\" Numeric, \"21429\" Numeric, \"21439\" Numeric, \"21494\" Numeric, \"21499\" Numeric, \"21521\" Numeric, \"21541\" Numeric, \"21543\" Numeric, \"21549\" Numeric, \"21550\" Numeric, \"21590\" Numeric, \"21710\" Numeric, \"22110\" Numeric, \"22120\" Numeric, \"22211\" Numeric, \"22219\" Numeric, \"22221\" Numeric, \"22222\" Numeric, \"22230\" Numeric, \"22241\" Numeric, \"22251\" Numeric, \"22259\" Numeric, \"22270\" Numeric, \"22290\" Numeric, \"23110\" Numeric, \"23120\" Numeric, \"23130\" Numeric, \"23140\" Numeric, \"23162\" Numeric, \"23311\" Numeric, \"23319\" Numeric, \"23410\" Numeric, \"23430\" Numeric, \"23490\" Numeric, \"23511\" Numeric, \"23520\" Numeric, \"23630\" Numeric, \"23660\" Numeric, \"23670\" Numeric, \"23710\" Numeric, \"23911\" Numeric, \"23913\" Numeric, \"23914\" Numeric, \"23991\" Numeric, \"23992\" Numeric, \"23994\" Numeric, \"23996\" Numeric, \"23999\" Numeric, \"24131\" Numeric, \"24139\" Numeric, \"24211\" Numeric, \"24310\" Numeric, \"24410\" Numeric, \"24490\" Numeric, \"25020\" Numeric, \"26320\" Numeric, \"26520\" Numeric, \"26610\" Numeric, \"26710\" Numeric, \"27110\" Numeric, \"27120\" Numeric, \"27140\" Numeric, \"27160\" Numeric, \"27180\" Numeric, \"27320\" Numeric, \"28210\" Numeric, \"28221\" Numeric, \"28223\" Numeric, \"28226\" Numeric, \"28229\" Numeric, \"28231\" Numeric, \"28232\" Numeric, \"28233\" Numeric, \"28234\" Numeric, \"28236\" Numeric, \"28237\" Numeric, \"29290\" Numeric, \"29310\" Numeric, \"29320\" Numeric, \"29420\" Numeric, \"31210\" Numeric, \"31430\" Numeric, \"32193\" Numeric, \"32220\" Numeric, \"32300\" Numeric, \"326\" Numeric, \"33330\" Numeric, \"33380\" Numeric, \"34131\" Numeric, \"34550\" Numeric, \"34661\" Numeric, \"34662\" Numeric, \"34669\" Numeric, \"35110\" Numeric, \"35250\" Numeric, \"35260\" Numeric, \"35270\" Numeric, \"35321\" Numeric, \"35322\" Numeric, \"35323\" Numeric, \"35331\" Numeric, \"35332\" Numeric, \"35333\" Numeric, \"36111\" Numeric, \"36270\" Numeric, \"36940\" Numeric, \"36971\" Numeric, \"36990\" Numeric, \"37116\" Numeric, \"37191\" Numeric, \"37210\" Numeric, \"37221\" Numeric, \"37350\" Numeric, \"37440\" Numeric, \"37540\" Numeric, \"38911\" Numeric, \"38993\" Numeric, \"38999\" Numeric, \"41263\" Numeric, \"42999\" Numeric, \"46410\" Numeric, \"46539\" Numeric, \"47530\" Numeric, \"47620\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 4:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(9, 4)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 4)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(cpc, 1, 4)') AS a(periodo Text, \"0111\" Numeric, \"0112\" Numeric, \"0119\" Numeric, \"0121\" Numeric, \"0123\" Numeric, \"0124\" Numeric, \"0125\" Numeric, \"0131\" Numeric, \"0132\" Numeric, \"0133\" Numeric, \"0135\" Numeric, \"0141\" Numeric, \"0144\" Numeric, \"0151\" Numeric, \"0159\" Numeric, \"0169\" Numeric, \"0196\" Numeric, \"0231\" Numeric, \"0412\" Numeric, \"2111\" Numeric, \"2112\" Numeric, \"2113\" Numeric, \"2115\" Numeric, \"2117\" Numeric, \"2122\" Numeric, \"2142\" Numeric, \"2143\" Numeric, \"2149\" Numeric, \"2152\" Numeric, \"2154\" Numeric, \"2155\" Numeric, \"2159\" Numeric, \"2171\" Numeric, \"2211\" Numeric, \"2212\" Numeric, \"2221\" Numeric, \"2222\" Numeric, \"2223\" Numeric, \"2224\" Numeric, \"2225\" Numeric, \"2227\" Numeric, \"2229\" Numeric, \"2311\" Numeric, \"2312\" Numeric, \"2313\" Numeric, \"2314\" Numeric, \"2316\" Numeric, \"2331\" Numeric, \"2341\" Numeric, \"2343\" Numeric, \"2349\" Numeric, \"2351\" Numeric, \"2352\" Numeric, \"2363\" Numeric, \"2366\" Numeric, \"2367\" Numeric, \"2371\" Numeric, \"2391\" Numeric, \"2399\" Numeric, \"2413\" Numeric, \"2421\" Numeric, \"2431\" Numeric, \"2441\" Numeric, \"2449\" Numeric, \"2502\" Numeric, \"2632\" Numeric, \"2652\" Numeric, \"2661\" Numeric, \"2671\" Numeric, \"2711\" Numeric, \"2712\" Numeric, \"2714\" Numeric, \"2716\" Numeric, \"2718\" Numeric, \"2732\" Numeric, \"2821\" Numeric, \"2822\" Numeric, \"2823\" Numeric, \"2929\" Numeric, \"2931\" Numeric, \"2932\" Numeric, \"2942\" Numeric, \"3121\" Numeric, \"3143\" Numeric, \"3219\" Numeric, \"3222\" Numeric, \"3230\" Numeric, \"326\" Numeric, \"3333\" Numeric, \"3338\" Numeric, \"3413\" Numeric, \"3455\" Numeric, \"3466\" Numeric, \"3511\" Numeric, \"3525\" Numeric, \"3526\" Numeric, \"3527\" Numeric, \"3532\" Numeric, \"3533\" Numeric, \"3611\" Numeric, \"3627\" Numeric, \"3694\" Numeric, \"3697\" Numeric, \"3699\" Numeric, \"3711\" Numeric, \"3719\" Numeric, \"3721\" Numeric, \"3722\" Numeric, \"3735\" Numeric, \"3744\" Numeric, \"3754\" Numeric, \"3891\" Numeric, \"3899\" Numeric, \"4126\" Numeric, \"4299\" Numeric, \"4641\" Numeric, \"4653\" Numeric, \"4753\" Numeric, \"4762\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 3:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(9, 3)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 3)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(cpc, 1, 3)') AS a(periodo Text, \"011\" Numeric, \"012\" Numeric, \"013\" Numeric, \"014\" Numeric, \"015\" Numeric, \"016\" Numeric, \"019\" Numeric, \"023\" Numeric, \"041\" Numeric, \"211\" Numeric, \"212\" Numeric, \"214\" Numeric, \"215\" Numeric, \"217\" Numeric, \"221\" Numeric, \"222\" Numeric, \"231\" Numeric, \"233\" Numeric, \"234\" Numeric, \"235\" Numeric, \"236\" Numeric, \"237\" Numeric, \"239\" Numeric, \"241\" Numeric, \"242\" Numeric, \"243\" Numeric, \"244\" Numeric, \"250\" Numeric, \"263\" Numeric, \"265\" Numeric, \"266\" Numeric, \"267\" Numeric, \"271\" Numeric, \"273\" Numeric, \"282\" Numeric, \"292\" Numeric, \"293\" Numeric, \"294\" Numeric, \"312\" Numeric, \"314\" Numeric, \"321\" Numeric, \"322\" Numeric, \"323\" Numeric, \"326\" Numeric, \"333\" Numeric, \"341\" Numeric, \"345\" Numeric, \"346\" Numeric, \"351\" Numeric, \"352\" Numeric, \"353\" Numeric, \"361\" Numeric, \"362\" Numeric, \"369\" Numeric, \"371\" Numeric, \"372\" Numeric, \"373\" Numeric, \"374\" Numeric, \"375\" Numeric, \"389\" Numeric, \"412\" Numeric, \"429\" Numeric, \"464\" Numeric, \"465\" Numeric, \"475\" Numeric, \"476\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 2:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(9, 2)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 2)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(cpc, 1, 2)') AS a(periodo Text, \"01\" Numeric, \"02\" Numeric, \"04\" Numeric, \"21\" Numeric, \"22\" Numeric, \"23\" Numeric, \"24\" Numeric, \"25\" Numeric, \"26\" Numeric, \"27\" Numeric, \"28\" Numeric, \"29\" Numeric, \"31\" Numeric, \"32\" Numeric, \"33\" Numeric, \"34\" Numeric, \"35\" Numeric, \"36\" Numeric, \"37\" Numeric, \"38\" Numeric, \"41\" Numeric, \"42\" Numeric, \"46\" Numeric, \"47\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 1:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_cpc(9, 1)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(cpc, 1, 1)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(cpc, 1, 1)') AS a(periodo Text, \"0\" Numeric, \"2\" Numeric, \"3\" Numeric, \"4\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
            }
        }
        
        if ($sector == 10) {
            $consulta = 'SELECT ig.periodo, ia.indice alimentos, ina.indice no_alimentos, ig.indice general
                FROM if_indice2(10, 1) AS ia(periodo Text, indice Numeric),
                if_indice2(11, 1) AS ina(periodo Text, indice Numeric),
                if_indice2(4, 1) AS ig(periodo Text, indice Numeric)
                WHERE ig.periodo = ia.periodo
                AND ig.periodo = ina.periodo
                AND ig.periodo >= \''.$perini.'\' AND ig.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 11) {
            switch ($clasificacion) {
                case 4:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(11, 4)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 4)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(codigo_ciiu, 1, 4)') AS a(periodo Text, \"0111\" Numeric,\"0113\" Numeric,\"0121\" Numeric,\"0122\" Numeric,\"0123\" Numeric,\"0124\" Numeric,\"0127\" Numeric,\"0128\" Numeric,\"0146\" Numeric,\"0230\" Numeric,\"0322\" Numeric,\"1010\" Numeric,\"1030\" Numeric,\"1040\" Numeric,\"1050\" Numeric,\"1061\" Numeric,\"1062\" Numeric,\"1071\" Numeric,\"1072\" Numeric,\"1073\" Numeric,\"1074\" Numeric,\"1079\" Numeric,\"1080\" Numeric,\"1101\" Numeric,\"1102\" Numeric,\"1103\" Numeric,\"1104\" Numeric,\"1200\" Numeric,\"1311\" Numeric,\"1392\" Numeric,\"1399\" Numeric,\"1410\" Numeric,\"1430\" Numeric,\"1520\" Numeric,\"1701\" Numeric,\"1709\" Numeric,\"1811\" Numeric,\"1920\" Numeric,\"2011\" Numeric,\"2021\" Numeric,\"2022\" Numeric,\"2023\" Numeric,\"2100\" Numeric,\"2219\" Numeric,\"2220\" Numeric,\"2310\" Numeric,\"2392\" Numeric,\"2393\" Numeric,\"2394\" Numeric,\"2410\" Numeric,\"3290\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 3:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(11, 3)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 3)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(codigo_ciiu, 1, 3)') AS a(periodo Text, \"011\" Numeric,\"012\" Numeric,\"014\" Numeric,\"023\" Numeric,\"032\" Numeric,\"101\" Numeric,\"103\" Numeric,\"104\" Numeric,\"105\" Numeric,\"106\" Numeric,\"107\" Numeric,\"108\" Numeric,\"110\" Numeric,\"120\" Numeric,\"131\" Numeric,\"139\" Numeric,\"141\" Numeric,\"143\" Numeric,\"152\" Numeric,\"170\" Numeric,\"181\" Numeric,\"192\" Numeric,\"201\" Numeric,\"202\" Numeric,\"210\" Numeric,\"221\" Numeric,\"222\" Numeric,\"231\" Numeric,\"239\" Numeric,\"241\" Numeric,\"329\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 2:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(11, 2)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 2)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''NACIONAL''
                        ORDER BY substring(codigo_ciiu, 1, 2)') AS a(periodo Text, \"01\" Numeric,\"02\" Numeric,\"03\" Numeric,\"10\" Numeric,\"11\" Numeric,\"12\" Numeric,\"13\" Numeric,\"14\" Numeric,\"15\" Numeric,\"17\" Numeric,\"18\" Numeric,\"19\" Numeric,\"20\" Numeric,\"21\" Numeric,\"22\" Numeric,\"23\" Numeric,\"24\" Numeric,\"32\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
            }
        }
        
        if ($sector == 12) {
            switch ($clasificacion) {
                case 4:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(12, 4)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 4)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(codigo_ciiu, 1, 4)') AS a(periodo Text, \"0111\" Numeric,\"0113\" Numeric,\"0121\" Numeric,\"0123\" Numeric,\"0124\" Numeric,\"0127\" Numeric,\"0128\" Numeric,\"0146\" Numeric,\"0322\" Numeric,\"1010\" Numeric,\"1020\" Numeric,\"1030\" Numeric,\"1040\" Numeric,\"1050\" Numeric,\"1061\" Numeric,\"1071\" Numeric,\"1072\" Numeric,\"1073\" Numeric,\"1074\" Numeric,\"1079\" Numeric,\"1080\" Numeric,\"1101\" Numeric,\"1102\" Numeric,\"1103\" Numeric,\"1104\" Numeric,\"1200\" Numeric,\"1311\" Numeric,\"1392\" Numeric,\"1410\" Numeric,\"1430\" Numeric,\"1512\" Numeric,\"1520\" Numeric,\"1610\" Numeric,\"1621\" Numeric,\"1701\" Numeric,\"1709\" Numeric,\"1920\" Numeric,\"2021\" Numeric,\"2022\" Numeric,\"2023\" Numeric,\"2029\" Numeric,\"2100\" Numeric,\"2211\" Numeric,\"2219\" Numeric,\"2220\" Numeric,\"2310\" Numeric,\"2392\" Numeric,\"2393\" Numeric,\"2394\" Numeric,\"2680\" Numeric,\"2720\" Numeric,\"2740\" Numeric,\"3290\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 3:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(12, 3)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 3)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(codigo_ciiu, 1, 3)') AS a(periodo Text, \"011\" Numeric,\"012\" Numeric,\"014\" Numeric,\"032\" Numeric,\"101\" Numeric,\"102\" Numeric,\"103\" Numeric,\"104\" Numeric,\"105\" Numeric,\"106\" Numeric,\"107\" Numeric,\"108\" Numeric,\"110\" Numeric,\"120\" Numeric,\"131\" Numeric,\"139\" Numeric,\"141\" Numeric,\"143\" Numeric,\"151\" Numeric,\"152\" Numeric,\"161\" Numeric,\"162\" Numeric,\"170\" Numeric,\"192\" Numeric,\"202\" Numeric,\"210\" Numeric,\"221\" Numeric,\"222\" Numeric,\"231\" Numeric,\"239\" Numeric,\"268\" Numeric,\"272\" Numeric,\"274\" Numeric,\"329\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 2:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(12, 2)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 2)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        AND origen = ''IMPORTADO''
                        ORDER BY substring(codigo_ciiu, 1, 2)') AS a(periodo Text, \"01\" Numeric,\"03\" Numeric,\"10\" Numeric,\"11\" Numeric,\"12\" Numeric,\"13\" Numeric,\"14\" Numeric,\"15\" Numeric,\"16\" Numeric,\"17\" Numeric,\"19\" Numeric,\"20\" Numeric,\"21\" Numeric,\"22\" Numeric,\"23\" Numeric,\"26\" Numeric,\"27\" Numeric,\"32\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
            }
        }
        
        if ($sector == 13) {
            switch ($clasificacion) {
                case 4:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(13, 4)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 4)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(codigo_ciiu, 1, 4)') AS a(periodo Text, \"0111\" Numeric,\"0113\" Numeric,\"0121\" Numeric,\"0122\" Numeric,\"0123\" Numeric,\"0124\" Numeric,\"0127\" Numeric,\"0128\" Numeric,\"0146\" Numeric,\"0230\" Numeric,\"0322\" Numeric,\"1010\" Numeric,\"1020\" Numeric,\"1030\" Numeric,\"1040\" Numeric,\"1050\" Numeric,\"1061\" Numeric,\"1062\" Numeric,\"1071\" Numeric,\"1072\" Numeric,\"1073\" Numeric,\"1074\" Numeric,\"1079\" Numeric,\"1080\" Numeric,\"1101\" Numeric,\"1102\" Numeric,\"1103\" Numeric,\"1104\" Numeric,\"1200\" Numeric,\"1311\" Numeric,\"1392\" Numeric,\"1399\" Numeric,\"1410\" Numeric,\"1430\" Numeric,\"1512\" Numeric,\"1520\" Numeric,\"1610\" Numeric,\"1621\" Numeric,\"1701\" Numeric,\"1709\" Numeric,\"1811\" Numeric,\"1920\" Numeric,\"2011\" Numeric,\"2021\" Numeric,\"2022\" Numeric,\"2023\" Numeric,\"2029\" Numeric,\"2100\" Numeric,\"2211\" Numeric,\"2219\" Numeric,\"2220\" Numeric,\"2310\" Numeric,\"2392\" Numeric,\"2393\" Numeric,\"2394\" Numeric,\"2410\" Numeric,\"2680\" Numeric,\"2720\" Numeric,\"2740\" Numeric,\"3290\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 3:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(13, 3)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 3)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(codigo_ciiu, 1, 3)') AS a(periodo Text, \"011\" Numeric,\"012\" Numeric,\"014\" Numeric,\"023\" Numeric,\"032\" Numeric,\"101\" Numeric,\"102\" Numeric,\"103\" Numeric,\"104\" Numeric,\"105\" Numeric,\"106\" Numeric,\"107\" Numeric,\"108\" Numeric,\"110\" Numeric,\"120\" Numeric,\"131\" Numeric,\"139\" Numeric,\"141\" Numeric,\"143\" Numeric,\"151\" Numeric,\"152\" Numeric,\"161\" Numeric,\"162\" Numeric,\"170\" Numeric,\"181\" Numeric,\"192\" Numeric,\"201\" Numeric,\"202\" Numeric,\"210\" Numeric,\"221\" Numeric,\"222\" Numeric,\"231\" Numeric,\"239\" Numeric,\"241\" Numeric,\"268\" Numeric,\"272\" Numeric,\"274\" Numeric,\"329\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
                case 2:
                    $consulta = "SELECT *
                        FROM crosstab('SELECT *
                        FROM if_indice_ciiu(13, 2)
                        ORDER BY periodo, codigo',
                        'SELECT distinct substring(codigo_ciiu, 1, 2)
                        FROM seg_producto
                        WHERE apiestado NOT IN(''ANULADO'', ''DESCARTADO'')
                        ORDER BY substring(codigo_ciiu, 1, 2)') AS a(periodo Text, \"01\" Numeric, \"02\" Numeric, \"03\" Numeric, \"10\" Numeric, \"11\" Numeric, \"12\" Numeric, \"13\" Numeric, \"14\" Numeric, \"15\" Numeric, \"16\" Numeric, \"17\" Numeric, \"18\" Numeric, \"19\" Numeric, \"20\" Numeric, \"21\" Numeric, \"22\" Numeric, \"23\" Numeric, \"24\" Numeric, \"26\" Numeric, \"27\" Numeric, \"32\" Numeric)
                        WHERE a.periodo >= '".$perini."' AND a.periodo <= '".$perfin."'";
                    break;
            }
        }
        
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_variacion($sector, $clasificacion, $perini, $perfin, $n) {
        if ($sector == 1) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT *
                        FROM if_variacion(1, 10, '.$n.') AS (periodo VarChar, "0101010001" Numeric,"0101030001" Numeric,"0101030002" Numeric,"0101040001" Numeric,"0101050101" Numeric,"0101050201" Numeric,"0102010101" Numeric,"0102010201" Numeric,"0102010301" Numeric,"0102010401" Numeric,"0102019901" Numeric,"0102019902" Numeric,"0102020101" Numeric,"0102020102" Numeric,"0102020201" Numeric,"0102020301" Numeric,"0102029901" Numeric,"0102030101" Numeric,"0102030201" Numeric,"0102030301" Numeric,"0102039901" Numeric,"0102039902" Numeric,"0102039903" Numeric,"0102040101" Numeric,"0102040201" Numeric,"0102040301" Numeric,"0102040401" Numeric,"0102040402" Numeric,"0102040501" Numeric,"0102049901" Numeric,"0102049902" Numeric,"0103010101" Numeric,"0103010102" Numeric,"0103010301" Numeric,"0103020101" Numeric,"0103020201" Numeric,"0103020301" Numeric,"0103020401" Numeric,"0104010101" Numeric,"0104010201" Numeric,"0104010301" Numeric,"0104010401" Numeric,"0104010501" Numeric,"0104020001" Numeric,"0104030101" Numeric,"0104030201" Numeric,"0104040101" Numeric,"0104040201" Numeric,"0104040301" Numeric,"0104040401" Numeric,"0104040402" Numeric,"0104040601" Numeric,"0104040701" Numeric,"0104040901" Numeric,"0105010001" Numeric,"0105020001" Numeric,"0105030101" Numeric,"0105030201" Numeric,"0201010001" Numeric,"0303020101" Numeric,"0403010101" Numeric,"0403010201" Numeric,"0403010301" Numeric,"0403010401" Numeric,"0403010501" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT *
                        FROM if_variacion(1, 8, '.$n.') AS (periodo VarChar, "01010100" Numeric,"01010300" Numeric,"01010400" Numeric,"01010501" Numeric,"01010502" Numeric,"01020101" Numeric,"01020102" Numeric,"01020103" Numeric,"01020104" Numeric,"01020199" Numeric,"01020201" Numeric,"01020202" Numeric,"01020203" Numeric,"01020299" Numeric,"01020301" Numeric,"01020302" Numeric,"01020303" Numeric,"01020399" Numeric,"01020401" Numeric,"01020402" Numeric,"01020403" Numeric,"01020404" Numeric,"01020405" Numeric,"01020499" Numeric,"01030101" Numeric,"01030103" Numeric,"01030201" Numeric,"01030202" Numeric,"01030203" Numeric,"01030204" Numeric,"01040101" Numeric,"01040102" Numeric,"01040103" Numeric,"01040104" Numeric,"01040105" Numeric,"01040200" Numeric,"01040301" Numeric,"01040302" Numeric,"01040401" Numeric,"01040402" Numeric,"01040403" Numeric,"01040404" Numeric,"01040406" Numeric,"01040407" Numeric,"01040409" Numeric,"01050100" Numeric,"01050200" Numeric,"01050301" Numeric,"01050302" Numeric,"02010100" Numeric,"03030201" Numeric,"04030101" Numeric,"04030102" Numeric,"04030103" Numeric,"04030104" Numeric,"04030105" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT *
                        FROM if_variacion(1, 6, '.$n.') AS (periodo VarChar, "010101" Numeric, "010103" Numeric, "010104" Numeric, "010105" Numeric, "010201" Numeric, "010202" Numeric, "010203" Numeric, "010204" Numeric, "010301" Numeric, "010302" Numeric, "010401" Numeric, "010402" Numeric, "010403" Numeric, "010404" Numeric, "010501" Numeric, "010502" Numeric, "010503" Numeric, "020101" Numeric, "030302" Numeric, "040301" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT *
                        FROM if_variacion(1, 4, '.$n.') AS (periodo VarChar, "0101" Numeric,"0102" Numeric,"0103" Numeric,"0104" Numeric,"0105" Numeric,"0201" Numeric,"0303" Numeric,"0403" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT *
                        FROM if_variacion(1, 2, '.$n.') AS (periodo VarChar, "01" Numeric,"02" Numeric,"03" Numeric,"04" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT *
                        FROM if_variacion(1, 1, '.$n.') AS (periodo Text, "Indice" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 2) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT *
                        FROM if_variacion(2, 10, '.$n.') AS (periodo VarChar, "0701010101" Numeric, "0701010201" Numeric, "0701020101" Numeric, "0701030101" Numeric, "0701040101" Numeric, "0702010101" Numeric, "0702010102" Numeric, "0702010103" Numeric, "0702010104" Numeric, "0702010105" Numeric, "0801010001" Numeric, "0801020101" Numeric, "0802010101" Numeric, "0802010201" Numeric, "0802010202" Numeric, "0802010301" Numeric, "0803010001" Numeric, "0803010002" Numeric, "0899990101" Numeric, "0899990201" Numeric, "0899990202" Numeric, "0899990203" Numeric, "0899990301" Numeric, "0899990501" Numeric, "0899990601" Numeric, "0899999901" Numeric, "0901010101" Numeric, "0901010201" Numeric, "0901010301" Numeric, "0902010101" Numeric, "0902019901" Numeric, "0902019902" Numeric, "0902019903" Numeric, "0903010001" Numeric, "0903020001" Numeric, "0903990101" Numeric, "0903990102" Numeric, "0903990201" Numeric, "0903990202" Numeric, "0903990203" Numeric, "0903990204" Numeric, "0903999901" Numeric, "0903999903" Numeric, "0904010101" Numeric, "0906010001" Numeric, "1001010101" Numeric, "1001019901" Numeric, "1102010001" Numeric, "1102020001" Numeric, "1102030001" Numeric, "1102040101" Numeric, "1102040201" Numeric, "1103010101" Numeric, "1201010001" Numeric, "1201010002" Numeric, "1202010101" Numeric, "1202010301" Numeric, "1202010302" Numeric, "1202010401" Numeric, "1203010101" Numeric, "1203010102" Numeric, "1203010401" Numeric, "1203010501" Numeric, "1203010601" Numeric, "1203010602" Numeric, "1204010101" Numeric, "1204010201" Numeric, "1204019901" Numeric, "1205010101" Numeric, "1205010102" Numeric, "1205010103" Numeric, "1205010104" Numeric, "1205010105" Numeric, "1205010106" Numeric, "1205010108" Numeric, "1205010109" Numeric, "1205010201" Numeric, "1205010202" Numeric, "1205010203" Numeric, "1205010301" Numeric, "1205010401" Numeric, "1205010501" Numeric, "1205019901" Numeric, "1205019902" Numeric, "1205019903" Numeric, "1301010101" Numeric, "1301010301" Numeric, "1301020101" Numeric, "1301029901" Numeric, "1302010101" Numeric, "1303010101" Numeric, "1303010201" Numeric, "1303019901" Numeric, "1303019902" Numeric, "1303019903" Numeric, "1304010101" Numeric, "1304010201" Numeric, "1402010201" Numeric, "1405010101" Numeric, "1405010102" Numeric, "1405010201" Numeric, "1405010401" Numeric, "1405010402" Numeric, "1405010403" Numeric, "1405010601" Numeric, "1405010602" Numeric, "1405010603" Numeric, "1405010901" Numeric, "1405020201" Numeric, "1405020203" Numeric, "1405020204" Numeric, "1405020205" Numeric, "1405020206" Numeric, "1405020207" Numeric, "1405020301" Numeric, "1405020401" Numeric, "1405020402" Numeric, "1405020404" Numeric, "1405020405" Numeric, "1405020601" Numeric, "1405020602" Numeric, "1406010101" Numeric, "1406010102" Numeric, "1406010601" Numeric, "1406010701" Numeric, "1406019901" Numeric, "1407030101" Numeric, "1407030102" Numeric, "1407030103" Numeric, "1407030201" Numeric, "1603010102" Numeric, "1603010103" Numeric, "1603010104" Numeric, "1603010105" Numeric, "1603020102" Numeric, "1603020105" Numeric, "1604010301" Numeric, "1604019901" Numeric, "1702010101" Numeric, "1801040101" Numeric, "1801049901" Numeric, "1802010301" Numeric, "1802010401" Numeric, "1802010402" Numeric, "1803010101" Numeric, "1803010102" Numeric, "1803010201" Numeric, "1803010301" Numeric, "1804010101" Numeric, "1804010102" Numeric, "1804010103" Numeric, "1804010104" Numeric, "1804010105" Numeric, "1804010106" Numeric, "1804010107" Numeric, "1804010108" Numeric, "1804010109" Numeric, "1804010110" Numeric, "1804010111" Numeric, "1804010112" Numeric, "1804010114" Numeric, "1804010115" Numeric, "1804010116" Numeric, "1804010201" Numeric, "1804019902" Numeric, "1804019903" Numeric, "1804019904" Numeric, "1805010101" Numeric, "1805010102" Numeric, "1805010103" Numeric, "1805010104" Numeric, "1805010105" Numeric, "1805010106" Numeric, "1805010107" Numeric, "1805019901" Numeric, "1805019902" Numeric, "1805019903" Numeric, "1805019904" Numeric, "1805019905" Numeric, "1805019911" Numeric, "1805019912" Numeric, "1806019902" Numeric, "1807010101" Numeric, "1807010102" Numeric, "1807010103" Numeric, "1807010201" Numeric, "1807010202" Numeric, "1807019901" Numeric, "1807019902" Numeric, "1807019903" Numeric, "1807019904" Numeric, "1807019905" Numeric, "1807019906" Numeric, "1807019907" Numeric, "1901010501" Numeric, "1901010502" Numeric, "1902010301" Numeric, "1903020201" Numeric, "1903020401" Numeric, "1903020402" Numeric, "1903020403" Numeric, "1903020404" Numeric, "1903020405" Numeric, "1903020407" Numeric, "1903020408" Numeric, "1904010201" Numeric, "2002010501" Numeric, "2002010502" Numeric, "2002010503" Numeric, "2002019901" Numeric, "2204010307" Numeric, "2204010308" Numeric, "2204010309" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT *
                        FROM if_variacion(2, 8, '.$n.') AS (periodo VarChar, "07010101" Numeric, "07010102" Numeric, "07010201" Numeric, "07010301" Numeric, "07010401" Numeric, "07020101" Numeric, "08010100" Numeric, "08010201" Numeric, "08020101" Numeric, "08020102" Numeric, "08020103" Numeric, "08030100" Numeric, "08999901" Numeric, "08999902" Numeric, "08999903" Numeric, "08999905" Numeric, "08999906" Numeric, "08999999" Numeric, "09010101" Numeric, "09010102" Numeric, "09010103" Numeric, "09020101" Numeric, "09020199" Numeric, "09030100" Numeric, "09030200" Numeric, "09039901" Numeric, "09039902" Numeric, "09039999" Numeric, "09040101" Numeric, "09060100" Numeric, "10010101" Numeric, "10010199" Numeric, "11020100" Numeric, "11020200" Numeric, "11020300" Numeric, "11020401" Numeric, "11020402" Numeric, "11030101" Numeric, "12010100" Numeric, "12020101" Numeric, "12020103" Numeric, "12020104" Numeric, "12030101" Numeric, "12030104" Numeric, "12030105" Numeric, "12030106" Numeric, "12040101" Numeric, "12040102" Numeric, "12040199" Numeric, "12050101" Numeric, "12050102" Numeric, "12050103" Numeric, "12050104" Numeric, "12050105" Numeric, "12050199" Numeric, "13010101" Numeric, "13010103" Numeric, "13010201" Numeric, "13010299" Numeric, "13020101" Numeric, "13030101" Numeric, "13030102" Numeric, "13030199" Numeric, "13040101" Numeric, "13040102" Numeric, "14020102" Numeric, "14050101" Numeric, "14050102" Numeric, "14050104" Numeric, "14050106" Numeric, "14050109" Numeric, "14050202" Numeric, "14050203" Numeric, "14050204" Numeric, "14050206" Numeric, "14060101" Numeric, "14060106" Numeric, "14060107" Numeric, "14060199" Numeric, "14070301" Numeric, "14070302" Numeric, "16030101" Numeric, "16030201" Numeric, "16040103" Numeric, "16040199" Numeric, "17020101" Numeric, "18010401" Numeric, "18010499" Numeric, "18020103" Numeric, "18020104" Numeric, "18030101" Numeric, "18030102" Numeric, "18030103" Numeric, "18040101" Numeric, "18040102" Numeric, "18040199" Numeric, "18050101" Numeric, "18050199" Numeric, "18060199" Numeric, "18070101" Numeric, "18070102" Numeric, "18070199" Numeric, "19010105" Numeric, "19020103" Numeric, "19030202" Numeric, "19030204" Numeric, "19040102" Numeric, "20020105" Numeric, "20020199" Numeric, "22040103" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT *
                        FROM if_variacion(2, 6, '.$n.') AS (periodo VarChar, "070101" Numeric, "070102" Numeric, "070103" Numeric, "070104" Numeric, "070201" Numeric, "080101" Numeric, "080102" Numeric, "080201" Numeric, "080301" Numeric, "089999" Numeric, "090101" Numeric, "090201" Numeric, "090301" Numeric, "090302" Numeric, "090399" Numeric, "090401" Numeric, "090601" Numeric, "100101" Numeric, "110201" Numeric, "110202" Numeric, "110203" Numeric, "110204" Numeric, "110301" Numeric, "120101" Numeric, "120201" Numeric, "120301" Numeric, "120401" Numeric, "120501" Numeric, "130101" Numeric, "130102" Numeric, "130201" Numeric, "130301" Numeric, "130401" Numeric, "140201" Numeric, "140501" Numeric, "140502" Numeric, "140601" Numeric, "140703" Numeric, "160301" Numeric, "160302" Numeric, "160401" Numeric, "170201" Numeric, "180104" Numeric, "180201" Numeric, "180301" Numeric, "180401" Numeric, "180501" Numeric, "180601" Numeric, "180701" Numeric, "190101" Numeric, "190201" Numeric, "190302" Numeric, "190401" Numeric, "200201" Numeric, "220401" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT *
                        FROM if_variacion(2, 4, '.$n.') AS (periodo VarChar, "0701" Numeric, "0702" Numeric, "0801" Numeric, "0802" Numeric, "0803" Numeric, "0899" Numeric, "0901" Numeric, "0902" Numeric, "0903" Numeric, "0904" Numeric, "0906" Numeric, "1001" Numeric, "1102" Numeric, "1103" Numeric, "1201" Numeric, "1202" Numeric, "1203" Numeric, "1204" Numeric, "1205" Numeric, "1301" Numeric, "1302" Numeric, "1303" Numeric, "1304" Numeric, "1402" Numeric, "1405" Numeric, "1406" Numeric, "1407" Numeric, "1603" Numeric, "1604" Numeric, "1702" Numeric, "1801" Numeric, "1802" Numeric, "1803" Numeric, "1804" Numeric, "1805" Numeric, "1806" Numeric, "1807" Numeric, "1901" Numeric, "1902" Numeric, "1903" Numeric, "1904" Numeric, "2002" Numeric, "2204" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT *
                        FROM if_variacion(2, 2, '.$n.') AS (periodo VarChar, "07" Numeric,"08" Numeric,"09" Numeric,"10" Numeric,"11" Numeric,"12" Numeric,"13" Numeric,"14" Numeric,"16" Numeric,"17" Numeric,"18" Numeric,"19" Numeric,"20" Numeric,"22" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT *
                        FROM if_variacion(2, 1, '.$n.') AS (periodo Text, "Indice" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 3) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT *
                        FROM if_variacion(3, 10, '.$n.') AS (periodo VarChar, "0102010402" Numeric, "0102020101" Numeric, "0102020301" Numeric, "0102040101" Numeric, "0102040401" Numeric, "0103010102" Numeric, "0103020401" Numeric, "0104010301" Numeric, "0104020001" Numeric, "0104040801" Numeric, "0303020101" Numeric, "0403010301" Numeric, "0701020301" Numeric, "0701040101" Numeric, "0702010101" Numeric, "0702010102" Numeric, "0702010103" Numeric, "0702019901" Numeric, "0702019902" Numeric, "0702019903" Numeric, "0801020101" Numeric, "0802010202" Numeric, "0802010301" Numeric, "0803010001" Numeric, "0803010002" Numeric, "0899990102" Numeric, "0899990103" Numeric, "0899990301" Numeric, "0899990401" Numeric, "0899990501" Numeric, "0901010101" Numeric, "0903020001" Numeric, "0903990202" Numeric, "0903990203" Numeric, "0903990204" Numeric, "0903999902" Numeric, "0904010101" Numeric, "1001010101" Numeric, "1102010001" Numeric, "1102020001" Numeric, "1201010001" Numeric, "1202010101" Numeric, "1202010301" Numeric, "1202010302" Numeric, "1202010401" Numeric, "1203010101" Numeric, "1203010501" Numeric, "1203010601" Numeric, "1203010602" Numeric, "1204010101" Numeric, "1204019901" Numeric, "1205010101" Numeric, "1205010102" Numeric, "1205010107" Numeric, "1205010109" Numeric, "1205010202" Numeric, "1205010204" Numeric, "1205010301" Numeric, "1205019902" Numeric, "1205019903" Numeric, "1301010101" Numeric, "1301020101" Numeric, "1302010201" Numeric, "1302010401" Numeric, "1302019901" Numeric, "1303019901" Numeric, "1303019902" Numeric, "1304010101" Numeric, "1403010201" Numeric, "1403010301" Numeric, "1403010601" Numeric, "1405010101" Numeric, "1405010401" Numeric, "1405010403" Numeric, "1405010602" Numeric, "1405020201" Numeric, "1405020202" Numeric, "1405020203" Numeric, "1405020204" Numeric, "1405020205" Numeric, "1405020206" Numeric, "1405020301" Numeric, "1405020401" Numeric, "1405020402" Numeric, "1405020403" Numeric, "1405020405" Numeric, "1405020601" Numeric, "1405020602" Numeric, "1406010101" Numeric, "1406010201" Numeric, "1406010401" Numeric, "1406010702" Numeric, "1407029901" Numeric, "1407030101" Numeric, "1407030102" Numeric, "1407030103" Numeric, "1407030201" Numeric, "1407039901" Numeric, "1501010101" Numeric, "1502010102" Numeric, "1603010101" Numeric, "1603020101" Numeric, "1603020102" Numeric, "1603020103" Numeric, "1603020104" Numeric, "1603020105" Numeric, "1702010101" Numeric, "1702010201" Numeric, "1702010401" Numeric, "1702010402" Numeric, "1802010201" Numeric, "1802010202" Numeric, "1802010302" Numeric, "1802010401" Numeric, "1802010402" Numeric, "1802019901" Numeric, "1803010101" Numeric, "1803010102" Numeric, "1803010201" Numeric, "1803010301" Numeric, "1803010401" Numeric, "1804010101" Numeric, "1804010102" Numeric, "1804010103" Numeric, "1804010105" Numeric, "1804010106" Numeric, "1804010113" Numeric, "1804010117" Numeric, "1804010202" Numeric, "1804010203" Numeric, "1804010204" Numeric, "1804019901" Numeric, "1804019902" Numeric, "1804019903" Numeric, "1805010102" Numeric, "1805010103" Numeric, "1805010105" Numeric, "1805010106" Numeric, "1805010107" Numeric, "1805010108" Numeric, "1805010109" Numeric, "1805010301" Numeric, "1805019901" Numeric, "1805019902" Numeric, "1805019903" Numeric, "1805019904" Numeric, "1805019905" Numeric, "1805019906" Numeric, "1805019907" Numeric, "1805019908" Numeric, "1805019909" Numeric, "1805019910" Numeric, "1806010401" Numeric, "1806010601" Numeric, "1806019901" Numeric, "1807010101" Numeric, "1807010104" Numeric, "1807010105" Numeric, "1807010106" Numeric, "1807010107" Numeric, "1807010203" Numeric, "1808019908" Numeric, "1808019909" Numeric, "1808019910" Numeric, "1901010101" Numeric, "1901010501" Numeric, "1901010502" Numeric, "1901010503" Numeric, "1902010201" Numeric, "1902010301" Numeric, "1903020405" Numeric, "1903020406" Numeric, "1903020408" Numeric, "1904010101" Numeric, "1904010102" Numeric, "1904010201" Numeric, "2105010401" Numeric, "2105010402" Numeric, "2105010501" Numeric, "2106010401" Numeric, "2106010501" Numeric, "2204010201" Numeric, "2204010202" Numeric, "2204010203" Numeric, "2204010204" Numeric, "2204010301" Numeric, "2204010302" Numeric, "2204010303" Numeric, "2204010304" Numeric, "2204010305" Numeric, "2204010306" Numeric, "2204010307" Numeric, "2204010310" Numeric, "2204010601" Numeric, "2204010602" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT *
                        FROM if_variacion(3, 8, '.$n.') AS (periodo VarChar, "01020104" Numeric, "01020201" Numeric, "01020203" Numeric, "01020401" Numeric, "01020404" Numeric, "01030101" Numeric, "01030204" Numeric, "01040103" Numeric, "01040200" Numeric, "01040408" Numeric, "03030201" Numeric, "04030103" Numeric, "07010203" Numeric, "07010401" Numeric, "07020101" Numeric, "07020199" Numeric, "08010201" Numeric, "08020102" Numeric, "08020103" Numeric, "08030100" Numeric, "08999901" Numeric, "08999903" Numeric, "08999904" Numeric, "08999905" Numeric, "09010101" Numeric, "09030200" Numeric, "09039902" Numeric, "09039999" Numeric, "09040101" Numeric, "10010101" Numeric, "11020100" Numeric, "11020200" Numeric, "12010100" Numeric, "12020101" Numeric, "12020103" Numeric, "12020104" Numeric, "12030101" Numeric, "12030105" Numeric, "12030106" Numeric, "12040101" Numeric, "12040199" Numeric, "12050101" Numeric, "12050102" Numeric, "12050103" Numeric, "12050199" Numeric, "13010101" Numeric, "13010201" Numeric, "13020102" Numeric, "13020104" Numeric, "13020199" Numeric, "13030199" Numeric, "13040101" Numeric, "14030102" Numeric, "14030103" Numeric, "14030106" Numeric, "14050101" Numeric, "14050104" Numeric, "14050106" Numeric, "14050202" Numeric, "14050203" Numeric, "14050204" Numeric, "14050206" Numeric, "14060101" Numeric, "14060102" Numeric, "14060104" Numeric, "14060107" Numeric, "14070299" Numeric, "14070301" Numeric, "14070302" Numeric, "14070399" Numeric, "15010101" Numeric, "15020101" Numeric, "16030101" Numeric, "16030201" Numeric, "17020101" Numeric, "17020102" Numeric, "17020104" Numeric, "18020102" Numeric, "18020103" Numeric, "18020104" Numeric, "18020199" Numeric, "18030101" Numeric, "18030102" Numeric, "18030103" Numeric, "18030104" Numeric, "18040101" Numeric, "18040102" Numeric, "18040199" Numeric, "18050101" Numeric, "18050103" Numeric, "18050199" Numeric, "18060104" Numeric, "18060106" Numeric, "18060199" Numeric, "18070101" Numeric, "18070102" Numeric, "18080199" Numeric, "19010101" Numeric, "19010105" Numeric, "19020102" Numeric, "19020103" Numeric, "19030204" Numeric, "19040101" Numeric, "19040102" Numeric, "21050104" Numeric, "21050105" Numeric, "21060104" Numeric, "21060105" Numeric, "22040102" Numeric, "22040103" Numeric, "22040106" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT *
                        FROM if_variacion(3, 6, '.$n.') AS (periodo VarChar, "010201" Numeric, "010202" Numeric, "010204" Numeric, "010301" Numeric, "010302" Numeric, "010401" Numeric, "010402" Numeric, "010404" Numeric, "030302" Numeric, "040301" Numeric, "070102" Numeric, "070104" Numeric, "070201" Numeric, "080102" Numeric, "080201" Numeric, "080301" Numeric, "089999" Numeric, "090101" Numeric, "090302" Numeric, "090399" Numeric, "090401" Numeric, "100101" Numeric, "110201" Numeric, "110202" Numeric, "120101" Numeric, "120201" Numeric, "120301" Numeric, "120401" Numeric, "120501" Numeric, "130101" Numeric, "130102" Numeric, "130201" Numeric, "130301" Numeric, "130401" Numeric, "140301" Numeric, "140501" Numeric, "140502" Numeric, "140601" Numeric, "140702" Numeric, "140703" Numeric, "150101" Numeric, "150201" Numeric, "160301" Numeric, "160302" Numeric, "170201" Numeric, "180201" Numeric, "180301" Numeric, "180401" Numeric, "180501" Numeric, "180601" Numeric, "180701" Numeric, "180801" Numeric, "190101" Numeric, "190201" Numeric, "190302" Numeric, "190401" Numeric, "210501" Numeric, "210601" Numeric, "220401" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT *
                        FROM if_variacion(3, 4, '.$n.') AS (periodo VarChar, "0102" Numeric, "0103" Numeric, "0104" Numeric, "0303" Numeric, "0403" Numeric, "0701" Numeric, "0702" Numeric, "0801" Numeric, "0802" Numeric, "0803" Numeric, "0899" Numeric, "0901" Numeric, "0903" Numeric, "0904" Numeric, "1001" Numeric, "1102" Numeric, "1201" Numeric, "1202" Numeric, "1203" Numeric, "1204" Numeric, "1205" Numeric, "1301" Numeric, "1302" Numeric, "1303" Numeric, "1304" Numeric, "1403" Numeric, "1405" Numeric, "1406" Numeric, "1407" Numeric, "1501" Numeric, "1502" Numeric, "1603" Numeric, "1702" Numeric, "1802" Numeric, "1803" Numeric, "1804" Numeric, "1805" Numeric, "1806" Numeric, "1807" Numeric, "1808" Numeric, "1901" Numeric, "1902" Numeric, "1903" Numeric, "1904" Numeric, "2105" Numeric, "2106" Numeric, "2204" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT *
                        FROM if_variacion(3, 2, '.$n.') AS (periodo VarChar, "01" Numeric, "03" Numeric, "04" Numeric, "07" Numeric, "08" Numeric, "09" Numeric, "10" Numeric, "11" Numeric, "12" Numeric, "13" Numeric, "14" Numeric, "15" Numeric, "16" Numeric, "17" Numeric, "18" Numeric, "19" Numeric, "21" Numeric, "22" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT *
                        FROM if_variacion(3, 1, '.$n.') AS (periodo Text, "Indice" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 4) {
            $consulta = 'SELECT a.periodo, (a."Indice" / lag(a."Indice", ?) OVER (ORDER BY a.periodo) - 1) * 100 agricola, (m."Indice" / lag(m."Indice", ?) OVER (ORDER BY m.periodo) - 1) * 100 manufacturado, (i."Indice" / lag(i."Indice", ?) OVER (ORDER BY i.periodo) - 1) * 100 importado, ((a."Indice" * 0.0662860495139841 + m."Indice" * 0.652194863130863 + i."Indice" * 0.281519087355153) / (lag(a."Indice", ?) OVER (ORDER BY a.periodo) * 0.0662860495139841 + lag(m."Indice", ?) OVER (ORDER BY m.periodo) * 0.652194863130863 + lag(i."Indice", ?) OVER (ORDER BY i.periodo) * 0.281519087355153) - 1) * 100 general
                FROM if_indice(1, 1) AS a(periodo Text, "Indice" Numeric),
                    if_indice(2, 1) AS m(periodo Text, "Indice" Numeric),
                    if_indice(3, 1) AS i(periodo Text, "Indice" Numeric)
                WHERE a.periodo = m.periodo AND m.periodo = i.periodo
                AND a.periodo >= \''.$perini.'\' AND a.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 5) {
            $consulta = 'SELECT a.periodo, ((a."Indice" * 0.0922586088890994 + m."Indice" * 0.907741391110901) / (lag(a."Indice", ?) OVER (ORDER BY a.periodo) * 0.0922586088890994 + lag(m."Indice", ?) OVER (ORDER BY a.periodo) * 0.907741391110901) - 1) * 100 nacional, (i."Indice" / lag(i."Indice", ?) OVER (ORDER BY i.periodo) - 1) * 100 importado, ((a."Indice" * 0.0662860495139841 + m."Indice" * 0.652194863130863 + i."Indice" * 0.281519087355153) / (lag(a."Indice", ?) OVER (ORDER BY a.periodo) * 0.0662860495139841 + lag(m."Indice", ?) OVER (ORDER BY m.periodo) * 0.652194863130863 + lag(i."Indice", ?) OVER (ORDER BY i.periodo) * 0.281519087355153) - 1) * 100 general
                FROM if_indice(1, 1) AS a(periodo Text, "Indice" Numeric),
                    if_indice(2, 1) AS m(periodo Text, "Indice" Numeric),
                    if_indice(3, 1) AS i(periodo Text, "Indice" Numeric)
                WHERE a.periodo = m.periodo AND m.periodo = i.periodo
                AND a.periodo >= \''.$perini.'\' AND a.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 6) {
            $consulta = 'SELECT ig.periodo, (ian.indice / lag(ian.indice, ?) OVER (ORDER BY ian.periodo) - 1) * 100 agricola_nacional, (iai.indice / lag(iai.indice, ?) OVER (ORDER BY iai.periodo) - 1) * 100 agricola_importado, (iag.indice / lag(iag.indice, ?) OVER (ORDER BY iag.periodo) - 1) * 100 agricola, (imn.indice / lag(imn.indice, ?) OVER (ORDER BY imn.periodo) - 1) * 100 manufacturado_nacional, (imi.indice / lag(imi.indice, ?) OVER (ORDER BY imi.periodo) - 1) * 100 manufacturado_importado, (img.indice / lag(img.indice, ?) OVER (ORDER BY img.periodo) - 1) * 100 manufacturado, (ig.indice / lag(ig.indice, ?) OVER (ORDER BY ig.periodo) - 1) * 100 general
                FROM if_indice2(1, 1) AS ian(periodo Text, indice Numeric),
                if_indice2(5, 1) AS iai(periodo Text, indice Numeric),
                if_indice2(6, 1) AS iag(periodo Text, indice Numeric),
                if_indice2(2, 1) AS imn(periodo Text, indice Numeric),
                if_indice2(7, 1) AS imi(periodo Text, indice Numeric),
                if_indice2(8, 1) AS img(periodo Text, indice Numeric),
                if_indice2(4, 1) AS ig(periodo Text, indice Numeric)
                WHERE ig.periodo = ian.periodo
                AND ig.periodo = iai.periodo
                AND ig.periodo = iag.periodo
                AND ig.periodo = imn.periodo
                AND ig.periodo = imi.periodo
                AND ig.periodo = img.periodo
                AND ig.periodo >= \''.$perini.'\' AND ig.periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 10) {
            $consulta = 'SELECT ia.periodo, (ia."Indice" / lag(ia."Indice", ?) OVER (ORDER BY ia.periodo) - 1) * 100 alimentos, (ina."Indice" / lag(ina."Indice", ?) OVER (ORDER BY ina.periodo) - 1) * 100 no_alimentos, (ig."Indice" / lag(ig."Indice", ?) OVER (ORDER BY ig.periodo) - 1) * 100 general
                FROM if_indice2(10, 1) AS ia(periodo Text, "Indice" Numeric),
                    if_indice2(10, 1) AS ina(periodo Text, "Indice" Numeric),
                    if_indice2(4, 1) AS ig(periodo Text, "Indice" Numeric)
                WHERE ig.periodo = ia.periodo AND ig.periodo = ina.periodo
                AND ig.periodo >= \''.$perini.'\' AND ig.periodo <= \''.$perfin.'\'';
        }
        switch ($sector) {
            case 6:
                $query = $this->db->query($consulta, Array($n, $n, $n, $n, $n, $n, $n));
                break;
            case 10:
                $query = $this->db->query($consulta, Array($n, $n, $n));
                break;
            default:
                $query = $this->db->query($consulta, Array($n, $n, $n, $n, $n, $n));
                break;
        }
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_incidencia($sector, $clasificacion, $perini, $perfin) {
        if ($sector == 1) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT *
                        FROM if_incidencia(1, 10) AS (periodo VarChar, "0101010001" Numeric,"0101030001" Numeric,"0101030002" Numeric,"0101040001" Numeric,"0101050101" Numeric,"0101050201" Numeric,"0102010101" Numeric,"0102010201" Numeric,"0102010301" Numeric,"0102010401" Numeric,"0102019901" Numeric,"0102019902" Numeric,"0102020101" Numeric,"0102020102" Numeric,"0102020201" Numeric,"0102020301" Numeric,"0102029901" Numeric,"0102030101" Numeric,"0102030201" Numeric,"0102030301" Numeric,"0102039901" Numeric,"0102039902" Numeric,"0102039903" Numeric,"0102040101" Numeric,"0102040201" Numeric,"0102040301" Numeric,"0102040401" Numeric,"0102040402" Numeric,"0102040501" Numeric,"0102049901" Numeric,"0102049902" Numeric,"0103010101" Numeric,"0103010102" Numeric,"0103010301" Numeric,"0103020101" Numeric,"0103020201" Numeric,"0103020301" Numeric,"0103020401" Numeric,"0104010101" Numeric,"0104010201" Numeric,"0104010301" Numeric,"0104010401" Numeric,"0104010501" Numeric,"0104020001" Numeric,"0104030101" Numeric,"0104030201" Numeric,"0104040101" Numeric,"0104040201" Numeric,"0104040301" Numeric,"0104040401" Numeric,"0104040402" Numeric,"0104040601" Numeric,"0104040701" Numeric,"0104040901" Numeric,"0105010001" Numeric,"0105020001" Numeric,"0105030101" Numeric,"0105030201" Numeric,"0201010001" Numeric,"0303020101" Numeric,"0403010101" Numeric,"0403010201" Numeric,"0403010301" Numeric,"0403010401" Numeric,"0403010501" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT *
                        FROM if_incidencia(1, 8) AS (periodo VarChar, "01010100" Numeric,"01010300" Numeric,"01010400" Numeric,"01010501" Numeric,"01010502" Numeric,"01020101" Numeric,"01020102" Numeric,"01020103" Numeric,"01020104" Numeric,"01020199" Numeric,"01020201" Numeric,"01020202" Numeric,"01020203" Numeric,"01020299" Numeric,"01020301" Numeric,"01020302" Numeric,"01020303" Numeric,"01020399" Numeric,"01020401" Numeric,"01020402" Numeric,"01020403" Numeric,"01020404" Numeric,"01020405" Numeric,"01020499" Numeric,"01030101" Numeric,"01030103" Numeric,"01030201" Numeric,"01030202" Numeric,"01030203" Numeric,"01030204" Numeric,"01040101" Numeric,"01040102" Numeric,"01040103" Numeric,"01040104" Numeric,"01040105" Numeric,"01040200" Numeric,"01040301" Numeric,"01040302" Numeric,"01040401" Numeric,"01040402" Numeric,"01040403" Numeric,"01040404" Numeric,"01040406" Numeric,"01040407" Numeric,"01040409" Numeric,"01050100" Numeric,"01050200" Numeric,"01050301" Numeric,"01050302" Numeric,"02010100" Numeric,"03030201" Numeric,"04030101" Numeric,"04030102" Numeric,"04030103" Numeric,"04030104" Numeric,"04030105" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT *
                        FROM if_incidencia(1, 6) AS (periodo VarChar, "010101" Numeric, "010103" Numeric, "010104" Numeric, "010105" Numeric, "010201" Numeric, "010202" Numeric, "010203" Numeric, "010204" Numeric, "010301" Numeric, "010302" Numeric, "010401" Numeric, "010402" Numeric, "010403" Numeric, "010404" Numeric, "010501" Numeric, "010502" Numeric, "010503" Numeric, "020101" Numeric, "030302" Numeric, "040301" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT *
                        FROM if_incidencia(1, 4) AS (periodo VarChar, "0101" Numeric,"0102" Numeric,"0103" Numeric,"0104" Numeric,"0105" Numeric,"0201" Numeric,"0303" Numeric,"0403" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT *
                        FROM if_incidencia(1, 2) AS (periodo VarChar, "01" Numeric,"02" Numeric,"03" Numeric,"04" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT *
                        FROM if_incidencia(1, 1) AS (periodo VarChar, "Incidencia" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 2) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT *
                        FROM if_incidencia(2, 10) AS (periodo VarChar, "0701010101" Numeric,"0701010201" Numeric,"0701020101" Numeric,"0701030101" Numeric,"0701040101" Numeric,"0702010101" Numeric,"0702010102" Numeric,"0702010103" Numeric,"0702010104" Numeric,"0702010105" Numeric,"0801010001" Numeric,"0801020101" Numeric,"0802010101" Numeric,"0802010201" Numeric,"0802010202" Numeric,"0802010301" Numeric,"0803010001" Numeric,"0803010002" Numeric,"0899990101" Numeric,"0899990201" Numeric,"0899990202" Numeric,"0899990203" Numeric,"0899990301" Numeric,"0899990501" Numeric,"0899990601" Numeric,"0899999901" Numeric,"0901010101" Numeric,"0901010201" Numeric,"0901010301" Numeric,"0902010101" Numeric,"0902019901" Numeric,"0902019902" Numeric,"0902019903" Numeric,"0903010001" Numeric,"0903020001" Numeric,"0903990101" Numeric,"0903990102" Numeric,"0903990201" Numeric,"0903990202" Numeric,"0903990203" Numeric,"0903990204" Numeric,"0903999901" Numeric,"0903999903" Numeric,"0904010101" Numeric,"0906010001" Numeric,"1001010101" Numeric,"1001019901" Numeric,"1102010001" Numeric,"1102020001" Numeric,"1102030001" Numeric,"1102040101" Numeric,"1102040201" Numeric,"1103010101" Numeric,"1201010001" Numeric,"1201010002" Numeric,"1202010101" Numeric,"1202010301" Numeric,"1202010302" Numeric,"1202010401" Numeric,"1203010101" Numeric,"1203010102" Numeric,"1203010401" Numeric,"1203010501" Numeric,"1203010601" Numeric,"1203010602" Numeric,"1204010101" Numeric,"1204010201" Numeric,"1204019901" Numeric,"1205010101" Numeric,"1205010102" Numeric,"1205010103" Numeric,"1205010104" Numeric,"1205010105" Numeric,"1205010106" Numeric,"1205010108" Numeric,"1205010109" Numeric,"1205010201" Numeric,"1205010202" Numeric,"1205010203" Numeric,"1205010301" Numeric,"1205010401" Numeric,"1205010501" Numeric,"1205019901" Numeric,"1205019902" Numeric,"1205019903" Numeric,"1301010101" Numeric,"1301010301" Numeric,"1301020101" Numeric,"1301029901" Numeric,"1302010101" Numeric,"1303010101" Numeric,"1303010201" Numeric,"1303019901" Numeric,"1303019902" Numeric,"1303019903" Numeric,"1304010101" Numeric,"1304010201" Numeric,"1402010201" Numeric,"1405010101" Numeric,"1405010102" Numeric,"1405010201" Numeric,"1405010401" Numeric,"1405010402" Numeric,"1405010403" Numeric,"1405010601" Numeric,"1405010602" Numeric,"1405010603" Numeric,"1405010901" Numeric,"1405020201" Numeric,"1405020203" Numeric,"1405020204" Numeric,"1405020205" Numeric,"1405020206" Numeric,"1405020207" Numeric,"1405020301" Numeric,"1405020401" Numeric,"1405020402" Numeric,"1405020404" Numeric,"1405020405" Numeric,"1405020601" Numeric,"1405020602" Numeric,"1406010101" Numeric,"1406010102" Numeric,"1406010601" Numeric,"1406010701" Numeric,"1406019901" Numeric,"1407030101" Numeric,"1407030102" Numeric,"1407030103" Numeric,"1407030201" Numeric,"1603010102" Numeric,"1603010103" Numeric,"1603010104" Numeric,"1603010105" Numeric,"1603020102" Numeric,"1603020105" Numeric,"1604010301" Numeric,"1604019901" Numeric,"1702010101" Numeric,"1801040101" Numeric,"1801049901" Numeric,"1802010301" Numeric,"1802010401" Numeric,"1802010402" Numeric,"1803010101" Numeric,"1803010102" Numeric,"1803010201" Numeric,"1803010301" Numeric,"1804010101" Numeric,"1804010102" Numeric,"1804010103" Numeric,"1804010104" Numeric,"1804010105" Numeric,"1804010106" Numeric,"1804010107" Numeric,"1804010108" Numeric,"1804010109" Numeric,"1804010110" Numeric,"1804010111" Numeric,"1804010112" Numeric,"1804010114" Numeric,"1804010115" Numeric,"1804010116" Numeric,"1804010201" Numeric,"1804019902" Numeric,"1804019903" Numeric,"1804019904" Numeric,"1805010101" Numeric,"1805010102" Numeric,"1805010103" Numeric,"1805010104" Numeric,"1805010105" Numeric,"1805010106" Numeric,"1805010107" Numeric,"1805019901" Numeric,"1805019902" Numeric,"1805019903" Numeric,"1805019904" Numeric,"1805019905" Numeric,"1805019911" Numeric,"1805019912" Numeric,"1806019902" Numeric,"1807010101" Numeric,"1807010102" Numeric,"1807010103" Numeric,"1807010201" Numeric,"1807010202" Numeric,"1807019901" Numeric,"1807019902" Numeric,"1807019903" Numeric,"1807019904" Numeric,"1807019905" Numeric,"1807019906" Numeric,"1807019907" Numeric,"1901010501" Numeric,"1901010502" Numeric,"1902010301" Numeric,"1903020201" Numeric,"1903020401" Numeric,"1903020402" Numeric,"1903020403" Numeric,"1903020404" Numeric,"1903020405" Numeric,"1903020407" Numeric,"1903020408" Numeric,"1904010201" Numeric,"2002010501" Numeric,"2002010502" Numeric,"2002010503" Numeric,"2002019901" Numeric,"2204010307" Numeric,"2204010308" Numeric,"2204010309" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT *
                        FROM if_incidencia(2, 8) AS (periodo VarChar, "07010101" Numeric,"07010102" Numeric,"07010201" Numeric,"07010301" Numeric,"07010401" Numeric,"07020101" Numeric,"08010100" Numeric,"08010201" Numeric,"08020101" Numeric,"08020102" Numeric,"08020103" Numeric,"08030100" Numeric,"08999901" Numeric,"08999902" Numeric,"08999903" Numeric,"08999905" Numeric,"08999906" Numeric,"08999999" Numeric,"09010101" Numeric,"09010102" Numeric,"09010103" Numeric,"09020101" Numeric,"09020199" Numeric,"09030100" Numeric,"09030200" Numeric,"09039901" Numeric,"09039902" Numeric,"09039999" Numeric,"09040101" Numeric,"09060100" Numeric,"10010101" Numeric,"10010199" Numeric,"11020100" Numeric,"11020200" Numeric,"11020300" Numeric,"11020401" Numeric,"11020402" Numeric,"11030101" Numeric,"12010100" Numeric,"12020101" Numeric,"12020103" Numeric,"12020104" Numeric,"12030101" Numeric,"12030104" Numeric,"12030105" Numeric,"12030106" Numeric,"12040101" Numeric,"12040102" Numeric,"12040199" Numeric,"12050101" Numeric,"12050102" Numeric,"12050103" Numeric,"12050104" Numeric,"12050105" Numeric,"12050199" Numeric,"13010101" Numeric,"13010103" Numeric,"13010201" Numeric,"13010299" Numeric,"13020101" Numeric,"13030101" Numeric,"13030102" Numeric,"13030199" Numeric,"13040101" Numeric,"13040102" Numeric,"14020102" Numeric,"14050101" Numeric,"14050102" Numeric,"14050104" Numeric,"14050106" Numeric,"14050109" Numeric,"14050202" Numeric,"14050203" Numeric,"14050204" Numeric,"14050206" Numeric,"14060101" Numeric,"14060106" Numeric,"14060107" Numeric,"14060199" Numeric,"14070301" Numeric,"14070302" Numeric,"16030101" Numeric,"16030201" Numeric,"16040103" Numeric,"16040199" Numeric,"17020101" Numeric,"18010401" Numeric,"18010499" Numeric,"18020103" Numeric,"18020104" Numeric,"18030101" Numeric,"18030102" Numeric,"18030103" Numeric,"18040101" Numeric,"18040102" Numeric,"18040199" Numeric,"18050101" Numeric,"18050199" Numeric,"18060199" Numeric,"18070101" Numeric,"18070102" Numeric,"18070199" Numeric,"19010105" Numeric,"19020103" Numeric,"19030202" Numeric,"19030204" Numeric,"19040102" Numeric,"20020105" Numeric,"20020199" Numeric,"22040103" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT *
                        FROM if_incidencia(2, 6) AS (periodo VarChar, "070101" Numeric, "070102" Numeric, "070103" Numeric, "070104" Numeric, "070201" Numeric, "080101" Numeric, "080102" Numeric, "080201" Numeric, "080301" Numeric, "089999" Numeric, "090101" Numeric, "090201" Numeric, "090301" Numeric, "090302" Numeric, "090399" Numeric, "090401" Numeric, "090601" Numeric, "100101" Numeric, "110201" Numeric, "110202" Numeric, "110203" Numeric, "110204" Numeric, "110301" Numeric, "120101" Numeric, "120201" Numeric, "120301" Numeric, "120401" Numeric, "120501" Numeric, "130101" Numeric, "130102" Numeric, "130201" Numeric, "130301" Numeric, "130401" Numeric, "140201" Numeric, "140501" Numeric, "140502" Numeric, "140601" Numeric, "140703" Numeric, "160301" Numeric, "160302" Numeric, "160401" Numeric, "170201" Numeric, "180104" Numeric, "180201" Numeric, "180301" Numeric, "180401" Numeric, "180501" Numeric, "180601" Numeric, "180701" Numeric, "190101" Numeric, "190201" Numeric, "190302" Numeric, "190401" Numeric, "200201" Numeric, "220401" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT *
                        FROM if_incidencia(2, 4) AS (periodo VarChar, "0701" Numeric,"0702" Numeric,"0801" Numeric,"0802" Numeric,"0803" Numeric,"0899" Numeric,"0901" Numeric,"0902" Numeric,"0903" Numeric,"0904" Numeric,"0906" Numeric,"1001" Numeric,"1102" Numeric,"1103" Numeric,"1201" Numeric,"1202" Numeric,"1203" Numeric,"1204" Numeric,"1205" Numeric,"1301" Numeric,"1302" Numeric,"1303" Numeric,"1304" Numeric,"1402" Numeric,"1405" Numeric,"1406" Numeric,"1407" Numeric,"1603" Numeric,"1604" Numeric,"1702" Numeric,"1801" Numeric,"1802" Numeric,"1803" Numeric,"1804" Numeric,"1805" Numeric,"1806" Numeric,"1807" Numeric,"1901" Numeric,"1902" Numeric,"1903" Numeric,"1904" Numeric,"2002" Numeric,"2204" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT *
                        FROM if_incidencia(2, 2) AS (periodo VarChar, "07" Numeric,"08" Numeric,"09" Numeric,"10" Numeric,"11" Numeric,"12" Numeric,"13" Numeric,"14" Numeric,"16" Numeric,"17" Numeric,"18" Numeric,"19" Numeric,"20" Numeric,"22" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT *
                        FROM if_incidencia(2, 1) AS (periodo VarChar, "Incidencia" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 3) {
            switch ($clasificacion) {
                case 10:
                    $consulta = 'SELECT *
                        FROM if_incidencia(3, 10) AS (periodo VarChar, "0102010402" Numeric, "0102020101" Numeric, "0102020301" Numeric, "0102040101" Numeric, "0102040401" Numeric, "0103010102" Numeric, "0103020401" Numeric, "0104010301" Numeric, "0104020001" Numeric, "0104040801" Numeric, "0303020101" Numeric, "0403010301" Numeric, "0701020301" Numeric, "0701040101" Numeric, "0702010101" Numeric, "0702010102" Numeric, "0702010103" Numeric, "0702019901" Numeric, "0702019902" Numeric, "0702019903" Numeric, "0801020101" Numeric, "0802010202" Numeric, "0802010301" Numeric, "0803010001" Numeric, "0803010002" Numeric, "0899990102" Numeric, "0899990103" Numeric, "0899990301" Numeric, "0899990401" Numeric, "0899990501" Numeric, "0901010101" Numeric, "0903020001" Numeric, "0903990202" Numeric, "0903990203" Numeric, "0903990204" Numeric, "0903999902" Numeric, "0904010101" Numeric, "1001010101" Numeric, "1102010001" Numeric, "1102020001" Numeric, "1201010001" Numeric, "1202010101" Numeric, "1202010301" Numeric, "1202010302" Numeric, "1202010401" Numeric, "1203010101" Numeric, "1203010501" Numeric, "1203010601" Numeric, "1203010602" Numeric, "1204010101" Numeric, "1204019901" Numeric, "1205010101" Numeric, "1205010102" Numeric, "1205010107" Numeric, "1205010109" Numeric, "1205010202" Numeric, "1205010204" Numeric, "1205010301" Numeric, "1205019902" Numeric, "1205019903" Numeric, "1301010101" Numeric, "1301020101" Numeric, "1302010201" Numeric, "1302010401" Numeric, "1302019901" Numeric, "1303019901" Numeric, "1303019902" Numeric, "1304010101" Numeric, "1403010201" Numeric, "1403010301" Numeric, "1403010601" Numeric, "1405010101" Numeric, "1405010401" Numeric, "1405010403" Numeric, "1405010602" Numeric, "1405020201" Numeric, "1405020202" Numeric, "1405020203" Numeric, "1405020204" Numeric, "1405020205" Numeric, "1405020206" Numeric, "1405020301" Numeric, "1405020401" Numeric, "1405020402" Numeric, "1405020403" Numeric, "1405020405" Numeric, "1405020601" Numeric, "1405020602" Numeric, "1406010101" Numeric, "1406010201" Numeric, "1406010401" Numeric, "1406010702" Numeric, "1407029901" Numeric, "1407030101" Numeric, "1407030102" Numeric, "1407030103" Numeric, "1407030201" Numeric, "1407039901" Numeric, "1501010101" Numeric, "1502010102" Numeric, "1603010101" Numeric, "1603020101" Numeric, "1603020102" Numeric, "1603020103" Numeric, "1603020104" Numeric, "1603020105" Numeric, "1702010101" Numeric, "1702010201" Numeric, "1702010401" Numeric, "1702010402" Numeric, "1802010201" Numeric, "1802010202" Numeric, "1802010302" Numeric, "1802010401" Numeric, "1802010402" Numeric, "1802019901" Numeric, "1803010101" Numeric, "1803010102" Numeric, "1803010201" Numeric, "1803010301" Numeric, "1803010401" Numeric, "1804010101" Numeric, "1804010102" Numeric, "1804010103" Numeric, "1804010105" Numeric, "1804010106" Numeric, "1804010113" Numeric, "1804010117" Numeric, "1804010202" Numeric, "1804010203" Numeric, "1804010204" Numeric, "1804019901" Numeric, "1804019902" Numeric, "1804019903" Numeric, "1805010102" Numeric, "1805010103" Numeric, "1805010105" Numeric, "1805010106" Numeric, "1805010107" Numeric, "1805010108" Numeric, "1805010109" Numeric, "1805010301" Numeric, "1805019901" Numeric, "1805019902" Numeric, "1805019903" Numeric, "1805019904" Numeric, "1805019905" Numeric, "1805019906" Numeric, "1805019907" Numeric, "1805019908" Numeric, "1805019909" Numeric, "1805019910" Numeric, "1806010401" Numeric, "1806010601" Numeric, "1806019901" Numeric, "1807010101" Numeric, "1807010104" Numeric, "1807010105" Numeric, "1807010106" Numeric, "1807010107" Numeric, "1807010203" Numeric, "1808019908" Numeric, "1808019909" Numeric, "1808019910" Numeric, "1901010101" Numeric, "1901010501" Numeric, "1901010502" Numeric, "1901010503" Numeric, "1902010201" Numeric, "1902010301" Numeric, "1903020405" Numeric, "1903020406" Numeric, "1903020408" Numeric, "1904010101" Numeric, "1904010102" Numeric, "1904010201" Numeric, "2105010401" Numeric, "2105010402" Numeric, "2105010501" Numeric, "2106010401" Numeric, "2106010501" Numeric, "2204010201" Numeric, "2204010202" Numeric, "2204010203" Numeric, "2204010204" Numeric, "2204010301" Numeric, "2204010302" Numeric, "2204010303" Numeric, "2204010304" Numeric, "2204010305" Numeric, "2204010306" Numeric, "2204010307" Numeric, "2204010310" Numeric, "2204010601" Numeric, "2204010602" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 8:
                    $consulta = 'SELECT *
                        FROM if_incidencia(3, 8) AS (periodo VarChar, "01020104" Numeric, "01020201" Numeric, "01020203" Numeric, "01020401" Numeric, "01020404" Numeric, "01030101" Numeric, "01030204" Numeric, "01040103" Numeric, "01040200" Numeric, "01040408" Numeric, "03030201" Numeric, "04030103" Numeric, "07010203" Numeric, "07010401" Numeric, "07020101" Numeric, "07020199" Numeric, "08010201" Numeric, "08020102" Numeric, "08020103" Numeric, "08030100" Numeric, "08999901" Numeric, "08999903" Numeric, "08999904" Numeric, "08999905" Numeric, "09010101" Numeric, "09030200" Numeric, "09039902" Numeric, "09039999" Numeric, "09040101" Numeric, "10010101" Numeric, "11020100" Numeric, "11020200" Numeric, "12010100" Numeric, "12020101" Numeric, "12020103" Numeric, "12020104" Numeric, "12030101" Numeric, "12030105" Numeric, "12030106" Numeric, "12040101" Numeric, "12040199" Numeric, "12050101" Numeric, "12050102" Numeric, "12050103" Numeric, "12050199" Numeric, "13010101" Numeric, "13010201" Numeric, "13020102" Numeric, "13020104" Numeric, "13020199" Numeric, "13030199" Numeric, "13040101" Numeric, "14030102" Numeric, "14030103" Numeric, "14030106" Numeric, "14050101" Numeric, "14050104" Numeric, "14050106" Numeric, "14050202" Numeric, "14050203" Numeric, "14050204" Numeric, "14050206" Numeric, "14060101" Numeric, "14060102" Numeric, "14060104" Numeric, "14060107" Numeric, "14070299" Numeric, "14070301" Numeric, "14070302" Numeric, "14070399" Numeric, "15010101" Numeric, "15020101" Numeric, "16030101" Numeric, "16030201" Numeric, "17020101" Numeric, "17020102" Numeric, "17020104" Numeric, "18020102" Numeric, "18020103" Numeric, "18020104" Numeric, "18020199" Numeric, "18030101" Numeric, "18030102" Numeric, "18030103" Numeric, "18030104" Numeric, "18040101" Numeric, "18040102" Numeric, "18040199" Numeric, "18050101" Numeric, "18050103" Numeric, "18050199" Numeric, "18060104" Numeric, "18060106" Numeric, "18060199" Numeric, "18070101" Numeric, "18070102" Numeric, "18080199" Numeric, "19010101" Numeric, "19010105" Numeric, "19020102" Numeric, "19020103" Numeric, "19030204" Numeric, "19040101" Numeric, "19040102" Numeric, "21050104" Numeric, "21050105" Numeric, "21060104" Numeric, "21060105" Numeric, "22040102" Numeric, "22040103" Numeric, "22040106" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 6:
                    $consulta = 'SELECT *
                        FROM if_incidencia(3, 6) AS (periodo VarChar, "010201" Numeric, "010202" Numeric, "010204" Numeric, "010301" Numeric, "010302" Numeric, "010401" Numeric, "010402" Numeric, "010404" Numeric, "030302" Numeric, "040301" Numeric, "070102" Numeric, "070104" Numeric, "070201" Numeric, "080102" Numeric, "080201" Numeric, "080301" Numeric, "089999" Numeric, "090101" Numeric, "090302" Numeric, "090399" Numeric, "090401" Numeric, "100101" Numeric, "110201" Numeric, "110202" Numeric, "120101" Numeric, "120201" Numeric, "120301" Numeric, "120401" Numeric, "120501" Numeric, "130101" Numeric, "130102" Numeric, "130201" Numeric, "130301" Numeric, "130401" Numeric, "140301" Numeric, "140501" Numeric, "140502" Numeric, "140601" Numeric, "140702" Numeric, "140703" Numeric, "150101" Numeric, "150201" Numeric, "160301" Numeric, "160302" Numeric, "170201" Numeric, "180201" Numeric, "180301" Numeric, "180401" Numeric, "180501" Numeric, "180601" Numeric, "180701" Numeric, "180801" Numeric, "190101" Numeric, "190201" Numeric, "190302" Numeric, "190401" Numeric, "210501" Numeric, "210601" Numeric, "220401" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 4:
                    $consulta = 'SELECT *
                        FROM if_incidencia(3, 4) AS (periodo VarChar, "0102" Numeric, "0103" Numeric, "0104" Numeric, "0303" Numeric, "0403" Numeric, "0701" Numeric, "0702" Numeric, "0801" Numeric, "0802" Numeric, "0803" Numeric, "0899" Numeric, "0901" Numeric, "0903" Numeric, "0904" Numeric, "1001" Numeric, "1102" Numeric, "1201" Numeric, "1202" Numeric, "1203" Numeric, "1204" Numeric, "1205" Numeric, "1301" Numeric, "1302" Numeric, "1303" Numeric, "1304" Numeric, "1403" Numeric, "1405" Numeric, "1406" Numeric, "1407" Numeric, "1501" Numeric, "1502" Numeric, "1603" Numeric, "1702" Numeric, "1802" Numeric, "1803" Numeric, "1804" Numeric, "1805" Numeric, "1806" Numeric, "1807" Numeric, "1808" Numeric, "1901" Numeric, "1902" Numeric, "1903" Numeric, "1904" Numeric, "2105" Numeric, "2106" Numeric, "2204" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 2:
                    $consulta = 'SELECT *
                        FROM if_incidencia(3, 2) AS (periodo VarChar, "01" Numeric, "03" Numeric, "04" Numeric, "07" Numeric, "08" Numeric, "09" Numeric, "10" Numeric, "11" Numeric, "12" Numeric, "13" Numeric, "14" Numeric, "15" Numeric, "16" Numeric, "17" Numeric, "18" Numeric, "19" Numeric, "21" Numeric, "22" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
                case 1:
                    $consulta = 'SELECT *
                        FROM if_incidencia(3, 1) AS (periodo VarChar, "Incidencia" Numeric)
                        WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
                    break;
            }
        }
        
        if ($sector == 4) {
            $consulta = 'SELECT *
                FROM if_incidencia(4, 1) AS (periodo Text, agricola Numeric, manufacturado Numeric, importado Numeric)
                WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 5) {
            $consulta = 'SELECT *
                FROM if_incidencia(5, 1) AS (periodo Text, nacional Numeric, importado Numeric)
                WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 6) {
            $consulta = 'SELECT *
                FROM if_incidencia(6, 1) AS (periodo Text, agricola Numeric, manufacturado Numeric)
                WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
        }
        
        if ($sector == 10) {
            $consulta = 'SELECT *
                FROM if_incidencia(10, 1) AS (periodo Text, alimentos Numeric, no_alimentos Numeric)
                WHERE periodo >= \''.$perini.'\' AND periodo <= \''.$perfin.'\'';
        }
        
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_incidencia2($tipo, $per) {
        if ($tipo == 1) {
            $consulta = "WITH a AS(SELECT ip.periodo, ip.nacional, ip.codigo, (ip.indice - lag(ip.indice) OVER (PARTITION BY ip.nacional, ip.codigo ORDER BY ip.periodo)) / (lag(ig.indice) OVER (PARTITION BY ip.nacional, ip.codigo ORDER BY ig.periodo) / 100) * p.total_indice incidencia, (ip.indice / lag(ip.indice) OVER (PARTITION BY ip.nacional, ip.codigo ORDER BY ip.periodo) - 1) * 100 variacion
                FROM cat_ponderador p, if_indicev(4, 8) ip, if_indicev(4, 1) ig
                WHERE p.nacional = ip.nacional AND p.codigo = ip.codigo
                AND ip.periodo = ig.periodo)
                SELECT CASE a.nacional WHEN true THEN 'NACIONAL' ELSE 'IMPORTADO' END origen, c.especificacion, a.variacion, a.incidencia
                FROM cat_clasificador c, a
                WHERE c.codigo = a.codigo
                AND incidencia >= 0
                AND periodo = ?
                ORDER BY incidencia DESC";
        } else {
            $consulta = "WITH a AS(SELECT ip.periodo, ip.nacional, ip.codigo, (ip.indice - lag(ip.indice) OVER (PARTITION BY ip.nacional, ip.codigo ORDER BY ip.periodo)) / (lag(ig.indice) OVER (PARTITION BY ip.nacional, ip.codigo ORDER BY ig.periodo) / 100) * p.total_indice incidencia, (ip.indice / lag(ip.indice) OVER (PARTITION BY ip.nacional, ip.codigo ORDER BY ip.periodo) - 1) * 100 variacion
                FROM cat_ponderador p, if_indicev(4, 8) ip, if_indicev(4, 1) ig
                WHERE p.nacional = ip.nacional AND p.codigo = ip.codigo
                AND ip.periodo = ig.periodo)
                SELECT CASE a.nacional WHEN true THEN 'NACIONAL' ELSE 'IMPORTADO' END origen, c.especificacion, a.variacion, a.incidencia
                FROM cat_clasificador c, a
                WHERE c.codigo = a.codigo
                AND incidencia < 0
                AND periodo = ?
                ORDER BY incidencia";
        }
        
        $query = $this->db->query($consulta, $per);
        return $query->result_array();
    }
}