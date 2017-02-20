<?php

class Asignacion extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Obtiene la asignación actual.
    ///@param identificador del usuario.
    ///@return false si no existe true si existe.
    public function get_asignacion($id) {
        $consulta = "SELECT af_ids_consolidables(?)";
        $query = $this->db->query($consulta, $id);
        return $query->row_array()['af_ids_consolidables'];
    }
    
    ///@brief Obtiene la asignación actual.
    ///@param identificador del usuario.
    ///@return false si no existe true si existe.
    public function get_asignacion2($boleta, $id, $gestion, $periodo) {
        if ($boleta == 1) {
            $consulta = "SELECT string_agg(id_asignacion::Text, ',')
                FROM seg_asignacion
                WHERE id_usuario = ?
                AND gestion = ?
                AND semana = ?
                AND exportado";
        } else {
            $consulta = "SELECT string_agg(id_asignacion::Text, ',')
                FROM seg_asignacion
                WHERE id_usuario = ?
                AND gestion = ?
                AND mes = ?
                AND semana = 0
                AND exportado";
        }
        $query = $this->db->query($consulta, Array($id, $gestion, $periodo));
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Marca asignación como exportada.
    ///@param identificador del usuario.
    ///@return nro de filas afectadas.
    public function set_exportado($id) {
        $consulta = "UPDATE seg_asignacion 
                SET exportado = true 
                WHERE activo = 1
                AND id_usuario = ?";
        $query = $this->db->query($consulta, $id);
        return $this->db->affected_rows($query);
    }
    
    public function get_departamento($id_usuario) {
        $consulta = "SELECT cd.id_departamento, cd.descripcion
            FROM cat_departamento cd, seg_usuario su
            WHERE (cd.id_departamento = su.id_departamento OR su.nacional)
            AND su.id_usuario = ?
            ORDER BY cd.id_departamento";
        $query = $this->db->query($consulta, Array($id_usuario));
        $res = $query->result_array();
        $depto = Array();
        foreach ($res as $row) {
            $depto[$row['id_departamento']] = $row['descripcion'];
        }
        return $depto;
    }
    
    public function get_values($id_usuario) {
        $consulta = "SELECT id_departamento, DATE_PART('WEEK', now() - '14 HOUR'::Interval)::Int week, DATE_PART('MONTH', now() - '14 HOUR'::Interval)::Int mes, DATE_PART('YEAR', now() - '14 HOUR'::Interval)::Int anio
            FROM seg_usuario
            WHERE id_usuario = ?";
        $query = $this->db->query($consulta, Array($id_usuario));
        return $query->row_array();
    }
    
    public function get_cotizador($id_departamento) {
        $consulta = "SELECT su.id_usuario, coalesce(su.nombre || ' ', '') || coalesce(su.paterno || ' ', '') || coalesce(su.materno, '') cotizador
            FROM seg_usuario su
            WHERE su.activo AND su.serie IS NOT NULL
            AND su.id_departamento = ?";
        $query = $this->db->query($consulta, Array($id_departamento));
        return $query->result_array();
    }
    
    public function get_mercado($id_departamento) {
        $consulta = "SELECT id_informador, descripcion, carga, recorrido_carga
            FROM seg_informador si
            WHERE id_boleta = 1 AND id_departamento = ?
            AND NOT EXISTS(SELECT * FROM seg_asignacion WHERE id_informador = si.id_informador
                AND gestion = DATE_PART('YEAR', now() - '14 HOUR'::Interval)::Int
                AND semana = DATE_PART('WEEK', now() - '14 HOUR'::Interval)::Int AND activo = 1)
            AND apiestado <> 'ANULADO'
            ORDER BY carga, recorrido_carga, id_informador";
        $query = $this->db->query($consulta, Array($id_departamento));
        return $query->result_array();
    }
    
    public function get_asignacion_mercados($id_departamento) {
        $consulta = "SELECT si.id_informador, coalesce(su.nombre || ' ', '') || coalesce(su.paterno || ' ', '') || coalesce(su.materno, '') cotizador, si.descripcion, exportado
            FROM seg_usuario su, seg_asignacion sa, seg_informador si
            WHERE su.id_usuario = sa.id_usuario AND sa.id_informador = si.id_informador 
            AND si.id_boleta = 1 AND sa.gestion = DATE_PART('YEAR', now() - '14 HOUR'::Interval)::Int 
            AND sa.semana = DATE_PART('WEEK', now() - '14 HOUR'::Interval)::Int 
            AND sa.activo = 1 AND si.id_departamento = ?
            ORDER BY su.id_usuario, si.id_informador";
        $query = $this->db->query($consulta, Array($id_departamento));
        return $query->result_array();
    }
    
    public function guardar_mercados($cot, $upms, $usuario) {
        $consulta = "SELECT af_asignar_mercados(DATE_PART('YEAR', now())::Int, DATE_PART('WEEK', now())::Int, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($cot, $upms, $usuario));
        return $query->row_array()['af_asignar_mercados'];
    }
    
    public function get_comercializadora($id_departamento) {
        $consulta = "SELECT id_informador, descripcion, carga, recorrido_carga
            FROM seg_informador si
            WHERE id_boleta = 2 AND id_departamento = ?
            AND NOT EXISTS(SELECT * FROM seg_asignacion WHERE id_informador = si.id_informador
                AND gestion = DATE_PART('YEAR', now())::Int AND mes = DATE_PART('MON', now())::Int
                AND activo = 1)
            AND apiestado <> 'ANULADO'
            ORDER BY carga, recorrido_carga, id_informador";
        $query = $this->db->query($consulta, Array($id_departamento));
        return $query->result_array();
    }
    
    public function get_asignacion_comercializadoras($id_departamento) {
        $consulta = "SELECT si.id_informador, coalesce(su.nombre || ' ', '') || coalesce(su.paterno || ' ', '') || coalesce(su.materno, '') cotizador, si.descripcion, exportado
            FROM seg_usuario su, seg_asignacion sa, seg_informador si
            WHERE su.id_usuario = sa.id_usuario AND sa.id_informador = si.id_informador 
            AND si.id_boleta = 2 AND sa.gestion = DATE_PART('YEAR', now())::Int 
            AND sa.mes = DATE_PART('MON', now())::Int
            AND sa.activo = 1 AND si.id_departamento = ?
            ORDER BY su.id_usuario, si.id_informador";
        $query = $this->db->query($consulta, Array($id_departamento));
        return $query->result_array();
    }
    
    public function guardar_comercializadoras($cot, $upms, $usuario) {
        $consulta = "SELECT af_asignar_comercializadoras(DATE_PART('YEAR', now())::Int, DATE_PART('MON', now())::Int, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($cot, $upms, $usuario));
        return $query->row_array()['af_asignar_comercializadoras'];
    }
    
    public function get_carga($id_departamento, $id_tipo) {
        $consulta = "SELECT carga
            FROM seg_informador
            WHERE apiestado <> 'ANULADO'
            AND id_departamento = ?
            AND id_boleta = ?
            GROUP BY carga
            ORDER BY carga";
        $query = $this->db->query($consulta, Array($id_departamento, $id_tipo));
        $res = $query->result_array();
        $carga = Array();
        foreach ($res as $row) {
            $carga[$row['carga']] = $row['carga'];
        }
        return $carga;
    }
    
    public function get_mercados_ord($id_departamento, $carga) {
        $consulta = "SELECT id_informador, descripcion, carga, recorrido_carga, direccion || coalesce(' # ' || numero, '') direccion, entre_calles
            FROM seg_informador
            WHERE id_boleta = 1 AND id_departamento = ?
            AND apiestado <> 'ANULADO' AND carga = ?
            ORDER BY carga, recorrido_carga, id_informador";
        $query = $this->db->query($consulta, Array($id_departamento, $carga));
        return $query->result_array();
    }
    
    public function get_comercializadoras_ord($id_departamento, $carga) {
        $consulta = "SELECT id_informador, descripcion, carga, recorrido_carga, direccion || coalesce(' # ' || numero, '') direccion, entre_calles
            FROM seg_informador
            WHERE id_boleta = 2 AND id_departamento = ?
            AND apiestado <> 'ANULADO' AND carga = ?
            ORDER BY carga, recorrido_carga, id_informador";
        $query = $this->db->query($consulta, Array($id_departamento, $carga));
        return $query->result_array();
    }
    
    public function subir($id) {
        $consulta = "SELECT af_subir_informante(?)";
        $query = $this->db->query($consulta, Array($id));
        return $query->row_array()['af_subir_informante'];
    }
    
    public function bajar($id) {
        $consulta = "SELECT af_bajar_informante(?)";
        $query = $this->db->query($consulta, Array($id));
        return $query->row_array()['af_bajar_informante'];
    }
    
    public function get_anios($gestion) {
        $consulta = "SELECT date_part('YEAR', generate_series)::Int anio
            FROM generate_series((? || '-01-01')::TimeStamp, now(), '1 YEAR'::Interval)";
        $query = $this->db->query($consulta, Array($gestion));
        return $query->result_array();
    }
    
    public function get_periodo($tipo, $gestion) {
        if ($tipo == 1) {
            $consulta = "SELECT date_part('WEEK', d)::Int id, lpad(date_part('WEEK', d)::Int::Text, 2, '0') || '. ' || to_char(d - '4 day'::Interval, 'dd TMMonth') || ' al ' || to_char(d + '2 day'::Interval, 'dd TMMonth') periodo
                FROM generate_series('2014-08-01', now() + '4 DAY'::Interval, '1 WEEK'::Interval) d
                WHERE date_part('YEAR', d) = ?";
            if ($gestion == 2015) {
                $consulta .= " UNION SELECT 53, '53. 28 Diciembre al 03 Enero' ORDER BY 1";
            }
        } else {
            $consulta = "SELECT distinct DATE_PART('MONTH', d)::Int id, TO_CHAR(d, 'TMMONTH') periodo
                FROM generate_series('2014-08-01', now(), '1 MONTH'::Interval) d
                WHERE date_part('YEAR', d) = ?
                ORDER BY id";
        }
        $query = $this->db->query($consulta, Array($gestion));
        return $query->result_array();
    }
    
    ///@brief Formatea los datos en JavaScript.
    ///@param table Tabla con los datos a graficar.
    ///@param cols Nombres de las columnas de la tabla.
    ///@return Vector JavaScript con los datos.
    public function get_jsdata($table) {
        if (count($table) > 0) {
            $keys = array_keys($table[0]);
            $js = '[';
            for ($i = 0; $i < count($table); $i++) {
                $js .= '{';
                for ($j = 0; $j < count($keys); $j++) {
                    $js .= '"'.$keys[$j].'": ';
                    $js .= '"'.$table[$i][$keys[$j]].'"';
                    if ($j < count($keys) - 1) {
                        $js .= ', ';
                    }
                }
                $js .= '}';
                if ($i < count($table) - 1) {
                    $js .= ', ';
                }
            }
            $js .= ']';
            return $js;
        } else {
            return '[]';
        }
    }
    
    ///@brief Formatea los graficos en JavaScript.
    ///@param table Tabla con los campos y etiquetas.
    ///@param type Tipo de gráfico.
    ///@param title Titulo del gráfico.
    ///@param balloonText Etiqueta del grafico.
    ///@param valueField Campo que referencia los datos del gráfico.
    ///@return Vector JavaScript con los Graphs.
    public function get_jsgraph($table, $type, $label, $title, $balloonText, $valueField, $second = false) {
        $js = '[';
        foreach ($table AS $r) {
            $js .= '{type: "'.$type.'",';
            $js .= 'fillAlphas: 0.7,';
            $js .= 'lineAlpha: 0.2,';
            $js .= 'labelText: "'.$label.'",';
            $js .= 'labelRotation: 45,';
            $js .= 'title: "'.$r[$title].'",';
            $js .= 'balloonText: "'.$balloonText.'",';
            $js .= 'valueField: "'.$r[$valueField].'"},';
            if ($second) {
                $js .= '{bullet: "round",';
                $js .= 'dashLengthField: "dashLengthLine",';
                $js .= 'lineThickness: 3,';
                $js .= 'bulletSize: 7,';
                $js .= 'bulletBorderAlpha: 1,';
                $js .= 'bulletColor: "#FFFFFF",';
                $js .= 'useLineColorForBulletBorder: true,';
                $js .= 'bulletBorderThickness: 3,';
                $js .= 'fillAlphas: 0,';
                $js .= 'lineAlpha: 1,';
                $js .= 'title: "'.$r[$title].'",';
                $js .= 'valueField: "'.$r[$valueField].'"},';
            }
        }
        $js .= ']';
        return $js;
    }
    
    ///@brief Porcentaje de asignación de cargas de trabajo por periodo.
    ///@param id_usuario Identificador del usuario.
    ///@param periodo (Año-Semana).
    ///@return El periodo en forma de numero decimal.
    public function get_avance_asignacion_mercados($id_usuario, $periodo) {
        $consulta = "SELECT d.descripcion departamento, ((COUNT(i.id_informador) - COUNT(a.id_asignacion))::Float / COUNT(i.id_informador) * 100)::Numeric(5, 2) pendiente, (COUNT(a.id_asignacion)::Float / COUNT(i.id_informador) * 100)::Numeric(5, 2) asignado
            FROM cat_departamento d
            INNER JOIN seg_informador i
            ON d.id_departamento = i.id_departamento
            INNER JOIN seg_usuario u
            ON (i.id_departamento = u.id_departamento OR u.nacional)
            AND u.id_usuario = ?
            LEFT JOIN seg_asignacion a 
            ON i.id_informador = a.id_informador 
            AND gestion || '-' || lpad(semana::Text, 2, '0') = ?
            WHERE i.id_boleta = 1
            AND i.apiestado <> 'ANULADO'
            GROUP BY d.id_departamento, d.descripcion
            ORDER BY d.id_departamento";
        $query = $this->db->query($consulta, Array((Int)$id_usuario, $periodo));
        $table = $this->get_jsdata($query->result_array());
        
        $res = Array();
        $res[0]['codigo'] = 'asignado';
        $res[0]['descripcion'] = 'Informantes asignados';
        $res[1]['codigo'] = 'pendiente';
        $res[1]['descripcion'] = 'Informantes no asignados';
        $label = $this->get_jsgraph($res, 'column', '[[value]]%', 'descripcion', '<b>[[departamento]]</b>', 'codigo');
        
        return [$table, $label];
    }
    
    ///@brief Porcentaje de asignación de cargas de trabajo por periodo.
    ///@param id_usuario Identificador del usuario.
    ///@param periodo (Año-Mes).
    ///@return El periodo en forma de numero decimal.
    public function get_avance_asignacion_comercializadoras($id_usuario, $periodo) {
        $consulta = "SELECT d.descripcion departamento, ((COUNT(i.id_informador) - COUNT(a.id_asignacion))::Float / COUNT(i.id_informador) * 100)::Numeric(5, 2) pendiente, (COUNT(a.id_asignacion)::Float / COUNT(i.id_informador) * 100)::Numeric(5, 2) asignado
            FROM cat_departamento d
            INNER JOIN seg_informador i
            ON d.id_departamento = i.id_departamento
            INNER JOIN seg_usuario u
            ON (i.id_departamento = u.id_departamento OR u.nacional)
            AND u.id_usuario = ?
            LEFT JOIN seg_asignacion a 
            ON i.id_informador = a.id_informador 
            AND gestion || '-' || lpad(mes::Text, 2, '0') = ?
            WHERE i.id_boleta = 2
            AND i.apiestado <> 'ANULADO'
            GROUP BY d.id_departamento, d.descripcion
            ORDER BY d.id_departamento";
        $query = $this->db->query($consulta, Array((Int)$id_usuario, $periodo));
        $table = $this->get_jsdata($query->result_array());
        
        $res = Array();
        $res[0]['codigo'] = 'asignado';
        $res[0]['descripcion'] = 'Informantes asignados';
        $res[1]['codigo'] = 'pendiente';
        $res[1]['descripcion'] = 'Informantes no asignados';
        $label = $this->get_jsgraph($res, 'column', '[[value]]%', 'descripcion', '<b>[[departamento]]</b>', 'codigo');
        
        return [$table, $label];
    }
    
    ///@brief Avance de consolidación mercados por periodo.
    ///@param id_usuario Identificador del usuario.
    ///@param Periodo (Año-Semana).
    ///@return El periodo en forma de numero decimal.
    public function get_avance_agricolas($id_usuario, $periodo) {
        $consulta = "SELECT d.id_departamento, d.descripcion departamento, u.login, (SUM(CASE i.apiestado WHEN 'PENDIENTE' THEN 1.0 ELSE 0.0 END) / COUNT(i.apiestado) * 100)::Numeric(5,2) pendiente, (SUM(CASE i.apiestado WHEN 'CONCLUIDO' THEN 1.0 ELSE 0.0 END) / COUNT(i.apiestado) * 100)::Numeric(5,2) concluido, (SUM(CASE i.apiestado WHEN 'INEXISTENTE' THEN 1.0 ELSE 0.0 END) / COUNT(i.apiestado) * 100)::Numeric(5,2) inexistente
            FROM cat_departamento d, seg_usuario u, enc_informante i, seg_asignacion a, (SELECT id_informador, gestion, mes, semana, max(version) AS version
                FROM seg_asignacion a
                WHERE gestion || '-' || lpad(semana::Text, 2, '0') = ?
                GROUP BY id_informador, gestion, mes, semana) a2
            WHERE d.id_departamento = u.id_departamento
            AND u.id_usuario = a.id_usuario
            AND i.id_asignacion = a.id_asignacion
            AND a.id_informador = a2.id_informador AND a.gestion = a2.gestion 
            AND a.semana = a2.semana AND a.version = a2.version
            AND EXISTS(SELECT id_usuario FROM seg_usuario WHERE (nacional OR id_departamento = d.id_departamento) AND id_usuario = ?)
            GROUP BY d.id_departamento, d.descripcion, u.login
            ORDER BY d.id_departamento, u.login";
        $query = $this->db->query($consulta, Array($periodo, (Int)$id_usuario));
        $table = $this->get_jsdata($query->result_array());
        
        $res = Array();
        $res[0]['codigo'] = 'concluido';
        $res[0]['descripcion'] = 'Datos cotizados';
        $res[1]['codigo'] = 'inexistente';
        $res[1]['descripcion'] = 'Datos que no pudieron cotizarse (Inexistencia)';
        $res[2]['codigo'] = 'pendiente';
        $res[2]['descripcion'] = 'Datos pendientes de cotizar';
        $label = $this->get_jsgraph($res, 'column', '[[value]]%', 'descripcion', '<b>[[login]]</b>', 'codigo');
        
        return [$table, $label];
    }
    
    ///@brief Avance de consolidación comercializadoras por periodo.
    ///@param id_usuario Identificador del usuario.
    ///@param gestion Periodo (Año).
    ///@param mes Periodo (Mes).
    ///@return El periodo en forma de numero decimal.
    public function get_avance_manufacturados($id_usuario, $periodo) {
        $consulta = "SELECT d.id_departamento, d.descripcion departamento, u.login, (SUM(CASE i.apiestado WHEN 'PENDIENTE' THEN 1.0 ELSE 0.0 END) / COUNT(i.apiestado) * 100)::Numeric(5,2) pendiente, (SUM(CASE i.apiestado WHEN 'CONCLUIDO' THEN 1.0 ELSE 0.0 END) / COUNT(i.apiestado) * 100)::Numeric(5,2) concluido, (SUM(CASE i.apiestado WHEN 'INEXISTENTE' THEN 1.0 ELSE 0.0 END) / COUNT(i.apiestado) * 100)::Numeric(5,2) inexistente
            FROM cat_departamento d, seg_usuario u, enc_informante i, seg_asignacion a, (SELECT id_informador, gestion, mes, semana, max(version) AS version
                FROM seg_asignacion a
                WHERE gestion || '-' || lpad(mes::Text, 2, '0') = ?
                GROUP BY id_informador, gestion, mes, semana) a2
            WHERE d.id_departamento = u.id_departamento
            AND u.id_usuario = a.id_usuario
            AND i.id_asignacion = a.id_asignacion
            AND a.id_informador = a2.id_informador AND a.gestion = a2.gestion 
            AND a.mes = a2.mes AND a.semana = 0 AND a.version = a2.version
            AND EXISTS(SELECT id_usuario FROM seg_usuario WHERE (nacional OR id_departamento = d.id_departamento) AND id_usuario = ?)
            GROUP BY d.id_departamento, d.descripcion, u.login
            ORDER BY d.id_departamento, u.login";
        $query = $this->db->query($consulta, Array($periodo, (Int)$id_usuario));
        $table = $this->get_jsdata($query->result_array());
        
        $res = Array();
        $res[0]['codigo'] = 'concluido';
        $res[0]['descripcion'] = 'Datos cotizados';
        $res[1]['codigo'] = 'inexistente';
        $res[1]['descripcion'] = 'Datos que no pudieron cotizarse (Inexistencia)';
        $res[2]['codigo'] = 'pendiente';
        $res[2]['descripcion'] = 'Datos pendientes de cotizar';
        $label = $this->get_jsgraph($res, 'column', '[[value]]%', 'descripcion', '<b>[[login]]</b>', 'codigo');
        
        return [$table, $label];
    }
}