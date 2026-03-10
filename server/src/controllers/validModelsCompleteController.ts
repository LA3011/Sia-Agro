import { Request, Response } from 'express';
import pool from '../database';

class ValidModelsCompleteControllers { 


  // Validar: Estructura Archivo ZIP
  //! descactivado
  public async validZip(req: Request, res: Response){
  
    // Importando Modulos
    const fs = require('fs');
    const path = require('path');
    const unzipper = require('unzipper');
    
    // Función para verificar si un archivo es una imagen
    const isImage = (fileName:any) => {
      try{
        const ext = path.extname(fileName).toLowerCase();
        return ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.tiff', '.webp'].includes(ext);
      
      }catch{
        res.status(500).json({ error: 'Error interno del servidor: Estructura Archivo ZIP' });
      }
    };
    
    // Función principal para verificar el contenido del .zip
    const verifyZip = async (zipP:any) => {
      try {
        const directory = await unzipper.Open.file(zipPath);
        const files = directory.files;
    
        // Verificar que no hay carpetas en el .zip
        const folders = files.filter((file:any) => file.type === 'Directory');
        if (folders.length > 0) {
            // res.json('El archivo .zip no debe contener carpetas.')
            // res.json(false) desactivado
            res.json(true);
          throw new Error('El archivo .zip no debe contener carpetas.');
        }
    
        // Verificar que todos los archivos son imágenes
        const allImages = files.every((file:any) => file.type === 'File' && isImage(file.path));
        if (!allImages) {
            // res.json('El archivo .zip debe contener solo archivos de imagen.')
            res.json(false);
            
          throw new Error('El archivo .zip debe contener solo archivos de imagen.');
        }
    
        // res.json('El archivo .zip es válido.');
        res.json(true);

    } catch (error:any) {
      console.error('Error:', error.message);

    }
  };
        
  // Ruta al archivo .zip 
  const zipPath = path.join(__dirname, '..','..','..', 'InteligenciaArtificial','data','entrenamiento','zipProcess.zip');      
  // const zipPath = 'C:/Users/LaptopDell/Desktop/siaAgro/Proyecto/InteligenciaArtificial/data/entrenamiento/zipProcess.zip';
  verifyZip(zipPath);        
     
  }

}
const validModelsComplete = new ValidModelsCompleteControllers;
export default validModelsComplete;