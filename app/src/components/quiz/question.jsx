export default function Question({ question }) {
  return (
    <div className="flex items-center justify-center p-5">
      <p className="text-3xl my-16 break-words">
        {question.card.entry.readings[0].kana}
      </p>
    </div>
  );
}
