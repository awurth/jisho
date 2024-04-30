import clsx from 'clsx';
import {toRomaji} from 'wanakana';
import Tag from './tag.jsx';

export default function Entry({entry, ...props}) {
  return (
    <div className={clsx('shadow rounded-md px-3 py-2 flex flex-col', props.className ?? '')}>
      <div className="flex mb-1">
        <div className="left mr-3">
          <div className="text-xl font-bold">{entry.japanese}</div>
          <div className="text-xs text-gray-600">{toRomaji(entry.japanese)}</div>
        </div>
        <div className="right">
          {entry.french.join(', ')}
        </div>
      </div>
      <div className="flex flex-wrap">
        {entry.tags.map((tag) => (
          <Tag key={tag} name={tag} className="mr-1 mb-1"/>
        ))}
      </div>
    </div>
  );
}
