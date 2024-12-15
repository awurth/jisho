import axios from "axios";
import clsx from "clsx";
import { useEffect, useRef, useState } from "react";
import { useSearchParams } from "react-router";
import Button from "../components/button.jsx";
import Input from "../components/forms/input.jsx";
import Timer from "../components/quiz/timer.jsx";
import { useDeckStore } from "../stores/deck.js";

function shuffle(array) {
  let currentIndex = array.length;

  while (currentIndex !== 0) {
    let randomIndex = Math.floor(Math.random() * currentIndex);
    currentIndex--;

    [array[currentIndex], array[randomIndex]] = [
      array[randomIndex],
      array[currentIndex],
    ];
  }
}

export default function Quiz() {
  const [searchParams] = useSearchParams();
  const answerRef = useRef(null);
  const deck = useDeckStore((state) => state.activeDeck);
  const [entries, setEntries] = useState([]);
  const [currentEntryIndex, setCurrentEntryIndex] = useState(null);
  const [points, setPoints] = useState(0);
  const [answer, setAnswer] = useState("");
  const [skipped, setSkipped] = useState(false);

  const tags = searchParams.get("tags")?.split(",").filter(Boolean);

  useEffect(() => {
    axios.get(`/api/decks/${deck.id}/cards`).then(({ data }) => {
      if (data.length === 0) {
        return;
      }

      const cards =
        tags.length > 0
          ? data.filter((card) => tags.some((tag) => card.tags.includes(tag)))
          : data;
      shuffle(cards);
      setCurrentEntryIndex(0);
      setEntries(cards);
    });
  }, []);

  useEffect(() => {
    let timeoutId;
    if (skipped) {
      timeoutId = setTimeout(skip, 500);
    }

    answerRef.current?.focus();

    return () => clearTimeout(timeoutId);
  }, [skipped]);

  const onKeyUp = (e) => {
    if (e.code !== "Enter") {
      return;
    }

    const entry = entries[currentEntryIndex];

    const french = entry.french.map((string) =>
      string
        .toLowerCase()
        .normalize("NFD")
        .replace(/\p{Diacritic}/gu, ""),
    );
    const japanese = e.target.value
      .toLowerCase()
      .normalize("NFD")
      .replace(/\p{Diacritic}/gu, "");

    if (!french.includes(japanese)) {
      return;
    }

    setPoints(points + 1);
    setCurrentEntryIndex(currentEntryIndex + 1);
    setAnswer("");
  };

  const skip = () => {
    setSkipped(false);
    setCurrentEntryIndex(currentEntryIndex + 1);
    setAnswer("");
  };

  return (
    <>
      <h1 className="text-xl text-white font-semibold mb-2">
        Quiz {!!tags.length && `"${tags.join(", ")}"`}
      </h1>
      {!!entries.length && (
        <div className="flex flex-col items-center">
          <span className="font-bold text-white">
            {Math.min(currentEntryIndex + 1, entries.length)}/{entries.length}
          </span>
          <Timer
            className="font-bold text-2xl text-white"
            running={currentEntryIndex !== entries.length}
          />
        </div>
      )}
      {!!entries.length && currentEntryIndex === entries.length && (
        <p className="grow flex justify-center items-center font-bold text-4xl text-white mb-32">
          Terminé ! {points} points / {entries.length}
        </p>
      )}
      {!!entries.length && currentEntryIndex !== entries.length && (
        <div className="grow flex flex-col">
          <div className="flex items-center justify-center p-5">
            <p className="text-4xl text-white my-16">
              {entries[currentEntryIndex]?.japanese}
            </p>
          </div>
          <div className="flex items-center p-5">
            <div className={clsx("my-16 grow flex", { hidden: skipped })}>
              <Input
                ref={answerRef}
                className="px-5 w-full h-20 text-4xl mr-1"
                value={answer}
                onChange={(e) => setAnswer(e.target.value)}
                onKeyUp={onKeyUp}
                autoFocus
              />
              <Button className="px-5" onClick={() => setSkipped(true)}>
                Passer
              </Button>
            </div>
            {skipped && (
              <div className="mb-32 grow flex">
                <p className="text-4xl text-white">
                  {entries[currentEntryIndex]?.french}
                </p>
              </div>
            )}
          </div>
        </div>
      )}
    </>
  );
}
