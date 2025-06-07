import axios from 'axios';

export default {
  data() {
    return {
      questions: [],
      currentQuestionIndex: 0,
      userAnswers: [],
      startTime: null,
      endTime: null,
    };
  },
  methods: {
    fetchQuestions() {
      const amount = 10;
      const category = 9; // General Knowledge
      const difficulty = 'medium';
      const type = 'multiple';
      const url = `https://opentdb.com/api.php?amount=${amount}&category=${category}&difficulty=${difficulty}&type=${type}&encode=base64`;

      axios.get(url)
        .then(response => {
          this.questions = response.data.results.map(q => ({
            question: atob(q.question),
            correct_answer: atob(q.correct_answer),
            incorrect_answers: q.incorrect_answers.map(ans => atob(ans)),
          }));
          this.startTime = new Date();
        })
        .catch(error => {
          console.error(error);
        });
    },
    submitAnswer(answer) {
      this.userAnswers.push({
        question: this.questions[this.currentQuestionIndex].question,
        selected: answer,
        correct: this.questions[this.currentQuestionIndex].correct_answer,
        isCorrect: answer === this.questions[this.currentQuestionIndex].correct_answer,
        timeTaken: (new Date() - this.startTime) / 1000,
      });
      this.currentQuestionIndex++;
      this.startTime = new Date();
    },
  },
  mounted() {
    this.fetchQuestions();
  },
};
