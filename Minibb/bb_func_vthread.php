<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, supplied with no warranties.
Check COPYING file for more details.
Copyright (C) 2019 Paul Puzyrev. www.minibb.com
Latest File Update: 2019-Sep-30
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

if($user_id==0) $l_sub_post_msg=$l_enterforums.'/'.$l_sub_post_msg;

$listPosts=''; $deleteTopic='';
$displayQuote='true';

if(!function_exists('convertTopicTitle')){
function convertTopicTitle($val){
return $val;
}
}

/*** CHECK ***/
if($topicData and $topicData[4]==$forum){

$topicName=convertTopicTitle($topicData[0]);

if ($topicName=='') $topicName=$l_emptyTopic;
$topicStatus=$topicData[1];
$topicSticky=$topicData[6];
$topicPoster=$topicData[2];
$topicPosterName=$topicData[3];
$topic_views=$topicData[7]+1;
$topicTime=$topicData[8];
}
else {

/* Try to find this topic in Archives */
if(isset($archives)){
foreach($archives as $key=>$val){
$Tt_arc=$key.'_'.$Tt;
if($row=db_simpleSelect(0, $Tt_arc, 'topic_id, forum_id', 'topic_id', '=', $topic)){
header($proto.' 301 Moved Permanently');
if(isset($archivesFolder)) $archivesURL="{$main_url}/{$archivesFolder}"; else $archivesURL="{$main_url}";
$furlCl=addGenURLPage("{$archivesURL}/{$key}/{$indexphp}action=vthread&forum={$row[1]}&topic={$row[0]}", $page, '&');
header("{$rheader}{$furlCl}");
exit;
}
}
}
/* If this is an Archive, try to find topic in Live table */
if(defined('ARCHIVE')){
if($row=db_simpleSelect(0, $forumsTt, 'topic_id, forum_id', 'topic_id', '=', $topic)){
header($proto.' 301 Moved Permanently');
$furlCl=addGenURLPage("{$forums_url}/{$indexphp}action=vthread&forum={$row[1]}&topic={$row[0]}", $page, '&');
header("{$rheader}{$furlCl}");
exit;
}
}

//header($proto.' 404 Not Found');
//header('Status: 404 Not Found');
header($proto.' 410 Gone');
$metaRobots='NOINDEX,NOFOLLOW';
$errorMSG=$l_topicnotexists; $correctErr='';
$title=$title.$l_topicnotexists;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}

$st=0; $frm=$forum;
include ($pathToFiles.'bb_func_forums.php');

if(!isset($forumsArray[$forum])){
//header($proto.' 404 Not Found');
//header('Status: 404 Not Found');
header($proto.' 410 Gone');
$errorMSG=$l_forumnotexists; $correctErr=$backErrorLink;
$title=$title.$l_forumnotexists;
echo load_header(); echo ParseTpl(makeUp('main_warning')); return;
}
unset($result);unset($countRes);

$forumName=$forumsArray[$forum][0]; $forumIcon=$forumsArray[$forum][1]; $forum_desc=$forumsArray[$forum][2];

/* Redirect to the proper URL if needed */
$requestUrl=parseRequestTopicURL($main_url);

if(isset($mod_rewrite) and $mod_rewrite) $origUrl=addTopicURLPage(genTopicURL($main_url, $forum, $forumName, $topic, $topicData[0]), $page);
else $origUrl=addGenURLPage("{$main_url}/{$indexphp}action=vthread&forum={$forum}&topic={$topic}", $page, '&');

if($requestUrl!=$origUrl and !defined('NO_RELOC')){
header($proto.' 301 Moved Permanently');
if(isset($metaLocation)) { $meta_relocate="{$origUrl}"; echo ParseTpl(makeUp($metaLocation)); }
else{ header("{$rheader}{$origUrl}"); }
exit;
}
/* --Redirect... */

