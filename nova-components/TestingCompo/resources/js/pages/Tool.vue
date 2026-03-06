<!--
  Tool.vue — The main page for our custom Nova Tool.

  WHAT THIS DOES:
  A complete "Order Analytics Dashboard" with:
    1. Two Chart.js charts (Doughnut + Bar) showing order statistics
    2. A filters bar (status dropdown, date inputs, search) — all "live"
    3. A data table with server-side pagination

  HOW THE DATA FLOW WORKS:
    mounted() → fetchStats() + fetchOrders()
                    ↓                ↓
        API: /orders/stats    API: /orders?status=&page=1
                    ↓                ↓
        Updates chartData     Updates orders[] + pagination
                    ↓                ↓
        Charts re-render      Table re-renders

  LIVE FILTERING:
    When user changes a filter input:
      1. v-model updates the reactive data property (e.g., filters.status)
      2. @change or @input event fires → calls applyFilters()
      3. applyFilters() calls fetchOrders() with new filter values
      4. API returns filtered results → table updates instantly (no page reload!)

  NOVA INTEGRATION:
    - Nova.request() = Axios instance pre-configured with CSRF token + auth
    - <Head>, <Heading>, <Card> = Nova's built-in UI components (globally available)
    - Tailwind CSS classes work because Nova includes Tailwind
-->
<template>
  <div>
    <Head title="Order Analytics" />

    <Heading class="mb-6">Order Analytics Dashboard</Heading>

    <!-- ========== CHARTS SECTION ========== -->
    <!-- Two charts side-by-side using Tailwind grid (2 columns on medium+ screens) -->
    <div class="grid md:grid-cols-2 gap-6 mb-6">

      <!-- Doughnut Chart: Orders by Status -->
      <Card class="p-6">
        <h3 class="text-lg font-semibold mb-4 dark:text-gray-200">Orders by Status</h3>
        <div style="max-height: 300px; display: flex; justify-content: center;">
          <DoughnutChart :chartData="statusChartData" />
        </div>
      </Card>

      <!-- Bar Chart: Orders by Month -->
      <Card class="p-6">
        <h3 class="text-lg font-semibold mb-4 dark:text-gray-200">Orders by Month</h3>
        <BarChart :chartData="monthChartData" />
      </Card>
    </div>

    <!-- ========== FILTERS SECTION ========== -->
    <!--
      Each filter uses v-model for two-way data binding:
        v-model="filters.status" means:
          - Input value is bound to filters.status (display)
          - When input changes, filters.status is updated (reactivity)

      @change / @input events trigger applyFilters() to re-fetch data
      The search input uses a debounce to avoid firing on every keystroke
    -->
    <Card class="p-4 mb-6">
      <div class="flex flex-wrap gap-4 items-end">

        <!-- Status Filter (dropdown) -->
        <div class="flex flex-col">
          <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Status</label>
          <select
            v-model="filters.status"
            @change="applyFilters"
            class="form-select rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-3 py-2 text-sm"
          >
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="failed">Failed</option>
          </select>
        </div>

        <!-- Date From Filter -->
        <div class="flex flex-col">
          <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">From Date</label>
          <input
            type="date"
            v-model="filters.date_from"
            @change="applyFilters"
            class="form-input rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-3 py-2 text-sm"
          />
        </div>

        <!-- Date To Filter -->
        <div class="flex flex-col">
          <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">To Date</label>
          <input
            type="date"
            v-model="filters.date_to"
            @change="applyFilters"
            class="form-input rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-3 py-2 text-sm"
          />
        </div>

        <!-- Search Input (with debounce) -->
        <div class="flex flex-col flex-1 min-w-[200px]">
          <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Search Company</label>
          <input
            type="text"
            v-model="filters.search"
            @input="debounceSearch"
            placeholder="Type company name..."
            class="form-input rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 px-3 py-2 text-sm"
          />
        </div>

        <!-- Reset button -->
        <button
          @click="resetFilters"
          class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded text-sm hover:bg-gray-300 dark:hover:bg-gray-600 transition"
        >
          Reset
        </button>
      </div>
    </Card>

    <!-- ========== DATA TABLE SECTION ========== -->
    <Card>
      <!-- Loading state -->
      <div v-if="loading" class="p-8 text-center text-gray-500">
        Loading orders...
      </div>

      <!-- Table -->
      <table v-else class="w-full text-left">
        <thead>
          <tr class="border-b border-gray-200 dark:border-gray-700">
            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">ID</th>
            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Company</th>
            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Amount</th>
            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Status</th>
            <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500">Order Date</th>
          </tr>
        </thead>
        <tbody>
          <!--
            v-for loops through each order in the orders array.
            :key is required by Vue for efficient DOM updates.
          -->
          <tr
            v-for="order in orders"
            :key="order.id"
            class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50"
          >
            <td class="px-4 py-3 text-sm dark:text-gray-300">{{ order.id }}</td>
            <td class="px-4 py-3 text-sm dark:text-gray-300">{{ order.company ? order.company.name : '-' }}</td>
            <td class="px-4 py-3 text-sm dark:text-gray-300">${{ parseFloat(order.amount).toFixed(2) }}</td>
            <td class="px-4 py-3 text-sm">
              <!--
                Dynamic CSS class based on status value.
                :class binds a JavaScript expression to the class attribute.
                statusClass() returns 'bg-green-100 text-green-800' for 'paid', etc.
              -->
              <span :class="statusClass(order.status)" class="px-2 py-1 rounded-full text-xs font-semibold">
                {{ order.status.charAt(0).toUpperCase() + order.status.slice(1) }}
              </span>
            </td>
            <td class="px-4 py-3 text-sm dark:text-gray-300">{{ order.order_date }}</td>
          </tr>

          <!-- Empty state -->
          <tr v-if="orders.length === 0">
            <td colspan="5" class="px-4 py-8 text-center text-gray-500">No orders found.</td>
          </tr>
        </tbody>
      </table>

      <!-- ========== PAGINATION ========== -->
      <!--
        Server-side pagination: the API returns { data: [...], current_page, last_page, ... }
        We show page numbers and handle clicks to fetch the next page.
      -->
      <div v-if="pagination.lastPage > 1" class="flex items-center justify-between px-4 py-3 border-t border-gray-200 dark:border-gray-700">
        <span class="text-sm text-gray-500">
          Page {{ pagination.currentPage }} of {{ pagination.lastPage }}
          ({{ pagination.total }} total)
        </span>
        <div class="flex gap-2">
          <button
            @click="goToPage(pagination.currentPage - 1)"
            :disabled="pagination.currentPage <= 1"
            class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
          >
            Previous
          </button>
          <button
            @click="goToPage(pagination.currentPage + 1)"
            :disabled="pagination.currentPage >= pagination.lastPage"
            class="px-3 py-1 text-sm rounded border border-gray-300 dark:border-gray-600 disabled:opacity-50 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
          >
            Next
          </button>
        </div>
      </div>
    </Card>
  </div>
