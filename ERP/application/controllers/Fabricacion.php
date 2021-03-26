<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Fabricacion extends CI_Controller
{

//// FABRICACIÓN        | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) 
        {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } 
        else 
        {   
            if ($this->session->userdata('Rol_acceso') > 1) // visible para todos
            {
                $this->load->view('fabricacion_listado');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FABRICACIÓN        | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            if ($this->session->userdata('Rol_acceso') > 1)  // visible para todos
            {
                $this->load->view('fabricacion_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FABRICACIÓN        | VISTA | DATOS
    public function stockfabricados()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            if ($this->session->userdata('Rol_acceso') > 1)  // visible para todos
            {
                $this->load->view('fabricacion_productos_stock');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FABRICACIÓN        | OBTENER LISTADO 
	public function obtener_listado_de_productos()
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

        //$estado = $_GET["estado"];
        $categoria = $_GET["categoria"];
        $empresa = $_GET["empresa"];

		$this->db->select('	tbl_fabricacion.*,
                            tbl_fabricacion_categorias.Nombre_categoria,
                            tbl_empresas.Nombre_empresa');

        $this->db->from('tbl_fabricacion');

        $this->db->join('tbl_fabricacion_categorias', 'tbl_fabricacion_categorias.Id = tbl_fabricacion.Categoria_fabricacion_id','left');
        $this->db->join('tbl_empresas', 'tbl_empresas.Id = tbl_fabricacion.Empresa_id','left');

        //$this->db->where('tbl_fabricacion.Visible', 1);

        if($categoria > 0)
        {
            $this->db->where('tbl_fabricacion.Categoria_fabricacion_id', $categoria);
        }
        if($empresa > 0)
        {
            $this->db->where('tbl_fabricacion.Empresa_id', $empresa);
        }

		$this->db->order_by("tbl_fabricacion.Nombre_producto", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// FABRICACIÓN        | OBTENER LISTADO PARA VENTAS
    public function obtener_listado_de_productos_ventas()
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

        //$estado = $_GET["estado"];
        /* $categoria = $_GET["categoria"];
        $empresa = $_GET["empresa"];

        $this->db->select('	tbl_fabricacion.*,
                            tbl_fabricacion_categorias.Nombre_categoria,
                            tbl_empresas.Nombre_empresa'); */

        $this->db->select('	Codigo_interno,
                            Fecha_ultima_mod,
                            Id,
                            Nombre_producto,
                            Precio_venta
            '); 

        $this->db->from('tbl_fabricacion');/* 

        $this->db->join('tbl_fabricacion_categorias', 'tbl_fabricacion_categorias.Id = tbl_fabricacion.Categoria_fabricacion_id','left');
        $this->db->join('tbl_empresas', 'tbl_empresas.Id = tbl_fabricacion.Empresa_id','left'); */

        //$this->db->where('tbl_fabricacion.Visible', 1);

        /* if($categoria > 0)
        {
            $this->db->where('tbl_fabricacion.Categoria_fabricacion_id', $categoria);
        }
        if($empresa > 0)
        {
            $this->db->where('tbl_fabricacion.Empresa_id', $empresa);
        } */

        $this->db->order_by("tbl_fabricacion.Nombre_producto", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
        
    }
    
//// FABRICACIÓN        | OBTENER Datos del item
    public function obtener_datos_id()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        $Id = $_GET["Id"];

        $this->db->select('	tbl_fabricacion.*,
                            tbl_fabricacion_categorias.Nombre_categoria');

        $this->db->from('tbl_fabricacion');

        $this->db->join('tbl_fabricacion_categorias', 'tbl_fabricacion_categorias.Id = tbl_fabricacion.Categoria_fabricacion_id', 'left');

        //$this->db->where('tbl_fabricacion.Visible', 1);

        $this->db->where('tbl_fabricacion.Id', $Id);


        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }


//// FABRICACIÓN        | DESACTIVAR 
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
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN ARCH 	| OBTENER Listado archivos
    public function obtener_listado_archivos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        
        $token = @$CI->db->token;
        
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        
        if ($this->datosObtenidos->token != $token) 
        { 
            exit("No coinciden los token");
        }

        $Id = $_GET["Id"];

        $this->db->select(' tbl_fabricacion_archivos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_fabricacion_archivos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_fabricacion_archivos.Usuario_id', 'left');
        
        $this->db->where('tbl_fabricacion_archivos.Producto_id', $Id);
        $this->db->order_by("tbl_fabricacion_archivos.Nombre_archivo", "desc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
    }

//// FABRICACIÓN ARCH 	| CARGAR ACTUALIZAR
    public function cargar_archivo()
    {
        $CI = &get_instance();
        $CI->load->database();

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null; if (isset($this->datosObtenidos->Datos->Id))  {  $Id = $this->datosObtenidos->Datos->Id; }
        $Descripcion = "Sin descripción";     if(isset($this->datosObtenidos->Data->Descripcion)) { $Descripcion = $this->datosObtenidos->Data->Descripcion; }

        $data = array(

            'Producto_id'       => $this->datosObtenidos->Producto_id,
            'Nombre_archivo'    => $this->datosObtenidos->Datos->Nombre_archivo,
            'Descripcion'       => $Descripcion,
            'Usuario_id'        => $this->session->userdata('Id'),
            'Visible'           => 1,
        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_archivos');

        if ($insert_id >= 0) // SI SE CARGO BIEN DEBE ACTUALIZAR LA TABLA tbl_fabricacion, con el calculod de stock actual y el Id de la última actualización
        {
            echo json_encode(array("Id" => $insert_id));
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN ARCH 	| OBTENER ULTIMOS archivos REGISTRADOS --- SIRVE PARA EL DASHBOARD
    public function obtener_ultimos_archivos()
    {

        //Esto siempre va es para instanciar la base de datos
        $CI = &get_instance();
        $CI->load->database();
        $token = @$CI->db->token;

        //$Id = $_GET["Id"];
        $limit = 25;
        $start = 0;
        
        $this->db->select(  'tbl_fabricacion_archivos.*,
                            tbl_usuarios.Nombre,
                            tbl_fabricacion.Nombre_item');
        $this->db->from('tbl_fabricacion_archivos');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_fabricacion_archivos.Usuario_id', 'left');
        $this->db->join('tbl_fabricacion', 'tbl_fabricacion.Id = tbl_fabricacion_archivos.Producto_id', 'left');
        
        //$this->db->where('Stock_id', $Id);
        $this->db->order_by("tbl_fabricacion_archivos.Id", "desc");
        $this->db->limit($limit, $start);

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);

    }

//// FABRICACIÓN ARCH   | ACTUALIZAR DESCRIPCIÓN
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

        $Id = null;     if(isset($this->datosObtenidos->Data->Id)) { $Id = $this->datosObtenidos->Data->Id; }
        $Descripcion = "Sin descripción";     if(isset($this->datosObtenidos->Data->Descripcion)) { $Descripcion = $this->datosObtenidos->Data->Descripcion; }

		$data = array(
					'Descripcion' => $Descripcion,
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_archivos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN        | CARGAR O EDITAR PRODUCTO
	public function cargar_producto()
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
		if(isset($this->datosObtenidos->Data->Id)) { $Id = $this->datosObtenidos->Data->Id; }
		
		$data = array(
                    
                    'Empresa_id' => 		            $this->datosObtenidos->Data->Empresa_id,
                    'Codigo_interno' => 		        $this->datosObtenidos->Data->Codigo_interno,
					'Categoria_fabricacion_id' => 	    $this->datosObtenidos->Data->Categoria_fabricacion_id,
                    'Nombre_producto' => 		        $this->datosObtenidos->Data->Nombre_producto,
                    'Precio_venta' => 		            $this->datosObtenidos->Data->Precio_venta,
                    'Descripcion_publica_corta' => 		$this->datosObtenidos->Data->Descripcion_publica_corta,
                    'Descripcion_publica_larga' => 		$this->datosObtenidos->Data->Descripcion_publica_larga,
                    'Descripcion_tecnica_privada' => 	$this->datosObtenidos->Data->Descripcion_tecnica_privada,
                    'Ult_usuario' =>                    $this->session->userdata('Id')
                     
                );
                /// 'Ultimo_editor_id' => 		$this->session->userdata('Id')

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// FABRICACIÓN        | SUBIR FOTO
	public function subirFotoProducto()
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
                echo json_encode(array('status' => $status, 'Msg' => $msg));
			}
			else
			{
				/// coloco el dato en la base de datos
					$Id = $_GET["Id"];
					
					$data = $this->upload->data();
					
					$file_info = $this->upload->data();
					$nombre_imagen = $file_info['file_name'];
                    
                    /// DEPENDE EL CAMPO QUE TRAIGA, SERA DONDE GUARDE EL URL DE LA IMAGEN
					if (isset($_GET["Campo"])) 
                    {
                        if($_GET["Campo"] == 'Posicion')
                        {
                            $data = array(    
                                'Ubicacion_pieza_url' =>		$nombre_imagen,
                            ); 
                        }

                        else if($_GET["Campo"] == 'Subconjunto')
                        {
                            $data = array(    
                                'Subproducto_url' =>		$nombre_imagen,
                            ); 
                        }

                        $this->load->model('App_model');
					    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_insumos_producto');
                    }
                    else
                    {
                        $data = array(    
                            'Imagen' =>		$nombre_imagen,
                        );

                        $this->load->model('App_model');
					    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion');
                    }
					

					 
					
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
    

//// CATEGORIAS 	    | OBTENER 
	public function obtener_categorias()
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


		$this->db->select('*');
		$this->db->from('tbl_fabricacion_categorias');
		$this->db->order_by("Nombre_categoria", "asc");
        $query = $this->db->get();
		$result = $query->result_array();

		echo json_encode($result);
		
    }

//// CATEGORIAS 	    | CARGAR O EDITAR
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
        if (isset($this->datosObtenidos->Data->Id)) 
        {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $Id = null;
        if (isset($this->datosObtenidos->Data->Id)) 
        {
            $Id = $this->datosObtenidos->Data->Id;
        }

        $data = array(

            'Nombre_categoria' => $this->datosObtenidos->Data->Nombre_categoria,
            'Descripcion' => $this->datosObtenidos->Data->Descripcion,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_categorias');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }




//// ARCHIVOS 	        | SUBIR  
	public function subirArchivo()
	{
		$status = "";
		$msg = "";
		$file_element_name = 'Archivo';
		
		if ($status != "error")
		{
			$config['upload_path'] = './uploads/imagenes';
			$config['allowed_types'] = 'jpg|jpeg|doc|docx|xlsx|xls|pdf|dwg|rar';
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
					$insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_archivos');
					
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


//// FABRICACIÓN        | OBTENER VENTAS DE UN PRODUCTO
    public function obtener_ventas_producto()
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

        
        //// BUSCAR EN TBL-VENTAS-PRODUCTOS, TODOS LAS FILAS DONDE APAREZCA EL PRODUCTO EN CUESTIÓN
            /// Adicionalmente debe agrupar los resultados por venta_id
                /// al final debo tener un array con las ventas donde aparecen
            
            $Producto_id = $_GET["Producto_id"];

            $this->db->select(' tbl_ventas.Id,
                                tbl_ventas.Identificador_venta,
                                tbl_clientes.Nombre_cliente,
                                tbl_usuarios.Nombre as Nombre_vendedor,
                                tbl_ventas.Fecha_venta,
                                tbl_ventas.Fecha_finalizada');

            $this->db->from('tbl_ventas_productos');

            $this->db->join('tbl_ventas', 'tbl_ventas.Id = tbl_ventas_productos.Venta_id','left');
            $this->db->join('tbl_clientes', 'tbl_clientes.Id = tbl_ventas.Cliente_id','left');
            $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_ventas.Vendedor_id','left');

            //$this->db->where('tbl_ventas.Visible', 1);
            $this->db->where('tbl_ventas_productos.Producto_id', $Producto_id);

            $this->db->group_by('tbl_ventas_productos.Venta_id');
            
            $query = $this->db->get();
            $array_ventas_vinculadas = $query->result_array();
        
        //// EMPIEZO A RECORRER ESTE ARRAY, BUSCANDO INFORMACIÓN DE LA VENTA, 
            ///dentro de este array, debo generar también una consulta para saber la CANTIDAD DE VECES QUE APARECE ESTE PRODUCTO EN ESA VENTA

            $Datos_ventas = array();

            foreach ($array_ventas_vinculadas as $venta) 
            {
                // CONSULTANDO CANTIDAD DEL PRODUCTO VENDIDO
                $this->db->select('Id');
                $this->db->from('tbl_ventas_productos');
                    //$this->db->where("DATE_FORMAT(fecha,'%Y-%m')", $Mes);
                $this->db->where('Producto_id', $Producto_id);
                $this->db->where('Venta_id', $venta["Id"]);
                $query = $this->db->get();
                $cant_vendida = $query->num_rows();
                
                $datos = array('Datos_venta' => $venta, 'Cantidad_vendida_producto' => $cant_vendida);

                array_push($Datos_ventas, $datos);
            }

        echo json_encode($Datos_ventas);
        
    }

//// FABRICACIÓN        | CARGAR O EDITAR UN INSUMO
	public function cargar_insumo_producto()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id = $this->datosObtenidos->Datos->Id;
        }
        
        $Fabricacion_id = $_GET["Fabricacion_id"];
		
		$data = array(
                        
					'Fabricacion_id' =>     $Fabricacion_id,
					'Stock_id' => 	        $this->datosObtenidos->Datos->Stock_id,
                    'Cantidad' => 	        $this->datosObtenidos->Datos->Cantidad,
                    'Dimension' => 	        $this->datosObtenidos->Datos->Dimension,
					'Posicion' => 		    $this->datosObtenidos->Datos->Posicion,
                    'Subconjunto' => 		    $this->datosObtenidos->Datos->Subconjunto,
                    'Observaciones' => 		$this->datosObtenidos->Datos->Observaciones,
                     
                );
                /// 'Ultimo_editor_id' => 		$this->session->userdata('Id')

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_insumos_producto');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }

//// FABRICACIÓN        | OBTENER LISTADO DE INSUMOS VINCULADOS
    public function obtener_listado_insumos()
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

        //$estado = $_GET["estado"];
        $Fabricacion_id = $_GET["Fabricacion_id"];

        $this->db->select('	tbl_fabricacion_insumos_producto.*,
                            tbl_stock.Nombre_item,
                            tbl_stock.Unidad_medida,
                            tbl_stock.Cant_comercial');

        $this->db->from('tbl_fabricacion_insumos_producto');

        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_fabricacion_insumos_producto.Stock_id','left');

        $this->db->where('tbl_fabricacion_insumos_producto.Visible', 1);
        $this->db->where('tbl_fabricacion_insumos_producto.Fabricacion_id', $Fabricacion_id);

        $this->db->order_by("tbl_fabricacion_insumos_producto.Posicion", "asc");

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
        
    }


//// FABRICACIÓN 	| OBTENER CANTIDADES DE PRODUCTOS A COMPRAR
    public function obtener_lista_total_productos_comprar()
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

        $Fabricacion_id = $_GET["Fabricacion_id"];
        $Datos = array();
        
        $this->db->select('	tbl_fabricacion_insumos_producto.*,
                            tbl_stock.Nombre_item,
                            tbl_stock.Unidad_medida,
                            tbl_stock.Cant_comercial');

        $this->db->from('tbl_fabricacion_insumos_producto');

        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_fabricacion_insumos_producto.Stock_id','left');

        $this->db->where('tbl_fabricacion_insumos_producto.Visible', 1);
        $this->db->where('tbl_fabricacion_insumos_producto.Fabricacion_id', $Fabricacion_id);
        $this->db->group_by('tbl_fabricacion_insumos_producto.Stock_id');

        $this->db->order_by("tbl_stock.Nombre_item", "asc");

        $query = $this->db->get();
        $array_materias_primas = $query->result_array();        

        /// Sumando cantidades
        foreach ($array_materias_primas as $producto) 
        {
            
            $this->db->select('Cantidad, Dimension');
            $this->db->from('tbl_fabricacion_insumos_producto');
            $this->db->where('Fabricacion_id', $Fabricacion_id);
            $this->db->where('Stock_id', $producto["Stock_id"]);
            $this->db->where('Visible', 1);
            $query = $this->db->get();
            $array_cantidades = $query->result_array();            
            
            $Total_cantidad_requerida = 0;

            foreach ($array_cantidades as $movimiento) 
            {   
                
                $Total_cantidad_requerida = $Total_cantidad_requerida + ( $movimiento["Cantidad"] * $movimiento["Dimension"] );

            }

            $producto["Total_cantidad"] = $Total_cantidad_requerida;
            
            array_push($Datos, $producto);
        }
        

        echo json_encode($Datos);
            
    }

//// PRODUCTOS VENDIDOS 	| ANULAR PRODUCTO
    public function reasignar_producto()
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


        //// si la cantidad es la misma, sigue la reasigna derecho
        if($this->datosObtenidos->Datos->Cantidad_nueva === $this->datosObtenidos->Datos->Cantidad)
        {
            $data = array(
                'Venta_id' =>   $this->datosObtenidos->Datos->Venta_id,
            );

            $this->load->model('App_model');
            $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');
        }
        else 
        {
            /// si la cantidad es menos, resta la cantidad original, con la nueva
            // la que coloca en el campo de texto es para la venta original, el restante es para la venta nueva
                $data = array(
                    'Venta_id' =>   $this->datosObtenidos->Datos->Venta_id,
                    'Cantidad' =>   $this->datosObtenidos->Datos->Cantidad_nueva,
                );

                $this->load->model('App_model');
                $insert_id = $this->App_model->insertar($data, $Id, 'tbl_ventas_productos');

            /// Ahora el producto restante, lo paso a la venta de reserva
                /// genero un clon de los datos originales
                $cantidad_restante = $this->datosObtenidos->Datos->Cantidad - $this->datosObtenidos->Datos->Cantidad_nueva;

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
                    'Venta_id' =>                   1,
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
                    'Descripcion' =>   $this->datosObtenidos->Datos->Cantidad_nueva . ' '.$this->datosObtenidos->Datos->Nombre_producto. 'añadidos desde stock de reserva: ',
                    'Usuario_id' =>    $this->session->userdata('Id'),
                    'Visible' =>       1
                );

                $this->load->model('App_model');
                $insert_id_seguimiento = $this->App_model->insertar($data, null, 'tbl_ventas_seguimiento');
                    
                echo json_encode(array( "Id" => $insert_id, "Seguimiento_id" => $insert_id_seguimiento));
            }
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// PLANILLAS	    | OBTENER 
    public function obtener_planillas()
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


        $this->db->select('*');
        $this->db->from('tbl_fabricacion_planillas');
        $this->db->where('Visible', 1);
        $this->db->order_by("Nombre_planilla", "asc");
        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
        
    }

//// PLANILLAS	    | CARGAR O EDITAR
    public function cargar_planilla()
    {
        $CI = &get_instance();
        $CI->load->database();
        
        
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        

        $Id = null; if (isset($this->datosObtenidos->Data->Id))  { $Id = $this->datosObtenidos->Data->Id; }
        $Descripcion = 'Sin descripción'; if (isset($this->datosObtenidos->Data->Descripcion))  { $Descripcion = $this->datosObtenidos->Data->Descripcion; }

        $data = array(

            'Nombre_planilla'   => $this->datosObtenidos->Data->Nombre_planilla,
            'Descripcion'       => $Descripcion,

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_planillas');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }


//// PLANILLAS	    | OBTENER 
    public function obtener_items_planillas()
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


        $this->db->select(' tbl_fabricacion_planillas_vinculo.Id,
                            tbl_fabricacion_planillas_vinculo.Metodo,
                            tbl_fabricacion_planillas_vinculo.Observaciones,
                            tbl_stock.Nombre_item,
                            tbl_fabricacion_insumos_producto.Id as Insumo_fabricacion_id,
                            tbl_fabricacion_insumos_producto.Posicion,
                            tbl_fabricacion_insumos_producto.Subconjunto,
                            tbl_fabricacion_insumos_producto.Ubicacion_pieza_url,
                            tbl_fabricacion_insumos_producto.Subproducto_url,
                            tbl_fabricacion_insumos_producto.Cantidad,
                            tbl_fabricacion_planillas.Nombre_planilla');

        $this->db->from('tbl_fabricacion_planillas_vinculo'); 
        $this->db->join('tbl_fabricacion_insumos_producto', 'tbl_fabricacion_insumos_producto.Id = tbl_fabricacion_planillas_vinculo.Insumo_fabricacion_id','left');
        $this->db->join('tbl_stock', 'tbl_stock.Id = tbl_fabricacion_insumos_producto.Stock_id','left');
        $this->db->join('tbl_fabricacion_planillas', 'tbl_fabricacion_planillas.Id = tbl_fabricacion_planillas_vinculo.Planilla_id','left');
        
        $this->db->where('tbl_fabricacion_planillas_vinculo.Producto_id', $_GET["Producto_id"]);
        $this->db->where('tbl_fabricacion_planillas_vinculo.Planilla_id', $_GET["Planilla_id"]);

        $this->db->where('tbl_fabricacion_planillas_vinculo.Visible', 1);

        $this->db->order_by("tbl_fabricacion_insumos_producto.Posicion", "asc");

        $query = $this->db->get();
        $result = $query->result_array();

        echo json_encode($result);
        
    }

//// PLANILLAS	    | CARGAR O EDITAR
    public function cargar_vinculo_planilla()
    {
        $CI = &get_instance();
        $CI->load->database();
        

        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }
        

        $Id = null; if (isset($this->datosObtenidos->Data->Id))  { $Id = $this->datosObtenidos->Data->Id; }
        $Metodo = 'Sin descripción'; if (isset($this->datosObtenidos->Data->Metodo))  { $Metodo = $this->datosObtenidos->Data->Metodo; }
        $Observaciones = 'Sin observaciones'; if (isset($this->datosObtenidos->Data->Observaciones))  { $Observaciones = $this->datosObtenidos->Data->Observaciones; }

        $data = array(

            'Producto_id'       => $this->datosObtenidos->Producto_id,
            'Planilla_id'       => $this->datosObtenidos->Planilla_id,
            'Insumo_fabricacion_id'          => $this->datosObtenidos->Data->Insumo_fabricacion_id,
            'Metodo'            => $Metodo,
            'Observaciones'     => $Observaciones,
            'Usuario_id'        => $this->session->userdata('Id'),

        );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_fabricacion_planillas_vinculo');

        if ($insert_id >= 0) {
            echo json_encode(array("Id" => $insert_id));
        } else {
            echo json_encode(array("Id" => 0));
        }
    }

///// fin documento
}
