<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:71:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/goodsappraises/list.html";i:1477495430;s:56:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/base.html";i:1477495670;}*/ ?>
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
<div class="wst-toolbar">

	<div id="query" style="float:left;">
		<p style="float:left;height:25px;line-height:25px;">地区:</p>

		<select id="areaId1" class='query' level="0" onchange="WST.ITAreas({id:'areaId1',val:this.value,className:'query'});" >
		  <option value="-1">-请选择-</option>
		  <?php foreach($area1 as $v): ?>
        	<option value="<?=$v['areaId']?>"><?=$v['areaName']?></option>
          <?php endforeach; ?>
		</select>
		<br />
		所属店铺:<input type="text" name="shopName" placeholder='店铺名称' id="shopName" class="query" />
		商品:<input type="text" name="goodsName" placeholder='商品名称' id="goodsName" class="query" />
		<input type="button" class="btn btn-blue" onclick="javascript:loadGrid1()" value="查询">
	</div>
   <div style="clear:both"></div>
</div>
<div id="maingrid"></div>

<script>
$(function(){
	initGrid();
});
</script>



<script src="__ADMIN__/goodsappraises/goodsappraises.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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