</template>

<script>
/*
  Vue Component Script — the "brain" of the component.

  KEY CONCEPTS:
    - data()        → Reactive state. When these change, the template re-renders automatically.
    - methods       → Functions you can call from the template or other methods.
    - mounted()     → Lifecycle hook — runs once when component first appears on screen.
    - components    → Child components registered for use in this template.
    - Nova.request() → Axios wrapper that auto-includes CSRF token + auth cookie.
*/

import DoughnutChart from '../components/DoughnutChart.vue'
import BarChart from '../components/BarChart.vue'

export default {
  // Register child components so we can use <DoughnutChart /> and <BarChart /> in template
  components: {
    DoughnutChart,
    BarChart,
  },

  // data() returns the reactive state object.
  // Any change to these properties triggers a re-render of the template.
  data() {
    return {
      // Raw data from API
      orders: [],
      loading: true,

      // Filters — bound to inputs via v-model
      filters: {
        status: '',
        date_from: '',
        date_to: '',
        search: '',
      },

      // Pagination state (from Laravel's paginate() response)
      pagination: {
        currentPage: 1,
        lastPage: 1,
        total: 0,
      },

      // Chart data — formatted for Chart.js (passed to child components via props)
      statusChartData: {
        labels: [],
        values: [],
        colors: [],
      },
      monthChartData: {
        labels: [],
        values: [],
      },

      // Debounce timer for search input
      searchTimeout: null,
    }
  },

  // mounted() = called once when the component is first rendered
  // Perfect place to fetch initial data from the API
  mounted() {
    this.fetchStats()
    this.fetchOrders()
  },

  methods: {
    /**
     * Fetch chart statistics from the API.
     *
     * Nova.request() is an Axios instance pre-configured by Nova.
     * It automatically includes the CSRF token and session cookie.
     *
     * The URL '/nova-vendor/testing-compo/orders/stats' maps to
     * OrderController@stats via routes/api.php
     */
    async fetchStats() {
      try {
        const response = await Nova.request().get('/nova-vendor/testing-compo/orders/stats')
        const { byStatus, byMonth } = response.data

        // Format data for the Doughnut chart
        // byStatus comes as { paid: 85, pending: 25, failed: 15 }
        const colorMap = {
          paid: '#10B981',     // Green
          pending: '#F59E0B',  // Amber
          failed: '#EF4444',   // Red
        }
        this.statusChartData = {
          labels: Object.keys(byStatus).map(s => s.charAt(0).toUpperCase() + s.slice(1)),
          values: Object.values(byStatus),
          colors: Object.keys(byStatus).map(s => colorMap[s] || '#6B7280'),
        }

        // Format data for the Bar chart
        // byMonth comes as [{ month: "2026-01", count: 20 }, ...]
        this.monthChartData = {
          labels: byMonth.map(item => {
            // Convert "2026-01" to "Jan 2026"
            const [year, month] = item.month.split('-')
            const date = new Date(year, parseInt(month) - 1)
            return date.toLocaleDateString('en-US', { month: 'short', year: 'numeric' })
          }),
          values: byMonth.map(item => item.count),
        }
      } catch (error) {
        console.error('Failed to fetch stats:', error)
      }
    },

    /**
     * Fetch orders from the API with current filters and pagination.
     *
     * Builds a query string from this.filters + page number:
     *   /nova-vendor/testing-compo/orders?status=paid&search=acme&page=2
     */
    async fetchOrders(page = 1) {
      this.loading = true
      try {
        // Build query params from filters
        const params = {
          page: page,
          ...(this.filters.status && { status: this.filters.status }),
          ...(this.filters.date_from && { date_from: this.filters.date_from }),
          ...(this.filters.date_to && { date_to: this.filters.date_to }),
          ...(this.filters.search && { search: this.filters.search }),
        }

        const response = await Nova.request().get('/nova-vendor/testing-compo/orders', { params })

        // Laravel's paginate() returns: { data: [...], current_page, last_page, total, ... }
        this.orders = response.data.data
        this.pagination = {
          currentPage: response.data.current_page,
          lastPage: response.data.last_page,
          total: response.data.total,
        }
      } catch (error) {
        console.error('Failed to fetch orders:', error)
      } finally {
        this.loading = false
      }
    },

    /**
     * Called when any filter changes.
     * Resets to page 1 (filtered results may have fewer pages).
     */
    applyFilters() {
      this.fetchOrders(1)
    },

    /**
     * Debounced search — waits 300ms after the user stops typing
     * before making the API call. Prevents flooding the server
     * with requests on every keystroke.
     */
    debounceSearch() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.applyFilters()
      }, 300)
    },

    /**
     * Reset all filters and reload data.
     */
    resetFilters() {
      this.filters = { status: '', date_from: '', date_to: '', search: '' }
      this.fetchOrders(1)
    },

    /**
     * Navigate to a specific page.
     */
    goToPage(page) {
      if (page >= 1 && page <= this.pagination.lastPage) {
        this.fetchOrders(page)
      }
    },

    /**
     * Returns Tailwind CSS classes for status badges.
     * This is a helper method called from the template via :class="statusClass(order.status)"
     */
    statusClass(status) {
      const classes = {
        paid: 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400',
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800/20 dark:text-yellow-400',
        failed: 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400',
      }
      return classes[status] || 'bg-gray-100 text-gray-800'
    },
  },
}
</script>

<style>
/* Custom styles for this tool — minimal since we use Tailwind from Nova */
</style>
