<?php

/**
 * Description of Informador
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Informante extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Actualiza la información de informante.
    public function consolidar($informante) {
        $consulta = "SELECT f_consolidar_informante(?)";
        $query = $this->db->query($consulta, $informante);
        $res = $query->row_array();
        return $res['f_consolidar_informante'];
    }
    
    public function get_departamento($id_usuario) {
        $consulta = "SELECT d.id_departamento, d.descripcion
            FROM cat_departamento d, seg_usuario u
            WHERE (u.nacional OR d.id_departamento = u.id_departamento)
            AND u.id_usuario = ? 
            ORDER BY d.id_departamento";
        $query = $this->db->query($consulta, Array($id_usuario));
        $res = $query->result_array();
        $depto = Array();
        foreach ($res as $row) {
            $depto[$row['id_departamento']] = $row['descripcion'];
        }
        return $depto;
    }
    
    public function get_ciudad($id_usuario, $id_departamento) {
        $consulta = "SELECT c.id_ciudad, c.descripcion
            FROM cat_ciudad c, cat_departamento d, seg_usuario u
            WHERE c.id_departamento = d.id_departamento
            AND (u.nacional OR d.id_departamento = u.id_departamento)
            AND u.id_usuario = ? AND c.id_departamento = ?
            ORDER BY c.id_ciudad";
        $query = $this->db->query($consulta, Array($id_usuario, $id_departamento));
        $res = $query->result_array();
        $depto = Array();
        foreach ($res as $row) {
            $depto[$row['id_ciudad']] = $row['descripcion'];
        }
        return $depto;
    }
    
    ///@brief Selecciona los informantes.
    ///@return Matriz con los informantes.
    public function get_informantes($id_tipolistado, $id_departamento, $id_upm) {
        $consulta = "SELECT id_informador, carga, recorrido_carga, descripcion informante, direccion, entre_calles
            FROM seg_informador
            WHERE apiestado <> 'ANULADO' 
            AND id_boleta = ? AND id_departamento = ? AND id_informador <> ?
            ORDER BY carga, recorrido_carga";
        $query = $this->db->query($consulta, Array($id_tipolistado, $id_departamento, $id_upm));
        return $query->result_array();
    }
    
    ///@brief Selecciona el informante indicado.
    ///@return Vector con el informante.
    public function get_informador($id_informador) {
        $consulta = "SELECT *
            FROM seg_informador WHERE id_informador = ?";
        $query = $this->db->query($consulta, Array($id_informador));
        return $query->row_array();
    }
    
    ///@brief Selecciona los informantes.
    ///@return Vector con el informante.
    public function get_directorio($id_boleta, $id_usuario) {
        $consulta = "SELECT d.descripcion departamento, i.descripcion, i.zona, i.direccion || coalesce(' ' || numero, '') direccion, i.entre_calles
            FROM cat_departamento d, seg_informador i, seg_usuario u
            WHERE d.id_departamento = i.id_departamento
            AND i.apiestado <> 'ANULADO'
            AND i.id_informador IN(SELECT id_informador
            FROM seg_producto
            WHERE apiestado <> 'ANULADO'
            AND id_boleta = ?)
            AND u.id_usuario = ?
            AND (d.id_departamento = u.id_departamento OR u.nacional)
            ORDER BY d.id_departamento, i.descripcion";
        $query = $this->db->query($consulta, Array($id_boleta, $id_usuario));
        return $query->result_array();
    }
    
    ///@brief Selecciona el producto indicado.
    ///@return Vector con el informante.
    public function get_producto($id_producto)
    {
        $consulta = "SELECT *
            FROM seg_producto WHERE id_producto = ?";
        $query = $this->db->query($consulta, Array($id_producto));
        return $query->row_array();
    }
    
    ///@brief Selecciona los mercados con sus productos.
    ///@return Matriz con los mercados.
    public function get_mercados($id_usuario, $mercado = '%', $producto = '%') {
        $consulta = "SELECT i.id_informador, i.id_departamento, d.descripcion departamento, i.descripcion, i.carga
            FROM cat_departamento d, seg_informador i, seg_usuario u
            WHERE d.id_departamento = i.id_departamento AND i.id_boleta = 1 
            AND i.apiestado = 'ELABORADO' AND u.id_usuario = ? 
            AND (u.nacional OR d.id_departamento = u.id_departamento)
            AND i.descripcion ILIKE ? ";
        if ($producto != '%' && $producto != '%%') {
            $consulta .= "AND i.id_informador IN(
                SELECT id_informador FROM seg_producto WHERE producto || especificacion ILIKE ?) 
                ORDER BY i.id_departamento, i.carga, i.recorrido_carga";
            $query = $this->db->query($consulta, Array($id_usuario, $mercado, $producto));
        } else {
            $consulta .= "ORDER BY i.id_departamento, i.carga, i.recorrido_carga";
            $query = $this->db->query($consulta, Array($id_usuario, $mercado));
        }
        $res = $query->result_array();
        for ($i = 0; $i < count($res); $i++) {
            $subcon = "SELECT id_producto, codigo, producto, especificacion, cantidad_a_cotizar || ' ' || unidad_a_cotizar unidad, cantidad_equivalente || ' ' || unidad_convencional equivalencia, factor, unidad_final
                FROM seg_producto
                WHERE apiestado <> 'ANULADO'
                AND id_informador = ? AND especificacion ILIKE ?
                ORDER BY codigo, producto";
            $subquery = $this->db->query($subcon, Array($res[$i]['id_informador'], $producto));
            $res[$i]['productos'] = $subquery->result_array();
        }
        return $res;
    }
    
    ///@brief Inserta una solicitud de nuevo mercado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function insert_mercado($depto, $ciudad, $mercado, $zona, $usuario) {
        $consulta = "SELECT af_mercado_ins(?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($depto, $ciudad, $mercado, $zona, $usuario));
        return $query->row_array()['af_mercado_ins'];
    }
    
    ///@brief Inserta una solicitud de actualizacion de mercado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function update_mercado($id, $depto, $ciudad, $mercado, $zona, $justificacion, $usuario) {
        $consulta = "SELECT af_mercado_upd(?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $depto, $ciudad, $mercado, $zona, $justificacion, $usuario));
        return $query->row_array()['af_mercado_upd'];
    }
    
    ///@brief Inserta una solicitud para descartar mercado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function discard_mercado($id, $justificacion, $usuario) {
        $consulta = "SELECT af_mercado_dis(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $justificacion, $usuario));
        return $query->row_array()['af_mercado_dis'];
    }
    
    ///@brief Selecciona las comercializadoras con sus productos.
    ///@return Matriz con las comercializadoras.
    public function get_comercializadoras($id_usuario, $comercializadora = '%', $producto = '%') {
        $consulta = "SELECT i.id_departamento, i.id_informador, d.descripcion departamento, i.nit, i.regine, i.descripcion, i.nombre_informante, i.direccion || coalesce(' Nro. ' || i.numero, '') direccion, i.entre_calles, i.carga
            FROM cat_departamento d, seg_informador i, seg_usuario u
            WHERE d.id_departamento = i.id_departamento AND i.id_boleta = 2 
            AND i.apiestado = 'ELABORADO' AND u.id_usuario = ?
            AND (u.nacional OR d.id_departamento = u.id_departamento)
            AND i.descripcion ILIKE ? ";
        if ($producto != '%' && $producto != '%%') {
            $consulta .= "AND i.id_informador IN(
                SELECT id_informador FROM seg_producto WHERE producto || especificacion ILIKE ?) 
                ORDER BY i.id_departamento, i.carga, i.recorrido_carga";
            $query = $this->db->query($consulta, Array($id_usuario, $comercializadora, $producto));
        } else {
            $consulta .= "ORDER BY i.id_departamento, i.carga, i.recorrido_carga";
            $query = $this->db->query($consulta, Array($id_usuario, $comercializadora));
        }
        $res = $query->result_array();
        for ($i = 0; $i < count($res); $i++) {
            $subcon = "SELECT id_producto, codigo, producto, especificacion, unidad_talla_peso, marca, modelo, cantidad_a_cotizar || ' ' || unidad_a_cotizar unidad, cantidad_equivalente || ' ' || unidad_convencional equivalencia, envase, origen, factor, unidad_final
                FROM seg_producto
                WHERE apiestado <> 'ANULADO' AND id_informador = ?
                AND producto || especificacion ILIKE ?
                ORDER BY codigo, producto";
            $subquery = $this->db->query($subcon, Array($res[$i]['id_informador'], $producto));
            $res[$i]['productos'] = $subquery->result_array();
        }
        return $res;
    }
    
    ///@brief Inserta una solicitud de nueva comercializadora.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function insert_comercializadora($depto, $ciudad, $nit, $regine, $descripcion, $nombre_informante, $direccion, $numero, $entre_calles, $edificio, $piso, $oficina, $zona, $referencia, $telefono, $fax, $casilla, $e_mail, $pagina_web, $carga, $usuario) {
        $consulta = "SELECT af_comercializadora_ins(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($depto, $ciudad, ($nit == '')? null : $nit, ($regine == '')? null : $regine, $descripcion, $nombre_informante, $direccion, $numero, $entre_calles, $edificio, $piso, $oficina, $zona, $referencia, $telefono, $fax, $casilla, $e_mail, $pagina_web, $carga, $usuario));
        return $query->row_array()['af_comercializadora_ins'];
    }
    
    ///@brief Inserta una solicitud de actualizacion de comercializadora.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function update_comercializadora($id, $depto, $ciudad, $nit, $regine, $descripcion, $nombre_informante, $direccion, $numero, $entre_calles, $edificio, $piso, $oficina, $zona, $referencia, $telefono, $fax, $casilla, $e_mail, $pagina_web, $carga, $justificacion, $usuario) {
        $consulta = "SELECT af_comercializadora_upd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $depto, $ciudad, ($nit == '')? null : $nit, ($regine == '')? null : $regine, $descripcion, $nombre_informante, $direccion, $numero, $entre_calles, $edificio, $piso, $oficina, $zona, $referencia, $telefono, $fax, $casilla, $e_mail, $pagina_web, $carga, $justificacion, $usuario));
        return $query->row_array()['af_comercializadora_upd'];
    }
    
    ///@brief Inserta una solicitud para descartar comercializadora.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function discard_comercializadora($id, $justificacion, $usuario) {
        $consulta = "SELECT af_comercializadora_dis(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $justificacion, $usuario));
        return $query->row_array()['af_comercializadora_dis'];
    }
    
    public function get_punto_json($id)
    {
        $consulta = "SELECT id_informador, recorrido_carga, json
            FROM seg_informador 
            WHERE apiestado <> 'ANULADO' AND id_informador = ?";
        $query = $this->db->query($consulta, $id);
        return $query->result_array();
    }
    
    public function get_puntos_json($depto, $tipo, $carga, $id)
    {
        $consulta = "SELECT id_informador, recorrido_carga, json
            FROM seg_informador 
            WHERE apiestado <> 'ANULADO' AND id_departamento = ? 
            AND id_boleta = ? AND carga = ? AND id_informador <> ?";
        $query = $this->db->query($consulta, Array($depto, $tipo, $carga, $id));
        return $query->result_array();
    }
    
    public function guardar_punto($id, $json) {
        $consulta = "UPDATE seg_informador SET json = ? 
            WHERE id_informador = ?";
        $query = $this->db->query($consulta, Array($json, $id));
        return $this->db->affected_rows($query);
    }
    
    public function get_json($tipo, $anio, $periodo, $depto) {
        $consulta = "SELECT si.id_informador, e.usucre, '{\"type\":\"Feature\",\"geometry\":{\"type\":\"Point\",\"coordinates\":[' || avg(e.longitud) || ',' || avg(e.latitud) || ']},\"crs\":{\"type\":\"name\",\"properties\":{\"name\":\"urn:ogc:def:crs:OGC:1.3:CRS84\"}}}' json
            FROM enc_encuesta e, enc_informante i, seg_asignacion a, seg_informador si 
            WHERE e.id_asignacion = i.id_asignacion AND e.correlativo = i.correlativo 
            AND i.id_asignacion = a.id_asignacion AND a.id_informador = si.id_informador 
            AND si.id_boleta = ? AND e.latitud <> 0 
            AND a.gestion = ? AND ".($tipo == 1 ? 'semana' : 'mes')." = ? AND si.id_departamento = ? 
            GROUP BY si.id_informador, e.usucre";
        $query = $this->db->query($consulta, Array($tipo, $anio, $periodo, $depto));
        return $query->result_array();
    }
}