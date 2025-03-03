import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useState } from "react";
import { useNavigate } from "react-router";
import { getTags, postDeck } from "../api/deck.js";
import { postQuiz } from "../api/quiz.js";
import Button from "../components/button.jsx";
import Tags from "../components/forms/tags.jsx";
import PageContainer from "../components/page-container.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function NewQuiz() {
  const navigate = useNavigate();
  const deck = useDeckStore((state) => state.activeDeck);
  // const [tags, setTags] = useState([]);
  //
  // const { data: existingTags = [] } = useQuery({
  //   queryKey: ["tags", deck.id],
  //   queryFn: () => getTags(deck.id),
  // });

  const queryClient = useQueryClient();
  const mutation = useMutation({
    mutationFn: (quiz) => postQuiz(quiz),
    onSuccess: ({ id }) => {
      queryClient.invalidateQueries({ queryKey: ["quizzes"] });
      navigate(`/quiz/${id}`);
    },
  });

  const onPlayClick = () => {
    const params = {
      // tags: tags.map((tag) => tag.value).join(","),
    };

    mutation.mutate({ deck: `/decks/${deck.id}` });
  };

  return (
    <PageContainer>
      <h1 className="text-xl font-semibold mb-2">New quiz</h1>
      {/*<label className="text-white font-semibold">Tags</label>*/}
      {/*<Tags*/}
      {/*  options={existingTags.map((tag) => tag.name)}*/}
      {/*  value={tags}*/}
      {/*  onChange={setTags}*/}
      {/*  className="mt-1 mb-2"*/}
      {/*/>*/}
      <Button onClick={onPlayClick} size="block">
        Play
      </Button>
    </PageContainer>
  );
}
