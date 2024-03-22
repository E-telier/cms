<?php
function eCMSAutoload($classname)
{
    //Can't use __DIR__ as it's only in PHP 5.3+
    $filename = dirname(__FILE__).DIRECTORY_SEPARATOR.($classname).'.php';
    if (is_readable($filename)) {
        require $filename;
    }
}

//SPL autoloading was introduced in PHP 5.1.2
if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
      spl_autoload_register('eCMSAutoload', true, true);
} else {
      spl_autoload_register('eCMSAutoload');
}
