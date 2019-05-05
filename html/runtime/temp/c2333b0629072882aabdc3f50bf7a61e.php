<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:68:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/recommends/shops.html";i:1478085540;s:56:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/base.html";i:1477495670;}*/ ?>
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
<form autocomplete='off'>
<table class='wst-form wst-box-top'>
	  <tr>
	     <th width='120'>经营范围<font color='red'>*</font>：</th>
	     <td colspan='2'>
	        <select id="cat12_0" class='ipt pgoodsCats1_2' level="0" onchange="WST.ITGoodsCats({id:'cat12_0',val:this.value,isRequire:false,className:'pgoodsCats1_2'});">
	          <option value=''>请选择</option>
	          <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	          <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	          <?php endforeach; endif; else: echo "" ;endif; ?>
	        </select>
	     </td>
	     <td>
	        &nbsp;
	     </td>
	  <tr>
	     <th width='120'>搜索：</th>
	     <td colspan='2'>
	        <input type='text' id='key_2' style='width:250px' placeholder='店铺名称、店铺编号'/>
	        <input type='button' value='搜索' class='btn btn-blue' onclick='javascript:loadShops("_2")'>
	     </td>
	     <td>
	       经营范围<font color='red'>*</font>：
	        <select id="cat22_0" class='ipt pgoodsCats2_2' level="0" onchange="WST.ITGoodsCats({id:'cat22_0',val:this.value,isRequire:false,className:'pgoodsCats2_2',afterFunc:'listQueryByShops'});">
	          <option value=''>所有范围</option>
	          <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	          <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	          <?php endforeach; endif; else: echo "" ;endif; ?>
	        </select>
	       <input type='hidden' id='dataType_2' value='0'/>
	     </td>
	  </tr>
	  <tr>
	     <th>请选择<font color='red'>*</font>：</th>
	     <td width='320'>
	       <div class="recom-lbox">
	            <div class="trow head">
	              <div class="tck"><input onclick="WST.checkChks(this,'.lchk_2')" type="checkbox"></div>
	              <div class="ttxt">店铺</div>
	            </div>
	            <div id="llist_2" style="width:350px;"></div>
	       </div>
	     </td>
	     <td align='center'>
	       <input type='button' value='》》' class='btn btn-blue' onclick='javascript:moveRight("_2")'/>
	       <br/><br/>
	       <input type='button' value='《《' class='btn btn-blue' onclick='javascript:moveLeft("_2")'/>
	       <input type='hidden' id='ids_2'/>
	     </td>
	     <td>
	       <div class="recom-rbox">
	            <div class="trow head">
		            <div class="tck"><input onclick="WST.checkChks(this,'.rchk_2')" type="checkbox"></div>
		            <div class="ttxt">店铺</div>
		            <div class="top">排序</div>
		        </div>
	            <div id="rlist_2"></div>
	       </div>
	     </td>
	  </tr>
	  <?php if(WSTGrant('DPTJ_04')): ?>
	  <tr>
	     <td colspan='4' align='center' style='padding-top:10px;'>
	       <input type='button' value='保存' class='btn btn-blue' onclick='javascript:editShops("_2")'>
	     </td>
	  </tr>
	  <?php endif; ?>
</table>
</form>


<script src="__ADMIN__/recommends/recommends.js?v=1<?php echo $v; ?>" type="text/javascript"></script>

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