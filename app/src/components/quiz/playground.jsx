import { useMutation } from "@tanstack/react-query";
import clsx from "clsx";
import { useEffect, useRef, useState } from "react";
import { patchQuestion, postQuestion } from "../../api/quiz.js";
import { useQuizStore } from "../../stores/quiz.js";
import Button from "../button.jsx";
import Input from "../forms/input.jsx";
import Question from "./question.jsx";

export default function Playground({ quiz }) {
  const answerInputRef = useRef(null);
  const currentQuestion = useQuizStore((state) => state.currentQuestion);
  const setCurrentQuestion = useQuizStore((state) => state.setCurrentQuestion);
  const [answer, setAnswer] = useState("");
  const [correctAnswer, setCorrectAnswer] = useState("");
  const [skipped, setSkipped] = useState(false);
  const [wrong, setWrong] = useState(false);

  const postQuestionMutation = useMutation({
    mutationFn: () => postQuestion(quiz.id),
    onSuccess: (data) => {
      setCurrentQuestion(data);
    },
  });

  const patchQuestionMutation = useMutation({
    mutationFn: (payload) =>
      patchQuestion(quiz.id, currentQuestion.id, payload),
    onSuccess: (data, payload) => {
      if (payload.skipped) {
        setSkipped(true);
      }
      setCorrectAnswer(
        payload.skipped ? data.card.entry.senses[0].translations[0].value : "",
      );
      postQuestionMutation.mutate();
    },
    onError: () => {
      setWrong(true);
    },
  });

  useEffect(() => {
    postQuestionMutation.mutate();
  }, []);

  useEffect(() => {
    let timeoutId;
    if (skipped) {
      timeoutId = setTimeout(() => setSkipped(false), 1000);
    }

    answerInputRef.current?.focus();

    return () => clearTimeout(timeoutId);
  }, [skipped]);

  useEffect(() => {
    let timeoutId;
    if (wrong) {
      timeoutId = setTimeout(() => setWrong(false), 500);
    }

    return () => clearTimeout(timeoutId);
  }, [wrong]);

  if (!currentQuestion) {
    return null;
  }

  const onKeyUp = (e) => {
    if (e.code !== "Enter") {
      return;
    }

    patchQuestionMutation.mutate({ answer: e.target.value });
    setAnswer("");
  };

  const onSkipButtonClick = () => {
    setAnswer("");
    patchQuestionMutation.mutate({ skipped: true });
  };

  return (
    <>
      {!skipped && <Question question={currentQuestion} />}
      <div className={clsx({ hidden: skipped })}>
        <Input
          ref={answerInputRef}
          className={clsx(
            "px-5 block w-full h-20 text-2xl text-center mb-3 placeholder:text-gray-300 shadow-sm",
            { shake: wrong },
          )}
          placeholder="Answer"
          value={answer}
          onChange={(e) => setAnswer(e.target.value)}
          onKeyUp={onKeyUp}
          autoFocus
        />
        <div className="flex justify-center">
          <Button size="large" onClick={onSkipButtonClick}>
            Skip question
          </Button>
        </div>
      </div>
      {skipped && <p className="mt-56 text-4xl text-center">{correctAnswer}</p>}
    </>
  );
}
