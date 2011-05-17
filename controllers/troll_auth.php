<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! class_exists('Controller'))
{
	class Controller extends CI_Controller {}
}

class Troll_auth extends Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('troll_auth');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
	}

	//redirect if needed, otherwise display the user list
	function index()
	{
		//set the flash data error message if there is one
		$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

		//list the users
		$this->data['users'] = $this->ion_auth->get_users_array();
		$this->load->view('troll_auth/index', $this->data);
	}
	
	// list all group functions and currently existing groups
	function groups()
	{
	}

	// list all resource functions and currently existing resources
	function resources()
	{
	}

}
