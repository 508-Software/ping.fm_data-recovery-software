<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7aee9e2b506427f8ec2c025600a5729b
{
    public static $classMap = array (
        'Lozad\\Classes\\Settings\\SettingsConstants' => __DIR__ . '/../..' . '/classes/settings/SettingsConstants.php',
        'Lozad\\ContentProcessing' => __DIR__ . '/../..' . '/classes/ContentProcessing.php',
        'Lozad\\FrontPageProcessing' => __DIR__ . '/../..' . '/classes/FrontPageProcessing.php',
        'Lozad\\Settings\\AdminSettingsPage' => __DIR__ . '/../..' . '/classes/settings/AdminSettingsPage.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit7aee9e2b506427f8ec2c025600a5729b::$classMap;

        }, null, ClassLoader::class);
    }
}
