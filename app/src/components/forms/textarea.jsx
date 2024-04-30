import clsx from 'clsx';
import {forwardRef} from 'react';
import TextareaAutosize from 'react-textarea-autosize';

const Textarea = forwardRef(function Textarea({children, ...props}, ref) {
  props.ref = ref;
  props.className = clsx('border shadow rounded-xl hover:border-gray-300 focus:outline-none focus:border-primary-400 caret-primary-400 px-3 py-2 resize-none', props.className ?? '');

  return (
    <TextareaAutosize {...props}>
      {children}
    </TextareaAutosize>
  );
});

export default Textarea;
