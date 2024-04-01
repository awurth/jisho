import clsx from 'clsx';
import {forwardRef} from 'react';

const Input = forwardRef(function Input(props, ref) {
  return (
    <input {...props}
           ref={ref}
           className={clsx('border-2 rounded-xl hover:border-gray-300 focus:outline-none focus:border-primary-400 caret-primary-400 px-3 py-2', props.className ?? '')}/>
  );
});

export default Input;
