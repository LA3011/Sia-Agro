import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'io.ionic.starter',
  appName: 'SiaAgro',
  webDir: 'www',
  bundledWebRuntime: false,
  server : {
    cleartext: true,
    // url: "http://192.168.1.100:3000" 
  },
  

  // habilitar APIs Locales
  plugins:{
    CapacitorHttp:{
      enabled: true
    },
  },

};

export default config;
