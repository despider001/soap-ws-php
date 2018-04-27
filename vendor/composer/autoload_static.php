<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1014d7010e9192fc9376f3d2a6051ecd
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'Wingu\\OctopusCore\\Reflection\\' => 29,
        ),
        'P' => 
        array (
            'PHP2WSDL\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Wingu\\OctopusCore\\Reflection\\' => 
        array (
            0 => __DIR__ . '/..' . '/wingu/reflection/src',
        ),
        'PHP2WSDL\\' => 
        array (
            0 => __DIR__ . '/..' . '/php2wsdl/php2wsdl/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1014d7010e9192fc9376f3d2a6051ecd::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1014d7010e9192fc9376f3d2a6051ecd::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}