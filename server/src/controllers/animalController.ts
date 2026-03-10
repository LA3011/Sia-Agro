import { Request, Response } from 'express';
import pool from '../database';

class AnimalController { 

    public async getList(req: Request, res: Response) {
        try {
            const animals = await pool.query(`SELECT * FROM animales WHERE "Venta"='Venta'`);
            
            res.json(animals.rows)

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Cultivo/Plaga' });            
        }
    } 

    public async getListPAstoreo(req: Request, res: Response) {
        try {
            const animals = await pool.query(`SELECT * FROM "animalsTest"`);
            
            res.json(animals.rows)

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Cultivo/Plaga' });            
        }
    } 


}
const animalController = new AnimalController;
export default animalController;