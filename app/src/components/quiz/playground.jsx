import { useMutation } from "@tanstack/react-query";
import clsx from "clsx";
import { useEffect, useRef, useState } from "react";
import { patchQuestion, postQuestion } from "../../api/quiz.js";
import Button from "../button.jsx";
import Input from "../forms/input.jsx";
import Question from "./question.jsx";
import Timer from "./timer.jsx";

export default function Playground({ quiz, onFinish }) {
  const answerInputRef = useRef(null);
  const [currentQuestion, setCurrentQuestion] = useState(null);
  const [answer, setAnswer] = useState("");
  const [correctAnswer, setCorrectAnswer] = useState("");
  const [wrong, setWrong] = useState(false);

  const postQuestionMutation = useMutation({
    mutationFn: () => postQuestion(quiz.id),
    onSuccess: (data) => {
      setCurrentQuestion(data);
    },
    onError: () => {
      onFinish();
    },
  });

  const patchQuestionMutation = useMutation({
    mutationFn: (payload) =>
      patchQuestion(quiz.id, currentQuestion.id, payload),
    onSuccess: (data, payload) => {
      setCorrectAnswer(
        payload.skipped ? data.card.entry.senses[0].translations[0].value : "",
      );
      postQuestionMutation.mutate();
    },
    onError: () => {
      setWrong(true);
    },
    onSettled: () => {
      setAnswer("");
    },
  });

  const fetchingQuestion = postQuestionMutation.isPending;
  const pending =
    postQuestionMutation.isPending || patchQuestionMutation.isPending;

  useEffect(() => {
    postQuestionMutation.mutate();
  }, []);

  useEffect(() => {
    let timeoutId;
    if (correctAnswer) {
      timeoutId = setTimeout(() => {
        setCorrectAnswer("");
      }, 1000);
    }

    answerInputRef.current?.focus();

    return () => clearTimeout(timeoutId);
  }, [correctAnswer]);

  useEffect(() => {
    let timeoutId;
    if (wrong) {
      timeoutId = setTimeout(() => setWrong(false), 500);
    }

    answerInputRef.current?.focus();

    return () => clearTimeout(timeoutId);
  }, [wrong]);

  if (!currentQuestion) {
    return null;
  }

  const onAnswerInputKeyUp = (e) => {
    if (e.code !== "Enter") {
      return;
    }

    submitAnswer(e.target.value);
  };

  const onSkipButtonClick = () => {
    setAnswer("");
    patchQuestionMutation.mutate({ skipped: true });
  };

  const submitAnswer = (answer) => {
    patchQuestionMutation.mutate({ answer });
  };

  return (
    <>
      <div className="flex justify-between items-center px-3">
        <span className="font-bold">
          Question{" "}
          {Math.min(currentQuestion.position + 1, quiz.numberOfQuestions)}/
          {quiz.numberOfQuestions}
        </span>
        <Timer
          className="font-bold"
          running={true}
          startDate={new Date(quiz.startedAt)}
        />
      </div>
      {!correctAnswer && !fetchingQuestion && (
        <>
          <Question question={currentQuestion} />
          <Input
            ref={answerInputRef}
            className={clsx(
              "px-5 block w-full h-20 text-2xl text-center mb-3 placeholder:text-gray-300 shadow-xs",
              { shake: wrong },
            )}
            placeholder="Answer"
            value={answer}
            onChange={(e) => setAnswer(e.target.value)}
            onKeyUp={onAnswerInputKeyUp}
            disabled={pending}
            autoFocus
          />
          <div className="flex flex-col items-center">
            <Button
              className="mb-3"
              onClick={(e) => submitAnswer(e.target.value)}
              disabled={pending}
            >
              Submit answer
            </Button>
            <Button
              intent="secondary"
              size="small"
              onClick={onSkipButtonClick}
              disabled={pending}
            >
              Skip question
            </Button>
          </div>
        </>
      )}
      {!!correctAnswer && (
        <p className="mt-56 text-4xl text-center">{correctAnswer}</p>
      )}
      {!correctAnswer && fetchingQuestion && (
        <p className="mt-56 text-xl text-center text-gray-600">
          Loading next question...
        </p>
      )}
    </>
  );
}
