<template>
  <div>
    <Head title="Vehicle Live Location" />

    <div class="mb-6 flex items-center justify-between">
    <Heading>Live Location Coordinates</Heading>
      <div class="flex gap-2">
        <Link href="/bu" class="btn btn-default">Vehicle Home</Link>
        <button class="btn btn-default" @click="fetchVehicles" :disabled="loading">
          {{ loading ? 'Refreshing...' : 'Refresh now' }}
        </button>
      </div>
    </div>

    <Card class="p-6">
      <p class="mb-4 text-80 text-sm">
       This is only for demonstrain purposes. it shows the Latitude and longitude in table. <span class="text-lg text-red-500">Demo only</span>
      </p>

      <div v-if="error" class="mb-4 rounded border border-danger bg-danger-soft p-3 text-danger">
        {{ error }}
      </div>

      <div v-if="loading" class="py-6 text-center text-80">Loading vehicles...</div>

      <div v-else class="overflow-x-auto">
        <table class="w-full table-auto">
          <thead>
            <tr class="border-b border-50 text-left text-xs uppercase tracking-wide text-80">
              <th class="px-3 py-2">Vehicle</th>
              <th class="px-3 py-2">Plate</th>
              <th class="px-3 py-2">Status</th>
              <th class="px-3 py-2">Latitude</th>
              <th class="px-3 py-2">Longitude</th>
              <th class="px-3 py-2">Updated</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="v in vehicles" :key="v.id" class="border-b border-50">
              <td class="px-3 py-2">{{ v.name || ('Vehicle #' + v.id) }}</td>
              <td class="px-3 py-2">{{ v.license_plate || '-' }}</td>
              <td class="px-3 py-2">{{ v.status || '-' }}</td>
              <td class="px-3 py-2">{{ v.lat }}</td>
              <td class="px-3 py-2">{{ v.lng }}</td>
              <td class="px-3 py-2">{{ v.updated_at }}</td>
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
      vehicles: [],
      loading: false,
      error: '',
      intervalId: null,
    }
  },

  mounted() {
    this.fetchVehicles()
    this.intervalId = setInterval(this.fetchVehicles, 500000)  //to refresh evry 500000ms
  },

  beforeUnmount() {
    if (this.intervalId) clearInterval(this.intervalId)
  },

  methods: {
    async fetchVehicles() {
      this.loading = true
      this.error = ''

      try {
        const { data } = await Nova.request().get('/nova-vendor/bus/vehicle-locations')
        this.vehicles = data.vehicles || []
      } catch (e) {
        this.error = e?.response?.data?.message || 'Unable to load vehicle locations.'
      } finally {
        this.loading = false
      }
    },
  },
}
</script>