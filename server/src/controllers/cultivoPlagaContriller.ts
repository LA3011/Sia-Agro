import { Request, Response } from 'express';
import pool from '../database';

class CultivoPlagaController { 

    public async getList(req: Request, res: Response) {
        try {
            // const cultivoPlaga = await pool.query(`SELECT DISTINCT ON (c."ID") c."ID", COALESCE(c.nombre, '') AS nombre, COALESCE(cp.Id_plaga, 0) AS Id_plaga, COALESCE(c.tipo, '') AS tipo, COALESCE(c."espacio", '') AS espacio, COALESCE(cp.nombre_plaga, '') AS nombre_plaga, COALESCE(cp.fecha_ultima_deteccion::TEXT, '') AS fecha_ultima_deteccion FROM cultivos c LEFT JOIN cultivo_plaga cp ON c."ID" = cp.id ORDER BY c."ID", cp.fecha_ultima_deteccion DESC;`)
            const cultivoPlaga = await pool.query(`
            WITH registros_filtrados AS (
            SELECT DISTINCT ON (c."ID") 
                    c."ID",
                    c."estado" AS estado,  
                    COALESCE(c.nombre, '') AS nombre,
                    COALESCE(cp.Id_plaga, 0) AS Id_plaga,
                    COALESCE(c.tipo, '') AS tipo,
                    COALESCE(c."espacio", '') AS espacio,
                    COALESCE(cp.nombre_plaga, '') AS nombre_plaga,
                    COALESCE(cp.fecha_ultima_deteccion::TEXT, '') AS fecha_ultima_deteccion
                FROM public.cultivos c
                LEFT JOIN cultivo_plaga cp ON c."ID" = cp.id
                ORDER BY c."ID", cp.fecha_ultima_deteccion DESC
            )
            SELECT ROW_NUMBER() OVER () AS iterador, *
            FROM registros_filtrados
            WHERE estado = false;
            `)
            
            res.json(cultivoPlaga.rows)

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Cultivo/Plaga' });            
        }
    }
    
    public async getListCosechados(req: Request, res: Response) {
        try {
            // const cultivoPlaga = await pool.query(`SELECT DISTINCT ON (c."ID") c."ID", COALESCE(c.nombre, '') AS nombre, COALESCE(cp.Id_plaga, 0) AS Id_plaga, COALESCE(c.tipo, '') AS tipo, COALESCE(c."espacio", '') AS espacio, COALESCE(cp.nombre_plaga, '') AS nombre_plaga, COALESCE(cp.fecha_ultima_deteccion::TEXT, '') AS fecha_ultima_deteccion FROM cultivos c LEFT JOIN cultivo_plaga cp ON c."ID" = cp.id ORDER BY c."ID", cp.fecha_ultima_deteccion DESC;`)
            const cultivoPlaga = await pool.query(`
            WITH registros_filtrados AS (
            SELECT DISTINCT ON (c."ID") 
                    c."ID",
                    c."estado" AS estado,  
                    COALESCE(c.nombre, '') AS nombre,
                    COALESCE(cp.Id_plaga, 0) AS Id_plaga,
                    COALESCE(c.tipo, '') AS tipo,
                    COALESCE(c."espacio", '') AS espacio,
                    COALESCE(cp.nombre_plaga, '') AS nombre_plaga,
                    COALESCE(cp.fecha_ultima_deteccion::TEXT, '') AS fecha_ultima_deteccion
                FROM public.cultivos c
                LEFT JOIN cultivo_plaga cp ON c."ID" = cp.id
                ORDER BY c."ID", cp.fecha_ultima_deteccion DESC
            )
            SELECT ROW_NUMBER() OVER () AS iterador, *
            FROM registros_filtrados
            WHERE estado = true;
            `)
            
            res.json(cultivoPlaga.rows)

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Cultivo/Plaga' });            
        }
    }

    public async getHistory(req: Request, res: Response){
        try {
            const {id} = req.params

            // const cultivoPlaga = await pool.query(`SELECT cultivo_plaga.*, cultivos.* FROM cultivo_plaga INNER JOIN cultivos ON cultivo_plaga.id = cultivos."ID"`);
            const cultivoPlaga = await pool.query(`
                SELECT cultivo_plaga.*, cultivos.*
                FROM cultivo_plaga
                INNER JOIN cultivos ON cultivo_plaga.id = cultivos."ID"
                WHERE cultivo_plaga.id = '${Number(id)}'
                ORDER BY cultivo_plaga.fecha_ultima_deteccion DESC;
            `);
            
            res.json(cultivoPlaga.rows)

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Cultivo/Plaga' });            
        }
    }

    public async getOne(req: Request, res: Response){
        try {
            const {id} = req.params
            const OnecultivoPlaga = await pool.query(`SELECT cultivo_plaga.*, cultivos.* FROM cultivo_plaga INNER JOIN cultivos ON cultivo_plaga.id = cultivos."ID" WHERE cultivos.nombre = '${id}' ORDER BY cultivo_plaga.fecha_ultima_deteccion DESC LIMIT 1`);

            if(OnecultivoPlaga.rowCount == 0){
                const OnecultivoPlaga = await pool.query(`SELECT * FROM cultivos WHERE nombre = '${id}' LIMIT 1`);
                return res.json(OnecultivoPlaga.rows[0].ID)
            }else{
                return res.json(OnecultivoPlaga.rows[0])
            }

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Detalles Cultivo/Plaga' });
            
        }
    }

    public async update(req: Request, res: Response){
        try {
            const {id} = req.params
            const data = req.body

            if(data.nombre_plaga == "Sin Plagas"){
                data.nombre_plaga = "" 
            }

            // const UpdatePlaga = await pool.query(`UPDATE cultivo_plaga SET nombre_plaga='${data.nombre_plaga}', fecha_ultima_deteccion='${data.fecha_ultima_deteccion}' WHERE id = ${Number(id)}`)
            const insertPlaga = await pool.query(`INSERT INTO cultivo_plaga (id, nombre_plaga, fecha_ultima_deteccion) VALUES (${Number(id)}, '${data.nombre_plaga}', '${data.fecha_ultima_deteccion}');`)
            res.json(insertPlaga)

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Actualizar Cultivo/Plaga' });
            
        }
    }
    

}
const cultivoPlagaController = new CultivoPlagaController;
export default cultivoPlagaController;