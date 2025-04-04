import { Router } from 'express';
import express, { Application } from 'express'
import tagController from '../controllers/tagController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {  
        // Etiquetas
        this.router.get('/', tagController.listTags);           // Listar: 'Nombres' Etiquetas
        
        this.router.get('/:tag', tagController.MoveExpandTag);  // Mover: zipProcess (Expanción)
        this.router.put('/', tagController.ExtraExpandTag);     // Descomprimir: zipProcess.zip (Expanción)
        this.router.post('/', tagController.DistExpandTag);     // Distribuir: 80% + 20% | Eliminacion Arc/Dir (restantes)
        this.router.delete('/:tag', tagController.deleteTags);  // Eliminar: Etiqueta { Eliminar Carpetas |  Modificar: Archivo }
    } 

}

export default new GameRoutes().router;
