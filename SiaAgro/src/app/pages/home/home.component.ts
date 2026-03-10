import { Component, OnInit } from '@angular/core';
import { ValidateService } from '../../services/validate.service';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Router } from '@angular/router';
import { UsersService } from 'src/app/services/users.service';

@Component({
  selector: 'app-home',
  templateUrl: './home.component.html',
  styleUrls: ['./home.component.scss'],
})
export class HomeComponent  implements OnInit {

  // Carga Info. Usuario
  userPass = {};
  permisosUsers:any
  programs = {
    cultivos:false,
    usuarios:false,
    Detector:false,
  }


  constructor(  
    public validateService:ValidateService,
    private modalService: NgbModal,
    public router:Router,
    public usersService:UsersService
  ){}

  ngOnInit() {
    this.validateService.SessionRedirectOne('/login');         // Validación [Redirecciona al no Estar Reg.]
    const url = this.usersService.AddressAPIStatus()           // Info. Url 
    this.userPass = this.validateService.valSession().status;  // Info. Estado-Usuarios

    // console.log(`Usuario Sessión: ${this.userPass}`);
    // console.log(`Usuario Active: ${this.validateService.valSession().data }`);   
    this.verifyPermisosUsers()
  }

    // Consultar Detalles Usuario 
  async verifyPermisosUsers(){

    // Data Usuario Activo (obj)
    const userActive = JSON.parse(this.validateService.valSession().data)

    // Consultar Detalles Usuario 
    await this.usersService.listUserDetails(userActive.Id_Perfil_Movil).subscribe(
      (res:any) => {
        const programasArray = res[0].programas;
        const modulosArray = res[0].modulos;
        this.permisosUsers = {
          usuario: res[0].usuario,
          tipo_usuario: res[0].tipo_usuario,
          Habilitado: res[0].Habilitado,
          programas: {
              animales: programasArray.includes('Animales') ? true : false,
              cultivos: programasArray.includes('Cultivos') ? true : false,
              usuarios: programasArray.includes('Usuarios') ? true : false,
              intelArt: programasArray.includes('IA') ? true : false,
          },
          modulos: {
              modelos: modulosArray.includes('Modelos') ? true : false,
              detector: modulosArray.includes('Detector') ? true : false,
          }
        };
        
        this.programs.Detector = this.permisosUsers.modulos.detector
        this.programs.usuarios = this.permisosUsers.programas.usuarios
        this.programs.cultivos = this.permisosUsers.programas.cultivos

        console.log(this.programs)

        },(erro:any) =>{
          console.error('Error: Cargar Opciones');
        }
    );
  }

  // Refresh de la Página
  refresh(event:any) {
    setTimeout(() => {
      event.target.complete();
    }, 2000);
  }
  
  


}
