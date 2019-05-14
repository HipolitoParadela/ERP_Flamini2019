<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Funcionescomunes extends CI_Controller
{
    //// 			| ELIMINAR ALGO
	public function eliminar()
    {
        $CI =& get_instance();
		$CI->load->database();
		
		$token = @$CI->db->token;
        $this->datosObtenidos = json_decode(file_get_contents('php://input'));
        if ($this->datosObtenidos->token != $token)
        { 
            exit("No coinciden los token");
        }

		$Id = NULL;
		$tabla = NULL;

		if(isset($this->datosObtenidos->Id))
        {
            $Id = $this->datosObtenidos->Id;
        }

		if(isset($this->datosObtenidos->tabla))
        {
            $tabla = $this->datosObtenidos->tabla;
        }
		
        $this->load->model('App_model');
        $insert_id = $this->App_model->eliminar($Id, $tabla);
                
    }

    //// DATOS USUARIO JS | VISTA | PRODUCCION
    public function config()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } 
        else 
        {
            $Datos = array( 'Rol_acceso' => $this->session->userdata('Rol_acceso'),
                            'Usuario_id' => $this->session->userdata('Id'));

            $this->load->view('config', $Datos);
        }
    }

////////////////////////////////
}