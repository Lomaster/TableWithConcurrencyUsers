<?php
define('_ProjectPrefix', 'TableWithConcurrencyUsers');
define('_GoodsDB', _ProjectPrefix.'_GoodsDB');
define('_TripsCollection', 'Trips');

define('CLASS_DIR', 'classes/');
set_include_path(get_include_path().PATH_SEPARATOR.CLASS_DIR);
spl_autoload_extensions('.class.php,.php');
spl_autoload_register(function($class) { return spl_autoload(str_replace('_', '/', $class));});


