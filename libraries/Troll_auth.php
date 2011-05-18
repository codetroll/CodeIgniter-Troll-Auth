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



	/**
	 * Has access functions
	 *
	 * user_has_read_access_to_resource : returns true if user has READ access to resource.
     * user_has_write_access_to_resource : returns true if user has WRITE access to resource.
     * group_has_read_access_to_resource : returns true if group has READ access to resource.
     * group_has_write_access_to_resource : returns true if group has WRITE access to resource.
	 *
	 * @author Claus Paludan
	 */

	/**
	 * Returns true if user has read access to the resource OR is member of a group that has read access to the resource.
	 * If no read access can be found false is returned.
	 *
	 * @param int $resource_id
	 * @param int $user_id
	 * @return boolean
	 */
	public function user_has_read_access_to_resource($resource_id,$user_id)
	{
		$access_level = $this->ci->troll_auth_model->get_user_access_granted($resource_id,$user_id);
		if ($access_level == READ || $access_level == READWRITE)
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns true if user has write access to the resource OR is member of a group that has write access to the resource.
	 * If no read access can be found false is returned.
	 *
	 * @param int $resource_id
	 * @param int $user_id
	 * @return boolean
	 */
	public function user_has_write_access_to_resource($resource_id,$user_id)
	{
		$access_level = $this->ci->troll_auth_model->get_user_access_granted($resource_id,$user_id);
		if ($access_level == WRITE || $access_level == READWRITE)
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns true if group has write access to the resource .
	 * If no read access can be found false is returned.
	 *
	 * @param int $resource_id
	 * @param int $group_id
	 * @return boolean
	 */
	public function group_has_read_access_to_resource($resource_id,$group_id)
	{
		$access_level = $this->ci->troll_auth_model->get_group_access_granted($resource_id,$group_id);
		if ($access_level == READ || $access_level == READWRITE)
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Returns true if group has read access to the resource .
	 * If no read access can be found false is returned.
	 *
	 * @param int $resource_id
	 * @param int $group_id
	 * @return boolean
	 */
	public function group_has_write_access_to_resource($resource_id,$group_id)
	{
		$access_level = $this->ci->troll_auth_model->get_group_access_granted($resource_id,$group_id);
		if ($access_level == WRITE || $access_level == READWRITE)
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Misc.
	 *
	 * user_is_admin : is user member of the default admin group
	 *
	 * @author Claus Paludan
	 */

	/**
	 * Returns true if user is a member of the default admin group
	 *
	 * @param int $user_id
	 * @return boolean
	 * @author Claus Paludan
	 */
	public function user_is_admin($user_id)
	{
		return $this->ci->troll_auth_model->is_user_member_of_group($user_id, $this->config->item('default_admin_group', 'troll_auth'));
	}

}