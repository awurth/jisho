import clsx from 'clsx';

export default function Tag({name, ...props}) {
  return (
    <span {...props}
         className={clsx('bg-gray-300 hover:bg-gray-400 rounded-full text-white text-xs px-2 py-0.5 cursor-pointer text-nowrap', props.className ?? '')}>
      {name}
    </span>
  );
}
