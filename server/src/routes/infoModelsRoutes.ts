import { Router } from 'express';
import express, { Application } from 'express'
import infoModelsController from '../controllers/infoModelsController';

class GameRoutes { 

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {
        // Monitoreo
        this.router.get('/rendimient', infoModelsController.getRendimient); 
        this.router.get('/detection', infoModelsController.getDetection); 
        this.router.post('/presicion', infoModelsController.createPress); 
        this.router.post('/likes', infoModelsController.createLikes); 

        // Agregar Nueva Inf. Etiqueta
        this.router.get('/:id', infoModelsController.getOne);    // Mostrar Detalles Único Inf. Modelo Etiqueta
        this.router.post('/', infoModelsController.create);      // Almacena Nueva Inf. Modelo Etiqueta
        this.router.put('/:id', infoModelsController.update);    // Actualizar Inf. Modelo Etiqueta
        this.router.delete('/:id', infoModelsController.delete); // Eliminar Inf. Modelo Etiqueta            
        
    } 

}

export default new GameRoutes().router;
