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
    
    // Monitoriar Rendimienodo General
    async getRendimient(req: Request, res: Response){
        const rendimient = {
            detect: 0,
            tag: "0",
            right: 0,
            discarded: 0
        }   

        try{   
            // presicion | Acertados | Descarte 
            const {rows} = await pool.query(`SELECT * FROM "press_detection"`);  

            // Peso Img
            const path = require('path');
            const dirPath = path.join(__dirname, '..', '..', '..', 'InteligenciaArtificial', 'data');
            const size = await getTotalSize(dirPath);

            // calculos de precision
            const total = Number(rows[0].right) + Number(rows[0].discarded);
            const precision = total > 0 ? (Number(rows[0].right) / total) * 100 : 0;
            const precisionFormateada = parseFloat(precision.toFixed(2));

            // Resultados
            rendimient.detect = rows[0].detection       // presicion IA 
            rendimient.discarded = rows[0].discarded    // descartadas IA
            rendimient.right = precisionFormateada            // Acertadas IA
            rendimient.tag = formatBytes(size)          // Peso Img (GB)

            return res.json(rendimient);                                                       

        } catch (error) {
            res.status(500).json({ error: 'Error: Server Detalle Info. Rendimiento' });  
        }
    }

    // Agregar/Acumular puntos de precision (por deteccion)
    async createPress(req: Request, res: Response){
        try {
            const { state } = req.body;  // right || discarded 
            const column = state == "right" ? '"right"' : '"discarded"';
            
            const query = `
                UPDATE press_detection 
                SET ${column} = ${column} + 1 
                RETURNING *;
            `;

            const response = await pool.query(query);

            // Si la tabla está vacía, rowCount será 0
            if (response.rowCount === 0) {
                return res.status(404).json({ error: "La tabla está vacía, no hay nada que actualizar" });
            }

            res.json({
                message: "Contador actualizado",
                stats: response.rows[0]
            });

        } catch (error) {
            console.error("Error al actualizar Opinion:", error);
            res.status(500).json({ error: "Error en el servidor" });
        }
    }

    // registrar deteccion
    async getDetection(req: Request, res: Response){
        try{
            await pool.query(`UPDATE press_detection SET detection = detection + 1 RETURNING *;`);
            res.json("Deteccion Registrada Excitosamente")

        }catch(error){
            console.log(error)
            res.status(500).json({ error: 'Error: Server Registro Deteccion' });  
            
        }
    }

    // registrar likes/dislikes
    async createLikes(req: Request, res: Response) {
        try {
            // Extraemos los booleanos del body
            const { like, dislike } = req.body; 
            
            // Validamos que al menos uno sea true para no hacer una consulta vacía
            if (!like && !dislike) {
                return res.status(400).json({ error: "Debes enviar un like o un dislike" });
            }

            let updates = [];
            
            // Usamos los nombres de columna: "right" para likes y "discarded" para dislikes
            if (like) updates.push('"right" = "right" + 1');
            if (dislike) updates.push('"discarded" = "discarded" + 1');

            // Construimos el query. 
            // IMPORTANTE: Si solo hay un registro, usualmente tiene un ID (ej. 1)
            // Si no usas WHERE, actualizará todas las filas (que en tu caso es una sola).
            const query = `
                UPDATE press_detection 
                SET ${updates.join(', ')} 
                RETURNING *;
            `;

            const response = await pool.query(query);

            if (response.rowCount === 0) {
                return res.status(401).json({ 
                    error: "No se encontró el registro para actualizar" 
                });
            }

            res.json({
                message: "Contador actualizado con éxito",
                stats: response.rows[0]
            });

        } catch (error) {
            console.error("Error al actualizar Likes/Dislikes:", error);
            res.status(500).json({ error: "Error interno del servidor" });
        }
    }

}

// calcular peso img
async function getTotalSize(dirPath: string): Promise<number> {
    const fs = require('fs').promises;
    const path = require('path');
    let totalSize = 0;

    try {
        const items = await fs.readdir(dirPath, { withFileTypes: true });

        // Procesamos todos los elementos en paralelo para mayor velocidad
        const sizes = await Promise.all(items.map(async (item:any) => {
            const fullPath = path.join(dirPath, item.name);
            
            try {
                if (item.isDirectory()) {
                    // Si es carpeta, entramos recursivamente
                    return await getTotalSize(fullPath);
                } else {
                    // Si es archivo, sumamos su tamaño
                    const stats = await fs.stat(fullPath);
                    return stats.size;
                }
            } catch (error) {
                // Si falla un archivo o carpeta específica, devolvemos 0 y seguimos
                console.warn(`No se pudo leer: ${fullPath}`);
                return 0;
            }
        }));

        // Sumamos todos los resultados obtenidos
        totalSize = sizes.reduce((acc, curr) => acc + curr, 0);

    } catch (error) {
        console.error(`Error abriendo el directorio: ${dirPath}`);
        return 0;
    }

    return totalSize;
}

// formatear bytes [dinamico] ('Bytes', 'KB', 'MB', 'GB', 'TB')
function formatBytes(bytes: number, decimals: number = 2) {
    if (bytes === 0) return '0 Bytes';

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + sizes[i];
}

const infoModelsController = new InfoModelsController;
export default infoModelsController;