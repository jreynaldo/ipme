<?php

/**
 * Description of Periodo
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Periodo extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function get_variable($variable) {
        $consulta = "SELECT valor FROM variable WHERE var = ?";
        $query = $this->db->query($consulta, Array($variable));
        return $query->row_array()['valor'];
    }
    
    public function get_anios($gestion) {
        $consulta = "SELECT date_part('YEAR', generate_series)::Int anio
            FROM generate_series((? || '-01-01')::TimeStamp, now(), '1 YEAR'::Interval)";
        $query = $this->db->query($consulta, Array($gestion));
        return $query->result_array();
    }
    
    public function get_meses($gestion) {
        /*$consulta = "SELECT date_part('MONTH', generate_series)::Int id, to_char(generate_series, 'TMMONTH') mes
            FROM generate_series('2014-07-04'::TimeStamp, now(), '1 MONTH'::interval)
            WHERE date_part('YEAR', generate_series)::Int = ?";*/
        $consulta = "SELECT date_part('MONTH', generate_series)::Int id, to_char(generate_series, 'TMMONTH') mes
            FROM generate_series('2014-08-01'::TimeStamp, now(), '1 MONTH'::interval)
            WHERE date_part('YEAR', generate_series)::Int = ?";
        $query = $this->db->query($consulta, Array($gestion));
        return $query->result_array();
    }
    
    public function get_semanas($gestion) {
        /*$consulta = "SELECT date_part('WEEK', generate_series)::Int sem, 
                lpad(date_part('WEEK', generate_series)::Int::Text, 2, '0') || '. ' || to_char(generate_series - '4 day'::Interval, 'dd TMMonth') || ' al ' || to_char(generate_series + '2 day'::Interval, 'dd TMMonth') semana
            FROM generate_series('2014-07-04'::timestamp, now(), '1 week'::interval)
            WHERE date_part('YEAR', generate_series)::Int = ?";*/
        $consulta = "SELECT date_part('WEEK', generate_series)::Int sem, 
                lpad(date_part('WEEK', generate_series)::Int::Text, 2, '0') || '. ' || to_char(generate_series - '4 day'::Interval, 'dd TMMonth') || ' al ' || to_char(generate_series + '2 day'::Interval, 'dd TMMonth') semana
            FROM generate_series('2014-08-01'::timestamp, now(), '1 week'::interval)
            WHERE date_part('YEAR', generate_series)::Int = ?";
        $query = $this->db->query($consulta, Array($gestion));
        return $query->result_array();
    }
    
    public function get_periodo($tipo, $gestion) {
        $consulta = "SELECT distinct ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::Int id, 
            CASE ?::Int WHEN 1 THEN TO_CHAR(generate_series, 'TMMONTH') ELSE
                CASE ceiling(DATE_PART('MONTH', generate_series)::Int / ?::Real)::Int
                WHEN 1 THEN '1er Periodo'
                WHEN 2 THEN '2do Periodo'
                WHEN 3 THEN '3er Periodo'
                WHEN 4 THEN '4to Periodo'
                WHEN 5 THEN '5to Periodo'
                WHEN 6 THEN '6to Periodo' END
            END periodo
        FROM generate_series('2014-08-01', now(), '1 MONTH'::Interval)
        WHERE DATE_PART('YEAR', generate_series)::Int = ?
        ORDER BY id";
        $query = $this->db->query($consulta, Array($tipo, $tipo, $tipo, $gestion));
        return $query->result_array();
    }
    
    ///@brief Ultimo periodo mercados
    ///@param id_usuario Identificador del usuario.
    ///@param id_boleta Identificador de la boleta.
    ///@return El periodo en forma de numero decimal.
    public function get_ultimo_periodo($id_usuario, $id_boleta, $anterior)
    {
        if ($id_boleta == 1) {
            $consulta = "SELECT gestion || '-' || lpad(semana::Text, 2, '0') periodo
                FROM seg_asignacion a, seg_informador i, seg_usuario u
                WHERE a.id_informador = i.id_informador AND i.id_boleta = 1
                AND (i.id_departamento = u.id_departamento OR u.nacional)
                AND u.id_usuario = ?
                GROUP BY gestion, semana
                ORDER BY gestion DESC, semana DESC
                LIMIT 1 OFFSET ?";
        } else {
            $consulta = "SELECT gestion || '-' || lpad(mes::Text, 2, '0') periodo
                FROM seg_asignacion a, seg_informador i, seg_usuario u
                WHERE a.id_informador = i.id_informador AND i.id_boleta = 2
                AND (i.id_departamento = u.id_departamento OR u.nacional)
                AND u.id_usuario = ?
                GROUP BY gestion, mes
                ORDER BY gestion DESC, mes DESC
                LIMIT 1 OFFSET ?";
        }
        $query = $this->db->query($consulta, Array($id_usuario, $anterior));
        return $query->row_array()['periodo'];
    }
}
