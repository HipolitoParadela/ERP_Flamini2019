<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ventas extends CI_Controller
{

//// VENTAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            
            /// Visible para roles administradores y acceso especial a Yohana, Dayana, Jaqui, Belen
            if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 5 || $this->session->userdata('Id') == 6 || $this->session->userdata('Id') == 33 || $this->session->userdata('Id') == 7 ) {
                $this->load->view('ventas_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// VENTAS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}
            
            /// Visible para roles administradores y acceso especial a Yohana, Dayana, Jaqui, Belen
            if (    
                $this->session->userdata('Rol_acceso') > 3 
                || $this->session->userdata('Id') == 5 
                || $this->session->userdata('Id') == 6 
                || $this->session->userdata('Id') == 33 
                || $this->session->userdata('Id') == 7
            ) 
            {
                //Esto siempre va es para instanciar la base de datos
                    $CI = &get_instance();
                    $CI->load->database();

                    $Id = $_GET["Id"];

                    $this->db->select(' tbl_ventas.*,
                                                tbl_vendedor.Nombre as Nombre_vendedor,
                                                tbl_resp_1.Nombre as Nombre_resp_1,
                                                tbl_resp_2.Nombre as Nombre_resp_2,
                                                tbl_empresas.Nombre_empresa,
                                                tbl_clientes.Nombre_cliente');

                    $this->db->from('tbl_ventas');

                    $this->db->join('tbl_clientes', 'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
                    $this->db->join('tbl_usuarios as tbl_vendedor', 'tbl_vendedor.Id   = tbl_ventas.Vendedor_id', 'left');
                    $this->db->join('tbl_empresas', 'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
                    $this->db->join('tbl_usuarios as tbl_resp_1', 'tbl_resp_1.Id   = tbl_ventas.Vendedor_id', 'left');
                    $this->db->join('tbl_usuarios as tbl_resp_2', 'tbl_resp_2.Id   = tbl_ventas.Vendedor_id', 'left');

                    $this->db->where('tbl_ventas.Id', $Id);
                    $this->db->where('tbl_ventas.Visible', 1);
                    

                    $query = $this->db->get();
                    $result = $query->result_array();

                
                $Datos = array('Datos_venta' => $result[0]);
                
                $this->load->view('ventas_datos', $Datos );
                
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// VENTAS       | VISTA | PRODUCCION
    public function produccion()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } 
        else 
        {
            if ($this->session->userdata('Rol_acceso') > 1 || $this->session->userdata('Id') == 3 || $this->session->userdata('Id') == 5 || $this->session->userdata('Id') == 6 || $this->session->userdata('Id') == 33 || $this->session->userdata('Id') == 7) //USUARIO 3, FRANCO DÍAZ 
            {
                $this->load->view('ventas_produccion');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }
        }
    }

//// VENTAS 	  | OBTENER TODAS
	public function obtener_listado_ventas()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }
        
        ///Filtros
        $Empresa_id         = $_GET["Empresa_id"];
        $Vendedor_id        = $_GET["Vendedor_id"];
        $Cliente_id         = $_GET["Cliente_id"];
        $Planificacion_id   = $_GET["Planificacion_id"];

        /// PERMITIR VER VENTAS ANULADAS O ELIMINADAS
        $Visible = 1; if($_GET["Estado"] == 0) {  $Visible = 0; }

        $this->db->select(' tbl_ventas.*,
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente,
                            tbl_planificaciones.Nombre_planificacion');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',   'tbl_clientes.Id    = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',   'tbl_usuarios.Id    = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',   'tbl_empresas.Id    = tbl_ventas.Empresa_id', 'left');
        $this->db->join('tbl_planificaciones',   'tbl_planificaciones.Id    = tbl_ventas.Planificacion_id', 'left');

        if($Vendedor_id > 0)    { $this->db->where('tbl_ventas.Vendedor_id', $Vendedor_id); }
        if($Cliente_id > 0)     { $this->db->where('tbl_ventas.Cliente_id', $Cliente_id); }
        if($Empresa_id > 0)     { $this->db->where('tbl_ventas.Empresa_id', $Empresa_id); }
        if($Planificacion_id > 0)     { $this->db->where('tbl_ventas.Planificacion_id', $Planificacion_id); }

        $this->db->where('tbl_ventas.Visible', $Visible);
        
        if($_GET["Estado"] == 9) /// muestra ventas en estado de cobranza
        {
            $this->db->where('tbl_ventas.Estado', '9');
        }
        else if($_GET["Estado"] == 10) /// muestra ventas cobradas
        {
            $this->db->where('tbl_ventas.Estado', '10');
        }
        else {
            $this->db->where('tbl_ventas.Estado <', 9); /// Muestra ventas en estados anterior a entregados... o sea en estado de cobranza
        }
        
		$this->db->order_by('tbl_ventas.Fecha_venta', 'desc');
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// VENTAS 	  | CARGAR O EDITAR 
	public function cargar_venta()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}

        ///// ANALIZAR ACA SI PONGO DIRECTAMENTE EL ID A MANO O LE BUSCO OTRA MANERA, POR EL MOMENTO A MANO VA A FUNCIONAR BIEN, SI ALGUN DIA TIENEN MAS DE UN RESPONSABLE, LO TENDRAN QUE ELEGIR DE UN LISTADO
        
        $Responsable_id_planif_inicial = $this->datosObtenidos->Data->Responsable_id_planif_inicial;
        $Responsable_id_planif_final = $this->datosObtenidos->Data->Responsable_id_planif_final;
        $Responsable_id_logistica = $this->datosObtenidos->Data->Responsable_id_logistica;
        $Responsable_id_instalacion = $this->datosObtenidos->Data->Responsable_id_instalacion;
        $Responsable_id_cobranza = $this->datosObtenidos->Data->Responsable_id_cobranza;
        
        
        $data = array(
                        
                    'Identificador_venta' => 	    $this->datosObtenidos->Data->Identificador_venta,
                    'Planificacion_id' => 			    $this->datosObtenidos->Data->Planificacion_id,
                    'Cliente_id' => 			    $this->datosObtenidos->Data->Cliente_id,
                    "Empresa_id" =>                 $this->datosObtenidos->Data->Empresa_id,
					'Vendedor_id' => 			    $this->datosObtenidos->Data->Vendedor_id,
                    'Fecha_venta' => 	            $this->datosObtenidos->Data->Fecha_venta,
                    'Fecha_estimada_entrega' =>     $this->datosObtenidos->Data->Fecha_estimada_entrega,
                    
                    'Responsable_id_planif_inicial' => 	$Responsable_id_planif_inicial,
                    'Responsable_id_planif_final' => 	$Responsable_id_planif_final,
                    'Responsable_id_logistica' => 	    $Responsable_id_logistica,
                    'Responsable_id_instalacion' => 	$Responsable_id_instalacion,
                    'Responsable_id_cobranza' => 	    $Responsable_id_cobranza,

                    'Info_logistica' => 	        $this->datosObtenidos->Data->Info_logistica,
                    'Info_instalaciones' => 	    $this->datosObtenidos->Data->Info_instalaciones,
                    'Info_cobranza' => 	            $this->datosObtenidos->Data->Info_cobranza,

                    'Valor_logistica' => 	            $this->datosObtenidos->Data->Valor_logistica,
                    'Valor_instalacion' => 	            $this->datosObtenidos->Data->Valor_instalacion,

                    'Descuento' => 	            $this->datosObtenidos->Data->Descuento,
                    'Recargo' => 	            $this->datosObtenidos->Data->Recargo,

                    'Prioritario'                => $this->datosObtenidos->Data->Prioritario,
                    'Observaciones_venta'=>         $this->datosObtenidos->Data->Observaciones_venta,
					'Usuario_id' => 	            $this->session->userdata('Id'),
					
				);

                $this->load->model('App_model');
                $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas');
                        
                //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
                if ($insert_id >=0 ) 
                {   
                    
                    if($Id == null) {   $descripcion_seguimiento = "Inicio de fabricación";    }
                    else {   $descripcion_seguimiento = "Se actualizaron los datos en la ficha de esta venta.";
        }
            
            $data = array(

            'Venta_id' =>       $insert_id,
            'Descripcion' =>    $descripcion_seguimiento,
            'Usuario_id' =>    $this->session->userdata('Id'),
            'Visible' =>        1
            );

            $this->load->model('App_model');
            $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                
            echo json_encode(array("Id" => $insert_id, "Seguimiento_id" => $insert_id_seguimiento));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// VENTAS 	  | AVANZAR ESTADO
    public function cambiar_estado_venta()
    {
        
        $CI =& get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }

        $Venta_id = null;  if(isset($this->datosObtenidos->Venta_id)) { $Venta_id = $this->datosObtenidos->Venta_id; }

        ///// ANALIZAR ACA SI PONGO DIRECTAMENTE EL ID A MANO O LE BUSCO OTRA MANERA, POR EL MOMENTO A MANO VA A FUNCIONAR BIEN, SI ALGUN DIA TIENEN MAS DE UN RESPONSABLE, LO TENDRAN QUE ELEGIR DE UN LISTADO
        
        $estado = $this->datosObtenidos->Estado + 1;
        $Monto_cobrado = $this->datosObtenidos->Monto_cobrado;
        

        //// DEL ESTADO 8 AL 9, SE DA POR ENTREGADO E INSTALADO, SOLO FALTANDO COBRAR
            // aca debe tomar el monto completo de la venta y llenar el campo Monto_cobrado
        $Fecha_entregado = null; 
        if($estado == 9){  $Fecha_entregado = date("Y-m-d"); }

        //// DEL ESTADO 9 AL 10, SE CIERRA LA VENTA COMPLETAMENTE, INCLUSO LA COBRANZA
        $Fecha_finalizada = null; 
        if($estado == 10){  $Fecha_finalizada = date("Y-m-d"); }
        
        $data = array(  
                        'Fecha_entregado'  => $Fecha_entregado,
                        'Fecha_finalizada'  => $Fecha_finalizada,
                        'Monto_cobrado'     => $Monto_cobrado,      
                        'Estado'            => $estado,
                );

        $this->load->model('App_model');
        $venta_insert_id = $this->App_model->insertar( $data, $Venta_id, 'tbl_ventas' );
                
        //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
        if ($venta_insert_id >=0 ) 
        {   
            
            if($estado == 2)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Proceso de Materiales."; $Categoria = 2; }
            if($estado == 3)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Soldadura."; $Categoria = 2; }
            if($estado == 4)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Pintura."; $Categoria = 2; }
            if($estado == 5)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Rotulación."; $Categoria = 2; }
            if($estado == 6)  {  $descripcion_seguimiento = "Avanzó el lote a la estación de Empaque."; $Categoria = 2; }
            if($estado == 7)  {  $descripcion_seguimiento = "Finalizó el proceso de producción de este lote. Continúa en Logística."; $Categoria = 2; }
            if($estado == 8)  {  $descripcion_seguimiento = "Logística finalizada.";  $Categoria = 3;}
            if($estado == 9)  {  $descripcion_seguimiento = "Instalación finalizada.";  $Categoria = 4;}
            if($estado == 10) {  $descripcion_seguimiento = "Cobranza finalizada.";  $Categoria = 5;}

            
            $data = array(

                'Venta_id' =>               $venta_insert_id,
                'Categoria_seguimiento' =>  $Categoria,
                'Descripcion' =>            $descripcion_seguimiento,
                'Usuario_id' =>             $this->session->userdata('Id'),
                'Visible' =>                1
            );

            $this->load->model('App_model');
            $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                
            echo json_encode(array(
                "Id" => $venta_insert_id, 
                "Seguimiento_id" => $insert_id_seguimiento
            ));         
        } 
        else 
        {
            echo json_encode(array("Id" => "Error en el proceso"));
        }
    }
    
//// VENTAS 	  | OBTENER DATOS DE UNA VENTA
    public function obtener_datos_venta()
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

        $Id = $_GET["Id"];

        $this->db->select(' tbl_ventas.*,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Id as Cliente_id,
                            tbl_clientes.Nombre_cliente,
                            tbl_clientes.CUIT_CUIL,
                            tbl_clientes.Cond_iva,
                            tbl_clientes.Direccion,
                            tbl_clientes.Localidad,
                            tbl_clientes.Provincia,
                            tbl_clientes.Telefono,
                            tbl_clientes.Email,
                            tbl_clientes.Nombre_persona_contacto,
                            tbl_planificaciones.Nombre_planificacion,
                            tbl_vendedor.Nombre as Nombre_vendedor,
                            tbl_resp_1.Nombre as Nombre_resp_1,
                            tbl_resp_2.Nombre as Nombre_resp_2,
                            tbl_logistica.Nombre as Nombre_logistica,
                            tbl_instalacion.Nombre as Nombre_instalacion,
                            tbl_cobranza.Nombre as Nombre_cobranza');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_empresas',  'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
        $this->db->join('tbl_planificaciones',  'tbl_planificaciones.Id   = tbl_ventas.Planificacion_id', 'left');
        $this->db->join('tbl_usuarios as tbl_vendedor',  'tbl_vendedor.Id   = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_1',  'tbl_resp_1.Id   = tbl_ventas.Responsable_id_planif_inicial', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_2', 'tbl_resp_2.Id   = tbl_ventas.Responsable_id_planif_final', 'left');
        $this->db->join('tbl_usuarios as tbl_logistica', 'tbl_logistica.Id   = tbl_ventas.Responsable_id_logistica', 'left');
        $this->db->join('tbl_usuarios as tbl_instalacion', 'tbl_instalacion.Id   = tbl_ventas.Responsable_id_instalacion', 'left');
        $this->db->join('tbl_usuarios as tbl_cobranza', 'tbl_cobranza.Id   = tbl_ventas.Responsable_id_cobranza', 'left');


        $this->db->where('tbl_ventas.Id', $Id);
        $this->db->where('tbl_ventas.Visible', 1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }


//// VENTAS 	    | DESACTIVAR / ELIMINAR
	public function desactivarVenta()
    {
        $CI =& get_instance();
		$CI->load->database();
		
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $Id = $this->datosObtenidos->Id;

		$data = array(
                        
                'Visible' => 0,
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// SEGUIMIENTOS 	| OBTENER Listado
    public function obtener_seguimientos()
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

        $Id = $_GET["Id"];

        $this->db->select(' tbl_ventas_seguimiento.*,
                            tbl_usuarios.Nombre');
        
        $this->db->from('tbl_ventas_seguimiento');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_ventas_seguimiento.Usuario_id', 'left');

        if($_GET["Categoria_seguimiento"] != '0')
        {
            $this->db->where('tbl_ventas_seguimiento.Categoria_seguimiento', $_GET["Categoria_seguimiento"]);
        }

        $this->db->where('tbl_ventas_seguimiento.Venta_id', $Id);
        $this->db->where('tbl_ventas_seguimiento.Visible', 1);

        $this->db->order_by('tbl_ventas_seguimiento.Id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }


//// SEGUIMIENTOS 	| CARGAR O EDITAR
    public function cargar_seguimiento()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if (isset($this->datosObtenidos->Datos->Id)) {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $data = array(

            'Venta_id'              => $this->datosObtenidos->Venta_id,
            'Categoria_seguimiento' => $this->datosObtenidos->Categoria_seguimiento,
            'Descripcion'           => $this->datosObtenidos->Datos->Descripcion,
            'Usuario_id'            => $this->session->userdata('Id'),
            'Visible' =>            1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_seguimiento');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }


//// SEGUIMIENTOS 	| SUBIR FOTO 
	public function subirFotoSeguimiento()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'Archivo';
		
		if ($status != "error")
		{
			$config['upload_path'] = './uploads/imagenes';
			$config['allowed_types'] = 'jpg|jpeg|doc|docx|xlsx|pdf';
			$config['max_size'] = 0;
			$config['encrypt_name'] = TRUE;
	
			$this->load->library('upload', $config);
	
			if (!$this->upload->do_upload($file_element_name))
			{
				$status = 'error';
				$msg = $this->upload->display_errors('', '');
			}
			else
			{
				/// coloco el dato en la base de datos
					$Id = $_GET["Id"];
					
					$data = $this->upload->data();
					
					$file_info = $this->upload->data();
					$nombre_archivo = $file_info['file_name'];
					
					$data = array(    
						'Url_archivo' =>		$nombre_archivo,
					);

					$this->load->model('App_model');
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_seguimiento');
					
					// $file_id = $this->files_model->insert_file($data['file_name'], $_POST['title']);
					if($insert_id > 0)
					{
						$status = 1;
						$msg = "File successfully uploaded";
					}
					else
					{
						unlink($data['full_path']);
						$status = 0;
						$msg = "Something went wrong when saving the file, please try again.";
					}
			}
			@unlink($_FILES[$file_element_name]);
		}
		echo json_encode(array('status' => $status, 'Url_archivo' => $nombre_archivo));
    }

//// PLANIFICACIONES 	| OBTENER Listado
    public function obtener_listado_planificaciones()
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

        $this->db->select('*');
        
        $this->db->from('tbl_planificaciones');

        $this->db->where('Visible', 1);

        $this->db->order_by('Id', 'desc');
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }


//// PLANIFICACIONES 	| CARGAR O EDITAR
    public function cargar_planificacion()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if (isset($this->datosObtenidos->Data->Id)) {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $data = array(

            'Nombre_planificacion'  => $this->datosObtenidos->Data->Nombre_planificacion,
            'Fecha_inicio'          => $this->datosObtenidos->Data->Fecha_inicio,
            'Fecha_fin'             => $this->datosObtenidos->Data->Fecha_fin,
            'Descripcion'           => $this->datosObtenidos->Data->Descripcion,
            'Usuario_id'            => $this->session->userdata('Id'),
            'Visible'               => 1

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_planificaciones');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }

//// VENTAS 	    | LISTADO DASHBOARD
	public function obtener_listado_ventas_dashboard()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }

        $this->db->select(' tbl_ventas.*,
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',   'tbl_clientes.Id    = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',   'tbl_usuarios.Id    = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',   'tbl_empresas.Id    = tbl_ventas.Empresa_id', 'left');
        
        $this->db->where('tbl_ventas.Visible', 1);
        
        $this->db->where('tbl_ventas.Estado <', 10); /// EL ESTADO 10 ES LA VENTA QUE YA ESTA COBRADA Y FINALIZADA
        
        $this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        $this->db->limit(10);

        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }


//// CONSULTAR LAS VENTAS DEL AÑO
    public function obtener_cantVentas()
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
        $this->db->from('tbl_ventas');
        $this->db->where("DATE_FORMAT(Fecha_venta,'%Y')", $Anio);
        
        $query = $this->db->get();
        $total = $query->num_rows();

        echo json_encode($total);

    }

//// VENTAS 	    | OBTENER 
    public function obtener_productos_usados()
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

        $Id = $_GET["Id"];

        /// consultar en la tabla de tbl_ventas_productos, todos los productos que este vinculados a la Venta_id

        



        $this->db->select(' tbl_ventas.*,
                            tbl_vendedor.Nombre as Nombre_vendedor,
                            tbl_resp_1.Nombre as Nombre_resp_1,
                            tbl_resp_2.Nombre as Nombre_resp_2,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios as tbl_vendedor',  'tbl_vendedor.Id   = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',  'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_1',  'tbl_resp_1.Id   = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_usuarios as tbl_resp_2', 'tbl_resp_2.Id   = tbl_ventas.Vendedor_id', 'left');


        $this->db->where('tbl_ventas.Id', $Id);
        $this->db->where('tbl_ventas.Visible', 1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// PRODUCTOS VENDIDOS 	| CARGAR O EDITAR
    public function agregarProducto()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if (isset($this->datosObtenidos->Datos->Id)) {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        $Venta_id = $_GET["Id"];

        $Cantidad = null;
        if (isset($this->datosObtenidos->Datos->Cantidad)) {
            $Cantidad = $this->datosObtenidos->Datos->Cantidad;
        }

        $Tipo_produccion = 1;
        if (isset($this->datosObtenidos->Datos->Tipo_produccion)) {
            $Tipo_produccion = $this->datosObtenidos->Datos->Tipo_produccion;
        }

        $data = array(

            'Venta_id' =>               $Venta_id,
            'Producto_id' =>            $this->datosObtenidos->Datos->Producto_id,
            'Cantidad' =>               $Cantidad,
            'Precio_venta_producto' =>  $this->datosObtenidos->Datos->Precio_venta_producto,
            'Tipo_produccion' =>        $Tipo_produccion,
            'Observaciones' =>          $this->datosObtenidos->Datos->Observaciones,
            'S_1_Requerimientos' =>     $this->datosObtenidos->Datos->S_1_Requerimientos,
            'S_2_Requerimientos' =>     $this->datosObtenidos->Datos->S_2_Requerimientos,
            'S_3_Requerimientos' =>     $this->datosObtenidos->Datos->S_3_Requerimientos,
            'S_4_Requerimientos' =>     $this->datosObtenidos->Datos->S_4_Requerimientos,
            'S_5_Requerimientos' =>     $this->datosObtenidos->Datos->S_5_Requerimientos,
            'S_6_Requerimientos' =>     $this->datosObtenidos->Datos->S_6_Requerimientos,
            'S_7_Requerimientos' =>     $this->datosObtenidos->Datos->S_7_Requerimientos,
            'Usuario_id' =>             $this->session->userdata('Id'),
            

        );

        $this->load->model('App_model');
        /// ESTE MODO, ES MAS SIMPLE... AL CARGAR UN PRODUCTO PONE UNA CANTIDAD Y SE GESTIONAN SUS AVANCES EN CONJUNTO, Y NO UNO POR UNO 
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        
        /* 
        ESTE MODO... SIRVE PARA QUE UN PRODUCTO SE CARGUE MUCHAS VECES. DEPENDIENDO DE LA CANTIDAD

        if($Cantidad != null)
        {
            for ($i=0; $i < $Cantidad; $i++) 
            {  
                $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
            }
        }
        else
        {
            $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        } */
        
            
        if ($insert_id >= 0) {
            

            //// ACTUALIZA EL VALOR DEL PRODUCTO EN TBL_FABRICACIÓN
            $data = array(
                        
                'Precio_venta' => $this->datosObtenidos->Datos->Precio_venta_producto,
                'Ult_usuario' =>  $this->session->userdata('Id')
                 
            );
            /// 'Ultimo_editor_id' => 		$this->session->userdata('Id')

            $this->load->model('App_model');
            $actualizacion_producto_insert_id = $this->App_model->insertar($data, $this->datosObtenidos->Datos->Producto_id, 'tbl_fabricacion');

            echo json_encode(array(
                "Id" => $insert_id,
                "Actualizacion_producto_id" => $actualizacion_producto_insert_id
                ));

        } else {
            echo json_encode(array("Id" => 0,
                                "msg" => "No se cargo el producto"));
        }
    }
    

//// PRODUCTOS VENDIDOS 	| Obtener listado
    public function obtener_listado_de_productos_vendidos()
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

        $Id = $_GET["Id"];

        $this->db->select(' tbl_ventas_productos.*,
                            tbl_fabricacion.Nombre_producto,
                            tbl_fabricacion.Imagen,
                            tbl_fabricacion.Precio_venta,
                            tbl_fabricacion.Codigo_interno');
        
        $this->db->from('tbl_ventas_productos');

        $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');
        
        $this->db->where('tbl_ventas_productos.Venta_id', $Id);
        $this->db->where('tbl_ventas_productos.Visible', 1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// PRODUCTOS VENDIDOS 	| CAMBIAR ESTADO PRODUCTO
    public function cambiarEstadoProducto()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if (isset($this->datosObtenidos->Datos->Producto_id)) {
            $Id = $this->datosObtenidos->Datos->Producto_id;
        }

        //// seteando en que lugar va a cargar los datos
            if($this->datosObtenidos->Datos->Estado == 2)
            {
                $data = array(
                    'S_1_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_1_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            elseif($this->datosObtenidos->Datos->Estado == 3)
            {
                $data = array(
                    'S_2_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_2_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            elseif($this->datosObtenidos->Datos->Estado == 4)
            {
                $data = array(
                    'S_3_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_3_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            elseif($this->datosObtenidos->Datos->Estado == 5)
            {
                $data = array(
                    'S_4_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_4_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }  
            elseif($this->datosObtenidos->Datos->Estado == 6)
            {
                $data = array(
                    'S_5_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_5_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }  
            elseif($this->datosObtenidos->Datos->Estado == 7)
            {
                $data = array(
                    'S_6_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_6_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }  
            elseif($this->datosObtenidos->Datos->Estado == 8)
            {
                $data = array(
                    'S_7_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_7_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }
            
            elseif($this->datosObtenidos->Datos->Estado == 9)
            {
                $data = array(
                    'S_8_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_8_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }    


            elseif($this->datosObtenidos->Datos->Estado == 10)
            {
                $data = array(
                    'S_9_Fecha_finalizado' =>   $this->datosObtenidos->Datos->Fecha,
                    'S_9_Observaciones' =>      $this->datosObtenidos->Datos->Comentarios,
                    'Estado' =>                 $this->datosObtenidos->Datos->Estado,
                );
            }    
        

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }

//// PRODUCTOS VENDIDOS 	| ANULAR PRODUCTO
    public function anular_producto()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null; if (isset($this->datosObtenidos->Datos->Id)) { $Id = $this->datosObtenidos->Datos->Id; }
        
        
        

        /* CONTROLAR QUE LA CANTIDAD A ANULAR SEA LA QUE SE ESTA ANULANDO */ 

        
        //// si la cantidad es la misma, sigue la reasigna derecho
        if($this->datosObtenidos->Datos->Cantidad_anulada === $this->datosObtenidos->Datos->Cantidad)
        {
            $data = array(
                'Venta_id' =>   1,
            );

            $this->load->model('App_model');
            $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        }
        else 
        {
            /// si la cantidad es menos, resta la cantidad original, con la nueva
            // la que coloca en el campo de texto es para la venta original, el restante es para la venta nueva
                $data = array(
                    'Venta_id' =>   1,
                    'Cantidad' =>   $this->datosObtenidos->Datos->Cantidad_anulada,
                );

                $this->load->model('App_model');
                $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');

            /// Ahora el producto restante, lo paso a la venta de reserva
                /// genero un clon de los datos originales
                $cantidad_restante = $this->datosObtenidos->Datos->Cantidad - $this->datosObtenidos->Datos->Cantidad_anulada;

                $data = array(                    

                    'Cantidad' =>                   $cantidad_restante,  
                    'Estado' =>                     $this->datosObtenidos->Datos->Estado, 
                    'Observaciones' =>              $this->datosObtenidos->Datos->Observaciones,   
                    'Precio_venta_producto' =>      $this->datosObtenidos->Datos->Precio_venta_producto,   // controlar esta fila si esta funcionando bien
                    'Producto_id' =>                $this->datosObtenidos->Datos->Producto_id, 
                    'S_1_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_1_Fecha_finalizado,    
                    'S_1_Observaciones' =>          $this->datosObtenidos->Datos->S_1_Observaciones,   
                    'S_1_Requerimientos' =>         $this->datosObtenidos->Datos->S_1_Requerimientos,  
                    'S_2_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_2_Fecha_finalizado,    
                    'S_2_Observaciones' =>          $this->datosObtenidos->Datos->S_2_Observaciones,   
                    'S_2_Requerimientos' =>         $this->datosObtenidos->Datos->S_2_Requerimientos,  
                    'S_3_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_3_Fecha_finalizado,    
                    'S_3_Observaciones' =>          $this->datosObtenidos->Datos->S_3_Observaciones,   
                    'S_3_Requerimientos' =>         $this->datosObtenidos->Datos->S_3_Requerimientos,  
                    'S_4_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_4_Fecha_finalizado,    
                    'S_4_Observaciones' =>          $this->datosObtenidos->Datos->S_4_Observaciones,   
                    'S_4_Requerimientos' =>         $this->datosObtenidos->Datos->S_4_Requerimientos,  
                    'S_5_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_5_Fecha_finalizado,    
                    'S_5_Observaciones' =>          $this->datosObtenidos->Datos->S_5_Observaciones,   
                    'S_5_Requerimientos' =>         $this->datosObtenidos->Datos->S_5_Requerimientos,  
                    'S_6_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_6_Fecha_finalizado,    
                    'S_6_Observaciones' =>          $this->datosObtenidos->Datos->S_6_Observaciones,   
                    'S_6_Requerimientos' =>         $this->datosObtenidos->Datos->S_6_Requerimientos,  
                    'S_7_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_7_Fecha_finalizado,    
                    'S_7_Observaciones' =>          $this->datosObtenidos->Datos->S_7_Observaciones,   
                    'S_7_Requerimientos' =>         $this->datosObtenidos->Datos->S_7_Requerimientos,  
                    'S_8_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_8_Fecha_finalizado,    
                    'S_8_Observaciones' =>          $this->datosObtenidos->Datos->S_8_Observaciones,   
                    'S_8_Requerimientos' =>         $this->datosObtenidos->Datos->S_8_Requerimientos,  
                    'S_9_Fecha_finalizado' =>       $this->datosObtenidos->Datos->S_9_Fecha_finalizado,    
                    'S_9_Observaciones' =>          $this->datosObtenidos->Datos->S_9_Observaciones,   
                    'S_9_Requerimientos' =>         $this->datosObtenidos->Datos->S_9_Requerimientos,  
                    'Tipo_produccion' =>            $this->datosObtenidos->Datos->Tipo_produccion, 
                    'Venta_id' =>                   $this->datosObtenidos->Datos->Venta_id,
                    'Usuario_id' =>                 $this->session->userdata('Id'),
                );
        
                $this->load->model('App_model');
                $insert_id_nuevo = $this->App_model->insertar($data, null, 'tbl_ventas_productos');

        }
        
        
        
        
        
        
        
        
        
        
        if ($insert_id >= 0) 
        {
           //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
            if ($insert_id >=0 ) 
            {   
                
                $data = array(

                    'Venta_id' =>      $this->datosObtenidos->Datos->Venta_id,
                    'Categoria_seguimiento' =>  2,
                    'Descripcion' =>   'Producto Anulado: '.$this->datosObtenidos->Datos->Nombre_producto .' - '.$this->datosObtenidos->Datos->Comentarios_anulacion,
                    'Usuario_id' =>    $this->session->userdata('Id'),
                    'Visible' =>       1
                );

                $this->load->model('App_model');
                $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                    
                echo json_encode(array("Id" => $insert_id, "Seguimiento_id" => $insert_id_seguimiento));
            }
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// VENTAS 	    | DESACTIVAR / ELIMINAR
    public function eliminar_producto()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $Id = $this->datosObtenidos->Id;

        $data = array(
                        
                'Visible' => 0,
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }


//// PRODUCCION 	| Obtener listados
    public function obtener_productos_a_fabricar()
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

        $Estado = $this->datosObtenidos->Estado;
        $Planificacion_id   = $_GET["Planificacion_id"];

        $this->db->select(' tbl_ventas_productos.*,
                            tbl_fabricacion.Nombre_producto,
                            tbl_fabricacion.Imagen,
                            tbl_ventas.Identificador_venta,
                            tbl_ventas.Fecha_venta,
                            tbl_ventas.Responsable_id_planif_inicial,
                            tbl_ventas.Responsable_id_planif_final,
                            tbl_ventas.Prioritario,
                            tbl_ventas.Fecha_estimada_entrega,
                            tbl_clientes.Nombre_cliente'); 
        
        $this->db->from('tbl_ventas_productos');

        $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');
        $this->db->join('tbl_ventas',  'tbl_ventas.Id   = tbl_ventas_productos.Venta_id', 'left');
        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
        
        // Filtrar por Planificación
        if($Planificacion_id > 0)     { $this->db->where('tbl_ventas.Planificacion_id', $Planificacion_id); }

        $this->db->where('tbl_ventas_productos.Estado <', $Estado);
        $this->db->where('tbl_ventas_productos.Visible', 1);
        
        /// ACA VIENE EL FILTRO, SOLO VA A BUSCAR EN LAS VENTAS DONDE EL USUARIO LOGUEADO HAYA SIDO ELEGIDO.
        /* if ($this->session->userdata('Rol_acceso') < 4)
        {
            if($this->session->userdata('Id') == 3) /// ROL 3 es Franco
            {
                // Si es el diseñador gráfico quien podrá ver todo el proceso
            }
            elseif($this->session->userdata('Id') == 7) /// ROL 7 que es belen
            {
                // Si es el encargado de smart, puede ver solo productos de smart en producción.
                $this->db->where('tbl_fabricacion.Empresa_id', 3); // Empresa 3 es smart
            }
            else
            { 
                $this->db->group_start(); // Open bracket
                $this->db->where('tbl_ventas.Responsable_id_planif_final',      $this->session->userdata('Id'));
                $this->db->or_where('tbl_ventas.Responsable_id_planif_inicial', $this->session->userdata('Id'));
                $this->db->group_end(); // Close bracket  
             }
              
        } */

        $this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// COBRANZAS 	| Obtener listado resumen productos
    public function obtener_listado_resumen_productos()
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

        $Id = $_GET["Id"];

        /// ARMO UN ARRAY CON LOS PRODUCTOS VENDIDOS, AGRUPANDOLOS
            $this->db->select(' tbl_ventas_productos.*,
                                tbl_fabricacion.Nombre_producto,
                                tbl_fabricacion.Codigo_interno,
                                tbl_fabricacion.Imagen,
                                tbl_fabricacion.Id as Producto_id,
                                tbl_fabricacion.Precio_venta');
            
            $this->db->from('tbl_ventas_productos');

            $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');
            
            $this->db->where('tbl_ventas_productos.Venta_id', $Id);
            $this->db->where('tbl_ventas_productos.Visible', 1);
            $this->db->group_by('tbl_ventas_productos.Producto_id'); 
            
            $query = $this->db->get();
            $array_productos = $query->result_array();
        
        /// RECORRO EL ARRAY AGRUPANDOLOS, SUMO LAS CANTIDADES y al total lo multiplico por el valor de venta
            $Datos = array();
            
            foreach ($array_productos as $productos) 
            {
                
                $this->db->select('Id, Cantidad, Precio_venta_producto');
                $this->db->from('tbl_ventas_productos');
                $this->db->where('Producto_id', $productos["Producto_id"]);
                $this->db->where('Venta_id', $Id);
                $this->db->where('Visible', 1);

                /// con un poco mas de laburo puedo incluso traer un promedio de por donde van en su construcción
        
                $query = $this->db->get();
                //$Cantidad_metodo_uno_por_uno = $query->num_rows();
                $result_productos = $query->result_array();

                $cantidad = 0;
                $subtotal = 0;

                /// SUMANDO CANTIDADES ENCONTRADAS DEL MISMO PRODUCTO
                foreach ($result_productos as $producto_individual) {
                    
                    $cantidad =  $cantidad + $producto_individual["Cantidad"];
                    $subtotal =  $subtotal + $producto_individual["Precio_venta_producto"];
                }
                
                //$subtotal = $cantidad * $productos["Precio_venta_producto"]; 
                
                $datos_producto = array(
                                            'Codigo_interno' =>     $productos["Codigo_interno"],
                                            'Nombre_producto' =>    $productos["Nombre_producto"], 
                                            'Precio_venta' =>       $productos["Precio_venta_producto"],
                                            'Cantidad' =>           $cantidad,
                                            //'Cantidad_metodo_uno_por_uno' =>           $Cantidad_metodo_uno_por_uno,
                                            'Subtotal' =>           $subtotal,
                                        );

                array_push($Datos, $datos_producto);
            }

		echo json_encode($Datos);

    }


//// COBRANZAS 	| OBTENER LISTADO 
    public function obtener_listado_ventas_cobranzas()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        //Seguridad
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {
            exit("No coinciden los token");
        }
        
        ///Filtros
        $Empresa_id         = $_GET["Empresa_id"];
        $Vendedor_id        = $_GET["Vendedor_id"];
        $Cliente_id         = $_GET["Cliente_id"];
        $Planificacion_id   = $_GET["Planificacion_id"];
        $Estado             = $_GET["Estado"];

        $this->db->select(' tbl_ventas.*,
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_clientes.Nombre_cliente,
                            tbl_planificaciones.Nombre_planificacion');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',   'tbl_clientes.Id    = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',   'tbl_usuarios.Id    = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_planificaciones',   'tbl_planificaciones.Id    = tbl_ventas.Planificacion_id', 'left');

        if($Vendedor_id > 0)    { $this->db->where('tbl_ventas.Vendedor_id', $Vendedor_id); }
        if($Cliente_id > 0)     { $this->db->where('tbl_ventas.Cliente_id', $Cliente_id); }
        if($Empresa_id > 0)     { $this->db->where('tbl_ventas.Empresa_id', $Empresa_id); }
        if($Planificacion_id > 0)     { $this->db->where('tbl_ventas.Planificacion_id', $Planificacion_id); }

        $this->db->where('tbl_ventas.Visible', 1);
        
        if($Estado == 10) /// esto es porque si necesito la lista completa sin discriminar por estado, Mando un valor 4 al estado
        {
            $this->db->where('tbl_ventas.Estado', 10);
        }
        else {
            $this->db->where('tbl_ventas.Estado <', 10);
        }
        
        $this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        
        $query = $this->db->get();
        $array_ventas = $query->result_array();

        $Datos = array();
        $Total_monto_venta = 0;
        $Total_cobros_IMU = 0;
        $Total_cobrosSJunior = 0;
        $Total_saldo = 0;


        /// RECORRER CADA VENTA
        foreach ($array_ventas as $venta) {

            $Venta_id = $venta["Id"];
    
            /// BUSCAR PAGOS

                $this->db->select('Monto, Fecha_ejecutado, Empresa_id');
                $this->db->from('tbl_cobros');
                $this->db->where('Origen_movimiento', "Ventas");
                $this->db->where('Fila_movimiento', $Venta_id);
                $this->db->where('Visible', 1);
                $this->db->order_by('Id', 'desc');
                $query = $this->db->get();
                $array_pagos = $query->result_array();

                // Pagos IMU
                $Cobros_IMU = 0;
                // Pagos Junior
                $CobrosSJunior = 0;
                // Ultimo Pago Registrado
                $Fecha_ult_cobro = null;

                foreach ($array_pagos as $pago) {

                    if($pago["Empresa_id"] == 1){
                        $Cobros_IMU = $Cobros_IMU + $pago["Monto"];
                    }
                    else if($pago["Empresa_id"] == 2){
                        $CobrosSJunior = $CobrosSJunior + $pago["Monto"];
                    }
                        
                    $Fecha_ult_cobro = $pago["Fecha_ejecutado"];
                }


            /// PRODUCTOS VENDIDOS
                    
                $Monto_ventas = $venta["Valor_logistica"] + $venta["Valor_instalacion"] + $venta["Recargo"] - $venta["Descuento"];
                
                // Productos propios
                    $this->db->select('Cantidad, Precio_venta_producto');
                    $this->db->from('tbl_ventas_productos');
                    $this->db->where('Venta_id', $Venta_id);
                    $this->db->where('Visible', 1);
                    
                    
                    $query = $this->db->get();
                    $array_productos_venta = $query->result_array();

                    foreach ($array_productos_venta as $producto) {

                        $Monto_ventas = $Monto_ventas + ( $producto["Precio_venta_producto"] * $producto["Cantidad"]);

                    }

                // Productos reventa

                    // Armando array de productos
                    $this->db->select(' tbl_stock_movimientos.Stock_id,
                                        tbl_stock.Id as Stock_id');

                    $this->db->from('tbl_stock_movimientos');

                    $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_movimientos.Stock_id', 'left');
                    $this->db->where('tbl_stock_movimientos.Proceso_id', $Venta_id);
                    $this->db->where('tbl_stock_movimientos.Modulo', 'Ventas');
                    $this->db->where('tbl_stock_movimientos.Visible', 1);
                    $this->db->group_by('tbl_stock.Id');

                    $query = $this->db->get();
                    $array_productos_reventa = $query->result_array();

                    // Total productos reventa
                    $Total_prod_reventa = 0;
                    
                    /// Sumando movimientos
                    foreach ($array_productos_reventa as $producto) 
                    {
                        $cantidad = 0;
                        $monto_producto_reventa = 0;

                        $this->db->select('Cantidad, Tipo_movimiento, Precio_venta_producto');
                        $this->db->from('tbl_stock_movimientos');
                        $this->db->where('Modulo', 'Ventas');
                        $this->db->where('Proceso_id', $Venta_id);
                        $this->db->where('Stock_id', $producto["Stock_id"]);
                        $this->db->where('Visible', 1);
                        $this->db->order_by("Id", "asc");

                        $query = $this->db->get();
                        $array_movimientos_producto = $query->result_array();

                        foreach ($array_movimientos_producto as $movimiento) 
                        {   
                            $movCantidad = 0;
                            $precioVentaProducto = 0;

                            if (is_numeric($movimiento["Cantidad"])) { $movCantidad = $movimiento["Cantidad"]; } 
                            if (is_numeric($movimiento["Precio_venta_producto"])) { $precioVentaProducto = $movimiento["Precio_venta_producto"]; } 

                            // El 1 resta del stock, pero suma en la venta ya que son productos que salen de stock para irse en la venta al cliente
                            if($movimiento["Tipo_movimiento"] == 1 ){
                                
                                
                                $cantidad = $cantidad + $movCantidad;
                            }
                            else {
                                $cantidad = $cantidad - $movCantidad;
                            }

                            $monto_producto_reventa = $cantidad * $precioVentaProducto * (-1);
                        }

                        $Total_prod_reventa = $Total_prod_reventa + $monto_producto_reventa;
                    }
                    
                    
            /// Calculando el total de las ventas
                $Monto_ventas = $Monto_ventas + $Total_prod_reventa;

                $Saldo = $Monto_ventas - $Cobros_IMU - $CobrosSJunior;

                $Total_monto_venta =    $Total_monto_venta + $Monto_ventas;
                $Total_cobros_IMU =     $Total_cobros_IMU + $Cobros_IMU;
                $Total_cobrosSJunior =  $Total_cobrosSJunior + $CobrosSJunior;
                $Total_saldo =          $Total_saldo + $Saldo;



            /// ARMANDO ARRAY FINAL
            $datosVenta = array(
                "Id"                    => $Venta_id,
                "Identificador_venta"   => $venta["Identificador_venta"],
                "Nombre_cliente"        => $venta["Nombre_cliente"],
                "Estado"                => $venta["Estado"],

                "Monto_venta"           => $Monto_ventas,
                "Cobros_IMU"            => $Cobros_IMU,
                "CobrosSJunior"         => $CobrosSJunior,
                "Saldo"                 => $Saldo,

                "Fecha_ult_cobro"       => $Fecha_ult_cobro,
                "Fecha_venta"           => $venta["Fecha_venta"],
                "Fecha_entregado"       => $venta["Fecha_entregado"],
                "Nombre_vendedor"       => $venta["Nombre_vendedor"],
                "Total_prod_reventa" => $Total_prod_reventa,
            );

            array_push($Datos, $datosVenta);
        }
        
        $Datos_generales = array(
                        "Datos" => $Datos,
                        "Total_monto_venta" => $Total_monto_venta,
                        "Total_cobros_IMU" => $Total_cobros_IMU,
                        "Total_cobrosSJunior" => $Total_cobrosSJunior,
                        "Total_saldo" => $Total_saldo,
        );
        echo json_encode($Datos_generales);
        
    }


//// COBRANZAS  | OBTENER MOVIMIENTOS
    public function obtener_listado_cobros()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance(); 
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $Desde = $this->datosObtenidos->Fecha_inicio;
		$Hasta = $this->datosObtenidos->Fecha_fin;

        if($Desde == NULL) 
		{ 
			$fecha = date('Y-m-d');
			$Desde = strtotime ( '-30 day' , strtotime ( $fecha ) ) ;
			$Desde = date ( 'Y-m-d' , $Desde );
		}
		if($Hasta == NULL) 
		{ 
			$Hasta = date('Y-m-d');
		}


        //// 
        $this->db->select(' tbl_cobros.*, 
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_clientes.Nombre_cliente,
                            tbl_ventas.Id as Venta_id,
                            tbl_ventas.Identificador_venta');

        $this->db->from('tbl_cobros');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_cobros.Usuario_id', 'left');
        $this->db->join('tbl_ventas', 'tbl_ventas.Id = tbl_cobros.Fila_movimiento', 'left');
        $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_ventas.Cliente_id', 'left');

        $this->db->where('tbl_cobros.Visible', 1);
        $this->db->where('tbl_cobros.Origen_movimiento', 'Ventas');
        
        $this->db->where("DATE_FORMAT(tbl_cobros.Fecha_ejecutado,'%Y-%m-%d') >=", $Desde);
        $this->db->where("DATE_FORMAT(tbl_cobros.Fecha_ejecutado,'%Y-%m-%d') <=", $Hasta);
            
        $this->db->order_by("tbl_cobros.Id", "desc");

        $query = $this->db->get();
        $array_cobros = $query->result_array();
        
        $Datos = array();
        
        /////  SUMAR MONTOS
        $Cobros_IMU = 0;
        $Cobro_sJunior = 0;
        

        foreach ($array_cobros as $cobro) 
        {   
            $Cobro_columna_imu = 0;
            $Cobro_columna_sJunior = 0;

            if($cobro["Empresa_id"] == 1)
            {
                $Cobros_IMU = $Cobros_IMU + $cobro["Monto"];
                $Cobro_columna_imu = $cobro["Monto"];  
            }
            else if($cobro["Empresa_id"] == 2)
            {
                $Cobro_sJunior = $Cobro_sJunior + $cobro["Monto"];
                $Cobro_columna_sJunior = $cobro["Monto"];
            }

            $Total = $Cobro_columna_imu + $Cobro_columna_sJunior;

            $Datos_venta = array(   "Venta_id"          => $cobro["Venta_id"],
                                    "Identificador_venta"     => $cobro["Identificador_venta"],
                                    "Nombre_cliente"     => $cobro["Nombre_cliente"],
                                    "Cobro_IMU"         => $Cobro_columna_imu,
                                    "Cobro_sJunior"     => $Cobro_columna_sJunior,
                                    "Total"             => $Total,
                                    "Fecha_ejecutado"   => $cobro["Fecha_ejecutado"],
                                    "Modalidad_pago"    => $cobro["Modalidad_pago"],
                                    "Observaciones"     => $cobro["Observaciones"],
                                    "Nombre_vendedor"   => $cobro["Nombre_vendedor"]
                    );
        
            array_push($Datos, $Datos_venta);
        
        }

        $Total_tabla = $Cobros_IMU + $Cobro_sJunior;
        
        echo json_encode(
            array(
                'Datos'         => $Datos,
                'Cobros_IMU'    => $Cobros_IMU,
                'Cobros_sJunior' => $Cobro_sJunior,
                'Total' => $Total_tabla,
            )
        );
    }

    
///// fin documento
}