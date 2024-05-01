import clsx from "clsx";

export default function Button(props) {
  return (
    <button
      {...props}
      className={clsx(
        "bg-primary-500 border-b-4 border-primary-600 rounded-xl text-white font-semibold px-2 py-1",
        props.className ?? "",
      )}
    />
  );
}
