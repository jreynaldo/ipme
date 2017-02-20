<?php

class Precio extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Buscar los productos coincidentes con el criterio.
    ///@param id_boleta 1 para productos agricolas 2 para manufacturados.
    ///@param descripcion criterio de selección.
    ///@return Matriz con los productos coincidentes.
    public function get_productos($id_boleta, $descripcion) {
        $consulta = "SELECT d.descripcion departamento, i.descripcion, p.id_producto, p.producto, p.especificacion
            FROM cat_departamento d, seg_informador i, seg_producto p
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador
            AND p.apiestado <> 'ANULADO' AND p.id_boleta = ? AND p.especificacion ILIKE ?";
        $query = $this->db->query($consulta, Array($id_boleta, '%'.$descripcion.'%'));
        return $query->result_array();
    }
    
    ///@brief Recupera los precios.
    ///@param id_boleta 1 para productos agricolas 2 para manufacturados.
    ///@param descripcion criterio de selección.
    ///@return Matriz con los productos coincidentes.
    public function get_precios($id_boleta, $id, $gestion, $periodo) {
        if ($id_boleta == 1) {
            $consulta = "SELECT e.id_asignacion, e.id_pregunta, e.fila, e.correlativo, 'COTIZACION: ' || e.fila nro, e.codigo_respuesta, e.respuesta
                FROM enc_encuesta e, enc_informante i, seg_asignacion a
                WHERE e.id_asignacion = i.id_asignacion AND e.correlativo = i.correlativo 
                AND i.id_asignacion = a.id_asignacion AND e.id_pregunta IN(6, 9)
                AND e.id_last > 0 AND i.id = ? AND a.gestion = ? AND a.semana = ?
                ORDER BY id_asignacion, correlativo, id_pregunta, fila";
        } else {
            $consulta = "SELECT e.id_asignacion, e.id_pregunta, e.fila, e.correlativo, 'COTIZACION: ' || e.fila nro, e.codigo_respuesta, e.respuesta
                FROM enc_encuesta e, enc_informante i, seg_asignacion a
                WHERE e.id_asignacion = i.id_asignacion AND e.correlativo = i.correlativo 
                AND i.id_asignacion = a.id_asignacion AND e.id_pregunta IN(21, 23)
                AND e.id_last > 0 AND i.id = ? AND a.gestion = ? AND a.mes = ?
                ORDER BY id_asignacion, correlativo, id_pregunta, fila";
        }
        $query = $this->db->query($consulta, Array((Int)$id, (Int)$gestion, (Int)$periodo));
        return $query->result_array();
    }
    
    public function editar($id_asignacion, $correlativo, $id_pregunta, $fila, $valor, $usuario) {
        $consulta = "SELECT af_editar(?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id_asignacion, $correlativo, $id_pregunta, $fila, $valor, $usuario));
        return $query->row_array()['af_editar'];
    }
    
    ///@brief Selecciona los periodos en semanas dentro del rango especificado.
    ///@return Matriz con los precios recuperados.
    public function get_semanas($perini, $perfin) {
        $consulta = "SELECT to_char(g, 'YYYY TMMONTH ') || lpad(date_part('WEEK', g)::Text, 2, '0') p
            FROM generate_series('2014-08-01', f_semana_actual(), '1 WEEK') g
            WHERE to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') >= ?
            AND to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') <= ?";
        $query = $this->db->query($consulta, Array($perini, $perfin));
        return $query->result_array();
    }
    
    ///@brief Selecciona los periodos en semanas dentro del rango especificado.
    ///@return Matriz con los precios recuperados.
    public function get_meses($perini, $perfin) {
        $consulta = "SELECT to_char(g, 'YYYY TMMONTH') p
            FROM generate_series('2014-08-01', now(), '1 MONTH') g
            WHERE to_char(g, 'YYYY_MM') >= ?
            AND to_char(g, 'YYYY_MM') <= ?";
        $query = $this->db->query($consulta, Array($perini, $perfin));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_agricolas_detallado($perini, $perfin, $cod, $id_depto = '%') {
        $consulta = "SELECT *
            FROM r_precios_agricolas_detallado(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $cod, $id_depto));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_agricolas_horizontal($perini, $perfin, $cod, $id_depto = '%') {
        $consulta = "SELECT string_agg('\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\" Text', ', ')
            FROM generate_series('2014-08-01', f_semana_actual(), '1 WEEK') g
            WHERE to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') >= ?
            AND to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') <= ?";
        $query = $this->db->query($consulta, Array($perini, $perfin));
        $labels = $query->row_array()['string_agg'];
        
        $consulta = "SELECT string_agg('split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 1) \"P1.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || 
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 2) \"O1.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 3) \"P2.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 4) \"O2.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 5) \"P3.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 6) \"O3.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 7) \"P4.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 8) \"O4.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 9) \"P5.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 10) \"O5.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 11) \"P6.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 12) \"O6.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\"', ', ')
            FROM generate_series('2014-08-01', f_semana_actual(), '1 WEEK') g
            WHERE to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') >= ?
            AND to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') <= ?";
        $query = $this->db->query($consulta, Array($perini, $perfin));
        $columns = $query->row_array()['string_agg'];
        
        $consulta = "SELECT c.id_producto, d.descripcion departamento, i.descripcion mercado, p.codigo, p.producto, p.especificacion, p.cantidad_a_cotizar || ' ' || p.unidad_a_cotizar unidad, p.cantidad_equivalente || ' ' || p.unidad_convencional equivalencia, p.factor_ajuste factor, p.unidad_final, $columns 
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT i.id, a.gestion || ''.'' || lpad(a.semana::Text, 2, ''0''), string_agg(coalesce(e.respuesta, '''') || ''|'' || coalesce(e.observacion, ''''), ''|'' ORDER BY e.fila)
            FROM seg_asignacion a, enc_informante i, enc_encuesta e
            WHERE a.id_asignacion = i.id_asignacion
            AND i.id_asignacion = e.id_asignacion
            AND i.correlativo = e.correlativo
            AND e.id_pregunta = 6 AND e.id_last > 0
            GROUP BY i.id, a.gestion, a.semana
            ORDER BY i.id, a.gestion, a.semana',
            'SELECT to_char(g, ''YYYY.'') || lpad(date_part(''WEEK'', g)::Text, 2, ''0'')
            FROM generate_series(''2014-08-01'', f_semana_actual(), ''1 WEEK'') g
            WHERE to_char(g, ''YYYY.'') || lpad(date_part(''WEEK'', g)::Text, 2, ''0'') >= ''' || ? || '''
            AND to_char(g, ''YYYY.'') || lpad(date_part(''WEEK'', g)::Text, 2, ''0'') <= ''' || ? || '''') AS c(id_producto Int, $labels)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = c.id_producto AND p.apiestado <> 'ANULADO'
            AND d.id_departamento::Text LIKE ? AND p.codigo IN($cod)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $id_depto));
        return $query->result_array();
    }
    
    ///@brief Selecciona la procedencia en el periodo indicado de los productos especificados.
    ///@return Matriz con las procedencias recuperados.
    public function get_reporte_agricolas_horizontal_proc($perini, $perfin, $cod, $id_depto = '%') {
        $consulta = "SELECT string_agg('\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\" Text', ', ')
            FROM generate_series('2014-08-01', f_semana_actual(), '1 WEEK') g
            WHERE to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') >= ?
            AND to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') <= ?";
        $query = $this->db->query($consulta, Array($perini, $perfin));
        $labels = $query->row_array()['string_agg'];
        
        $consulta = "SELECT string_agg('split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 1) \"P1.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || 
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 2) \"O1.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 3) \"P2.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 4) \"O2.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 5) \"P3.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 6) \"O3.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 7) \"P4.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 8) \"O4.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 9) \"P5.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 10) \"O5.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 11) \"P6.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') ||
                '\", split_part(\"' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\", ''|'', 12) \"O6.' || to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') || '\"', ', ')
            FROM generate_series('2014-08-01', f_semana_actual(), '1 WEEK') g
            WHERE to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') >= ?
            AND to_char(g, 'YYYY.') || lpad(date_part('WEEK', g)::Text, 2, '0') <= ?";
        $query = $this->db->query($consulta, Array($perini, $perfin));
        $columns = $query->row_array()['string_agg'];
        
        $consulta = "SELECT c.id_producto, d.descripcion departamento, i.descripcion mercado, p.codigo, p.producto, p.especificacion, p.cantidad_a_cotizar || ' ' || p.unidad_a_cotizar unidad, p.cantidad_equivalente || ' ' || p.unidad_convencional equivalencia, p.factor_ajuste factor, p.unidad_final, $columns 
            FROM cat_departamento d, seg_informador i, seg_producto p, crosstab('SELECT i.id, a.gestion || ''.'' || lpad(a.semana::Text, 2, ''0''), string_agg(e.codigo_respuesta || ''. '' || trim(replace(respuesta, ''_'', '''')) || ''|'' || coalesce(e.observacion, ''''), ''|'' ORDER BY e.fila)
            FROM seg_asignacion a, enc_informante i, enc_encuesta e
            WHERE a.id_asignacion = i.id_asignacion
            AND i.id_asignacion = e.id_asignacion
            AND i.correlativo = e.correlativo
            AND e.id_pregunta = 7
            AND e.codigo_respuesta <> ''997''
            GROUP BY i.id, a.gestion, a.semana
            ORDER BY i.id, a.gestion, a.semana',
            'SELECT to_char(g, ''YYYY.'') || lpad(date_part(''WEEK'', g)::Text, 2, ''0'')
            FROM generate_series(''2014-08-01'', f_semana_actual(), ''1 WEEK'') g
            WHERE to_char(g, ''YYYY.'') || lpad(date_part(''WEEK'', g)::Text, 2, ''0'') >= ''' || ? || '''
            AND to_char(g, ''YYYY.'') || lpad(date_part(''WEEK'', g)::Text, 2, ''0'') <= ''' || ? || '''') AS c(id_producto Int, $labels)
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = c.id_producto AND p.apiestado <> 'ANULADO'
            AND d.id_departamento::Text LIKE ? AND p.codigo IN($cod)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $id_depto));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_manufacturados_detallado($perini, $perfin, $cod, $id_depto = '%') {
        $consulta = "SELECT *
            FROM r_precios_manufacturados_detallado(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $cod, $id_depto));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_manufacturados_horizontal($perini, $perfin, $cod, $id_depto = 0) {
        $consulta = "SELECT *
            FROM ar_comercializadora(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $id_depto, $cod));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_manufacturados_horizontal_unificado($perini, $perfin, $cod, $id_depto = 0) {
        $consulta = "SELECT *
            FROM ar_comercializadora_unificado(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $id_depto, $cod));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_manufacturados_geo($perini, $perfin, $cod, $id_depto = 0) {
        $consulta = "SELECT *
            FROM ar_comercializadora_geo(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $id_depto, $cod));
        return $query->result_array();
    }
    
    ///@brief Selecciona los precios recolectados en el periodo indicado de los productos especificados.
    ///@return Matriz con los precios recuperados.
    public function get_reporte_encadenado($perini, $perfin, $cod) {
        $consulta = "SELECT *
            FROM ar_encadenado(?, ?, ?)";
        $query = $this->db->query($consulta, Array($perini, $perfin, $cod));
        return $query->result_array();
    }
    
    ///@brief Selecciona las 8 variaciones myores en el último periodo.
    ///@return Matriz con las variaciones.
    public function get_variacion($id_usuario, $tipo, $neg = false) {
        if ($tipo == 1) {
            $this->db->query('SELECT rf_promediar()');
            $consulta = "SELECT a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion, ((geom_mean(a.precio_var / b.precio_var) - 1) * 100)::Numeric(5, 2) variacion, max(a.justificacion) justificacion
                FROM cat_clasificador c, agricola a, agricola b, variable v, seg_usuario u
                WHERE c.codigo = a.codigo
                AND a.gestion = date_part('YEAR', v.valor::TimeStamp)
                AND a.semana = date_part('WEEK', v.valor::TimeStamp)
                AND b.gestion = date_part('YEAR', v.valor::TimeStamp - '1 WEEK'::Interval)
                AND b.semana = date_part('WEEK', v.valor::TimeStamp - '1 WEEK'::Interval)
                AND a.id_producto = b.id_producto
                AND a.id_departamento = u.id_departamento
                AND v.var = 'ultimo_viernes'
                AND u.id_usuario = ?
                AND a.precio_var IS NOT NULL AND b.precio_var IS NOT NULL
                AND c.codigo NOT IN('0102030201', '0102030301', '0102039901', '0102039902', '0102039903')
                GROUP BY a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion
                ORDER BY variacion ".($neg?'':'DESC')."
                LIMIT 8";
            $query = $this->db->query($consulta, Array($id_usuario));
        } else {
            $this->db->query('SELECT rf_promediar_mes()');
            $consulta = "SELECT a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion, ((geom_mean(a.precio_var / b.precio_var) - 1) * 100)::Numeric(5, 2) variacion, max(a.justificacion) justificacion
                FROM cat_clasificador c, agricola a, agricola b, variable v, seg_usuario u
                WHERE c.codigo = a.codigo
                AND a.gestion = date_part('YEAR', v.valor::TimeStamp)
                AND a.mes = date_part('MONTH', v.valor::TimeStamp)
                AND a.semana = 0
                AND b.gestion = date_part('YEAR', v.valor::TimeStamp - '1 MONTH'::Interval)
                AND b.mes = date_part('MONTH', v.valor::TimeStamp - '1 MONTH'::Interval)
                AND b.semana = 0
                AND a.id_producto = b.id_producto
                AND a.id_departamento = u.id_departamento
                AND v.var = 'ultimo_mes'
                AND u.id_usuario = ?
                AND a.precio_var IS NOT NULL AND b.precio_var IS NOT NULL
                AND c.codigo NOT IN('0102030201', '0102030301', '0102039901', '0102039902', '0102039903')
                GROUP BY a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion
                ORDER BY variacion ".($neg?'':'DESC')."
                LIMIT 8";
            $query = $this->db->query($consulta, Array($id_usuario));
        }
        return $query->result_array();
    }
    
    public function get_just($tipo, $gestion, $periodo, $depto, $neg = false) {
        $gest_ant = $gestion;
        if ($tipo == 1) {
            if ($periodo > 1) {
                $per_ant = $periodo - 1;
            } else {
                $gest_ant = $gest_ant - 1;
                $per_ant = 52;
            }
            $consulta = "SELECT a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion, ((geom_mean(a.precio_var / b.precio_var) - 1) * 100)::Numeric(5, 2) variacion, max(a.justificacion) justificacion
                FROM cat_clasificador c, agricola a, agricola b, variable v
                WHERE c.codigo = a.codigo
                AND a.gestion = ?
                AND a.semana = ?
                AND b.gestion = ?
                AND b.semana = ?
                AND a.id_producto = b.id_producto
                AND a.id_departamento = ?
                AND v.var = 'ultimo_viernes'
                AND a.precio_var IS NOT NULL AND b.precio_var IS NOT NULL
                AND c.codigo NOT IN('0102030201', '0102030301', '0102039901', '0102039902', '0102039903')
                GROUP BY a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion
                ORDER BY variacion ".($neg?'':'DESC')."
                LIMIT 8";
            $query = $this->db->query($consulta, Array($gestion, $periodo, $gest_ant, $per_ant, $depto));
        } else {
            if ($periodo > 1) {
                $per_ant = $periodo - 1;
            } else {
                $gest_ant = $gest_ant - 1;
                $per_ant = 12;
            }
            $consulta = "SELECT a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion, ((geom_mean(a.precio_var / b.precio_var) - 1) * 100)::Numeric(5, 2) variacion, max(a.justificacion) justificacion
                FROM cat_clasificador c, agricola a, agricola b, variable v
                WHERE c.codigo = a.codigo
                AND a.gestion = ?
                AND a.mes = ?
                AND a.semana = 0
                AND b.gestion = ?
                AND b.mes = ?
                AND b.semana = 0
                AND a.id_producto = b.id_producto
                AND a.id_departamento = ?
                AND v.var = 'ultimo_viernes'
                AND a.precio_var IS NOT NULL AND b.precio_var IS NOT NULL
                AND c.codigo NOT IN('0102030201', '0102030301', '0102039901', '0102039902', '0102039903')
                GROUP BY a.gestion, a.mes, a.semana, a.origen, a.codigo, c.especificacion
                ORDER BY variacion ".($neg?'':'DESC')."
                LIMIT 8";
            $query = $this->db->query($consulta, Array($gestion, $periodo, $gest_ant, $per_ant, $depto));
        }
        return $query->result_array();
    }
    
    ///@brief Registra las justificaciones del periodo.
    ///@id_departamento Identificador del departamento.
    ///@datos JSon con las justificaciones.
    ///@observacion Observaciones generales a las variaciones.
    ///@return Ok o Error.
    public function set_just($id_departamento, $datos, $observacion) {
        $consulta = "SELECT af_set_just(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id_departamento, $datos, $observacion));
        return $query->row_array()['af_set_just'];
    }
    
    ///@brief Registra las justificaciones del periodo mensual.
    ///@id_departamento Identificador del departamento.
    ///@datos JSon con las justificaciones.
    ///@observacion Observaciones generales a las variaciones.
    ///@return Ok o Error.
    public function set_just_mes($id_departamento, $datos, $observacion) {
        $consulta = "SELECT af_set_just_mes(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id_departamento, $datos, $observacion));
        return $query->row_array()['af_set_just_mes'];
    }
    
    ///@brief Recupera la observacion del periodo activo en el departamento indicado.
    ///@id_departamento Identificador del departamento
    ///@id_boleta Tipo de operativo (Mercados/Comercializadoras).
    ///@usuario Usuario que realiza la acción.
    ///@return Observacion.
    public function get_observacion($id_departamento, $id_boleta, $usuario) {
        $consulta = "SELECT af_get_observacion(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id_departamento, $id_boleta, $usuario));
        return $query->row_array()['af_get_observacion'];
    }
}