<template>
  <div class="bg-white p-6 rounded-xl shadow-lg max-w-xl mx-auto">
    <h2 class="text-2xl font-bold mb-4 text-center text-gray-800">Results</h2>
    <div class="text-center mb-4">
      <p class="text-lg text-gray-700">Score: {{ result.score }}</p>
      <p class="text-lg text-gray-700">Average Time: {{ result.averageTime.toFixed(2) }} seconds</p>
    </div>
    <canvas id="chart" width="400" height="200"></canvas>
  </div>
</template>

<script>
import Chart from 'chart.js/auto'

export default {
  props: ['result'],
  mounted() {
    new Chart(document.getElementById('chart'), {
      type: 'pie',
      data: {
        labels: ['Correct', 'Incorrect'],
        datasets: [{
          data: [
            this.result.answers.filter(a => a).length,
            this.result.answers.filter(a => !a).length
          ],
          backgroundColor: ['#34D399', '#F87171'],
          hoverBackgroundColor: ['#48BB78', '#F56565']
        }]
      }
    })
  }
}
</script>

<style scoped>
canvas {
  max-width: 100%;
}
</style>
