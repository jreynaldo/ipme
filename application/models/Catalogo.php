<?php

/**
 * Description of Catalogo
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Catalogo extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Selecciona las etiquetas de productos agricolas.
    ///@return Matriz con el clasificador.
    public function get_agricola()
    {
        $consulta = "SELECT id_agricola, descripcion
            FROM cat_agricola
            ORDER BY descripcion";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
    
    ///@brief Selecciona el clasificador.
    ///@return Matriz con el clasificador.
    public function get_clasificador()
    {
        $consulta = "SELECT *
            FROM cat_clasificador
            ORDER BY 1";
        $query = $this->db->query($consulta);
        return $query->result_array();
    }
}
