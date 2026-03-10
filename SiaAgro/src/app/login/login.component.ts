// import { CapacitorHttp, HttpResponse } from '@capacitor/core'
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Routes } from '@angular/router';
import { ILogin } from '../models/login';
import { UsersService } from '../services/users.service';
import { Router } from '@angular/router';
import { ValidateService } from '../services/validate.service';
import { TemplateModule } from '../template/template.module';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
})
export class LoginComponent  implements OnInit {

  login:ILogin = {
    user: '',
    password: ''
  };

  // Mensajes/parametro Validacion
  validationData = false;
  messageE = "No se Admiten Campos Vacios o Caracteres Especiales*";
  patternExclud = /[!#$%^&*()_+{}\[\]:;<>,?~]/; 
  valCaract = false;

  // Controlador, Conexión APIs
  isModalOpenAPI = false 

  // Verify API
  UrlDefault = 'http://51.81.63.216:3100'; 
  Url:any
  Err_iP = false
  Err_Pt = false
  selectedOption!: string;
  protocolo!:string;
  dominio!:string
  puerto!:string;
  btn=false

  // RecuperarPassword
  Statuslogin = true
  StatusRecuperacion = false   
  testUser:any
  testQuest:any
  testPassword:any
  StatusRecuperacionPaso1:any
  StatusRecuperacionPaso2:any
  StatusRecuperacionPaso3:any

  constructor(
    public router:Router,
    public usersService:UsersService,
    public validateService:ValidateService,
    private activatedRoute: ActivatedRoute,
  ) { }

  
  ngOnInit() {
    this.DefUrlDefect()
    this.reset();
    this.statusAPI(); 
    console.log(this.Url)
    this.onRadioChange('http');
    this.redirectoInicio()
  }

  // Redirigir Usuarios Ya Logeados
  redirectoInicio(){
  const redirected = this.validateService.valSession().status;  // Info. Estado-Usuarios (Boolean)
    if (redirected) {
      window.location.href = '/inicio';
    }
  }

  // Cerrar modal [Conexion APIs]
  handleModalDismiss() {
    this.isModalOpenAPI = false; 
  }

  // Validación Campos [Ususario, Clave]
  valInputs(){
    const verName = this.login.user + "";
    const verPass = this.login.password + "";

    this.valCaract = this.patternExclud.test(verName) ? true : false;
    this.valCaract = this.patternExclud.test(verPass) ? true : false;

    if( !this.login.user || !this.login.password){
      this.validationData = true;
      setTimeout( () => { this.validationData = false; }, 3000);
      return true;
    }
    if(this.valCaract){
      this.validationData = true;
      setTimeout( () => { this.validationData = false; }, 3000);
      return true;
    }
    return false
  }
 
  // Vaciar Campos [Ususario, Clave]
  reset(){
    this.login.user = ""; 
    this.login.password = "";
  }

  // Envio de Formulario logearse
  async sendLogin(){ 
    if (this.valInputs()) return; // finish function => (true)
    const userARRAY:any = [this.login]; // BIEN:ARRAY [{...}] , MAL:OBJETO {...}
    await this.usersService.searchSiaAgroBD(userARRAY)
      .subscribe(
        (res:any) => { 
          // console.log(res);
          if(!res.rows){
            this.validationData = true;
            this.messageE = "¡ Los Datos Ingresados son Incorrectos !";
            setTimeout( () => { this.validationData = false; }, 3000);
            return 0
          }
            console.log(`Access System: `, res.rows[0] )
            localStorage.setItem("user", JSON.stringify(res.rows[0])); // Memory LocalStorage
            this.reset();
            window.location.href = '/inicio' 
            return 1
                    
        },(err:any) => {
            this.isModalOpenAPI = true;
            console.error(err)
          }
      );
  }

  // Recuperación contraseña
  recupCont(){
    this.Statuslogin = false
    this.StatusRecuperacion = true  
    this.StatusRecuperacionPaso1 = true
  }
  BackVerifyUser(){
    this.StatusRecuperacion = false
    this.Statuslogin = true
  }
  BackVerifyQuest(){
    this.StatusRecuperacionPaso2 = false;
    this.StatusRecuperacionPaso1 = true;
  } 
  VerifyUser(){
    this.testUser = true
    this.StatusRecuperacionPaso2 = true
    this.StatusRecuperacionPaso1 = false

  }
  VerifyQuest(){
    this.testQuest = true
    this.StatusRecuperacionPaso2 = false
    this.StatusRecuperacionPaso3 = true

  }
  VerifyPassword(){
    this.testPassword = true
  }
  ConfirmPassword(){
    this.StatusRecuperacionPaso3 = false
    this.StatusRecuperacion = false
    this.Statuslogin = true
  }


  // Manejo [URL APIs]
  DefUrlDefect(){
    // Definir URL por Defecto (LocalStorage)
    if(!localStorage.getItem('url')){
      localStorage.setItem('url', this.UrlDefault);
    }
  }
  onRadioChange(selectedRadio: string) {
    // Visualizar campo 'Modal' (Protocolo)
    this.protocolo = selectedRadio;
  }
  editAddress(modalnfo:any){
    // Modificar IP APIs
    this.Err_iP = false
    this.Err_Pt = false

    // verificar definicion puerto
    if(this.puerto != ''){
      this.Url = this.protocolo + '://' + this.dominio + ':' + this.puerto;
    }else{
      this.Url = this.protocolo + '://' + this.dominio;
    }
    
    // validar [simbolos] (dominio)
    const contieneSimbolo = /[@!#$%^&*(),?":;``{}|<>]/.test(this.dominio);
    if(contieneSimbolo){
      this.Err_iP = true
    }

    // validar [Longitud] (dominio)
    if((this.dominio.length > 15) || (this.dominio.length < 4)){
      this.Err_iP = true
    }

    // validar [Longitud] (puerto)
    const puertoString = String(this.puerto)
    if( puertoString.length > 4 || puertoString.length == 1 ){
      this.Err_Pt = true
    }

    // completed
    if(!this.Err_iP && !this.Err_Pt){
      this.btn = false;
      modalnfo.dismiss();
      localStorage.removeItem('url');
      localStorage.setItem('url', this.Url);
    }

  }
  resetAddress(modalnfo:any){
    // Establecer Valores por 'Defecto' APIs
    modalnfo.dismiss();
    localStorage.removeItem('url');
    localStorage.setItem('url', this.UrlDefault);
  }
  statusAPI(){
    // Visualizar Estado de la APIs [ModuleServices]
    this.btn = false;
    this.Url = this.usersService.AddressAPIStatus();
  }
  clear(){
    // Limpiar Campos ModalApi
    this.btn = true
    this.dominio = ''
    this.puerto = '';
    this.Err_iP = false

  }
  
}
