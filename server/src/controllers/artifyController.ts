import { Request, Response } from 'express';
import pool from '../database';

class ArtifyController { 
    
    // Ejecutar: Entrenamiento Python
    public async artify(req: Request, res: Response): Promise<void> { 
        try{
            const path = require('path');

            // Ubicacion Archivo Python
            const pathLocationPy = path.join(__dirname, '..', '..', '..','InteligenciaArtificial', 'inferenciasPredict', 'predecir_umbral.py');
            // const locationPy = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/inferenciasPredict/predecir_umbral.py'
            
            // Ejecutar Codigo de Python desde NodeJS
            const { spawn } = require('child_process');
            const python = spawn('python', [pathLocationPy]);
            let scriptOutput="";
            const datapy = python.stdout.on('data', (data:any) => {
                scriptOutput += data.toString(); 
                // Retorno: C/U printf del Codigo de Python
                res.json(scriptOutput);    
            });
            

        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Python' });
        } 
    } 

    // Descomprimir: Archivo.zip --> zipProcess.zip [.data/entrenamiento/tag/zipProcess.zip] | Retorna: 'Nombre' de la Carpeta Descomprimida
    public async desc(req: Request, res: Response) {
        
        try {

            // Nombre: Zip a Descomprimir 
            const nameZip = 'zipProcess.zip'; 
            const Data = req.body;
            const name = Data[0].dir;

            const fs = require('fs'); 
            const path = require('path');
            const AdmZip = require('adm-zip');
            // Direccion: Archivo.zip a Descomprimir
            const pathLocationPy = path.join(__dirname, '..', '..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', name, nameZip);
            // const pathLocationPy = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${name}/${nameZip}`
            const zip = new AdmZip(pathLocationPy);

            // Descomprimir: contenido en el directorio de destino
            const descomp = path.join(__dirname, '..', '..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', name);
            // const descomp = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${name}`
            zip.extractAllTo(descomp,true);
            const route = path.join(__dirname, '..', '..','..', 'InteligenciaArtificial', 'data', 'entrenamiento', name);
            // const route = `C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/${name}`
            // Nombre: carpeta descomprimida
            const elementos = fs.readdirSync(route); 
            // Retorno: Información 'Nombre' de la Carpeta Descomprimida


            console.log(elementos[0]) // Test Descomprimir EXITOSO
            res.json(elementos[0])


        } catch (error) {
            // Retorno: Msj Error Servidor / Petición
            res.status(500).json({ error: 'Error interno del servidor: Descompimiendo' });
            
        }
    } 



    public async test(req: Request, res: Response) {
        try {
            const path = req.params
            console.log(`---> API TEST`)
            console.log(path)
            res.json("test API")
        } catch (error) {
            res.status(500).json({ error: 'Error interno del servidor: Test' });            
        }
    } 

}
const artifyController = new ArtifyController;
export default artifyController;