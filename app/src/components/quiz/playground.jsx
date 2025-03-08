import { useMutation } from "@tanstack/react-query";
import clsx from "clsx";
import { useEffect } from "react";
import { patchQuestion, postQuestion } from "../../api/quiz.js";
import { useQuizStore } from "../../stores/quiz.js";
import Button from "../button.jsx";
import Input from "../forms/input.jsx";
import Question from "./question.jsx";

export default function Playground({ quiz }) {
  const currentQuestion = useQuizStore((state) => state.currentQuestion);
  const setCurrentQuestion = useQuizStore((state) => state.setCurrentQuestion);

  const postQuestionMutation = useMutation({
    mutationFn: () => {
      return postQuestion(quiz.id);
    },
    onSuccess: (data) => {
      setCurrentQuestion(data);
    },
  });

  const patchQuestionMutation = useMutation({
    mutationFn: (payload) => {
      return patchQuestion(quiz.id, currentQuestion.id, payload);
    },
    onSuccess: (data) => {
      setCurrentQuestion(data);
    },
  });

  useEffect(() => {
    postQuestionMutation.mutate();
  }, []);

  if (!currentQuestion) {
    return null;
  }

  const onKeyUp = (e) => {
    if (e.code !== "Enter") {
      return;
    }

    patchQuestionMutation.mutate({ answer: e.target.value });
  };

  const skipped = false;

  return (
    <>
      <Question question={currentQuestion} />
      <div className={clsx({ hidden: skipped })}>
        <Input
          // ref={answerRef}
          className="px-5 block w-full h-20 text-2xl text-center mb-3 placeholder:text-gray-300 shadow-sm"
          placeholder="Answer"
          // value={answer}
          // onChange={(e) => setAnswer(e.target.value)}
          onKeyUp={onKeyUp}
          autoFocus
        />
        <div className="flex justify-center">
          <Button size="large">Skip question</Button>
        </div>
      </div>
      {skipped && (
        <div className="mb-32 grow flex">
          <p className="text-4xl">{"french"}</p>
        </div>
      )}
    </>
  );
}
