import { Request, Response } from 'express';
import pool from '../database';
 
class menuController {

    // PERFECTO
    public async listprogramas(req: Request, res: Response): Promise<void> {
        const users = await pool.query('SELECT * FROM programa');
        res.json(users.rows);  
    } 
 
    // PERFECTO
    public async listsubprogramas(req: Request, res: Response) {
        try{
            const users = await pool.query('SELECT * FROM sub_programa');
            res.json(users.rows);
            
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

}

const MenuController = new menuController;
export default MenuController;