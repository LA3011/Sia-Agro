import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { Obj } from '@popperjs/core';
import { UsersService } from 'src/app/services/users.service';
import { ValidateService } from 'src/app/services/validate.service';



@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.component.html',
  styleUrls: ['./perfil.component.scss'],
})
export class PerfilComponent  implements OnInit {

  // Info. Usuario Activo
  userActive:any

  // ...
  Pver:any
  Pedit:any
  Pimp:any
  Peli:any
  Pagr:any
  usuariForPerfEdit:any
  msj_ErrorUpdate:any

  // Paginas 
  pagUsuarios = true
  pagPerfil = false

  // Msj Accion (Usuarios)
  msj_act_Usuario = ""

  // Msj Accion
  msj_act = ""
  deleteNot:any


  // Msj Error Form
  msj_Error = {
    "state": false,
    "Mensaje": "Rellene Todos los Campos",
    "stateOpc": false,
    "MensajeOpc": "Seleccione Al menos 1 Opcion",
    "statePrv": false,
    "MensajePrv": "Verifique los Privilegio", 
  }
  // Msj Error Form Edit
  msj_ErrorEdit = {
    "state": false,
    "Mensaje": "Rellene Todos los Campos",
    "stateOpc": false,
    "MensajeOpc": "Seleccione Al menos 1 Opcion",
    "statePrv": false,
    "MensajePrv": "Verifique los Privilegio", 
  }
  msj_act_Usuario_ERR=""

  // Msj Error Form Usuario
  valER = false
  // Colores Inputs
  colorUxP=""

  // List [Usuarios]
  usersList:any;
  usersXperfil:any = {
    nombre_perfil: ""
  };

  // List [Perfiles, Usuarios]
  perfiles:any;
  usuariosSinPerfil:any;
  userForPerfil:any;
  userForPrivilegio:any = {};
  userForAcc:any; 

  // Conotrolador Modal Conexion APIs
  isModalOpenAPI = false

  // Opciones Agregar
  nombre_perfil = "";
  userSelect = ""
  programas = false 
  animales = false 
  cultivos = false 
  usuarios = false 
  modelo = false 
  detector = false 
  ia = false 
  ver = true // privilegio por defect
  editar = true;
  eliminar = true;
  imprimir = true;
  agregar = true;
  [key: string]: any;
  AccAnimales:any = false
  AccCultivos:any = false
  AccUsuarios:any = false
  AccIA:any = false
  AccDetector:any = false
  AccModelos:any = false;

  // Opciones Usuario
  usuariForPerf:any = ""
  perfilForPerf:any = ""
  
  // EditarPerfil
  perfilEditForm:any

  // Botones Form Perfil
  programasAll?:boolean
  state: boolean = false;

  constructor( 
    private usersService: UsersService,
    public validateService:ValidateService) {
    }
  ngOnInit() { 
    this.validateService.SessionRedirectOne('/login');
    this.userActive = this.validateService.valSession().status;           // Info. Estado-Usuarios
    // console.log(`User Data: ${this.validateService.valSession().data }`); // Info. User Activo
    this.listPerfilUsers(); 
    this.listUsersSinPerfil();
    this.getlistUsers()
    
    if(this.userActive){
      this.listPriv()
    }
  }
  // Listar Priv
  PrivMyPerfil:any = {
    ver: false,
    editar: false,
    eliminar:false
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
  }


  // verificar Privilegios
  validatePriv(): boolean {
    return this.ver || this.editar || this.eliminar || this.imprimir || this.agregar;
  }
  validatePrivEdit(perfil:any): boolean {
    return perfil.ver || perfil.editar || perfil.eliminar || perfil.imprimir || perfil.agregar;
  }
  
