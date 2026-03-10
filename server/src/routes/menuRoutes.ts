import express, { Router } from 'express';
import menuController from '../controllers/menuController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {
        // CRUD: Usuarios
        this.router.get('/programas', menuController.listprogramas);         // Listar Usuario(s)
        this.router.get('/subprogramas', menuController.listsubprogramas);         // Listar Usuario(s)
      
    }

}

export default new GameRoutes().router;

