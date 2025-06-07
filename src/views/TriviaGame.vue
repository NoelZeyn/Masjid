<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { fetchQuestions } from '../composables/useQuestions'
import type { TriviaQuestion } from '../composables/useQuestions'

const questions = ref<TriviaQuestion[]>([])
const current = ref(0)
const selected = ref('')
const score = ref(0)
const name = ref('')

onMounted(async () => {
  questions.value = await fetchQuestions(5)
})

const nextQuestion = () => {
  if (selected.value === questions.value[current.value].correct_answer) {
    score.value++
  }
  selected.value = ''
  current.value++
}

const submitScore = async () => {
  const baseUrl = import.meta.env.VITE_API_BASE_URL
  await fetch(`${baseUrl}/score`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ name: name.value, score: score.value }),
  })
  alert('Skor tersimpan!')
}
</script>

<template>
  <div v-if="questions.length && current < questions.length">
    <h2>{{ questions[current].question }}</h2>
    <div v-for="ans in [...questions[current].incorrect_answers, questions[current].correct_answer]" :key="ans">
      <label>
        <input type="radio" :value="ans" v-model="selected" />
        {{ ans }}
      </label>
    </div>
    <button @click="nextQuestion" :disabled="!selected">Next</button>
  </div>

  <div v-else>
    <h2>Game selesai! Skor kamu: {{ score }}</h2>
    <input v-model="name" placeholder="Nama kamu" />
    <button @click="submitScore" :disabled="!name">Simpan Skor</button>
  </div>
</template>
