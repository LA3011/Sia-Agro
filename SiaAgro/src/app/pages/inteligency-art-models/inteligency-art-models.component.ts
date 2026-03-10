import { Component, OnInit } from '@angular/core';
import { UsersService } from 'src/app/services/users.service';
import { FormGroup, FormControl, Validators } from '@angular/forms';
import { ReactiveFormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { ValidateService } from 'src/app/services/validate.service';
import { firstValueFrom } from 'rxjs';
import axios from 'axios'


@Component({
  selector: 'app-inteligency-art-models',
  templateUrl: './inteligency-art-models.component.html',
  styleUrls: ['./inteligency-art-models.component.scss'],
})
export class InteligencyArtModelsComponent  implements OnInit {

  rectificadorEntrenamiento:any 

  ultimateTimeModel:any

  // Estado LoginUser
  userActive:any

  // Verify API
  isModalOpenAPI = false

  // Notificacion
  message:any
  notificacion:any; // "expand" | "pricetags" | "double"
  // UpServer
  selectedInput!:string;
  selectedZip = '';
  selectedFile!: File;
  imageZip!: any;
  checkZip = false;
  checkInput = false;
  spinnerZip = false;
  isDisabledPosicZip = false;
  stepsProcess = 0; // 1
  // validateInputZip 
  msgErrorZipMB:any
  maxSizeInKB:any
  fileSizeError:any
  validNameTag:any
  notErrorZip!:boolean;
  msgErrorTag = "";
  msgErrorZip = "";
  msgError = "Ni Campos Vacios*";
  msgError2 = "Maximo 11 Caracteres*";
  msgError3:any;
  inputTextStyle = false;
  inputText!:boolean; 
  inputZipStyle!:boolean
  verifyConfirm = false
  file = false          
  inputTextIsset:any;  
  successConfirm = false;
  caracteres = 0
  // move
  valorActual = "";
  nameTag:any = "";
  // ResponseAPI's
  nameDir:any
  stepsSuc3:any
  cantClass:any
  clasess:any
  ExitpeticionHttp:any
  InfoListTag = {
    entrenamiento : 0,
    validacion : 0
  }
  Mydataset:any
  // styles Agregar-Entrena
  timeInicio!:number;
  expression1 = false;
  expression2 = false;
  expression3 = false;
  expression4 = false;
  expression5 = false;
  expression6 = false;
  expression7 = false;
  expression8 = false;
  dis = false;
  zip:any
  // styles Expandir-Tag
  ExpanExpression2 = false
  ExpanExpression3 = false
  btnOpenModalInfoTag = false
  // Menu
  menu = ''
  lisMytags:any
  tagExpandir:any
  tokenExistentTag?:string;
  dirExpand:any;
  // descripcionEtiqueta
  errorInputTextModal:any
  ModalInfIA = {
    nombre: '',
    definicion: '',
    tratamiento: '',
    familia: '',
    amenaza: true
  }
  myDescripTag = {
    definicion : '',
    familia : '',
    tratamiento : '',
    amenaza: null,
    mode : ''
  }
  errorInputTextModalExp:any
  btnOpenModalInfoTagExp=false
  controlerBtn = false

  constructor( 
    public usersService:UsersService, 
    public validateService:ValidateService,
    public router:Router)
  { 
    this.getRendimient()
  } 


  // content-rendimient
  rendimient = {
    detect: 0,
    tag: "0",
    right: 0,
    discarded: 0
  } 
  async getRendimient(){
    this.usersService.InfoRendimientoIA().subscribe({
      next: (data) => {
        this.rendimient = data;
      },
      error: (err) => console.error('Error al obtener rendimiento', err)
    });
  }


  // Formulario Reactivo
  formGroup = new FormGroup({
    fileZip: new FormControl(null), 
    // Expresión regular para letras y números (máx. 10 caracteres)
    textZip: new FormControl('', [
      Validators.required,
      // Validators.pattern('[0-9a-zA-Z]{1,20}'), 
    ]),  
  });
   
  ngOnInit() {
    this.ultimateTimeModel = "--:--:--"
    this.validateService.SessionRedirectOne('/login');     // Func. Validación [Redirecciona al no Estar Reg.]
    // this.ListenNotify();  
    this.viewNotify()

    this.userActive = this.validateService.valSession().status;           // Info. Estado-Usuarios
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



  // cerrar modal [Conexion API]
  handleModalDismiss() {
    this.isModalOpenAPI = false; 
  }

  // Visualizar Nofitificación
  async viewNotify() {

    await this.usersService.viewTagNotify().subscribe(
       (res) => { 
        const notifyObjeto = {
          exp : res.expansion,
          tag : res.etiqueta
        }

        if(notifyObjeto.exp != false){ this.notificacion = "expand"; }
        if(notifyObjeto.tag != false){ this.notificacion = "pricetags"; }
        if(notifyObjeto.tag == false && notifyObjeto.exp == false){ this.notificacion = "" }
        if(notifyObjeto.tag != false && notifyObjeto.exp != false){ this.notificacion = "double" }

        },(err) => {
          console.error('Error: Ver Noficacion:', err);
          this.isModalOpenAPI = true

        }
      )
  }
  // Nofitificación de Tag-Exp RESET
  async ResetNotify() {
    const Notify:any = [{
        "etiqueta": false,
        "expansion" : false
    }];

    try {
      const res = await axios.put(`${this.usersService.UrlUpdateTagNotify(1)}`, Notify);
      console.log('')
      console.log('Reseteada: Notificaciones 🟢')
      this.viewNotify();
    } catch (err) { 
      console.error('Error: Reset Noficacion:', err);
    }

    

    // await this.usersService.updateTagNotify(1, Notify).subscribe(
    //    (res) => { 
    //     console.log('')
    //     console.log('Reseteada: Notificaciones 🟢')
    //     this.viewNotify();
    //     },(err) => {
    //       console.error('Error: Reset Noficacion:', err);
    //     }
    // )
  }
  // Nofitificación de Tag-Exp RESET
  async AddTagNotify() {
    const Notify:any = [{
        "etiqueta": true,
        "expansion": null
    }];

    await this.usersService.updateTagNotify(1, Notify).subscribe(
       (res) => { 
        console.log('')
        console.log('Notificaciones: Agregar Etiqueta 🟢')
        this.viewNotify()
        },(err) => {
          console.error('Error: Agregar Etiqueta Noficacion:', err);
        }
      )
  }
  // Nofitificación de Tag-Exp RESET
  async AddExpNotify() {
    const Notify:any = [{
        "etiqueta": null,
        "expansion": true,
    }];

    await this.usersService.updateTagNotify(1, Notify).subscribe(
       (res) => { 
        console.log('')
        console.log('Notificaciones: Agregar Expansion Notificaciones 🟢')
        this.viewNotify();
        },(err) => {
          console.error('Error: Agregar Expansion Noficacion:', err);
        }
      )
  }


  // modalInfo. Etiqueta
  setOpenModalInfoTag(isOpen: boolean) {
    this.btnOpenModalInfoTag = isOpen;
  }
  // modalInfo. Etiqueta Expandir
  setOpenModalInfoTagExp(isOpen: boolean) {
    this.btnOpenModalInfoTagExp = isOpen;
  }
  // Validar: InputTextModalIA Informativo
  InputTextModal(modalInfo:any){

    this.errorInputTextModal = !this.ModalInfIA.definicion || !this.ModalInfIA.familia || !this.ModalInfIA.tratamiento;
    
    if (!this.errorInputTextModal) {
      modalInfo.dismiss();
      this.btnOpenModalInfoTag = false;
    }

  }
  // Validar: InputTextModalIA Informativo
  InputTextModalExp(modalInfo:any){

    this.errorInputTextModalExp = !this.myDescripTag.definicion || !this.myDescripTag.familia || !this.myDescripTag.tratamiento;

    if (!this.errorInputTextModalExp) {
      modalInfo.dismiss();
      this.btnOpenModalInfoTagExp = false;
    }

  }
  // Ver: Descripcion Etiqueta
  async descInfoM(modalnfo:any,tag:any){
    modalnfo.dismiss(); 

    this.setOpenModalInfoTag(true)

    await this.usersService.descInfoM(tag).subscribe(
      (res:any) => {
        this.myDescripTag.definicion = res.definicion;
        this.myDescripTag.familia = res.familia;
        this.myDescripTag.tratamiento = res.tratamiento;

        this.myDescripTag.amenaza = res.amenaza

      },(error) => {
        console.error('Error: Ver Detalle Etiqueta:', error);
      }
    );  
    
  }
  // Crear: Info. Descripcion 'Agregar'
  async sendInputTextModalIA(){
    await this.usersService.sendInputTextModalIA([this.ModalInfIA]).subscribe(
      (res:any) => {        
        console.log('Info. Etiqueta Almacenada Correctamente')
        this.ObjApply.mode = "Plaga"
        this.ObjApply.amenaza = true
        this.ObjApply.message = ""

      },(error:any) => {
        console.error('Error: Almacenar Info. Etiqueta', error);
      }
    );
  }
  // Ver: Formulario InputTextModalIA 'Expandir'
  myDescripTagBR:any
  async sendInputTextModalIAExp(ModalInfIA:any){
    await this.usersService.descInfoM(ModalInfIA).subscribe(
      (res:any) => {
        // console.log('PRUEBA EXPANCION', this.myDescripTag)
        this.myDescripTag.definicion = res.definicion;
        this.myDescripTag.familia = res.familia;
        this.myDescripTag.tratamiento = res.tratamiento;

        this.myDescripTag.amenaza = res.amenaza;

        if(this.myDescripTag.amenaza){
          this.myDescripTag.mode = "Plaga"
        }else{
          this.myDescripTag.mode = "Planta/Cultivo"

        }

        this.myDescripTagBR = res.tratamiento.replace(/<br>/g, '');  // sin br
        
        
      },(error) => {
        console.error('Error: Ver Detalle Etiqueta:', error);
      }
    );  
  }
  // Actualizar: Info. Descripcion 'Agregar'
  async UpdateInputTextModalIA(){
    console.log([this.myDescripTag])
    await this.usersService.UpdateInputTextModalIA(this.tagExpandir, [this.myDescripTag]).subscribe(
      (res:any) => {        
        console.log('Info. Etiqueta Actualizada Correctamente')
      },(error:any) => {
        console.error('Error: Almacenar Info. Etiqueta', error);
      }
    );
  }
  // Eliminar: Formulario InputTextModalIA
  async deleteInfoModalIA(tag:string){
    await this.usersService.deleteInfoModalIA(tag).subscribe(
      (res:any) => {        
        console.log('Info. Etiqueta Eliminada Correctamente')
      },(error:any) => {
        console.error('Error: Almacenar Info. Etiqueta', error);
      }
    );
  }
  // Reset InputTextModalIA
  resetInputTextModal(){
    this.ModalInfIA.tratamiento = ''
    this.ModalInfIA.definicion = ''
    this.ModalInfIA.familia = ''
    this.ModalInfIA.nombre = ''
    this.ModalInfIA.amenaza = true
    this.controlerBtn = false
  }


  // Validar: Input.Zip   
  onFileZipSelected(event: any) {

    if (event.target.files[0]) {
      this.zip = event.target.files[0]
      const fileName = event.target.files[0].name;
      this.file = fileName;
      const isZipFile = fileName.toLowerCase().endsWith('.zip');
      if (isZipFile) {
        this.notErrorZip = true; 
        const file = event.target.files[0];
        this.selectedFile = <File>event.target.files[0];
      } else {
        this.notErrorZip = false; // El archivo no es un .zip
      }
    }
    // validar tamaño del ZIP
    const file = event.target.files[0];
    if (file) {
      this.maxSizeInKB = 20480; // Tamaño Max-KB (20 MB)
      const fileSizeInKB = file.size / 1024;
      if (fileSizeInKB > this.maxSizeInKB) {
        this.fileSizeError = false
      } else {
        // Procesar el archivo
        this.fileSizeError = true;
      }
    }

  }
  // Almacenar: Cantidad de Caracteres | nombreEtiqueta
  onInput(event: Event) {
    const inputElement = event.target as HTMLInputElement;
    this.caracteres = inputElement.value.length;
  }
  // Validar: NombreEtiqueta, Arc.Zip | llamada Func. (Iniciar Periodo-Espera Arc.zip)
  onSubmit() {

    this.errorInputTextModal = !this.ModalInfIA.definicion || !this.ModalInfIA.familia || !this.ModalInfIA.tratamiento;

    if(this.errorInputTextModal){
      this.controlerBtn = true

    }

    // validar nombre etiqueta
    const tagVal = this.formGroup.controls.textZip.value;
    if(typeof tagVal === 'string' ){
      this.validNameTag = this.lisMytags.map((elemento:any) => elemento.toLowerCase()).includes(tagVal.toLowerCase());
    }

    // Styles ValidateInputs
    const notErrorTag = this.formGroup.controls.textZip.valid 
    const notErrorZip = this.notErrorZip
    if(!notErrorTag && !notErrorZip){
      this.inputTextStyle = true;
      this.inputZipStyle = true;
    }
    if(notErrorZip){
      this.checkZip = true;
      this.inputZipStyle = false;
    }else{
      this.inputZipStyle = true;
      this.checkZip = false;
      this.msgErrorZip = "Debe ser Formato .zip*"
    }
    if(notErrorTag){
      this.inputText = true;
      this.inputTextStyle = false;
    }else{
      this.inputTextStyle = true;
      this.inputText = false;
      this.msgErrorTag = "Sin caracteres especiales*"
    }

    if(this.tokenExistentTag != 'Expandir'){
      if(this.validNameTag){
        this.inputText = false
        this.inputTextStyle = true;
        this.msgError3 = "Etiqueta Existente*"
      }
    }else{
      this.validNameTag = false
    }
    
    // Confirmar llamada Func. (Iniciar Periodo-Espera Arc.zip)
    if(notErrorZip && notErrorTag){ 
      if (this.fileSizeError) {
        if(!this.validNameTag){
          if(!this.controlerBtn || this.tokenExistentTag == 'Expandir'){
            // console.log('Sin errores')
            this.nameTag =  this.formGroup.controls.textZip.value;
            this.ModalInfIA.nombre = this.nameTag
            this.uploadFileZip()
  
            this.menu = "AddTag"
          }
        }   
      }else{
        this.inputZipStyle = true;
        this.checkZip = false;
        this.msgErrorZipMB = 'Tamaño Maximo 20MB';
      }

    }

  }
  // PeriodoEspera (styles): Arc.zip
  async uploadFileZip(){ 

    this.successConfirm = true;
    this.spinnerZip = true;
    this.isDisabledPosicZip = true;
    this.stepsProcess += 1; 
    this.spinnerZip = false;
    this.isDisabledPosicZip = false;

    this.stepsSuccess0()
  } 
  // Subir: Arc.zip
  async stepsSuccess0(){

    console.log('');
    console.log("--- Subiendo Archivo.zip  ---");
    const formData = new FormData();
    formData.append('fileZip', this.selectedFile);

    await fetch(this.usersService.UrlUploadZip(), {
      method: 'POST',
      body: formData,
    })
    .then(response => {
      this.expression1 = true;
      console.log('Zip Subido: 🟢');
      this.validZipStruct(); // validar Zip struct

    })
    .catch(error => {
      console.error('Error al subir el Zip:', error);
    });


    // console.log('');
    // console.log("--- Subiendo Archivo.zip  ---");
    // const formData = new FormData();
    // formData.append('fileZip', this.selectedFile);
    // await this.usersService.uploadZip(formData).subscribe(
    //   ((res:any) => {

    //     this.validZipStruct(); // validar Zip struct

    //     }),(error:any) => {
    //       console.error('Error al subir el Zip:', error);
    //     }
    //   );
  }

  // Validar: Estructura Arch.Zip [comprimido/carpeta/fotos.jpg]
  async validZipStruct(){
    const res = await this.usersService.validTags().subscribe(
      (res) => {
        if(res){
          if(this.tokenExistentTag == "Agregar"){ // 'Agregar' ---> Etiqueta
            this.stepsSuccess();  

          }else{ // 'Expandir' ---> Dir existente  
            this.MoveExpandTag(); 
            this.UpdateInputTextModalIA()

          }

        }else{
          this.message = 'Error: Estructura No Esperada en el Archivo.zip'
          this.cancelarAddTagg()
          setTimeout(() => {
            this.message = undefined
          }, 15000);
        }
        
        console.log('ZIP tiene la estructura correcta: ',res);
        return res;

      },(error) => {
        console.error('ValidacionStruct Zip:', error);
      }
    );
    return res;
  }
  // Generar: Carpetas [Entrenamiento, Validacion]
  async stepsSuccess(){
      // GenerateDir
    console.log('');
    console.log("--- Generando Directorios ---");
    const nameTag = { name: this.nameTag };
    const ObjetNameTag = [nameTag]
    console.log(`Nombre-Etiqueta-Dir: ${this.nameTag}`);
    const tiempoInicio = performance.now();
    await this.usersService.move(ObjetNameTag).subscribe( 
      (res) => {
        const tiempoFinal = performance.now();
        const total = ( ((tiempoFinal - tiempoInicio)/1000) ); // seg
        this.expression2 = true;
        console.log(`Tiempo-Gener-Dir: ${total/60} min`); // min
        console.log('Directorio Creado: 🟢');
        this.nameDir = res;

        this.stepsSuccess2();

      },(error) => {
        console.error('Error al Crear Directorio:', error);
      }
    );
  }



  // Descomprimir: Arch.zip = zipProcess.zip [./Entrenamiento/Tag]
  async stepsSuccess2(){ 
    console.log('');
    const dir = {
      'dir': this.nameTag,
    }    
    console.log("--- Descomprimir Arch.zip ---"); 
    const ObjDir = [dir]; // Convert Obj 
    try {

      const res = await axios.put(`${this.usersService.UrlDesc()}`, ObjDir);
      this.nameDir.dir = res.data
      console.log(this.nameDir.dir);
      this.expression3 = true;
      console.log('Zip Descomprimido: 🟢');
         
      this.stepsSuccess3();
          
    } catch (error) { 
      console.error('Zip Descomprimido:', error);
    }


    // NO SE DESARROLLA (COMPLETAMENTE)
    // await this.usersService.desc(ObjDir).subscribe(
    //   (res) => {
    //     this.nameDir.dir = res
    //     console.log(`Nombre Dir Descomprimir: ${res}`);
    //     this.expression3 = true;
    //     console.log('Zip Descomprimido: 🟢');
         
    //     this.stepsSuccess3();

    //   },(error) => {
    //     console.error('Zip Descomprimido:', error);
    //   }
    // );
  }


  // Distribuir: 60%: [./Entrenamiento/Tag], 40%: [./Validacion/Tag] 
  async stepsSuccess3(){    
    console.log('');
    const dir = {
      'nameDir': this.nameDir.dir,
      'tag': this.nameTag
    };
    const ObjDir = [dir]; // Convert Obj 

    try {
      const response = await fetch(this.usersService.Urldist(), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(ObjDir)
      });
      if (!response.ok) {
        throw new Error(`Error al subir el Zip: ${response.statusText}`);
      }
      const res = await response.json();
      this.stepsSuc3 = res;
      this.expression4 = true;
      console.log(`Cantidad-Arch: ${this.stepsSuc3.cantArch}`); // min
      console.log('Zip Distribuido: 🟢');

          // API TEST
          await this.usersService.test("stepsSuccess3 then()").subscribe(
          (res) => {
            console.log("test API")
          },(error) => {
            console.error('Test:', error);
          }


    );


      this.stepsSuccess4();
    } catch (error) {
      console.error('Zip Distribuido:', error);

    }


    // console.log('');
    // const dir = {
    //   'nameDir': this.nameDir.dir,
    //   'tag': this.nameTag
    // }
    // console.log("--- Distribuyendo Arch.zip ---"); 
    // const ObjDir = [dir]; // Convert Obj 
    // await this.usersService.dist(ObjDir).subscribe(
    //   (res:any) => {        
    //     this.stepsSuc3 = res
    //     this.expression4 = true;
    //     console.log(`Cantidad-Arch: ${this.stepsSuc3.cantArch}`); // min
    //     console.log('Zip Distribuido: 🟢');

    //     this.stepsSuccess4();

    //   },(error) => {
    //     console.error('Zip Distribuido:', error);
    //   }
    // );

  } 
  // Limpiando Directorios
  async stepsSuccess4(){
    console.log('');
    const dir = {
      'nameDir': this.nameDir.dir,
      'tag': this.nameTag
    }
    console.log("--- Limpiando Directorios ---"); 
    const stepsobject = [dir]
    try {
      await axios.put(`${this.usersService.UrlLimpMove()}`,stepsobject);
      this.expression5 = true;      
      this.sendInputTextModalIA(); // Enviar Data Etiqueta BD
      this.stepsSuccess5();
          
    } catch (error) { 
      console.error('Directorios Limpios:', error);
    }

    // console.log('');
    // const dir = {
    //   'nameDir': this.nameDir.dir,
    //   'tag': this.nameTag
    // }
    // console.log("--- Limpiando Directorios ---"); 
    // const tiempoInicioDesc = performance.now();
    // const stepsobject = [dir]
    // await this.usersService.limpMove(stepsobject).subscribe(
      // (res:any) => {
      //   // const tiempoFinalDesc = performance.now();
      //   // const total = ( ((tiempoFinalDesc - tiempoInicioDesc)/1000) ); // seg
      //   // this.expression5 = true;
      //   // console.log(`Tiempo-Dist-Zip: ${total/60} min`); // min
      //   // console.log('Directorios Limpios: 🟢');
        
      //   // this.sendInputTextModalIA(); // Enviar Data Etiqueta BD
      //   // this.stepsSuccess5();

      // },(error) => {
      //   console.error('Directorios Limpios:', error);
      // }
    // );
  }
  // Modificar: Arch.py [predecir_umbral.py]
  async stepsSuccess5(){
    console.log('');
    const tag = {
      'tag': this.nameTag,
    }
    console.log("--- Preparando Modelo ---"); 
    const object = [tag]
    await this.usersService.write(tag.tag).subscribe(
      (res:any) => {
        const tiempoFinalDesc = performance.now();
        this.expression6 = true;
        console.log('Preparacion: 🟢');
        this.cantClass = res 

        this.messageSuccess = "Etiqueta Agregada Exitosamente"  
        setTimeout(() => {
          this.messageSuccess = ""
        }, 5000);

        this.stepsSuccess6();

      },(error) => {
        console.error('Preparacion Listo:', error);
      }
    ); 
  }
  // Modificar: Arch.py [entrenamiento.py]
  async stepsSuccess6(){ 
    const classes = { 
      'class': { value: this.cantClass }
    };
    const object = [classes]
    await this.usersService.writeEntren(classes.class.value).subscribe(
      (res:any) => {
        this.resetInputTextModal();
        this.clasess = res // N~Clases Model
        this.cancelar();
        this.menu='listTag';
        this.listTag();
        this.AddTagNotify(); 

      },(error) => {
        console.error('entrenamiento.py:', error);
      }
    );

    
  }

  // Inicializadores | BackBTN
  initStyle(){
    this.timeInicio = 0;
    this.expression1 = false;
    this.expression2 = false;
    this.expression3 = false;
    this.expression4 = false;
    this.expression5 = false;
    this.expression6 = false;
    this.expression7 = false;
    this.expression8 = false;
    this.ExpanExpression2 = false
    this.ExpanExpression3 = false
    this.dis = false
    this.caracteres = 0
  }
  initInputZip(){
    this.inputTextStyle = false;
    this.verifyConfirm = false
    this.file = false          
    this.successConfirm = false;
    this.checkZip = false;
    this.checkInput = false;
    this.spinnerZip = false;
    this.isDisabledPosicZip = false;
    this.inputZipStyle = false;
    this.msgErrorZip = "";
    this.msgErrorTag = "";
    this.formGroup.reset();
    this.inputText = false;
    this.notErrorZip = false
    this.caracteres = 0
  }
  cancelar(){
    this.viewNotify() // Notificiacion
    // this.ListenNotify(); 
    this.stepsProcess=1; // Inicializando 
    this.initStyle();    
    this.initInputZip();
  }
  cancelarMenu(){
    this.stepsProcess=0; 
    this.menu='';
    this.viewNotify()

    this.caracteres = 0
    this.tokenExistentTag = ''
  }


  // Vista: Entrenamiento
  async entrenar(){

    this.ultimateTimeModel = "--:--:--"

    // Registro Ultimo tiempo Entrenamiento
    const res:any = await axios.get(`${this.usersService.UrldataSet()}`); // Cambia la URL por la correcta para tu servicio

        if(res){
          this.ultimateTimeModel = res.tiempo  
        }
        
        this.dataset();
        this.cancelar();
        this.stepsProcess = 2;
        this.menu = "trainTag"
        this.rectificadorEntrenamiento = false

  }
  // Almacenar: Info. DataSet
  async dataset(){

    console.log('');
    console.log("--- Información DataSet ---");
    
    try {
      const response = await axios.get(`${this.usersService.UrldataSet()}`); // Cambia la URL por la correcta para tu servicio
    
      if (response.data) {
        const res = response.data;
        console.log(`Total Clases: ${res.directorios}`);
        console.log(`Arch Validación: ${res.validacion}`);
        console.log(`Arch Entrenamiento: ${res.entrenamiento}`);
        console.log(`Arch Total: ${res.total}`);
        this.Mydataset = res;
    
        // Empezando Configuración
        this.autoConfig();
      }
    } catch (error) {
      console.error('Config-Entrenamiento', error);
      this.isModalOpenAPI = true;
    }
  }
  // Modificar: Entrenamiento.py [Valores DataSet]
  async autoConfig(){

    const data = this.Mydataset
    console.log('');
    console.log("--- Configuracion Entrenamiento ---"); 
    const response = await axios.put(`${this.usersService.UrlautoConfig()}`, [data]);  
    if (response.data) {
      // AutoConfiguración 'entrenar.py'
      console.log("---->" ,response.data);
      
      if (this.menu === "trainTag") {
        // Empezando Entrenamiento
        this.stepsSuccessPre7();
      }
    }

  }





  // Verificacion: 'Procesos Python' [Si Se encuetra un Entrenamiento Previo]
  peticion:any
  async stepsSuccessPre7(){
    console.log('');
    console.log("--- Verificando si Hay Otra Ejecución.py ---");

    try {

        const res: any = await axios.get(this.usersService.UrlVerifyProcessEntren());
        this.peticion = res.data; // Asumiendo que la respuesta relevante está en "data"

          if (res.data === false) { // Evaluamos el contenido de "data"
              this.stepsSuccess7();
              this.rectificadorEntrenamiento = true;
          } else {
              this.menu = '';
              this.stepsProcess = 0;
              this.message = 'Ya Se está Ejecutando un Entrenamiento, Por favor Espere...';

              setTimeout(() => {
                  this.message = undefined;
              }, 8000);
          }

    } catch (error) {
            console.error('Entrenamiento:', error);
    }

    this.ExitpeticionHttp = this.peticion;
  } 
  // Ejecucion: Entrenamiento 'Python'
  async stepsSuccess7(){
    console.log('');
    console.log("--- Entrenamiento Modelo ---"); 
    // time:contador
    this.resetTimer()
    this.startTimer();
    const Objectnathing =  [{ nada : 'nada' }];


    try {
      const res = await axios.post(`${this.usersService.UrlEntren()}`, Objectnathing);
      if(res){
        this.expression7 = true;
        console.log('Fase-Entrenamiento: 🟢');
        this.stepsSuccess8();
          // Registrar tiempo Entrenamiento
          await axios.get(`${this.usersService.UrlTimeModel(this.formattedTime)}`);
          this.resetTimer()
      }
      
    } catch (error) { 
      console.error('Entrenamiento:', error);
      this.message = "Disculpe, se ha Producido un Error en el Entrenamiento"
      this.cancelarMenu()    
      this.resetTimer()
      setTimeout(() => {
        this.message = undefined
      }, 8000);
    }



    // const peticion = await this.usersService.entren(Objectnathing).subscribe(
    //   async (res:any) => {
    //       // console.log(`Respuesta-Modelo: ${res}`)   // AL CRUDO PYTHON
    //       if(res){
    //         this.expression7 = true;
    //         console.log('Fase-Entrenamiento: 🟢');
    //         this.stepsSuccess8();
            // Registrar tiempo Entrenamiento
            // await this.usersService.timeModel(this.formattedTime).subscribe(
            //   (res:any) => {
            //       this.resetTimer()
            //       console.log("tiempo registrado")
            //   }
          //   )
          // }
      // },(error:any) => {
      //   console.error('Entrenamiento:', error);
      //   this.message = "Disculpe, se ha Producido un Error en el Entrenamiento"
      //   this.cancelarMenu()    
      //   this.resetTimer()
      //   setTimeout(() => {
      //     this.message = undefined
      //   }, 15000);

      // }
    // );
    
    // this.ExitpeticionHttp = peticion;
  }
  // Limpieza: Pesos-Models Anteriores | Mover: Pesos-Models Nuevos
  async stepsSuccess8(){

    const nada = [{ tag : 'nada' }]; 

    try {
      const res = await axios.post(`${this.usersService.UrlCompleted()}`, nada);
      console.log('');
      console.log("--- Resultado Modelo ---"); 
      console.log('Entrenamiento Completado: 🟢');

      this.expression8 = true;
      this.ResetNotify();
      this.cancelarMenu();
      this.listTag();
      this.PredicWrite();
      
    } catch (error) { 
      console.error('Entrenamiento CompletadoError:', error);
    }
    
    // await this.usersService.completed(nada).subscribe(
    //   (res:any) => {
    //     console.log('');
    //     console.log("--- Resultado Modelo ---"); 
    //     const tiempoFinalDesc = performance.now();
    //     const total = ( ((tiempoFinalDesc - tiempoInicioDesc)/1000) ); // seg
    //     this.expression8 = true;
    //     console.log('Entrenamiento Completado: 🟢');
        
    //     this.ResetNotify();
    //     this.cancelarMenu();
    //     this.listTag();
    //     this.PredicWrite();

    //   },(error:any) => {
    //     console.error('Entrenamiento CompletadoError:', error);
    //   }
    // );
  }

  // Function: Modificando Arch.predict.py
  async PredicWrite(){

    try {
      const res = await axios.get(`${this.usersService.UrlPredicWrite()}`);
      const resp = res 
    } catch (error) { 
      console.error('Error al DesEtiquetar:', error);
    }
    
    // await this.usersService.PredicWrite().subscribe(
    //   (res:any) => {
    //     const resp = res 
    //   },(error) => {
    //     console.error('Error al DesEtiquetar:', error);
    //   }
    // );
  }
  // Function: Cancelar Entrenamiento.py
  async cancelarEntren(){

    if (this.ExitpeticionHttp) {
      this.ExitpeticionHttp.unsubscribe();
      console.log('Petición cancelada');
      console.log("--- Entrenamiento Cancelado 🔴 ---")
    }

    await this.usersService.killProcessEntren().subscribe(
      (res:any) => {
        console.log(res)
      },(error) => {
        console.error('ERROR: KILL PROCESS PY ', error);
      }
    );

    this.menu = '';
    this.stepsProcess = 0;
  }

  // Cerrar: Modales
  cancelarAddTagg(){
    this.menu = 'listTag';
    this.listTag()
    this.cancelar();
    this.resetInputTextModal();

  }
  cancelarInfoTagg(modalD:any){
    modalD.dismiss();
    setTimeout(()=>{
      this.InfoListTag = {
        entrenamiento:0,
        validacion:0
      }
    },500)
  }

  // Gestion Etiquetas
  myTags(){ // Listar Etiquetas (controlador) 
    this.menu = "listTag"
    this.listTag();
  }
  async deleteTag(tag:any, modalD:any){ // Eliminar: Etiqueta
    
    // eliminar la etiqueta de la (vista)  
    const arreglo = this.lisMytags 
    const elementoAEliminar = tag;
    const nuevoArreglo = arreglo.reduce((resultado:any, elemento:any) => {
      if (elemento !== elementoAEliminar) {
        resultado.push(elemento);
      }
      return resultado;
    }, []);
    this.lisMytags = nuevoArreglo
  
    // eliminar la etiqueta del (modelo)
    const data:any = await this.usersService.deleteTags(tag).subscribe(
      (res) => {
        // console.log(`${res} 🟢`);
        this.deleteInfoModalIA(tag)
      },(error) => {
        console.error('Error al Listar Etiquetas:', error);
      }
    );    

    // cerrar modal
    modalD.dismiss(); 

  }
  async listTag(){ // Listar Etiqueta(s)
    await this.usersService.listTags().subscribe(
      (res) => {
        this.lisMytags = res 
        // console.log(res)
      },(error) => {
        console.error('Error al Listar Etiquetas:', error);
        this.isModalOpenAPI = true
      }
    );
  }
  async listTagInfo(tag:any){ // Mostrar Inf. Etiqueta
    await this.usersService.listTagsInfo(tag).subscribe(
      (res:any) => {
        this.InfoListTag.entrenamiento = res.entrenamiento;
        this.InfoListTag.validacion = res.validacion;
        console.log(res)

      },(error) => {
        console.error('Error al Listar Etiquetas:', error);
      }
    );
  }
  expandTag(tag:any){ // Expandir Etiqueta (controlador)
    this.menu = ''
    this.stepsProcess = 1
    this.tagExpandir = tag;
    this.nameTag = this.formGroup.controls['textZip'].setValue(tag);
    this.tokenExistentTag = 'Expandir';

    this.sendInputTextModalIAExp(tag);

  } 
  async MoveExpandTag(){ // Mover zipProcess.zip [./Entrenamiento/tag]
    await this.usersService.MoveExpandTag(this.tagExpandir).subscribe(
      (res) => {
        console.log(`${res} 🟢`)
        this.ExtraExpandTag();
      },(error) => {
        console.error('Error al Listar Etiquetas:', error);
      }
    );
  }
  async ExtraExpandTag(){ // Descomprimir zipProcess.zip [./Entrenamiento/tag]
  const tagObj = [{
    "tag": this.tagExpandir,
  }]

  try {
    const res = await axios.put(`${this.usersService.UrlExtraExpandTag()}`, tagObj);
    this.dirExpand = res.data // nameDir despues de descomprimirlo
    this.ExpanExpression2 = true
    this.DistExpandTag();
  } catch (error) { 
    console.error('Error al Descomprimir Expand:', error);
  }


  // await this.usersService.ExtraExpandTag(tagObj).subscribe(
    // (res:any) => {
    //   this.dirExpand = res // nameDir despues de descomprimirlo
    //   this.ExpanExpression2 = true
    //   this.DistExpandTag();
  //   },(error:any) => {
  //     console.error('Error al Descomprimir Expand:', error);
  //   }
  // );

  }
  messageSuccess:string = ""
  async DistExpandTag(){ // Distribuir zipProcess.zip 60%, 40%
    const tag = this.tagExpandir
    const tagObj = [{
      "tag": tag,
      "dir": this.dirExpand 
    }]

    try {
      const res = await axios.post(`${this.usersService.UrlDistExpandTag()}`, tagObj);
        console.log(`${res.data} 🟢`)
        this.ExpanExpression3 = true
        this.menu='listTag';

        this.messageSuccess = "Etiqueta Expandida Exitosamente"  
        setTimeout(() => {
          this.messageSuccess = ""
        }, 5000);

        this.AddExpNotify();
        this.listTag();
        this.cancelar();

      
    } catch (error) { 
      console.error('Error al Listar Etiquetas:', error);
    }


    // await this.usersService.DistExpandTag(tagObj).subscribe(
      // (res) => {
      //   console.log(`${res} 🟢`)
      //   this.ExpanExpression3 = true
      //   this.menu='listTag';
      //   this.AddExpNotify();
      //   this.listTag();
      //   this.cancelar();

    //   },(error) => {
    //     console.error('Error al Listar Etiquetas:', error);
    //   }
    // );
  }


  // Agregar Etiqueta (Controlador)
  addTagMenu(){ 
    this.stepsProcess = 1; 
    this.menu = ''
    this.tokenExistentTag = 'Agregar';
    this.controlerBtn = false
  }
  // Expandir Etiqueta (Controlador)
  readonlyExpandir(): boolean {
    return this.tokenExistentTag === 'Expandir';
  }


  ObjApply = {
    mode: 'Plaga',
    amenaza: true,
    message: '',
  }

  onAmenazaChange(value: any){ 

    this.ObjApply.amenaza = value

    if(this.ObjApply.amenaza){
      this.ObjApply.mode = "Plaga"
      this.ObjApply.message = ""
      this.ModalInfIA.tratamiento = this.ObjApply.message
      this.ModalInfIA.amenaza = this.ObjApply.amenaza


    }else{
      this.ObjApply.mode = "Planta/Cultivo"
      this.ObjApply.message = "No Aplica"
      this.ModalInfIA.tratamiento = this.ObjApply.message
      this.ModalInfIA.amenaza = this.ObjApply.amenaza

    }

    console.log(this.ObjApply)
    console.log(this.ModalInfIA)
  }


  onAmenazaChangeUpdate(value: any){ 

    this.myDescripTag.amenaza = value

    if(this.myDescripTag.amenaza){
      this.myDescripTag.mode = "Plaga"
      this.myDescripTag.tratamiento = ""

    }else{
      this.myDescripTag.mode = "Planta/Cultivo"
      this.myDescripTag.tratamiento = "No Aplica"

    }

    console.log(this.myDescripTag)
  }

  
// Time: Entrenamiento 
days: number = 0;
hours: number = 0;
minutes: number = 0;
seconds: number = 0;
intervalId: any;
formattedTime: string = '00:00:00';

startTimer() { 
  this.intervalId = setInterval(() => this.tick(), 1000); 
} 
tick() {
 this.seconds++;
  if (this.seconds >= 60) { 
    this.seconds = 0; this.minutes++; 
    if (this.minutes >= 60) {
      this.minutes = 0; 
      this.hours++; 
      if (this.hours >= 24) { 
        this.hours = 0; this.days++; 
      } 
    } 
  } 
  this.updateFormattedTime(); 
} 
updateFormattedTime() { 
  this.formattedTime = `${this.hours.toString().padStart(2, '0')}:${this.minutes.toString().padStart(2, '0')}:${this.seconds.toString().padStart(2, '0')}`; 
} 
stopTimer() { 
  clearInterval(this.intervalId); 
}
resetTimer() { 
  this.stopTimer(); 
  this.hours = 0; 
  this.minutes = 0; 
  this.seconds = 0; 
  this.updateFormattedTime(); 
}


}
