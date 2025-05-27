<?php return array(
    'root' => array(
        'name' => '__root__',
        'pretty_version' => 'dev-main',
        'version' => 'dev-main',
        'reference' => 'e96c8b47ecec785085c9be4086c05ccb8781d000',
        'type' => 'library',
        'install_path' => __DIR__ . '/../../',
        'aliases' => array(),
        'dev' => true,
    ),
    'versions' => array(
        '__root__' => array(
            'pretty_version' => 'dev-main',
            'version' => 'dev-main',
            'reference' => 'e96c8b47ecec785085c9be4086c05ccb8781d000',
            'type' => 'library',
            'install_path' => __DIR__ . '/../../',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'sendgrid/php-http-client' => array(
            'pretty_version' => '3.10.7',
            'version' => '3.10.7.0',
            'reference' => 'f01e6fe7e33b811715ba93da3f85573e175bb801',
            'type' => 'library',
            'install_path' => __DIR__ . '/../sendgrid/php-http-client',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'sendgrid/sendgrid' => array(
            'pretty_version' => '6.2.0',
            'version' => '6.2.0.0',
            'reference' => '4d500a972739ef2c596299f3ad822dd231aab4df',
            'type' => 'library',
            'install_path' => __DIR__ . '/../sendgrid/sendgrid',
            'aliases' => array(),
            'dev_requirement' => false,
        ),
        'sendgrid/sendgrid-php' => array(
            'dev_requirement' => false,
            'replaced' => array(
                0 => '*',
            ),
        ),
    ),
);
