<?php
defined('BASEPATH') or exit('No direct script access allowed');

class finanzas extends CI_Controller
{

//// FINANZAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 3) {
                $this->load->view('periodos_listado');
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FINANZAS       | VISTA | DATOS
    public function datos()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('periodos_datos');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FINANZAS       | VISTA | FONDO
    public function fondo()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 3) {}

            if ($this->session->userdata('Rol_acceso') > 3) 
            {
                $this->load->view('finanzas_fondo');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }

//// FINANZAS       | OBTENER listado todas
	public function obtener_periodos()
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

        $this->db->select(' tbl_periodos.*,
                            tbl_usuarios.Nombre');
        $this->db->from('tbl_periodos');

        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_periodos.Usuario_id', 'left');

        $this->db->where('tbl_periodos.Visible', 1);
		$this->db->order_by("tbl_periodos.Fecha_cierre", "desc");
        
        $query = $this->db->get();
		$result = $query->result_array();

        
        /// tengo que hacer un scrip que me calcule los ingresos y egresos en este periodo, con un foreach
            /// por ahora que muestre solamente los datos
            $datos = array('Datos' => $result, 'Saldo' => 0);
        
        echo json_encode($datos);
		
    }


//// FINANZAS       | CARGAR O EDITAR
	public function cargar_periodo()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        $fecha = date("Y-m-d");

        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id =       $this->datosObtenidos->Datos->Id;
            $fecha =    $this->datosObtenidos->Datos->Fecha_creado;
		}

        

		$data = array(
                        
					'Identificador' => 			$this->datosObtenidos->Datos->Identificador,
					'Fecha_inicio' => 			$this->datosObtenidos->Datos->Fecha_inicio,
                    'Fecha_cierre' => 	        $this->datosObtenidos->Datos->Fecha_cierre,
                    'Fecha_creado' => 	        $fecha,
                    'Usuario_id' => 		    $this->session->userdata('Id'),
                    'Observaciones_iniciales' => $this->datosObtenidos->Datos->Observaciones_iniciales,                    
				);

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_periodos');
                
		if ($insert_id >=0 ) 
		{   
            echo json_encode(array("Id" => $insert_id));         
		} 
		else 
		{
            echo json_encode(array("Id" => 0));
        }
    }


//// MOVIMIENTOS       | CARGAR MOVIMIENTO GENÉRICO
    public function cargar_movimiento()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

        $Id = null;
        $fecha = date("Y-m-d");
        $Observaciones = 'Sin observaciones'; 
        if(isset($this->datosObtenidos->Datos->Observaciones))
        {
            $Observaciones = $this->datosObtenidos->Datos->Observaciones;
        }

        if(isset($this->datosObtenidos->Datos->Id))
        {
            $Id =       $this->datosObtenidos->Datos->Id;
            $fecha =    $this->datosObtenidos->Datos->Fecha_cargado;
        }

        $data = array(
                    
                    'Planificacion_id' =>   $this->datosObtenidos->Planificacion_id,
                    'Empresa_id' =>         $this->datosObtenidos->Datos->Empresa_id,
                    'Identificador_factura' =>    $this->datosObtenidos->Datos->Identificador_factura,
                    'Modalidad_pago' =>     $this->datosObtenidos->Datos->Modalidad_pago,
                    'Origen_movimiento' => 	$this->datosObtenidos->Origen_movimiento,
                    'Fila_movimiento' =>    $this->datosObtenidos->Fila_movimiento,
                    'Op' =>                 $this->datosObtenidos->Op,
                    'Monto' => 	            $this->datosObtenidos->Datos->Monto,
                    'Fecha_ejecutado' => 	$this->datosObtenidos->Datos->Fecha_ejecutado,
                    'Observaciones' =>      $Observaciones,               
                    'Fecha_cargado' => 	    $fecha,
                    'Usuario_id' => 		$this->session->userdata('Id'),
                         
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_cobros');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// MOVIMIENTOS       | OBTENER MOVIMIENTOS EFECTIVO
    public function obtener_movimientos()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        //// Condicional para saber setear el origen del movimiento
        $Origen_movimiento  = $this->datosObtenidos->Origen_movimiento;
        $Fila_movimiento    = $this->datosObtenidos->Fila_movimiento;

        $this->db->select('*');

        $this->db->from('tbl_cobros');

        $this->db->where('Origen_movimiento', $Origen_movimiento);
        $this->db->where('Fila_movimiento', $Fila_movimiento);
        $this->db->where('Visible', 1);
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        /////  SUMAR MONTOS
        $Total = 0;
        foreach ($result as $movimiento) 
        {
            $Total = $Total + $movimiento["Monto"];
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total);

        echo json_encode($Datos);
    }
    

