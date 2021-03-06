<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('dashboard');

            }
            elseif ($this->session->userdata('Rol_acceso') < 4) 
            {
                $this->load->view('dashboard_alternativo');

            } 
            else 
            {
                $this->session->sess_destroy();
                header("Location: " . base_url() . "login"); /// enviar a pagina de error

            }
        }
    }

/// CANTIDAD DE LISTADOS DE MES EN CURSO
    public function obtener_cantidad_de_items_stoqueados()
    {
        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));

        //$Mes = date('Y-m');

        //$fecha = date("Y-m-d");

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_stock');
        //$this->db->where("DATE_FORMAT(fecha,'%Y-%m')", $Mes);
        $this->db->where('Visible', 1);
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }


/// CANTIDAD DE USUARIOS A LA FECHA
    public function obtener_cantidad_usuarios()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        /// DATOS DE COMANDAS
        $this->db->select('Id');
        $this->db->from('tbl_usuarios');
        $this->db->where('Activo', 1);
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }
    

/// CONSULTAR ALS VENTAS DEL AÑO
    public function obtener_cantCompras()
    {
        $Anio = date('Y');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) 
        {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_compras');
        $this->db->where("DATE_FORMAT(Fecha_compra,'%Y')", $Anio);
        
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }


/// CANTIDAD DE PRDUCTOS DE FABRICACIÓN PROPIA
    public function obtener_productosPropios()
    {
        $Anio = date('Y');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) 
        {
            exit("No coinciden los token");
        }

        $this->db->select('Id');
        $this->db->from('tbl_fabricacion');
        
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }


    
    
/// CONSULTAR LAS VENTAS DE HOY
    public function obtener_listado_comandas_hoy()
    {

        $Fecha_hoy = date('Y-m-d');

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m-%d')", $Fecha_hoy);
        $this->db->where('Estado', 1);
        $query = $this->db->get();
        $array_comandas = $query->result_array();

        // sumar
        $valor_ventas_comandas = 0;
        foreach ($array_comandas as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento;
        }

        /// DATOS DE DELIVERY
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_delibery');
        $this->db->where("DATE_FORMAT(FechaHora_pedido,'%Y-%m-%d')", $Fecha_hoy);
        $this->db->where('Estado', 1);
        $query = $this->db->get();
        $array_delivery = $query->result_array();

        // sumar
        $valor_ventas_delivery = 0;
        foreach ($array_delivery as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento;
        }

        $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;

        echo json_encode($total_ventas);

    }

/// CONSULTAR LAS VENTAS DE AYER
    public function obtener_listado_comandas_de_ayer()
    {
        //$this->datosObtenidos = json_decode(file_get_contents('php://input'));

        $fecha = date('Y-m-d');
        $fecha_ayer = strtotime('-1 day', strtotime($fecha));
        $fecha_ayer = date('Y-m-d', $fecha_ayer);

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        /// DATOS DE COMANDAS
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_comandas');
        $this->db->where("DATE_FORMAT(Fecha,'%Y-%m-%d')", $fecha_ayer);
        $this->db->where('Estado', 1);
        $query = $this->db->get();
        $array_comandas = $query->result_array();

        // sumar
        $valor_ventas_comandas = 0;
        foreach ($array_comandas as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_comandas = $valor_ventas_comandas + $valor - $descuento;
        }

        /// DATOS DE DELIVERY
        $this->db->select('	Valor_cuenta,
                                    Valor_descuento');
        $this->db->from('tbl_delibery');
        $this->db->where("DATE_FORMAT(FechaHora_pedido,'%Y-%m-%d')", $fecha_ayer);
        $this->db->where('Estado', 1);
        $query = $this->db->get();
        $array_delivery = $query->result_array();

        // sumar
        $valor_ventas_delivery = 0;
        foreach ($array_delivery as $comanda) {
            $valor = $comanda["Valor_cuenta"];
            $descuento = $comanda["Valor_descuento"];
            $valor_ventas_delivery = $valor_ventas_delivery + $valor - $descuento;
        }

        $total_ventas = $valor_ventas_comandas + $valor_ventas_delivery;

        echo json_encode($total_ventas);

    }

/// info Minuto a minuto
    public function infoTimeline()
    {
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));

        $Pagina_actual = $this->datosObtenidos->Actual;

        $inicio = 0;
        $cantidadItems = 10;

        if ($this->datosObtenidos->Peticion == "Next") {$inicio = $Pagina_actual + $cantidadItems;} elseif ($this->datosObtenidos->Peticion == "Prev") {$inicio = $Pagina_actual - $cantidadItems;}

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->db->select('	tbl_comandas.Id,
                                tbl_comandas_timeline.*,
                                tbl_mesas.Identificador,
                                tbl_usuarios.Nombre as Nombre_moso');

        $this->db->from('tbl_comandas_timeline');

        $this->db->join('tbl_comandas', 'tbl_comandas.Id = tbl_comandas_timeline.Comanda_id', 'left');
        $this->db->join('tbl_mesas', 'tbl_mesas.Id = tbl_comandas.Mesa_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_comandas.Moso_id', 'left');

        /// segun el rol el lo que pueda ver
        if ($this->session->userdata('Rol_acceso') == 2) {
            $Usuario_login_id = $this->session->userdata('Id');
            $this->db->where('tbl_comandas.Moso_id', $Usuario_login_id);
        }

        $this->db->order_by("tbl_comandas_timeline.Hora", "desc");
        $this->db->limit($cantidadItems, $inicio); /// paginación

        //$this->db->where("DATE_FORMAT(tbl_comandas_timeline.Fecha,'%Y-%m-%d')", $fecha_ayer);

        //$this->db->where('tbl_comandas.Estado', 1);

        $query = $this->db->get();
        $result = $query->result_array();
        $totalRegistros = $query->num_rows();

        $Final = $totalRegistros / $cantidadItems;
        $Final = ceil($Final);

        $datos = array('Datos' => $result, 'Inicio' => $inicio, 'Final' => $Final);

        echo json_encode($datos);

    }