  // Agrega Un nuevo Perfil 
  async agregarPefil(modalA:any){ 
    // data formulario Agregar 
    const opcionesForm = {
      animales : this.animales,
      cultivos : this.cultivos,
      usuarios : this.usuarios,
      ia :       this.ia,
      detector:  this.detector,
      modelo :   this.modelo,
    }
    const privilegios = {
      ver: this.ver,
      editar: this.editar,
      eliminar: this.eliminar,
      imprimir: this.imprimir,
      agregar: this.agregar
    }  

    // valida input [nombre_pefil/usuario]
    this.validarInputPerfil()

    // Proceso de Agregar Usuario
    if(this.validarObjet(opcionesForm)){
      this.msj_Error.stateOpc = false

      if(!this.msj_Error.state){
        if(this.validarObjet(privilegios)){

          // datos para enviar
          const data = {
            nombre_perfil: this.nombre_perfil,
            usuario :this.userSelect,
            estado  :"activo", // defecto Habilitado
            programas : {
              animales : [2, this.animales],
              cultivos : [3, this.cultivos],
              usuarios : [4, this.usuarios],
              ia :       [5, this.ia],
            },
            subprogramas: {
              detector:  [1, this.detector],
              modelo :   [2, this.modelo],
              conexion:  [3, true] // Acceso Defecto
            },
            privilegios: {
              ver: this.ver,
              editar: this.editar,
              eliminar: this.eliminar,
              imprimir: this.imprimir,
              agregar: this.agregar
            }
          }  

          // Almacenar BD Peril->Usuario (Movil)
          await this.usersService.aggPerfil(data).subscribe(
            (res) => {
              // Reset Form Agregar
              this.resetFormAgregar()
              modalA.dismiss()
              this.funct_msjAct("Perfil Agregado Exitosamente")
              // Observar Cambios
              this.listPerfilUsers(); 
              this.listUsersSinPerfil();
            },(error)=>{
              // this.isModalOpenAPI = true;
              // --------------------------------------- considerar al Transpilar a JAVA
              this.resetFormAgregar()
              modalA.dismiss()
              this.funct_msjAct("Perfil Agregado Exitosamente")
              // Observar Cambios
              this.listPerfilUsers(); 
              this.listUsersSinPerfil();
            }
          );
        }else{
          this.msj_Error.statePrv = true;
        }
      }
    }else{
      this.msj_Error.stateOpc = true
    }
    
  }

