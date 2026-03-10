import { Request, Response } from 'express';
import pool from '../database';

class TagModelsCompleteController { 
    
    // Calcular: Cantidad Imagenes todas las Etiquetas  
    public async InfoTag(req: Request, res: Response)  {

        try{

            const { tag } = req.params;
            const fs = require('fs'); 

            const pathpackage = require('path');
            const dirBase = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data');      
            // const dirBase = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data';

            // N~Archivos './Entrenamiento/tag'
            const pathArchivosEntren = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data','entrenamiento',tag);      
            const archivosEntren = fs.readdirSync(pathArchivosEntren);
            const arcEntrenamiento = archivosEntren.length;

            // N~Archivos './Validacion/tag'
            const pathArchivosValid = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data','validacion',tag);      
            const archivosValid = fs.readdirSync(pathArchivosValid);
            const arcValidacion = archivosValid.length;

            console.log(archivosEntren)

            // Data
            const data = {
                "entrenamiento" : arcEntrenamiento,
                "validacion" : arcValidacion
            }

            res.json(data);

        }catch(err) {
            console.error('Error: Visualizar Información Etiqueta');

        }

    } 

    // Modificar: Archivo 'predecir_umbral.py', Eliminando Todos los "#Entrenar" (etiquetas que no han pasado por un entrenamiento)
    public async PredicWrite(req: Request, res: Response)  {

        const fs = require('fs');
        const pathpackage = require('path');
        
        const removeHashes = () => {
            const file = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'inferenciasPredict', 'predecir_umbral.py');      
            // const file = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/inferenciasPredict/predecir_umbral.py';
        try {
            // Leer el contenido del archivo
            const data = fs.readFileSync(file, 'UTF-8');
            // Dividir el contenido por saltos de línea
            const lines = data.split('\n');
            // Eliminar el carácter "#" de cada línea
            const cleanedLines = lines.map( (line:any) => line.replace('#Entrenar', ''));
            // Unir las líneas limpias nuevamente
            const cleanedData = cleanedLines.join('\n');
            // Guardar el resultado en el archivo
            fs.writeFileSync(file, cleanedData, 'UTF-8');

            res.json("DesEtiquetado-#Entrenar")

        } catch (err) {
            console.error(err);
        }
    };
    
    removeHashes();
    

    } 
     

}
const tagModelsCompleteController = new TagModelsCompleteController;
export default tagModelsCompleteController;
