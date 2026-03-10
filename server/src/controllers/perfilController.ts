import { Request, Response } from 'express';
import pool from '../database';
 
class PerfilController {


    // Listar Perfile(s) con determinado Priv    
    public async getPerfilForPriv(req: Request, res: Response) {
        try{
            const id_perfil = req.params.id_perfil;
            const users = await pool.query(`SELECT * FROM privilegios_movil WHERE "id_perfil" = ${id_perfil}`);
            res.json(users.rows)
            
        } catch (error) {
            res.status(500).json({ error: 'Error Server: Busqueda Usuario X Perfil' });
        }
    }

    // Listar Perfile(s) con determinado Acces    
    public async getPerfilForAcc(req: Request, res: Response) {
        try{
            const id_perfil = req.params.id_perfil;
            // Obteniendo id(s) Programas
            const Query_perfilPrograma:any = await pool.query(`SELECT * FROM "perfil_programa_movil" WHERE "Id_Perfil" = ${id_perfil}`);
            let Ids_Programa:any = []
            for (let i = 0; i < Query_perfilPrograma.rows.length; i++) {
                Ids_Programa.push(Query_perfilPrograma.rows[i].Id_Programa)
            }
            // Obteniendo id(s) SubProgramas
            const Query_perfilSubPrograma:any = await pool.query(`SELECT * FROM "perfil_subprograma_movil" WHERE "Id_Perfil" = ${id_perfil}`);
            let Ids_subPrograma:any = []
            for (let i = 0; i < Query_perfilSubPrograma.rows.length; i++) {
                Ids_subPrograma.push(Query_perfilSubPrograma.rows[i].Id_Subprograma)
            }
            // Obteniendo nombres Programas
            let names_Programa:any = []
            for (let i = 0; i < Ids_Programa.length; i++) {
                const name_perfilPrograma:any = await pool.query(`SELECT * FROM "programa_movil" WHERE "Id_Programa" = ${Ids_Programa[i]}`);
                names_Programa.push(name_perfilPrograma.rows[0].nombre)
            }
            // Obteniendo nombres SubProgramas
            let Subnames_Programa:any = []
            for (let i = 0; i < Ids_subPrograma.length; i++) {
                const name_subPrograma:any = await pool.query(`SELECT * FROM "sub_programa_movil" WHERE "Id_Subprograma" = ${Ids_subPrograma[i]}`);
                Subnames_Programa.push(name_subPrograma.rows[0].nombre_subp)
            }
            // anidar results
            const data = {
                programa : names_Programa,
                subPrograma : Subnames_Programa,
            }            
            
            res.json(data)
            
        } catch (error) {
            res.status(500).json({ error: 'Error Server: Busqueda Usuario X Perfil' });
        }
    }

    // listar Perfiles (app)
    public async list(req: Request, res: Response): Promise<void> {
        try{
            const users = await pool.query('SELECT * FROM perfil_movil INNER JOIN privilegios_movil ON perfil_movil."Id_Perfil" = privilegios_movil.id_perfil');
            
            res.json(users.rows);  
        }catch{
            res.status(500).json({ error: 'Error Server: Listar Usuarios' });
        }
    } 

    // Crear Un Nuevo Perfil (app)
    public async create(req: Request, res: Response): Promise<void> {
        try {
            // Cuerpo Solicitud
            const updatedData = req.body;
            // Procesar Ids Programas [almacenar Ids-Programas en un Array]
            const Ids_Programa = [];
            for (const key in updatedData.programas) {
                if (updatedData.programas[key][1] === true) {
                    Ids_Programa.push(updatedData.programas[key][0]);
                }
            }
            // Procesar Ids SubProgramas [almacenar Ids-SubProgramas en un Array]
            const Ids_Subp = [];
            for (const key in updatedData.subprogramas) {
                if (updatedData.subprogramas[key][1] === true) {
                    Ids_Subp.push(updatedData.subprogramas[key][0]);
                }
            }
            // Datos del Perfil
            const nombre_perfil = updatedData.nombre_perfil;
            const estado = updatedData.estado;
            // Datos Privilegios
            const ver = updatedData.privilegios.ver;
            const editar = updatedData.privilegios.editar;
            const eliminar = updatedData.privilegios.eliminar;
            const imprimir = updatedData.privilegios.imprimir;
            const agregar = updatedData.privilegios.agregar;

            // Si 1 SubOpc Del Prog.'IA' esta Select., entonces IA Tambien Lo esta
            if(Ids_Subp[0]) Ids_Programa.push(5)  

            // Insertar Datos del Perfil
            const query1 =  await pool.query(`INSERT INTO perfil_movil (nombre_perfil,estado) VALUES ('${nombre_perfil}', '${estado}')`);
            // Obtener Ultimo Registro de la Tabla Perfil
            const query2 =  await pool.query(`SELECT "Id_Perfil" FROM perfil_movil ORDER BY "Id_Perfil" DESC LIMIT 1;`);
            // Insertar Privilegios en Funcion al perfil 'recien creado'
            const query3 = await pool.query(`INSERT INTO privilegios_movil (id_perfil, ver, editar, eliminar, imprimir, agregar) VALUES ('${query2.rows[0].Id_Perfil}' , '${ver}', '${editar}', '${eliminar}', '${imprimir}', '${agregar}')`);
            // insertar valores del arays 'indices Programas'
            for(let i = 0; i < Ids_Programa.length; i++) {
                const query4 = await pool.query(`INSERT INTO perfil_programa_movil ("Id_Perfil","Id_Programa") VALUES ('${query2.rows[0].Id_Perfil}', ${Ids_Programa[i]})`);
            }
            // insertar valores del arays 'indices SubProgramas'
            for(let y = 0; y < Ids_Subp.length; y++) {
                const query5 = await pool.query(`INSERT INTO perfil_subprograma_movil ("Id_Perfil","Id_Subprograma") VALUES ('${query2.rows[0].Id_Perfil}', ${Ids_Subp[y]})`);
            }

            res.json("Perfil Registrado");

        } catch (error) {
            res.status(500).json({ error: 'Error Server: Agregar Perfil' });
        }
    }
    
