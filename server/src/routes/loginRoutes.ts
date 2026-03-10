import express, { Router } from 'express';
import loginController from '../controllers/loginController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }
 
    config() {
        // Consultar: Existencia Usuario(s) en BD
        this.router.get('/', loginController.list);     // Listar Usuario(s)
        // this.router.post('/', loginController.search);  // Consultar: Usuario si Existe en BD
        this.router.post('/Usuario', loginController.searchBD);  // Consultar: Usuario si Existe en BD

    }

}

export default new GameRoutes().router;

