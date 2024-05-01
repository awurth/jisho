import clsx from 'clsx';

export default function Tag({name, ...props}) {
  return (
    <span {...props}
         className={clsx('bg-secondary-500 hover:bg-secondary-400 rounded-full text-white text-xs px-2 py-0.5 cursor-pointer text-nowrap', props.className ?? '')}>
      {name}
    </span>
  );
}
