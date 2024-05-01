import { faCaretDown, faPlus } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { Menu } from "@headlessui/react";
import { useQuery } from "@tanstack/react-query";
import { getDictionaries } from "../api/dictionary.js";
import { useDictionaryStore } from "../stores/dictionary.js";

export default function DictionaryDropdown() {
  const setActiveDictionary = useDictionaryStore(
    (state) => state.setActiveDictionary,
  );

  const { data: dictionaries = [] } = useQuery({
    queryKey: ["dictionaries"],
    queryFn: getDictionaries,
  });

  const onDictionaryClick = (dictionary) => {
    setActiveDictionary(dictionary);
  };

  return (
    <Menu as="div" className="relative">
      <Menu.Button className="text-white bg-black/15 hover:bg-black/20 px-3 py-2 rounded-md">
        <span className="mr-1">Japonais</span>
        <FontAwesomeIcon icon={faCaretDown} />
      </Menu.Button>
      <Menu.Items className="absolute bg-white px-2 py-3 mt-1 rounded-md shadow-lg">
        {dictionaries.map(({ id, name }) => (
          <Menu.Item
            key={id}
            className="px-4 py-2 hover:bg-gray-100 rounded-md cursor-pointer"
          >
            <div onClick={() => onDictionaryClick({ id, name })}>{name}</div>
          </Menu.Item>
        ))}
        <Menu.Item className="px-4 py-2 hover:bg-gray-100 rounded-md cursor-pointer whitespace-nowrap">
          <div>
            <FontAwesomeIcon icon={faPlus} className="mr-1" />
            Nouveau
          </div>
        </Menu.Item>
      </Menu.Items>
    </Menu>
  );
}
