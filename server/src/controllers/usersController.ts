import { Request, Response } from 'express';
import pool from '../database';
 
class UsersController {

    // Listar Usuario(s)
    public async list(req: Request, res: Response): Promise<void> { // Fuction: request => Solicitud | response => Respuesta
        const users = await pool.query('SELECT * FROM persona');    // Consulta: SQL 
        res.json(users);                                            // Retorno: Informacion de Todos los Usuarios 'BD', al Cliente
    } 
 
    // Mostrar Detalles Único Usuario
    public async getOne(req: Request, res: Response) {
        try{                                                                                        // Manejo de Exepciones: Try/Catch
            const { id } = req.params;                                                              // Asignacion: valores por parametro (GET: number)
            const users = await pool.query(`SELECT * FROM persona WHERE id = ${[id]}`);             // Consulta: SQL 'Tipo: Select' | Uso de Param.
            if (users.rows[0]) {
                return res.json(users.rows);                                                        // Retorno: Informacion del Usuario 'BD' (Coincidencia)
            }
            res.status(404).json({ text: "User InExist" });                                         // Retorno: Msj recurso no encontrado (Usuario Inexistente)
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });                                        // Retorno: Msj Error Servidor / Petición
        }
    }

    // Crear Nuevo Usuario
    public async create(req: Request, res: Response): Promise<void> {
        try {
            const updatedData = req.body;                                                           // Asignacion: valores por parametro (POST: json)
            const nombre = updatedData[0].nombre;                                                   // Asignacion: valores (ArrayObj -> var. 'nombre')
            const edad = updatedData[0].edad;                                                       // Asignacion: valores (ArrayObj -> var. 'edad')
            await pool.query(`INSERT INTO persona (nombre,edad) VALUES ('${nombre}', '${edad}')`);  // Consulta: SQL 'Tipo: Insert' | Uso de var.
            res.json({ message: 'User Memory Check' });                                             // Retorno: Msj Exito Consulta
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

    // Actualizar Usuario
    public async update(req: Request, res: Response) {
        try {
            const id = req.params.id;                                                               // Asignacion: valores por parametro (PUT: number)
            const updatedData = req.body;
            const nombre = updatedData[0].nombre;
            const edad = updatedData[0].edad;
            await pool.query(`UPDATE persona SET nombre = '${nombre}', edad = '${edad}' WHERE id = '${id}' `);
            res.json( { massage: 'User Update Check' } );                                           // Retorno: Msj Exito Consulta
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

    // Eliminar Usuario
    public async delete(req: Request, res: Response): Promise<void> {
        try{
            const { id } = req.params;                                                              // Asignacion: valores por parametro (DELETE: number)
            await pool.query(`DELETE FROM persona WHERE id = ${[id]}`);
            res.json({ message: "User Delete Check" });
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }
    

}

const usersController = new UsersController;
export default usersController;