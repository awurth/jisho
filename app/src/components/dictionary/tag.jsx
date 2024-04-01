import clsx from 'clsx';

export default function Tag({name, ...props}) {
  return (
    <span {...props}
         className={clsx('bg-primary-400 hover:bg-primary-500 rounded text-white text-xs px-2 py-0.5 cursor-pointer text-nowrap', props.className ?? '')}>
      {name}
    </span>
  );
}
