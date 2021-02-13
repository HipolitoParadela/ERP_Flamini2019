<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cobranzas extends CI_Controller
{

//// COBRANZAS       | VISTA | LISTADO
    public function index()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {

            if ($this->session->userdata('Rol_acceso') > 3  || $this->session->userdata('Id') == 6  || $this->session->userdata('Id') == 5)
            { 
                $this->load->view('cobranzas_listado');
            } 
            else 
            {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }
        }
    }

//// COBRANZAS       | VISTA | DATOS
    public function cobros()
    {
        if ($this->session->userdata('Login') != true) {
            header("Location: " . base_url() . "login"); /// enviar a pagina de error
        } else {
            ////COMENZAR A FILTRAR Y REDIRECCIONAR SEGUN ROL Y PLAN CONTRATADO
            //if (plan_contratado() > 1) {}

            if ($this->session->userdata('Rol_acceso') > 3  || $this->session->userdata('Id') == 6  || $this->session->userdata('Id') == 5) 
            {
                $this->load->view('cobranzas_cobros');
                
            } else {
                header("Location: " . base_url() . "login"); /// enviar a pagina de error
            }

        }
    }
///// fin documento
}
