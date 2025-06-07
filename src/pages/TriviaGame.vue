<template>
  <div>
    <h1>Trivia Game</h1>
    <div v-if="question">
      <p>{{ question.question }}</p>
      <div v-for="(answer, index) in allAnswers" :key="index">
        <button @click="selectAnswer(answer)">{{ answer }}</button>
      </div>
    </div>
    <div v-else>
      <p>Loading...</p>
    </div>
    <p v-if="showResult">You answered correctly: {{ isCorrect ? 'Yes' : 'No' }}</p>
  </div>
</template>

<script lang="ts" setup>
import { ref, computed, onMounted } from "vue";
import { fetchQuestions, TriviaQuestion } from "@/services/api";

const questions = ref<TriviaQuestion[]>([]);
const currentIndex = ref(0);
const selectedAnswer = ref("");
const showResult = ref(false);

onMounted(async () => {
  questions.value = await fetchQuestions(1); // 1 soal dulu
});

const question = computed(() => questions.value[currentIndex.value]);
const allAnswers = computed(() => {
  if (!question.value) return [];
  return shuffle([question.value.correct_answer, ...question.value.incorrect_answers]);
});

function shuffle(array: string[]) {
  return array.sort(() => Math.random() - 0.5);
}

const isCorrect = computed(() => selectedAnswer.value === question.value?.correct_answer);

function selectAnswer(answer: string) {
  selectedAnswer.value = answer;
  showResult.value = true;
  saveScore(isCorrect.value ? 1 : 0);
}

async function saveScore(score: number) {
  await fetch(import.meta.env.VITE_API_URL + "/score", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ score }),
  });
}
</script>
