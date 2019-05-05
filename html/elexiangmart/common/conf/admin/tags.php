<?php 
/**
 * ============================================================================

 */
return [
    'module_init'=> [
        'elexiangmart\\admin\\behavior\\InitConfig'
    ],
    'action_begin'=> [
        'elexiangmart\\admin\\behavior\\ListenLoginStatus',
        'elexiangmart\\admin\\behavior\\ListenPrivilege',
        'elexiangmart\\admin\\behavior\\ListenOperate'
    ]
]
?>