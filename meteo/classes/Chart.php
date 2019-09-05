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
        $i = 0;
        $dataPoints = [];
        foreach ($weathers as $weather) {
            $data[$i] = (new SimpleXMLElement($weather->content))->forecast->time[$i];
            $i++;
            $dataPoints = ["y" => round($data[$i]->temperature['value']), 'label' => round($data[$i]['from'])];
        }
        var_dump($dataPoints);
        die();
        echo $this->chartView($dataPoints);
    }

    public function chartView($dataPoints)
    {
        echo "<script>
        window.onload = function () {
        
        var chart = new CanvasJS.Chart(\"chartContainer\", {
            title: {
                text: \"Push-ups Over a Week\"
            },
            axisY: {
                title: \"Number of Push-ups\"
            },
            data: [{
                type: \"line\",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        chart.render();
        
        }
        </script>
        <div id=\"chartContainer\" style=\"height: 370px; width: 100%;\"></div>
        <script src=\"https://canvasjs.com/assets/script/canvasjs.min.js\"></script>
        ";
    }
}

new Chart();