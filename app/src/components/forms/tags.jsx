import clsx from 'clsx';
import CreatableSelect from 'react-select/creatable';

export default function Tags({options, ...props}) {
  return (
    <CreatableSelect
      options={options.map((tag) => ({label: tag, value: tag}))}
      styles={{
        indicatorsContainer: () => ({display: 'none'}),
        indicatorSeparator: () => ({display: 'none'}),
        dropdownIndicator: () => ({display: 'none'}),
      }}
      classNames={{
        control: ({isFocused}) =>
          clsx(
            'border-2 rounded-xl caret-primary-400 px-1 py-0.5 text-sm',
            {'border-primary-400': isFocused, 'hover:border-gray-300': !isFocused},
          ),
        placeholder: () => 'text-gray-400',
        menuList: () => 'bg-white rounded-lg shadow-lg p-1 mt-1',
        option: ({isSelected}) =>
          clsx(
            'rounded-md px-3 py-2 mb-1 text-gray-600 text-sm cursor-pointer',
            {'bg-primary-400 text-white': isSelected, 'hover:bg-gray-100': !isSelected},
          ),
        multiValue: () => 'bg-primary-400 hover:bg-primary-500 rounded text-white text-xs pl-2 pr-1 py-1 mx-0.5 my-0.5',
        multiValueRemove: () => 'ml-1',
      }}
      closeMenuOnSelect={false}
      hideSelectedOptions={false}
      placeholder="verbe, adjectif, nom, ..."
      isClearable={false}
      unstyled
      isMulti
      {...props}
    />
  );
}
