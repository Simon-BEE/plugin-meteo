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
        
        if ((new Table())->selectConfig()->view == 0) {
            $view = $this->view($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp);
        }elseif((new Table())->selectConfig()->view == 1){
            $view = $this->secondView($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp);
        }else{
            $view = $this->thirdView($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp);
        }
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
        @import url('https://fonts.googleapis.com/css?family=Oswald:500|Quicksand:300&display=swap');
        *{box-sizing:border-box;margin:0;padding:0;font-family:Quicksand;}
        .title {
            font-family: Oswald, sans-serif;font-size: 2em;
            letter-spacing: 2px;
            text-transform: uppercase; }
            .left{
              margin-top: -30px;
            }
            .left p{
                margin-top: -10px;
            }
            .left h3{font-size:1em;font-family:'Quicksand';text-transform:uppercase;font-weight:lighter;}
            .top .title{
            margin-top: 60px;
            }
            .right .title{
                margin-top:10px;
            }
          #card {
            width: 50%;
            height: 350px;
            padding: 20px;
            background: linear-gradient(to bottom, #01748d 0%, #4fbbb8 100%);
            color: #fff;
            font-family: Quicksand, sans-serif; }
            #card .border {
              border: 1px solid #fff;
              padding: 20px; height:100%; }
              #card .border .inside {
                border-radius: 10px;
                box-shadow: 0 0 5px #555;
                padding: 20px;
                display: flex;
                flex-direction: column;
                align-items: center; height:100%;
                background: radial-gradient(ellipse farthest-corner at 150% 100%, #58d3cf 0%, #479eb0 71%, rgb(255,255,255, 0.3) 10%); }
                #card .border .inside .top {
                  display: flex;
                  width: 100%;
                  justify-content: space-between;margin-top:-75px; }
                  #card .border .inside .top .top-wind, #card .border .inside .top .top-wind {
                    display: flex; }
                #card .border .inside .middle {
                  display: flex;
                  width: 100%;
                  justify-content: space-between;
                  align-items: center; }
                  #card .border .inside .middle .center {
                    font-size: 2rm;
                    display: flex;
                    flex-direction: column;
                    align-items: center; }
                    #card .border .inside .middle .center i {
                      font-size: 4em; }
                  #card .border .inside .middle .right {
                    font-size: 35px;letter-spacing: 2px;font-stretch: extra-expanded; }
                    .top-wind{margin-top:68px;}
        </style>
        <section id=\"card\">
        
            <div class=\"border\">
        
                <div class=\"inside\">
        
                    <div class=\"top\">
        
                        <p class=\"title\">".$city."</p>
                        <div class=\"top-wind\">
                            <p><i class=\"fas fa-long-arrow-alt-down\"></i> ".$windTempMin." </p>
                            <p> <i class=\"fas fa-long-arrow-alt-up\"></i> ".$windTempMax."</p>
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
                            <p class=\"title\">".$temp."</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>";
    }

    public function secondView($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp)
    {
        echo "
        <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat&display=swap');
        .carte{
            padding: 5px;
            box-shadow: 0px 0px 20px #efefef;
            background-color: #444;
            width: 230px;
            height: auto;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
          }
          .carte-top{
            display: flex;
            align-items: center;
            justify-content: space-around;
            border-bottom: 1px solid #efefef;
            margin: 0 auto 15px auto;
            width: 80%;
          }
          .temp{
            font-weight: bold;
            font-size: 1.2rm;
          }
          .carte-mid{
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rm;
            border-bottom: 1px solid #f1f1f1;
            margin: 0 auto 15px auto;
            width: 80%;
            min-height: 65px;
          }
          .carte-bot, .min-max{
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 65px;
          }
          .wind{
            display: flex;
            flex-direction: column;
            margin-bottom: 16px;
            font-size:14px;
          }
          .min-max p {
            color: #afafaf;
            font-size: 16px;
          }
        </style>
        <div class=\"carte\">
            <div class=\"carte-top\">
                <div class=\"logo\">
                <i class=\"fas fa-cloud-sun\"></i>
                </div>
                <div class=\"temp\">$temp °C &nbsp; <i class=\"fas fa-thermometer-half\"></i></div>
            </div>
            <div class=\"carte-mid\">
                <p>$city</p>
            </div>
            <div class=\"carte-bot\">
                <div class=\"wind\">
                <i class=\"fas fa-wind\"></i>
                    <p>$wind km/h</p>
                </div>
                <div class=\"min-max\">
                    <p>$windTempMin °C</p>
                    <p>/</p>
                    <p>$windTempMax °C</p>
                </div>
            </div>
        </div>
        ";
    }

    public function thirdView($city, $windTempMin, $windTempMax, $day, $date, $wind, $humidity, $temp)
    {
        echo "
        <style>
        @import url('https://use.typekit.net/tzr8rho.css');
        .main{
            min-width: 500px;
            background-color: #1d4277;
            box-shadow: 0 0 10px #999;
            text-transform: uppercase;
            color: #fff;
            font-family: bebas-neue-by-fontfabric, sans-serif;
            font-weight: 200;
            font-style: normal;
        }
        .main >*, .main p{box-sizing: border-box;margin: 0;padding: 0;}
        .top{
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(to top, #205d8e 0%,#1d5988 100%);
            padding: 5%;
        }
        .top-left{
            display: flex;
            font-family: bebas-neue-by-fontfabric, sans-serif;
            font-weight: 700;
            font-style: normal;
            align-items: baseline;
            font-size: 1.6em;
        }
        .top-left i{
            font-size: 1.5em;
            margin-right: 10px;
        }
        .top-right{
            font-size: 1.9em;
        }
        .middle{
            width: 100%;
            display: flex;
            justify-content: space-around;
            padding: 6%;
            align-items: center;
        }
        .mid-left .title{
            font-size: 1.5em;
        }
        .min-max{
            display: flex;
            justify-content: space-between;
        }
        .big-temp{
            font-family: bebas-neue-by-fontfabric, sans-serif;
            font-weight: 700;
            font-style: normal;
            font-size: 4em;
        }
        .mid-right{
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 3em;
        }
        .mid-right i{
            font-size: 3em;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        </style>

        <div class=\"main\">
        <div class=\"top\">
            <div class=\"top-left\">
                <i class=\"fas fa-map-marker\"></i>
                <p class=\"title\">$city</p>
            </div>
            <div class=\"top-right\">
                <p>$date</p>
            </div>
        </div>
        <div class=\"middle\">
            <div class=\"mid-left\">
                <p class=\"title\">$day</p>
                <p>Humidity $humidity</p>
                <div class=\"min-max\">
                    <p><i class=\"fas fa-arrow-down\"></i> $windTempMin</p>
                    <p><i class=\"fas fa-arrow-up\"></i> $windTempMax</p>
                </div>
                <div class=\"big-temp\">
                    $temp
                </div>
            </div>
            <div class=\"mid-right\">
                <i class=\"fas fa-sun\"></i>
                <p>Clear</p>
            </div>
        </div>
        ";
    }
}

new Display();