//// CHEQUES      | CARGAR
    public function cargar_cheques()
    {
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
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

        $fecha = date("Y-m-d");

        $data = array(
                        
                    'Tipo' => 	        $this->datosObtenidos->Datos->Tipo,
                    'Nombre_entrega' => $this->datosObtenidos->Datos->Nombre_entrega,
                    'Monto' => 	        $this->datosObtenidos->Datos->Monto,
                    'Banco' => 	        $this->datosObtenidos->Datos->Banco,
                    'Numero_cheque' => 	$this->datosObtenidos->Datos->Numero_cheque,
                    'Vencimiento' => 	$this->datosObtenidos->Datos->Vencimiento,    
                    'Fecha_cargado' => 	$fecha,
                    'Usuario_id' =>     $this->session->userdata('Id'),
                    'Observaciones' =>  $this->datosObtenidos->Datos->Observaciones,                    
                );

        $this->load->model('App_model');
        $insert_id = $this->App_model->insertar($data, $Id, 'tbl_cheques');
                
        if ($insert_id >=0 ) 
        {   
            echo json_encode(array("Id" => $insert_id));         
        } 
        else 
        {
            echo json_encode(array("Id" => 0));
        }
    }

//// CHEQUES 	  | SUBIR IMAGEN 
    public function subirImagen()
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
                        'Imagen' =>		$nombre_archivo,
                    );

                    $this->load->model('App_model');
                    $insert_id = $this->App_model->insertar($data, $Id, 'tbl_cheques');
                    
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
        echo json_encode(array('status' => $status, 'Imagen' => $nombre_archivo));
    }

//// CHEQUES      | OBTENER
    public function obtener_cheques()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $Estado = $this->datosObtenidos->Estado;

        $this->db->select('*');
        $this->db->from('tbl_cheques');
        if($Estado > 0)
        {
            $this->db->where('Estado', $Estado);
        }
        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $result = $query->result_array();

        //// FUNCIÓN PARA SETEAR SI ESTA VENCIDO O NO
            // consultar si fecha de vencimiento es menor o igual a date("")

        echo json_encode($result);
    }


//// CHEQUES      | OBTENER DIFERENCIADOS POR VENCIMIENTO
    public function obtener_cheques_diferenciados()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $fecha = date('Y-m-d');
        $treintaDias = strtotime('30 day', strtotime($fecha));
        $treintaDias = date('Y-m-d', $treintaDias);

        //// CHEQUES VENCIDOS
        $this->db->select('*');
        $this->db->from('tbl_cheques');
        $this->db->where('Estado', 1);
    
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') <=", $fecha);

        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $array_vencidos = $query->result_array();

        //// CHEQUES QUE VENCEN DENTRO DE LOS PROXIMOS 30 DÍAS
        $this->db->select('*');
        $this->db->from('tbl_cheques');
        $this->db->where('Estado', 1);
    
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') >", $fecha);
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') <=", $treintaDias);

        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $array_cerca = $query->result_array();

        //// CHEQUES QUE VENCEN PASADOS LOS 30 DÍAS
        $this->db->select('*');
        $this->db->from('tbl_cheques');
        $this->db->where('Estado', 1);
    
        $this->db->where("DATE_FORMAT(Vencimiento,'%Y-%m-%d') >", $treintaDias);

        $this->db->where('Visible', 1);
        $this->db->order_by("Vencimiento", "asc");
    
        $query = $this->db->get();
        $array_lejos = $query->result_array();


        $Datos = array('Vencidos' => $array_vencidos, 'Cerca' => $array_cerca, 'Lejos' => $array_lejos);

        echo json_encode($Datos);
    }


