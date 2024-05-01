import { faArrowsUpDown } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import clsx from "clsx";
import { useEffect, useRef, useState } from "react";
import { bind, isKana } from "wanakana";
import { postEntry } from "../../api/dictionary.js";
import { useDictionaryStore } from "../../stores/dictionary.js";
import Button from "../button.jsx";
import Input from "../forms/input.jsx";
import existingTags from "../../data/tags.json";
import Tags from "../forms/tags.jsx";
import Textarea from "../forms/textarea.jsx";

export default function AddEntry({ onAdd, ...props }) {
  const dictionary = useDictionaryStore((state) => state.activeDictionary);
  const japaneseRef = useRef(null);
  const [french, setFrench] = useState("");
  const [tags, setTags] = useState([]);
  const [notes, setNotes] = useState("");

  const [japaneseError, setJapaneseError] = useState(null);
  const [frenchError, setFrenchError] = useState(null);

  const queryClient = useQueryClient();
  const mutation = useMutation({
    mutationFn: (data) => postEntry(dictionary.id, data),
    onSuccess: () => {
      japaneseRef.current.value = "";
      setJapaneseError(null);

      setFrench("");
      setFrenchError(null);

      setTags([]);
      setNotes("");

      queryClient.invalidateQueries({ queryKey: ["entries"] });
    },
  });

  const onKeyUp = (e) => {
    if (e.code === "Enter" && !e.shiftKey) {
      validate() && submit();
    }
  };

  const validateJapanese = () => {
    const japanese = japaneseRef.current.value;
    if (japanese.length === 0 || !isKana(japanese)) {
      setJapaneseError("Seuls les hiragana et les katakana sont acceptés");
      return false;
    }

    setJapaneseError(null);
    return true;
  };

  const validateFrench = () => {
    if (french.length === 0) {
      setFrenchError("Veuillez renseigner au moins un mot");
      return false;
    }

    setFrenchError(null);
    return true;
  };

  const validate = () => {
    const validJapanese = validateJapanese();
    const validFrench = validateFrench();

    return validJapanese && validFrench;
  };

  useEffect(() => {
    bind(japaneseRef.current);
    japaneseRef.current.focus();
  }, []);

  const submit = () => {
    const kana = japaneseRef.current.value;

    mutation.mutate({
      japanese: kana,
      french: french.split(", "),
      tags: tags.map((tag) => tag.value),
    });
  };

  return (
    <div
      {...props}
      className={clsx(
        "border-2 border-b-4 border-dark-900 p-4 rounded-xl flex flex-col",
        props.className ?? "",
      )}
    >
      <p className="mb-3 text-gray-300 text-sm font-semibold">Nouveau mot</p>
      <Input
        type="text"
        placeholder="gohan"
        className="w-full mb-4"
        ref={japaneseRef}
        onKeyUp={onKeyUp}
        error={japaneseError}
      />
      <FontAwesomeIcon icon={faArrowsUpDown} className="text-gray-400 mb-4" />
      <Input
        type="text"
        placeholder="riz, repas"
        className="w-full mb-4"
        value={french}
        onKeyUp={onKeyUp}
        onChange={(e) => setFrench(e.target.value)}
        error={frenchError}
      />
      <Tags
        options={existingTags}
        value={tags}
        onChange={setTags}
        className="mb-4"
      />
      <Textarea
        className="w-full mb-4"
        placeholder="Notes"
        onChange={(e) => setNotes(e.target.value)}
        onKeyUp={onKeyUp}
        value={notes}
      />
      <Button onClick={submit} className="py-2 w-full">
        Ajouter
      </Button>
    </div>
  );
}
