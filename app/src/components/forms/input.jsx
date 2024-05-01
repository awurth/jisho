import clsx from 'clsx';
import {forwardRef} from 'react';

const Input = forwardRef(function Input({...props}, ref) {
  props.ref = ref;
  props.className = clsx(
    'bg-dark-950 border-2 border-dark-900 rounded-xl focus:outline-none caret-gray-400 text-gray-400 px-3 py-2',
    {
      'hover:border-dark-800 focus:border-dark-600': !props.error,
      'border-red-400': !!props.error,
    },
    props.className ?? '',
  );

  return (
    <input {...props}/>
  );
});

export default Input;
