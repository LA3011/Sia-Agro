import { Component, OnInit } from '@angular/core';
import axios from 'axios';
import { UsersService } from 'src/app/services/users.service';
import { ValidateService } from 'src/app/services/validate.service';

@Component({
  selector: 'app-cultivos',
  templateUrl: './cultivos.component.html',
  styleUrls: ['./cultivos.component.scss'],
})
export class CultivosComponent  implements OnInit {

  // Priv. Usuario
  PrivMyPerfil:any = {
    ver: false,
    editar: false,
    eliminar:false
  }

  // Verify API
  isModalOpenAPI = false

  // Listado: Cultivos-Plaga
  listCultPlags:any
  listCultPlagsCosechado:any
  


  // Form-Edit-Plaga: Nombre Plaga
  name_plaga:string = ''

  // Detect-Error: Form Plaga 
  error_name_plaga:boolean = false

  // msj notificaciones
  msj_act:any
  deleteNot = "green"


  constructor(
    public usersService:UsersService,
    public validateService:ValidateService,
  ) { }

  ngOnInit() {
    this.listPriv()
    this.listCultivoPlaga()
    this.listCultivoPlagaCosechado()

  }

  async listPriv(){
    const userActive = JSON.parse(this.validateService.valSession().data)
    const id_Perfil = userActive.Id_Perfil_Movil
    await this.usersService.listUsersForPriv(id_Perfil).subscribe(
      (res:any) => {
        const Priv = res[0]
        for (let key in Priv) { 
          Priv[key] = (Priv[key] === "true");
        }
        this.PrivMyPerfil = Priv
      },(error)=>{
        this.isModalOpenAPI = true;
      }
    );

    console.log(this.PrivMyPerfil)
  }

  historiCultivos:any
  async fetchupdateCultivoPlaga(id:number){

    const res: any = await axios.get(`${this.usersService.UrlfetchupdateCultivoPlaga(id)}`);

    // Inicializar la variable `historiCultivos` dependiendo de la respuesta
    this.historiCultivos = res.data.length === 0
      ? [
          {
            fecha_ultima_deteccion: "Sin Registros",
          },
        ]
      : res.data.map((registro: any) => {
          // Formatear la fecha y manejar valores vacíos
          const fechaFormateada = registro.fecha_ultima_deteccion 
            ? this.setFecha(registro.fecha_ultima_deteccion) 
            : "Sin Registros";

          return {
            ...registro,
            fecha_ultima_deteccion: fechaFormateada,
          };
        });

    
        

    // await this.usersService.fetchupdateCultivoPlaga(id)
    // .subscribe(
      // (res:any) => {
      //   this.historiCultivos = res
      //   // Mapear el array para formatear cada fecha
      //   this.historiCultivos = res.map( (resgistro:any) => {
      //     const fechaFormateada = resgistro.fecha_ultima_deteccion ? this.setFecha(resgistro.fecha_ultima_deteccion) : "Fecha no válida";
      //     return {
      //       ...resgistro,
      //       fecha_ultima_deteccion: fechaFormateada
      //     };
      //   });
      // }
  // );
  }

  historiCultivosCosechado:any
  async fetchupdateCultivoPlagaCosechado(id:number){

    const res: any = await axios.get(`${this.usersService.UrlfetchupdateCultivoPlagaCosechado(id)}`);

    // Inicializar la variable `historiCultivos` dependiendo de la respuesta
    this.historiCultivosCosechado = res.data.length === 0
      ? [
          {
            fecha_ultima_deteccion: "Sin Registros",
          },
        ]
      : res.data.map((registro: any) => {
          // Formatear la fecha y manejar valores vacíos
          const fechaFormateada = registro.fecha_ultima_deteccion 
            ? this.setFecha(registro.fecha_ultima_deteccion) 
            : "Sin Registros";

          return {
            ...registro,
            fecha_ultima_deteccion: fechaFormateada,
          };
        });

    
        

    // await this.usersService.fetchupdateCultivoPlaga(id)
    // .subscribe(
      // (res:any) => {
      //   this.historiCultivos = res
      //   // Mapear el array para formatear cada fecha
      //   this.historiCultivos = res.map( (resgistro:any) => {
      //     const fechaFormateada = resgistro.fecha_ultima_deteccion ? this.setFecha(resgistro.fecha_ultima_deteccion) : "Fecha no válida";
      //     return {
      //       ...resgistro,
      //       fecha_ultima_deteccion: fechaFormateada
      //     };
      //   });
      // }
  // );
  }


