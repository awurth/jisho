import clsx from 'clsx';
import {forwardRef} from 'react';

const Input = forwardRef(function Input({...props}, ref) {
  props.ref = ref;
  props.className = clsx(
    'border-2 rounded-xl focus:outline-none caret-primary-400 px-3 py-2',
    {
      'hover:border-gray-300 focus:border-primary-400': !props.error,
      'border-red-400': !!props.error,
    },
    props.className ?? '',
  );

  return (
    <input {...props}/>
  );
});

export default Input;
