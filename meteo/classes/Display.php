<?php

class Display extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'idDisplay', 
            'Display Weather',
            array('description' => 'Widget for weather'));
        include_once plugin_dir_path( __FILE__ ).'Table.php';
    }

    /**
     * The principal function of the widget, return everything
     */
    public function widget($args, $instance)
    {
        $tW = $this->getWeather();
        
        $windTempMin = floor($tW->temperature['min']).'°';
        $windTempMax = ceil($tW->temperature['max']).'°';
        $day = 'Tomorrow';
        $date = (new \DateTime('tomorrow'))->format('d/m/Y');
        $wind = $tW->windSpeed['mps'];
        $humidity = $tW->humidity['value'].'%';
        $temp = round($tW->temperature['value']).'°';
        $city = $tW->city;
                
        $view = $this->view($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp);
        echo $view.'<br/>';
    }

    /**
     * Getting data about the xml files
     */
    public function getWeather()
    {
        $tomorrow = (new \DateTime('tomorrow'))->format('Y-m-d\T09:00:00');
        $weather = (new Table())->verifWeather();
        
        for ($i=0; $i < count($weather->forecast->time); $i++) { 
            if ($tomorrow == $weather->forecast->time[$i]['from']) {
                $tW = $weather->forecast->time[$i];
            }
        }
        $tW->city = $weather->location->name;
        return $tW;
    }

    /**
     * The widget's view
     */
    public function view($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp)
    {
        echo "<style>
        @import url('https://fonts.googleapis.com/css?family=Anton|Quicksand&display=swap');
        .title {
            font-family: Anton, sans-serif; }
          
          #card {
            width: 100%;
            min-height: 350px;
            padding: 20px;
            background: linear-gradient(to bottom, #01748d 0%, #4fbbb8 100%);
            color: #fff;
            font-family: Quicksand, sans-serif; }
            #card .border {
              border: 1px solid #fff;
              padding: 20px; }
              #card .border .inside {
                border-radius: 10px;
                background-color: rgba(255, 255, 255, 0.1);
                box-shadow: 0 0 5px #555;
                padding: 10px;
                display: flex;
                flex-direction: column;
                align-items: center; }
                #card .border .inside .top {
                  display: flex;
                  width: 100%;
                  justify-content: space-between; }
                  #card .border .inside .top .top-wind, #card .border .inside .top .top-wind {
                    display: flex; }
                #card .border .inside .middle {
                  display: flex;
                  width: 100%;
                  justify-content: space-between;
                  align-items: center; }
                  #card .border .inside .middle .center {
                    font-size: 2em; }
                    #card .border .inside .middle .center i {
                      font-size: 4em; }
                  #card .border .inside .middle .right {
                    font-size: 4em; }
        </style>
        <section id=\"card\">
        
            <div class=\"border\">
        
                <div class=\"inside\">
        
                    <div class=\"top\">
        
                        <h1 class=\"title\">".$city."</h1>
                        <div class=\"top-wind\">
                            <p><i class=\"fas fa-long-arrow-alt-down\"></i> ".$windTempMin."</p>
                            <p><i class=\"fas fa-long-arrow-alt-up\"></i> ".$windTempMax."</p>
                        </div>
                    </div>
        
                    <div class=\"middle\">
                        <div class=\"left\">
                            <h3>".$day."</h3>
                            <p class=\"date\">".$date."</p>
                            <p class=\"wind\">".$wind."</p>
                            <p class=\"wet\"><i class=\"fas fa-tint\"></i> ".$humidity."</p>
                        </div>
        
                        <div class=\"center\">
                            <i class=\"fas fa-cloud-sun\"></i>
                            <p>Cloudy</p>
                        </div>
                        <div class=\"right\">
                            <h2 class=\"title\">".$temp."</h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>";
    }
}

new Display();