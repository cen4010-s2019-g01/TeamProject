var selektion, selektionEvent;
var globalPost=0;

function getSelectionText() {
var selectedText='';
if (window.getSelection) {
selectedText=window.getSelection().toString();
}
return selectedText;
}

document.addEventListener('mouseup', function(){
selektionEvent = getSelectionText();
}, false);

document.addEventListener('touchend', function(){
selektionEvent = getSelectionText();
}, false);

function insertAtCursor(myField, myValue, bbCode1, bbCode2, endOfLine) {
var bbb;
if(bbCode1=='[url=null]') { bbCode1=''; bbCode2=''; }
if(bbCode1=='[imgs]' && myValue==null) { bbCode1=''; bbCode2=''; myValue=''; }
if(bbCode1=='[imgs=null]') { bbCode1=''; bbCode2=''; myValue=''; }
if(bbCode2=='null[/imgs]') { bbCode2='[/imgs]'; myValue=''; }
if(bbCode1=='[youtube=null]') { bbCode1=''; bbCode2=''; myValue=''; }
if(bbCode1=='[vimeo=null]') { bbCode1=''; bbCode2=''; myValue=''; }
if(bbCode1=='[youtube=null]') { bbCode1=''; bbCode2=''; myValue=''; }

//MOZILLA/NETSCAPE/OPERA support
if (typeof(myField.selectionStart) == 'number') {
myField.focus();
var startPos = myField.selectionStart;
var endPos = myField.selectionEnd;
var scrollTop = myField.scrollTop;
var bbb2, bbV, eoll;
if(myValue=='') myValue = myField.value.substring(startPos, endPos);
//alert(myValue);
myField.value = myField.value.substring(0, startPos) + bbCode1 + myValue + bbCode2 + endOfLine + myField.value.substring(endPos, myField.value.length);
if(myValue=='') {

if(bbCode1.substring(0,4)=='[img' || bbCode1.substring(0,4)=='[url'){
bbb=bbCode1.length + myValue.length + bbCode2.length;
myField.selectionStart=startPos+bbb; myField.selectionEnd=startPos+bbb;
}
else{
bbb=bbCode1.length;
myField.selectionStart=startPos+bbb;
myField.selectionEnd=endPos+bbb;
}

}
else {
bbb=bbCode1.length;
bbb2=bbCode2.length;
bbV=myValue.length;
eoll=endOfLine.length;
myField.selectionStart=startPos+bbV+bbb+bbb2+eoll;
myField.selectionEnd=myField.selectionStart;
}
myField.scrollTop = scrollTop;
myField.focus();
return;
}

else if (document.selection) {
//IE support
var str = document.selection.createRange().text;
sel = document.selection.createRange();
sel.text = bbCode1 + myValue + bbCode2 + endOfLine;
if(myValue=='') {
bbb=bbCode2.length; 
if(bbCode1.substring(0,4)=='[img' ) bbb=0; else bbb=-bbb;
sel.moveStart('character',bbb); sel.moveEnd('character',bbb);
}
sel.select();
myField.focus();
return;
}

else {
myField.value += myValue;
myField.focus();
return;
}
}

function paste_strinL(strinL, isQuote, bbCode1, bbCode2, endOfLine, User, Post){
if(isQuote==1 && strinL=='') {
alert(l_quoteMsgAlert);
return;
}
else if(isQuote==2 && strinL=='') {
globalPost=Post;
bbCode1='[b]' + User + '[/b]\n'; bbCode2=''; endOfLine='';
//alert(l_quoteMsgAlert);
}
else{
globalPost=Post;
if (isQuote==1) {
bbCode1='[quote=' + User + ']'; bbCode2='[/quote]'; endOfLine='\n';
}
if (isQuote==2) {
strinL=User;
bbCode1='[b]'; bbCode2='[/b]'; endOfLine='\n';
}
}
var isForm=document.getElementById('postMsg');
if (isForm) {
var input=document.getElementById('postText');
//var input=document.forms["postMsg"].elements["postText"];
insertAtCursor(input, strinL, bbCode1, bbCode2, endOfLine);
}
else alert(l_accessDenied);
//}
}

