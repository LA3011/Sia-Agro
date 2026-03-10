import { Request, Response } from 'express';
import pool from '../database';
 
class NotifyController {

    // Actualizar Notificacion
    public async update(req: Request, res: Response) {
        try {
            const id = req.params.id;                                                               // Asignacion: valores por parametro (PUT: number)
            const idnum = parseFloat(id);
            const updatedData = req.body;
            const etiqueta = updatedData[0].etiqueta;
            const expansion = updatedData[0].expansion;

            // Resetear: Notificaciones_movil
            if(etiqueta == false && expansion == false){
                await pool.query(`UPDATE notificaciones_movil SET etiqueta = ${etiqueta}, expansion = ${expansion} WHERE id_notificacion = ${idnum} `);
            }

            // Notifica: Una nueva Etiqueta
            if(etiqueta == true){
                await pool.query(`UPDATE notificaciones_movil SET etiqueta = ${etiqueta} WHERE id_notificacion = ${idnum} `);
            }else{ 
                // Notifica: Una nueva Expansion
                await pool.query(`UPDATE notificaciones_movil SET expansion = ${expansion} WHERE id_notificacion = ${idnum} `);
            }

           
            res.json( { massage: 'Etiqueta Actualizada' } );                                         // Retorno: Msj Exito Consulta
        
        } catch (error) {
            res.status(500).json({ error: 'Error Server: Notificaciones_movil Etiquetas' });
        }
    }

    // Listar Notificacion(s)
    public async list(req: Request, res: Response): Promise<void> {
        const notify = await pool.query('SELECT * FROM notificaciones_movil');    
        res.json(notify.rows[0]);                                            
    } 

    
}

const notifyController = new NotifyController;
export default notifyController;