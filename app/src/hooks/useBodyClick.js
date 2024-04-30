import {useEffect} from 'react';

export default function useBodyClick(containerSelectors, callback) {
  const onBodyClick = (event) => {
    if (containerSelectors.every((selector) => !event.target.closest(selector))) {
      callback(event);
    }
  };

  useEffect(() => {
    document.addEventListener('click', onBodyClick);

    return () => {
      document.removeEventListener('click', onBodyClick);
    };
  }, []);
}
