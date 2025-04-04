import { Router } from 'express';
import artifyModelsController from '../controllers/artifyModelsController';
import express, { Application } from 'express'

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    } 

    config() {  
        this.router.post('/', artifyModelsController.dist);      // Distribuir: 20% del zipProcess.zip
        this.router.put('/', artifyModelsController.limpMove);   // Distribuir: 80% del zipProcess.zip | Eliminar: Archivo/Carpeta Sobrante    
        this.router.get('/:tag', artifyModelsController.write);  // ~ Modificar: Archivo predecir_umbral.py (Agregar Nueva Etiqueta)  
    }  

}

export default new GameRoutes().router;
