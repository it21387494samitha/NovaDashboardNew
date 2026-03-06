<?php

namespace Samitha\TestingCompo\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

/**
 * API Controller for the custom TestingCompo tool.
 *
 * HOW THIS WORKS:
 * The Vue component makes AJAX calls to these endpoints.
 * Routes are registered in nova-components/TestingCompo/routes/api.php
 * and prefixed with /nova-vendor/testing-compo/
 *
 * So the full URL for index() would be:
 *   GET /nova-vendor/testing-compo/orders?status=paid&search=acme&page=1
 */
class OrderController extends Controller
{
    /**
     * List orders with filtering and pagination.
     *
     * Supports query parameters:
     *   ?status=paid           — Filter by status
     *   ?search=acme           — Search company name
     *   ?date_from=2026-01-01  — Orders from this date
     *   ?date_to=2026-03-01    — Orders until this date
     *   ?page=2                — Pagination page number
     */
    public function index(Request $request)
    {
        $query = Order::with('company'); // Eager load company to avoid N+1

        // Apply filters only if present in the request
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            // Search by company name using a whereHas (subquery on relationship)
            $search = $request->input('search');
            $query->whereHas('company', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('date_from')) {
            $query->where('order_date', '>=', $request->input('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('order_date', '<=', $request->input('date_to'));
        }

        // Paginate results (15 per page). Laravel handles ?page= automatically.
        $orders = $query->orderBy('order_date', 'desc')->paginate(15);

        return response()->json($orders);
    }

    /**
     * Aggregated stats for the charts.
     *
     * Returns:
     *   - byStatus: { pending: 25, paid: 85, failed: 15 }    → for pie/doughnut chart
     *   - byMonth:  [ { month: "Jan", count: 20 }, ... ]       → for bar chart
     */
    public function stats()
    {
        // Group orders by status → for doughnut chart
        $byStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Group orders by month (last 6 months) → for bar chart
        $byMonth = Order::select(
                DB::raw("strftime('%Y-%m', order_date) as month"),
                DB::raw('count(*) as count'),
                DB::raw('sum(amount) as total_amount')
            )
            ->where('order_date', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return response()->json([
            'byStatus' => $byStatus,
            'byMonth'  => $byMonth,
        ]);
    }
}
