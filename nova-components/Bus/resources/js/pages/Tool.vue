<template>
  <div>
    <Head title="Bus" />

    <div class="mb-6 flex items-center justify-between gap-4">
      <Heading>Bus</Heading>

      <div class="flex items-center gap-2">
        <Link href="/bus/orders" class="btn btn-default">Open Orders Custom Page</Link>
        <Link href="/bus/vehicle-locations" class="btn btn-default">Vehicle Live Location</Link>
      </div>
      
    </div>

    <Card
      class="flex flex-col items-center justify-center"
      style="min-height: 300px"
    >
      <svg
        class="spin fill-80 mb-6"
        width="69"
        height="72"
        viewBox="0 0 23 24"
        xmlns="http://www.w3.org/2000/svg"
      >
        <path
          d="M20.12 20.455A12.184 12.184 0 0 1 11.5 24a12.18 12.18 0 0 1-9.333-4.319c4.772 3.933 11.88 3.687 16.36-.738a7.571 7.571 0 0 0 0-10.8c-3.018-2.982-7.912-2.982-10.931 0a3.245 3.245 0 0 0 0 4.628 3.342 3.342 0 0 0 4.685 0 1.114 1.114 0 0 1 1.561 0 1.082 1.082 0 0 1 0 1.543 5.57 5.57 0 0 1-7.808 0 5.408 5.408 0 0 1 0-7.714c3.881-3.834 10.174-3.834 14.055 0a9.734 9.734 0 0 1 .03 13.855zM4.472 5.057a7.571 7.571 0 0 0 0 10.8c3.018 2.982 7.912 2.982 10.931 0a3.245 3.245 0 0 0 0-4.628 3.342 3.342 0 0 0-4.685 0 1.114 1.114 0 0 1-1.561 0 1.082 1.082 0 0 1 0-1.543 5.57 5.57 0 0 1 7.808 0 5.408 5.408 0 0 1 0 7.714c-3.881 3.834-10.174 3.834-14.055 0a9.734 9.734 0 0 1-.015-13.87C5.096 1.35 8.138 0 11.5 0c3.75 0 7.105 1.68 9.333 4.319C16.06.386 8.953.632 4.473 5.057z"
          fill-rule="evenodd"
        />
      </svg>

      <h1 class="dark:text-white text-3xl font-light mb-6">
        Bus tool custom pages example
      </h1>

      <p class="dark:text-white text-base opacity-70 mb-4">
        Use this starter page to navigate to a custom Orders page and learn the full flow.
      </p>

      <p class="dark:text-white text-base opacity-70">
        You can edit this page at:
        <code
          class="ml-1 border border-gray-100 dark:border-gray-900 text-sm font-mono text-white bg-black rounded px-2 py-1"
        >
          /nova-components/Bus/resources/js/pages/Tool.vue
        </code>
      </p>

      <p class="dark:text-white text-base opacity-70 mt-4">
        Orders page source:
        <code
          class="ml-1 border border-gray-100 dark:border-gray-900 text-sm font-mono text-white bg-black rounded px-2 py-1"
        >
          /nova-components/Bus/resources/js/pages/Orders.vue
        </code>
      </p>
    </Card>
  </div>
</template>

<script>
export default {
  mounted() {
    //
  },
}
</script>

<style>
/* Scoped Styles */
</style>
