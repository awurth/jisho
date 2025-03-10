import clsx from "clsx";
import { forwardRef } from "react";

const Input = forwardRef(function Input({ ...props }, ref) {
  props.ref = ref;
  props.className = clsx(
    "bg-gray-200/50 hover:bg-gray-100 focus:bg-gray-100 rounded-full focus:outline-hidden caret-gray-400 text-gray-400 px-3 py-2",
    {
      // "hover:border-gray-200 focus:border-gray-200": !props.error,
      "border-red-400": !!props.error,
    },
    props.className ?? "",
  );

  return <input {...props} />;
});

export default Input;
