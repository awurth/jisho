import { useMutation } from "@tanstack/react-query";
import { useEffect, useState } from "react";
import { postQuestion } from "../../api/quiz.js";

export default function Playground({ quiz }) {
  const [question, setQuestion] = useState(null);
  const postQuestionMutation = useMutation({
    mutationFn: () => {
      return postQuestion(quiz.id);
    },
    onSuccess: (data) => {
      setQuestion(data);
    },
  });

  useEffect(() => {
    postQuestionMutation.mutate();
  }, []);

  return <></>;
}