  // Editar Perfil[...]
  async editarPerfil(
    modalA:any,
    dataPerfilEdit:any, 
    AccAnimales:any,
    AccCultivos:any,
    AccUsuarios:any, 
    AccIA:any, 
    AccDetector:any, 
    AccModelos:any,

    Pver:any,
    Pedit:any,
    Peli:any,
    Pimp:any,
    Pagr:any,
  ){ 

    const perfilPrivObj = {
      "ver": Pver,
      "editar": Pedit,
      "eliminar": Peli,
      "imprimir": Pimp,
      "agregar": Pagr
    }

    // validar nombre Perfil y Privilegios
    if(!dataPerfilEdit.nombre_perfil ){
      this.msj_ErrorEdit.state = true
    }else{
      this.msj_ErrorEdit.state = false
    }
    if(!this.validatePrivEdit(perfilPrivObj)){
      this.msj_ErrorEdit.statePrv = true
    }else{
      this.msj_ErrorEdit.statePrv = false
    }

    // validar Programas/SubProgrmas Perfil
    if (AccAnimales || AccCultivos || AccUsuarios || AccIA || AccDetector || AccModelos) {
      this.msj_ErrorEdit.stateOpc = false
    } else {
      this.msj_ErrorEdit.stateOpc = true
    }

    if(!this.msj_ErrorEdit.stateOpc && !this.msj_ErrorEdit.state && !this.msj_ErrorEdit.statePrv){
      const Ids_Programa = []
      if (AccAnimales) Ids_Programa.push(2);
      if (AccCultivos) Ids_Programa.push(3);
      if (AccUsuarios) Ids_Programa.push(4);
      if (AccIA) Ids_Programa.push(5);
      const Ids_subPrograma = []
      if (AccDetector) Ids_subPrograma.push(1)
      if (AccModelos) Ids_subPrograma.push(2)
     
      const sendDataPerfilEdit = [{
        "Ids_Programa" : Ids_Programa,
        "Ids_Subp" : Ids_subPrograma,
        "nombre_perfil" : dataPerfilEdit.nombre_perfil,
        "estado" : "activo", // defecto Activo
        "ver" : String(perfilPrivObj.ver),
        "editar" : String(perfilPrivObj.editar),
        "eliminar" : String(perfilPrivObj.eliminar),
        "imprimir" : String(perfilPrivObj.imprimir),
        "agregar" : String(perfilPrivObj.agregar)
      }]

    // Data Usuario Activo (obj)
    const userActive = JSON.parse(this.validateService.valSession().data)
    
    // Validacion Usuario no debe actualizar su Propio Perfil
    if( userActive.Id_Perfil_Movil == dataPerfilEdit.id_perfil){
      // Msj Notificacion
      this.funct_msjAct("No Puede Editar Su propio Perfil")
      this.deleteNot = "red"
      setTimeout(()=>{
        this.deleteNot = ""
      }, 3000)

      modalA.dismiss();
      return 0
    }

      console.log(dataPerfilEdit.id_perfil)
      console.log( sendDataPerfilEdit)

      // Editar Peril (Movil)
      await this.usersService.editarPerfil(dataPerfilEdit.id_perfil, sendDataPerfilEdit).subscribe(
        (res) => {
          modalA.dismiss();
          this.funct_msjAct("Perifil Editado Exitosamente")     
          
        },(error)=>{
          // this.isModalOpenAPI = true;
          // --------------------------------------- considerar al Transpile a JAVA
          modalA.dismiss();
          this.funct_msjAct("Perifil Editado Exitosamente")     
        }
      );    
        
      
    }
    
    return 0


  }

  // Eliminar Perfil [perfil, programa, subPrograma]
  async deletePerfil(id:number){
    await this.usersService.deletePerfil(id).subscribe(
      (res) => {

        if(res){
          // Observar Cambios
          this.listPerfilUsers(); 
          this.listUsersSinPerfil();
          this.funct_msjAct("Perifil Eliminado Exitosamente")
        }else{
          this.funct_msjAct("Se Encuentró Usuario(s) en Ese Perfil")
          this.deleteNot = "red"
          setTimeout(()=>{
            this.deleteNot = ""
          }, 3000)
        }



      },(error)=>{
        this.isModalOpenAPI = true;
      }
    );
  }

  // Listar Usuarios 
  async getlistUsers(){
    await this.usersService.getlistUsers().subscribe(
    (res) => {
      this.usersList = res;
      // console.log(this.usersList)
    },(error)=>{
      this.isModalOpenAPI = true;
    }
    );
  }

  // Listar Usuarios Sin Perfiles
  async listUsersSinPerfil(){
    await this.usersService.listUsersNotPerfil().subscribe(
    (res) => {
      this.usuariosSinPerfil = res;
    },(error)=>{
      this.isModalOpenAPI = true;
    }
    );
  }

  // Listar Usuarios Determinado Perfil
  async listUsersForPerfil(id_perfil:number){
    await this.usersService.listUsersForPerfil(id_perfil).subscribe(
    (res) => {
      this.userForPerfil = res;
    },(error)=>{
      this.isModalOpenAPI = true;
    }
    );
  }

  // Listar Perfil Determinado Privilegios
  async listUsersForPriv(id_priv:number){
    await this.usersService.listUsersForPriv(id_priv).subscribe(
    (res) => {
      const ForPrivilegio:any = res;
      this.userForPrivilegio = ForPrivilegio[0]

      if(this.userForPrivilegio.ver == 'true'){this.Pver = true}else{this.Pver = false}
      if(this.userForPrivilegio.editar == 'true'){this.Pedit = true}else{this.Pedit = false}
      if(this.userForPrivilegio.imprimir == 'true'){this.Pimp = true}else{this.Pimp = false}
      if(this.userForPrivilegio.eliminar == 'true'){this.Peli = true}else{this.Peli = false}
      if(this.userForPrivilegio.agregar == 'true'){this.Pagr = true}else{this.Pagr = false}

    },(error)=>{
      this.isModalOpenAPI = true;
    }
    );
  }

