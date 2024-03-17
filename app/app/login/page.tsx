export default function Page() {
  return (
    <div className="relative py-16">
      <div className="container relative m-auto px-6 text-gray-500 md:px-12 xl:px-40">
        <div className="m-auto w-6/12">
          <div
            className="rounded-3xl border border-gray-100 bg-white shadow-2xl shadow-gray-600/10">
            <div className="p-8 py-12 sm:p-16">
              <div className="space-y-4">
                <h2 className="mb-8 text-2xl font-bold text-gray-800">
                  Connexion
                </h2>
              </div>
              <div className="mt-16 grid space-y-4">
                <button
                  className="group relative flex h-11 items-center px-6 before:absolute before:inset-0 before:rounded-full before:bg-white before:border before:border-gray-200 before:transition before:duration-300 hover:before:scale-105 active:duration-75 active:before:scale-95 disabled:before:bg-gray-300 disabled:before:scale-100">
                  <span
                    className="w-full relative flex justify-center items-center gap-3 text-base font-medium text-gray-600">
                    <img src="/google.svg" className="absolute left-0 w-5" alt="google logo"/>
                    <span>Continuer avec Google</span>
                  </span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
