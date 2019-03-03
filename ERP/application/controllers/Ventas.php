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

            if ($this->session->userdata('Rol_acceso') > 1) {
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

            if ($this->session->userdata('Rol_acceso') > 1) 
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
        $Empresa_id = $_GET["Empresa_id"];
        $Vendedor_id  = $_GET["Vendedor_id"];
        $Cliente_id  = $_GET["Cliente_id"]; 

        $this->db->select(' tbl_ventas.*,
                            tbl_usuarios.Nombre as Nombre_vendedor,
                            tbl_empresas.Nombre_empresa,
                            tbl_clientes.Nombre_cliente');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',   'tbl_clientes.Id    = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_usuarios',   'tbl_usuarios.Id    = tbl_ventas.Vendedor_id', 'left');
        $this->db->join('tbl_empresas',   'tbl_empresas.Id    = tbl_ventas.Empresa_id', 'left');

        if($Vendedor_id > 0)    { $this->db->where('tbl_ventas.Vendedor_id', $Vendedor_id); }
        if($Cliente_id > 0)     { $this->db->where('tbl_ventas.Cliente_id', $Cliente_id); }
        if($Empresa_id > 0)     { $this->db->where('tbl_ventas.Empresa_id', $Empresa_id); }

        $this->db->where('tbl_ventas.Visible', 1);
        
        if($_GET["Estado"] == 10) /// esto es porque si necesito la lista completa sin discriminar por estado, Mando un valor 4 al estado
        {
            $this->db->where('tbl_ventas.Estado', 10);
        }
        else {
            $this->db->where('tbl_ventas.Estado <', 10);
        }
        
		$this->db->order_by('tbl_ventas.Fecha_estimada_entrega', 'asc');
        
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
            else {   $descripcion_seguimiento = "<b>Se actualizaron los datos en la ficha de esta venta.</b>";
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
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
        }

        ///// ANALIZAR ACA SI PONGO DIRECTAMENTE EL ID A MANO O LE BUSCO OTRA MANERA, POR EL MOMENTO A MANO VA A FUNCIONAR BIEN, SI ALGUN DIA TIENEN MAS DE UN RESPONSABLE, LO TENDRAN QUE ELEGIR DE UN LISTADO
        
        $estado = $this->datosObtenidos->Datos->Estado + 1;    
        
        if($estado == 10){
            $Fecha_finalizada = date("Y-m-d");
        }
        else { $Fecha_finalizada = null; }
        
        $data = array(
                        
                        'Fecha_finalizada'  => $Fecha_finalizada,            
                        'Estado'            => $estado,                
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas');
                
        //// si cargo bien esto, toma el id de la orden y la carga en la tbl_ordentrabajo_seguimietno
        if ($insert_id >=0 ) 
        {   
            
            if($estado == 2)  {  $descripcion_seguimiento = "<b>Avanzó el lote a la estación de Proceso de Materiales.</b>";  }
            if($estado == 3)  {  $descripcion_seguimiento = "<b>Avanzó el lote a la estación de Soldadura.</b>";  }
            if($estado == 4)  {  $descripcion_seguimiento = "<b>Avanzó el lote a la estación de Pintura.</b>";  }
            if($estado == 5)  {  $descripcion_seguimiento = "<b>Avanzó el lote a la estación de Rotulación.</b>";  }
            if($estado == 6)  {  $descripcion_seguimiento = "<b>Avanzó el lote a la estación de Empaque.</b>";  }
            if($estado == 7)  {  $descripcion_seguimiento = "<b>Finalizó el proceso de producción de este lote. Continúa en Logística.</b>";  }
            if($estado == 8)  {  $descripcion_seguimiento = "<b>Logística finalizada.</b>";  }
            if($estado == 9)  {  $descripcion_seguimiento = "<b>Instalación finalizada.</b>";  }
            if($estado == 10) {  $descripcion_seguimiento = "<b>Cobranza finalizada.</b>";  }

            
            $data = array(

            'Venta_id' =>       $insert_id,
            'Categoria_seguimiento' => 2,
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
    
//// VENTAS 	    | OBTENER DATOS DE UNA VENTA
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
                            tbl_clientes.Nombre_cliente,
                            tbl_vendedor.Nombre as Nombre_vendedor,
                            tbl_resp_1.Nombre as Nombre_resp_1,
                            tbl_resp_2.Nombre as Nombre_resp_2,
                            tbl_logistica.Nombre as Nombre_logistica,
                            tbl_instalacion.Nombre as Nombre_instalacion,
                            tbl_cobranza.Nombre as Nombre_cobranza');
        
        $this->db->from('tbl_ventas');

        $this->db->join('tbl_clientes',  'tbl_clientes.Id   = tbl_ventas.Cliente_id', 'left');
        $this->db->join('tbl_empresas',  'tbl_empresas.Id   = tbl_ventas.Empresa_id', 'left');
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

        $data = array(

            'Venta_id' =>               $Venta_id,
            'Producto_id' =>            $this->datosObtenidos->Datos->Producto_id,
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
        
        for ($i=0; $i < $Cantidad; $i++) 
        {  
            $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        }
            
        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
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
                            tbl_fabricacion.Imagen');
        
        $this->db->from('tbl_ventas_productos');

        $this->db->join('tbl_fabricacion',  'tbl_fabricacion.Id   = tbl_ventas_productos.Producto_id', 'left');

        $this->db->where('tbl_ventas_productos.Venta_id', $Id);
        $this->db->where('tbl_ventas_productos.Visible', 1);
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);

    }

//// SEGUIMIENTOS 	| CARGAR O EDITAR
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
        

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }
///// fin documento
}
