import clsx from 'clsx';

export default function Button(props) {
  return (
    <button {...props}
           className={clsx('bg-primary-400 hover:bg-primary-500 rounded-lg text-white border-2 border-primary-400 hover:border-primary-500 px-2 py-1', props.className ?? '')}/>
  );
}
