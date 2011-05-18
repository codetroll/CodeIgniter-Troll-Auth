<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Troll Auth Model
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

//  CI 2.0 Compatibility
if(!class_exists('CI_Model')) { class CI_Model extends Model {} }


class Troll_auth_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var array
	 **/
	public $tables = array();

	/**
	 * Enable membership backup.
	 * If for some reason you need to remove all group memberships of a user for a period of time setting this to true will
	 * enable copying of data from accmember to accmember_back following wich all copied entries will be deleted from accmember.
	 *
	 * Later the memberships can be moved back to accmember
	 *
	 * @var boolean
	 */
	public $enable_membership_backup = FALSE;

	/**
	 * Default administrator group id
	 *
	 * @var integer
	 */
	public $default_admin_group = 0;

	/**
	 * Default user group id
	 *
	 * @var integer
	 */
	public $default_user_group = 0;

	/**
	 * Default access level
	 *
	 * @var integer
	 */
	public $default_access_level = 0;
	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('troll_auth', TRUE);
//		$this->load->library('session');

		$this->tables  = $this->config->item('tables', 'troll_auth');

		$this->enable_membership_backup = $this->config->item('enable_membership_backup', 'troll_auth');
		$this->default_admin_group		= $this->config->item('default_admin_group', 'troll_auth');
		$this->default_user_group		= $this->config->item('default_user_group', 'troll_auth');
		$this->default_access_level		= $this->config->item('default_access_level', 'troll_auth');
	}

	/**
	 * This function returns true if the provided access level provides read access, otherwise returns false
	 *
	 * @param int access_level
	 * @return boolean
	 * @author Claus Paludan
	 **/
    function has_read_access($access_level)
	{
        if ($access_level == READ || $access_level == READWRITE)
		{
            return TRUE;
        } else {
            return FALSE;
        }
    }

	/**
	 * This function returns true if the provided access level provides write access, otherwise returns false
	 *
	 * @param access_level
	 * @return boolean
	 * @author Claus Paludan
	 **/
    function has_write_access($access_level)
	{
        if ($access_level == WRITE || $access_level == READWRITE)
		{
            return TRUE;
        } else {
            return FALSE;
        }
    }

	/**
	 * This function returns returns access level granted to group for resource
	 *         6 - read/write
     *         4 - read
     *         2 - write
     *         0 - no access
	 *
	 * @param int resource_id
	 * @param int group_id
	 * @return int access level
	 * @author Claus Paludan
	 **/
    function get_group_access_granted($resource_id,$group_id)
	{
		// keep this for reference until CI code has been tested

        $query = "";
        $query .= " select distinct * ";
        $query .= " from accgrants grants, ";
        $query .= " accgroups groups, ";
        $query .= " accresources ressource ";
        $query .= " where ressource.numAccResId = $resource_id ";
        $query .= " and grants.numAccResId = ressource.numAccResId ";
        $query .= " and grants.numAccGrpId = $group_id ";
        $query .= " order by numAccLevel desc";
        $select = mysql_query($query);

		echo "<br />".$query;
        $access = mysql_fetch_array($select);
        $access_level = $access['numAccLevel'];
		echo "<br />access_level : ".$access_level;
//        return $access_level;

		$this->db->distinct();
		$this->db->from($this->tables['accgrants']);
		$this->db->from($this->tables['accgroups']);
		$this->db->from($this->tables['accresources']);
		$this->db->where('ressource.resource_id',$resource_id);
		$this->db->where('grants.resource_id','ressource.resource_id');
		$this->db->where('grants.group_id',$group_id);
		$this->db->order_by('acclevel','desc');
		$query = $this->db->query();
		echo $this->db->last_query();
		echo "<br />access_level : ".$query->result()->row()->access_level;
		return $query->result()->row()->access_level;
    }

	/**
	 * This function returns returns access level granted to user for resource
	 *         6 - read/write
     *         4 - read
     *         2 - write
     *         0 - no access
	 *
	 * @param int resource_id
	 * @param int user_id
	 * @return int access level
	 * @author Claus Paludan
	 **/
	function get_user_access_granted($resource_id,$user_id)
	{
		if (empty($resource_id) || $resource_id == 0) return 0; //
	    if (empty($user_id)) {
	        $user_id = 20; // default bruger
	    }
		$query = "";
	    $query .= " select distinct * from accgrants grants,";
	    $query .= " accgroups groups,";
	    $query .= " accmember member,";
	    $query .= " accresources resource";
	    $query .= " where grants.resource_id=resource.resource_id";
	    $query .= " and ressource.resource_id=$resource_id";
	    $query .= " and (grants.user_id=$user_id";
	    $query .= " or (grants.group_id=groups.group_id";
	    $query .= " and groups.group_id=member.group_id";
	    $query .= " and member.user_id = $user_id)";
	    $query .= ") order by access_level desc";
//		$query .= "limit 1";
//    echo "<br />[$query]";

		$access = mysql_fetch_array($select);
		$accesslevel = $access['numAccLevel'];
		return $accesslevel;
	}


	/**
	 * Grant a user access to a specific ressource with a given access level
	 * returns true if grant succeeds, otherwise false
	 * NB!!  This IS NOT for normal purpose - you should grant groups access instead and add the user to said groups
	 *         6 - read/write
     *         4 - read
     *         2 - write
     *         0 - no access
	 *
	 * @param access_level
	 * @return boolean
	 * @author Claus
	 **/
    function grant_user_access($resource_id,$user_id, $access_level )
	{
		try
		{
			$data = array(
				'user_id' => $user_id,
				'group_id' => -1,
				'resource_id' => $resource_id,
				'access_level' => $access_level
			);
			$this->db->insert($this->tables['accgrants'],$data);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    } 

    /**
     * Grant a group access to a specific ressource with a given access level
	 * 
     * @param int ressource id
     * @param int group id
     * @param int accesslevel
	 * @return boolean
	 * @author Claus
     *         6 - read/write
     *         4 - read
     *         2 - write
     *         0 - no access
     */
    function grant_group_access($resource_id,$group_id,$access_level)
	{
		try
		{
			$data = array(
				'user_id' => -1,
				'group_id' => $group_id,
				'resource_id' => $resource_id,
				'access_level' => $access_level
			);
			$this->db->insert($this->tables['accgrants'],$data);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
     * Revoke a users access to a specific ressource with a given access level
	 * 
     * @param int ressource_id
     * @param int user_id
	 * @return boolean
	 * @author Claus
     */
    function revoke_user_access($resource_id,$user_id)
	{
		try
		{
			$this->db->where('user_id',$user_id);
			$this->db->where('resource_id',$resource_id);
			$this->db->delete($this->tables['accgrants']);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
     * removes all access grants for specified userid
     * @param int user_id
	 * @return boolean
	 * @author Claus
     */
    function revoke_all_user_access($user_id)
	{
		try
		{
			$this->db->where('user_id',$user_id);
			$this->db->delete($this->tables['accgrants']);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    } 

    /**
     * removes membership in all groups for user_id
     * @param int user_id
	 * @return boolean
	 * @author Claus
     */
    function revoke_all_user_memberships($user_id)
	{
		try
		{
			$this->db->where('user_id',$user_id);
			$this->db->delete($this->tables['accmember']);
			if ($this->enable_membership_backup)
			{
				$this->db->where('user_id',$user_id);
				$this->db->delete('accmember_back');
			}
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

	/**
     * Revoke a group's access to a specific ressource with a given access level
	 * returns true if successful, otherwise false
     * @param int resource_id
     * @param int group_id
	 * @return boolean
	 * @author Claus
     */
    function revoke_group_access($resource_id,$group_id)
	{
		try
		{
			$this->db->where('resource_id',$resource_id);
			$this->db->where('group_id',$group_id);
			$this->db->delete($this->tables['accgrants']);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

	/**
     * Remove all groups access to a specific resource
	 * returns true if successful, otherwise false
     * @param int resource_id
	 * @return boolean
	 * @author Claus
     */
    function revoke_all_group_access($resource_id)
	{
		try
		{
			$this->db->where('resource_id',$resource_id);
			$this->db->where('user_id',-1); // if user_id = -1 it is a group access grant
			$this->db->delete($this->tables['accgrants']);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
     * @param int userid
     * @param int groupid
     * Makes a user a member of a specific group
     */
    function add_user_to_group($user_id,$group_id)
	{
	    $this->db->trans_begin();

		$this->db->where('user_id',$user_id);
		$this->db->where('group_id',$group_id);
		$this->db->delete($this->tables['accmember']);

		$data = array (
			'user_id' => $user_id,
			'group_id' => $group_id
		);
		$this->db->insert($this->tables['accmember'],$data);
		
	    if ($this->db->trans_status() === FALSE)
	    {
			$this->db->trans_rollback();
			return FALSE;
	    }

	    $this->db->trans_commit();
	    return TRUE;
    }


    /**
     * Returns true if the user is a member of the specified group
     * @param int userid
     * @param int groupid
     * @returns boolean
     * @Author Claus Paludan
     */
    function is_user_member_of_group($user_id,$group_id)
	{
        if (empty($user_id) || empty($group_id)) return FALSE;
		$this->db->where('user_id',$user_id);
		$this->db->where('group_id',$group_id);
		$this->db->from($this->tables['accmember']);
		
        if ($this->db->count_all_results() > 0)
		{
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Returns name of group
     * @param int group_id
     * @returns string
     * @Author Claus Paludan
     */
    function get_group_name($group_id)
	{
		$this->db->where('group_id',$group_id);
		return $this->db->get($this->tables['accgroups'])->row()->name;
    }


    /**
     * Removes a user from a specific group
	 * 
     * @param int user_id
     * @param int group_id
     * @return boolean
     * @Author Claus Paludan
     */
    function remove_user_from_group($user_id,$group_id)
	{
        if (empty($user_id) || empty($group_id)) return FALSE;
		try
		{
			$this->db->where('user_id',$user_id);
			$this->db->where('group_id',$group_id);
			$this->db->delete($this->tables['accmember']);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
     * Removes a user from all groups
	 *
     * @param int user_id
     * @return boolean
     * @Author Claus Paludan
     */
    function remove_user_from_all_groups($user_id) {
        if (empty($user_id)) return FALSE;
	    $this->db->trans_begin();

		$this->db->where('user_id',$user_id);
		$this->db->delete($this->tables['accmember']);

		if ($this->enable_membership_backup)
		{
			$this->db->where('user_id',$user_id);
			$this->db->delete($this->tables['accmember_back']);
		}

		if ($this->db->trans_status() === FALSE)
	    {
			$this->db->trans_rollback();
			return FALSE;
	    }

	    $this->db->trans_commit();
	    return TRUE;
    }

    /**
     * Removes all users from a specific group
	 * 
     * @param int userid
     * @param int groupid
     * @return boolean
     * @Author Claus Paludan
     */
    function remove_all_users_from_group($group_id) {
        if (empty($group_id)) return FALSE;
		try
		{
			$this->db->where('group_id',$group_id);
			$this->db->delete($this->tables['accmember']);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

	/**
     * Adds a new group to the database
	 * 
     * @param string name
     * @param boolean active
     * @return int group_id
     * @Author Claus Paludan
     */
    function add_group($name,$active) {
		try
		{
			$data = array (
				'name' => $name,
				'active' => $active,
			);
			$this->db->insert($this->tables['accgroups'],$data);
			return $this->db->insert_id();
		} catch (Exception $err) {
			return FALSE;
		}
    }


    /**
     * Delete group and all grants and memberships to group
	 *
     * @param int group_id
     * @return boolean
     * @Author Claus Paludan
     */
    function remove_group($group_id) {
        if (empty($group_id)) return FALSE;
	    $this->db->trans_begin();

		$this->db->where('group_id',$group_id);
		$this->db->delete($this->tables['accgroups']);

		$this->db->where('group_id',$group_id);
		$this->db->delete($this->tables['accgrants']);

		if ($this->db->trans_status() === FALSE)
	    {
			$this->db->trans_rollback();
			return FALSE;
	    }

	    $this->db->trans_commit();
	    return TRUE;
    }


    /**
     * Activates a group
	 * 'active' can be used to determine what groups to show in drop down lists etc
	 *
     * @param int group_id
     * @return boolean
     * @Author Claus Paludan
     */
    function activate_group($group_id) {
        if (empty($group_id)) return FALSE;
		try
		{
			$data = array (
				'active' => 1
			);
			$this->db->where('group_id',$group_id);
			$this->db->update($this->tables['accgroups'],$data);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
     * Deactivates a group
	 * 'active' can be used to determine what groups to show in drop down lists etc
	 *
     * @param int group_id
     * @return boolean
     * @Author Claus Paludan
     */
    function inactivate_group($group_id) {
        if (empty($group_id)) return FALSE;
		try
		{
			$data = array (
				'active' => 0
			);
			$this->db->where('group_id',$group_id);
			$this->db->update($this->tables['accgroups'],$data);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }



    /**
	 * Change group - update name and/or active flag
     * @param int group_id
     * @param string name
     * @param boolean active
	 * @author Claus Paludan
     */
    function change_group($group_id,$name,$active) {
        if (empty($group_id)) return FALSE;
        if ($active == "on" || $active == "1" || $active == TRUE) {
            $active = 1;
        } else {
            $active = 0;
        }
        if ($group_id == "") {
            return $this->add_group($name,$active);
        } else {
			try
			{
				$data = array (
					'active' => $active,
					'name' => $name
				);
				$this->db->where('group_id',$group_id);
				$this->db->update($this->tables['accgroups'],$data);
				return TRUE;
			} catch (Exception $err) {
				return FALSE;
			}
        }
    }


    /**
	 * Add a resource
     * @param string name
     * @param boolean active
	 * @return resource_id
	 * @author Claus Paludan
     */
    function add_resource($name, $active=1) {
		try
		{
			$data = array(
				'name' => $name,
				'active' => $active
			);
			$this->db->insert($this->tables['accresources'],$data);
			return $this->db->insert_id();
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
	 * Remove a resource
	 * Removes both resource and grants to said resource
	 *
     * @param string name
     * @param boolean active
	 * @return resource_id
	 * @author Claus Paludan
     */
    function remove_resource($resource_id) {
        if (empty($resource_id)) return FALSE;
	    $this->db->trans_begin();

		$this->db->where('resource_id',$resource_id);
		$this->db->delete($this->tables['accresources']);

		$this->db->where('resource_id',$resource_id);
		$this->db->delete($this->tables['accgrants']);

		if ($this->db->trans_status() === FALSE)
	    {
			$this->db->trans_rollback();
			return FALSE;
	    }

	    $this->db->trans_commit();
	    return TRUE;
    }

    /**
     * @param int numAccResId
     * @param string vchAccessName
     * @param int bitActive
     * Change a resource
     */
    function update_resource($resource_id,$name,$active) {
        if (empty($resource_id)) return FALSE;
		try
		{
			$data = array(
				'name' => $name,
				'active' => $active
			);
			$this->db->where('resource_id',$resource_id);
			$this->db->update($this->tables['accresources'],$data);
			return TRUE;
		} catch (Exception $err) {
			return FALSE;
		}
    }

    /**
     * tager alle gruppemedlemskaber fra accmember og flytter til accmember_back
     * sletter alle entries i accmember bagefter
     * vampyrer, ynglinge, effe og bajar hekse skal ikke fjernes fra disse grupper???? FUCK
     * @param userid
     */
    function save_users_groups($user_id) {
        if (empty($user_id)) return FALSE;
		if (!$this->enable_membership_backup) return FALSE;
	    $this->db->trans_begin();

		$this->db->where('user_id',$user_id);
		$query = $this->db->get($this->tables['accmember']);

		foreach ($query->result() as $row)
		{
			$data = array(
				'user_id' => $row->user_id,
				'group_id' => $row->group_id
			);
			$this->db->insert($this->tables['accmember_back'],$data);
		}
//        $sql = "delete from accmember where numUserId=$userid AND numAccGrpId != 67 AND numAccGrpId != 81 AND numAccGrpId !=72 AND numAccGrpId != 63"; // Man kan ikke fjernes fra vampyr, yngling, bajar eller Effe
		$this->db->where('user_id',$user_id);
		$this->db->delete('accmember');
		if ($this->db->trans_status() === FALSE)
	    {
			$this->db->trans_rollback();
			return FALSE;
	    }

	    $this->db->trans_commit();
	    return TRUE;
    }

    /**
     * Moves all entries for user from accmember_back to accmember
     * All entries for that user in accmember_back will be deleted subsequently
	 *
     * @param userid
	 * @return boolean
	 * @author Claus Paludan
     */
    function restore_users_groups($user_id) {
        if (empty($user_id)) return FALSE;
		if (!$this->enable_membership_backup) return FALSE;
	    $this->db->trans_begin();

		$this->db->where('user_id',$user_id);
		$query = $this->db->get($this->tables['accmember_back']);

		foreach ($query->result() as $row)
		{
			$data = array(
				'user_id' => $row->user_id,
				'group_id' => $row->group_id
			);
			$this->db->insert($this->tables['accmember'],$data);
			if ($this->db->_error_number() == 1062)
			{
				
			}
		}
//                $sql = "delete from accmember_back where numUserId=$userid";

		$this->db->where('user_id',$user_id);
		$this->db->delete($this->tables['accmember_back']);
		if ($this->db->trans_status() === FALSE)
	    {
			$this->db->trans_rollback();
			return FALSE;
	    }

	    $this->db->trans_commit();
	    return TRUE;
    }

    /*********************************************************************************************/
    /*********************************************************************************************/
    /*********************************************************************************************/
    /* LEFNET SPECIFIC FUNCTIONS                                                                 */
    /* LEFNET SPECIFIC FUNCTIONS                                                                 */
    /* LEFNET SPECIFIC FUNCTIONS                                                                 */
    /*********************************************************************************************/
    /*********************************************************************************************/
    /*********************************************************************************************/

    /**
     * @param int groupid
     * @return 0:ok, 1+:errors
     * Removes all characters from a specific group
     */
    function removeAllCharsFromGroup($group_id) {
        if ($group_id == '' || $group_id = 0) return 1;
        $sql = "DELETE FROM accmember WHERE numAccGrpId=$group_id AND numUserId IN (SELECT numUserId FROM tbluser WHERE numownerid !=0);";
        $query = mysql_query($sql);
        if (mysql_error() != "") {
            show_sql_error(mysql_error(),$sql, __FUNCTION__, $_SERVER['PHP_SELF']);
            $status = 1;
        } else {
            $status = 0;
        }
        return $status;
    }
}
