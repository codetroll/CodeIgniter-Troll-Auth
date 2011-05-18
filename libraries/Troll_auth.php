<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Troll Auth
*
* Author: Claus Paludan
*		  claus.paludan@gmail.com
*
*
* Created:  16.05.2011
*
* Description:  Authorization system with groups and ressources.
*
* Requirements: PHP5 or above
*
*/

class Troll_auth
{
	/**
	 * CodeIgniter global
	 *
	 * @var string
	 **/
	protected $ci;



	/**
	 * __construct
	 *
	 * @return void
	 * @author Claus Paludan
	 **/
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->load->config('troll_auth', TRUE);
//		$this->ci->lang->load('troll_auth');
		$this->ci->load->model('troll_auth_model');

	}

	/**
	 * __call
	 *
	 * Acts as a simple way to call model methods without loads of stupid alias
	 * @author Ben Edmunds
	 *
	 **/
	public function __call($method, $arguments)
	{
		if (!method_exists( $this->ci->ion_auth_model, $method) )
		{
			throw new Exception('Undefined method Ion_auth::' . $method . '() called');
		}

		return call_user_func_array( array($this->ci->ion_auth_model, $method), $arguments);
	}

}