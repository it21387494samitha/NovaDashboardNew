<!--
  DoughnutChart.vue — Renders a Chart.js Doughnut (pie) chart.

  HOW VUE COMPONENTS WORK:
    - <template> = HTML structure (the UI)
    - <script>   = JavaScript logic (data, methods, lifecycle hooks)
    - <style>    = CSS for this component only (if scoped)

  HOW CHART.JS WORKS:
    1. You create a <canvas> element in the template
    2. In mounted(), you get a reference to that canvas (this.$refs.chart)
    3. You create a new Chart() instance, passing the canvas + config
    4. Chart.js renders onto the canvas

  PROPS:
    Props are data passed DOWN from the parent component.
    <DoughnutChart :chartData="myData" /> ← parent passes data
    props: ['chartData']                  ← child receives it

  WATCHERS:
    watch: { chartData() { ... } } runs whenever chartData changes,
    so we can re-render the chart when new data arrives from the API.
-->
<template>
  <div>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Chart, DoughnutController, ArcElement, Tooltip, Legend } from 'chart.js'

// Register only the components we need (tree-shaking for smaller bundle)
Chart.register(DoughnutController, ArcElement, Tooltip, Legend)

export default {
  props: {
    // Data passed from parent. Expected format:
    // { labels: ['Paid', 'Pending', 'Failed'], values: [85, 25, 15], colors: [...] }
    chartData: {
      type: Object,
      default: () => ({ labels: [], values: [], colors: [] })
    }
  },

  data() {
    return {
      chartInstance: null  // Store the Chart.js instance so we can destroy/update it
    }
  },

  mounted() {
    // mounted() runs after the component's HTML is rendered in the DOM
    this.renderChart()
  },

  watch: {
    // Re-render when parent sends new data
    chartData: {
      handler() {
        this.renderChart()
      },
      deep: true  // Watch nested properties too
    }
  },

  methods: {
    renderChart() {
      // Destroy old chart if it exists (prevents canvas reuse errors)
      if (this.chartInstance) {
        this.chartInstance.destroy()
      }

      const ctx = this.$refs.chart.getContext('2d')

      this.chartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: this.chartData.labels,
          datasets: [{
            data: this.chartData.values,
            backgroundColor: this.chartData.colors || ['#10B981', '#F59E0B', '#EF4444'],
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'bottom',
            }
          }
        }
      })
    }
  },

  // Cleanup when component is removed from DOM
  beforeUnmount() {
    if (this.chartInstance) {
      this.chartInstance.destroy()
    }
  }
}
</script>
