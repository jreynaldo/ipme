<?php

/**
 * Description of Encuesta
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Encuesta extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Actualiza la información de la tabla encuesta.
    public function consolidar($encuesta)
    {
        $consulta = "SELECT f_consolidar_encuesta(?)";
        $query = $this->db->query($consulta, $encuesta);
        $res = $query->row_array();
        return $res['f_consolidar_encuesta'];
    }
}
