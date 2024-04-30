import {Outlet} from 'react-router-dom';
import Header from '../components/header';
import TabBar from '../components/tab-bar.jsx';

export default function Root() {
  return (
    <div className="bg-primary-600 min-h-full flex flex-col">
      <Header></Header>
      <main className="flex flex-col container mx-auto grow px-2">
        <div className="grow bg-white rounded-t-3xl pt-4 px-4 pb-14">
          <Outlet/>
        </div>
      </main>
      <TabBar></TabBar>
    </div>
  );
}
