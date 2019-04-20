<?php
/*
Plugin Name: Olnhausen Design Support
Description: Support och anpassad kod för din hemsida från Olnhausen Design. Behövs för att hemsidan skall fungera.
Version: 0.9
License: GPL
Author: Olnhausen Design
Author URI: http://www.olnhausendesign.se
*/
require 'white-label.php';
require 'optimizations.php';

//Plugin updating
require 'plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/olivervo/odsupport',
	__FILE__,
	'odsupport'
);

//Optional: Set the branch that contains the stable release.
$myUpdateChecker->setBranch('master');
?>
