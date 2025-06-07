export const fetchQuestions = async (
  amount: number = 5,
  category: number = 18,
  difficulty: string = "medium"
): Promise<TriviaQuestion[]> => {
  const response = await fetch(
    `https://opentdb.com/api.php?amount=${amount}&category=${category}&difficulty=${difficulty}&type=multiple&encode=base64`
  );
  const data: ApiResponse = await response.json();

  return data.results.map((item) => ({
    question: atob(item.question),
    correct_answer: atob(item.correct_answer),
    incorrect_answers: item.incorrect_answers.map((ans) => atob(ans)),
  }));
};

export const sendScore = async (username: string, score: number) => {
  await fetch(import.meta.env.VITE_API_URL + "/api/score", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ username, score }),
  });
};

export type TriviaQuestion = {
  question: string;
  correct_answer: string;
  incorrect_answers: string[];
};

export type ApiResponse = {
  results: {
    question: string;
    correct_answer: string;
    incorrect_answers: string[];
  }[];
};
