<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit83cf536379e40aec2f94fe33b11abe74
{
    public static $files = array (
        '89ff252b349d4d088742a09c25f5dd74' => __DIR__ . '/..' . '/yahnis-elsts/plugin-update-checker/plugin-update-checker.php',
    );

    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Psr\\Container\\' => 14,
        ),
        'D' => 
        array (
            'DiviFramework\\UpdateChecker\\' => 28,
            'DiviFramework\\Hub\\' => 18,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Psr\\Container\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/container/src',
        ),
        'DiviFramework\\UpdateChecker\\' => 
        array (
            0 => __DIR__ . '/..' . '/diviframework/update-checker/src',
        ),
        'DiviFramework\\Hub\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'Pimple' => 
            array (
                0 => __DIR__ . '/..' . '/pimple/pimple/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit83cf536379e40aec2f94fe33b11abe74::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit83cf536379e40aec2f94fe33b11abe74::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit83cf536379e40aec2f94fe33b11abe74::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}