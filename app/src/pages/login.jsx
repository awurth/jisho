import { GoogleLogin } from "@react-oauth/google";
import { useMutation } from "@tanstack/react-query";
import { useNavigate } from "react-router";
import { connectWithGoogle } from "../api/user.js";
import illustration from "../assets/illustration.svg";
import { useUserStore } from "../stores/user.js";

export default function Login() {
  const setUser = useUserStore((state) => state.setUser);
  const navigate = useNavigate();
  const mutation = useMutation({
    mutationFn: ({ accessToken }) => connectWithGoogle(accessToken),
    onSuccess: ({ token, name, avatarUrl }) => {
      localStorage.setItem("token", token);
      setUser({ name, avatarUrl });
      navigate("/");
    },
  });

  return (
    <div className="bg-dark-950 min-h-full flex flex-col items-center justify-center">
      <img
        src={illustration}
        className="px-16 mb-8"
        alt="two people practicing japanese vocabulary on a couch"
      />
      <h1 className="text-4xl font-semibold text-white mb-5">Jisho.fr</h1>
      <p className="text-white font-semibold px-10 mb-5 text-center">
        Create your own Japanese dictionary and improve your vocabulary at your own pace with personalized quizzes!
      </p>
      <GoogleLogin
        onSuccess={(credentialResponse) => {
          mutation.mutate({ accessToken: credentialResponse.credential });
        }}
        onError={() => {
          console.log("Login Failed");
        }}
      />
    </div>
  );
}
