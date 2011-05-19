<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Troll Auth Config
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

	/**
	 * Tables.
	 **/
	$config['tables']['grants']			= 'accgrants';
	$config['tables']['groups']			= 'accgroups';
	$config['tables']['members']		= 'accmember';
	$config['tables']['members_back']	= 'accmember_back';
	$config['tables']['resources']		= 'accresources';
	
	/**
	 * Default groups
	 */
	$config['default_admin_group']		= 1;
	$config['default_user_group']		= 2;

	/**
	 * Default access level
	 *         6 - read/write
     *         4 - read
     *         2 - write
     *         0 - no access
	 */
	$config['default_access_level']			= 6;

	/**
	 * Enable membership backup.
	 * If for some reason you need to remove all group memberships of a user for a period of time setting this to true will
	 * enable copying of data from accmember to accmember_back following wich all copied entries will be deleted from accmember.
	 *
	 * Later the memberships can be moved back to accmember
	 */
	$config['enable_membership_backup']		= FALSE;
	
/* End of file troll_auth.php */
/* Location: ./application/config/troll_auth.php */