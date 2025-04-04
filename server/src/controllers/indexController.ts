import { Request, Response } from 'express';

class IndexController {

    // Mensaje: Prueba de Funcionamiento
    public index(req: Request, res: Response) {
        res.json({text: 'API Proyecto SiaAgro'});
    }

}

export const indexController = new IndexController;