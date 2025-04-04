import { Request, Response } from 'express';
import pool from '../database';
 
class UsersController {

    // Listar Usuarios Sin PerfilWaiting for pgAdmi
    public async listUserNotPerf(req: Request, res: Response) {
        try {
            const usersNotPerfil = await pool.query('SELECT * FROM usuarios WHERE "Id_Perfil_Movil" IS NULL OR "Id_Perfil_Movil" = 0');
            res.json(usersNotPerfil.rows);  
        } catch (error) {
            res.status(500).json({ error: 'Error Server: Listar Usuarios Sin Perfil' });
        }
    } 

    // Listar Usuario(s) 
    public async list(req: Request, res: Response): Promise<void> {
        const users = await pool.query('SELECT * FROM usuarios');
        res.json(users.rows);  
    } 
 
    // Listar Usuario(s) con determinado Perfil    
    public async getOne(req: Request, res: Response) {
        try{
            const id_perfil = req.params.id_perfil;
            const users = await pool.query(`SELECT * FROM usuarios WHERE "Id_Perfil_Movil" = ${id_perfil}`);
            res.json(users.rows)
            
        } catch (error) {
            res.status(500).json({ error: 'Error Server: Busqueda Usuario X Perfil' });
        }
    }

    // Listar Perfile(s) con determinado Usuario    
    public async getOnePerfilUser(req: Request, res: Response) {
        try{
            const id_perfil = req.params.id_perfil;
            const perfil = await pool.query(`SELECT * FROM perfil_movil WHERE "Id_Perfil" = ${Number(id_perfil)}`);
            res.json(perfil.rows[0])
            
        } catch (error) {
            res.status(500).json({ error: 'Error Server: Busqueda Perfil X Usuario' });
        }
    }

    // Muestra todos los Datos Relacionados Con un Usuario
    public async details(req: Request, res: Response) {
        try{
            const { id } = req.params;
            const query = await pool.query(`SELECT * FROM usuarios INNER JOIN perfil_movil ON usuarios."Id_Perfil_Movil" = perfil_movil."Id_Perfil" WHERE "Id_Perfil" = ${[id]}`);
            const usuario_perfil = query.rows[0];

            
            if (query.rows[0]) {
                const query2 = await pool.query(`SELECT * FROM privilegios_movil WHERE "id_perfil" = '${query.rows[0].Id_Perfil_Movil}' `);
                const query3 = await pool.query(`SELECT * FROM perfil_programa_movil WHERE "Id_Perfil" = '${query.rows[0].Id_Perfil_Movil}' `);
                const Ids_Programas = query3.rows;
                const Programas = []
                for(let i = 0; i < Ids_Programas.length; i++) {
                   const query4 = await pool.query(`SELECT * FROM programa_movil WHERE "Id_Programa" = ${Ids_Programas[i].Id_Programa} `);
                   Programas.push(query4.rows[0].nombre);
                    }

                const query6 = await pool.query(`SELECT * FROM perfil_subprograma_movil WHERE "Id_Perfil" = '${query.rows[0].Id_Perfil_Movil}' `);
                const Ids_SubProgramas = query6.rows;
                const SubProgramas = []
                for(let i = 0; i < Ids_SubProgramas.length; i++) {
                   const query5 = await pool.query(`SELECT * FROM sub_programa_movil WHERE "Id_Subprograma" = ${Ids_SubProgramas[i].Id_Subprograma} `);
                   SubProgramas.push(query5.rows[0].nombre_subp);
                    }

                    const usuarioDetails = 
                    [{
                        "usuario": usuario_perfil.Usuario,
                        "clave": usuario_perfil.Clave,
                        "tipo_usuario": usuario_perfil.tipo_usuario,
                        "Respuesta_1": usuario_perfil.Respuesta_1,
                        "Respuesta_2": usuario_perfil.Respuesta_2,
                        "Respuesta_3": usuario_perfil.Respuesta_3,
                        "Habilitado" : usuario_perfil.Habilitado,
                        "programas" : Programas,
                        "modulos": SubProgramas
                    }]
                return res.json(usuarioDetails);
            }

        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

    // Crear Nueva Asociacion (Usuario-Perfil)
    public async create(req: Request, res: Response): Promise<void> {
        try {
            const updatedData = req.body;
            // Obteniendo Ids Usuario y Perfil
            const id_usuario = Number(updatedData[0].id_usuario);
            const id_perfil =  Number(updatedData[0].id_perfil);

            await pool.query(`UPDATE usuarios SET "Id_Perfil_Movil"= '${Number(id_perfil)}' WHERE "Id_Usuario" = ${Number(id_usuario)};`);

            res.json("Asociacion Generada Correctamente");

        } catch (error) {
            res.status(500).json({ error: 'Error Server: Crear Asociacion [Usuario -> Perfil]' });
        }
    }

    // Eliinar (usuario)
    public async delete(req: Request, res: Response): Promise<void> {
        try{
            const { id } = req.params;
            await pool.query(`DELETE FROM usuarios WHERE "Id_Usuario" = ${[id]}`);
            res.json({ message: "User Delete Check" });
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

    // actualizar (usuarios)
    public async update(req: Request, res: Response) {
        try {
            const id = req.params.id;
            const updatedData = req.body;
            const nombre_perfil = updatedData[0].nombre_perfil;
            const estado = updatedData[0].estado;
            const Usuario = updatedData[0].Usuario;
            const Clave = updatedData[0].Clave;
            const Nombre = updatedData[0].Nombre;
            const Apellido = updatedData[0].Apellido;
            const tipo_usuario = updatedData[0].tipo_usuario;
            const Respuesta_1 = updatedData[0].Respuesta_1;
            const Respuesta_2 = updatedData[0].Respuesta_2;
            const Respuesta_3 = updatedData[0].Respuesta_3
            const Habilitado = updatedData[0].Habilitado;
            const Fecha = updatedData[0].Fecha;

            await pool.query(`UPDATE usuarios SET "Usuario" = '${Usuario}', "Clave" = '${Clave}', "Nombre" = '${Nombre}', "Apellido" = '${Apellido}', "tipo_usuario" = '${tipo_usuario}', "Respuesta_1" = '${Respuesta_1}', "Respuesta_2" = '${Respuesta_2}', "Respuesta_3" = '${Respuesta_3}', "Habilitado" = '${Habilitado}', "Fecha" = '${Fecha}' WHERE "Id_Perfilp" = '${id}' ; UPDATE perfil SET nombre_perfil = '${nombre_perfil}', estado = '${estado}' WHERE "Id_Perfil" = '${id}' `);
            res.json( { massage: 'User Update Check' } ); 
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }
    
 
}

const usersController = new UsersController;
export default usersController;