<!--
  BarChart.vue — Renders a Chart.js Bar chart.

  Same pattern as DoughnutChart:
  1. Receive data via props
  2. Render Chart.js on a <canvas> in mounted()
  3. Watch for data changes and re-render

  The bar chart shows monthly order counts — great for spotting trends.
-->
<template>
  <div>
    <canvas ref="chart"></canvas>
  </div>
</template>

<script>
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js'

// Register bar chart components
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend)

export default {
  props: {
    // Expected format:
    // { labels: ['Jan', 'Feb', 'Mar'], values: [20, 35, 15] }
    chartData: {
      type: Object,
      default: () => ({ labels: [], values: [] })
    }
  },

  data() {
    return {
      chartInstance: null
    }
  },

  mounted() {
    this.renderChart()
  },

  watch: {
    chartData: {
      handler() {
        this.renderChart()
      },
      deep: true
    }
  },

  methods: {
    renderChart() {
      if (this.chartInstance) {
        this.chartInstance.destroy()
      }

      const ctx = this.$refs.chart.getContext('2d')

      this.chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: this.chartData.labels,
          datasets: [{
            label: 'Orders',
            data: this.chartData.values,
            backgroundColor: '#6366F1',  // Indigo — matches Nova's theme
            borderRadius: 4,              // Rounded bar corners
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false  // Hide legend for single-dataset charts
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              ticks: {
                // Only show whole numbers on y-axis
                stepSize: 1
              }
            }
          }
        }
      })
    }
  },

  beforeUnmount() {
    if (this.chartInstance) {
      this.chartInstance.destroy()
    }
  }
}
</script>
