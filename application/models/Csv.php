<?php

/**
 * Description of Csv
 *
 * @author Alberto Daniel Inch Sáinz
 */
class Csv extends CI_Model {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    ///@brief Verifica el registro del movil.
    ///@return Vector con el identificador del proyecto.
    public function get_registrado($serie) {
        $consulta = "SELECT id_proyecto, id_usuario
            FROM seg_usuario
            WHERE activo AND serie = ?";
        $query = $this->db->query($consulta, (String)$serie);
        
        return $query->row_array();
    }
    
    ///@brief Obtiene los proyectos registrados.
    ///@return Texto con los proyectos registrados.
    public function seg_proyecto() {
        $consulta = "WITH a AS(SELECT 1 ord,'id_proyecto|nombre|codigo|descripcion|fecinicio|fecfin|color_web|color_movil|color_font|codigo_desbloqueo|version_boleta|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_proyecto || '|' || coalesce(nombre::Text, '') || '|' || coalesce(codigo::Text, '') || '|' || coalesce(descripcion::Text, '') || '|' || extract(epoch FROM fecinicio)::Int || '|' || coalesce(extract(epoch FROM fecfin)::Text, '') || '|' || coalesce(color_web, '') || '|' || coalesce(color_movil, '') || '|' || coalesce(color_font, '') || '|' || coalesce(codigo_desbloqueo, '') || '|' || coalesce(version_boleta, '') || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '') cont 
            FROM seg_proyecto)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los usuarios registrados.
    ///@return Texto con los usuarios registrados.
    public function seg_usuario($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_usuario|login|password|activo|carnet|nombre|paterno|materno|direccion|telefono|nacional|id_departamento|id_grupo|id_proyecto|serie|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_usuario || '|' || coalesce(login::Text, '') || '|' || coalesce(password::Text, '') || '|' || coalesce(activo::Int::Text, '') || '|' || coalesce(carnet::Text, '') || '|' || coalesce(nombre::Text, '') || '|' || coalesce(paterno::Text, '') || '|' || coalesce(materno::Text, '') || '|' || coalesce(direccion::Text, '') || '|' || coalesce(telefono::Text, '') || '|' || coalesce(nacional::Int::Text, '') || '|' || coalesce(id_departamento::Text, '') || '|' || coalesce(id_grupo::Text, '') || '|' || coalesce(id_proyecto::Text, '') || '|' || coalesce(serie::Text, '') || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '') cont
            FROM seg_usuario
            WHERE id_proyecto = ?)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, $proy);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los grupos de usuarios registrados.
    ///@return Texto con los grupos registrados.
    public function seg_grupo() {
        $consulta = "WITH a AS(SELECT 1 ord,'id_grupo|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_grupo || '|' || descripcion || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '')
            FROM seg_grupo)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los permisos por grupo registrados.
    ///@return Texto con los permisos registrados.
    public function seg_permiso() {
        $consulta = "WITH a AS(SELECT 1 ord,'id_permiso|id_grupo|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_permiso || '|' || id_grupo || '|' || descripcion || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '')
            FROM seg_permiso)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene las boletas registradas.
    ///@return Texto con las boletas registrados.
    public function seg_boleta($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_boleta|id_proyecto|descripcion|id_pregunta|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_boleta || '|' || id_proyecto || '|' || descripcion || '|' || id_pregunta || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '')
            FROM seg_boleta
            WHERE id_proyecto = ?)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, $proy);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los tipos de preguntas.
    ///@return Texto con los tipos de preguntas.
    public function enc_tipo() {
        $consulta = "WITH a AS(SELECT 1 ord,'id_tipo|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_tipo || '|' || descripcion || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '')
            FROM enc_tipo)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los niveles admitidos.
    ///@return Texto con los niveles admitidos.
    public function enc_nivel() {
        $consulta = "WITH a AS(SELECT 1 ord,'id_nivel|nivel|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_nivel || '|' || nivel || '|' || descripcion || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '')
            FROM enc_nivel)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene las secciones que agrupan preguntas dentro del proyecto.
    ///@return Texto con las secciones de las preguntas.
    public function enc_seccion($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_seccion|id_proyecto|id_boleta|id_nivel|codigo|seccion|abierta|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_seccion || '|' || id_proyecto || '|' || id_boleta || '|' || id_nivel || '|' || codigo || '|' || seccion || '|' || abierta || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '')
            FROM enc_seccion
            WHERE id_proyecto = ?)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, $proy);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene las preguntas del proyecto.
    ///@return Texto con las preguntas.
    public function enc_pregunta($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_pregunta|id_proyecto|id_boleta|id_nivel|id_seccion|codigo_pregunta|pregunta|ayuda|instruccion|id_tipo|minimo|maximo|catalogo|longitud|bucle|variable_bucle|codigo_especifique|mostrar_ventana|variable|codigo_especial|formula|rpn_formula|revision|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_pregunta || '|' || id_proyecto || '|' || id_boleta || '|' || id_nivel || '|' || coalesce(id_seccion::Text, '') || '|' || codigo_pregunta || '|' || pregunta || '|' || ayuda || '|' || instruccion || '|' || id_tipo || '|' || minimo || '|' || maximo || '|' || catalogo || '|' || coalesce(longitud::Text, '') || '|' || bucle || '|' || variable_bucle || '|' || codigo_especifique || '|' || mostrar_ventana || '|' || variable || '|' || codigo_especial || '|' || replace(formula, '|', '\:') || '|' || replace(rpn_formula, '|', '\:') || '|' || revision || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '') cont
            FROM enc_pregunta
            WHERE id_proyecto = ?
            AND apiestado = 'ELABORADO')
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, Array($proy));
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene las opciones de selección.
    ///@return Texto con las opciones de selección.
    public function enc_respuesta($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_respuesta|id_pregunta|codigo|respuesta|factor|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_respuesta || '|' || coalesce(r.id_pregunta::Text, '') || '|' || codigo || '|' || respuesta || '|' || factor || '|' || r.apiestado || '|' || r.usucre || '|' || extract(epoch FROM r.feccre)::BigInt || '|' || coalesce(r.usumod::Text, '') || '|' || coalesce(extract(epoch FROM r.fecmod)::Int::Text, '') cont
            FROM enc_respuesta r, enc_pregunta p
            WHERE r.id_pregunta = p.id_pregunta
            AND id_proyecto = ?
            AND r.apiestado = 'ELABORADO')
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, $proy);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene la saltos entre preguntas.
    ///@return Texto con los saltos entre preguntas.
    public function enc_flujo($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_flujo|id_proyecto|id_pregunta|id_pregunta_destino|orden|regla|rpn|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_flujo || '|' || coalesce(id_proyecto::Text, '') || '|' || id_pregunta || '|' || id_pregunta_destino || '|' || orden || '|' || replace(regla, '|', '\:') || '|' || replace(rpn, '|', '\:') || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '') cont
            FROM enc_flujo
            WHERE id_proyecto = ?
            AND apiestado = 'ELABORADO')
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, $proy);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene las reglas de validación preguntas.
    ///@return Texto con las reglas.
    public function enc_regla($proy) {
        $consulta = "WITH a AS(SELECT 1 ord,'id_regla|id_proyecto|id_pregunta|orden|regla|rpn|mensaje|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord,id_regla || '|' || coalesce(id_proyecto::Text, '') || '|' || id_pregunta || '|' || orden || '|' || replace(regla, '|', '\:') || '|' || replace(rpn, '|', '\:') || '|' || mensaje || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre)::Int || '|' || coalesce(usumod::Text, '') || '|' || coalesce(extract(epoch FROM fecmod)::Int::Text, '') cont
            FROM enc_regla
            WHERE id_proyecto = ?
            AND apiestado = 'ELABORADO')
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta, $proy);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los catalogos.
    ///@return Texto con los catalogos.
    public function cat_catalogo() {
        $consulta = "WITH a AS(SELECT 1 ord,'id_catalogo|catalogo|codigo|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
            UNION ALL
            SELECT 2 ord, id_catalogo || '|' || catalogo || '|' || codigo || '|' || descripcion || '|' || apiestado || '|' || usucre || '|' || extract(epoch FROM feccre) || '|' || coalesce(usumod, '') || '|' || coalesce(extract(epoch FROM fecmod)::Text, '') cont
            FROM cat_catalogo)
            SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
        $query = $this->db->query($consulta);
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los informantes Mercados/Comercializadoras registrados.
    ///@return Texto con los informantes registrados.
    public function seg_informador($usuario, $tipo, $periodo = NULL) {
        if ($periodo == NULL) {
            $consulta = "WITH a AS(SELECT 1 ord,'id_informador|id_departamento|id_ciudad|id_boleta|descripcion|regine|nombre_informante|nit|situacion|direccion|numero|edificio|piso|oficina|zona|entre_calles|referencia|telefono|fax|casilla|e_mail|pagina_web|carga|recorrido_carga|json|apiestado|usucre|feccre|usumod|fecmod' cont
                UNION ALL
                SELECT 2 ord,i.id_informador || '|' || i.id_departamento || '|' || i.id_ciudad || '|' || i.id_boleta || '|' || i.descripcion || '|' || coalesce(i.regine::Text, '') || '|' || coalesce(i.nombre_informante::Text, '') || '|' || coalesce(i.nit::Text, '') || '|' || coalesce(i.situacion::Text, '') || '|' || coalesce(i.direccion::Text, '') || '|' || coalesce(i.numero::Text, '') || '|' || coalesce(i.edificio::Text, '') || '|' || coalesce(i.piso::Text, '') || '|' || coalesce(i.oficina::Text, '') || '|' || coalesce(i.zona::Text, '') || '|' || coalesce(i.entre_calles::Text, '') || '|' || coalesce(i.referencia::Text, '') || '|' || coalesce(i.telefono::Text, '') || '|' || coalesce(i.fax::Text, '') || '|' || coalesce(i.casilla::Text, '') || '|' || coalesce(i.e_mail::Text, '') || '|' || coalesce(i.pagina_web::Text, '') || '|' || coalesce(i.carga::Text, '') || '|' || coalesce(i.recorrido_carga::Text, '') || '|' || coalesce(split_part(split_part(i.json, '[', 2), ']', 1), '') || '|' || i.apiestado || '|' || i.usucre || '|' || extract(epoch FROM i.feccre)::Int || '|' || coalesce(i.usumod::Text, '') || '|' || coalesce(extract(epoch FROM i.fecmod)::Int::Text, '')
                FROM seg_informador i, seg_asignacion a
                WHERE i.id_informador = a.id_informador
                AND a.activo = 1 AND a.id_usuario = ? AND i.id_boleta = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo));
        } else {
            $consulta = "WITH a AS(SELECT 1 ord,'id_informador|id_departamento|id_ciudad|id_boleta|descripcion|regine|nombre_informante|nit|situacion|direccion|numero|edificio|piso|oficina|zona|entre_calles|referencia|telefono|fax|casilla|e_mail|pagina_web|carga|recorrido_carga|json|apiestado|usucre|feccre|usumod|fecmod' cont
                UNION ALL
                SELECT 2 ord,i.id_informador || '|' || i.id_departamento || '|' || i.id_ciudad || '|' || i.id_boleta || '|' || i.descripcion || '|' || coalesce(i.regine::Text, '') || '|' || coalesce(i.nombre_informante::Text, '') || '|' || coalesce(i.nit::Text, '') || '|' || coalesce(i.situacion::Text, '') || '|' || coalesce(i.direccion::Text, '') || '|' || coalesce(i.numero::Text, '') || '|' || coalesce(i.edificio::Text, '') || '|' || coalesce(i.piso::Text, '') || '|' || coalesce(i.oficina::Text, '') || '|' || coalesce(i.zona::Text, '') || '|' || coalesce(i.entre_calles::Text, '') || '|' || coalesce(i.referencia::Text, '') || '|' || coalesce(i.telefono::Text, '') || '|' || coalesce(i.fax::Text, '') || '|' || coalesce(i.casilla::Text, '') || '|' || coalesce(i.e_mail::Text, '') || '|' || coalesce(i.pagina_web::Text, '') || '|' || coalesce(i.carga::Text, '') || '|' || coalesce(i.recorrido_carga::Text, '') || '|' || coalesce(split_part(split_part(i.json, '[', 2), ']', 1), '') || '|' || i.apiestado || '|' || i.usucre || '|' || extract(epoch FROM i.feccre)::Int || '|' || coalesce(i.usumod::Text, '') || '|' || coalesce(extract(epoch FROM i.fecmod)::Int::Text, '')
                FROM seg_informador i, seg_asignacion a
                WHERE i.id_informador = a.id_informador
                AND a.exportado AND a.id_usuario = ? AND i.id_boleta = ?
                AND a.gestion || '-' || lpad(a.".($tipo == 1?'semana':'mes')."::Text, 2, '0') = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo, $periodo));
        }
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene la asignación de informantes.
    ///@return Texto con la asignación de informantes.
    public function seg_asignacion($usuario, $tipo, $periodo = NULL) {
        if ($periodo == NULL) {
            $consulta = "WITH a AS(SELECT 1 ord,'id_asignacion|id_usuario|id_informador|gestion|mes|semana|apiestado|usucre|feccre|usumod|fecmod' cont
                UNION ALL
                SELECT 2 ord,a.id_asignacion || '|' || a.id_usuario || '|' || a.id_informador || '|' || a.gestion || '|' || a.mes || '|' || a.semana || '|' || a.apiestado || '|' || a.usucre || '|' || extract(epoch FROM a.feccre)::Int || '|' || coalesce(a.usumod::Text, '') || '|' || coalesce(extract(epoch FROM a.fecmod)::Int::Text, '')
                FROM seg_asignacion a, seg_informador i
                WHERE a.id_informador = i.id_informador
                AND a.activo = 1 AND a.id_usuario = ? AND i.id_boleta = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo));
        } else {
            $consulta = "WITH a AS(SELECT 1 ord,'id_asignacion|id_usuario|id_informador|gestion|mes|semana|apiestado|usucre|feccre|usumod|fecmod' cont
                UNION ALL
                SELECT 2 ord,a.id_asignacion || '|' || a.id_usuario || '|' || a.id_informador || '|' || a.gestion || '|' || a.mes || '|' || a.semana || '|' || a.apiestado || '|' || a.usucre || '|' || extract(epoch FROM a.feccre)::Int || '|' || coalesce(a.usumod::Text, '') || '|' || coalesce(extract(epoch FROM a.fecmod)::Int::Text, '')
                FROM seg_asignacion a, seg_informador i
                WHERE a.id_informador = i.id_informador
                AND a.exportado AND a.id_usuario = ? AND i.id_boleta = ?
                AND a.gestion || '-' || lpad(a.".($tipo == 1?'semana':'mes')."::Text, 2, '0') = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo, $periodo));
        }
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene los productos asignados por usuario.
    ///@return Texto con los productos.
    public function enc_informante($usuario, $tipo, $periodo = NULL) {
        if ($periodo == NULL) {
            $consulta = "WITH a AS(SELECT 1 ord,'id_asignacion|correlativo|id_asignacion_padre|correlativo_padre|id_usuario|id_informador|id_nivel|latitud|longitud|id|cod|codigo|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
                UNION ALL
                SELECT 2 ord,i.id_asignacion || '|' || correlativo || '|' || coalesce(id_asignacion_padre::Text, '') || '|' || coalesce(correlativo_padre::Text, '') || '|' || coalesce(i.id_usuario::Text, '') || '|' || coalesce(i.id_informador::Text, '') || '|' || coalesce(id_nivel::Text, '') || '|' || latitud || '|' || longitud || '|' || coalesce(id::Text, '') || '|' || coalesce(cod::Text, '') || '|' || coalesce(codigo::Text, '') || '|' || coalesce(i.descripcion::Text, '') || '|' || i.apiestado || '|' || i.usucre || '|' || extract(epoch FROM i.feccre)::Int || '|' || coalesce(i.usumod::Text, '') || '|' || coalesce(extract(epoch FROM i.fecmod)::Int::Text, '') cont
                FROM enc_informante i, seg_asignacion a, seg_informador si
                WHERE i.id_asignacion = a.id_asignacion AND a.id_informador = si.id_informador
                AND a.activo = 1 AND a.id_usuario = ? AND si.id_boleta = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo));
        } else {
            $consulta = "WITH a AS(SELECT 1 ord,'id_asignacion|correlativo|id_asignacion_padre|correlativo_padre|id_usuario|id_informador|id_nivel|latitud|longitud|id|cod|codigo|descripcion|apiestado|usucre|feccre|usumod|fecmod' cont
                UNION ALL
                SELECT 2 ord,i.id_asignacion || '|' || correlativo || '|' || coalesce(id_asignacion_padre::Text, '') || '|' || coalesce(correlativo_padre::Text, '') || '|' || coalesce(i.id_usuario::Text, '') || '|' || coalesce(i.id_informador::Text, '') || '|' || coalesce(id_nivel::Text, '') || '|' || latitud || '|' || longitud || '|' || coalesce(id::Text, '') || '|' || coalesce(cod::Text, '') || '|' || coalesce(codigo::Text, '') || '|' || coalesce(i.descripcion::Text, '') || '|' || i.apiestado || '|' || i.usucre || '|' || extract(epoch FROM i.feccre)::Int || '|' || coalesce(i.usumod::Text, '') || '|' || coalesce(extract(epoch FROM i.fecmod)::Int::Text, '') cont
                FROM enc_informante i, seg_asignacion a, seg_informador si
                WHERE i.id_asignacion = a.id_asignacion AND a.id_informador = si.id_informador
                AND a.exportado AND a.id_usuario = ? AND si.id_boleta = ?
                AND a.gestion || '-' || lpad(a.".($tipo == 1?'semana':'mes')."::Text, 2, '0') = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo, $periodo));
        }
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene el detalle de los productos.
    ///@return Texto con el detalle.
    public function enc_encuesta($usuario, $tipo, $periodo = NULL) {
        if (TRUE) {
            $consulta = "WITH a AS(SELECT 1 ord,'id_asignacion|correlativo|id_pregunta|fila|id_respuesta|codigo_respuesta|respuesta|observacion|factor|latitud|longitud|id_last|apiestado|usucre|feccre|usumod|fecmod|presicion' cont
                UNION ALL
                SELECT 2 ord,e.id_asignacion || '|' || correlativo || '|' || id_pregunta || '|' || fila || '|' || id_respuesta || '|' || codigo_respuesta || '|' || respuesta || '|' || coalesce(replace(observacion, chr(10)||chr(13), ''), '') || '|' || factor || '|' || coalesce(latitud, 0) || '|' || coalesce(longitud, 0) || '|' || id_last || '|' || e.apiestado || '|' || 
                e.usucre || '|' || extract(epoch FROM e.feccre)::Int || '|' || coalesce(e.usumod::Text, '') || '|' || coalesce(extract(epoch FROM e.fecmod)::Int::Text, '') || '|' || coalesce(presicion::Text, '') cont
                FROM enc_encuesta e, seg_asignacion a, seg_informador si
                WHERE e.id_asignacion = a.id_asignacion AND a.id_informador = si.id_informador
                AND a.activo = 1 AND a.id_usuario = ? AND si.id_boleta = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo));
        } else {
            $consulta = "WITH a AS(SELECT 1 ord,'id_asignacion|correlativo|id_pregunta|fila|id_respuesta|codigo_respuesta|respuesta|observacion|factor|latitud|longitud|id_last|apiestado|usucre|feccre|usumod|fecmod|presicion' cont
                UNION ALL
                SELECT 2 ord,e.id_asignacion || '|' || correlativo || '|' || id_pregunta || '|' || fila || '|' || id_respuesta || '|' || codigo_respuesta || '|' || respuesta || '|' || coalesce(replace(observacion, chr(10)||chr(13), ''), '') || '|' || factor || '|' || coalesce(latitud, 0) || '|' || coalesce(longitud, 0) || '|' || id_last || '|' || e.apiestado || '|' || e.usucre || '|' || extract(epoch FROM e.feccre)::Int || '|' || coalesce(e.usumod::Text, '') || '|' || coalesce(extract(epoch FROM e.fecmod)::Int::Text, '') || '|' || coalesce(presicion::Text, '') cont
                FROM enc_encuesta e, seg_asignacion a, seg_informador si
                WHERE e.id_asignacion = a.id_asignacion AND a.id_informador = si.id_informador
                AND a.exportado AND a.id_usuario = ? AND si.id_boleta = ?
                AND a.gestion || '-' || lpad(a.".($tipo == 1?'semana':'mes')."::Text, 2, '0') = ?)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array((Int)$usuario, (Int)$tipo, $periodo));
        }
        
        return $query->row_array()['string_agg'];
    }
    
    ///@brief Obtiene las últimas 3 cotizaciones de los productos asignados.
    ///@return Texto con el detalle.
    public function cotizacion($usuario, $tipo, $periodo = NULL) {
        if ($periodo == NULL) {
            $consulta = "WITH a AS(SELECT 1 ord,'id_producto|id_boleta|gestion|mes|semana|codigo_estado|cotizacion|precio' cont
                UNION ALL
                SELECT 2 ord, id_producto || '|' || id_boleta || '|' || gestion || '|' || mes || '|' || semana || '|' || codigo_estado || '|' || cotizacion || '|' || precio_var
                FROM (SELECT row_number() OVER (PARTITION BY c.id_producto ORDER BY c.gestion DESC, c.mes DESC, c.semana DESC), c.id_producto, p.id_boleta, c.gestion, c.mes, c.semana, c.codigo_estado, c.cotizacion, c.precio_var
                    FROM cotizacion c, seg_producto p, seg_asignacion a
                    WHERE c.id_producto = p.id_producto
                    AND p.id_informador = a.id_informador
                    AND a.activo = 1
                    AND a.id_usuario = ?
                    AND p.id_boleta = ?
                    AND c.gestion || '-' || lpad(c.mes::Text, 2, '0') || '-' || lpad(c.semana::Text, 2, '0') <> f_periodo_actual(?)) c
                WHERE row_number < 4
                UNION ALL
                SELECT 2 ord, id_producto || '|' || id_boleta || '|' || gestion || '|' || mes || '|' || semana || '|' || codigo_estado || '|' || precio || '|' || precio
                FROM (WITH a AS(SELECT p.id_producto, p.id_boleta, gestion, mes, semana, cod codigo_estado, geom_mean(respuesta::Numeric) precio
                        FROM seg_asignacion a, enc_informante i, enc_encuesta e, seg_producto p
                        WHERE a.id_asignacion = i.id_asignacion
                        AND i.id_asignacion = e.id_asignacion
                        AND i.correlativo = e.correlativo
                        AND i.id = p.id_producto
                        AND p.apiestado = 'DESCARTADO'
                        AND e.id_pregunta IN(6, 21)
                        AND respuesta <> ''
                        AND p.id_boleta = ?
                        GROUP BY p.id_producto, p.id_boleta, gestion, mes, semana, cod
                        ORDER BY p.id_producto, gestion DESC, mes DESC, semana DESC)
                    SELECT row_number() OVER (PARTITION BY id_producto ORDER BY gestion DESC, mes DESC, semana DESC), id_producto, id_boleta, gestion, mes, semana, codigo_estado, precio
                    FROM a
                    WHERE gestion || '-' || lpad(mes::Text, 2, '0') || '-' || lpad(semana::Text, 2, '0') <> f_periodo_actual(?)) c
                WHERE row_number < 4)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array($usuario, $tipo, $tipo, $tipo, $tipo));
        } else {
            $consulta = "WITH a AS(SELECT 1 ord,'id_producto|id_boleta|gestion|mes|semana|codigo_estado|cotizacion|precio' cont
                UNION ALL
                SELECT 2 ord, id_producto || '|' || id_boleta || '|' || gestion || '|' || mes || '|' || semana || '|' || codigo_estado || '|' || cotizacion || '|' || precio_var
                FROM (SELECT row_number() OVER (PARTITION BY c.id_producto ORDER BY c.gestion DESC, c.mes DESC, c.semana DESC), c.id_producto, p.id_boleta, c.gestion, c.mes, c.semana, c.codigo_estado, c.cotizacion, c.precio_var
                    FROM cotizacion c, seg_producto p, seg_asignacion a
                    WHERE c.id_producto = p.id_producto
                    AND p.id_informador = a.id_informador
                    AND a.exportado
                    AND a.id_usuario = ?
                    AND p.id_boleta = ?
                    AND c.gestion || '-' || lpad(c.".($tipo == 1?'semana':'mes')."::Text, 2, '0') < ?
                    AND a.gestion || '-' || lpad(a.".($tipo == 1?'semana':'mes')."::Text, 2, '0') = ?) c
                WHERE row_number < 4
                UNION ALL
                SELECT 2 ord, id_producto || '|' || id_boleta || '|' || gestion || '|' || mes || '|' || semana || '|' || codigo_estado || '|' || precio || '|' || precio
                FROM (WITH a AS(SELECT p.id_producto, p.id_boleta, gestion, mes, semana, cod codigo_estado, geom_mean(respuesta::Numeric) precio
                        FROM seg_asignacion a, enc_informante i, enc_encuesta e, seg_producto p
                        WHERE a.id_asignacion = i.id_asignacion
                        AND i.id_asignacion = e.id_asignacion
                        AND i.correlativo = e.correlativo
                        AND i.id = p.id_producto
                        AND p.apiestado = 'DESCARTADO'
                        AND e.id_pregunta IN(6, 21)
                        AND respuesta <> ''
                        AND p.id_boleta = ?
                        GROUP BY p.id_producto, p.id_boleta, gestion, mes, semana, cod
                        ORDER BY p.id_producto, gestion DESC, mes DESC, semana DESC)
                    SELECT row_number() OVER (PARTITION BY id_producto ORDER BY gestion DESC, mes DESC, semana DESC), id_producto, id_boleta, gestion, mes, semana, codigo_estado, precio
                    FROM a
                    WHERE gestion || '-' || lpad(".($tipo == 1?'semana':'mes')."::Text, 2, '0') < ?) c
                WHERE row_number < 4)
                SELECT string_agg(cont, chr(13)||chr(10) ORDER BY ord) FROM a";
            $query = $this->db->query($consulta, Array($usuario, $tipo, $periodo, $periodo, $tipo, $periodo));
        }
        
        return $query->row_array()['string_agg'];
    }
}