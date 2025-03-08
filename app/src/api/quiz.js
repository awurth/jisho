import httpClient from "./http-client.js";

export async function getQuizzes() {
  const { data } = await httpClient.get("/quizzes");
  return data;
}

export async function getQuiz(id) {
  const { data } = await httpClient.get(`/quizzes/${id}`);
  return data;
}

export async function postQuiz(quiz) {
  const { data } = await httpClient.post(`/quizzes`, quiz);
  return data;
}

export async function postQuestion(quizId) {
  const { data } = await httpClient.post(`/quizzes/${quizId}/questions`, {});
  return data;
}

export async function patchQuestion(quizId, questionId, payload) {
  const { data } = await httpClient.patch(
    `/quizzes/${quizId}/questions/${questionId}`,
    payload,
  );
  return data;
}
