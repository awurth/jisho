// const filterTranslations = (translations) => {
//   const french = translations.filter((translation) => translation.language === "fre");
//
//   return !!french.length ? french : translations.filter((translation) => translation.language === "eng");
// };

// const filterSenses = (senses) => {
//   const french = senses.map((sense) => ({
//     ...sense,
//     translations: sense.translations.filter((translation) => translation.language === "fre"),
//   })).filter((sense) => !!sense.translations.length);
//
//   return !!french.length ? french : senses.map((sense) => ({
//     ...sense,
//     translations: sense.translations.filter((translation) => translation.language === "eng"),
//   })).filter((sense) => !!sense.translations.length);
// };

export default function SearchResult({entry}) {
  const main = entry.kanji[0]?.value ?? entry.readings[0].kana;

  const senses = entry.senses;
  // const language = senses[0].translations[0].language;

  return (
    <div className="bg-dark-950 rounded-lg text-white p-2 mb-2">
      <p className="text-lg font-semibold mb-1">{main}</p>
      {!!entry.kanji.length && <p className="text-sm text-gray-300 mb-1">{entry.readings[0].kana}</p>}
      <ul>{senses.map((sense, index) => (
        <li key={index}>- {sense.translations.map((translation, index) => (
          <span key={index}>
            <span className="inline-block bg-dark-900 rounded px-1 mr-1">{translation.language === 'fre' ? 'fr' : 'en'}</span>
            {translation.value}{index === sense.translations.length - 1 ? '' : ', '}
          </span>
        ))}</li>
      ))}</ul>
    </div>
  );
}
