import express, { Router } from 'express';
import perfilController from '../controllers/perfilController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {

        // Listar Perfile(s) con determinado Privilegios     
        this.router.get('/privilegios/:id_perfil', perfilController.getPerfilForPriv);   
        
        // Listar Perfile(s) con determinado Privilegios     
        this.router.get('/acceso/:id_perfil', perfilController.getPerfilForAcc);   

        // listar Perfiles (app)
        this.router.get('/', perfilController.list);

        //! SIN USO
        this.router.get('/:id', perfilController.getOne);

        // Crear Un Nuevo Perfil (app)
        this.router.post('/', perfilController.create); 

        // Edita Un Nuevo Perfil (app)
        this.router.put('/:id', perfilController.update);

        // Elimina Totalmente el Perfil y Sus Asociaciones
        this.router.delete('/:id', perfilController.delete);

    }

}

export default new GameRoutes().router;

