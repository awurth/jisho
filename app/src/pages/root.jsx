import {Outlet} from 'react-router-dom';
import Header from '../components/header';

export default function Root() {
  return (
    <div className="flex flex-col h-full">
      <Header></Header>
      <main className="container mx-auto grow">
        <Outlet/>
      </main>
    </div>
  );
}
