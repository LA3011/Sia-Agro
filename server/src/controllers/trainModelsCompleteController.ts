import { Request, Response } from 'express';
import pool from '../database';

class TrainModelsCompleteControllers { 
    
    // Leer: DataSet (Todo) 
    public async dataSet(req: Request, res: Response){
        const fs = require('fs');
        const fsP = require('fs').promises;
        const path = require('path'); 
        const pathpackage = require('path');

        const pathBase = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','data');      
        // const pathBase = "C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data"
        
        const rutaCarpeta = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','data','validacion');      
        // const rutaCarpeta = `${pathBase}/validacion`;
        const cantidadArchivosVal = contarArchivosEnDirectorioVal(rutaCarpeta);

        function contarArchivosEnDirectorioVal(ruta:any) {
            try{
                let totalArchivos = 0;
                function explorarDirectorio(directorio:any) {
                    const archivos = fs.readdirSync(directorio);
                    archivos.forEach((archivo:any) => {
                        const rutaCompleta = path.join(directorio, archivo);
                        const infoArchivo = fs.statSync(rutaCompleta);

                        if (infoArchivo.isFile()) {
                            totalArchivos++;
                        } else if (infoArchivo.isDirectory()) {
                            explorarDirectorio(rutaCompleta); // Explora subdirectorios de forma recursiva
                        }
                    });
                }

                explorarDirectorio(ruta);
                return totalArchivos;
            }catch{
                console.error('Error al leer dataSet');
            }
        }

        const cantidadArchivosEnt = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','data','entrenamiento');      
        // const cantidadArchivosEnt = contarArchivosEnDirectorioEnt(`${pathBase}/entrenamiento`);
        function contarArchivosEnDirectorioEnt(ruta:any) {
        let totalArchivos = 0;

        function explorarDirectorio(directorio:any) {
            const archivos = fs.readdirSync(directorio);
            archivos.forEach((archivo:any) => {
                const rutaCompleta = path.join(directorio, archivo);
                const infoArchivo = fs.statSync(rutaCompleta);
                if (infoArchivo.isFile()) {
                    totalArchivos++;
                } else if (infoArchivo.isDirectory()) {
                    explorarDirectorio(rutaCompleta); // Explora subdirectorios de forma recursiva
                }
            });
        }
            explorarDirectorio(ruta);
            return totalArchivos;

        }

        contarCarpetas(rutaCarpeta);
        async function contarCarpetas(directorio:any) {
            try {
                const elementos = await fsP.readdir(directorio, { withFileTypes: true });
                let contadorCarpetas = 0;
        
                for (const elemento of elementos) {
                    if (elemento.isDirectory()) {
                        contadorCarpetas++;
                    }
                }
        
                // console.log(`Total de carpetas: ${contadorCarpetas}`);
                return contadorCarpetas; // Devuelve el valor de contadorCarpetas

            } catch (error:any) {
                console.error('Error al leer el directorio:', error.message);
                return -1; // Devuelve un valor negativo para indicar un error
            }
        }
        const totalCarpetas = await contarCarpetas(rutaCarpeta);
                

        const data = {
            "validacion" : cantidadArchivosVal,
            "entrenamiento" : cantidadArchivosEnt,
            // "total" : cantidadArchivosVal + cantidadArchivosEnt,
            "directorios" : totalCarpetas
        };
        res.json(data);

        
    }

