<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$route['default_controller'] = "app";
$route['404_override'] = '';

$route['^sl/(.+)$'] = "$1";
$route['^en/(.+)$'] = "$1";
$route['^fr/(.+)$'] = "$1";
$route['^it/(.+)$'] = "$1";
$route['^de/(.+)$'] = "$1";
$route['^pl/(.+)$'] = "$1";
$route['^sr/(.+)$'] = "$1";
$route['^hr/(.+)$'] = "$1";

$route['^sl$'] = $route['default_controller'];
$route['^en$'] = $route['default_controller'];
$route['^fr$'] = $route['default_controller'];
$route['^it$'] = $route['default_controller'];
$route['^de$'] = $route['default_controller'];
$route['^pl$'] = $route['default_controller'];
$route['^sr$'] = $route['default_controller'];
$route['^hr$'] = $route['default_controller'];