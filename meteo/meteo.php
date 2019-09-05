<?php
/*
Plugin Name: Mon plugin météo
Plugin URI: https://github.com/Simon-BEE/plugin-meteo
Description: Un plugin affichant la météo
Version: 0.1
Author: Simon
Author URI: https://github.com/Simon-BEE/
Text Domain: meteo
 */

include_once plugin_dir_path(__FILE__).'classes/Weather.php';

// function adminChart_widget(){
//     wp_add_dashboard_widget('adminChartId', 'Chart Line about temperatures', 'adminChart');
// }

// add_action('wp_dashboard_setup', 'adminChart_widget');

// function adminChart() {
//     echo 'Contenu du widget';
// }

register_activation_hook(__FILE__,array('Weather','install'));
register_deactivation_hook(__FILE__,array('Weather','uninstall'));
register_uninstall_hook(__FILE__,array('Weather','uninstall'));
?>