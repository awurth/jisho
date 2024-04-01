export default function Login() {
  return (
    <div className="bg-gray-50 h-full">
      <div className="flex flex-col items-center justify-center px-6 py-8 mx-auto">
        <div className="grid grid-cols-2 w-full bg-white rounded-xl shadow-lg max-w-2xl">
          <div className="bg-primary-100 rounded-l-xl py-5">
            <h1 className="text-2xl text-center">Jish.io</h1>
          </div>
          <div className="text-center py-5">
            <a href="/api/connect/google" className="inline-block border-2 rounded-full p-3">
              Se connecter avec Google
            </a>
          </div>
        </div>
      </div>
    </div>
  );
}