/* form reply */
$l_messageABC=$l_sub_answer;
if ($topicStatus!=1) {
$emailCheckBox=emailCheckBox();

$allowForm=(!defined('ARCHIVE') and ($user_id==1 or $isMod==1));
$c1=(in_array($forum,$clForums) and isset($clForumsUsers[$forum]) and !in_array($user_id,$clForumsUsers[$forum]) and !$allowForm);
$c4=(isset($roForums) and in_array($forum, $roForums) and !$allowForm);

if ($c1 or $c4){
$mainPostForm='';$mainPostArea='';
$nTop=0;
$displayQuote='false';
}else{

$backToPostLink="<a href=\"JavaScript:getToPost();\" class=\"mnblnk mnblnkn\"><img src=\"{$main_url}/img/up.gif\" alt=\"{$l_backToPostLink}\" title=\"{$l_backToPostLink}\" /></a>";

//dynamic BB buttons
$mpf=ParseTpl(makeUp('main_post_form'));
$mpfs=convertBBJS($mpf);
if($mpfs!='') $mainPostForm=$mpfs; else $mainPostForm=$mpf;

$mainPostArea=makeUp('main_post_area');
$nTop=1;
}
}
else {
$mainPostArea=makeUp('main_post_closed');
$displayQuote='false';
$c1=FALSE; $c4=FALSE;
}
$mainPostArea=ParseTpl($mainPostArea);

if($displayQuote=='true') $eachReply='<a href="#newreply" class="mnblnk">'.$l_reply.'</a>&nbsp;';
else $eachReply='';


/* actual */

$numRows=$topicData[5];

//avoiding duplicated content issues
$totalPages=ceil($numRows/$viewmaxreplys);

if($page<PAGE1_OFFSET+1) {
$realPage=PAGE1_OFFSET+1;

if(isset($mod_rewrite) and $mod_rewrite and $sortBy==0) {
$urlp=addTopicURLPage(genTopicURL($main_url, $forum, $forumName, $topic, $topicData[0]), $realPage);
}
else $urlp=addGenURLPage("{$main_url}/{$indexphp}action=vthread&forum=$forum&topic={$topic}", $realPage, '&');

header($proto.' 301 Moved Permanently');

if(isset($metaLocation)) {
$meta_relocate="{$urlp}"; echo ParseTpl(makeUp($metaLocation));
}
else{
header("{$rheader}{$urlp}");
}
exit;
}

elseif($totalPages>0 and $page-PAGE1_OFFSET>$totalPages) {
header($proto.' 404 Not Found');
header('Status: 404 Not Found');
$metaRobots='NOINDEX,NOFOLLOW';

if(isset($mod_rewrite) and $mod_rewrite and $sortBy==0) {
$urlpFirst=addTopicURLPage(genTopicURL($main_url, $forum, $forumName, $topic, $topicData[0]), PAGE1_OFFSET+1);
$urlpLast=addTopicURLPage(genTopicURL($main_url, $forum, $forumName, $topic, $topicData[0]), $totalPages);
}
else {
$urlpFirst="{$main_url}/{$indexphp}action=vthread&amp;forum=$forum&amp;topic={$topic}&amp;page=".(PAGE1_OFFSET+1);
$urlpLast="{$main_url}/{$indexphp}action=vthread&amp;forum=$forum&amp;topic={$topic}&amp;page={$totalPages}";
}

$errorMSG=$l_accessDenied;
if(!isset($l_pageNotFoundLinked)) $correctErr=''; else $correctErr=str_replace(array('{PAGE_NUMBER}', '{FIRST_PAGE_LINK}', '{LAST_PAGE_LINK}'), array($page, $urlpFirst, $urlpLast), $l_pageNotFoundLinked);
$title=$l_forbidden;
echo load_header();
echo ParseTpl(makeUp('main_warning'));
display_footer();
exit;
}

$topicDesc=0;
$topic_reverse='';
if(isset($themeDesc) and in_array($topic,$themeDesc)) {
$topicDesc=1;
$topic_reverse="<img src=\"{$main_url}/img/topic_reverse.gif\" class=\"authorIcon\" alt=\"\" />&nbsp;";
}

if($page==-1 and $topicDesc==0) $page=pageChk($page,$numRows,$viewmaxreplys);
elseif($page==-1 and $topicDesc==1) $page=PAGE1_OFFSET+1;

if(isset($mod_rewrite) and $mod_rewrite) {
$urlp=genTopicURL($main_url, $forum, $forumName, $topic, $topicData[0]);
$urlType='Topic';
}
else {
$urlp="{$main_url}/{$indexphp}action=vthread&amp;forum=$forum&amp;topic=$topic";
$urlType='Gen';
}

$pageNav=pageNav($page,$numRows,$urlp,$viewmaxreplys,FALSE,$urlType);
if($pageNav!='') $mbpn='mb';
$makeLim=makeLim($page,$numRows,$viewmaxreplys);

$anchor=1;
if($page==PAGE1_OFFSET+1) $anchor2=1; else $anchor2=($page-PAGE1_OFFSET-1)*$viewmaxreplys+1;
$i=1;
$ii=0;

