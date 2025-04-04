import { Component, OnInit  } from '@angular/core';
import { UsersService } from 'src/app/services/users.service';
import { ValidateService } from 'src/app/services/validate.service';

@Component({
  selector: 'app-conexion',
  templateUrl: './conexion.component.html',
  styleUrls: ['./conexion.component.scss'],
})
export class ConexionComponent  implements OnInit {

  // URL defecto
  UrlDefault = 'http://192.168.1.101:3000';
  Url:any 
  
  // Campos Formulario 
  selectedOption!: string;
  protocolo!:string;
  dominio!:string
  puerto!:string;

  // styles
  Err_iP = false
  Err_Pt = false 
 
  btn=false

  constructor(
    public validateService:ValidateService,
    public usersService:UsersService
  ){}

  ngOnInit() {
    this.statusAPI(); 
    this.onRadioChange('http');
    this.validateService.SessionRedirectOne('/login');     // Func. Validación [Redirecciona al no Estar Reg.]

  }


  // Visualizar campo 'Modal' (Protocolo)
  onRadioChange(selectedRadio: string) {
    this.protocolo = selectedRadio;
  }

  // Modificar IP APIs
  editAddress(modalnfo:any){
    // inicializando
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

  // Establecer Valores por 'Defecto' APIs
  resetAddress(modalnfo:any){
    modalnfo.dismiss();
    localStorage.removeItem('url');
    localStorage.setItem('url', this.UrlDefault);
  }

  // Visualizar Estado de la APIs [ModuleServices]
  statusAPI(){
    this.btn = false;
    this.Url = this.usersService.AddressAPIStatus();
  }
  
  // Limpiar Campos ModalApi
  clear(){
    this.btn = true
    this.dominio = ''
    this.puerto = '';
    this.Err_iP = false

  }

}