function pasteSel() {
selektion='';
var c, s, i;

if(selektionEvent.length>0){
selektion=selektionEvent;
}

else if(window.getSelection) {

selObj=window.getSelection();

if(selObj.rangeCount) {
c=document.createElement("div");
for(i=0;i<selObj.rangeCount;++i){
c.appendChild(selObj.getRangeAt(i).cloneContents());
}
selektion=c.innerHTML;
}
else{
//selektion=selObj.getRangeAt(0);
selektion=selObj.toString();
}
}
else if(document.getSelection) {
selektion=document.getSelection();
}
else if(document.selection) {
selektion=document.selection.createRange().text;
}
}


//disable anonymous postings solution //
var names=new Array('anon', 'guest', '@', '.com', '.net', 'org');

function checkIt(){
var ret=true;
var pf=document.forms['postMsg'];
var nLow, sLow, a;

if (pf.elements['user_usr']!=undefined){
if (pf.elements['user_usr'].value.length>2 && pf.elements['user_usr'].value.length<=15){

var nLn=names.length;
strL=pf.elements['user_usr'].value.length;
sLow=pf.elements['user_usr'].value.toLowerCase();

for (var i=0; i<nLn; i++){

nLnL=names[i].length; 
nLow=names[i].toLowerCase(); 

if(nLnL>strL) {}
else{

if(sLow.indexOf(nLow)>=0) {
ret=false;
break;
}

}

}

}
else ret=false;
}
if(!ret) {
alert('Please enter something containing between 3 and 15 symbols in the Username box!\nEmpty or forbidden usernames are disallowed.');
pf.elements['user_usr'].focus();
}
return ret;
}
// eof disable anonymous postings solution //

function trimTxt(s) {
while (s.substring(0,1) == ' ') {
s = s.substring(1,s.length);
}
while (s.substring(s.length-1,s.length) == ' ') {
s = s.substring(0,s.length-1);
}
return s;
}

function submitForm(){

// custom - anonymous postings solution
if(anonPost==1 && !checkIt()) return false;

var pf=document.forms['postMsg'];
var ftitle=false, ftext=false, flogin=false, fpass=false, user_usr='', user_pwd='', topicTitle='', postText='', fsubmit=true, warn=l_accessDenied;
if(pf.elements['user_usr']) { flogin=true; user_usr=trimTxt(pf.elements['user_usr'].value); }
if(pf.elements['user_pwd']) { fpass=true; user_pwd=trimTxt(pf.elements['user_pwd'].value); }
if(pf.elements['postText']) { ftext=true; postText=trimTxt(pf.elements['postText'].value); }
if(pf.elements['topicTitle']) { ftitle=true; topicTitle=trimTxt(pf.elements['topicTitle'].value); }
if(pf.elements['CheckSendMail'] && pf.elements['CheckSendMail'].checked) { tlength=0; }

if(flogin && fpass && user_usr!='' && user_pwd!='') fsubmit=true;
else if(flogin && fpass && anonPost==0 && user_pwd=='') fsubmit=false;
else if(ftext && postText.length<tlength) fsubmit=false;
else if(ftitle && topicTitle.length<tlength) fsubmit=false;

if(fsubmit) {
pf.elements['subbut'].disabled=true; document.forms['postMsg'].submit();
}
else {
if(ftitle && topicTitle.length<tlength) warn=warn+'\n'+enterSubject;
if(ftext && postText.length<tlength) warn=warn+'\n'+enterMessage;
if(flogin && fpass && anonPost==0 && user_pwd=='') warn=warn+'\n'+enterLogin;
alert(warn);
return;
}
}
