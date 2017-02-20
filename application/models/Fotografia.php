<?php

/**
 * Description of Fotografia
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Fotografia extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Recupera imagen1 del producto.
    ///@return Imagen.
    public function get_imagen1($id) {
        $consulta = "SELECT encode(img1, 'base64') img
            FROM archivos.seg_fotografia WHERE id_fotografia = ?";
        $query = $this->db->query($consulta, Array($id));
        return $query->row_array()['img'];
    }
    
    ///@brief Recupera imagen2 del producto.
    ///@return Imagen.
    public function get_imagen2($id) {
        $consulta = "SELECT encode(img2, 'base64') img
            FROM archivos.seg_fotografia WHERE id_fotografia = ?";
        $query = $this->db->query($consulta, Array($id));
        return $query->row_array()['img'];
    }
    
    ///@brief Selecciona el informante indicado.
    ///@return Vector con el informante.
    public function imagenes($id, $img1, $img2) {
        $consulta = "SELECT af_imagen_upd(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $img1, $img2));
        return $query->row_array()['af_imagen_upd'];
    }
    
    ///@brief Recupera imagen1 temporal del producto.
    ///@return Imagen.
    public function get_temp1($id) {
        $consulta = "SELECT encode(img1, 'base64') img
            FROM archivos.seg_temp WHERE id_temp = ?";
        $query = $this->db->query($consulta, Array($id));
        return $query->row_array()['img'];
    }
    
    ///@brief Recupera imagen2 tempporal del producto.
    ///@return Imagen.
    public function get_temp2($id) {
        $consulta = "SELECT encode(img2, 'base64') img
            FROM archivos.seg_temp WHERE id_temp = ?";
        $query = $this->db->query($consulta, Array($id));
        return $query->row_array()['img'];
    }
    
    ///@brief Selecciona el informante indicado.
    ///@return Vector con el informante.
    public function temp($id, $img1, $img2) {
        $consulta = "SELECT af_temp_upd(?, ?, ?)";
        $query = $this->db->query($consulta, Array($id, $img1, $img2));
        return $query->row_array()['af_temp_upd'];
    }
    
    ///@brief Recupera imagen del justificativo.
    ///@return Imagen.
    public function get_justificativo($gestion, $semana, $depto, $origen, $codigo) {
        $consulta = "SELECT encode(img, 'base64') img
            FROM archivos.seg_justificativo 
            WHERE gestion = ?
            AND semana = ?
            AND id_departamento = ?
            AND origen = ?
            AND codigo = ?";
        $query = $this->db->query($consulta, Array($gestion, $semana, $depto, $origen, $codigo));
        return $query->row_array()['img'];
    }
    
    ///@brief Guarda la imagen del justificativo.
    ///@return Ok o error.
    public function justificativo($gestion, $mes, $semana, $depto, $origen, $codigo, $img1) {
        $consulta = "SELECT af_justificativo_upd(?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($gestion, $mes, $semana, $depto, $origen, $codigo, $img1));
        return $query->row_array()['af_justificativo_upd'];
    }
}