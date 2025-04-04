import { Router } from 'express';
import express, { Application } from 'express'
import cultivoPlagaContriller from '../controllers/cultivoPlagaContriller';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {  
        this.router.get('/', cultivoPlagaContriller.getList);           // listar: Cultivos + Plaga
        this.router.get('/cosechado', cultivoPlagaContriller.getListCosechados);           // listar: Cultivos + Plaga (particular)
        this.router.get('/historial/:id', cultivoPlagaContriller.getHistory);           // listar Historial: Cultivos + Plaga (particular)
        this.router.get('/:id', cultivoPlagaContriller.getOne);         // Mostrar Detalles Cultivos + Plaga
        this.router.put('/:id', cultivoPlagaContriller.update);               // Actualizar Plaga

    } 

}

export default new GameRoutes().router;
