<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------+
| Database Backup developed by CrappoMan
| email: crappoman@email.com
+----------------------------------------------------*/
require_once "../maincore.php";

//////////////////////////////////////////////////////////////////
// full server path to backup folder
// or blank for immediate upload/download of backups
//   e.g: C:/nonweb/backups/   (must include trailing slash)
// (FOR SECURITY REASONS MUST BE PRIVATE - NOT WEB ACCESSIBLE)

// echo BASEDIR;
// exit();

//$settings['db_backup_folder']='../../db_backups/';
//$settings['db_backup_folder']='C:/_fusion6/db_backups/';
//$settings['db_backup_folder']='';

//////////////////////////////////////////////////////////////////

if (!checkrights("DB")) fallback("../index.php");

// @ini_set('memory_limit', '100M');

$backupdir=$settings['db_backup_folder'];
if (!isset($action)) $action = "";

if($action == "download"){
  require_once INCLUDES."class.httpdownload.php";
  $dl = new httpdownload;
  $dl->set_byfile($backupdir.$file);
  if(substr($file,-2)==".gz"){
    $dl->set_mime('application/x-gzip gz tgz');
  }else{
    $dl->set_mime('text/plain');
  }
  $dl->use_resume = true;
  $dl->download();
  exit;
}elseif(isset($_POST['btn_create_backup'])) {
  $db_tables=$_POST['db_tables'];
  if(count($db_tables)>0){
    $SQLresult ="#----------------------------------------------------------\r\n";
    $SQLresult.="# PHP-Fusion SQL Data Dump\r\n";
    $SQLresult.="# Database Name: `{$db_name}`\r\n";
    $SQLresult.="# Table Prefix: `{$db_prefix}`\r\n";
    $SQLresult.="# Date: `".date("d/m/Y H:i")."`\r\n";
    $SQLresult.="#----------------------------------------------------------\r\n";
    dbquery('SET SQL_QUOTE_SHOW_CREATE=1');
    foreach($db_tables as $table){
      @set_time_limit(1200);
      dbquery("OPTIMIZE TABLE `{$table}`");
      $SQLcreate=dbarraynum(dbquery("SHOW CREATE TABLE `{$table}`"));
      $SQLresult.="\r\n#\r\n# Structure for Table `{$table}`\r\n#\r\n";
      $SQLresult.="DROP TABLE IF EXISTS `{$table}`;\r\n";
      $SQLresult.="{$SQLcreate[1]};\r\n";
      if(!isset($backup_structonly)) {
        dbquery("LOCK TABLES `{$table}` WRITE;");
        $SQLselect=dbquery("SELECT * FROM `{$table}`");
        if($SQLselect && dbrows($SQLselect)){
          $SQLresult.="\r\n#\r\n# Table Data for `{$table}`\r\n#\r\n";
          while($row=dbarray($SQLselect)) {
            $fields='';
            $values='';
            $j = 0;
            while(list($col_name, $col_value) = each($row)) {
              if(!is_int($col_name)) {
                $fields.="`$col_name`,";
                $values.="'".mysql_escape_string($col_value)."',";
              }
            }
            $SQLfields=substr($fields, 0, strlen($fields)-1);
            $SQLvalues=substr($values, 0, strlen($values)-1);
            $SQLresult.="INSERT INTO `{$table}` ({$SQLfields}) VALUES ({$SQLvalues});\r\n";
          }
        }
        dbquery("UNLOCK TABLES;");
      }
    }
    $file=$_POST['backup_filename'].".sql";
    if(!empty($backupdir)) {
      @umask(0111);
      $fp=fopen("{$backupdir}{$file}","w");
      $ok=fwrite($fp,$SQLresult);
      fclose($fp);
      if($_POST['backup_type']==".gz"){if(gzcompressfile("{$backupdir}{$file}",9)!=false){unlink("{$backupdir}{$file}");}}
    }else{
      require_once INCLUDES."class.httpdownload.php";
      $dl = new httpdownload;
      $dl->use_resume = false;
      if($_POST['backup_type']==".gz"){
        $dl->set_mime('application/x-gzip gz tgz');
        $dl->set_bydata(gzencode($SQLresult,9));
        $dl->set_filename($file.'.gz');
      }else{
        $dl->set_mime('text/plain');
        $dl->set_bydata($SQLresult);
        $dl->set_filename($file);
      }
      $dl->download();
      exit;
    }
  }
  redirect(FUSION_SELF);
}elseif($action == "delete"){
  if(!empty($backupdir)) {
    $backup_file=$backupdir.$file;
    if(file_exists($backup_file)){@unlink($backup_file);}
    redirect(FUSION_SELF);
  }
}

require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include_once LOCALE.LOCALESET."admin/db-backup.php";

