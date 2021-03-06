<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitfc232463020e82115b2091e1211f28e3
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'Ortic\\ColorConverter\\' => 21,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Ortic\\ColorConverter\\' => 
        array (
            0 => __DIR__ . '/..' . '/ortic/color-converter/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitfc232463020e82115b2091e1211f28e3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitfc232463020e82115b2091e1211f28e3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitfc232463020e82115b2091e1211f28e3::$classMap;

        }, null, ClassLoader::class);
    }
}
