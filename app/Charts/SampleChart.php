<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;

class SampleChart extends Chart
{
    /**
     * Initializes the chart.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    function getMonthName($monthNumber)
    {   
        return date("F", mktime(0, 0, 0, $monthNumber, 1));
    }
}