  // Listar Perfil Determinado Acceso
  async listUsersForAcc(id_priv:number){
    await this.usersService.listUsersForAcc(id_priv).subscribe(
    (res) => {
      this.userForAcc = res;
      this.verifyPropertis(res)
    
    },(error)=>{
      this.isModalOpenAPI = true;
    }
    );
  }

  // Listar Perfiles
  async listPerfilUsers(){
    await this.usersService.listPerfilUsers().subscribe(
    (res) => {
      this.perfiles = res;
    },(error)=>{
      this.isModalOpenAPI = true;
    }
    );
  }

  // manejador Mensaje 'Accion'
  funct_msjAct(msj:string){
    this.msj_act = msj
    setTimeout(()=>{
      this.msj_act = ""
    }, 3000)
  }
  
  // cerrar modal [Conexion API]
  handleModalDismiss() {
   this.isModalOpenAPI = false; 
  }
  
  // Cambio Estado opciones  
  allOpcion(){
    this.programas = !this.state
    this.animales = !this.state
    this.cultivos = !this.state
    this.usuarios = !this.state
    this.ia = !this.state
    this.modelo = !this.state
    this.detector = !this.state

    this.state = !this.state
  }

  // Inicializar Valores Form Agregar
  resetFormAgregar(){
    this.state = true
    this.allOpcion();
    this.userSelect = ""
    this.nombre_perfil = ""
    this.msj_Error.statePrv = false;
    this.msj_Error.stateOpc = false;
    this.msj_Error.state = false;

    this.editar = !this.state
    this.eliminar = !this.state
    this.imprimir = !this.state
    this.agregar = !this.state
  }

  // Validat Obj
  validarObjet(obj:Obj) {
    let resultado = false; // Variable para almacenar el resultado
  
    for (const key in obj) {
      if (obj[key] === true) {
        resultado = true;
        break; // Salir del bucle si se encuentra un valor true
      }
      if (typeof obj[key] === 'object' && obj[key] !== null) {
        if (this.validarObjet(obj[key])) {
          resultado = true;
          break; // Salir del bucle si se encuentra un valor true en un objeto anidado
        }
      }
    }
  
    return resultado; // Devolver el resultado al final de la función
  }

  // validar Nombre y Usuario esten definidos
  validarInputPerfil() {
    if(!this.nombre_perfil ){
      this.msj_Error.state = true
    }else{
      this.msj_Error.state = false
    }

    if(!this.validatePriv()){
      this.msj_Error.statePrv = true
    }else{
      this.msj_Error.statePrv = true
    }

  }

  // Cargar [privilegios, perfil]
  cargarDataPerfil(id_perfil:number){
    this.listUsersForPriv(id_perfil);
    this.listUsersForPerfil(id_perfil)
    this.listUsersForAcc(id_perfil)
  }

  // Verificar Propiedades Acess
  verifyPropertis(objeto:any){  
    const properties = ["Animales", "Cultivos", "Usuarios", "IA"];
    properties.forEach(prop => {
        this[`Acc${prop}`] = objeto.programa.includes(prop);
    });

    if (objeto.subPrograma.includes("Modelos")) {
      this.AccModelos = true
    }else{
      this.AccModelos = false
    }
    if (objeto.subPrograma.includes("Detector")) {
      this.AccDetector = true
    }else{
      this.AccDetector = false
    }
    
  }
 

  // Cambio de Pagina
  CambioPag(){
    this.pagUsuarios = true;
    this.pagPerfil = false;
  }
  CambioPag2(){
    this.pagUsuarios = false;
    this.pagPerfil = true;
  }

