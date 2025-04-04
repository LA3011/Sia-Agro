
import keys from './keys';
import pg from 'pg';

// Configura la conexión
const pool = new pg.Pool({
  user: keys.database.user,
  password: keys.database.password,
  host: keys.database.host,
  port: keys.database.port,
  database: keys.database.database, 
});

// Realiza la conexión
pool.connect()
  .then((client: any) => {
    console.log('Conexión exitosa a la base de datos');
    // Realiza tus operaciones con la base de datos aquí
    client.release(); // Libera el cliente cuando hayas terminado
  });
  
export default pool;