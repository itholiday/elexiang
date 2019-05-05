<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:66:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/goodscats/list.html";i:1478578768;s:56:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/base.html";i:1477495670;}*/ ?>
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

<div class="l-loading" style="display: block" id="wst-loading"></div>
<?php if(WSTGrant('SPFL_01')): ?>
<div class="wst-toolbar">
   <button class="btn btn-green f-right" onclick='javascript:toEdit(0)'>新增</button>
   <div style='clear:both'></div>
</div>
<?php endif; ?>
<div id="maingrid"></div>
<div id='goodscatsBox' style='display:none'>
  <form id='goodscatsForm' autocomplete="off">
  <input type='hidden' id='parentId' name="parentId" class='ipt'/>
  <table class='wst-form wst-box-top'>
     <tr>
        <th width='100'>商品分类名称<font color='red'>*</font>：</th>
        <td><input type='text' id='catName' name="catName" class='ipt' maxLength='20' style='width:200px;'/></td>
     </tr>
     <tr>
        <th width='100'>佣金<font color='red'>*</font>：</th>
        <td height='24'>
            <input type="text" id="commissionRate" name="commissionRate" class="ipt" value="-1" data-target="#msg_commissionRate" size='7' class='ipt'>%<span id='msg_commissionRate'>（-1代表继承上级佣金）</span>
        </td>
     </tr>
     <tr>
        <th width='100'>是否显示<font color='red'>*</font>：</th>
        <td height='24'>
           <label>
              <input type="radio" id="isShow1" name="isShow" class="ipt" value="1" checked>显示
           </label>
           <label>
              <input type="radio" id="isShow0" name="isShow" class="ipt" value="0">隐藏
           </label>
        </td>
     </tr>
     <tr>
        <th width='100'>是否首页楼层<font color='red'>*</font>：</th>
        <td height='24'>
           <label>
              <input type="radio" id="isFloor1" name="isFloor" class="ipt" value="1" checked>推荐
           </label>
           <label>
              <input type="radio" id="isFloor0" name="isFloor" class="ipt" value="0">不推荐
           </label>
        </td>
     </tr>
     <tr>
        <th width='100'>排序号<font color='red'>*</font>：</th>
        <td><input type='text' id='catSort' name='catSort' class='ipt' style='width:60px;' onkeypress='return WST.isNumberKey(event);' onkeyup="javascript:WST.isChinese(this,1)" maxLength='10' value='0'/></td>
     </tr>
  </table>
  </form>
</div>


<script src="__ADMIN__/js/wstgridtree.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script src="__ADMIN__/goodscats/goodscats.js?v=<?php echo $v; ?>" type="text/javascript"></script>
<script>
$(function(){initGrid();})
</script>

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