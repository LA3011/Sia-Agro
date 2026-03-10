import { Router } from 'express';
import express, { Application } from 'express'
import artifyCancelController from '../controllers/artifyCancelController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {  
        this.router.get('/', artifyCancelController.killProcess);           // Cancelar: ProcesoCMD entrenamiento.py
        this.router.get('/:nada', artifyCancelController.verifyProcess);    // Verificación: Ejecución de otro Entrenamiento

    } 

}

export default new GameRoutes().router;
