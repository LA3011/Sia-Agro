import { Router } from 'express';
import trainModelsController from '../controllers/trainModelsController';
import express, { Application } from 'express'

class GameRoutes {

    router: Router = Router();

    constructor() { 
        this.config();
    } 

    config() {  
        // IA
        this.router.get('/:classes', trainModelsController.writeEntren); // Modificar: Archivo entrenar.py (Define la Cantidad de Clases para este arch.) 
        this.router.post('/', trainModelsController.completed);          // Eliminar: Modelos Viejos | Mover: Modelos Nuevos 
        this.router.put('/', trainModelsController.move);                // ~ Genera: Carpetas [Entrenamiento + Validacion] | Mueve: Archivo zipProcess.zip --> Entrenamiento
    }  

}

export default new GameRoutes().router;
