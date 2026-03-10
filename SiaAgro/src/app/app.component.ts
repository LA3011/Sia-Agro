import { Component, NgModule,OnDestroy } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { ValidateService } from './services/validate.service';
import { UsersService } from './services/users.service';
import { Location } from '@angular/common';


@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
}) 
export class AppComponent {

  userPass = {};

  loginPage = '';
  Page = ''; 
  plataformView:any
  isModalOpen:any

  // Menú Desplegable
  moduloIA:any
  iconDespleIA = 'chevron-down-outline'
  moduloConfig:any
  iconDespleConfig = 'chevron-down-outline'

  // Permisos
  permisosUsers:any = {
    usuario       : null,
    clave         : null,
    tipo_usuario  : null,
    Respuesta_1   : null,
    Respuesta_2   : null,    
    Respuesta_3   : null,    
    Habilitado    : null,
    programas : {
      animales : false,
      cultivos : false,
      usuarios : false,
      intelArt : false
    },
    modulos : {
      modelos  : false,
      detector : false,
    }
  }

  constructor(  
    public validateService:ValidateService,
    public usersService:UsersService,
    public router:Router,
  ){}

  ngOnInit(){  
    this.verifyUsuario() // Observar estado (false: NoLogeado) (true:LoginOK)
    this.plataformView = this.usersService.testWebMovil(); // Verificar ViewClient App    
    console.log(`Usuario Sessión: ${this.userPass}`);     
    if(this.userPass){
      this.verifyPermisosUsers(); // verificar Permisos, Accesibilidad Usuario
    }
    // console.log(`Usuario Data: ${this.validateService.valSession().data }`);
    
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
  
        },(erro:any) =>{
          console.error('Error: Cargar Opciones');
        }
    );
  }

  // Verificar: Existencia Usuario
  verifyUsuario(){
    this.userPass = this.validateService.valSession().status;  // Info. Estado-Usuarios (Boolean)
  }

  // Cerrar Session
  exit(){
    this.verifyUsuario()
    window.location.reload();
    localStorage.removeItem("user");
    window.location.href = '/login' 
    // this.router.navigate(['/login']); // Redirecto
    console.clear()

  }

  // Refresh: Pagina
  refresh(event:any) {
    setTimeout(() => {
      location.reload();
      console.clear();
      event.target.complete();
    }, 500);
  }
  
  // Desplegables: Menú
  menuModuloIA(){
    this.moduloIA = !this.moduloIA;
    this.iconDespleIA = this.moduloIA ? 'chevron-up-outline' : 'chevron-down-outline';

    this.moduloConfig = false
    this.iconDespleConfig = 'chevron-down-outline'
  }
  menuModuloConfig(){
    this.moduloConfig = !this.moduloConfig;
    this.iconDespleConfig = this.moduloConfig ? 'chevron-up-outline' : 'chevron-down-outline';
    
    this.moduloIA = false
    this.iconDespleIA = 'chevron-down-outline'
  }
}
