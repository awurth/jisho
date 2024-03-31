import {useEffect} from 'react';

export default function useBodyClick(containerSelector, callback) {
  const onBodyClick = (event) => {
    if (!event.target.closest(containerSelector)) {
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
