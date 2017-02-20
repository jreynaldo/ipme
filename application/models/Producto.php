<?php

/**
 * Description of Producto
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Producto extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Selecciona el producto indicado.
    ///@return Vector con el producto.
    public function get_producto($id_producto) {
        $consulta = "SELECT *
            FROM seg_producto WHERE id_producto = ?";
        $query = $this->db->query($consulta, Array($id_producto));
        return $query->row_array();
    }
    
    ///@brief Selecciona la solicitud del producto indicado.
    ///@return Vector con el producto.
    public function get_producto_sol($id_producto_sol) {
        $consulta = "SELECT *
            FROM sol_producto WHERE id_producto_sol = ?";
        $query = $this->db->query($consulta, Array($id_producto_sol));
        return $query->row_array();
    }

    ///@brief Inserta una solicitud de nuevo producto agricola.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function insert_prod_agri($id_informador, $codigo, $producto, $especificacion, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $origen, $usuario) {
        $consulta = "SELECT af_prod_agri_ins(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id_informador, $codigo, $producto, $especificacion, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $origen, $usuario));
        return $query->row_array()['af_prod_agri_ins'];
    }
    
    ///@brief Inserta una solicitud de actualizacion de producto agricola.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function update_prod_agri($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $origen, $justificacion, $usuario) {
        $consulta = "SELECT af_prod_agri_upd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $origen, $justificacion, $usuario));
        return $query->row_array()['af_prod_agri_upd'];
    }
    
    ///@brief Inserta una solicitud para descartar producto agricola.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function discard_prod_agri($id, $justificacion, $usuario) {
        $consulta = "SELECT af_prod_agri_dis(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $justificacion, $usuario));
        return $query->row_array()['af_prod_agri_dis'];
    }
    
    ///@brief Inserta una solicitud para fusionar producto agricola.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function fusion_prod_agri($id, $id_upm, $justificacion, $usuario) {
        $consulta = "SELECT af_prod_agri_fus(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $id_upm, $justificacion, $usuario));
        return $query->row_array()['af_prod_agri_fus'];
    }
    
    ///@brief Selecciona las solicitudes.
    ///@return Matriz con las solicitudes.
    public function get_sol_agircolas($usuario = '%', $estado = 'SOLICITADO') {
        $consulta = "SELECT p.id_producto_sol, d.descripcion departamento, i.descripcion, p.codigo, p.producto, p.especificacion, p.cantidad_inicial || ' ' || p.unidad_a_cotizar unidad, p.cantidad_equivalente || ' ' || p.unidad_convencional equivalencia, accion, p.justificacion, p.comentario
            FROM cat_departamento d, seg_informador i, sol_producto p
            WHERE d.id_departamento = i.id_departamento
            AND i.id_informador = p.id_informador
            AND p.id_boleta = 1
            AND p.apiestado = ?
            AND p.usucre LIKE ?";
        $query = $this->db->query($consulta, Array($estado, $usuario));
        return $query->result_array();
    }
    
    ///@brief Selecciona los productos agricolas.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_producto_agricola() {
        $consulta = "SELECT codigo, descripcion FROM af_producto_agricola()";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Inserta una solicitud de nuevo producto manufacturado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function insert_prod_man($codigo, $producto, $especificacion, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $id_upm, $usuario) {
        $consulta = "SELECT af_prod_man_ins(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($codigo, $producto, $especificacion, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $id_upm, $usuario));
        return $query->row_array()['af_prod_man_ins'];
    }
    
    ///@brief Inserta una solicitud de actualizacion de producto manufacturado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function update_prod_man($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $justificacion, $usuario) {
        $consulta = "SELECT af_prod_man_upd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array((Integer)$id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $justificacion, $usuario));
        return $query->row_array()['af_prod_man_upd'];
    }
    
    ///@brief Inserta una solicitud para descartar producto manufacturado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function discard_prod_man($id, $justificacion, $usuario) {
        $consulta = "SELECT af_prod_man_dis(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $justificacion, $usuario));
        return $query->row_array()['af_prod_man_dis'];
    }
    
    ///@brief Inserta una solicitud para fusionar producto manufacturado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function fusion_prod_man($id, $id_upm, $justificacion, $usuario) {
        $consulta = "SELECT af_prod_man_fus(?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $id_upm, $justificacion, $usuario));
        return $query->row_array()['af_prod_man_fus'];
    }
    
    ///@brief Edita una solicitud de actualizacion de producto manufacturado.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function update_sol_man($id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $justificacion, $usuario) {
        $consulta = "SELECT af_sol_man_upd(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array((Integer)$id, $codigo, $producto, $especificacion, $factor_ajuste, $cantidad_a_cotizar, $unidad_a_cotizar, $cantidad_equivalente, $unidad_convencional, $unidad_talla_peso, $marca, $modelo, $envase, $origen, $procedencia, $justificacion, $usuario));
        return $query->row_array()['af_sol_man_upd'];
    }
    
    ///@brief Selecciona las solicitudes.
    ///@return Matriz con las solicitudes.
    public function get_sol_manufacturados($usuario = '%', $estado = 'SOLICITADO') {
        $consulta = "SELECT p.id_producto_sol, d.descripcion departamento, i.descripcion, p.codigo, p.producto, p.especificacion, p.marca, p.modelo, p.cantidad_inicial || ' ' || p.unidad_a_cotizar unidad, p.cantidad_equivalente || ' ' || p.unidad_convencional equivalencia, accion, p.justificacion, p.comentario
            FROM cat_departamento d, seg_informador i, sol_producto p
            WHERE d.id_departamento = i.id_departamento
            AND i.id_informador = p.id_informador
            AND p.id_boleta = 2
            AND p.apiestado = ?
            AND p.usucre LIKE ?
            ORDER BY d.id_departamento, p.accion, p.codigo, p.id_producto_sol";
        $query = $this->db->query($consulta, Array($estado, $usuario));
        return $query->result_array();
    }
    
    ///@brief Selecciona los productos manufacturados.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_producto_manufacturado() {
        $consulta = "SELECT codigo, descripcion FROM af_producto_manufacturado()";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los productos manufacturados.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_producto_indice() {
        $consulta = "SELECT codigo, descripcion FROM af_producto_indice()";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona los valores pendientes de codificación ordenados por el número de ocurrencias.
    ///@return Matriz con las variables pendientes de codificación.
    public function get_productos($sector, $clasificacion) {
        $consulta = "SELECT codigo, descripcion FROM af_producto(?, ?)";
        $query = $this->db->query($consulta, Array($sector, $clasificacion));
        return $query->result_array();
    }
    
    ///@brief Selecciona la fecha y hora de cotización de los productos agricolas ordenados por la fecha y hora.
    ///@return Matriz con las fechas y horas de los productos cotizados en el periodo selecionado.
    public function agricola($gestion, $semana, $departamento, $usuario) {
        $consulta = "SELECT e.feccre, e.fecmod, sp.producto, e.latitud, e.longitud
            FROM seg_informador si, seg_asignacion a, enc_informante i, enc_encuesta e, seg_producto sp
            WHERE si.id_informador = a.id_informador AND a.id_asignacion = i.id_asignacion 
            AND i.id_asignacion = e.id_asignacion AND i.correlativo = e.correlativo
            AND i.id = sp.id_producto AND e.id_pregunta = 6 
            AND sp.id_boleta = 1 AND gestion = ? AND semana = ? 
            AND si.id_departamento = ? AND e.usucre = ? 
            ORDER BY feccre";
        $query = $this->db->query($consulta, Array($gestion, $semana, $departamento, $usuario));
        return $query->result_array();
    }
    
    ///@brief Selecciona la fecha y hora de cotización de los productos manufacturados ordenados por la fecha y hora.
    ///@return Matriz con las fechas y horas de los productos cotizados en el periodo selecionado.
    public function manufacturado($gestion, $mes, $departamento, $usuario) {
        $consulta = "SELECT e.feccre, e.fecmod, sp.producto, e.latitud, e.longitud
            FROM seg_informador si, seg_asignacion a, enc_informante i, enc_encuesta e, seg_producto sp
            WHERE si.id_informador = a.id_informador AND a.id_asignacion = i.id_asignacion 
            AND i.id_asignacion = e.id_asignacion AND i.correlativo = e.correlativo
            AND i.id = sp.id_producto AND e.id_pregunta = 21 
            AND sp.id_boleta = 2 AND gestion = ? AND mes = ? 
            AND si.id_departamento = ? AND e.usucre = ? 
            ORDER BY feccre";
        $query = $this->db->query($consulta, Array($gestion, $mes, $departamento, $usuario));
        return $query->result_array();
    }
    
    public function get_productos_pdf($tipo, $depto) {
        $consulta = "SELECT d.descripcion departamento, p.id_producto, p.codigo, p.producto, p.especificacion, p.unidad_talla_peso, p.marca, p.modelo, p.cantidad_a_cotizar || ' ' || p.unidad_a_cotizar cantidad_a_cotizar, p.cantidad_equivalente || ' ' || p.unidad_convencional equivalencia, p.envase, p.origen, p.procedencia, i.descripcion, encode(img.img1, 'base64') img1, encode(img.img2, 'base64') img2
            FROM cat_departamento d, seg_informador i, seg_producto p, archivos.seg_fotografia img
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador AND p.id_producto = img.id_fotografia
            AND p.apiestado <> 'ANULADO'
            AND p.id_boleta = ?
            AND i.id_departamento = ?";
        $query = $this->db->query($consulta, Array($tipo, $depto));
        return $query->result_array();
    }
    
    ///@brief Seleccion la solicitud del producto especificado.
    ///@return Matriz con los valores de la solicitud y del producto.
    public function get_solicitud($id) {
        $consulta = "SELECT 1, p.id_boleta, p.id_producto, p.id_producto_sol, d.descripcion departamento, i.descripcion, p.codigo, p.producto, p.especificacion, p.cantidad_inicial || ' ' || p.unidad_inicial base, p.factor_ajuste, p.cantidad_a_cotizar || ' ' || p.unidad_a_cotizar unidad, p.cantidad_equivalente || ' ' || p.unidad_convencional equivalencia, p.factor, p.unidad_final, p.unidad_talla_peso, p.marca, p.modelo, p.envase, p.origen, p.procedencia
            FROM cat_departamento d, seg_informador i, sol_producto p
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador
            AND p.id_producto_sol = ?
            UNION
            SELECT 2, p.id_boleta, p.id_producto, s.id_producto_sol, d.descripcion departamento, i.descripcion, p.codigo, p.producto, p.especificacion, p.cantidad_inicial || ' ' || p.unidad_inicial, p.factor_ajuste, p.cantidad_a_cotizar || ' ' || p.unidad_a_cotizar, p.cantidad_equivalente || ' ' || p.unidad_convencional, p.factor, p.unidad_final, p.unidad_talla_peso, p.marca, p.modelo, p.envase, p.origen, p.procedencia
            FROM cat_departamento d, seg_informador i, seg_producto p, sol_producto s
            WHERE d.id_departamento = i.id_departamento AND i.id_informador = p.id_informador
            AND s.id_producto = p.id_producto AND s.id_producto_sol = ?
            ORDER BY 1";
        $query = $this->db->query($consulta, Array($id, $id));
        return $query->result_array();
    }
    
    ///@brief Aprueba la solicitud especificada.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function aprobar_prod($id, $usuario) {
        $consulta = "SELECT af_aprobar_prod(?, ?)";
        $query = $this->db->query($consulta, Array($id, $usuario));
        return $query->row_array()['af_aprobar_prod'];
    }
    
    ///@brief Rechaza la solicitud especificada.
    ///@return Ok en caso de registro correcto o caso contrario el error.
    public function rechazar_prod($id, $comentario, $usuario) {
        $consulta = "SELECT af_rechazar_prod(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $comentario, $usuario));
        return $query->row_array()['af_rechazar_prod'];
    }
    
    public function cambios_pendientes($id_usuario, $informante = false) {
        $consulta = "SELECT f_ultimo_periodo(2, id_departamento)
            FROM seg_usuario
            WHERE id_usuario = ?";
        $query = $this->db->query($consulta, Array($id_usuario));
        $per = $query->row_array()['f_ultimo_periodo'];
        
        $consulta = "SELECT i.id, i.cod, i.codigo, i.descripcion, a.gestion, a.mes, 'Especificacion' cambio, d.descripcion departamento
            FROM enc_informante i, seg_asignacion a, seg_informador si, seg_usuario u, cat_departamento d
            WHERE i.id_asignacion = a.id_asignacion
            AND a.id_informador = si.id_informador
            AND si.id_departamento = d.id_departamento
            AND si.id_departamento = u.id_departamento
            AND u.id_usuario = ?
            AND gestion || '-' || lpad(mes::Text, 2, '0') || '-' || lpad(semana::Text, 2, '0') = ?
            AND exportado
            AND cod = 20
            AND i.id NOT IN(SELECT id_producto
            FROM sol_producto
            WHERE to_char(feccre, 'yyyy-MM-00') = ?
            AND accion = 'EDITAR'
            AND apiestado = 'APROBADO')";
        if ($informante) {
            $consulta .= " UNION
            SELECT i.id, i.cod, i.codigo, i.descripcion, a.gestion, a.mes, 'Informante' cambio, d.descripcion departamento
            FROM enc_informante i, seg_asignacion a, seg_informador si, seg_usuario u, cat_departamento d
            WHERE i.id_asignacion = a.id_asignacion
            AND a.id_informador = si.id_informador
            AND si.id_departamento = d.id_departamento
            AND si.id_departamento = u.id_departamento
            AND u.id_usuario = ?
            AND gestion || '-' || lpad(mes::Text, 2, '0') || '-' || lpad(semana::Text, 2, '0') = ?
            AND exportado
            AND cod = 21
            AND i.id NOT IN(SELECT id_producto
            FROM sol_producto
            WHERE to_char(feccre, 'yyyy-MM-00') = ?
            AND accion = 'FUSIONAR'
            AND apiestado = 'APROBADO')";
            $query = $this->db->query($consulta, Array($id_usuario, $per, $per, $id_usuario, $per, $per));
        } else {
            $query = $this->db->query($consulta, Array($id_usuario, $per, $per));
        }
        return $query->result_array();
    }
    
    public function cambios_pendientes_nacional() {
        $consulta = "SELECT i.id, i.cod, i.codigo, i.descripcion, a.gestion, a.mes, 'Especificación' cambio, d.descripcion departamento
            FROM enc_informante i, seg_asignacion a, seg_informador si, cat_departamento d
            WHERE i.id_asignacion = a.id_asignacion
            AND a.id_informador = si.id_informador
            AND si.id_departamento = d.id_departamento
            AND gestion || '-' || lpad(mes::Text, 2, '0') || '-' || lpad(semana::Text, 2, '0') = f_ultimo_periodo(2, d.id_departamento)
            AND exportado
            AND cod = 20
            AND NOT EXISTS(SELECT id_producto_sol
            FROM sol_producto
            WHERE id_producto = i.id
            AND to_char(feccre, 'yyyy-MM-00') = a.gestion || '-' || lpad(a.mes::Text, 2, '0') || '-' || lpad(a.semana::Text, 2, '0')
            AND accion = 'EDITAR'
            AND apiestado = 'APROBADO')
            UNION
            SELECT i.id, i.cod, i.codigo, i.descripcion, a.gestion, a.mes, 'Informante' cambio, d.descripcion departamento
            FROM enc_informante i, seg_asignacion a, seg_informador si, cat_departamento d
            WHERE i.id_asignacion = a.id_asignacion
            AND a.id_informador = si.id_informador
            AND si.id_departamento = d.id_departamento
            AND gestion || '-' || lpad(mes::Text, 2, '0') || '-' || lpad(semana::Text, 2, '0') = f_ultimo_periodo(2, d.id_departamento)
            AND exportado
            AND cod = 21
            AND NOT EXISTS(SELECT id_producto_sol
            FROM sol_producto
            WHERE id_producto = i.id
            AND to_char(feccre, 'yyyy-MM-00') = a.gestion || '-' || lpad(a.mes::Text, 2, '0') || '-' || lpad(a.semana::Text, 2, '0')
            AND accion = 'FUSIONAR'
            AND apiestado = 'APROBADO')";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    public function cotizacion($id_producto) {
        $consulta = "SELECT replace(p.pregunta, '[DESCRIPCION]', sp.producto) pregunta, e.respuesta
            FROM enc_pregunta p, enc_encuesta e, enc_informante i, seg_asignacion a, seg_producto sp
            WHERE p.id_pregunta = e.id_pregunta
            AND e.id_asignacion = i.id_asignacion
            AND e.correlativo = i.correlativo
            AND i.id_asignacion = a.id_asignacion
            AND i.id = sp.id_producto
            AND gestion || '-' || lpad(mes::Text, 2, '0') || '-00' = f_ultimo_periodo(2)
            AND e.id_last > 0 AND i.id = ?
            ORDER BY p.codigo_pregunta";
        $query = $this->db->query($consulta, Array($id_producto));
        return $query->result_array();
    }
}