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

$users      = ossn_generate_list();
$count      = ossn_generate_list(array(
		'count' => true
));
$list       = ossn_plugin_view('output/users', array(
		'users' => $users
));
$pagination = ossn_view_pagination($count);
$lang       = ossn_print('com:ossn:site:members');
$html       = <<<EOD
 <div class="ossn-search-page">
    <div class="search-data">
 		<strong style='font-size:14px;margin-bottom:5px;display: block;'>$lang</strong>	
        $list  
		$pagination
	</div>
</div>
EOD;
echo $html;