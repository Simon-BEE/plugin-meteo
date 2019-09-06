<?php

class Chart
{
    public function __construct()
    {
        include_once plugin_dir_path( __FILE__ ).'Table.php';
        add_action('wp_dashboard_setup', array($this, 'adminChart_widget'));
    }


    public function adminChart_widget(){
        return wp_add_dashboard_widget('adminChartId', 'Chart Line about temperatures', array($this, 'adminChart'));
    }
    
    public function adminChart() {
        $weathers = (new Table())->selectByCity('Moulins,fr');
        $dataPoints = [];
        if ($weathers) {
            foreach ($weathers as $weather) {
                $datas[] = (new SimpleXMLElement($weather->content));
                break;
            }
            foreach ($datas as $data) {
                for ($i=0; $i < count($data->forecast->time); $i++) { 
                    $weathersData[] = $data->forecast->time[$i];
                }
                
            }
            foreach ($weathersData as $weather) {
                $value = round($weather->temperature['value']);
                $dataPoints[] = ["y" => $value, 'label' => (array)$weather['from']];
            }
            echo $this->chartView($dataPoints);
        }else{
            echo "Aucune donnée pour Moulins";
        }
        
    }

    public function chartView($dataPoints)
    {
        echo "
        <script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script>
        <script>
        window.onload = function () {

            var chart = new CanvasJS.Chart(\"chartContainer\", {
                title: {
                    text: \"Moulins temperatures previsions\"
                },
                axisX: {
                    title: \"Date\",
                    suffix: \" \"
                },
                axisY: {
                    title: \"Temperature\",
                    suffix: \" °C\"
                },
                data: [{
                    type: \"area\",
                    markerSize: 0,
                    xValueFormatString: \"#,##0 °C\",
                    yValueFormatString: \"#,##0.000 mPa·s\",
                    dataPoints: ".json_encode($dataPoints, JSON_NUMERIC_CHECK)."
                }]
            });
            chart.render();
            
            }
        </script>
        <style>
        #adminChartId{width:100vw;};
        </style>
        <div id=\"chartContainer\" style=\"height: 500px; width: 100%;\"></div>
        ";
    }
}

new Chart();