import {defineConfig} from 'vite';
import react from '@vitejs/plugin-react';
import fs from 'fs';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    host: 'jisho.docker',
    https: {
      key: fs.readFileSync('/certs/ssl-key.pem'),
      cert: fs.readFileSync('/certs/ssl.pem'),
    },
  },
})
