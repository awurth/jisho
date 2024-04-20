import {useStopwatch} from 'react-timer-hook';

export default function Timer({running, ...props}) {
  const {
    seconds,
    minutes,
    isRunning,
    start,
    pause,
  } = useStopwatch({ autoStart: false });

  if (running) {
    if (!isRunning) {
      start();
    }
  } else if (isRunning) {
    pause();
  }

  return (
    <span {...props}>{minutes.toLocaleString('en-US', {minimumIntegerDigits: 2})}:{seconds.toLocaleString('en-US', {minimumIntegerDigits: 2})}</span>
  );
}
