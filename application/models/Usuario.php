<?php

class Usuario extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function login($login, $password) {
        $consulta = "SELECT id_usuario, activo, login, nombre, paterno, nacional, id_departamento, id_grupo, id_proyecto
            FROM seg_usuario
            WHERE login = ?
            AND password = md5(?)
            AND activo";
        $query = $this->db->query($consulta, Array($login, $password));
        return $query->row_array();
    }
    
    public function get_exists($login) {
        $consulta = "SELECT id_usuario
            FROM seg_usuario
            WHERE activo AND login = ?";
        $query = $this->db->query($consulta, $login);
        return $query->row_array()['id_usuario'];
    }
    
    ///@brief Verifica el registro del movil.
    ///@return Vector con el identificador del proyecto.
    public function get_proyecto($usuario)
    {
        $consulta = "SELECT p.id_proyecto, p.version_boleta, u.id_grupo
            FROM seg_proyecto p, seg_usuario u
            WHERE p.id_proyecto = u.id_proyecto
            AND u.login = ?";
        $query = $this->db->query($consulta, Array($usuario));
        
        return $query->row_array();
    }
    
    public function get_values($id_usuario) {
        $consulta = "SELECT id_departamento, DATE_PART('WEEK', now())::Int week, DATE_PART('MONTH', now())::Int mes, DATE_PART('YEAR', now())::Int anio
            FROM seg_usuario
            WHERE id_usuario = ?";
        $query = $this->db->query($consulta, Array($id_usuario));
        return $query->row_array();
    }
    
    public function get_cotizador($id_departamento) {
        $consulta = "SELECT login
            FROM seg_usuario u
            WHERE id_departamento = ?
            AND activo AND id_grupo = 4";
        $query = $this->db->query($consulta, Array($id_departamento));
        return $query->result_array();
    }
    
    public function permisos($login) {
        $consulta = "SELECT descripcion
            FROM seg_permiso p, seg_usuario u
            WHERE p.id_grupo = u.id_grupo
            AND u.login = ?";
        $query = $this->db->query($consulta, Array($login));
        foreach ($query->result_array() as $r) {
            $permisos[] = $r['descripcion'];
        }
        return $permisos;
    }
    
    public function cambiar($login, $pass, $passn) {
        $consulta = "SELECT f_cambiar(?, ?, ?)";
        $query = $this->db->query($consulta, Array($login, $pass, $passn));
        $res = $query->row_array();
        return $res['f_cambiar'];
    }
    
    public function crear($login, $pass, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $departamento, $serie) {
        $consulta = "SELECT f_crear_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $query = $this->db->query($consulta, Array($login, $pass, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $departamento, $serie));
        $res = $query->row_array();
        return $res['f_crear_usuario'];
    }
    
    public function get_usuarios($id_usuario ,$estado,$dep,$s_login,$s_nombre,$s_apellido_p,$s_apellido_m) {
        $consulta = "SELECT g.id_departamento, g.login,g.id_usuario,g.activo, g.nombre, g.paterno, g.materno,g.descripcion,gp.descripcion grupo
FROM (SELECT u.id_usuario, u.login, u.activo, u.nombre, u.paterno, u.materno,d.id_departamento,d.descripcion ,u.id_grupo
FROM seg_usuario u, seg_usuario a, seg_grupo g,cat_departamento d 
WHERE (u.id_departamento = a.id_departamento and u.id_departamento=d.id_departamento OR a.nacional) 
AND a.id_usuario = ? 
AND u.activo=coalesce(?,true) 
AND a.id_grupo = g.id_grupo 
AND u.id_grupo = ANY(g.grupo) 
ORDER BY d.id_departamento,login) g , seg_usuario us , seg_grupo gp
where g.id_usuario=us.id_usuario 
     AND us.id_grupo=gp.id_grupo
      AND g.id_departamento=us.id_departamento 
      AND g.id_departamento=coalesce(?,g.id_departamento) 
      AND  UPPER(g.login) LIKE  UPPER(coalesce(?,g.login))
      AND UPPER(g.nombre) like UPPER(coalesce(?,g.nombre))
       AND UPPER(g.paterno) like UPPER(coalesce(?,g.paterno))
       AND UPPER(g.materno) like UPPER(coalesce(?,g.materno)) 
       ORDER BY 1,2";
        $query = $this->db->query($consulta, Array($id_usuario ,$estado,$dep==0 ? NULL : $dep,$s_login."%",$s_nombre."%",$s_apellido_p."%",$s_apellido_m."%"));
  
        return $query->result_array();
    }
    
    public function get_grupos($id_usuario) {
        $consulta = "SELECT g.id_grupo, g.descripcion
            FROM seg_grupo g, seg_usuario u, seg_grupo ug
            WHERE g.id_grupo = ANY(ug.grupo)
            AND u.id_grupo = ug.id_grupo
            AND u.id_usuario = ?
            ORDER BY g.id_grupo";
        $query = $this->db->query($consulta, Array($id_usuario));
        $res = $query->result_array();
        $grupo = Array();
        foreach ($res as $row) {
            $grupo[$row['id_grupo']] = $row['descripcion'];
        }
        return $grupo;
    }
    
    public function get_usuario($id_usuario) {
        $consulta = "SELECT id_usuario, login, activo, carnet, nombre, paterno, materno, direccion, telefono, id_departamento, id_grupo, id_proyecto, serie
            FROM seg_usuario
            WHERE id_usuario = ?";
        $query = $this->db->query($consulta, Array($id_usuario));
        return $query->row_array();
    }
    
    public function insert_usuario($login, $activo, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $id_departamento, $id_grupo, $id_proyecto, $serie, $usucre) {
        $consulta = "SELECT f_insert_usuario(?::VarChar, ?::Boolean, ?::VarChar, ?::VarChar, ?::VarChar, ?::VarChar, ?::VarChar, ?::VarChar, ?::Int, ?::Int, ?::Int, ?::VarChar, ?::VarChar)";
        $query = $this->db->query($consulta, Array($login, $activo, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $id_departamento, $id_grupo, $id_proyecto, $serie, $usucre));
        return $query->row_array()['f_insert_usuario'];
    }
    
    public function update_usuario($id_usuario, $login, $activo, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $id_departamento, $id_grupo, $id_proyecto, $serie, $usucre) {
        $consulta = "SELECT f_update_usuario(?::Int, ?::VarChar, ?::Boolean, ?::VarChar, ?::VarChar, ?::VarChar, ?::VarChar, ?::VarChar, ?::VarChar, ?::Int, ?::Int, ?::Int, ?::VarChar, ?::VarChar)";
        $query = $this->db->query($consulta, Array($id_usuario, $login, $activo, $carnet, $nombre, $paterno, $materno, $direccion, $telefono, $id_departamento, $id_grupo, $id_proyecto, $serie, $usucre));
        return $query->row_array()['f_update_usuario'];
    }
}
