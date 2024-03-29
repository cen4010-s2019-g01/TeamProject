<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty. 
Check COPYING file for more details.
Copyright (C) 2017 Paul Puzyrev. www.minibb.com
Latest File Update: 2018-Nov-01
*/
if (!defined('INCLUDED776')) die ('Fatal error.');

$cookieexptime=time()+$cookie_expires;

function user_logged_in() {

$httponlycookie=$GLOBALS['httponlycookie'];

if(isset($GLOBALS['cook']) and trim($GLOBALS['cook'])!='') $c=explode('|',$GLOBALS['cook']);
else $c=getMyCookie();

$username=$c[0]; $userpassword=$c[1]; $exptime=(int)$c[2]+0;

$returned=FALSE;
$resetCookie=FALSE;

if($username=='') { $returned=FALSE; return; }

$pasttime=$exptime-time();

if(strlen($GLOBALS['admin_pwd'])==32) $user_pwd_cmp=$GLOBALS['admin_pwd']; else $user_pwd_cmp=writeUserPwd($GLOBALS['admin_pwd']);

if(isset($GLOBALS['loginsCase']) and $GLOBALS['loginsCase']) { $caseComp1=$GLOBALS['caseComp'].'('; $caseComp2=')'; $usernameSql=strtolower_unicode($username); } else { $caseComp1=''; $caseComp2=''; $usernameSql=$username; }

if ( ($username==$GLOBALS['admin_usr'] OR (isset($GLOBALS['loginsCase']) and $GLOBALS['loginsCase'] and strtolower_unicode($username)==strtolower_unicode($GLOBALS['admin_usr']))) and $userpassword==$user_pwd_cmp) {
//if ($username==$GLOBALS['admin_usr'] and $userpassword==$GLOBALS['admin_pwd']) {
$returned=TRUE;
$GLOBALS['logged_user']=0; $GLOBALS['logged_admin']=1; $GLOBALS['user_id']=1;
$username=$GLOBALS['admin_usr'];

if($row=db_simpleSelect(0,$GLOBALS['Tu'],$GLOBALS['dbUserSheme']['user_sorttopics'][1].','.$GLOBALS['dbUserSheme']['language'][1].', '.$GLOBALS['dbUserSheme']['num_posts'][1],$GLOBALS['dbUserId'],'=',1)) {
$GLOBALS['user_sort']=$row[0];
$GLOBALS['langu']=$row[1];
$GLOBALS['user_num_posts']=$row[2];
}
else{
$GLOBALS['user_sort']=0;
$GLOBALS['langu']=$GLOBALS['lang'];
$GLOBALS['user_num_posts']=0;
}

if ($pasttime<=$GLOBALS['cookie_renew']) {
// if expiration time of cookie is less than defined in setup, we redefine it below
$resetCookie=TRUE;
}

}

elseif($row=db_simpleSelect(0,$GLOBALS['Tu'],$GLOBALS['dbUserId'].','. $GLOBALS['dbUserSheme']['user_sorttopics'][1].','. $GLOBALS['dbUserSheme']['language'][1].','. $GLOBALS['dbUserAct'] .','. $GLOBALS['dbUserSheme']['user_password'][1] .', '.$GLOBALS['dbUserSheme']['username'][1].', '.$GLOBALS['dbUserSheme']['num_posts'][1], $caseComp1.$GLOBALS['dbUserSheme']['username'][1].$caseComp2, '=', $usernameSql, '', 1)){

if($row[4]==$userpassword){
$returned=TRUE;
$GLOBALS['user_id']=$row[0]; $GLOBALS['user_sort']=$row[1]; $GLOBALS['logged_user']=1; $GLOBALS['logged_admin']=0;
$GLOBALS['langu']=$row[2];
$GLOBALS['user_activity']=$row[3];
$username=$row[5];
$GLOBALS['user_num_posts']=$row[6];

if ($pasttime<=$GLOBALS['cookie_renew']) {
$resetCookie=TRUE;
}

}
else{
/* Preventing hijack */
$username='';
$GLOBALS['user_usr']=$username;
}

}

else{
$returned=FALSE;
if ($pasttime<=$GLOBALS['cookie_renew']) {
$userpassword='';
$resetCookie=TRUE;
}
}

if($resetCookie) {
deleteMyCookie();
if($httponlycookie) setMyCookie($username,$userpassword,$GLOBALS['cookieexptime'],FALSE,TRUE);
else setMyCookie($username,$userpassword,$GLOBALS['cookieexptime'],FALSE);
}

$GLOBALS['user_usr']=$username;

return $returned;
}

function setMyCookie($userName,$userPass,$userExpTime,$encodePass=TRUE){
$httponlycookie=$GLOBALS['httponlycookie'];
if($userPass!='' and $encodePass) $userPass=writeUserPwd($userPass);
if($httponlycookie) setcookie($GLOBALS['cookiename'], $userName.'|'.$userPass.'|'.$userExpTime, $GLOBALS['cookieexptime'], $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure'], TRUE);
else setcookie($GLOBALS['cookiename'], $userName.'|'.$userPass.'|'.$userExpTime, $GLOBALS['cookieexptime'], $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure']);
}

function getMyCookie(){
if(isset($_COOKIE[$GLOBALS['cookiename']]) and $cv=$_COOKIE[$GLOBALS['cookiename']] and substr_count($cv, '|')==2 and substr_count($cv, '<')==0 and substr_count($cv, '>')==0) {
$cookievalue=explode ('|', $cv);
if(get_magic_quotes_gpc()==0) $cookievalue[0]=addslashes($cookievalue[0]);
$cookievalue[1]=str_replace("'",'',$cookievalue[1]);
}
else $cookievalue=array('','','');
return $cookievalue;
}

function deleteMyCookie(){
$httponlycookie=$GLOBALS['httponlycookie'];
if($httponlycookie) setcookie($GLOBALS['cookiename'], '', (time()-2592000), $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure'], TRUE);
else setcookie($GLOBALS['cookiename'], '', (time()-2592000), $GLOBALS['cookiepath'], $GLOBALS['cookiedomain'], $GLOBALS['cookiesecure']);
}

function writeUserPwd($pwd){
return md5($pwd);
}

?>