<?php

namespace App\Nova\Dashboards;


use Laravel\Nova\Dashboard;
use App\Nova\Metrics\TotalVehicle;
// use App\Nova\Metrics\TotalVehicle;

class UserInsights extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            // new NewOrders,  
            new TotalVehicle,    
        ];
    }

    /**
     * Get the URI key for the dashboard.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-insights';
    }
}
