import { useQuery } from "@tanstack/react-query";
import { useState } from "react";
import { useNavigate } from "react-router";
import { getTags } from "../api/deck.js";
import Button from "../components/button.jsx";
import Tags from "../components/forms/tags.jsx";
import { useDeckStore } from "../stores/deck.js";

export default function NewQuiz() {
  const navigate = useNavigate();
  // const deck = useDeckStore((state) => state.activeDeck);
  // const [tags, setTags] = useState([]);
  //
  // const { data: existingTags = [] } = useQuery({
  //   queryKey: ["tags", deck.id],
  //   queryFn: () => getTags(deck.id),
  // });

  const onPlayClick = () => {
    const params = {
      // tags: tags.map((tag) => tag.value).join(","),
    };
    navigate({
      pathname: "/quiz",
      search: new URLSearchParams(params).toString(),
    });
  };

  return (
    <>
      <h1 className="text-xl text-white font-semibold mb-2">New quiz</h1>
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
    </>
  );
}
