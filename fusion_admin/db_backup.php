<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
	db_backup.php written by CrappoMan
	simonpatterson@dsl.pipex.com
---------------------------------------------------
*/
@require "../fusion_config.php";
require "../header.php";
require fusion_basedir."subheader.php";
require fusion_langdir."admin/admin_main.php";
require fusion_langdir."admin/admin_db-backup.php";
require "navigation.php";

if (!Admin()) header("Location: ../index.php");

//*** change this to where to store the backups.
$admindir=fusion_basedir."fusion_admin";
$dbbackupdir="$admindir/db_backups/";
	
if((!file_exists($dbbackupdir))&&is_writable($admindir)){
	mkdir($dbbackupdir, 0777);
}
if(isset($_POST['btn_cancel'])){
	header("Location: ".$PHP_SELF);
}elseif(isset($_POST['btn_view'])){
	ob_start();
	@ob_implicit_flush(0);
	readgzfile("$dbbackupdir$file");
	$contents=ob_get_contents();
	ob_end_clean();
	echo "<pre>".htmlspecialchars($contents)."</pre>";
	exit;
}elseif($action=="delete"){
	if(file_exists("$dbbackupdir$file")){@unlink("$dbbackupdir$file");}
	header("Location: ".$PHP_SELF);
}elseif($action=="download"){
	if(preg_match("/.gz$/",$file)){
		header("Content-type: application/x-gzip");
	}else{
		header("Content-type: text/plain");
	}
	header("Content-Disposition: attachment; filename=\"$file\"\n");
	@readfile("$dbbackupdir$file");
	exit;
}

