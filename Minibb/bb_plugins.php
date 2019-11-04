<?php
if (!defined('INCLUDED776')) die ('Fatal error.');

/* Responsive videos */
if( (isset($is_mobile) and $is_mobile and $action=='vthread') or (isset($_POST['prevForm']) and $_POST['prevForm']==1) ){
$responsiveMediaBlock=<<<out
<style>
.ytc {position:relative;padding-bottom:56.25%;height:0;overflow:hidden;} .ytc iframe,.ytc object,.ytc embed {position:absolute;top:0;left:0;width:100%;height:100%;}
</style>
out;
}
/* --Responsive videos */

/* Youtube button */
if($action=='vthread' or $action=='vtopic' or $action=='editmsg' or $action=='pmail' or ($action=='' and isset($firstPageTopicForm) and $firstPageTopicForm==1)){
$button_youtube=<<<out
<a href="javascript:paste_strinL(selektion,4,'[youtube='+prompt('YouTube%20movie%20URL:','http://youtu.be/')+']','','')" onmouseover="window.status='{$l_bb_youtube}'; return true" onmouseout="window.status=''; return true" onmousemove="pasteSel()" rel="nofollow"><img src="{$main_url}/img/button_youtube.gif" style="width:22px;height:22px" alt="{$l_bb_youtube}" title="{$l_bb_youtube}" /></a><img src="{$main_url}/img/p.gif" style="width:{$bbimgmrg}px;height:22px" alt="" />
out;
}
/* --Youtube button */

?>
