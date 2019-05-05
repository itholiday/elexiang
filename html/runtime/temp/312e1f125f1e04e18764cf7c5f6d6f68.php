<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:65:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/speccats/list.html";i:1477495608;s:56:"/www/wwwroot/test.1041la.cn/wstmart/admin/view/base.html";i:1477495670;}*/ ?>
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
	<div class="f-left">
		<div id="pcat_0_box" class="f-left">
		 所属分类：<select id="cat_0" class='ipt pgoodsCats' level="0" onchange="WST.ITGoodsCats({id:'cat_0',val:this.value,isRequire:false,className:'pgoodsCats'});">
	      	<option value="">-请选择-</option>
	      	<?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
	        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	     </select>
	     </div>
	     <input type="text" id="keyName" placeholder="请输入规格名称"/>
	     <button class="btn btn-green" onclick="loadGrid(0)">查询</button>
     </div>
   <?php if(WSTGrant('SPGG_01')): ?>
   <button class="btn btn-green f-right" onclick="javascript:toEditCat(0);">新增</button>
   <?php endif; ?>
   <div style="clear:both"></div>
</div>
<div id="maingrid"></div>
<div id='specCatsBox' style='display:none'>
	<form id="specCatsForm">
	    <input type='hidden' id='catId' class='ipt'/>
		<table class='wst-form wst-box-top'>
		  <tr>
		      <th width='150'>
		    	 所属商品分类<font color='red'>*</font>：</th>
		     	<td id="bcat_0_box">
		            <select id="bcat_0" class='ipt goodsCats' level="0" onchange="WST.ITGoodsCats({id:'bcat_0',val:this.value,isRequire:false,className:'goodsCats'});" data-rule='所属商品分类:required;' data-target="#msg_bcat_0">
		                <option value="">-请选择-</option>
		                <?php $_result=WSTGoodsCats(0);if(is_array($_result) || $_result instanceof \think\Collection): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				        <option value="<?php echo $vo['catId']; ?>"><?php echo $vo['catName']; ?></option>
				        <?php endforeach; endif; else: echo "" ;endif; ?>
		           	</select>
		           	<span class='msg-box' id='msg_bcat_0' style='color:red;'>(至少选择一个商品分类)</span>
		          </td>
		       </tr>
		       <tr>
		          <th>规格名称<font color='red'>*</font>：</th>
		          <td>
		              <input type="text" id="catName" name="catName" class="ipt" maxLength='20'/>
		          </td>
		       </tr>
		       <tr>
		          <th>是否允许上传图片<font color='red'>  </font>：</th>
		          <td>
		            <label>
		              <input type="radio" name="isAllowImg" value="1" class="ipt"  />允许
		            </lable>
		            <label>
		              <input type="radio" name="isAllowImg" value="0" class="ipt" />不允许
		            </label>
		            <span style='color:red;margin-left:20px;'>(*同一分类下只能设置一个上传图片的规格分类)</span>
		          </td>
		       </tr>
		       <tr>
		          <th>是否显示<font color='red'>  </font>：</th>
		          <td>
		            <label>
		              <input type="radio" name="isShow" value="1" class="ipt"  />显示
		            </label>
		            <label>
		              <input type="radio" name="isShow" value="0" class="ipt" />隐藏
		            </label>
		          </td>
		       </tr>
		</table>
	</form>
</div>


<script src="__ADMIN__/speccats/speccats.js?v=<?php echo $v; ?>" type="text/javascript"></script>

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