    // Configurar: Entrenamiento [./entrenamientoModels/entrenar.py]
    public async autoConfig(req: Request, res: Response){

        try {
            // Data
        const dataset = req.body[0];
        const batch_size = 32
        const epocas = 100
        const archVal = dataset.validacion
        const archEnt = dataset.entrenamiento
        const clases = dataset.directorios 

        // Formula
        const pasosConfig = ( archEnt / batch_size )      // <== (total_muestras_train/batch_size)
        const pasosValConfig = ( archVal / batch_size )   // <== (total_muestras_val/batch_size)

        const fs = require("fs");
        const pathpackage = require('path');
        
        const ruta = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','entrenamientoModels','entrenar.py');      
        // const ruta = "C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/entrenamientoModels/entrenar.py";

        const pasos = Math.round(pasosConfig);
        const palabraBuscada = "pasos = ";
        const palabraBuscada2 = "pasos_validacion = ";
        const palabraBuscada3 = "clases = "
        const pasosValidacion = Math.round(pasosValConfig);

        // Autoconfig();
        // function Autoconfig() {
        //     fs.readFile(ruta, "utf8", (err:any, contenido:any) => {
        //         if (err) {
        //             console.error("Error al leer el archivo:", err);
        //         } else {
                    
        //             // Busca la palabra "pasos=" y reemplaza el número
        //             const nuevoContenido = contenido.replace(
        //                 new RegExp(`${palabraBuscada}\\d+`),
        //                 `${palabraBuscada}${pasos}`
        //             );

        //             // Busca la palabra "pasos_validacion=" y reemplaza el número
        //             const nuevoContenido2 = nuevoContenido.replace(
        //                 new RegExp(`${palabraBuscada2}\\d+`),
        //                 `${palabraBuscada2}${pasosValidacion}`
        //             );

        //             // Busca la palabra "clases=" y reemplaza el número
        //             const nuevoContenido3 = nuevoContenido2.replace(
        //                 new RegExp(`${palabraBuscada3}\\d+`),
        //                 `${palabraBuscada3}${clases}`
        //             );
 
        //             // Escribe el contenido modificado en el archivo
        //             fs.writeFile(ruta, nuevoContenido3, (err:any) => {
        //                 if (err) {
        //                     console.error("Error al escribir en el archivo:", err);
        //                 } else {
        //                     // Exito
        //                 }
        //             });

        //         }
        //     });
        // } 

        // const config = {
        //     "data" : {
        //         "dataset-validacion" : archVal,
        //         "dataset-entrenamiento" : archEnt,
        //         "clases" : clases
        //     },
        //     "pasos" : pasos,
        //     "pasosVal" : pasosValidacion,
        //     "batch_size" : batch_size,
        //     "epocas" : epocas
        // }
        
        res.json(ruta);   

        }catch{
            res.status(500).json('Error interno del servidor: Descomprimir Expansion' );
        }

         
    }

    // Ejecutar: Entrenando Model 
    public async entren(req: Request, res: Response){
        try{
            const pathpackage = require('path');
            const locationPy = pathpackage.join(__dirname, '..','..','..', 'InteligenciaArtificial','entrenamientoModels','entrenar.py');          
            const { spawn } = require('child_process');

            function runPythonScript() {
                return new Promise((resolve, reject) => {
                    const python = spawn('python', [locationPy]);
                    let scriptOutput = '';

                    python.stdout.on('data', (data:any) => {
                        scriptOutput += data.toString();
                    });

                    python.on('close', (code:any) => {
                        if (code === 0) {
                            // Response 
                            resolve(scriptOutput);
                        } else {
                            reject(new Error(`Python script exited with code ${code}`));
                        }
                    });
                });
            }

            // Example usage:
            runPythonScript().then((output) => {
                res.json(output);
            }).catch((error) => {
                res.status(500).json({ TestErrorLA: error.message });
            });


        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor: Python' });
        } 

    } 

    public async time(req: Request, res: Response){
        try {
            const {time} = req.params
            const sql = await pool.query(`INSERT INTO modelo (tiempo) VALUES ('${time}')`)
            res.json(sql)
        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor: tiempo Entrenamiento' });
            
        }
    }

    public async getTime(req: Request, res: Response){
        try {
            const timeModelo = await pool.query(`SELECT * FROM modelo ORDER BY id_modelo DESC LIMIT 1`)
            res.json(timeModelo.rows[0])

        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor: tiempo Entrenamiento' });
            
        }

    }

}
const trainModelsComplete = new TrainModelsCompleteControllers;
export default trainModelsComplete;