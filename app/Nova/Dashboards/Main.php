<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\NewOrders;
use App\Nova\Metrics\OrdersByStatus;
use App\Nova\Metrics\OrdersPerDay;
use Laravel\Nova\Dashboards\Main as Dashboard;

/**
 * DASHBOARD — The main Nova dashboard page.
 *
 * cards() returns an array of metrics/cards to display.
 * Nova renders them in a responsive grid — typically 3 metrics per row.
 *
 * Each metric type renders differently:
 *   - Value    → Single number + trend arrow
 *   - Trend    → Line chart over time
 *   - Partition → Doughnut/pie chart
 */
class Main extends Dashboard
{
    public function cards()
    {
        return [
            new NewOrders,       // Value metric: total order count + comparison
            new OrdersPerDay,    // Trend metric: line chart of orders by day
            new OrdersByStatus,  // Partition metric: doughnut chart by status
            // new help,
        ];
    }
    
}
