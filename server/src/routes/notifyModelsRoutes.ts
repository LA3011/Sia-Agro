import express, { Router } from 'express';
import notifyController from '../controllers/notifyModelsController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }
 
    config() {
        // Consulta: Modicar Norficacione(s) en BD
        this.router.put('/:id', notifyController.update); 
        this.router.get('/', notifyController.list);         
    }

}

export default new GameRoutes().router;