if(isset($_POST['btn_create_backup'])){
	$db_tables=$_POST['db_tables'];
	if(count($db_tables)>0){
		$crlf="\n";
		ob_start();
		@ob_implicit_flush(0);
		echo "#----------------------------------------------------------".$crlf;
		echo "# PHP-Fusion SQL Data Dump".$crlf;
		echo "# Database Name: `$dbname`".$crlf;
		echo "# Table Prefix: `$fusion_prefix`".$crlf;
		echo "# Date: `".date("d/m/Y H:i")."`".$crlf;
		echo "#----------------------------------------------------------".$crlf;
		dbquery('SET SQL_QUOTE_SHOW_CREATE=1');
		foreach($db_tables as $table){
			@set_time_limit(1200);
			dbquery("OPTIMIZE TABLE $table");
			echo $crlf."#".$crlf."# Structure for Table `".$table."`".$crlf."#".$crlf;
			echo "DROP TABLE IF EXISTS `$table`;$crlf";
			$row=dbarraynum(dbquery("SHOW CREATE TABLE $table"));
			echo $row[1].";".$crlf;
			$result=dbquery("SELECT * FROM $table");
			if($result&&dbrows($result)){
				echo $crlf."#".$crlf."# Table Data for `".$table."`".$crlf."#".$crlf;
				$column_list="";
				$num_fields=mysql_num_fields($result);
				for($i=0;$i<$num_fields;$i++){
					$column_list.=(($column_list!="")?", ":"")."`".mysql_field_name($result,$i)."`";
				}
			}
			while($row=dbarraynum($result)){
				$dump="INSERT INTO `$table` ($column_list) VALUES (";
				for($i=0;$i<$num_fields;$i++){
					$dump.=($i>0)?", ":"";
					if(!isset($row[$i])){
						$dump.="NULL";
					}elseif($row[$i]=="0"||$row[$i]!=""){
						$type=mysql_field_type($result,$i);
						if($type=="tinyint"||$type=="smallint"||$type=="mediumint"||$type=="int"||$type=="bigint"||$type=="timestamp"){
							$dump.=$row[$i];
						}else{
							$search_array=array('\\','\'',"\x00","\x0a","\x0d","\x1a");
							$replace_array=array('\\\\','\\\'','\0','\n','\r','\Z');
							$row[$i]=str_replace($search_array,$replace_array,$row[$i]);
							$dump.="'$row[$i]'";
						}
					}else{
					$dump.="''";
					}
				}
				$dump.=');';
				echo $dump.$crlf;
			}
		}
		$contents=ob_get_contents();
		ob_end_clean();
		@umask(0111);
		$file=$_POST['backup_filename'].".sql";
		$fp=fopen("$dbbackupdir$file","w");
		$ok=fwrite($fp,$contents);
		fclose($fp);
		if($_POST['backup_type']==".gz"){if(gzcompressfile("$dbbackupdir$file",9)!=false){unlink("$dbbackupdir$file");}}
	}
	header("Location: ".$PHP_SELF);
}elseif(isset($_POST['btn_do_restore'])){
	$sql=gzfile("$dbbackupdir$file");
	if((preg_match("/# Database Name: `(.+?)`/i", $sql[2], $tmp1)<>0)&&(preg_match("/# Table Prefix: `(.+?)`/i", $sql[3], $tmp2)<>0)){
		$inf_dbname=$tmp1[1];
		$inf_tblpre=$tmp2[1];
		$sql=array_slice($sql,7);
		$sqls=preg_split("/;$/m",implode("",$sql));
		if(count($list_tbl)>0){
			foreach($sqls as $sql){
				$sql=html_entity_decode($sql, ENT_QUOTES);
				if(preg_match("/^DROP TABLE IF EXISTS `(.*?)`/im",$sql,$tmp)<>0){
					$tbl=$tmp[1];
					if(in_array($tbl, $list_tbl)){
						$sql=preg_replace("/^DROP TABLE IF EXISTS `$inf_tblpre(.*?)`/im","DROP TABLE IF EXISTS `$restore_tblpre\\1`",$sql);
						mysql_unbuffered_query($sql);
					}
				}
				if(preg_match("/^CREATE TABLE `(.*?)`/im",$sql,$tmp)<>0){
					$tbl=$tmp[1];
					if(in_array($tbl, $list_tbl)){
						$sql=preg_replace("/^CREATE TABLE `$inf_tblpre(.*?)`/im","CREATE TABLE `$restore_tblpre\\1`",$sql);
						mysql_unbuffered_query($sql);
					}
				}
			}
		}
		if(count($list_ins)>0){
			foreach($sqls as $sql){
				if(preg_match("/INSERT INTO `(.*?)`/i",$sql,$tmp)<>0){
					$ins=$tmp[1];
					if(in_array($ins, $list_ins)){
						$sql=preg_replace("/INSERT INTO `$inf_tblpre(.*?)`/i","INSERT INTO `$restore_tblpre\\1`",$sql);
						mysql_unbuffered_query($sql);
					}
				}
			}
		}
		header("Location: ".$PHP_SELF);
	}else{
		opentable(LAN_400);
		echo "<center><b>".LAN_401."</b><br><br>".LAN_402."<br><br>";
		echo "<form action='$PHP_SELF' name='frm_info' method='post'>";
		echo "<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".LAN_403."'></td></tr>";
		echo "</form></center>";
		closetable();
	}
}elseif($action=="info"){
	$sql=gzfile("$dbbackupdir$file");
	$info_tbls=array();
	$info_ins_cnt=array();
	$info_inserts=array();
	foreach($sql as $sqlline){
		if(preg_match_all("/^# Database Name: `(.*?)`/", $sqlline, $sqlinfo)<>0){ $info_dbname=$sqlinfo[1][0]; }
		if(preg_match_all("/^# Table Prefix: `(.*?)`/", $sqlline, $sqlinfo)<>0){ $info_tblpref=$sqlinfo[1][0]; }
		if(preg_match_all("/^# Date: `(.*?)`/", $sqlline, $sqlinfo)<>0){ $info_date=$sqlinfo[1][0]; }
		if(preg_match_all("/^CREATE TABLE `(.+?)`/i", $sqlline, $sqlinfo)<>0){ $info_tbls[]=$sqlinfo[1][0]; }
		if(preg_match_all("/^INSERT INTO `(.+?)`/i", $sqlline, $sqlinfo)<>0){ $info_ins_cnt[]=$sqlinfo[1][0]; }
	}
	$insert_ins_cnt=array_count_values($info_ins_cnt);
	$table_opt_list="";
	$insert_opt_list="";
	sort($info_tbls);
	foreach($info_tbls as $key=>$info_tbl){
		$table_opt_list.="$info_tbl<br>";
		if(isset($insert_ins_cnt[$info_tbl])){$insert_opt_list.="($insert_ins_cnt[$info_tbl])";}
		$insert_opt_list.="<br>";
	}
	opentable(LAN_410);
	echo "<form action='$PHP_SELF' name='frm_info' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='4' class='altbg' align='left'>".LAN_411."</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_412."</td>
<td colspan='2'>$file</td>
</tr>
<tr><td class='alt' align='right'>".LAN_413."</td>
<td colspan='2'>$info_date</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_414."</td>
<td colspan='2'>$info_dbname</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_415."</td>
<td colspan='2'>$info_tblpref</td>
</tr>
<tr valign='top'><td class='alt' align='right'>".LAN_416."</td>
<td>$table_opt_list</td>
<td align='right'>$insert_opt_list</td></tr>
</tr>
<tr>
<td colspan='3' align='center'><hr>
<input type='hidden' name='file' value='$file'>
<input class='button' type='submit' name='btn_view' style='width:100px;' value='".LAN_417."'>
<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".LAN_418."'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}elseif($action=="restore"){
	$sql=gzfile("$dbbackupdir$file");
	$info_tbls=array();
	$info_ins_cnt=array();
	$info_inserts=array();
	foreach($sql as $sqlline){
		if(preg_match_all("/^# Database Name: `(.*?)`/", $sqlline, $sqlinfo)<>0){ $info_dbname=$sqlinfo[1][0]; }
		if(preg_match_all("/^# Table Prefix: `(.*?)`/", $sqlline, $sqlinfo)<>0){ $info_tblpref=$sqlinfo[1][0]; }
		if(preg_match_all("/^# Date: `(.*?)`/", $sqlline, $sqlinfo)<>0){ $info_date=$sqlinfo[1][0]; }
		if(preg_match_all("/^CREATE TABLE `(.+?)`/i", $sqlline, $sqlinfo)<>0){ $info_tbls[]=$sqlinfo[1][0]; }
		if(preg_match_all("/^INSERT INTO `(.+?)`/i", $sqlline, $sqlinfo)<>0){
			if(!in_array($sqlinfo[1][0], $info_inserts)) { $info_inserts[]=$sqlinfo[1][0]; }
			$info_ins_cnt[]=$sqlinfo[1][0];
		}
	}
	$table_opt_list="";
	sort($info_tbls);
	foreach($info_tbls as $key=>$info_tbl){
		$table_opt_list.="<option value='$info_tbl' selected>$info_tbl</option>";
	}
	$insert_ins_cnt=array_count_values($info_ins_cnt);
	$insert_opt_list="";
	sort($info_inserts);
	foreach($info_inserts as $key=>$info_insert){
		$insert_opt_list.="<option value='$info_insert' selected>$info_insert (".$insert_ins_cnt[$info_insert].")</option>";
	}
	$maxrows=max(count($info_tbls),count($info_inserts));
	opentable(LAN_430);
	echo "<script type='text/javascript'>
<!--
function tableSelectAll(){for(i=0;i<document.frmrestore.elements['list_tbl[]'].length;i++){document.frmrestore.elements['list_tbl[]'].options[i].selected=true;}}
function tableSelectNone(){for(i=0;i<document.frmrestore.elements['list_tbl[]'].length;i++){document.frmrestore.elements['list_tbl[]'].options[i].selected=false;}}
function populateSelectAll(){for(i=0;i<document.frmrestore.elements['list_ins[]'].length;i++){document.frmrestore.elements['list_ins[]'].options[i].selected=true;}}
function populateSelectNone(){for(i=0;i<document.frmrestore.elements['list_ins[]'].length;i++){document.frmrestore.elements['list_ins[]'].options[i].selected=false;}}
//-->
</script>
<form action='$PHP_SELF' name='frmrestore' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='2' class='altbg' align='left'>".LAN_431."</td>
</tr>
<tr>
<td colspan='2'><span class='alt'>".LAN_432."</span> $file</td>
</tr>
<tr>
<td colspan='2'><span class='alt'>".LAN_433."</span> $info_dbname</td>
</tr>
<tr>
<td colspan='2'><span class='alt'>".LAN_434."</span> $info_date</td>
</tr>
<tr>
<td colspan='2' class='alt'>".LAN_435."
<input class='textbox' type='text' name='restore_tblpre' value='$info_tblpref' style='width:150px'>
</td>
</tr>
<tr>
<td class='alt' valign='top'>".LAN_436."<br>
<select style='width:180px;' class='textbox' id='list_tbl' name='list_tbl[]' size='$maxrows' multiple>".$table_opt_list."</select>
<div align='center'>".LAN_438."&nbsp;&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:tableSelectAll()\">".LAN_439."</a>]&nbsp;&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:tableSelectNone()\">".LAN_440."</a>]</div></td>
<td class='alt' valign='top'>".LAN_437."<br>
<select style='width:180px;' class='textbox' id='list_ins' name='list_ins[]' size='$maxrows' multiple>".$insert_opt_list."</select>
<div align='center'>".LAN_438."&nbsp;&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:populateSelectAll()\">".LAN_439."</a>]&nbsp;&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:populateSelectNone()\">".LAN_440."</a>]</div></td>
</tr>
<tr>
<td colspan='2' align='center'><hr>
<input type='hidden' name='file' value='$file'>
<input class='button' type='submit' name='btn_do_restore' style='width:100px;' value='".LAN_441."'>
<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".LAN_442."'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}else{
	$table_opt_list="";
	$result=dbquery("SHOW tables");
	while($row=dbarraynum($result)){
		$table_opt_list.="<option value='".$row[0]."'";
		if(preg_match("/^".$fusion_prefix."/i",$row[0])){
			$table_opt_list.=" selected";
		}
		$table_opt_list.=">".$row[0]."</option>\n";
	}
	opentable(LAN_450);
	echo "
<script type='text/javascript'>
<!--
function backupDelete(what){if(confirm('".LAN_466."\\n\\n'+what)){window.location='$PHP_SELF?action=delete&file='+what;}}
function backupSelectCore(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=(document.frmbackup.elements['db_tables[]'].options[i].text).match(/^$fusion_prefix/);}}
function backupSelectAll(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=true;}}
function backupSelectNone(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=false;}}
//-->
</script>
<form action='$PHP_SELF' name='frmbackup' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td valign='top'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='2' class='altbg' align='left'>".LAN_451."</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_452."</td>
<td>$dbname</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_453."</td>
<td>$fusion_prefix</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_454."</td>
<td>".parseByteSize(get_database_size(),2,false)." (".get_table_count()." tables)</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_455."</td>
<td>".parseByteSize(get_database_size($fusion_prefix),2,false)." (".get_table_count($fusion_prefix)." tables)</td>
</tr>
<tr>
<td colspan='2' class='altbg' align='left'>".LAN_456."</td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_457."</td>
<td><input class='textbox' type='text' name='backup_filename' value='backup_".date('Y-m-d_Hi')."' style='width:200px;'></td>
</tr>
<tr>
<td class='alt' align='right'>".LAN_458."</td>
<td><select class='textbox' name='backup_type'>
<option value='.gz' selected>.sql.gz ".LAN_459."</option>
<option value='.sql'>.sql</option>
</select></td>
</tr>
</table>
</td>
<td valign='top'>
<table border='0' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td class='altbg'>".LAN_460."</td>
</tr>
<tr>
<td>
<select style='margin:5px 0px' class='textbox' id='tablelist' name='db_tables[]' size='17' multiple>".$table_opt_list."</select>
<div align='center'>".LAN_461."&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectCore()\">".LAN_462."</a>]&nbsp;&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectAll()\">".LAN_463."</a>]&nbsp;&nbsp;[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectNone()\">".LAN_464."</a>]</div>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan='2' align='center'><hr>
<input class='button' type='submit' name='btn_create_backup' style='width:100px;' value='".LAN_465."'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable(LAN_480);
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr><td>";
	$dh=@opendir("$dbbackupdir");
	if($dh!=false){
		$filelist=array();
		while(false!==($file=@readdir($dh))){
			if(is_file("$dbbackupdir$file")&&$file!="."&&$file!=".."&&preg_match("/.(sql|gz)$/",$file)){
				$filelist[]=array(filemtime("$dbbackupdir$file"), $file);
			}
		}
		@closedir($dh);
		if(!empty($filelist)){
			echo "<tr><td class='altbg'>".LAN_481."</td>";
			echo "<td class='altbg' align='center'>".LAN_482."</b></td>";
			echo "<td class='altbg' align='right'>".LAN_483."</td>";
			echo "<td class='altbg' align='right'>".LAN_484."</td></tr>";
			rsort($filelist);
			$i=0;
			foreach($filelist as $key=>$file){
				if(($i%2)==0){echo "<tr class='forum1'>";}else{echo "<tr class='forum2'>";}
				echo "<td><a href='$PHP_SELF?action=download&file=$file[1]'>$file[1]</a></td>";
				echo "<td align='center'>".strftime($settings[shortdate], $file[0])."</td>";
				echo "<td align='right'>".parseByteSize(@filesize("$dbbackupdir$file[1]"),2,false)."</td>";
				echo "<td align='right'>";
				echo "[<a href=\"javascript:backupDelete('$file[1]')\">".LAN_485."</a>]&nbsp;";
				echo "[<a href='$PHP_SELF?action=restore&file=$file[1]'>".LAN_486."</a>]&nbsp;";
				echo "[<a href='$PHP_SELF?action=info&file=$file[1]'>".LAN_487."</a>]";
				echo "</td></tr>";
				$i++;
			}
		}else{
			echo "<tr><td colspan='4' align='center'>".LAN_488."</td></tr>";
		}
	}else{
		echo "<tr><td colspan='4' align='center'>".LAN_489."'$dbbackupdir'".LAN_490."</td></tr>";
	}
	echo "</table>";
	closetable();
}

function get_database_size($prefix=""){
	global $dbname;
	$db_size=0;
	$result=dbquery("SHOW TABLE STATUS FROM $dbname");
	while($row=dbarray($result)){
		if((eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Type']))&&(preg_match("/^".$prefix."/",$row['Name']))){
			$db_size+=$row['Data_length']+$row['Index_length'];
		}
	}
	return $db_size;
}

function get_table_count($prefix=""){
	global $dbname;
	$tbl_count=0;
	$result=dbquery("SHOW TABLE STATUS FROM $dbname");
	while($row=dbarray($result)){
		if((eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Type']))&&(preg_match("/^".$prefix."/",$row['Name']))){
			$tbl_count++;
		}
	}
	return $tbl_count;
}

function gzcompressfile($source,$level=false){
	$dest=$source.'.gz';
	$mode='wb'.$level;
	$error=false;
	if($fp_out=gzopen($dest,$mode)){
		if($fp_in=fopen($source,'rb')){
			while(!feof($fp_in))
			gzputs($fp_out,fread($fp_in,1024*512));
			fclose($fp_in);
		}else $error=true;
		gzclose($fp_out);
	}else $error=true;
	if($error)return false; else return $dest;
}

echo "</td>\n";
require "../footer.php";
?>