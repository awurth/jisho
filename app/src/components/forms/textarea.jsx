import clsx from 'clsx';
import {forwardRef} from 'react';

const Textarea = forwardRef(function Textarea({children, ...props}, ref) {
  props.ref = ref;
  props.className = clsx('border-2 rounded-xl hover:border-gray-300 focus:outline-none focus:border-primary-400 caret-primary-400 px-3 py-2', props.className ?? '');

  return (
    <textarea {...props}>
      {children}
    </textarea>
  );
});

export default Textarea;