    // Elimina Totalmente el Perfil y Sus Asociaciones
    public async delete(req: Request, res: Response): Promise<void> {
        try{
            
            const { id } = req.params; // Id del Perfil A eliminar

            const user_perfil = await pool.query(`SELECT * FROM usuarios WHERE "Id_Perfil_Movil" = '${id}'`);

            if(user_perfil.rows[0]){
                // Se encuentra Un usuario en Ese perfil
                res.json(false);
            }else{

                await pool.query(`BEGIN; 
                    DELETE FROM privilegios_movil WHERE id_perfil = ${[id]}; 
                    DELETE FROM perfil_subprograma_movil WHERE "Id_Perfil" = ${[id]};
                    DELETE FROM perfil_programa_movil WHERE "Id_Perfil" = ${[id]};
                    DELETE FROM perfil_movil WHERE "Id_Perfil" = ${[id]}; 
                COMMIT;`);
                
                res.json({ message: "User Delete Check" });
            }

        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }

    // Edita Un Nuevo Perfil (app)
    public async update(req: Request, res: Response) {
        try {
            // Obteniendo (Id) Perfil
            const id = req.params.id;
            // Obteniendo (data) Perfil
            const updatedData = req.body;
            const Ids_Programa = updatedData[0].Ids_Programa;
            const Ids_Subp = updatedData[0].Ids_Subp;
            const nombre_perfil = updatedData[0].nombre_perfil;
            const estado = updatedData[0].estado;
            const ver = updatedData[0].ver;
            const editar = updatedData[0].editar;
            const eliminar = updatedData[0].eliminar;
            const imprimir = updatedData[0].imprimir;
            const agregar = updatedData[0].agregar;

            // Si 1 SubOpc Del Prog.'IA' esta Select., entonces IA Tambien Lo esta
            if(Ids_Subp[0]) Ids_Programa.push(5)  
                


            // agrgar Valores Defecto
            Ids_Programa.push(1) // Agregando Opcion 'Inicio'
            Ids_Subp.push(3) // Agregando Opcion 'Conexion'


            // Actualizar Perfil [perfil, Privilegios] {nombre, estado, ver, editar, ...}
            await pool.query(`BEGIN; UPDATE perfil_movil SET nombre_perfil = '${nombre_perfil}', estado = '${estado}' WHERE "Id_Perfil" = '${Number(id)}'; 
                UPDATE privilegios_movil SET ver = '${ver}', editar = '${editar}', eliminar = '${eliminar}', imprimir = '${imprimir}', agregar = '${agregar}' WHERE id_perfil = '${Number(id)}'; 
                COMMIT; `);
            
            // Actualizando Perfil-Programa [perfil_programa_movil] {opcProgramas}
            await pool.query(`DELETE FROM perfil_programa_movil WHERE "Id_Perfil" = ${[id]} `);
            for(let i = 0; i < Ids_Programa.length; i++) {
                const query4 = await pool.query(`INSERT INTO perfil_programa_movil ("Id_Perfil","Id_Programa") VALUES ('${[id]}', ${Ids_Programa[i]})`);
            }

            // Actualizando Perfil-SubPrograma [perfil_SubPrograma_movil] {opcSubProgramas}
            await pool.query(`DELETE FROM perfil_subprograma_movil WHERE "Id_Perfil" = ${[id]} `);
            for(let y = 0; y < Ids_Subp.length; y++) {
                const query5 = await pool.query(`INSERT INTO perfil_subprograma_movil ("Id_Perfil","Id_Subprograma") VALUES ('${[id]}', ${Ids_Subp[y]})`);
            }

            res.json("Perfil Editado Exitosamente"); 

        } catch (error) {
            res.status(500).json({ error: 'Error Server: Editar Perfil' });
        }
    }
    
    // listar privilegios en base a un perfil
    public async getOne(req: Request, res: Response) {
        try{
            const { id } = req.params;
            const users = await pool.query(`SELECT * FROM perfil INNER JOIN privilegios ON perfil."Id_Perfil" = privilegios.id_perfil WHERE "Id_Perfil" = ${[id]}`);  
            console.log(users);
            if (users.rows[0]) {
                return res.json(users.rows);
            }
            res.status(404).json({ text: "User InExist" });
        } catch (error) {
            res.status(500).json({ error: 'Error Server' });
        }
    }
    
}

const perfilController = new PerfilController;
export default perfilController;