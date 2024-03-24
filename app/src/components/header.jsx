import {useUserStore} from '../stores/user.js';

export default function Header() {
  const {name, avatarUrl} = useUserStore((state) => state.user);

  return (
    <header className="text-gray-600 body-font">
      <div className="container mx-auto flex flex-wrap p-5 flex-row items-center">
        <a className="flex title-font font-medium items-center text-gray-900 mb-0">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" strokeLinecap="round"
               strokeLinejoin="round" strokeWidth="2" className="w-10 h-10 text-white p-2 bg-red-500 rounded-full"
               viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path>
          </svg>
          <span className="ml-3 text-xl">Jish.io</span>
        </a>
        <nav className="ml-auto flex flex-wrap items-center text-base justify-center">
          <a className="mr-5">Quiz</a>
        </nav>
        <span className="mr-2">{name}</span>
        <img alt="avatar"
             className="w-10 h-10 object-cover object-center rounded-full inline-block"
             src={avatarUrl}/>
      </div>
    </header>
  );
}
