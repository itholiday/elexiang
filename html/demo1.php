<?php
$numLen=16;
$pwdLen=10;
$c=100;//生成100组卡号密码
$sNumArr=range(0,9);
$sPwdArr=array_merge($sNumArr,range('A','Z'));

$cards=array();
for($x=0;$x< $c;$x++){
    $tempNumStr=array();
    for($i=0;$i< $numLen;$i++){
        $tempNumStr[]=array_rand($sNumArr);
    }
    $tempPwdStr=array();
    for($i=0;$i< $pwdLen;$i++){
        $tempPwdStr[]=$sPwdArr[array_rand($sPwdArr)];
    }
    $cards[$x]['no']=implode('',$tempNumStr);
    $cards[$x]['pwd']=implode('',$tempPwdStr);
}
array_unique($cards);
echo '<pre>';
print_r($cards);