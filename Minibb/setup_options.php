<?php
/*
This file is part of miniBB. miniBB is free discussion forums/message board software, without any warranty.
Check COPYING file for more details.
Copyright (C) 2014 Paul Puzyrev. www.minibb.com
Latest File Update: 2014-Sep-28
*/

$DB='mysqli';

$DBhost='localhost';
$DBname='eortiz2018';
$DBusr='eortiz2018';
$DBpwd='TY52MqGC0n';

$Tf='minibbtable_forums';
$Tp='minibbtable_posts';
$Tt='minibbtable_topics';
$Tu='minibbtable_users';
$Ts='minibbtable_send_mails';
$Tb='minibbtable_banned';

$cookiedomain='';
$cookiename='CEN4010Project';
$cookiepath='';
$cookiesecure=FALSE;
$cookie_expires=108000;
$cookie_renew=1800;
$cookielang_exp=2592000;

/* determining the mobile device */
$is_mobile=FALSE;
$user_agent=(isset($_SERVER['HTTP_USER_AGENT'])?strtolower($_SERVER['HTTP_USER_AGENT']):'');

$is_mobile_test=FALSE;

$is_mobile_exclude=(substr_count($user_agent, 'pad')>0 or substr_count($user_agent, 'tab')>0);

$is_mobile_browser=(substr_count($user_agent, 'mobile')>0 or substr_count($user_agent, 'android')>0 or substr_count($user_agent, 'phone')>0);

$is_mobile_samsung=(substr_count($user_agent, 'samsungbrowser')>0);

$is_mobile=(!$is_mobile_exclude and ($is_mobile_test or $is_mobile_browser));

if(isset($_COOKIE[$cookiename.'_mobileswitch']) and $is_mobile) $is_mobile=FALSE;
if(isset($_COOKIE[$cookiename.'_mobileswitch']) and $is_mobile_exclude) $is_mobile=TRUE;

if($is_mobile_test or $is_mobile_browser) {
$borderRightMobile=' style="border-right:0px"';
}
/* -- determining the mobile device */

$admin_usr='Manny';
$admin_pwd='mottee11';
$admin_email='enmanuel.o11@gmail.com';
$reply_to_email=$admin_email;

$main_url='http://lamp.cse.fau.edu/~eortiz2018/Minibb';

$bb_admin='trusteduser.php?';

$indexphp='index.php?';
$pathToFiles='./';

$fontsCSS="<link href=\"https://fonts.googleapis.com/css?family=Roboto\" rel=\"stylesheet\">";

$skin='default';
$sitename=(isset($_SERVER['SERVER_NAME'])?$_SERVER['SERVER_NAME']:'').' Forum';
//$sitename='Awesome miniBB-Forums';
$startPageModern=TRUE;

/* Mobile Friendly/Template code */
if($is_mobile_browser) $startPageModern=FALSE;
/* --Mobile Friendly/Template code */

$lang='eng';

$emailadmin=0;
$emailusers=0;
$userRegName='_A-Za-z0-9 ';
$l_sepr='<span class="sepr">|</span>';

$post_text_maxlength=10240;
$post_word_maxlength=28;
$topic_max_length=250;
$viewmaxtopic=30;
$viewlastdiscussions=24;
$viewmaxreplys=15;
$viewmaxsearch=20;
$viewpagelim=5000;
$viewTopicsIfOnlyOneForum=0;

$protectWholeForum=0;
$protectWholeForumPwd='pwd';

$postRange=5;

$dateOnlyFormat='j F Y';
$timeOnlyFormat='H:i';
$dateFormat=$dateOnlyFormat.' '.$timeOnlyFormat;

$disallowNames=array('Anonymous', 'Fuck', 'Shit', 'Guest');
$disallowNamesIndex=array('admin', 'guest'); // 2.0 RC1f

$sortingTopics=0;
$topStats=4;
$genEmailDisable=0;

$defDays=60;
$userUnlock=0;

$emailadmposts=0;
$useredit=86400;

//$metaLocation='go';
//$closeRegister=1;
//$timeDiff=21600;

$stats_barWidthLim='31';

$dbUserSheme=array(
'username'=>array(1,'username','login'),
'user_password'=>array(3,'user_password','passwd'),
'user_email'=>array(4,'user_email','email'),
'user_icq'=>array(5,'user_icq','icq'),
'user_website'=>array(6,'user_website','website'),
'user_occ'=>array(7,'user_occ','occupation'),
'user_from'=>array(8,'user_from','from'),
'user_interest'=>array(9,'user_interest','interest'),
'user_viewemail'=>array(10,'user_viewemail','user_viewemail'),
'user_sorttopics'=>array(11,'user_sorttopics','user_sorttopics'),
'language'=>array(14,'language','language'),
'num_topics'=>array(16,'num_topics',''),
'num_posts'=>array(17,'num_posts',''),
'user_custom1'=>array(18,'user_custom1','user_custom1'),
'user_custom2'=>array(19,'user_custom2','user_custom2'),
'user_custom3'=>array(20,'user_custom3','user_custom3')
);
$dbUserId='user_id';
$dbUserDate='user_regdate'; $dbUserDateKey=2;
$dbUserAct='activity';
$dbUserNp='user_newpasswd';
$dbUserNk='user_newpwdkey';

$enableNewRegistrations=TRUE;
$enableProfileUpdate=TRUE;

$usersEditTopicTitle=TRUE;
//$includeHeader='header.php';
//$includeFooter='footer.php';
//$emptySubscribe=TRUE;
//$allForumsReg=TRUE;
//$registerInactiveUsers=TRUE;
//$mod_rewrite=TRUE;
$enableViews=TRUE;
//$userInfoInPosts=array();
//$userDeleteMsgs=1;

$description='miniBB is a free complete PHP forum software, bulletin board, having very strong bulletin board idea beside. Modern free forums script is mostly too large, too cool, sometimes funny and cumbersome, written by freelancers. mini bb is free from these lacks due its clear concepts of the whole search engine friendly forums solution, also freelance avalaible. mysql is the default database for minibb. Open source bulletin board mostly\'s oriented to users; having a website design concept behind, small bulletin board becomes further leader in building, integrating and embedding forums into website. miniBB supports multilingual content, language packs, rss, postgresql, mssql, bad words, smilies, instant online modules, mod rewrite, SEO. By bulletin bird, we mean the easiest forums solution for a website, speed, simplicity. Whatever your community, discussion is related to, you can download our bulletin forum software and use it on your site! www.miniBB.net has all useful software downloads for anyone using our bulletin board PHP solution.';

$startIndex='index.php'; // or 'index.html' for mod_rewrite
$manualIndex='index.php?action=manual'; // or 'manual.html' for mod_rewrite

$enableGroupMsgDelete=TRUE;
$post_text_minlength=2;
$loginsCase=TRUE;

$allowHyperlinks=0;

//$addMainTitle=1;
$editMsgReloc=TRUE;

$uname_maxlength=20;

?>
