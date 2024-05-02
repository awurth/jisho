import clsx from "clsx";
import { forwardRef } from "react";
import TextareaAutosize from "react-textarea-autosize";

const Textarea = forwardRef(function Textarea({ children, ...props }, ref) {
  props.ref = ref;
  props.className = clsx(
    "bg-dark-950 border-2 border-dark-900 rounded-xl hover:border-dark-800 focus:outline-none focus:border-dark-600 caret-gray-400 text-gray-400 px-3 py-2 resize-none",
    props.className ?? "",
  );

  return <TextareaAutosize {...props}>{children}</TextareaAutosize>;
});

export default Textarea;
