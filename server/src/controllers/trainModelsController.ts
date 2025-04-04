import { Request, Response } from 'express';
import pool from '../database';
 
class TrainModelsControllers {  
     
  // Modificar: Archivo entrenar.py (Define la Cantidad de Clases para este arch.)
  public async writeEntren(req: Request, res: Response)  {
    try{
        const { classes } = req.params;
        const clases = Number(classes) + 1; // +1 = Nuevo Dir Creado
        const fs = require('fs'); 
        const pathpackage = require('path');

        const path = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','entrenamientoModels','entrenar.py');      
        console.log(path)
        // const path = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/entrenamientoModels/entrenar.py`

        // Datos
        const datos = {
            classes: `clases = ${clases}\n#{{classes}}`,
        }; 
        // Leer la plantilla
        fs.readFile( path, 'utf8', (err:any, contenido:any ) => {
            if (err) {
                console.error('Error al leer la plantilla:', err);
                return;
            }  
            // Reemplazar los marcadores con los valores reales
            const contenidoFinal = contenido
                .replace('#{{classes}}', datos.classes)
            // Escribir el archivo final
            fs.writeFile( path, contenidoFinal, 'utf8', (err:any) => {
                if (err) {
                    console.error('Error al escribir el archivo final:', err);
                } else {
                  //Exito 
                }
            });
        });        
      res.json(`${clases}`)
    } catch (error) {
        res.status(500).json({ error: 'Error interno del servidor: Modificando Archivo Entrenamiento.py' });
    }
  } 

  // Eliminar: Modelos Viejos | Mover: Modelos Nuevos 
  public async completed(req: Request, res: Response) {
    const pathpackage = require('path');
    const path2 = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','modelos_create');      
    const path3 = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','modelos_create', 'pesos.h5');    
    const path4 = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','modelos_create', 'models.h5');      
    // const path2 = "C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/modelos_create"

    const fs = require('fs');
    // Eliminando: Modelos Viejos
    // fs.unlinkSync(path3);   
    fs.unlinkSync(path4);  
    // Mover: Modelos Nuevos [./modelos_create/stagingArea]
    const sourceDir = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','modelos_create', 'stagingArea');      
    // const sourceDir = `${path2}/stagingArea`; 
    const destinationDir = path2;
    const path = require('path');
          
      fs.readdir(sourceDir, (err:any, files:any) => {
        if (err) {
          console.error('Error al leer el directorio', err);
          return;
        }
        // Mover cada archivo (2)
        for (let i = 0; i < 1; i++) {
          const file = files[i];
          const sourceFile = path.join(sourceDir, file);
          const destFile = path.join(destinationDir, file);
          // Cambiar los archivos de carpeta
          fs.rename(sourceFile, destFile, (err:any) => {
            if (err) {
              console.error(`ErrorModel: al mover el archivo ${file}`, err);
            } 
          });
        }
      });

    res.json('Proceso Completado')
  } 

  // ~ Genera: Carpetas [Entrenamiento + Validacion] | Mueve: Archivo zipProcess.zip --> Entrenamiento
  public async move(req: Request, res: Response): Promise<void> {
    try{

      //  Creando Dir`s
      const data = req.body;
      const name = req.body[0].name;
      const path = require('path');
      const fs = require('fs');

      const rutaEntrenamiento = path.join(__dirname, '..','..','..', 'InteligenciaArtificial','data', 'entrenamiento');        
      const rutaValidacion = path.join(__dirname, '..','..','..', 'InteligenciaArtificial','data', 'validacion');        
      // const rutaEntrenamiento = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento';
      // const rutaValidacion = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/validacion';

      fs.mkdirSync(path.join(rutaEntrenamiento, name), { recursive: true }); // Crea la carpeta Entrenamiento
      fs.mkdirSync(path.join(rutaValidacion, name), { recursive: true }); // Crea la carpeta Validacion

      // Moviendo Zip a Dir`s
      const oldPath = path.join(__dirname, '..','..','..', 'InteligenciaArtificial','data','entrenamiento', 'zipProcess.zip');        
      const newPath = path.join(__dirname, '..','..','..', 'InteligenciaArtificial','data','entrenamiento', name,'zipProcess.zip');        
      // const oldPath = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/zipProcess.zip';
      // const newPath = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${name}/zipProcess.zip`;

      fs.rename(oldPath, newPath, (errMover:any) => {
        if (errMover) {
          console.error('Error: al mover el archivo [./entrenamiento/zipProcess.zip] --> [./entrenamiento/${name}/zipProcess.zip]' /**, errMover */);
        } else {
          res.json({ 
            massageDir: 'Directorio Creado',
            massageMov: 'Directorio Movido',
            dir: name
          });  
        }
      });
        
    } catch (error) { 
        //Error
        res.status(500).json({ error: 'Error interno del servidor: Creando/Mover' });
    } 
  } 

}
const trainModelsControllers = new TrainModelsControllers;
export default trainModelsControllers;