if(isset($_POST['btn_cancel'])){
  if(empty($backupdir) && isset($_POST['file']) && file_exists($_POST['file'])) unlink($file);
  redirect(FUSION_SELF);
}elseif(isset($_POST['btn_restore_backup']) || ($action == "restore")){
  if(!empty($backupdir)) {
    $backup_name=$file;
    $backup_file=$backupdir.$file;
    $backup_file_tmp=$backup_name;
    if(file_exists($backup_file)){
      $backup_data=gzfile($backup_file);
    }
  }else{
    if(is_uploaded_file($_FILES['upload_backup_file']['tmp_name'])){
      $backup_name=basename($_FILES['upload_backup_file']['name']);
      $backup_file=dirname($_FILES['upload_backup_file']['tmp_name'])."/{$backup_name}";
      $backup_file_tmp=$backup_file;
      move_uploaded_file($_FILES['upload_backup_file']['tmp_name'], $backup_file);
      $backup_data=gzfile($backup_file);
    }
  }
  $info_tbls=array();
  $info_ins_cnt=array();
  $info_inserts=array();
  foreach($backup_data as $resultline){
    if(preg_match_all("/^# Database Name: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_dbname=$resultinfo[1][0]; }
    if(preg_match_all("/^# Table Prefix: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_tblpref=$resultinfo[1][0]; }
    if(preg_match_all("/^# Date: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_date=$resultinfo[1][0]; }
    if(preg_match_all("/^CREATE TABLE `(.+?)`/i", $resultline, $resultinfo)<>0){ $info_tbls[]=$resultinfo[1][0]; }
    if(preg_match_all("/^INSERT INTO `(.+?)`/i", $resultline, $resultinfo)<>0){
      if(!in_array($resultinfo[1][0], $info_inserts)) { $info_inserts[]=$resultinfo[1][0]; }
      $info_ins_cnt[]=$resultinfo[1][0];
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
  opentable($locale['417']);
  echo "<script type='text/javascript'>
<!--
function tableSelectAll(){for(i=0;i<document.frmrestore.elements['list_tbl[]'].length;i++){document.frmrestore.elements['list_tbl[]'].options[i].selected=true;}}
function tableSelectNone(){for(i=0;i<document.frmrestore.elements['list_tbl[]'].length;i++){document.frmrestore.elements['list_tbl[]'].options[i].selected=false;}}
function populateSelectAll(){for(i=0;i<document.frmrestore.elements['list_ins[]'].length;i++){document.frmrestore.elements['list_ins[]'].options[i].selected=true;}}
function populateSelectNone(){for(i=0;i<document.frmrestore.elements['list_ins[]'].length;i++){document.frmrestore.elements['list_ins[]'].options[i].selected=false;}}
//-->
</script>
<form action='".FUSION_SELF."' name='frmrestore' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td class='forum-caption' colspan='2' align='left'>".$locale['430']."</td>
</tr>
<tr>
<td colspan='2'>
 <table align='left' width='100%' cellspacing='0' cellpadding='0' class=''>
  <tr>
   <td valign='top'>
<tr>
<td class='tbl1' align='right'>".$locale['408']."</td>
<td class='tbl1'>{$backup_name}</td>
</tr>
<tr>
<td class='tbl1' align='right'>".$locale['431']."</td>
<td class='tbl1'>{$info_date}</td>
</tr>
<tr>
<td class='tbl1' align='right'>".$locale['402']."</td>
<td class='tbl1'>{$info_dbname}</td>
</tr>
<tr>
<td class='tbl1' align='right'>".$locale['403']."</td>
<td class='tbl1'><input class='textbox' type='text' name='restore_tblpre' value='{$info_tblpref}' style='width:150px'></td>
</tr>
 </table>
</td>
</tr>
<tr>
<td class='tbl1' valign='top'>".$locale['432']."<br>
<select style='width:180px;' class='textbox' id='list_tbl' name='list_tbl[]' size='$maxrows' multiple>".$table_opt_list."</select>
<div align='center'>".$locale['412']." [<a href=\"javascript:void(0)\" onclick=\"javascript:tableSelectAll()\">".$locale['414']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:tableSelectNone()\">".$locale['415']."</a>]</div></td>
<td class='tbl1' valign='top'>".$locale['433']."<br>
<select style='width:180px;' class='textbox' id='list_ins' name='list_ins[]' size='$maxrows' multiple>".$insert_opt_list."</select>
<div align='center'>".$locale['412']." [<a href=\"javascript:void(0)\" onclick=\"javascript:populateSelectAll()\">".$locale['414']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:populateSelectNone()\">".$locale['415']."</a>]</div></td>
</tr>
<tr>
<td colspan='2' align='center'><hr>
<input type='hidden' name='file' value='{$backup_file_tmp}'>
<input class='button' type='submit' name='btn_restore_backup_do' style='width:100px;' value='".$locale['419']."'>
<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".$locale['434']."'>
</td>
</tr>
</table>
</form>\n";
  closetable();
}elseif(isset($_POST['btn_restore_backup_do'])){

  function checkline($line) { return($line[0]!='#'); }

  if(!empty($backupdir)) {
    $file=$backupdir.$file;
  }
  $result=gzfile($file);
  if((preg_match("/# Database Name: `(.+?)`/i", $result[2], $tmp1)<>0)&&(preg_match("/# Table Prefix: `(.+?)`/i", $result[3], $tmp2)<>0)){
    $inf_dbname=$tmp1[1];
    $inf_tblpre=$tmp2[1];
    $result=array_filter($result, "checkline");
    $results=preg_split("/;/",implode("",$result));
    if(isset($list_tbl) && count($list_tbl)>0){
      foreach($results as $result){
        $result=html_entity_decode($result, ENT_QUOTES);
        if(preg_match("/^DROP TABLE IF EXISTS `(.*?)`/im",$result,$tmp)<>0){
          $tbl=$tmp[1];
          if(in_array($tbl, $list_tbl)){
            $result=preg_replace("/^DROP TABLE IF EXISTS `$inf_tblpre(.*?)`/im","DROP TABLE IF EXISTS `$restore_tblpre\\1`",$result);
            mysql_unbuffered_query($result.';');
          }
        }
        if(preg_match("/^CREATE TABLE `(.*?)`/im",$result,$tmp)<>0){
          $tbl=$tmp[1];
          if(in_array($tbl, $list_tbl)){
            $result=preg_replace("/^CREATE TABLE `$inf_tblpre(.*?)`/im","CREATE TABLE `$restore_tblpre\\1`",$result);
            mysql_unbuffered_query($result.';');
          }
        }
      }
    }
    if(isset($list_ins) && count($list_ins)>0){
      foreach($results as $result){
        if(preg_match("/INSERT INTO `(.*?)`/i",$result,$tmp)<>0){
          $ins=$tmp[1];
          if(in_array($ins, $list_ins)){
            $result=preg_replace("/INSERT INTO `$inf_tblpre(.*?)`/i","INSERT INTO `$restore_tblpre\\1`",$result);
            mysql_unbuffered_query($result.';');
          }
        }
      }
    }
    if(empty($backupdir)) unlink($file);
    redirect(FUSION_SELF);
  }else{
    opentable($locale['450']);
    echo "<center><b>".$locale['451']."</b><br><br>".$locale['452']."<br><br>";
    echo "<form action='".FUSION_SELF."' name='frm_info' method='post'>";
    echo "<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".$locale['453']."'>";
    echo "</form></center>";
    closetable();
  }
}else{
  $table_opt_list="";
  $result=dbquery("SHOW tables");
  while($row=dbarraynum($result)){
    $table_opt_list.="<option value='".$row[0]."'";
    if(preg_match("/^".$db_prefix."/i",$row[0])){
      $table_opt_list.=" selected";
    }
    $table_opt_list.=">".$row[0]."</option>\n";
  }
  opentable($locale['400']);
  echo "
<script type='text/javascript'>
<!--
function backupDelete(what){if(confirm('".$locale['429']."\\n\\n'+what)){window.location='".FUSION_SELF."?action=delete&file='+what;}}
function backupSelectCore(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=(document.frmbackup.elements['db_tables[]'].options[i].text).match(/^$db_prefix/);}}
function backupSelectAll(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=true;}}
function backupSelectNone(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=false;}}
//-->
</script>
<form action='".FUSION_SELF."' name='frmbackup' method='post'>
 <table align='center' cellspacing='0' cellpadding='0' class='tbl'>
  <tr>
   <td valign='top'>
    <table align='center' cellspacing='0' cellpadding='0' class='tbl'>
     <tr>
      <td class='tbl2 bold' align='left' colspan='2'>".$locale['401']."</td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>".$locale['402']."</td>
      <td class='tbl1'>$db_name</td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>".$locale['403']."</td>
      <td class='tbl1'>".$db_prefix."</td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>".$locale['404']."</td>
      <td class='tbl1'>".sprintf($locale['406'], parseByteSize(get_database_info('size'),2,false), get_database_info('cnt'))."</td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>".$locale['405']."</td>
      <td class='tbl1'>".sprintf($locale['406'], parseByteSize(get_database_info('size',$db_prefix),2,false), get_database_info('cnt',$db_prefix))."</td>
     </tr>
     <tr>
      <td class='tbl2 bold' align='left' colspan='2'>".$locale['407']."</td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>".$locale['408']."</td>
      <td class='tbl1'><input class='textbox' type='text' name='backup_filename' value='backup_".date('Y-m-d_Hi')."' style='width:200px;'></td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>".$locale['409']."</td>
      <td class='tbl1'><select class='textbox' name='backup_type'>
<option value='.gz' selected>.sql.gz ".$locale['410']."</option>
<option value='.sql'>.sql</option>
</select></td>
     </tr>
     <tr>
      <td class='tbl1' align='right'>{$locale['426']}</td>
      <td class='tbl1'><input type='checkbox' name='backup_structonly'></td>
     </tr>
    </table>
   </td>
   <td valign='top'>
    <table cellpadding='0' cellspacing='0' class='tbl'>
     <tr>
      <td class='tbl2 bold' align='left'>".$locale['411']."</td>
     </tr>
     <tr>
      <td class='tbl1'>
<select style='margin:5px 0px' class='textbox' id='tablelist' name='db_tables[]' size='17' multiple>".$table_opt_list."</select>
<div align='center'>".$locale['412']." [<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectCore()\">".$locale['413']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectAll()\">".$locale['414']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectNone()\">".$locale['415']."</a>]</div></td>
     </tr>
    </table>
   </td>
  </tr>
  <tr>
   <td colspan='2' align='center'><hr><input class='button' type='submit' name='btn_create_backup' style='width:100px;' value='".$locale['416']."'></td>
  </tr>
</table>
</form>\n";
  closetable();
  tablebreak();

  if(!empty($backupdir)) {
    opentable($locale['420']);
    echo "<table width='100%' cellpadding='0' cellspacing='0'><tr><td>";
    $dh=@opendir($backupdir);
    if($dh!=false){
      $filelist=array();
      while(false!==($file=@readdir($dh))){
        if(is_file($backupdir.$file)&&$file!="."&&$file!=".."&&preg_match("/.(sql|gz)$/",$file)){
          $filelist[]=array(filemtime($backupdir.$file), $file);
        }
      }
      @closedir($dh);
      if(!empty($filelist)){
        echo "<tr><td class='tbl2 bold'>".$locale['421']."</td>";
        echo "<td class='tbl2 bold' align='center'>".$locale['422']."</b></td>";
        echo "<td class='tbl2 bold' align='right'>".$locale['423']."</td>";
        echo "<td class='tbl2 bold' align='right'>".$locale['424']."</td></tr>";
        rsort($filelist);
        $i=0;
        foreach($filelist as $key=>$file){
          if(($i%2)==0){echo "<tr class='forum1'>";}else{echo "<tr class='forum2'>";}
          echo "<td class='tbl1'><a href='".FUSION_SELF."?action=download&file=$file[1]'>$file[1]</a></td>";
          echo "<td class='tbl1' align='center'>".strftime($settings['shortdate'], $file[0])."</td>";
          echo "<td class='tbl1' align='right'>".parseByteSize(@filesize($backupdir.$file[1]),2,false)."</td>";
          echo "<td class='tbl1' align='right'>";
          echo "[<a href=\"javascript:backupDelete('$file[1]')\">".$locale['425']."</a>]&nbsp;";
          echo "[<a href='".FUSION_SELF."?action=restore&file=$file[1]'>".$locale['419']."</a>]&nbsp;";
          echo "</td></tr>";
          $i++;
        }
      }else{
        echo "<tr><td colspan='4' align='center'>".$locale['427']."</td></tr>";
      }
    }else{
      echo "<tr><td colspan='4' align='center'>".sprintf($locale['428'], $backupdir)."</td></tr>";
    }
    echo "</table>";
  }else{
    opentable($locale['417']);
    echo "<form action='".FUSION_SELF."' name='frmupload' method='post' enctype='multipart/form-data'>
 <table align='center' cellpadding='0' cellspacing='0' class='tbl'>
  <tr>
   <td class='tbl1'>".$locale['418']."&nbsp;<input type='file' name='upload_backup_file' enctype='multipart/form-data' class='textbox'></td>
  </tr>
  <tr>
   <td colspan='2' align='center'><hr>
   <input class='button' type='submit' name='btn_restore_backup' value='".$locale['419']."'>
   </td>
  </tr>
 </table>
</form>\n";
  }
  closetable();
}


//////////////////////////////////////////////////////////////////////////////////////////////////
function get_database_info($info="cnt",$prefix=""){
  global $db_name;
  $res=0;
  $result=dbquery("SHOW TABLE STATUS FROM `".$db_name."`");
  while($row=dbarray($result)){
    switch($info){
     case "cnt":
      $res++;
      break;
    case "size":
      $res += $row['Data_length']+$row['Index_length'];
      break;
    }
  }
  return $res;
}
//////////////////////////////////////////////////////////////////////////////////////////////////

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
require_once BASEDIR."footer.php";
?>