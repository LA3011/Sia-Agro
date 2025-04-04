import express, { Router } from 'express';
import userController from '../controllers/userController';

class GameRoutes {

    router: Router = Router();

    constructor() {
        this.config();
    }

    config() {
        // Listar Usuario(s) sin Perfil
        this.router.get('/sin-perfil', userController.listUserNotPerf);    
        
        // Listar Usuario(s)
        this.router.get('/', userController.list);         
        
        // Listar Usuario(s) con determinado Perfil     
        this.router.get('/:id_perfil', userController.getOne);   

        // Listar Perfile(s) con determinado Usuario     
        this.router.get('/detalle-perfil/:id_perfil', userController.getOnePerfilUser);   

        // Mostrar Detalles Completos Del Usuario
        this.router.get('/detalles/:id', userController.details);   

        // Crear Nuevo Asociacion [Usuario -> Perfil]
        this.router.post('/', userController.create);    

        // Actualizar Usuario [SIN USO]
        this.router.put('/:id', userController.update); 
        
        // Actualizar Usuario [SIN USO]
        this.router.delete('/:id', userController.delete);          // Eliminar Usuario


    }

}

export default new GameRoutes().router;

