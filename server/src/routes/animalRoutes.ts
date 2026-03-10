import { Router } from 'express';
import express, { Application } from 'express'
import animalController from '../controllers/animalController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {  
        this.router.get('/', animalController.getList); // listar: Animales Venta
        this.router.get('/pastoreo', animalController.getList); // listar: Animales Venta
    } 

}

export default new GameRoutes().router;