/// LISTADO DE SEGUIMIENTO DE PERSONAL
    public function obtener_seguimiento_personal()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->db->select(' tbl_usuarios_seguimiento.*,
                            tbl_usuarios.Nombre,
                            tbl_usuarios.Imagen,
                            tbl_usuarios.Id as Usuarios_id,
                            tbl_usuarios_seguimiento_categorias.Nombre_categoria');

        $this->db->from('tbl_usuarios_seguimiento');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_usuarios_seguimiento.Usuario_id', 'left');
        $this->db->join('tbl_usuarios_seguimiento_categorias', 'tbl_usuarios_seguimiento_categorias.Id = tbl_usuarios_seguimiento.Categoria_id', 'left');

        $this->db->order_by("tbl_usuarios_seguimiento.Fecha", "desc");
        $this->db->limit(25, 0); /// paginación

        //$this->db->where("DATE_FORMAT(tbl_usuarios_seguimiento.Fecha,'%Y-%m-%d')", $fecha_ayer);

        $this->db->where('tbl_usuarios_seguimiento.Visible', 1);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

/// Logs presencia
    public function obtener_log_presencia()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->datosObtenidos = json_decode(file_get_contents('php://input'));

        /// OBTENIENDO LA LISTA SOLAMENTE DE LOS USUARIOS ACTIVOS AHORA
        $this->db->select('	tbl_usuarios.Nombre,
                                    tbl_usuarios.Imagen,
                                    tbl_roles.Nombre_rol');

        $this->db->from('tbl_usuarios');

        $this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_acceso', 'left');
        $this->db->where('tbl_usuarios.Presencia', 1);
        $this->db->order_by("tbl_usuarios.Nombre", "desc");

        $query = $this->db->get();
        $result_presentes = $query->result_array();

    /// OBTENIENDO LA LISTA COMPLETA DE LOS LOG
        $Pagina_actual = $this->datosObtenidos->Actual;

        $inicio = 0;
        $cantidadItems = 10;

        if ($this->datosObtenidos->Peticion == "Next") {$inicio = $Pagina_actual + $cantidadItems;} elseif ($this->datosObtenidos->Peticion == "Prev") {$inicio = $Pagina_actual - $cantidadItems;}

        $this->db->select('	tbl_log_usuarios.*,
                                    tbl_usuarios.Nombre as Nombre_usuario,
                                    tbl_usuarios.Imagen,
                                    tbl_roles.Nombre_rol');

        $this->db->from('tbl_log_usuarios');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_log_usuarios.Colaborador_id', 'left');
        $this->db->join('tbl_roles', 'tbl_roles.Id = tbl_usuarios.Rol_acceso', 'left');

        $this->db->limit($cantidadItems, $inicio); /// paginación
        $this->db->order_by("tbl_log_usuarios.Fecha_hora", "desc");

        $query = $this->db->get();
        $result = $query->result_array();

        $totalRegistros = $query->num_rows();
        $Final = $totalRegistros / $cantidadItems;
        $Final = ceil($Final);

        $datos = array('usuariosPresentes' => $result_presentes, 'Datos' => $result, 'Inicio' => $inicio, 'Final' => $Final);

        echo json_encode($datos);

    }


//////
}
