import { Router } from 'express';
import artifyDataSetController from '../controllers/artifyDataSetController'; 
import express, { Application } from 'express'

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {  
        // Obtener: Información 'BD' DeteccionIA
        this.router.get('/:dataSet', artifyDataSetController.dataSet);    
        
    } 

}

export default new GameRoutes().router;
