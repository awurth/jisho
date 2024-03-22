import {defineConfig} from 'vite';
import react from '@vitejs/plugin-react';
import fs from 'fs';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    host: 'jish.io',
    https: {
      key: fs.readFileSync('/certs/jish.io-key.pem'),
      cert: fs.readFileSync('/certs/jish.io.pem'),
    },
  },
})
