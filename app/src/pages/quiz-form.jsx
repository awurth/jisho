import { useQuery } from "@tanstack/react-query";
import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { getTags } from "../api/dictionary.js";
import Button from "../components/button.jsx";
import Tags from "../components/forms/tags.jsx";
import { useDictionaryStore } from "../stores/dictionary.js";

export default function QuizForm() {
  const navigate = useNavigate();
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const [tags, setTags] = useState([]);

  const { data: existingTags = [] } = useQuery({
    queryKey: ["tags", dictionary.id],
    queryFn: () => getTags(dictionary.id),
  });

  const onPlayClick = () => {
    const params = {
      tags: tags.map((tag) => tag.value).join(","),
    };
    navigate({
      pathname: "/quiz",
      search: new URLSearchParams(params).toString(),
    });
  };

  return (
    <>
      <h1 className="text-xl text-white font-semibold mb-2">Nouveau quiz</h1>
      <label className="text-white font-semibold">Tags</label>
      <Tags
        options={existingTags.map((tag) => tag.name)}
        value={tags}
        onChange={setTags}
        className="mt-1 mb-2"
      />
      <Button onClick={onPlayClick} className="py-2 w-full">
        Jouer
      </Button>
    </>
  );
}
