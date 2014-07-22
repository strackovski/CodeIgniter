<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| Asset source directory
|--------------------------------------------------------------------------
|
| Directory (relative from project root) where asset sources are stored.
| This is used by Assetic asset management tool.
|
*/
$config['assets_source_dir'] = 'Assets';

/*
|--------------------------------------------------------------------------
| Asset output directory
|--------------------------------------------------------------------------
|
| Directory (relative from project root) where compiled assets are stored.
| This is the directory that will contain the asset files available for you
| to include in your pages (such as css, javascript, images, etc).
|
*/
$config['assets_output_dir'] = 'web/assets';

/*
|--------------------------------------------------------------------------
| Asset compilation filters
|--------------------------------------------------------------------------
|
| Names of filters to apply for each asset type. The filters must be
| installed (the default ones are).
|
*/
$config['assets_css_filters'] = 'css_min';
$config['assets_js_filters'] = 'js_min';