  // Asociar Usuario -> Perfil
  async aggUsuario(modalA:any){

    // Validar Campos Asociar Perfil/Usuario
    if(!this.usuariForPerf || !this.perfilForPerf){
      return this.valER = true
    }else{
      this.valER = false
    }
    const dataUsuarioPerfil = {
      id_usuario : this.usuariForPerf,
      id_perfil  : this.perfilForPerf
    }

    await this.usersService.asocUserPerfil([dataUsuarioPerfil]).subscribe(
      (res) => {
        this.listUsersSinPerfil()
        this.listPerfilUsers(); 
        this.getlistUsers();
        // this.usersList = res;
      },(error)=>{
      // --------------------------------------- considerar al Transpile a JAVA
      this.listUsersSinPerfil()
      this.listPerfilUsers(); 
      this.getlistUsers();
      //   this.isModalOpenAPI = true;
      }
    );
  
    // Resear Valores
    modalA.dismiss()
    this.usuariForPerf = "";
    this.perfilForPerf= "";
    

    return 0
    
  }

  // Editar un Usuarios -> Perfil
  async editarUsuarioXPerf(modal:any, id_user:any){
    
    // Data Usuario Activo (obj)
    const userActive = JSON.parse(this.validateService.valSession().data)
    
    // Validacion Usuario no debe actualizar su Propio Perfil
    if( userActive.Id_Usuario == id_user.Id_Usuario){
      // Msj Notificacion
      this.msj_act_Usuario_ERR = "No Puede Editar Su propio Perfil"
      setTimeout(()=>{
        this.msj_act_Usuario_ERR = ""
      }, 3000)
      modal.dismiss();
      return 0
    }
    
    if(this.usuariForPerfEdit){
      
      // Opcion Sin Usuario (app)
      if(this.usuariForPerfEdit == "NotPefil"){
        this.usuariForPerfEdit = null
      }
      // data Usuario -> Perfil
      const dataUsuarioPerfil = {
        id_usuario : id_user.Id_Usuario,
        id_perfil  : Number(this.usuariForPerfEdit)
      }
      await this.usersService.asocUserPerfil([dataUsuarioPerfil]).subscribe(
        (res) => {
          this.listUsersSinPerfil()
          this.listPerfilUsers(); 
          this.getlistUsers();
          // Msj Notificacion
          this.msj_act_Usuario = "Perfil/Usuario Editado Exitosamente"
          this.funct_msjAct("Perfil/Usuario Editado Exitosamente")
        },(error)=>{
          // this.isModalOpenAPI = true;
          // --------------------------------------- considerar al Transpile a JAVA
          this.listUsersSinPerfil()
          this.listPerfilUsers(); 
          this.getlistUsers();
          // Msj Notificacion
          this.msj_act_Usuario = "Perfil/Usuario Editado Exitosamente"
          this.funct_msjAct("Perfil/Usuario Editado Exitosamente")
        }
      );

      modal.dismiss();

    }else{
      console.log('sin cambios')
      this.msj_ErrorUpdate = "No se A detectado algun Cambio"
    }

    return this.usuariForPerfEdit = ""


  }
  // Estado Modal Desvanecer (Resetear:valores)
  onModalDismiss() {
    this.msj_ErrorUpdate = "";
    this.usuariForPerfEdit = ""
  }

  // Evaluar Perfil del Usuario
  async usuarioXperfil(id_perfil:number){
    if(id_perfil == null){
      this.usersXperfil.nombre_perfil = "Sin Perfil App"
      this.colorUxP = 'red';
      return 0
    }

    await this.usersService.usuarioXperfil(id_perfil).subscribe(
      (res:any) => {
        if(!res){
          this.usersXperfil.nombre_perfil = "Sin Perfil App"
          this.colorUxP = 'red';
        }else{
          this.colorUxP = ""
          this.usersXperfil = res;
        }
      },(error)=>{
        this.isModalOpenAPI = true;
      }
    );
    return 1
  }


}



