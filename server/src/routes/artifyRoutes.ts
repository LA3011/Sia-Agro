import { Router } from 'express';
import artifyController from '../controllers/artifyController';
import express, { Application } from 'express'

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {
        // Ejecutar: Deteccion Python
        this.router.get('/', artifyController.artify);    
        // Descomprimir: Arch.zip [.data/entrenamiento/tag/zipProcess.zip] | Retorna: 'Nombre' de la Carpeta Descomprimida
        this.router.put('/', artifyController.desc);  
        // testing
        this.router.get('/test/:patch', artifyController.test);  

    } 

}

export default new GameRoutes().router;
