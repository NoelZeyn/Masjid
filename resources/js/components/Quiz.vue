<template>
  <div class="bg-white p-6 rounded-xl shadow-lg max-w-xl mx-auto">
    <div v-if="loading" class="text-center text-xl font-semibold text-gray-700">
      <p>Loading questions...</p>
    </div>
    <div v-else-if="questionIndex < questions.length">
      <h2 class="text-xl font-bold mb-6 text-center text-gray-800">{{ questions[questionIndex].question }}</h2>
      <ul class="space-y-4">
        <li
          v-for="answer in questions[questionIndex].answers"
          :key="answer"
          class="cursor-pointer p-4 bg-gray-100 hover:bg-green-500 hover:text-white rounded-xl transition ease-in-out duration-200"
          @click="submitAnswer(answer)"
        >
          {{ answer }}
        </li>
      </ul>
    </div>
    <div v-else>
      <input v-model="name" class="w-full p-3 mt-4 rounded-md border border-gray-300" placeholder="Enter your name" />
      <button @click="submitResult" class="w-full mt-4 bg-blue-600 text-white p-3 rounded-xl hover:bg-blue-700 transition duration-300">
        See Results
      </button>
    </div>
  </div>
</template>

<script>
import axios from '@/plugins/axios'

export default {
  data() {
    return {
      questions: [],
      questionIndex: 0,
      score: 0,
      answers: [],
      startTime: 0,
      answerTimes: [],
      loading: true,
      name: '',
    }
  },
  async mounted() {
    try {
      const res = await fetch('https://opentdb.com/api.php?amount=5&encode=base64&type=multiple')
      const data = await res.json()
      this.questions = data.results.map((q) => {
        const allAnswers = [...q.incorrect_answers.map(a => atob(a)), atob(q.correct_answer)];
        return {
          question: atob(q.question),
          correct: atob(q.correct_answer),
          answers: allAnswers.sort(() => Math.random() - 0.5),
        }
      })
      this.startTime = Date.now()
      this.loading = false
    } catch (err) {
      console.error('Failed to fetch questions:', err)
    }
  },
  methods: {
    submitAnswer(answer) {
      const endTime = Date.now()
      const timeTaken = (endTime - this.startTime) / 1000
      this.answerTimes.push(timeTaken)

      const correct = this.questions[this.questionIndex].correct
      this.answers.push(answer === correct)
      if (answer === correct) this.score++

      this.questionIndex++
      this.startTime = Date.now()
    },
    async submitResult() {
      const avgTime = this.answerTimes.reduce((a, b) => a + b, 0) / this.answerTimes.length
      try {
        await axios.post('/submit-quiz', {
          name: this.name,
          score: this.score,
          average_time: avgTime,
          answers: this.answers
        })
        this.$emit('finished', {
          score: this.score,
          averageTime: avgTime,
          answers: this.answers
        })
      } catch (error) {
        console.error('Submit failed:', error)
      }
    }
  }
}
</script>
