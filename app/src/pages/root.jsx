import {Outlet} from 'react-router-dom';
import Header from '../components/header';

export default function Root() {
  return (
    <>
      <Header></Header>
      <main className="container mx-auto">
        <Outlet/>
      </main>
    </>
  );
}
