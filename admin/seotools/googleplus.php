<?php

/**
 * @Project NUKEVIET 3.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2012 VINADES.,JSC. All rights reserved
 * @Createdate 2-9-2010 14:43
 */

if( ! defined( 'NV_IS_FILE_SEOTOOLS' ) ) die( 'Stop!!!' );

$page_title = $lang_module['googleplus_page_title'];

// Sua
if( $nv_Request->isset_request( 'edit', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$gid = $nv_Request->get_int( 'gid', 'post', 0 );
	$title = $nv_Request->get_title( 'title', 'post', '', 1 );

	if( empty( $title ) )
	{
		die( "NO" );
	}
	$sql = "UPDATE `" . $db_config['prefix'] . "_googleplus` SET `title`=" . $db->dbescape( $title ) . ", `edit_time`=" . NV_CURRENTTIME . " WHERE `gid`=" . $gid;
	if( ! $db->exec( $sql ) )
	{
		die( "NO" );
	}
	die( "OK" );
}

// Them
if( $nv_Request->isset_request( 'add', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$title = $nv_Request->get_title( 'title', 'post', '', 1 );
	$idprofile = $nv_Request->get_title( 'idprofile', 'post', '', 1 );

	if( empty( $title ) )
	{
		die( "NO" );
	}

	$sql = "SELECT MAX(`weight`) FROM `" . $db_config['prefix'] . "_googleplus`";
	list( $weight ) = $db->sql_fetchrow( $db->sql_query( $sql ) );
	$weight = intval( $weight ) + 1;
	$query = "INSERT INTO `" . $db_config['prefix'] . "_googleplus`
		(`gid`, `title`, `idprofile`, `weight`, `add_time`, `edit_time`) VALUES (
		NULL, " . $db->dbescape( $title ) . ", " . $db->dbescape( $idprofile ) . ", " . $weight . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
	if( ! $db->sql_query_insert_id( $query ) )
	{
		die( "NO" );
	}
	nv_del_moduleCache( 'seotools' );
	die( "OK" );
}

// Chinh thu tu
if( $nv_Request->isset_request( 'changeweight', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$gid = $nv_Request->get_int( 'gid', 'post', 0 );
	$new_vid = $nv_Request->get_int( 'new_vid', 'post', 0 );

	if( empty( $gid ) ) die( "NO" );

	$query = "SELECT * FROM `" . $db_config['prefix'] . "_googleplus` WHERE `gid`=" . $gid;
	$result = $db->sql_query( $query );
	$numrows = $db->sql_numrows( $result );
	if( $numrows != 1 ) die( 'NO' );

	$query = "SELECT `gid` FROM `" . $db_config['prefix'] . "_googleplus` WHERE `gid`!=" . $gid . " ORDER BY `weight` ASC";
	$result = $db->sql_query( $query );
	$weight = 0;
	while( $row = $db->sql_fetchrow( $result ) )
	{
		++$weight;
		if( $weight == $new_vid ) ++$weight;
		$sql = "UPDATE `" . $db_config['prefix'] . "_googleplus` SET `weight`=" . $weight . " WHERE `gid`=" . $row['gid'];
		$db->sql_query( $sql );
	}
	$sql = "UPDATE `" . $db_config['prefix'] . "_googleplus` SET `weight`=" . $new_vid . " WHERE `gid`=" . $gid;
	$db->sql_query( $sql );
	die( "OK" );
}

// Xoa
if( $nv_Request->isset_request( 'del', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$gid = $nv_Request->get_int( 'gid', 'post', 0 );

	list( $gid ) = $db->sql_fetchrow( $db->sql_query( "SELECT `gid` FROM `" . $db_config['prefix'] . "_googleplus` WHERE `gid`=" . $gid ) );

	if( $gid )
	{
		$db->sql_query( "UPDATE `" . NV_MODULES_TABLE . "` SET `gid`=0 WHERE `gid`='" . $gid . "'" );
		nv_del_moduleCache( 'modules' );

		$query = "DELETE FROM `" . $db_config['prefix'] . "_googleplus` WHERE `gid`=" . $gid;
		if( $db->exec( $query ) )
		{
			// fix weight question
			$sql = "SELECT `gid` FROM `" . $db_config['prefix'] . "_googleplus` ORDER BY `weight` ASC";
			$result = $db->sql_query( $sql );
			$weight = 0;
			while( $row = $db->sql_fetchrow( $result ) )
			{
				++$weight;
				$sql = "UPDATE `" . $db_config['prefix'] . "_googleplus` SET `weight`=" . $weight . " WHERE `gid`=" . $row['gid'];
				$db->sql_query( $sql );
			}
			$db->sql_freeresult( $result );
			nv_del_moduleCache( 'seotools' );
			die( "OK" );
		}
	}
	die( "NO" );
}

// Change for module
if( $nv_Request->isset_request( 'changemod', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$title = $nv_Request->get_title( 'changemod', 'post', 0 );

	if( ! isset( $site_mods[$title] ) ) die( "NO" );

	$gid = $nv_Request->get_int( 'gid', 'post', 0 );

	$db->sql_query( "UPDATE `" . NV_MODULES_TABLE . "` SET `gid`=" . $gid . " WHERE `title`='" . $title . "'" );
	nv_del_moduleCache( 'modules' );
	die( "OK" );
}
$xtpl = new XTemplate( 'googleplus.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );

// Danh sach
if( $nv_Request->isset_request( 'qlist', 'post' ) )
{
	if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );

	$array_googleplus = array();
	$sql = "SELECT * FROM `" . $db_config['prefix'] . "_googleplus` ORDER BY `weight` ASC";
	$result = $db->sql_query( $sql );
	while( $row = $db->sql_fetch_assoc( $result ) )
	{
		$array_googleplus[$row['gid']] = $row;
	}
	$numgoogleplus = sizeof( $array_googleplus );

	if( $numgoogleplus )
	{
		$number = 0;
		foreach( $site_mods as $title => $row )
		{
			$row['title'] = $title;
			$row['number'] = ++$number;
			$xtpl->assign( 'ROW', $row );

			foreach( $array_googleplus as $gid => $grow )
			{
				$grow['selected'] = ( $gid == $row['gid'] ) ? " selected=\"selected\"" : "";
				$xtpl->assign( 'GOOGLEPLUS', $grow );
				$xtpl->parse( 'main.module.loop.gid' );
			}
			$xtpl->parse( 'main.module.loop' );
		}
		$xtpl->parse( 'main.module' );
	}
	foreach( $array_googleplus as $gid => $row )
	{
		$xtpl->assign( 'ROW', array(
			'gid' => $row['gid'],
			'idprofile' => $row['idprofile'],
			'title' => $row['title']
		) );

		for( $i = 1; $i <= $numgoogleplus; ++$i )
		{
			$xtpl->assign( 'WEIGHT', array(
				'key' => $i,
				'title' => $i,
				'selected' => $i == $row['weight'] ? ' selected=\'selected\'' : ''
			) );
			$xtpl->parse( 'main.googleplus.weight' );
		}

		$xtpl->parse( 'main.googleplus' );
	}
	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
}
else
{
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->parse( 'load' );
	$contents = $xtpl->text( 'load' );
	$contents = nv_admin_theme( $contents );
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';

?>