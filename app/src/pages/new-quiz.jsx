import {
  Field,
  Fieldset,
  Label,
  Legend,
  Radio,
  RadioGroup,
} from "@headlessui/react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useState } from "react";
import { useNavigate } from "react-router";
import { getTags, postDeck } from "../api/deck.js";
import { postQuiz } from "../api/quiz.js";
import Button from "../components/button.jsx";
import Input from "../components/forms/input.jsx";
import Tags from "../components/forms/tags.jsx";
import PageContainer from "../components/page-container.jsx";
import { useDeckStore } from "../stores/deck.js";

const choices = [
  { value: "japanese", label: "English to Japanese" },
  { value: "english", label: "Japanese to English" },
];

export default function NewQuiz() {
  const navigate = useNavigate();
  const deck = useDeckStore((state) => state.activeDeck);
  const [maxQuestions, setMaxQuestions] = useState(100);
  const [selected, setSelected] = useState("Startup");
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

    mutation.mutate({
      deck: `/decks/${deck.id}`,
      maxQuestions: parseInt(maxQuestions),
    });
  };

  return (
    <PageContainer>
      <h1 className="text-xl font-semibold mb-3">New quiz</h1>
      <div className="flex flex-col mb-3">
        <label className="font-semibold mb-2 text-sm">
          Number of questions (max)
        </label>
        <Input
          type="number"
          value={maxQuestions}
          onChange={(e) => setMaxQuestions(e.target.value)}
        />
      </div>
      <Fieldset className="mb-3">
        <Legend className="font-semibold mb-2 text-sm">Translate</Legend>
        <RadioGroup
          value={selected}
          onChange={setSelected}
          aria-label="Server size"
        >
          {choices.map(({ value, label }) => (
            <Field key={value}>
              <Radio value={value} className="group block">
                <Label className="block bg-gray-200/50 hover:bg-gray-200 mb-2 px-3 py-2 rounded-full text-sm text-gray-500 group-data-[checked]:bg-gray-300/75 group-data-[checked]:hover:bg-gray-300/75">
                  {label}
                </Label>
              </Radio>
            </Field>
          ))}
        </RadioGroup>
      </Fieldset>
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
