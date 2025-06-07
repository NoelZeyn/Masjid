import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import TriviaGame from '@/pages/TriviaGame.vue'

const routes = [
  { path: '/', component: TriviaGame },
]
export const router = createRouter({
  history: createWebHistory(),
  routes,
})
