<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{

//// STOCK          | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 7 || $this->session->userdata('Id') == 33 || $this->session->userdata('Id') == 32) {
                $this->load->view('stock_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// STOCK          | VISTA | DATOS
    public function movimientos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 7 || $this->session->userdata('Id') == 3 || $this->session->userdata('Id') == 33 || $this->session->userdata('Id') == 32) 
            {
                $this->load->view('stock_movimientos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// STOCK          | VISTA | PRODUCTOS DE REVENTA | DATOS
    public function pruductosdereventa()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 7 || $this->session->userdata('Id') == 3 || $this->session->userdata('Id') == 33) 
            {
                $this->load->view('stock_productos_reventa');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }


//// STOCK          | VISTA | PAÑOL | DATOS
    public function panol()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3 || $this->session->userdata('Id') == 7 || $this->session->userdata('Id') == 33) 
            {
                $this->load->view('stock_panol');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// STOCK 	        | OBTENER LISTADO STOCK
	public function obtener_listado_de_stock()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        //$estado = $_GET["estado"];
        $tipo = $_GET["tipo"];
        $categoria = $_GET["categoria"];


		$this->db->select('	tbl_stock.*,
                            tbl_stock_categorias.Nombre_categoria,
                            tbl_stock_movimientos.Fecha_hora');
        $this->db->from('tbl_stock');
        $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id','left');
        $this->db->join('tbl_stock_movimientos', 'tbl_stock_movimientos.Id = tbl_stock.Ult_modificacion_id', 'left');

        //$this->db->where('tbl_stock.Visible', 1);

        if($categoria > 0) { $this->db->where('tbl_stock.Categoria_id', $categoria); }
        if( $tipo > 0 )    { $this->db->where('tbl_stock.Tipo', $tipo); }

		$this->db->order_by("tbl_stock_movimientos.Fecha_hora", "desc");
        $this->db->order_by("tbl_stock.Nombre_item", "asc");
        $query = $this->db->get();
		$array_stock = $query->result_array();

        $Datos = array();
		//// SI ES IGUAL A 3, O SEA CATEGORIA PRODUCTOS DE REVENTA, REALIZA UN PROCESO ADICIONAL BUSCANDO PEDIDOS DE VENTAS
        if($tipo == 3)
        {
            /// Sumando movimientos
            foreach ($array_stock as $producto) 
            {
                $cantidad = 0;

                $this->db->select('Cantidad, Tipo_movimiento');

                $this->db->from('tbl_stock_movimientos');

                $this->db->where('Stock_id', $producto["Id"]);
                $this->db->where('Modulo', 'Ventas');
                $this->db->where('Entregado', 0);
                $this->db->where('Visible', 1);

                $query = $this->db->get();
                $array_movimientos_producto = $query->result_array();
                
                foreach ($array_movimientos_producto as $movimiento) 
                {   
                    // El 1 resta del stock, pero suma en la venta ya que son productos que salen de stock para irse en la venta al cliente
                    if($movimiento["Tipo_movimiento"] == 1 ){
                        $cantidad = $cantidad + $movimiento["Cantidad"];
                    }
                    else {
                        $cantidad = $cantidad - $movimiento["Cantidad"];
                    }
                }

                $producto["Cant_pedido"] = $cantidad * (-1); // cambio el signo necesariamente porque lo que hago con respecto al stock es inverso
                
                array_push($Datos, $producto );
            }
            

            echo json_encode($Datos);
        }

        else
        {
            echo json_encode($array_stock);
        }
    }
    
//// STOCK 	        | OBTENER Datos del item
    public function obtener_datos_item()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select('	tbl_stock.*,
                            tbl_stock_categorias.Nombre_categoria,
                            tbl_stock_categorias.Id as Categoria_id');
        $this->db->from('tbl_stock');
        $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id', 'left');
        $this->db->order_by("Nombre_item", "asc");
        $this->db->where('tbl_stock.Id', $Id);
        //$this->db->where('tbl_stock.Visible', 1);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
    }


//// STOCK 	        | DESACTIVAR 
	public function desactivar_producto()
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }



//// MOVIMIENTOS 	| OBTENER MOVIMIENTOS DE UN STOCK  ¡¡OBSOLETO!! 
    public function obtener_movimientos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select(  'tbl_stock_movimientos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_stock_movimientos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_stock_movimientos.Usuario_id', 'left');
        
        $this->db->where('Stock_id', $Id);
        $this->db->order_by("Id", "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// MOVIMIENTOS 	| OBTENER MOVIMIENTOS DE UN STOCK v2
    public function obtener_movimientos_v2()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];
        $Tipo_movimiento = $this->datosObtenidos->Tipo_movimiento; // 1 Equivale a compras, 2 a Ordenes de trabajo
        
        ////LA CONSULTA DEBE VARIAR SEGUN DE DONDE SE PIDA EL DATO

        if($Tipo_movimiento > 0) {    /// consulta desde ORDENES DE TRABAJO

            $this->db->select(' tbl_stock_movimientos.*,
                                tbl_stock.Nombre_item,
                                tbl_stock.Precio_costo');
        
            $this->db->from('tbl_stock_movimientos');
            
            $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_movimientos.Stock_id', 'left');
            
            $this->db->where('tbl_stock_movimientos.Tipo_movimiento', $Tipo_movimiento);
            $this->db->where('tbl_stock_movimientos.Proceso_id', $Id);
            $this->db->where('tbl_stock_movimientos.Visible', 1);

            $query = $this->db->get();
            $result = $query->result_array();
        }


        else {                      /// consulta desde MOVIMIENTOS STOCK

            //---
            $this->db->select(' tbl_stock_movimientos.*,
                                tbl_stock.Nombre_item');
            $this->db->from('tbl_stock_movimientos');
            $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_movimientos.Stock_id', 'left');
            $this->db->where('tbl_stock_movimientos.Stock_id', $Id);
            $this->db->order_by("tbl_stock_movimientos.Fecha", "desc");
            $query = $this->db->get();
            $result = $query->result_array();

        }

        echo json_encode($result);     
            
    }

//// MOVIMIENTOS 	| CARGAR NUEVO MOVIMIENTO V2
    public function cargar_movimiento()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) {  exit("No coinciden los token"); }

        $Stock_id = $this->datosObtenidos->Id;
        $Cantidad = $this->datosObtenidos->Cantidad;
        $Precio_costo = $this->datosObtenidos->Precio_costo;
        $Descripcion = $this->datosObtenidos->Descripcion;

        $Proceso_id = $this->datosObtenidos->Proceso_id;                // Se refiere al Id, de la orden de trabajo, o de la Compra
        $Tipo_movimiento = $this->datosObtenidos->Tipo_movimiento;      // Recibe un Número: 1 Equivale a compras, 2 a Ordenes de trabajo
        
        $data = array(

            'Stock_id'          => $Stock_id,
            'Cantidad'          => $Cantidad,
            'Descripcion'       => $Descripcion,
            'Usuario_id'        => $this->session->userdata('Id'),
            'Proceso_id'        => $Proceso_id,
            'Tipo_movimiento'   => $Tipo_movimiento,
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, Null, 'tbl_stock_movimientos');

        if ($insert_id >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_stock, con el calculo de stock actual y el Id de la última actualización
        {
            /// consultar stock en cuestión y obtener la cantidad hasta ese momento
                $this->db->select('Cant_actual');
                $this->db->from('tbl_stock');
                $this->db->where('Id', $Stock_id);
                $query = $this->db->get();
                $result = $query->result_array();
                
                if ($query->num_rows() > 0) // si encontro alguna fila previa, hace el calculo
                {
                    /// SEGUN EL TIPO DE MOVIMIENTO VA A SUMAR O RESTAR LA CANTIDAD INDICADA
                    if      ($Tipo_movimiento == 1)    {$cant_actual = $result[0]["Cant_actual"] + $Cantidad;} // suma cantidad7
                    else if ($Tipo_movimiento == null) {$cant_actual = $result[0]["Cant_actual"] + $Cantidad;} // SI VIENE NULO, TOMA EL SIGNO QUE TRAE LA VARIABLE

                    else                                { $cant_actual = $result[0]["Cant_actual"] - $Cantidad;} // resta cantidad
                }
                else // de lo contrario la cantidad actual será la primera reportada
                {
                    $cant_actual = $Cantidad;
                }

                    //////----------------------////////
                    // Si cantidad actual es menor a stock ideal, debería poner Stock bajo en algun campo, para poder filtrar más facilmente. y hacer listas solo de productos bajos de stock
                    //////----------------------////////
    

            /// Actualizo tbl_stock con los datos nuevos
                $data = array(
                    'Ult_modificacion_id' => $insert_id,
                    'Cant_actual' => $cant_actual,
                    'Precio_costo' => $Precio_costo,
                );

                $this->load->model('App_model');
                $insert_id_2 = $this->App_model->insertar($data, $Stock_id, 'tbl_stock');

                if ($insert_id_2 >= 0) {
                    echo json_encode(array("Id" => $insert_id));
                } else {
                    echo json_encode(array("Id" => 0));
                }
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// MOVIMIENTOS 	| OBTENER ULTIMOS MOVIMIENTOS REGISTRADOS DE STOCK
    public function obtener_ultimos_movimientos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //$Id = $_GET["Id"];
        $limit = 25;
        $start = 0;
        
        $this->db->select(  'tbl_stock_movimientos.*,
                            tbl_usuarios.Nombre,
                            tbl_stock.Nombre_item');
        $this->db->from('tbl_stock_movimientos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_stock_movimientos.Usuario_id', 'left');
        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_movimientos.Stock_id', 'left');
        
        //$this->db->where('Stock_id', $Id);
        $this->db->where('tbl_stock_movimientos.Visible', 1);
        $this->db->order_by("tbl_stock_movimientos.Id", "desc");
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// MOVIMIENTOS    | ACTUALIZAR DESCRIPCIÓN
	public function actualizarMovimiento()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		if(isset($this->datosObtenidos->Data->Id))
        {
            $Id = $this->datosObtenidos->Data->Id;
		}

		$data = array(
					'Descripcion' => 		$this->datosObtenidos->Data->Descripcion,
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock_movimientos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// STOCK 	        | CARGAR O EDITAR ITEM DEL STOCK
	public function cargar_stock_item()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        if($this->datosObtenidos->Data->Nombre_item == null){
            echo json_encode(array(
                                "Id" => 0,
                                "err" => 'No se cargó el nombre del producto')); 
                                exit;
        }

        if(isset($this->datosObtenidos->Data->Id)) { $Id = $this->datosObtenidos->Data->Id; }
        if(isset($this->datosObtenidos->Data->Precio_venta)) { $Precio_venta = $this->datosObtenidos->Data->Precio_venta; }
        if(isset($this->datosObtenidos->Data->Precio_costo)) { $Precio_costo = $this->datosObtenidos->Data->Precio_costo; }
	
		$data = array(
                        
                    'Tipo' => 		        $this->datosObtenidos->Tipo,
                    'Nombre_item' => 		$this->datosObtenidos->Data->Nombre_item,
                    'Categoria_id' => 		$this->datosObtenidos->Data->Categoria_id,
                    'Descripcion' => 		$this->datosObtenidos->Data->Descripcion,
                    'Unidad_medida'=>       $this->datosObtenidos->Data->Unidad_medida,
                    'Cant_comercial'=>       $this->datosObtenidos->Data->Cant_comercial,
                    'Cant_actual' => 		$this->datosObtenidos->Data->Cant_actual,
					'Cant_ideal' => 		$this->datosObtenidos->Data->Cant_ideal,
					'Precio_costo' => 		$Precio_costo,
                    'Precio_venta' => 		$Precio_venta,
                    'Ult_usuario_id' =>     $this->session->userdata('Id'),
                     
                );
                /// 'Ultimo_editor_id' => 		$this->session->userdata('Id')

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// STOCK 	        | SUBIR FOTO
	public function subirFotoStock()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'Archivo';
		
		if ($status != "error")
		{
			$config['upload_path'] = './uploads/imagenes';
			$config['allowed_types'] = 'gif|jpg|jpeg|png';
			$config['max_size'] = 1024 * 8;
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
					$nombre_imagen = $file_info['file_name'];
					
					$data = array(    
						'Imagen' =>		$nombre_imagen,
					);

					$this->load->model('App_model');
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock');
					
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
		echo json_encode(array('status' => $status, 'Imagen' => $nombre_imagen));
    }
    

//// CATEGORIAS 	| OBTENER 
	public function obtener_categorias()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

		$this->db->select('*');
        $this->db->from('tbl_stock_categorias');
        
        if($_GET["categoria_tipo"] == 3){
            $this->db->where('Tipo', $_GET["categoria_tipo"]);
        }
        
		$this->db->order_by("Nombre_categoria", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// CATEGORIAS 	| CARGAR O EDITAR
    public function cargar_categoria()
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

        $Tipo = null;
        if (isset($this->datosObtenidos->Data->Tipo)) {
            $Tipo = $this->datosObtenidos->Data->Tipo;
        }

        $data = array(

            'Nombre_categoria' => $this->datosObtenidos->Data->Nombre_categoria,
            'Descripcion' => $this->datosObtenidos->Data->Descripcion,
            'Tipo' => $Tipo,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_stock_categorias');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }


////// ----------------------------------- FUNCIONES PARA ASIGNAR PROVEEDOR
    
//// VINCULO PROVEEDOR | lista de proveedores no asignados
    
    public function obtener_listado_de_proveedores_no_asignados()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $Stock_id = $_GET["Id"];

        /// CREANDO LISTADO DE PROVEEDORES VINCULADOS
            $this->db->select('	tbl_proveedores.Id');
                                
            $this->db->from('tbl_stock_vinculo_proveedor');

            $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_stock_vinculo_proveedor.Proveedor_id', 'left');
            $this->db->where('tbl_stock_vinculo_proveedor.Visible', 1);
            $this->db->where('tbl_stock_vinculo_proveedor.Stock_id', $Stock_id);

            $query = $this->db->get();
            $arrayProveedoresVinculados = $query->result_array();

        /// Listado completo de proveedores
            $this->db->select('Id, Nombre_proveedor');
            $this->db->from('tbl_proveedores');
            $this->db->where('Visible', 1);
            $this->db->order_by("Nombre_proveedor", "asc");
            $query = $this->db->get();
            $arrayProveedores = $query->result_array();


            
        //// ELIMINANDO LOS PROVEEDORES ASIGNADOS DEL LISTADO COMPLETO
            foreach ($arrayProveedoresVinculados as $proveedorVinculado) // voy vinculado por vinculado
            {
                foreach ($arrayProveedores as $proveedor=>$valorProveedor) /// voy proveedor por proveedor $usuario=>$uservalue
                {
                    if ($proveedorVinculado['Id'] == $valorProveedor['Id']) /// comparo el Id del proveedor vinculado, con el proveedor que loopea en el momento
                    {
                        unset($arrayProveedores[$proveedor]); /// tengo q pasar el Id a eliminar
                    }
                }
            }

        echo json_encode($arrayProveedores);

    }


//// VINCULO PROVEEDOR | lista de proveedores asignados
    public function obtener_listado_de_proveedores_asignados()
    {
			
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
		$token = @$CI->db->token;

        $Stock_id = $_GET["Id"];

        ///BUSCAR A QUE CATEGORIA PERTENECE ESTE STOCK
        $this->db->select('	tbl_stock_categorias.Id');
        $this->db->from('tbl_stock');
        $this->db->join('tbl_stock_categorias', 'tbl_stock_categorias.Id = tbl_stock.Categoria_id', 'left');
        $this->db->where('tbl_stock.Id', $Stock_id);

        $query = $this->db->get();
        $result = $query->result_array();

        $Categoria_id = $result[0]["Id"];

        // BUSCAR PROVEEDORES QUE TENGAN QUE VER CON ESTA CATEGORIA
        $this->db->select('	tbl_proveedor_vinculo_categorias_producto.Id,
                            tbl_proveedores.Nombre_proveedor,
                            tbl_proveedores.Id as Proveedor_id');

        $this->db->from('tbl_proveedor_vinculo_categorias_producto'); 

        $this->db->join('tbl_proveedores', 'tbl_proveedores.Id = tbl_proveedor_vinculo_categorias_producto.Proveedor_id', 'left');

        $this->db->where('tbl_proveedor_vinculo_categorias_producto.Visible', 1);
        $this->db->where('tbl_proveedor_vinculo_categorias_producto.Categoria_id', $Categoria_id);

        $this->db->group_by('tbl_proveedores.Id');


        $this->db->order_by("tbl_proveedores.Nombre_proveedor", "asc");
        
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// VINCULO PROVEEDOR | vincular proveedor a producto
    public function Vincular_producto_proveedor()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        /// si existe pero no se veia, es posible que estuviera borrado, armar función que vuelva a 1 el campo VISIBLE

        $Proveedor_id   = $this->datosObtenidos->Proveedor_id;
        $Stock_id       = $this->datosObtenidos->Stock_id;

        $data = array(
                    'Proveedor_id'  => $Proveedor_id,
                    'Stock_id'      => $Stock_id,
                    'Visible'       => 1,
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, null, 'tbl_stock_vinculo_proveedor');

                
        if ($insert_id >=0 ) 
        {
            echo json_encode(array("Id_Vinc" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id_Vinc" => "failed"));
        }
    }

//// VINCULO PROVEEDOR | desvincular proveedor a producto
    public function Desvincular_producto_proveedor()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $Id_stock = $this->datosObtenidos->Id;

        $Proveedor_id = $this->datosObtenidos->Proveedor_id;
        $Stock_id = $this->datosObtenidos->Stock_id;

        $data = array(
                    
            'Visible' => 0,
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Stock_id, 'tbl_stock_vinculo_proveedor');

                
        if ($insert_id >=0 ) 
        {
            echo json_encode(array("Id_Vinc" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id_Vinc" => "failed"));
        }
    }
    
//// PRODUCTOS DE REVENTA 	| OBTENER MOVIMIENTOS
    public function obtener_movimientos_ventas_prod_reventa()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];
        
        // Armando array de productos
            $this->db->select(' tbl_stock_movimientos.*,
                                tbl_stock.Imagen,
                                tbl_stock.Nombre_item');
        
            $this->db->from('tbl_stock_movimientos');
            
            $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_stock_movimientos.Stock_id', 'left');
            $this->db->where('tbl_stock_movimientos.Proceso_id', $Id);
            $this->db->where('tbl_stock_movimientos.Visible', 1);
            $this->db->where('Modulo', "Ventas");
            $this->db->group_by('tbl_stock.Id');

            $query = $this->db->get();
            $array_productos = $query->result_array();
        
            $Datos = array();
           
           

        /// Sumando movimientos
        foreach ($array_productos as $producto) 
        {
            $cantidad = 0;

            $this->db->select('
                                Id,
                                Cantidad, 
                                Tipo_movimiento, 
                                Precio_venta_producto, 
                                Entregado,
                                Fecha_entregado');
            $this->db->from('tbl_stock_movimientos');
            $this->db->where('Proceso_id', $Id);
            $this->db->where('Stock_id', $producto["Stock_id"]);
            $this->db->where('Modulo', "Ventas");
            $this->db->where('Visible', 1);
            $this->db->order_by("Id", "asc"); /// es ascendente, para que el último me dé los datos que necesito

            $query = $this->db->get();
            $array_movimientos_producto = $query->result_array();
            
            foreach ($array_movimientos_producto as $movimiento) 
            {   
                // El 1 resta del stock, pero suma en la venta ya que son productos que salen de stock para irse en la venta al cliente
                if($movimiento["Tipo_movimiento"] == 1 ){
                    $cantidad = $cantidad + $movimiento["Cantidad"];
                }
                else {
                    $cantidad = $cantidad - $movimiento["Cantidad"];
                }
                $producto["Id"] = $movimiento["Id"]; // trato de que quede el ùltimo precio de venta registrado por ID
                $producto["Precio_venta_producto"] = $movimiento["Precio_venta_producto"]; // trato de que quede el ùltimo precio de venta registrado por ID
                $producto["Entregado"] = $movimiento["Entregado"]; // trato de que quede el ùltimo precio de venta registrado por ID
                $producto["Fecha_entregado"] = $movimiento["Fecha_entregado"]; // trato de que quede el ùltimo precio de venta registrado por ID
            }

            $producto["Cantidad"] = $cantidad * (-1); // cambio el signo necesariamente porque lo que hago con respecto al stock es inverso
            
            
            array_push($Datos, $producto);
        }
        

        echo json_encode($Datos);
            
    }

//// PRODUCTOS DE REVENTA 	| ELIMINAR MOVIMIENTOS
    public function eliminarProductoReventa()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        
        $Stock_id = $this->datosObtenidos->Stock_id;
        $Venta_id = $_GET["Venta_id"];

        $data = array(
                        
                'Visible' => 0,

                );
        
        /// BUSCAR TODAS LOS MOVIMIENTOS QUE TENGAN QUE VER CON
            $this->db->select('Id');
            $this->db->from('tbl_stock_movimientos');
            $this->db->where('Proceso_id', $Venta_id);
            $this->db->where('Stock_id', $Stock_id);
            $this->db->where('Modulo', "Ventas");
            $this->db->order_by("Id", "asc");

        $query = $this->db->get();
        $array_movimientos_producto = $query->result_array();
        $MovimientosEliminados = array();
            
        foreach ($array_movimientos_producto as $movimiento) {
            
            $this->load->model('App_model');
            $insert_id = $this->App_model->insertar($data, $movimiento["Id"], 'tbl_stock_movimientos');

            array_push($MovimientosEliminados, $insert_id);
        }
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode( array( "Movimientos_eliminados" => $MovimientosEliminados ) );         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// PRODUCTGOS DE REVENTA    | Marcar como entregados
    public function prodReventaEntregado()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        //// BUSCAR EN MOVIMIENTOS

            $Stock_id = $this->datosObtenidos->Stock_id;
            $Proceso_id = $this->datosObtenidos->Proceso_id;
            $Fecha = date("Y-m-d");
            $data = array(
                'Entregado' => 	1,
                'Fecha_entregado' => $Fecha
            );
            $movimientos_editados = array();

            /// TODOS LOS MOVIMIENTOS - Segundo Proceso_id, Modulo: Ventas, Stock_id...
            $this->db->select('Id');
            $this->db->from('tbl_stock_movimientos');
            $this->db->where('Proceso_id', $Proceso_id);
            $this->db->where('Stock_id', $Stock_id);
            $this->db->where('Modulo', "Ventas");
            $this->db->where('Visible', 1);

            $query = $this->db->get();
            $array_movimientos_producto = $query->result_array();
            
            /// lo recorro para setearlos como ya entregados
            foreach ($array_movimientos_producto as $movimiento) 
            {  
            
                $this->load->model('App_model');
                $insert_id = $this->App_model->insertar($data, $movimiento["Id"], 'tbl_stock_movimientos');
                        
                if ($insert_id >=0 ) 
                {   
                    array_push($movimientos_editados, $insert_id);
                } 

            } 
            
            echo json_encode(array("Ids" => $movimientos_editados));   
    }

///// fin documento
}
