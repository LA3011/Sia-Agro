import { Request, Response } from 'express';
import pool from '../database';

class ArtifyCancelController { 
    
  // Cancelar: ProcesoCMD entrenamiento.py
  public async killProcess(req: Request, res: Response) {
    try {

          const { exec } = require('child_process'); // Módulo para crear y controlar procesos secundarios
          const path = require('path'); // Módulo para manejar y transformar rutas de archivo
          const os = require('os'); // Módulo para obtener información del sistema operativo
          const sistemaOperativo = os.platform(); // Determinar S.O en que se este ejecutando el cod.

          const procesoAEliminar = 'python.exe'; // Nombre del proceso en Windows
          const rutaRelativaProcesoAEliminar = path.join(__dirname, '..', '..', '..','InteligenciaArtificial', 'entrenamientoModels', 'entrenar.py'); // Ruta del proceso en Linux

          if (sistemaOperativo === 'win32') {
              // Código específico para Windows
              exec(`taskkill /F /IM ${procesoAEliminar}`, (error:any, stdout:any, stderr:any) => {
                  if (error) {
                      console.error(`Error al detener el proceso en Windows: ${stderr}`);
                      return;
                  }
                  // console.log(`Proceso ${procesoAEliminar} detenido correctamente en Windows`);
              });
          
          } else if (sistemaOperativo === 'linux') {
              // Código específico para Linux
              exec(`pkill -f "${rutaRelativaProcesoAEliminar}"`, (error:any, stdout:any, stderr:any) => {
                  if (error) {
                      console.error(`Error al detener el proceso en Linux: ${stderr}`);
                      return;
                  }
                  // console.log(`Proceso con ruta ${rutaRelativaProcesoAEliminar} detenido correctamente en Linux`);
              });
          
          } else {
              console.error('Sistema operativo no soportado');
          }

      res.json({ message: `Proceso detenido correctamente` }); // Envía una respuesta al cliente

        
    } catch (error) {
        res.status(500).json({ error: 'Error al detener el proceso' });               // Retorno: Msj Error Servidor / Petición

    }
  }

  // Verificación: Si se está Ejecutando otro Entrenamiento ( !No puede Haber mas de 1 Entrenamiento ) 
  public async verifyProcess(req: Request, res: Response){
    try {
        const os = require('os'); // Importar módulo para obtener información del sistema
        const ps = require('ps-node'); 
        const plataforma = os.platform(); // Obtener la plataforma ('linux', 'win32', etc.)
    
        if (plataforma === 'linux') {
            // Código para Linux
            const procesoObjetivo = '/home/la/.pyenv/versions/3.9.13/bin/python'; // Nombre o ruta exacta del proceso
            ps.lookup({ psargs: 'ux' }, function(err:any, resultList:any) {
                if (err) {
                    throw new Error(err); // Manejo: Errores durante la Verificación
                }
                if (resultList && resultList.length > 0) {
                    const procesosCoincidentes = resultList.filter((obj:any) => 
                        Object.values(obj).some((valor) => String(valor) === procesoObjetivo) // Coincidencia exacta
                    );
                    if (procesosCoincidentes.length > 0) {
                        const numProcesos = procesosCoincidentes.length; // Contar procesos coincidentes
                        res.json(true);
                        console.log(numProcesos)
                    } else {
                        console.log(0)
                        res.json(false); // Retorno: Proceso no encontrado
                    }
                } else {
                    res.json(false); // Retorno: Ningún proceso activo
                }
            });
        } else if (plataforma === 'win32') {
            // Código para Windows
            const command = "python"; // Nombre del comando a buscar
            ps.lookup({ command, psargs: 'ux' }, function(err:any, resultList:any) {
                if (err) {
                    throw new Error(err); // Manejo: Errores durante la Verificación
                }
                if (resultList && resultList.length > 0) {
                    const procesosCoincidentes = resultList.filter((obj:any) => 
                        Object.values(obj).some((valor) => String(valor) === command) // Coincidencia exacta
                    );
                    if (procesosCoincidentes.length > 0) {
                        const numProcesos = procesosCoincidentes.length; // Contar procesos coincidentes
                        res.json(true);
                    } else {
                        res.json(false); // Retorno: Proceso no encontrado
                    }
                } else {
                    res.json(false); // Retorno: Ningún proceso activo
                }
            });
        } else {
            res.json(true); // Si no es Linux ni Windows
        }
    } catch (err) {
        res.status(500).json({ error: 'Error durante la verificación y terminación de procesos.', details: err });
    }
    

    

  }



}
const artifyCancelController = new ArtifyCancelController;
export default artifyCancelController;