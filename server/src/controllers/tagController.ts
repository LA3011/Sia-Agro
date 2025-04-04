import { Request, Response } from 'express';
import pool from '../database';

class TagController { 
    
    // Listar: Etiquetas 'Nombres' [./data/validacion] 
    public async listTags(req: Request, res: Response){ 
        try{
            const fs = require('fs');
            const path = require('path');

            // Crear la ruta multiplataforma
            const ruta = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'validacion');

            // Leer el contenido del directorio
            const elementos:any = fs.readdirSync(ruta);
            const directorios:any = [];

            // Iterar sobre cada elemento en el directorio
            elementos.forEach((elemento:any) => {
                const info = fs.lstatSync(path.join(ruta, elemento));
                if (info.isDirectory()) {
                    directorios.push(elemento);
                }
            });

            res.json(directorios);
  
        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor: Listando Etiquetas' });
        }
    } 

    // Mover: zipProcess [./Entrenamiento/tag]
    public async MoveExpandTag(req: Request, res: Response)  {
        const { tag } = req.params;
        const fs = require('fs');

        const pathpackage = require('path');
        const oldPath = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento','zipProcess.zip');      
        // const oldPath = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/zipProcess.zip';
        const newPath = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag,'zipProcess.zip'); 
        console.log(newPath)     
        // const newPath = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}/zipProcess.zip`;

        try{
            fs.rename(oldPath, newPath, (err:any) => {
                if (err) {
                  console.error('Error: al mover el archivo [./entrenamiento/zipProcess.zip] -> [./entrenamiento/tag/zipProcess.zip]', err);
                } else {
                  res.json('Zip-movido-extosamente-Expandir')
                }
            });
            
        } catch (error) {
            res.status(500).json({ error: 'ERROR-Zip-movido-extosamente-Expandir' });
        }
    } 

    // Eliminar: Etiqueta { Eliminar Carpetas |  Modificar: Archivo "predecir_umbral.py" }
    public async deleteTags(req: Request, res: Response)  {

        const { tag } = req.params;
        const fs3 = require('fs-extra');

        const pathpackage = require('path');
        const rutaBase = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial');      
        // const rutaBase = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial'
        const removeVal = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','data','validacion', tag);      
        const removeEnt = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag);      



        // Eliminar Carpetas 
        eliminarCarpetas(rutaBase, tag);
        function eliminarCarpetas(rutaBase:any, tag:any) {
            fs3.remove(removeVal).then(() => {
                // console.log('Carpeta de validación eliminada');
            });
            fs3.remove(removeEnt).then(() => {
                // console.log('Carpeta de entrenamiento eliminada');
            });
        }
    
        // Modificar: Archivo "predecir_umbral.py" (Eliminacion) + (Organización)
        const fs4 = require('fs').promises;
        const archivo1 = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'inferenciasPredict', 'predecir_umbral.py');      
        // const archivo1 = `${rutaBase}/inferenciasPredict/predecir_umbral.py`;
        eliminarLineaYAnterior(`${tag}`);

        async function eliminarLineaYAnterior(palabra:any) {
            try {
                const contenido = await fs4.readFile(archivo1, 'utf-8');
                const lineas = contenido.split('\n');
                const indicePalabra = lineas.findIndex( (linea:any) => linea.includes(palabra));

                if (indicePalabra > 0) {
                lineas.splice(indicePalabra - 1, 2);                    // Elimina la línea anterior y la línea con la palabra
                await fs4.writeFile(archivo1, lineas.join('\n'));
                
                // Function Organizar Etiquetas
                reorganizarEtiquetas();

            } else {
                // console.log('La palabra no se encontró o no hay línea anterior.');
            }
                
            } catch (error) {
                console.error('Ocurrió un error al eliminar la línea:', error);
            }
        }

        // Organizar: de manera ascendente en el arch. "predecir_umbral.py" 
        async function reorganizarEtiquetas() {
            try {
                const contenido = await fs4.readFile(archivo1, 'utf8');
                const regex = /==\s*\d+/g;
                let nuevoNumero = 0;

                const nuevoContenido = contenido.replace(regex, (match:any) => {
                    const numeroActual = parseInt(match.match(/\d+/)[0], 10);
                    const numeroReemplazo = nuevoNumero;
                    nuevoNumero++;
                    return `== ${numeroReemplazo}`;
                });

                await fs4.writeFile(archivo1, nuevoContenido, 'utf8');
                // console.log('Etiquetas reorganizadas correctamente.');

            } catch (error) {
                console.error('Ocurrió un error al reorganizar las etiquetas:', error);
            }
        }
        // Retorno: Msj Exito 
        res.json('Etiqueta-Eliminada-Exitosamente')
    }

    // Descomprimir: Archivo zipProcess.zip ... 'Expansion'
    public async ExtraExpandTag(req: Request, res: Response)  {
        
        try{

            const tag = req.body[0].tag;
            const AdmZip = require('adm-zip');
            const fs = require('fs');
            const path = require('path');

            const archivoZip = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag, 'zipProcess.zip');      
            // const archivoZip = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}/zipProcess.zip`;
            const directorioDestino = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag);      
            // const directorioDestino = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}`;

            // Descomprime el archivo ZIP
            const zip = new AdmZip(archivoZip);
            zip.extractAllTo(directorioDestino, true);

            // Obtiene el nombre de la carpeta
            const archivosEnDirectorio = fs.readdirSync(directorioDestino);
            const carpetas = archivosEnDirectorio.filter((archivo:any) => {
                return fs.statSync(path.join(directorioDestino, archivo)).isDirectory();
            });

            if (carpetas.length > 0) {
                const nombreCarpeta = carpetas[0];

                // Retorno: Nombre de la "carpeta" (despues de descomprimir)
                res.json(nombreCarpeta) 

            } else {
                console.log('No se encontraron carpetas en el archivo ZIP.');
            }

        }catch{
            res.status(500).json({ error: 'Error interno del servidor: Descomprimir Expansion' });
        }
    }

    // Distribuir: 60% + 40% | Eliminacion: Archivo/Carpeta zipProcess.zip (restante)
    public async DistExpandTag(req: Request, res: Response)  {
        const tag = req.body[0].tag;
        const dir = req.body[0].dir;

        const fs = require('fs');
        const path = require('path');

        const origen = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag, dir);      
        const destino1 = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag);      
        const destino2 = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'validacion', tag);      

        fs.readdir(origen, (err:any, files:any) => {
            if (err) {
                console.error('Error al leer la carpeta de origen:', err);
                return;
            }

            // Calcula el número de archivos a mover
            const numArchivosTotal = files.length;
            const numArchivos60Porciento = Math.ceil(numArchivosTotal * 0.8);
            const numArchivos40Porciento = numArchivosTotal - numArchivos60Porciento;

            // Mueve el 60 % de los archivos al destino1
            for (let i = 0; i < numArchivos60Porciento; i++) {
                const archivo = files[i];
                const origenPath = path.join(origen, archivo);
                const destinoPath = path.join(destino1, archivo);

                fs.rename(origenPath, destinoPath, (err:any) => {
                    if (err) {
                        console.error(`Error al mover ${archivo} a ${destino1}:`, err);
                    } else {
                    // exito
                    }
                });
            }

            // Mueve el 40 % restante al destino2
            for (let i = numArchivos60Porciento; i < numArchivosTotal; i++) {
                const archivo = files[i];
                const origenPath = path.join(origen, archivo);
                const destinoPath = path.join(destino2, archivo);
                fs.rename(origenPath, destinoPath, (err:any) => {
                    if (err) {
                        console.error(`Error al mover ${archivo} a ${destino2}:`, err);
                    }
                });
            }
        });

        // Eliminando Archivo.zip (restante)
        const Rutabase = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag);      
        // const Rutabase = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}`
        const Rutabase2 = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag,'zipProcess.zip');      
        fs.unlinkSync(Rutabase2);   
        
        // Eliminando Dir (restante)
        const fsPromises = require('fs/promises');
        const dirPath = path.join(__dirname, '..','..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', tag, dir);      
        // const dirPath = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${tag}/${dir}`;
        async function removeDirectory(dirPath:any) {
            try {
                await fsPromises.rmdir(dirPath, { recursive: true });
            } catch (error:any) {
                console.error(`Error-eliminar-directorio-Expandir-${error.message}`);
            }
        }

        removeDirectory(dirPath);

        // Retorno: Msj Exito Distribucion Y Limpieza
        res.json("distribuido-40%-60%-Limp")
    }
    

}
const tagController = new TagController;
export default tagController;