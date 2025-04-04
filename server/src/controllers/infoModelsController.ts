import { Request, Response } from 'express';
import pool from '../database';
 
class InfoModelsController {
 
    // Mostrar Detalles Info. Etiqueta
    public async getOne(req: Request, res: Response) {
        try{                                                                                        // Manejo de Exepciones: Try/Catch
            const { id } = req.params;                                                              // Asignacion: valores por parametro (GET: number)
            const tagInfo = await pool.query(`SELECT * FROM "dataSet" WHERE nombre = '${[id]}'`);             // Consulta: SQL 'Tipo: Select' | Uso de Param.
            if (tagInfo.rows[0]) {
                return res.json(tagInfo.rows[0]);                                                        // Retorno: Informacion del Usuario 'BD' (Coincidencia)
            }
            res.status(404).json({ text: "Info Etiqueta No Existe" });                                         // Retorno: Msj recurso no encontrado (Usuario Inexistente)
        } catch (error) {
            res.status(500).json({ error: 'Error: Server Detalle Info. Etiqueta' });                                        // Retorno: Msj Error Servidor / Petición
        }
    }

    // Crear Nuevo Info. Etiqueta
    public async create(req: Request, res: Response): Promise<void> {
        try {
            const updatedData = req.body;                                          // Asignacion: valores por parametro (POST: json)
            var tratamiento = updatedData[0].tratamiento;                        // Asignacion: valores (ArrayObj -> var. 'tratamiento')
            const definicion = updatedData[0].definicion;                                                       
            const familia = updatedData[0].familia;                                                   
            const nombre = updatedData[0].nombre;  
            const amenaza = updatedData[0].amenaza;                                                   
            
            if(!amenaza){
                tratamiento = "No Aplica"
            }

            await pool.query(`INSERT INTO "dataSet" (nombre,definicion,familia,tratamiento, amenaza) VALUES ('${nombre}', '${definicion}', '${familia}', '${tratamiento}', ${amenaza})`);  // Consulta: SQL 'Tipo: Insert' | Uso de var.
            res.json({ message: 'Info. Etiqueta Almacenada' });                    // Retorno: Msj Exito Consulta
        
        } catch (error) {
            res.status(500).json({ error: 'Error: Server Almacenar Info. Etiqueta' });
        }
    }

    // Actualizar Info. Etiqueta
    public async update(req: Request, res: Response) {
        try {     
            
            const nombre = req.params.id;   

            const updatedData = req.body;
            const familia = updatedData[0].familia;
            const definicion = updatedData[0].definicion;
            const tratamiento = updatedData[0].tratamiento;
            
            var tratamientoUpdate = updatedData[0].tratamiento;                        // Asignacion: valores (ArrayObj -> var. 'tratamiento')
            const amenaza = updatedData[0].amenaza;                                                   
            
            if(!amenaza){
                tratamientoUpdate = "No Aplica"
            }
            
            await pool.query(`UPDATE "dataSet" SET definicion='${definicion}', familia = '${familia}', tratamiento = '${tratamientoUpdate}', amenaza = ${amenaza} WHERE nombre = '${nombre}' `);
            
            res.json( { message: 'Info. Etiqueta Actualizada' } ); 
        
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

    // Eliminar Info. Etiqueta
    public async delete(req: Request, res: Response): Promise<void> {
        try{
            const { id } = req.params;                                                              // Asignacion: valores por parametro (DELETE: number)
            await pool.query(`DELETE FROM "dataSet" WHERE nombre = '${[id]}'`);
            res.json({ message: "Info. Etiqueta Eliminada" });
        } catch (error) {
            res.status(500).json({ error: 'Error: Server Eliminar Info. Etiqueta' });
        }
    }
    

}

const infoModelsController = new InfoModelsController;
export default infoModelsController;