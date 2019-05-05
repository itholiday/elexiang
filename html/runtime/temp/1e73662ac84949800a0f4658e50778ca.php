<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:62:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/shops/edit.html";i:1479007076;s:56:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/base.html";i:1477495670;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>后台管理中心 - <?php echo WSTConf('CONF.mallName'); ?></title>
<meta name="Keywords" content=""/>
<meta name="Description" content=""/> 
<link href="__ADMIN__/js/ligerui/skins/Aqua/css/ligerui-all.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" /> 
<link href="__STATIC__/plugins/validator/jquery.validator.css?v=<?php echo $v; ?>" rel="stylesheet">

<link rel="stylesheet" type="text/css" href="__STATIC__/plugins/webuploader/webuploader.css?v=<?php echo $v; ?>" />

<link href="__ADMIN__/css/style.css?v=<?php echo $v; ?>" rel="stylesheet" type="text/css" />   
<script src="__STATIC__/js/jquery.min.js?v=<?php echo $v; ?>"></script>  
<script src="__ADMIN__/js/ligerui/js/ligerui.all.js?v=<?php echo $v; ?>" type="text/javascript"></script> 
<script type='text/javascript' src='__STATIC__/plugins/layer/layer.js?v=<?php echo $v; ?>'></script> 
<script src="__STATIC__/js/common.js?v=<?php echo $v; ?>"></script>
<script>
window.conf = {"ROOT":"__ROOT__","APP":"__APP__","STATIC":"__STATIC__","SUFFIX":"<?php echo config('url_html_suffix'); ?>","GOODS_LOGO":"<?php echo WSTConf('CONF.goodsLogo'); ?>","SHOP_LOGO":"<?php echo WSTConf('CONF.shopLogo'); ?>","MALL_LOGO":"<?php echo WSTConf('CONF.mallLogo'); ?>","USER_LOGO":"<?php echo WSTConf('CONF.userLogo'); ?>",'GRANT':'<?php echo implode(",",session("WST_STAFF.privileges")); ?>'}
</script>
<script src="__ADMIN__/js/common.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/jquery.validator.js?v=<?php echo $v; ?>"></script>
<script type="text/javascript" src="__STATIC__/plugins/validator/local/zh-CN.js?v=<?php echo $v; ?>"></script>
</head>
<body>

