import express, { Application } from 'express'
import indexRoutes from './routes/indexRoutes';
import usersRoutes from './routes/usersRoutes';
import loginRoutes from './routes/loginRoutes';
import artifyRoutes from './routes/artifyRoutes';
import artifyModelsRoutes from './routes/artifyModelsRoutes';
import tagModelsRoutes from './routes/tagModelsRoutes';
import trainModelsRoutes from './routes/trainModelsRoutes';
import trainModelsCompleteRoutes from './routes/trainModelsCompleteRoutes';

import morgan from 'morgan';
import cors from 'cors';
import multer from 'express';
import tagModelsCompleteRoutes from './routes/tagModelsCompleteRoutes';
import artifyDataSetRoutes from './routes/artifyDataSetRoutes';
import validModelsCompleteRoutes from './routes/validModelsCompleteRoutes';
import artifyCancelRoutes from './routes/artifyCancelRoutes';
import infoModelsRoutes from './routes/infoModelsRoutes';
import notifyModelsRoutes from './routes/notifyModelsRoutes';
import menuRoutes from './routes/menuRoutes';
import perfilRoutes from './routes/perfilRoutes';
import userRoutes from './routes/userRoutes';
import plagaCultivoRoutes from './routes/plagaCultivoRoutes';
import animalRoutes from './routes/animalRoutes';

class Server {

    public app: Application;
    constructor(){
        this.app = express()
        this.config();
        this.routes();
    }

    config():void{
        const http = require('http');
        this.app.set('port', process.env.PORT || 3100);
        this.app.use(morgan('dev'))
        this.app.use(cors({
            origin: '*', 
            methods: ['GET', 'POST', 'PUT', 'DELETE'],
            allowedHeaders: [ 'Access-Control-Allow-Headers', 'Content-Type', 'Authorization'],
            credentials: true 
        }));
        
        this.app.use(express.json())
        this.app.use(express.json({ limit: '1000mb' }));
        this.app.use(express.urlencoded({ limit: '1000mb', extended: true }));
        
    }
    
    routes():void{

        // CRUD: Usuarios 
        this.app.use('/api/usuario',userRoutes); 

        // Gestion Perfiles [Privilegios, Acceso]
        this.app.use('/api/usuario-perfil',perfilRoutes); 

        // Vista Menu Opciones [sin uso]
        this.app.use('/api/menu',menuRoutes); 
        
        // CRUD: Usuarios [sin uso]
        this.app.use('/api/games',usersRoutes); 

        // Consultar: Existencia Usuario(s) en BD [sin uso]
        this.app.use('/api/login',loginRoutes);

        // Ejecucion: Deteccion.py | Descomprimir: Arch.zip
        this.app.use('/api/inteligencyArtificial',artifyRoutes);  

        // Cancelar: ProcesoCMD entrenamiento.py | Verificación: Ejecución de otro Entrenamiento
        this.app.use('/api/inteligencyArtificial-cancel',artifyCancelRoutes);

        //  Distribuir: 20% + 80% | Eliminar: Archivo/Carpeta Sobrante | Modificar: Archivo predecir_umbral.py (Agregar Nueva Etiqueta)
        this.app.use('/api/inteligencyArtificial-models',artifyModelsRoutes);  

        // Obtener: Información 'BD' DeteccionIA
        this.app.use('/api/inteligencyArtificial-dataSet',artifyDataSetRoutes);

        // Listar: 'Nombres' Etiquetas | Mover: zipProcess (Expanción) | Distribuir: 80% + 20% | Eliminacion Arc/Dir (restantes) | Eliminar: Etiqueta
        this.app.use('/api/inteligencyArtificial-models-tag',tagModelsRoutes);  

        // Calcular: Cantidad Imagenes todas las Etiquetas | Modificar: Archivo 'predecir_umbral.py', Eliminando Todos los "#Entrenar"
        this.app.use('/api/inteligencyArtificial-models-tag-Completed',tagModelsCompleteRoutes);

        // Modificar: Archivo entrenar.py (Define la Cantidad de Clases) | Eliminar: Modelos Viejos | Mover: Modelos Nuevos | Genera: Carpetas [Entrenamiento + Validacion] | otro...W 
        this.app.use('/api/inteligencyArtificial-models-entrenamiento',trainModelsRoutes);

        // Validar: Estructura Archivo ZIP [zip/carpeta/imagenes.jpg]
        this.app.use('/api/inteligencyArtificial-models-entrenamiento-Valid',validModelsCompleteRoutes);

        // Leer: DataSet (Todo) | Configurar: Entrenamiento [./entrenamientoModels/entrenar.py] | Ejecutar: Entrenando Model 
        this.app.use('/api/inteligencyArtificial-models-entrenamiento-Completed',trainModelsCompleteRoutes);

        // CRUD: Informacion de las Etiquetas BD | Monitoreo: Presicion del Modelo
        this.app.use('/api/inteligencyArtificial-models-Informacion', infoModelsRoutes);

        // Manejo: Notificaciones de las Etiquetas BD
        this.app.use('/api/inteligencyArtificial-models-Notificacion', notifyModelsRoutes);
        
        // CRUD: Informacion de cultivo/plaga 
        this.app.use('/api/cultivoPlaga', plagaCultivoRoutes)

        // Gestion Animal
        this.app.use('/api/animales', animalRoutes)


        /** ------- funcion-Nofound | aloneThis ------- */   

        // Function: Subir Imagen al Servidor.
        const multer = require('multer');
        const path = require('path');

        // ubicacion carpeta 'imagesTest' 
        const pathImagesTest = path.join(__dirname, '..','..', 'InteligenciaArtificial', 'imagesTest');
        // ubicacion carpeta 'entrenamiento' 
        const pathEntrenamiento = path.join(__dirname, '..','..', 'InteligenciaArtificial', 'data', 'entrenamiento');

        const storage = multer.diskStorage({
        destination: function(req:any, file:any, cb:any) { cb(null, pathImagesTest); },
        filename: function(req:any, file:any, cb:any) { cb(null, 'imagenProcess.jpg');}});
        const upload = multer({ storage: storage }, { dest: 'uploads/' });

        this.app.post('/api/inteligencyArtificial', upload.single('fileImage'),(req:any, res:any) => {
            const image = req.file
            return res.json(image);
        }); 
        
        // Function: Subir Arc.zip al Servidor. 
        const timeout = require('connect-timeout'); 
        this.app.use(timeout('120000s'));
        const http = require('http');
        const server = http.createServer(this.app);
        server.timeout = 300000; // Establece el tiempo de espera de lectura a 30 segundos (30000 ms)
        const multerzip = require('multer');
        const pathZip = require('path');
        const nameZip = 'zipProcess.zip'


        const storageZip = multerzip.diskStorage({
            
            limits: { fileSize: 1024 * 1024 * 1024 },
            destination: function(req:any, file:any, cb:any) { cb(null, pathEntrenamiento); },
            filename: function(req:any, file:any, cb:any) { cb(null, nameZip);}});
            const uploadZip = multerzip({ storage: storageZip });
            
            this.app.post('/api/InteligencyArtificial-model', uploadZip.single('fileZip'),(req:any, res:any) => {
            const zip = req.file

            return res.json(zip);
        }); 
    } 
    
    // Inicializar: Servidor | Otras Configuraciones
    start():void{
        this.app.listen(3100, () => {
            console.log("PUERTO", this.app.get('port'));
        } );
    } 

}

const server = new Server(); 
server.start();

export default new Server();
