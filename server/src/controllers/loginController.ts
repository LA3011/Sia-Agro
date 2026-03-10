import { Request, Response } from 'express';
import pool from '../database';
import bcrypt from 'bcryptjs' 
 
class GamesController {


    public async searchBD(req: Request, res: Response)  {
        try{

            // Obteniendo Data Usuario
            const updatedData = req.body;                                                                             // Asignacion: valores por parametro (POST: json)
            const user = updatedData[0].user;                                                                         // Asignacion: valores (ArrayObj -> var. 'nombre')
            const password = updatedData[0].password;   
            
            // Verificando nombre Usuario
            const userBD = await pool.query(`SELECT * FROM usuarios WHERE "Usuario" ='${user}' AND "Id_Perfil_Movil" IS NOT NULL AND "Id_Perfil_Movil" <> 0`);  

            // Usuario Inexistente (ningun Usuario contiene Ese Nombre Usuario)
            if(!userBD.rows[0]) return res.json({ message: 'Usuario No Existente', data: updatedData});  
            
            // Verificar Encryptado (Password)
            bcrypt.compare(password, userBD.rows[0].Clave, (_err, response) => {
                // Error en DesEncriptar
                if(_err) return res.status(500).json({ error: 'Error interno del servidor' });    
                // Usuario Valido
                if (response) return res.json(userBD); 
                // Usuario Invalido
                return res.json({ message: 'Usuario No Existente', data: updatedData});
            });


        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor' });                                            // Retorno: Msj Error Servidor / Petición
        }
    } 

    // Consultar: Usuario si Existe en BD
    public async search(req: Request, res: Response)  {
        try{
            const updatedData = req.body;                                                                             // Asignacion: valores por parametro (POST: json)
            const user = updatedData[0].user;                                                                         // Asignacion: valores (ArrayObj -> var. 'nombre')
            const password = updatedData[0].password;                                                                 // Asignacion: valores (ArrayObj -> var. 'contraseña')
            const User = await pool.query(`SELECT * FROM persona WHERE nombre='${user}' and edad='${password}'  `);   // Consulta: SQL 'Tipo: Select' | Uso de var.
            if (User) {
                return res.json(User);                                                                                // Retorno: Informacion del Usuario 'BD' (Coincidencia)
            }
            res.json({ message: 'Usuario No Existente' });                                                            // Retorno: Msj recurso no encontrado (Usuario Inexistente)
        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor' });                                            // Retorno: Msj Error Servidor / Petición
        }
    } 

    // Listar Usuario(s)
    public async list(req: Request, res: Response): Promise<void> {
        const games = await pool.query('SELECT nombre FROM persona');
        res.json(games);
    } 


}

const gamesController = new GamesController;
export default gamesController;