//// FONDO       | OBTENER MOVIMIENTOS EFECTIVO
	public function obtener_movimientos_efectivo_fondo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance(); 
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $this->db->select('tbl_dinero_efectivo.*, tbl_usuarios.Nombre');

        $this->db->from('tbl_dinero_efectivo');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_dinero_efectivo.Usuario_id', 'left');

        $this->db->where('tbl_dinero_efectivo.Visible', 1);
        
        if($this->datosObtenidos->Fecha_inicio != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
        }
        if($this->datosObtenidos->Fecha_fin != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
        }
        
        $query = $this->db->get();
		$result = $query->result_array();
        
        /////  SUMAR MONTOS
        $Total = 0;
        foreach ($result as $monto) 
        {
            if($monto["Op"] == true){
                $Total = $Total + $monto["Monto"];
            }
            else {
                $Total = $Total - $monto["Monto"];
            }
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total);

        echo json_encode($Datos);
    }


//// FONDO       | OBTENER MOVIMIENTOS TRANSFERENCIAS
    public function obtener_movimientos_transferencia_fondo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }


        $this->db->select(' tbl_dinero_transferencias.*,
                            tbl_usuarios.Nombre');

        $this->db->from('tbl_dinero_transferencias');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_dinero_transferencias.Usuario_id', 'left');

        $this->db->where('tbl_dinero_transferencias.Visible', 1);

        if($this->datosObtenidos->Fecha_inicio != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
        }
        if($this->datosObtenidos->Fecha_fin != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
        }
        
        $query = $this->db->get();
        $result = $query->result_array();
        
        /////  SUMAR MONTOS
        $Total = 0;
        foreach ($result as $monto) 
        {
            
            if($monto["Op"] == true){
                $Total = $Total + $monto["Monto_bruto"] - $monto["Debito_fiscal_iva_basico"] -$monto["Comision_resumen_cuenta"] -$monto["Retencion_ing_brutos"];
            }
            else {
                $Total = $Total - $monto["Monto_bruto"] - $monto["Debito_fiscal_iva_basico"] -$monto["Comision_resumen_cuenta"] -$monto["Retencion_ing_brutos"];
            }
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total);

        echo json_encode($Datos);
    }

//// FONDO       | OBTENER MOVIMIENTOS CHEQUES
    public function obtener_movimientos_cheques_fondo()
    {
        //Esto siempre va es para instanciar la base de datos
        $CI =& get_instance();
        $CI->load->database();
        
        ///Seguridad
        $token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token) { exit("No coinciden los token"); }

        $this->db->select(' tbl_cheques.Banco,
                            tbl_cheques.Monto,
                            tbl_cheques.Tipo,
                            tbl_cheques.Numero_cheque,
                            tbl_cheques.Nombre_entrega,
                            tbl_cheques.Imagen,
                            tbl_cheques.Vencimiento,
                            tbl_dinero_cheques.Op,
                            tbl_dinero_cheques.Origen_movimiento,
                            tbl_dinero_cheques.Fecha_ejecutado,
                            tbl_dinero_cheques.Observaciones,
                            tbl_usuarios.Nombre');

        $this->db->from('tbl_dinero_cheques');
        $this->db->join('tbl_cheques', 'tbl_cheques.Id = tbl_dinero_cheques.Cheque_id', 'left');
        $this->db->join('tbl_usuarios', 'tbl_usuarios.Id = tbl_dinero_cheques.Usuario_id', 'left');

        $this->db->where('tbl_dinero_cheques.Visible', 1);

        if($this->datosObtenidos->Fecha_inicio != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') >=", $this->datosObtenidos->Fecha_inicio);
        }
        if($this->datosObtenidos->Fecha_fin != null)
        {
            $this->db->where("DATE_FORMAT(Fecha_ejecutado,'%Y-%m-%d') <=", $this->datosObtenidos->Fecha_fin);
        }
        
        $query = $this->db->get();
        $result = $query->result_array();

        /////  SUMAR MONTOS
        $Total = 0;
        foreach ($result as $monto) 
        {
            if($monto["Op"] == true){
                $Total = $Total + $monto["Monto"];
            }
            else {
                $Total = $Total - $monto["Monto"];
            }
        }
        
        $Datos = array("Datos"=> $result, "Total" => $Total);

        echo json_encode($Datos);
    }

///// fin documento
}
