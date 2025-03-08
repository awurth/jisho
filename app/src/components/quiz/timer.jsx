import { useStopwatch } from "react-timer-hook";

export default function Timer({ running, startDate, ...props }) {
  const offset = new Date();
  const secondsElapsed = Math.floor(
    (offset.getTime() - startDate.getTime()) / 1000,
  );
  offset.setSeconds(offset.getSeconds() + secondsElapsed);

  const { seconds, minutes, hours, isRunning, start, pause } = useStopwatch({
    autoStart: false,
    offsetTimestamp: offset,
  });

  if (running) {
    if (!isRunning) {
      start();
    }
  } else if (isRunning) {
    pause();
  }

  return (
    <span {...props}>
      {0 !== hours &&
        `${hours.toLocaleString("en-US", { minimumIntegerDigits: 2 })}:`}
      {minutes.toLocaleString("en-US", { minimumIntegerDigits: 2 })}:
      {seconds.toLocaleString("en-US", { minimumIntegerDigits: 2 })}
    </span>
  );
}
