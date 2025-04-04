import { Component, OnInit } from '@angular/core';
import { Obj } from '@popperjs/core';
import axios from 'axios';
import { UsersService } from 'src/app/services/users.service';
import { ValidateService } from 'src/app/services/validate.service';

@Component({
  selector: 'app-animales',
  templateUrl: './animales.component.html',
  styleUrls: ['./animales.component.scss'],
})
export class AnimalesComponent  implements OnInit {

  
  // Info. Usuario Activo
  userActive:any

  // Paginas 
  pagUsuarios = true
  pagPerfil = false

  // Conotrolador Modal Conexion APIs
  isModalOpenAPI = false

  // Opciones Agregar
  ver = true // privilegio por defect
  editar = true;
  eliminar = true;
  imprimir = true;
  agregar = true;
  [key: string]: any;
  
  // Listar Priv
  PrivMyPerfil:any = {
    ver: false,
    editar: false,
    eliminar:false
  }

  // Animales Venta
  listAnimals:any

  // Animales Pastoreo
  listAnimalPasoreos:any


  constructor( 
    private usersService: UsersService,
    public validateService:ValidateService) {
  }

  ngOnInit() { 
    this.validateService.SessionRedirectOne('/login');
    this.userActive = this.validateService.valSession().status; // Info. Estado-Usuarios
    
    if(this.userActive){
      this.listPriv()
    }

    this.listAnimal()   // Listar Animales Venta
    this.listAnimalPastoreo()   // Listar Animales Venta

  }

  // Privilegios Sesion Activa
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
  }

  // Listar Animales Venta
  async listAnimal(){
    try {
      const SQLAnimals = await axios.get(`${this.usersService.listAnimalsVenta()}`);
      this.listAnimals = SQLAnimals.data  
      console.log(this.listAnimals)

    } catch (error) {
      this.isModalOpenAPI = true;
    }
  }

  // Listar Animales Pastoreo
  async listAnimalPastoreo(){
    try {
      const SQLAnimals = await axios.get(`${this.usersService.listAnimalsPastoreo()}`);
      this.listAnimalPasoreos = SQLAnimals.data  

    } catch (error) {
      this.isModalOpenAPI = true;
    }
  }

  // cerrar modal [Conexion API]
  handleModalDismiss() {
   this.isModalOpenAPI = false; 
  }

  // Cambio de Paginas
  CambioPag(){
    this.pagUsuarios = true;
    this.pagPerfil = false;
  }
  CambioPag2(){
    this.pagUsuarios = false;
    this.pagPerfil = true;
  }


}
