<?php 
namespace elexiangmart\admin\validate;
use think\Validate;
/**
 * ============================================================================

 * 菜单验证器
 */
class Menus extends Validate{
	protected $rule = [
        ['menuName'  ,'require|max:30','请输入菜单名称|菜单名称不能超过10个字符'],
        ['parentId'  ,'number','无效的父级菜单']
    ];

    protected $scene = [
        'add'   =>  ['menuName','parentId'],
        'edit'  =>  ['menuName'],
    ]; 
}