import express, { Router } from 'express';
import usersController from '../controllers/usersController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {
        // CRUD: Usuarios
        this.router.get('/', usersController.list);         // Listar Usuario(s)
        this.router.get('/:id', usersController.getOne);    // Mostrar Detalles Único Usuario
        this.router.post('/', usersController.create);      // Crear Nuevo Usuario
        this.router.put('/:id', usersController.update);    // Actualizar Usuario
        this.router.delete('/:id', usersController.delete); // Eliminar Usuario

    }

}

export default new GameRoutes().router;

