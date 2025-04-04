import { Request, Response } from 'express';
import pool from '../database';

class ArtifyModelsController {  
 
  // Distribuir: 20% del zipProcess.zip
  public async dist(req: Request, res: Response) {
    try { 

      // Calcular: Cantidad Arch del ZIP [./data/entrenamiento/tag/zipProcess.zip]
      const data = req.body;
      const nameDir = req.body[0].nameDir;   
      const tag = req.body[0].tag;
      
      const pathpackage = require('path');

      // /home/la/Escritorio/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/kjkjk/Plagas-Hongos
      const test = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag, nameDir);
      
      const path1 = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag);
      const pathZip = pathpackage.join(__dirname, '..','..', '..','InteligenciaArtificial', 'data', 'entrenamiento', tag, 'zipProcess.zip');
      // const path1 = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}`

      const AdmZip = require('adm-zip');                  // Uso: Modulo, Compresión y descompresión de datos en formato ZIP
      const zip = new AdmZip(pathZip);  // Cargar: el archivo zip
      const zipEntries = zip.getEntries();                // Obteniendo: las entradas del archivo zip
      const numberOfFiles = zipEntries.length;            // Cargar: La cantidad de archivos en el zip
        

      // Calcular | Mover: 20% de esas Imagenes 
      const fs = require('fs');                         // Uso: Modulo, interaccion archivos PC: leer, escribir, actualizar, etc
      const path = require('path');                     // Uso: Modulo, trabajar con rutas de archivos y directorios.
      const sourceDir = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag, nameDir);
      
      const destinationDir = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'validacion', tag);
      
      fs.readdir(sourceDir, (err:any, files:any) => {   // Leer: archivos del directorio de origen
        if (err) {
          console.error('Error al leer el directorio', err);
          return;
        }
        const filesToMove = Math.floor(files.length * 0.2); // Calcular: 20% de los archivos
        for (let i = 0; i < filesToMove; i++) {             // Mover cada archivo en base a ese %
          const file = files[i];
          const sourceFile = path.join(sourceDir, file);
          const destFile = path.join(destinationDir, file);
          fs.rename(sourceFile, destFile, (err:any) => {    // Cambiar los archivos de carpeta
            if (err) {
              console.error(`ErrorDist40%: al mover el archivo ${file}`/**,err*/);
            } 
          });
        }
      });


      // Cargando Datos Operaciondes
      const dataDir = {
          'cantArch' : numberOfFiles,
          'nameZip'  : 'zipProcess.zip',
          'nameDest' : path1,
          'nameDir'  : sourceDir,
      }


      // Retorno: Datos Operaciones
      res.json(dataDir)

    } catch (error) {
      // Retorno: Msj Error Servidor / Petición
      res.status(500).json({ error: 'Error interno del servidor: Distribuyendo 20%' }); 
    }
  } 

  // Distribuir: 60% del zipProcess.zip | Eliminar: Archivo/Carpeta Sobrante
  async limpMove(req: Request, res: Response){ 
    try{
      const fs = require('fs'); 
      const path = require('path');
      const sourceDir = req.body[0].nameDir;
      const tag = req.body[0].tag;

      const pathBase = path.join(__dirname, '..','..', '..','InteligenciaArtificial', 'data', 'entrenamiento', tag);
      // const pathBase = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}`
      
      const path1 = path.join(__dirname, '..','..', '..','InteligenciaArtificial', 'data', 'entrenamiento', tag, sourceDir);
      // const path1 = pathBase + "/" + sourceDir

      const path1Zip = path.join(__dirname, '..','..', '..','InteligenciaArtificial', 'data', 'entrenamiento', tag, 'zipProcess.zip');
      // const path1Zip = pathBase + '/zipProcess.zip'
      const destinationDir60 = path1;
  
      fs.readdir(path1, (err:any, files:any) => {                                // Leer archivos del directorio de origen
        if (err) {
            console.error('Error al leer el directorio', err);
            return;
        }
  
        const filesToMove = Math.floor(files.length * 1);                        // Calcular: 80% archivos (restante)
        for (let i = 0; i < filesToMove; i++) {                                  // Mover cada archivo
          const file = files[i];
          const sourceFile = path.join(path1, file);
          const destFile = path.join(pathBase, file);
          fs.rename(sourceFile, destFile, (err:any) => {                       // Cambiar los archivos de carpeta
            if (err) {
                console.error(`ErrorDistribucion: al mover el archivo ${file}`, err);
            } else {
                
              // Eliminar Carpeta Sobrante que deja despues de descomprimir                 
              const fs2 = require('fs');
              fs2.rm( path1, { recursive: true, force: true }, (err:any) => {
              });
              
              // Eliminar zipProcess.zip 
              const fs3 = require('fs');
              fs3.unlink(path1Zip, (err:any) => { });
            }
          });
        }
      });


    }catch{
      res.status(500).json({ error: 'Error interno del servidor: Distribuyendo 60%' }); 
    }
      
      
      res.json('listo')
  }
 
  // Modificar: Archivo predecir_umbral.py (Agregar Nueva Etiqueta) 
  public async write(req: Request, res: Response)  {
    try{      
      const { tag } = req.params;
      const fs = require('fs');
      const pathackage = require('path');
      

      const directorio = pathackage.join(__dirname, '..','..', '..','InteligenciaArtificial', 'data', 'entrenamiento');
      // const directorio = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento`
      
      const archivos = fs.readdirSync(directorio);
      const cantidadArchivos = archivos.length - 1; // 5 dir - 1 newDir

      const path = pathackage.join(__dirname, '..','..', '..','InteligenciaArtificial', 'inferenciasPredict', 'predecir_umbral.py');
      // const path = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/inferenciasPredict/predecir_umbral.py`
      
      // Datos: Plantillar
      const plantilla = `\n        #{{elif}}\n            #{{return}}`
      const datos = {
          elif: `#Entrenarelif respuesta == ${ cantidadArchivos }:`,
          return: `#Entrenarreturn print('${tag}')${plantilla}`
      };

      // Leer la plantilla
      fs.readFile( path, 'utf8', (err:any, contenido:any ) => {
        if (err) {
          console.error('Error al leer la plantilla:', err);
          return;
        }

        // Reemplazar los marcadores con los valores reales
        const contenidoFinal = contenido
          .replace('#{{elif}}', datos.elif)
          .replace('#{{return}}', datos.return)

        // Escribir el archivo final
        fs.writeFile( path, contenidoFinal, 'utf8', (err:any) => {
          if (err) {
              console.error('Error al escribir el archivo final:', err);
          } 
        });
      });        

      // Retorno: Numero Carpetas [.data/entrenamiento]
      res.json(`${cantidadArchivos}`)
      
    } catch (error) {
        res.status(500).json({ error: 'Error interno del servidor: Modificando Archivo' });
    }
  } 

}
const artifyModelsController = new ArtifyModelsController;
export default artifyModelsController; 