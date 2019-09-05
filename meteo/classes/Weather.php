<?php

class Weather
{
    public function __construct()
    {
        include_once plugin_dir_path( __FILE__ ).'Display.php';
        include_once plugin_dir_path( __FILE__ ).'Chart.php';
        add_action('widgets_init',function(){register_widget('Display');});

        add_action('admin_menu',array($this,'declareAdmin'));

        add_action('mon_evenement', 'faire_ceci_chaque_heure');

        add_shortcode('meteo', array($this, 'meteo'));
    }

    public function faire_ceci_chaque_heure()
    {
        //nothing for the moment
    }

    /**
     * Méthode pour installer le plugin
     */
    public static function install()
    {
        Weather::install_db();
        if (! wp_next_scheduled ( 'mon_evenement' )) {
            wp_schedule_event(time(), 'hourly', 'mon_evenement');
        }
    }

    /**
     * Méthode pour desinstaller ou desactiver le plugin
     */
    public static function uninstall()
    {
        Weather::uninstall_db();
    }

    /**
     * Create two table and insert a default config
     */
    private function install_db()
    {
        global $wpdb;
        $wpdb->query("CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."weather (id int(11) AUTO_INCREMENT PRIMARY KEY, city varchar(255) NOT NULL, content LONGTEXT NOT NULL, created_at varchar(255) NOT NULL);");
        $wpdb->query("CREATE TABLE IF NOT EXISTS ".$wpdb->prefix."weather_config (city varchar(255) NOT NULL, token varchar(255) NOT NULL);");

        include_once plugin_dir_path(__FILE__).'../private.php';
        $wpdb->insert($wpdb->prefix."weather_config", array('city' => "Moulins,fr", 'token' => $token));

    }

    /**
     * Delete tables
     */
    private function uninstall_db()
    {
        global $wpdb;
        $wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."weather");
        $wpdb->query("DROP TABLE IF EXISTS ".$wpdb->prefix."weather_config");
    }

    /**
     * Function for the shortcode
     */
    public static function meteo()
    {
        $weather = (new Display())->getWeather();
        
        echo "La température à $weather->city pour le ".date('j', strtotime('tomorrow')) . ' ' . date('F', strtotime('tomorrow'))." sera de ".round($weather->temperature['value']).'°';
    }

    /**
     * Install an admin page about the plugin
     */
    public static function declareAdmin()
    {
        $class = new Weather();

        add_menu_page(
            'Configuration de la météo',
            'Météo',
            'manage_options',
            'meteo',
            array(&$class, 'menuHtml')
        );
    }

    /**
     * Display the view of the admin page and change config if needed
     */
    public function menuHtml()
    {
        $table = new Table();
        $config = $table->selectConfig();
        echo '<h1>'.get_admin_page_title().'</h1>';
        echo '<p>Changer les paramètres</p>';
        echo "<form method='POST' action='#'>
        <label>Choisir une ville et son code pays (exemple : Paris,fr)</label>
        <input type='text' name='city' value='".$config->city."'> <br />
        <label>Changer la clé de sécurité ( /!\ )</label>
        <input type='text' name='token' value='".$config->token."'> <br />
        <input type='submit'>
        </form>
        ";

        if (!empty($_POST['city']) && !empty($_POST['token'])) {
            $table->newConfig($_POST['city'], $_POST['token']);
        }
    }
}

new Weather();