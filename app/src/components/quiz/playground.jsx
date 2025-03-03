import { useMutation } from "@tanstack/react-query";
import { postQuestion } from "../../api/quiz.js";
import Button from "../button.jsx";

export default function Playground({ quiz }) {
  const mutation = useMutation({
    mutationFn: () => {
      return postQuestion(quiz.id);
    },
    onSuccess: (data) => {
      console.log(data);
    },
  });

  return (
    <>
      <Button onClick={() => mutation.mutate()}>New question</Button>
    </>
  );
}
