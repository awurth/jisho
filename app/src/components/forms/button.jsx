import clsx from 'clsx';

export default function Button(props) {
  return (
    <button {...props}
           className={clsx('bg-primary-500 hover:bg-primary-600 rounded-lg text-white border-2 border-primary-500 hover:border-primary-600 px-2 py-1', props.className ?? '')}/>
  );
}