  // Listar Cultivos + Plagas
  async listCultivoPlaga(){

    const res = await axios.get(`${this.usersService.UrlListCultivoPlaga()}`);
    this.listCultPlags = res.data    
    this.listCultPlags = this.listCultPlags.map((obj:any) => {
      return {
        ...obj,
        fecha_ultima_deteccion: this.formatDate(obj.fecha_ultima_deteccion)
      };
    });

  
  }
  
  // Listar Cultivos + Plagas (cosechado)
  async listCultivoPlagaCosechado(){
    const res = await axios.get(`${this.usersService.UrlListCultivoPlagaCosechado()}`);
    this.listCultPlagsCosechado = res.data   
    this.listCultPlagsCosechado = this.listCultPlagsCosechado.map((obj:any) => {
      return {
        ...obj,
        fecha_ultima_deteccion: this.formatDate(obj.fecha_ultima_deteccion)
      };
    });

  }

  // Setear Fechas: dd/mm/yy
  formatDate(dateString:any) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses empiezan en 0
    const year = String(date.getFullYear()); // Obtener los últimos 2 dígitos del año
  
    return `${day}/${month}/${year}`;
  }
  // Retornar fecha de hoy 
  dateToday() {
    const now = new Date();
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0'); // Los meses empiezan en 0
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    const formattedDateTime = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
    return formattedDateTime;
  }


  // Form: Cultivo + Plaga
  async plaga_update(modalE:any, id_cultivo:number){
    if(!(this.name_plaga == "Sin Plagas")){
      this.name_plaga = this.pipeTextExample(this.name_plaga)   
    }

    // Verificar Caracteres Especiales
    const ErrorCarect = this.verificarCaracteresEspeciales(this.name_plaga) 
    if(ErrorCarect){
      this.error_name_plaga = true
    }else{
      this.error_name_plaga = false
    }

    if(this.name_plaga && (this.error_name_plaga == false)){
      // Data Formulario
      const data = {
        id: id_cultivo,
        nombre_plaga: this.name_plaga,
        fecha_ultima_deteccion: this.dateToday()
      } 

      const res = await axios.put(`${this.usersService.UrlUpdateCultivoPlaga(data.id)}`, data);
      this.listCultivoPlaga()
      console.log(res)
      // 
      this.error_name_plaga = false
      this.name_plaga = ""
      modalE.dismiss()

      this.funct_msjAct("Cultivo Modificado Exitosamente")


    }else{
      this.error_name_plaga = true
    }
  }

  // manejador Mensaje 'Accion'
  funct_msjAct(msj:string){
    this.msj_act = msj
    setTimeout(()=>{
      this.msj_act = ""
    }, 3000)
  }

  // Asignacion Text Lit
  TextsinPlagas(){
    this.name_plaga = "Sin Plagas"
  }

  // Pipe: Text 
  pipeTextExample(string:any) { 
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
  }

  // Verificar Carecteres especiales
  verificarCaracteresEspeciales(entrada:any) { 
    const regex = /[^a-zA-Z0-9 ]/; 
    return regex.test(entrada); 
  }

  // Resetear Valores
  resetInput(){
    this.name_plaga = ""
    this.error_name_plaga = false

  }

  setFecha(dateString:any) {
      // Convertir la cadena de fecha a un objeto Date
      const date = new Date(dateString);
      
      // Extraer el día, mes y año
      const day = String(date.getUTCDate()).padStart(2, '0');
      const month = String(date.getUTCMonth() + 1).padStart(2, '0'); // Los meses empiezan en 0
      const year = date.getUTCFullYear();
      
      // Formatear la fecha como dd-mm-yyyy
      const formattedDate = `${day}-${month}-${year}`;
      
      return formattedDate;
  
  }

  // cerrar modal [Conexion API]
  handleModalDismiss() {
    this.isModalOpenAPI = false; 
  }
  
  // Cambio de Paginas
  pagSinCosechar=true
  pagCosechados=false
  CambioPag2(){
    this.pagCosechados = true;
    this.pagSinCosechar = false;
  }
  CambioPag(){
    this.pagCosechados = false;
    this.pagSinCosechar = true;
  }
}



