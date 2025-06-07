<template>
  <div class="min-h-screen bg-background text-gray-800">
    <MusicPlayer musicSrc="./assets/quiz.mp3" />
    <div class="container mx-auto px-4 py-8">
      <div v-if="step === 'quiz'">
        <Quiz @finished="onFinish" />
      </div>
      <div v-else-if="step === 'result'">
        <Result :result="result" />
      </div>
      <Leaderboard v-if="step === 'result'" />
    </div>
  </div>
</template>

<script>
import Quiz from './components/Quiz.vue'
import Result from './components/Result.vue'
import Leaderboard from './components/Leaderboard.vue'
import MusicPlayer from './components/MusicPlayer.vue'

export default {
  components: { Quiz, Result, Leaderboard, MusicPlayer },
  data() {
    return {
      step: 'quiz',
      result: null
    }
  },
  methods: {
    onFinish(data) {
      this.result = data
      this.step = 'result'
    }
  }
}
</script>

<style scoped>
.container {
  max-width: 800px;
}
</style>
