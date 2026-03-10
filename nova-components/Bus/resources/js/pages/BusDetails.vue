<template>
    <div class="relative">
        <div class="container mx-auto py-8">
            <!-- Loading State -->
            <div v-if="loading" class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-400 text-lg">Loading bus details...</p>
            </div>

            <!-- Error State -->
            <div v-else-if="error" class="text-center py-8">
                <p class="text-red-600 dark:text-red-400 text-lg">{{ error }}</p>
                <button @click="fetchBusDetails" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500">Retry</button>
            </div>

            <!-- Details Content -->
            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bus Image (Placeholder) -->
                <div class="md:col-span-1">
                    <img 
                        src="https://ncgexpress.lk/wp-content/uploads/2024/05/passara-to-colombo-99-ncg-express.webp" 
                        :alt="bus.make"
                        class="w-full rounded-lg shadow-lg dark:opacity-90"
                    >
                </div>

                <!-- Bus Information -->
                <div class="md:col-span-2">
                    <h1 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">{{ bus.make }} {{ bus.model }}</h1>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm uppercase tracking-wide font-bold">License Plate</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ bus.license_plate }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm uppercase tracking-wide font-bold">Year</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ bus.year }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm uppercase tracking-wide font-bold">Color</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ bus.color }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm uppercase tracking-wide font-bold">Status</p>
                                <p class="text-xl font-semibold capitalize" :class="bus.status === 'available' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                    {{ bus.status }}
                                </p>
                            </div>
                             <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm uppercase tracking-wide font-bold">Created At</p>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ bus.created_at }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm uppercase tracking-wide font-bold">Last Updated</p>
                                <p class="text-base text-gray-900 dark:text-gray-100">{{ bus.updated_at }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a :href="`/nova/resources/vehicles/${bus.id}`" class="inline-block bg-primary-500 text-white px-6 py-2 rounded-lg hover:bg-primary-600 no-underline shadow transition">
                            View in Nova
                        </a>
                         <a :href="`/nova/resources/vehicles/${bus.id}/edit`" class="inline-block bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 no-underline shadow transition">
                            Edit Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BusDetails',
    props: ['busId'], // Accepts the ID passed from the route
    data() {
        return {
            bus: null,
            loading: true,
            error: null
        }
    },
    mounted() {
        this.fetchBusDetails()
    },
    methods: {
        async fetchBusDetails() {
            this.loading = true;
            this.error = null;
            try {
                // Call the API endpoint we created earlier
                const { data } = await Nova.request().get(`/nova-vendor/bus/busdetails/${this.busId}`);
                this.bus = data;
                this.loading = false;
            } catch (error) {
                console.error(error);
                this.error = "Failed to load bus details. Please try again.";
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>
/* No custom styles needed for basic Nova theme compatibility */
</style>