if(isset($themeDesc) and in_array($topic,$themeDesc)) $srt='DESC'; else $srt='ASC';

/* User info in posts */
if(isset($GLOBALS['userInfoInPosts']) and is_array($GLOBALS['userInfoInPosts'])){
$userInfo=array();
if($cols=db_simpleSelect(0,$Tp,'poster_id','topic_id','=',$topic,'post_id '.$srt,$makeLim)){
do{
if(!in_array($cols[0],$userInfo)) $userInfo[]=$cols[0];
}
while($cols=db_simpleSelect(1));
}
$xtr=getClForums($userInfo,'where','',$dbUserId,'or','=');
unset($userInfo);
if($cols=db_simpleSelect(0,$Tu,$dbUserId.','.implode(',',$userInfoInPosts))){
for($i=0;$i<sizeof($userInfoInPosts);$i++) ${'userInfo_'.$userInfoInPosts[$i]}=array();
do for($i=0;$i<sizeof($userInfoInPosts);$i++) {

/* keeping the original values of userInfoInPosts */
${'userInfoCl_'.$userInfoInPosts[$i]}[$cols[0]]=$cols[$i+1];

if(function_exists('parseUserInfo_'.$userInfoInPosts[$i])) $cols[$i+1]=call_user_func('parseUserInfo_'.$userInfoInPosts[$i],$cols[$i+1]);
${'userInfo_'.$userInfoInPosts[$i]}[$cols[0]]=$cols[$i+1];
}
while($cols=db_simpleSelect(1));
}
unset($xtr);
}
/* --User info in posts */

//echo $topicData[5];

