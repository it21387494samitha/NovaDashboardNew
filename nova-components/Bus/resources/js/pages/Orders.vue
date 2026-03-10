<template>
  <div>
    <Head title="Bus Orders" />

    <div class="mb-6 flex items-center justify-between gap-4">
      <Heading>Bus Orders (Custom Page)</Heading>

      <div class="flex items-center gap-2">
        <Link href="/bus" class="btn btn-default">Bus Home</Link>
        <button class="btn btn-default" :disabled="loading" @click="fetchOrders">
          {{ loading ? 'Refreshing...' : 'Refresh' }}
        </button>
      </div>
    </div>

    <Card class="p-6">
      <p class="mb-4 text-sm text-80">
        This is a custom Nova Tool page example. It loads data from
        <code class="rounded bg-30 px-2 py-1 text-xs">/nova-vendor/bus/orders</code>.
      </p>

      <div v-if="error" class="mb-4 rounded border border-danger bg-danger-soft p-3 text-danger">
        {{ error }}
      </div>

      <div v-if="loading" class="py-8 text-center text-80">Loading orders...</div>

      <div v-else class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead>
            <tr class="border-b border-50 text-left text-xs uppercase tracking-wide text-80">
              <th class="px-3 py-2">ID</th>
              <th class="px-3 py-2">Company</th>
              <th class="px-3 py-2">Vehicle</th>
              <th class="px-3 py-2">Amount</th>
              <th class="px-3 py-2">Status</th>
              <th class="px-3 py-2">Order Date</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="order in orders" :key="order.id" class="border-b border-50">
              <td class="px-3 py-2">{{ order.id }}</td>
              <td class="px-3 py-2">{{ order.company_name || '-' }}</td>
              <td class="px-3 py-2">{{ order.vehicle_registration || '-' }}</td>
              <td class="px-3 py-2">{{ order.amount }}</td>
              <td class="px-3 py-2">
                <span class="rounded bg-30 px-2 py-1 text-xs uppercase">{{ order.status }}</span>
              </td>
              <td class="px-3 py-2">{{ order.order_date || '-' }}</td>
            </tr>
            <tr v-if="!orders.length">
              <td colspan="6" class="px-3 py-6 text-center text-80">No orders found.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </Card>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: false,
      error: '',
      orders: [],
    }
  },

  mounted() {
    this.fetchOrders()
  },

  methods: {
    async fetchOrders() {
      this.loading = true
      this.error = ''

      try {
        const { data } = await Nova.request().get('/nova-vendor/bus/orders')
        this.orders = data.orders || []
      } catch (e) {
        this.error = e?.response?.data?.message || 'Unable to load orders.'
      } finally {
        this.loading = false
      }
    },
  },
}
</script>
