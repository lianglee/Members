<?php
/**
 * Open Source Social Network
 *
 * @packageOpen Source Social Network
 * @author    Open Social Website Core Team <info@informatikon.com>
 * @copyright 2014 iNFORMATIKON TECHNOLOGIES
 * @license   General Public Licence http://www.opensource-socialnetwork.org/licence
 * @link      http://www.opensource-socialnetwork.org/licence
 */
define('__MEMBERS__', ossn_route()->com . 'Members/');
/**
 * Initialize Members component
 *
 * @return void
 * @access private
 */
function ossn_members() {
		ossn_register_page('members', 'ossn_members_list_pagehandler');
    	$icon = ossn_site_url('components/OssnProfile/images/friends.png');
    	ossn_register_sections_menu('newsfeed', array(
        	'text' => ossn_print('com:ossn:site:members'),
        	'url' => ossn_site_url('members'),
        	'section' => 'links',
        	'icon' => $icon
    	));			
}
/**
 * Members page handler
 * 
 * @note Please don't call this function directly in your code.
 *
 * @return mixed
 * @access private
 */
function ossn_members_list_pagehandler() {
		$layout = 'contents';
		if(!ossn_isLoggedin()) {
			$layout = 'contents';
		}
		$title               = ossn_print('com:ossn:site:members');
		$contents['content'] = ossn_plugin_view('members/all');
		$content             = ossn_set_page_layout($layout, $contents);
		echo ossn_view_page($title, $content);
}
/**
 * Generate members list
 * 
 * @param array $params A extra options like count, limit etc.
 *
 * @return array|false
 */
function ossn_generate_list($params = array()) {
		$database = new OssnDatabase;
		//prepare default attributes
		$default  = array(
				'limit' => false,
				'order_by' => false,
				'offset' => input('offset', '', 1),
				'page_limit' => ossn_call_hook('pagination', 'page_limit', false, 10), //call hook for page limit
				'count' => false
		);
		$options  = array_merge($default, $params);
		$wheres   = array();
		
		//validate offset values
		if($options['limit'] !== false && $options['limit'] != 0 && $options['page_limit'] != 0) {
				$offset_vals = ceil($options['limit'] / $options['page_limit']);
				$offset_vals = abs($offset_vals);
				$offset_vals = range(1, $offset_vals);
				if(!in_array($options['offset'], $offset_vals)) {
						return false;
				}
		}
		//get only required result, don't bust your server memory
		$getlimit = $database->generateLimit($options['limit'], $options['page_limit'], $options['offset']);
		if($getlimit) {
				$options['limit'] = $getlimit;
		}
		
		//prepare search
		$params = array();
		
		$params['from']     = 'ossn_users as u';
		$params['order_by'] = $options['order_by'];
		$params['limit']    = $options['limit'];
		
		$data = $database->select($params, true);
		
		//prepare count data;
		if($options['count'] === true) {
				unset($params['params']);
				unset($params['limit']);
				$count           = array();
				$count['params'] = array(
						"count(*) as total"
				);
				$count           = array_merge($params, $count);
				return $database->select($count)->total;
		}
		if($data) {
				foreach($data as $user) {
						$users[] = ossn_user_by_guid($user->guid);
				}
				return $users;
		}
		return false;
}
//initilize ossn wall
ossn_register_callback('ossn', 'init', 'ossn_members');
