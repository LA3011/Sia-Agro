import { Router } from 'express';
import trainModelsCompleteController from '../controllers/trainModelsCompleteController';
import express, { Application } from 'express'

class GameRoutes {

    router: Router = Router();

    constructor() { 
        this.config();
    } 

    config() {  
        // IA
        this.router.get('/', trainModelsCompleteController.dataSet);    // Leer: DataSet (Todo)
        this.router.put('/', trainModelsCompleteController.autoConfig); // Configurar: Entrenamiento [./entrenamientoModels/entrenar.py]
        this.router.post('/', trainModelsCompleteController.entren);    // Ejecutar: Entrenando Model 
        this.router.get('/time', trainModelsCompleteController.getTime);  // Mostrar tiempo entrenamiento: Entrenando Model 
        this.router.get('/:time', trainModelsCompleteController.time);  // Modificar tiempo entrenamiento: Entrenando Model 
    
    }  

}

export default new GameRoutes().router;
