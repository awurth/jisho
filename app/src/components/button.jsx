import { cva } from "class-variance-authority";
import { Link } from "react-router";

const button = cva("rounded-full", {
  variants: {
    intent: {
      primary: ["bg-gray-950", "text-white", "border-transparent"],
      secondary: ["bg-gray-200", "text-gray-800", "border-transparent"],
    },
    size: {
      small: ["text-sm", "py-1", "px-2"],
      medium: ["text-base", "py-2", "px-4"],
      large: ["text-lg", "py-3", "px-6"],
      block: ["text-base", "py-2", "px-4", "w-full"],
    },
    disabled: {
      false: null,
      true: ["opacity-50", "cursor-not-allowed"],
    },
  },
  compoundVariants: [
    {
      intent: "primary",
      disabled: false,
      class: "hover:bg-gray-800",
    },
    {
      intent: "secondary",
      disabled: false,
      class: "hover:bg-gray-100",
    },
  ],
  defaultVariants: {
    disabled: false,
    intent: "primary",
    size: "medium",
  },
});

export default function Button({
  intent,
  size,
  disabled,
  className,
  href,
  ...props
}) {
  const buttonProps = {
    className: button({ intent, size, disabled, className }),
    disabled: disabled || undefined,
    ...props,
  };

  return !!href ? (
    <Link to={href} {...buttonProps} />
  ) : (
    <button {...buttonProps} />
  );
}
