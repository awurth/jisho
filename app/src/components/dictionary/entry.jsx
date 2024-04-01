import Tag from './tag.jsx';

export default function Entry({entry}) {
  return (
    <div className="border-2 border-transparent hover:border-gray-50 rounded-md px-3 py-2 cursor-text">
      <div className="flex mb-1">
        <div className="left mr-3">
          <div className="text-xl font-bold">{entry.japanese.kana}</div>
          <div className="text-xs text-gray-600">{entry.japanese.romaji}</div>
        </div>
        <div className="right">
          {entry.meanings.join(', ')}
        </div>
      </div>
      <div className="flex flex-wrap">
        {entry.tags.map((tag) => (
          <Tag key={tag} name={tag} className="mr-1 mb-1"/>
        ))}
      </div>
    </div>
  );
}
