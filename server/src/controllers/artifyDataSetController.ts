import { Request, Response } from 'express';
import pool from '../database';

class ArtifyDataSetController { 
    
    // Obtener: Información 'BD' DeteccionIA 
    public async dataSet(req: Request, res: Response){
        try{
            const { dataSet } = req.params;
            const resp = await pool.query(`SELECT * FROM "dataSet" WHERE "nombre" = '${[dataSet]}'`);
            if (resp.rows[0]) {
                res.json(resp); 
            }else{
                res.json({ msj: "DataSet InExist" });
            }

        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    } 

}
const artifyDataSetController = new ArtifyDataSetController;
export default artifyDataSetController;