<template>
  <div class="bg-white p-6 rounded-xl shadow-lg max-w-xl mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">🏆 Leaderboard</h2>
    <ul class="space-y-4">
      <li v-for="entry in leaderboard" :key="entry.id" class="p-4 bg-gray-100 rounded-xl">
        <div class="flex justify-between">
          <span class="font-semibold text-gray-800">{{ entry.name }}</span>
          <span class="font-bold text-lg text-blue-600">{{ entry.score }}</span>
        </div>
      </li>
    </ul>
  </div>
</template>

<script>
import axios from '@/plugins/axios'

export default {
  data() {
    return {
      leaderboard: []
    }
  },
  async mounted() {
    try {
      const res = await axios.get('/leaderboard')
      this.leaderboard = res.data
    } catch (error) {
      console.error('Failed to load leaderboard:', error)
    }
  }
}
</script>
