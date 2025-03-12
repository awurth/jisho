import { useQuery } from "@tanstack/react-query";
import { useEffect, useState } from "react";
import { useParams } from "react-router";
import { getQuiz } from "../api/quiz.js";
import Button from "../components/button.jsx";
import PageContainer from "../components/page-container.jsx";
import Playground from "../components/quiz/playground.jsx";

export default function Quiz() {
  const { id } = useParams();
  const [started, setStarted] = useState(false);
  const [ended, setEnded] = useState(false);

  const {
    isPending,
    isError,
    data: quiz = {},
    refetch,
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

  const onFinish = () => {
    setStarted(false);
    setEnded(true);
    refetch();
  };

  return (
    <PageContainer className="flex flex-col">
      <h1 className="text-xl text-center font-semibold mb-3">Quiz</h1>
      {!started && !ended && (
        <div className="grow flex items-center justify-center p-5">
          <Button size="large" onClick={() => setStarted(true)}>
            Start quiz
          </Button>
        </div>
      )}
      {ended && (
        <div className="grow flex flex-col justify-center items-center font-bold text-4xl">
          <p className="mb-3">The quiz is over!</p>
          <p>
            {quiz.score} / {quiz.numberOfQuestions}
          </p>
        </div>
      )}
      {started && !ended && <Playground quiz={quiz} onFinish={onFinish} />}
    </PageContainer>
  );
}