if($cols=db_simpleSelect(0,$Tp,'poster_id, poster_name, post_time, post_text, poster_ip, post_status, post_id','topic_id','=',$topic,'post_id '.$srt,$makeLim)){

if($page==PAGE1_OFFSET+1 and isset($enableViews) and $enableViews) updateArray(array('topic_views'),$Tt,'topic_id',$topic);

$tpl=makeUp('main_posts_cell');

$groupDelete=0;
$allPosters=array();

do{
if($i>0) $bg='tbCel1'; else $bg='tbCel2';

$poster_id=$cols[0];
$postDate=convert_date($cols[2]);

if(!($user_id==1 or $isMod==1 or $user_id==0)) $availEditMes=($topicStatus==0 and (time()-strtotime($cols[2])<$useredit OR $useredit==0 ) );
else $availEditMes=TRUE;

if($availEditMes) $allowedEdit="<a href=\"{$main_url}/{$indexphp}action=editmsg&amp;topic=$topic&amp;forum=$forum&amp;post={$cols[6]}&amp;page=$page&amp;anchor={$cols[6]}\" class=\"mnblnk\">$l_edit</a>";
else $allowedEdit='';

if ($logged_admin==1 or $isMod==1) $viewIP=' '.$l_sepr.' IP: '.'<a href="'.$main_url.'/'.$indexphp.'action=viewipuser&amp;postip='.$cols[4].'" target="_blank" class="mnblnk">'.$cols[4].'</a>';
else $viewIP='';

if ($logged_admin==1 or $isMod==1 or (isset($userDeleteMsgs) and $userDeleteMsgs>0 and $user_id!=0 and $user_id==$cols[0] and $availEditMes) ){
if($topicData[5]==1) $deleteM='';
//and (($ii==0 and $page==0 and $topicDesc==0) or ($topicDesc==1 and $numRows==$viewmaxreplys*$page+$i+1))) 
else {
$deleteM=<<<out
<a href="JavaScript:confirmDelete({$cols[6]},0)" onmouseover="window.status='{$l_deletePost}'; return true;" onmouseout="window.status=''; return true;" class="mnblnk">$l_deletePost</a>
out;
if (($logged_admin==1 or $isMod==1) and isset($enableGroupMsgDelete)) { $groupDelete++; $deleteBox="<input type=\"checkbox\" name=\"deleteAll[]\" value=\"{$cols[6]}\" />"; } else $deleteBox='';
}

$allowed=$allowedEdit." ".$deleteM;
}
else {
//$cols[4]='';
if ($topicData[1]==0 and $user_id==$cols[0] and $user_id !=0 and $cols[5]!=2 and $cols[5]!=3) {
$allowed=$allowedEdit;
}
else {
$allowed='';
}
}

$posterName=$cols[1];
$posterNameClean=strip_tags($cols[1]);

# post_status: 0-clear (available for edit), 1-edited by author, 2-edited by admin (available only for admin), 3 - edited by mod
if ($cols[5]==0) {
$editedBy='';
}
else {
if(isset($is_mobile) and $is_mobile) $is_mobileBr=$brtag; else $is_mobileBr=' '.$l_sepr.' ';
$editedBy="{$is_mobileBr}{$l_editedBy}";
if($cols[5]==2) $we=$l_admin;
elseif($cols[5]==1) $we=$cols[1];
elseif($cols[5]==3) $we=$l_moderator;
else $we='N/A';
$editedBy.=$we;
}

if ($cols[0]!=0) {
$cc=$cols[0];
if (isset($userRanks[$cc])) $ins=$userRanks[$cc];
elseif (isset($mods[$forum]) and is_array($mods[$forum]) and in_array($cc,$mods[$forum])) $ins=$l_moderator;
else { $ins=($cc==1?$l_admin:$l_member); }
$viewReg=$ins;
$pLink1="<a href=\"{$main_url}/{$indexphp}action=userinfo&amp;user={$cc}\" title=\"{$l_about} &quot;{$posterNameClean}&quot;\" class=\"mnblnk username\">";
$pLink2='</a>';
}
else {
if($cols[1]!=$l_anonymous) $viewReg=$l_anonymous;
else $viewReg='';
$pLink1='';
$pLink2='';
}

if(!isset($allPosters[$cols[0]])) $allPosters[$cols[0]]=TRUE;

if(substr_count($cols[1], '&#039;')==0) $posterNameJs=$posterNameClean;
else {
if(get_magic_quotes_gpc()==1) $apos=chr(92).chr(92).chr(92); else $apos=chr(92).chr(92);
$posterNameJs=str_replace('&#039;', $apos.'&#039;', $posterNameClean);
}
$posterNameJs=str_replace(' ', '%20', $posterNameJs);

$posterText=$cols[3];
if($anchor2!=1) $posterIcon='s'; else $posterIcon='t';

$posterName=usernameFilter($posterName);

if(function_exists('parseMessage')) parseMessage();

$tmp_anchor2=$anchor2; $anchor2=parseStatsNum($anchor2);
$listPosts.=ParseTpl($tpl);
$anchor2=$tmp_anchor2;

$i=-$i;
if($ii==0) {
$ii++;
$tmpDesc=strip_tags(str_replace(array("\r\n","\r",'<br />','&amp;','&quot;','&#039;',"\n",'"', '}', '{', '&gt;', '&lt;'),' ',$cols[3]));
$description=trim(substr_unicode(preg_replace(array("/&#[0-9]+;/", "/[ ]{2,}/"), array('', ' '), $tmpDesc),0,400));
}

$anchor++; $anchor2++;
}
while($cols=db_simpleSelect(1));
unset($result);unset($countRes);

//
if(isset($is_mobile) and $is_mobile) {
$l_seprTmp=$l_sepr;
$l_sepr=$brtag;
}

if ($logged_admin==1 or $isMod==1 or (isset($userDeleteMsgs) and $userDeleteMsgs==2 and $user_id!=0 and $user_id==$topicPoster and ($useredit==0 or time()-strtotime($topicTime)<$useredit) and $topicStatus==0 ) ) {
$deleteTopic="$l_sepr <a href=\"JavaScript:confirmDelete({$topic},1)\" onmouseover=\"window.status='{$l_deleteTopic}'; return true;\" onmouseout=\"window.status=''; return true;\" class=\"mnblnk\">$l_deleteTopic</a>";
}

if ($logged_admin==1 or $isMod==1) {

if(isset($enableGroupMsgDelete) and $groupDelete>0) $deleteAllMsgs="$l_sepr <a href=\"JavaScript:confirmDelete(0,2);\" class=\"mnblnk\">$l_deleteAllMsgs</a>";

$moveTopic="$l_sepr <a href=\"{$main_url}/{$indexphp}action=movetopic&amp;forum=$forum&amp;topic=$topic&amp;page=$page\" class=\"mnblnk\">$l_moveTopic</a>";

if ($topicStatus==0) { $chstat=1; $cT=$l_closeTopic; }
else { $chstat=0; $cT=$l_unlockTopic; }
$closeTopic="<a href=\"{$main_url}/{$indexphp}action=locktopic&amp;forum=$forum&amp;topic=$topic&amp;chstat=$chstat\" class=\"mnblnk\">$cT</a>";

if ($topicSticky==0) { $chstat=1; $cT=$l_makeSticky; }
else { $chstat=0; $cT=$l_makeUnsticky; }
$stickyTopic="$l_sepr <a href=\"{$main_url}/{$indexphp}action=unsticky&amp;forum=$forum&amp;topic=$topic&amp;chstat=$chstat\" class=\"mnblnk\">$cT</a>";

$extra=1;
if ($logged_admin==1 and $cnt=db_simpleSelect(0,$Ts,'count(*)','topic_id','=',$topic) and $cnt[0]>0) $subsTopic="$l_sepr <a href=\"{$main_url}/{$bb_admin}action=viewsubs&amp;topic=$topic\" class=\"mnblnk\">$l_subscriptions</a>"; else $subsTopic='';

if(isset($archives) and defined('ARCHIVE')){
$backToLive="{$l_sepr} <a href=\"JavaScript:confirmLive({$topic})\" onmouseover=\"window.status='{$l_arcBackToLive}'; return true;\" onmouseout=\"window.status=''; return true;\" class=\"mnblnk\">{$l_arcBackToLive}</a>";
$backToLiveJs=<<<out
function confirmLive(id){
if(confirm('{$l_arcBackToLiveConfirm}')){
var csrfcookie=getCSRFCookie();
if(csrfcookie!='') csrfcookie='&csrfchk='+csrfcookie;
document.location='{$main_url}/{$indexphp}action=archives&step=makelive&topic='+id+csrfcookie;
return;
}
}
out;
}
else $backToLive='';

}

elseif (($user_id==$topicPoster and $user_id!=0 and $user_id!=1) and $topicSticky!=1 and !$c4) {
if ($topicStatus==0 and $userUnlock!=2) $closeTopic="<a href=\"{$main_url}/{$indexphp}action=locktopic&amp;forum=$forum&amp;topic=$topic&amp;chstat=1\" class=\"mnblnk\">$l_closeTopic</a>";
elseif($topicStatus==1 and $userUnlock==1 and $userUnlock!=2) $closeTopic="<a href=\"{$main_url}/{$indexphp}action=locktopic&amp;forum=$forum&amp;topic=$topic&amp;chstat=0\" class=\"mnblnk\">$l_unlockTopic</a>";
else $closeTopic='';
}

if($logged_admin==1 and !isset($disableSuperSticky) and $topicSticky==0){
$stickyTopic.=" / <a href=\"{$main_url}/{$indexphp}action=sticky&amp;forum=$forum&amp;topic=$topic&amp;chstat=2\" class=\"mnblnk\">{$l_makeSupersticky}</a>";
}

if(isset($is_mobile) and $is_mobile) {
$l_sepr=$l_seprTmp;
}

if($page>PAGE1_OFFSET+1) $tpage=' - '.$l_page.' '.($page-PAGE1_OFFSET); else $tpage='';
$title1=$title; $title=strip_tags($topicName);
$title.=$tpage;
if(isset($addMainTitle)) $title.=' - '.str_replace(' - ','',$title1);


//$title=strip_tags($topicName).' - '.str_replace(' - ','',$title).$tpage;

}//if posts

if(isset($mod_rewrite) and $mod_rewrite) {
$linkToForums=addForumURLPage(genForumURL($main_url, $forum, $forumsArray[$forum][0]), PAGE1_OFFSET+1);
}
else $linkToForums="{$main_url}/{$indexphp}action=vtopic&amp;forum={$forum}";

echo load_header();

$mainTpl=makeUp('main_posts');

if($pageNav=='') $mainTpl=preg_replace("#<!--pageNav-->(.*?)<!--/pageNav-->#is", '', $mainTpl);

$delBlock=FALSE;
if($user_id==1 or $isMod==1) $delBlock=TRUE;
elseif(isset($userDeleteMsgs)){
if(isset($allPosters[$user_id])) $delBlock=TRUE;
elseif(isset($topicPoster) and $topicPoster==$user_id) $delBlock=TRUE;
}

if(!$delBlock) $mainTpl=preg_replace("#<!--ADMIN_JS-->(.*?)<!--/ADMIN_JS-->#is", '', $mainTpl);
if(isset($is_mobile) and $is_mobile and !isset($startReplyLink) and isset($l_menu[7])) $startReplyLink=trim(str_replace($l_sepr, '', $l_menu[7]));

echo ParseTpl($mainTpl);

?>