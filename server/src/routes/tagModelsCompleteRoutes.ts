import { Router } from 'express';
import express, { Application } from 'express'
import tagModelsCompleteController from '../controllers/tagModelsCompleteController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {  
        // Etiquetas
        this.router.get('/', tagModelsCompleteController.PredicWrite); // Modificar: Archivo 'predecir_umbral.py', Eliminando Todos los "#Entrenar"
        this.router.get('/:tag', tagModelsCompleteController.InfoTag); // Calcular: Cantidad Imagenes todas las Etiquetas
    } 

}

export default new GameRoutes().router;
