import { Router } from 'express';
import ValidModelsCompleteController from '../controllers/validModelsCompleteController';
import express, { Application } from 'express'

class GameRoutes {

    router: Router = Router();

    constructor() { 
        this.config();
    } 

    config() {  
        this.router.get('/', ValidModelsCompleteController.validZip);  // Validar: Estructura Archivo ZIP
    }  

}

export default new GameRoutes().router;
