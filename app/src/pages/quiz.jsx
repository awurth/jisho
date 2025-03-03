import { useQuery } from "@tanstack/react-query";
import { useState } from "react";
import { useParams } from "react-router";
import { getQuiz } from "../api/quiz.js";
import Button from "../components/button.jsx";
import PageContainer from "../components/page-container.jsx";
import Playground from "../components/quiz/playground.jsx";

export default function Quiz() {
  const { id } = useParams();
  const [started, setStarted] = useState(false);

  const {
    isPending,
    isError,
    data: quiz = {},
  } = useQuery({
    queryKey: ["quiz", id],
    queryFn: () => getQuiz(id),
  });

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
      {started && <Playground quiz={quiz} />}
    </PageContainer>
  );
}
