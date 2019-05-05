<?php 
/**
 * ============================================================================

 */
return [
    'module_init'=> [
        'elexiangmart\\home\\behavior\\InitConfig'
    ],
    'action_begin'=> [
        'elexiangmart\\home\\behavior\\ListenProtectedUrl'
    ]
]
?>