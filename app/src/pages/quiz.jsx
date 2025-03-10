import { useQuery } from "@tanstack/react-query";
import { useEffect, useState } from "react";
import { useParams } from "react-router";
import { getQuiz } from "../api/quiz.js";
import Button from "../components/button.jsx";
import PageContainer from "../components/page-container.jsx";
import Playground from "../components/quiz/playground.jsx";
import Timer from "../components/quiz/timer.jsx";
import { useQuizStore } from "../stores/quiz.js";

export default function Quiz() {
  const { id } = useParams();
  const [started, setStarted] = useState(false);
  const currentQuestion = useQuizStore((state) => state.currentQuestion);

  const {
    isPending,
    isError,
    data: quiz = {},
  } = useQuery({
    queryKey: ["quiz", id],
    queryFn: () => getQuiz(id),
  });

  useEffect(() => {
    if (quiz?.startedAt) {
      setStarted(true);
    }
  }, [quiz]);

  if (isPending) {
    return <></>;
  }

  if (isError) {
    return (
      <PageContainer className="flex flex-col">
        <p>The quiz does not exist</p>
      </PageContainer>
    );
  }

  return (
    <PageContainer className="flex flex-col">
      <h1 className="text-xl text-gray-950 font-semibold mb-2">Quiz</h1>
      {!started && (
        <div className="grow flex items-center justify-center p-5">
          <Button size="large" onClick={() => setStarted(true)}>
            Start quiz
          </Button>
        </div>
      )}
      {started && currentQuestion && (
        <div className="flex flex-col items-center">
          <span className="font-bold">
            {Math.min(currentQuestion.position + 1, quiz.numberOfQuestions)}/
            {quiz.numberOfQuestions}
          </span>
          <Timer
            className="font-bold text-2xl"
            running={currentQuestion.position + 1 !== quiz.numberOfQuestions}
            startDate={new Date(quiz.startedAt)}
          />
        </div>
      )}
      {started &&
        currentQuestion &&
        currentQuestion.position + 1 === quiz.numberOfQuestions && (
          <p className="grow flex justify-center items-center font-bold text-4xl mb-32">
            Terminé ! 1 points / {quiz.numberOfQuestions}
          </p>
        )}
      {started && <Playground quiz={quiz} />}
    </PageContainer>
  );
}