<style>
.goodsCat{display:inline-block;width:150px}
.accreds{display:inline-block;width:150px}
</style>
<div class="l-loading" style="display: block" id="wst-loading"></div>
<form id='editFrom' autocomplete='off'>
<input type='hidden' id='shopId' class='ipt' value="<?php echo $object['shopId']; ?>"/>
<input type='hidden' id='applyId' class='ipt' value="<?php echo $object['applyId']; ?>"/>
<table class='wst-form wst-box-top'>
  <tr>
     <td colspan='2' class='head-ititle'>基础信息</td>
  </tr>
 <?php if($object['shopId']==0 || $object['userId']==0): ?>
  <tr>
     <th width='150'>登录账号<font color='red'>*</font>：</th>
     <td><input type="text" id='loginName' name='loginName' class='ipt' value="<?php echo $object['loginName']; ?>" maxLength='20' data-rule="登录账号: required;length[6~20];remote(post:<?php echo url('admin/users/checkLoginKey'); ?>)" onkeyup="javascript:WST.isChinese(this,1)"/></td>
  </tr>
  <tr>
     <th>登录密码<font color='red'>*</font>：</th>
     <td><input type="password" id='loginPwd' class='ipt' maxLength='20' value='88888888' data-rule="登录密码: required;length[6~20];" data-target="#msg_loginPwd"/>
     <span class='msg-box' id='msg_loginPwd'>(默认为88888888)</span>
     </td>
  </tr>
 <?php endif; ?>
  <tr>
     <th width='150'>店铺编号<?php if($object['shopId']>0): ?><font color='red'>*</font><?php endif; ?>：</th>
     <td><input type="text" id='shopSn' name='shopSn' class='ipt' value="<?php echo $object['shopSn']; ?>" maxLength='20' data-rule="店铺编号:<?php if($object['shopId']>0): ?>required;length[1~20];<?php else: ?>ignoreBlank;<?php endif; ?>remote(post:<?php echo url('admin/shops/checkShopSn',array('shopId'=>$object['shopId'])); ?>)" data-target="#msg_shopSn"/><span class='msg-box' id='msg_shopSn'><?php if($object['shopId']==0): ?>(为空则自动生成'S000000001'类型号码)<?php endif; ?></span></td>
  </tr>
  <tr>
     <th>店铺名称<font color='red'>*</font>：</th>
     <td><input type="text" id='shopName' class='ipt' value="<?php echo $object['shopName']; ?>" maxLength='20' data-rule="店铺名称: required;"/></td>
  </tr>
  <tr>
     <th>店主姓名<font color='red'>*</font>：</th>
     <td><input type="text" id='shopkeeper' class='ipt' value="<?php echo $object['shopkeeper']; ?>" maxLength='20' data-rule="店主姓名: required;"/></td>
  </tr>
  <tr>
     <th>店主联系手机<font color='red'>*</font>：</th>
     <td><input type="text" id='telephone' class='ipt' value="<?php echo $object['telephone']; ?>" maxLength='11' data-rule="店主联系手机: required;mobile" data-rule-mobile="[/^1[3458]\d{9}$/, '请检查手机号格式']"/></td>
  </tr>
  <tr>
     <th>公司名称<font color='red'>*</font>：</th>
     <td><input type="text" id='shopCompany' class='ipt' value="<?php echo $object['shopCompany']; ?>"  style='width:400px;' maxLength='50' data-rule="公司名称: required;"/></td>
  </tr>
  <tr>
     <th>店铺联系电话<font color='red'>*</font>：</th>
     <td><input type="text" id='shopTel' class='ipt' value="<?php echo $object['shopTel']; ?>" maxLength='50' data-rule="店铺联系电话: required;"/></td>
  </tr>
  
  <tr>
     <th>经营范围<font color='red'>*</font>：</th>
     <td>
       <?php if(is_array($goodsCatList) || $goodsCatList instanceof \think\Collection): $i = 0; $__LIST__ = $goodsCatList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
       <label class='goodsCat'>
          <input type='checkbox' class='ipt' name='goodsCatIds' value='<?php echo $vo["catId"]; ?>' <?php if($i == 1): ?>data-rule="经营范围:checked"<?php endif; if(array_key_exists($vo['catId'],$object['catshops'])): ?>checked<?php endif; ?>/><?php echo $vo["catName"]; ?>
       </label>
       <?php endforeach; endif; else: echo "" ;endif; ?>
     </td>
  </tr>
  <tr>
     <th>认证类型：</th>
     <td>
       <?php if(is_array($accredList) || $accredList instanceof \think\Collection): $i = 0; $__LIST__ = $accredList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
       <label class='accreds'>
          <input type='checkbox' class='ipt' name='accredIds' value='<?php echo $vo["accredId"]; ?>' <?php if(array_key_exists($vo['accredId'],$object['accreds'])): ?>checked<?php endif; ?>/><?php echo $vo["accredName"]; ?>
       </label>
       <?php endforeach; endif; else: echo "" ;endif; ?>
     </td>
  </tr>
  <tr>
     <th>店铺图标<font color='red'>*</font>：</th>
     <td>
     <div id='shopImgPicker'>上传店铺图标</div><span id='uploadMsg'></span><span class='msg-box' id='msg_shopImg'></span>
     <?php if($object["shopImg"]!=''): ?>
     <img id='preview' width='150' height='150' src='__ROOT__/<?php echo $object["shopImg"]; ?>'/>
     <?php else: ?>
     <img id='preview' width='150' height='150' src="__ROOT__/<?php echo WSTConf('CONF.shopLogo'); ?>"/>
     <?php endif; ?>
     <input type="hidden" id='shopImg' class='ipt' value="<?php echo $object['shopImg']; ?>" data-rule="店铺图标: required;" data-target='#msg_shopImg'/>
     </td>
  </tr>
  <tr>
     <th>客服QQ：</th>
     <td><input type="text" id='shopQQ' class='ipt' value="<?php echo $object['shopQQ']; ?>" maxLength='200'/></td>
  </tr>
  <tr>
     <th>阿里旺旺：</th>
     <td><input type="text" id='shopWangWang' class='ipt' value="<?php echo $object['shopWangWang']; ?>" maxLength='200'/></td>
  </tr>
  <tr>
     <th>所属区域<font color='red'>*</font>：</th>
     <td>
        <select id="area_0" class='ipt j-areas' level="0" onchange="WST.ITAreas({id:'area_0',val:this.value,isRequire:true,className:'j-areas'});">
	      	<option value="">-请选择-</option>
	      	<?php if(is_array($areaList) || $areaList instanceof \think\Collection): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
     </td>
  </tr>
  <tr>
     <th>店铺详细地址<font color='red'>*</font>：</th>
     <td><input type="text" id='shopAddress' class='ipt' style='width:500px;' value="<?php echo $object['shopAddress']; ?>" maxLength='50' data-rule="店铺详细地址: required;"/></td>
  </tr>
  <tr>
     <th>是否提供开发票<font color='red'>*</font>：</th>
     <td>
        <label>
           <input type='radio' class='ipt' name='isInvoice' id='isInvoice1' value='1' <?php if($object['isInvoice']==1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(1,"#trInvoice")'>是
        </label>
        <label>
           <input type='radio' class='ipt' name='isInvoice' value='0' <?php if($object['isInvoice']==0): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(0,"#trInvoice")'>否
        </label>
     </td>
  </tr>
  <tr id='trInvoice' <?php if($object['isInvoice']==0): ?>style='display:none'<?php endif; ?>>
     <th>发票说明<font color='red'>*</font>：</th>
     <td><input type="text" id='invoiceRemarks' class='ipt' value="<?php echo $object['invoiceRemarks']; ?>" style='width:500px;' maxLength='100' data-rule="发票说明:required(#isInvoice1:checked)"/></td>
  </tr>
  <tr>
     <th>营业状态<font color='red'>*</font>：</th>
     <td>
        <label>
           <input type='radio' class='ipt' name='shopAtive' value='1' <?php if($object['shopAtive']==1): ?>checked<?php endif; ?>>营业中
        </label>
        <label>
           <input type='radio' class='ipt' name='shopAtive' value='0' <?php if($object['shopAtive']==0): ?>checked<?php endif; ?>>休息中
        </label>
     </td>
  </tr>
  <tr>
     <th>默认运费：</th>
     <td><input type="text" id='freight' class='ipt' value="<?php echo $object['freight']; ?>" maxLength='8' data-rule="默认运费: required;" onkeypress='return WST.isNumberdoteKey(event);' onkeyup="javascript:WST.isChinese(this,1)"/></td>
  </tr>
  <tr>
     <th>服务时间<font color='red'>*</font>：</th>
     <td>
        <select class='ipt' id='serviceStartTime'></select>
        至
        <select class='ipt' id='serviceEndTime'></select>
     </td>
  </tr>
  <tr>
     <td colspan='2' class='head-ititle'>结算信息</td>
  </tr>
  <tr>
     <th>所属银行<font color='red'>*</font>：</th>
     <td>
       <select class='ipt' id='bankId' data-rule="所属银行: required;">
          <option value=''>请选择</option>
          <?php if(is_array($bankList) || $bankList instanceof \think\Collection): $i = 0; $__LIST__ = $bankList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
          <option value='<?php echo $vo["bankId"]; ?>' <?php if($object['bankId']==$vo['bankId']): ?>selected<?php endif; ?>><?php echo $vo["bankName"]; ?></option>
          <?php endforeach; endif; else: echo "" ;endif; ?>
       </select>
     </td>
  </tr>
  <tr>
    <th>开户地区<font color='red'>*</font>：</th>
    <td>
      <select id="barea_0" class='ipt j-bareas' level="0" onchange="WST.ITAreas({id:'barea_0',val:this.value,isRequire:true,className:'j-bareas'});">
          <option value="">-请选择-</option>
          <?php if(is_array($areaList) || $areaList instanceof \think\Collection): $i = 0; $__LIST__ = $areaList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
          <option value="<?php echo $vo['areaId']; ?>"><?php echo $vo['areaName']; ?></option>
          <?php endforeach; endif; else: echo "" ;endif; ?>
       </select>
    </td>
  </tr>
  <tr>
     <th>银行卡账号<font color='red'>*</font>：</th>
     <td><input type="text" id='bankNo' class='ipt' value="<?php echo $object['bankNo']; ?>" maxLength='20'  style='width:300px;' data-rule="银行卡账号: required;"/></td>
  </tr>
  <tr>
     <th>持卡人<font color='red'>*</font>：</th>
     <td><input type="text" id='bankUserName' class='ipt' value="<?php echo $object['bankUserName']; ?>" maxLength='50' data-rule="持卡人: required;"/></td>
  </tr>
  <tr>
     <td colspan='2' class='head-ititle'>审核状态</td>
  </tr>
  <tr>
     <th>店铺状态<font color='red'>*</font>：</th>
     <td>
        <label>
           <input type='radio' class='ipt' name='shopStatus' id='shopStatus-1' value='-1' <?php if($object['shopStatus']==-1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(1,"#trStatusDesc")'>停止
        </label>
        <label>
           <input type='radio' class='ipt' name='shopStatus' value='1' <?php if($object['shopStatus']==1): ?>checked<?php endif; ?> onclick='javascript:WST.showHide(0,"#trStatusDesc")'>正常
        </label>
     </td>
  </tr>
  <tr id='trStatusDesc' <?php if($object['shopStatus']==1): ?>style='display:none'<?php endif; ?>>
     <th>停止原因<font color='red'>*</font>：</th>
     <td><textarea id='statusDesc' class='ipt' style='width:500px;height:100px;' maxLength='100' data-rule="停止原因:required(#shopStatus-1:checked);"><?php echo $object['statusDesc']; ?></textarea></td>
  </tr>
  <tr>
     <td colspan='2' align='center'>
       <input type='button' value='保存' class='btn btn-blue' onclick='javascript:save()'>
       <input type='button' value='返回' class='btn' onclick='javascript:history.go(-1)'>
     </td>
  </tr>
</table>
</form>
<script>
$(function(){initEdit({serviceStartTime:'<?php echo date("H:i",strtotime($object["serviceStartTime"])); ?>',serviceEndTime:'<?php echo date("H:i",strtotime($object["serviceEndTime"])); ?>',areaId:'<?php echo $object["areaId"]; ?>',areaIdPath:'<?php echo $object["areaIdPath"]; ?>',bankAreaId:'<?php echo $object["bankAreaId"]; ?>',bankAreaIdPath:'<?php echo $object["bankAreaIdPath"]; ?>'});})
</script>


<script type='text/javascript' src='__STATIC__/plugins/webuploader/webuploader.js?v=<?php echo $v; ?>'></script>
<script src="__ADMIN__/shops/shops.js?v=<?php echo $v; ?>" type="text/javascript"></script>

<script>
function showImg(opt){
	layer.photos(opt);
}
function showBox(opts){
	return WST.open(opts);
}
</script>
</body>
</html>