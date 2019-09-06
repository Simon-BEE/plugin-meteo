<?php

class Table
{
    /**
     * Select weather related to date and city
     */
    public function selectByDateAndCity($date, $city)
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."weather WHERE created_at = '$date' AND city = '$city'");
    }

    /**
     * Select weather related to date and city
     */
    public function selectByCity($city)
    {
        global $wpdb;
        return $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."weather WHERE city = '$city'");
    }

    /**
     * Verify if weather of the day and the city is not already in database
     */
    public function verifWeather()
    {
        global $wpdb;
        $now = (new DateTime())->format('d/m/Y');
        $config = $this->selectConfig();
        $where = $config->city;
        $table = $this->selectByDateAndCity($now, $where);
        
        if (!$table) {
            $weather = $this->newWeather();
        }else{
            $weather = new SimpleXMLElement($table->content);
        }

        return $weather;
    }

    public function newWeather()
    {
        global $wpdb;

        $now = (new DateTime())->format('d/m/Y');
        $where = $this->selectConfig()->city;
        $token = $this->selectConfig()->token;

        $request = wp_remote_request("http://api.openweathermap.org/data/2.5/forecast?q=".$where."&mode=xml&appid=".$token."&units=metric", array('method' => 'GET'));
        $body = wp_remote_retrieve_body($request);
        $weather = simplexml_load_string($body);

        $wpdb->insert($wpdb->prefix."weather", array('city' => $where, 'content' => $weather->asXML(), 'created_at' => $now));

        return $weather;
    }

    /**
     * Select the current config
     */
    public function selectConfig()
    {
        global $wpdb;
        return $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."weather_config");
    }

    /**
     * Update the weather_config table with new settings
     */
    public function newConfig($city, $token, $view)
    {
        global $wpdb;
        if ($wpdb->get_results("SELECT * FROM ".$wpdb->prefix."weather_config")) {
            $wpdb->query("TRUNCATE TABLE ".$wpdb->prefix."weather_config");
        }
        $wpdb->insert($wpdb->prefix."weather_config", array('city' => $city, 'token' => $token, 'view' => $view));
    }
}