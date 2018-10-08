<?php

/**
 * ZfcUserAdmin Configuration
 *
 * If you have a ./config/autoload/ directory set up for your project, you can
 * drop this config file in it and change the values as you wish.
 */
$settings = array(
    /**
     * Mapper for ZfcUser
     *
     * Set the mapper to be used here
     * Currently Available mappers
     * 
     * ZfcUserAdmin\Mapper\UserDoctrine
     *
     * By default this is using
     * ZfcUserAdmin\Mapper\UserZendDb
     */
    'user_mapper' => 'ZfcUserAdmin\Mapper\UserDoctrine',
    'user_list_elements' => array('Id' => 'id', 'Name' => 'displayName', 'Email address' => 'email', "Rol" => "roles", "Tel" => "tel"),
    'create_user_auto_password' => false,
    'create_form_elements' => array(
        'Nombre' => 'displayName',
        "Username" => 'username',
    ),
    'edit_form_elements' => array(
        'Name' => 'displayName',
        "Username" => 'username',
    ),
);

/**
 * You do not need to edit below this line
 */
return array(
    'zfcuseradmin' => $settings
);
