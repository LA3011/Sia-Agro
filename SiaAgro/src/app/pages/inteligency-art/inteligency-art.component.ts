import { HttpClient } from '@angular/common/http';
import { Component, OnInit } from '@angular/core';
import { UsersService } from 'src/app/services/users.service';
import { NgModel } from '@angular/forms';
import { ValidateService } from 'src/app/services/validate.service';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';

 
@Component({
  selector: 'app-inteligency-art',
  templateUrl: './inteligency-art.component.html',
  styleUrls: ['./inteligency-art.component.scss'],
})
export class InteligencyArtComponent  implements OnInit {

  // Modal-Asignacion Cultivos  
  btnCutivo:boolean = true;
  opcionUpdateCultPLaga:boolean = false
  confirmUpdateCultivo:boolean = false

  // color btn Fab
  colorFab:any = "medium"

  // Direccion IP
  ipAdress:any;

  // Carga Info. Usuario
  userPass = {};

  // Styles [btn, spinners, msj]
  statusImage = "Empezar Detección";
  buttonArtify = false;
  isDisabled = false;
  spinner2 = false; 
  spinner = false;

  // [InfoDeteccionIA, ControladorModalesIA] 
  dataSet:any
  dataSetModal:any
  messageTarget = '' 
  
  // manejo estado de los Inputs
  status = "";
  hidden = false;
  imageSrc!: any;
  selectedFile!: File;
  selectedImage: string = "";
  isDisabledPosicImag = false;
  
  // Controlador, Response Detecion
  isModalOpen:any

  // Controlador, Conexión APIs
  isModalOpenAPI=false
  
  constructor(
    private http: HttpClient, 
    public usersService:UsersService,
    public validateService:ValidateService
  ){}

  ngOnInit() {
    this.validateService.SessionRedirectOne('/login');     // Func. Validación [Redirecciona al no Estar Reg.]
    this.userPass = this.validateService.valSession().status; // Info. Estado-Usuarios
    console.log(`User Session: ${this.userPass}`);
    console.log(`User Data: ${this.validateService.valSession().data }`);

    this.listCultivoPlaga() // listar cultivos-Plagas
    this.resetlikes()
    this.ipAdress = this.usersService.AddressAPIs() + "/inteligencyArtificial"
  }

  // evaluar respuesta modelo IA
  like = false
  dislike = false
  likeResponse(){
    this.like = true
    this.dislike = false
  }
  dislikeResponse(){
    this.dislike = true
    this.like = false
  }
  resetlikes(){
    this.like = false
    this.dislike = false
  }
  async sendLikes(){
    await this.usersService.sendLikesIA(this.like, this.dislike).subscribe(
      (res) => {
        console.log(res)
      },(erro) =>{
        console.error('Error al Enviar Likes');
      }
    );
    this.resetlikes()

  }

  // [Conexion API]
  handleModalDismiss() {
    this.isModalOpenAPI = false; // cerrar modal [Conexion API]
  }

  async contadorDetect(){
    await this.usersService.setDetectionIA().subscribe(
      (res) => {
        console.log(res)
         
      },(erro) =>{
        console.error('Error al Contar Deteccion');

      }
  );
  }
    
  // Captura de Foto
  capturedImage: File | undefined | any; // Almacena la imagen capturada en formato File
  
  async takePicture() {
    try {
      const photo = await Camera.getPhoto({
        quality: 90,
        allowEditing: false,
        resultType: CameraResultType.Base64,
        source: CameraSource.Prompt, 
        
      });
  
      if (photo.base64String) {
        // Convertir Base64 a Blob
        const byteCharacters = atob(photo.base64String);
        const byteNumbers = new Array(byteCharacters.length);
        for (let i = 0; i < byteCharacters.length; i++) {
          byteNumbers[i] = byteCharacters.charCodeAt(i);
        }
        const byteArray = new Uint8Array(byteNumbers);
        const blob = new Blob([byteArray], { type: "image/jpeg" });
  
        // Simular la lógica del input file
        this.capturedImage = new File([blob], "captured_image.jpg", { type: "image/jpeg" });
  
        const reader = new FileReader();
        reader.onload = () => {
          this.imageSrc = reader.result as string; // Guardar en formato Base64
          this.buttonArtify = false;
          this.hidden = true;
          console.log("Imagen procesada desde la cámara:", this.imageSrc);
        };
        reader.readAsDataURL(this.capturedImage);
        
      } else {
        console.error("No se capturó ninguna imagen.");
      }
  
    } catch (error) {
      console.error("Error al tomar la foto:", error);
    }
  }

  // Carga-Image Input
  onFileSelected(event: any) {
    this.status = "";
    this.selectedFile = <File>event.target.files[0]; // Foto capturada como archivo:
    this.hidden = true;
    if (event.target.files && event.target.files[0]) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = e => this.imageSrc = reader.result;
      reader.readAsDataURL(file);
      this.buttonArtify = false;
    }
  }

  // reloj tiempo deteccion IA
  tiempoTotalDetec: number = 0;
  tiempoInicioDetec: number | null = null;
  comenzarTiempoDetec() {
    this.tiempoInicioDetec = performance.now();
  }
  detenerTiempoDetec() {
    if (this.tiempoInicioDetec === null) {
      console.warn("Advertencia: Se intentó detener el tiempo sin haberlo iniciado.");
      return 0;
    }

    const ms = performance.now() - this.tiempoInicioDetec;
    this.tiempoTotalDetec = ms / 1000;
    this.tiempoTotalDetec = Number(this.tiempoTotalDetec.toFixed(1))

    // Limpiamos el inicio para la próxima medición
    this.tiempoInicioDetec = null; 

    console.log(`Tiempo de Detección IA: ${this.tiempoTotalDetec.toFixed(2)} ms`);
    return this.tiempoTotalDetec;
  }
  

  // Upload-Image-Server
  async uploadFile() {

    // monitoriar tiempo de deteccion IA
    this.comenzarTiempoDetec();

    // contador detecciones
    this.contadorDetect();

    this.spinner2 = true;
    this.isDisabledPosicImag = true;
  
    try {
      // Reemplazar this.selectedFile por la imagen capturada desde la cámara
      const formData = new FormData();
      formData.append('fileImage', this.capturedImage); // Usamos la imagen obtenida con la cámara
  
      const response = await fetch(this.ipAdress, {
        method: 'POST',
        body: formData,
      });
  
      console.log('🟢 Preparing "Button" Detection 🤖');
      this.setReset(); // Reseteo después de subir
      this.intelify(); // Lógica adicional después del envío
    } catch (error) {
      this.isModalOpenAPI = true;
      this.setReset(); // Manejo de error y reseteo
      console.error('Error al subir la imagen:', error);
    } finally {
      this.spinner2 = false; // Detener el spinner al terminar
      this.isDisabledPosicImag = false; // Rehabilitar el botón
    }
  }

  // Inicializar btn DetecciónIA
  setReset(){
    this.spinner = false;
    this.spinner2 = false;
    this.isDisabledPosicImag = false;
    this.isDisabled = false;
    this.buttonArtify = true;
  }

  // Detection Imagen
  async intelify() {
    this.spinner = true;
    this.isDisabled = true;
    this.statusImage = "Detectar";
    await this.usersService.artify().subscribe(
        (res) => {
          this.messageTarget = res;
          this.setReset();
          this.test();
           
        },(erro) =>{
          console.error('Error al subir la imagen');
          this.isModalOpenAPI = true;
          this.setReset();
        }
    );
  }  

  // Modal-Response-Detecion
  test(){
    this.dataSetBD();
  }
  setOpen(isOpen: boolean) { 
    this.isModalOpen = isOpen;
    this.sendLikes();
  }
  async dataSetBD(){
    // Detection-Image-BD
    const dataSet = this.messageTarget
    await this.usersService.dataSetBD(dataSet)
      .subscribe(
        (res:any) => {
          if(res.msj){
            this.setOpen(true)
            // detener tiempo de deteccion IA
            this.detenerTiempoDetec();

            return this.dataSetModal = false


          }
          this.dataSet = res;
          this.dataSetModal = true 

          // detener tiempo de deteccion IA
          this.detenerTiempoDetec();

          // llamar func. Registro Deteccion
          this.optionsPlagaDetec()

          return this.setOpen(true) 



          // console.log(this.dataSet.rows[0]) // BaseData DataSet
        }
    );
  } 



  // Listado Cultivo-Plaga
  listCultPlags:any
  async listCultivoPlaga(){
    await this.usersService.listCultivoPlaga()
      .subscribe(
        (res:any) => {
          this.listCultPlags = res
        }
    );
  }

  // Select Cultivo - Plaga
  selectedCultPlag:any
  onSelectChangeCult(event: any) { 
    this.selectedCultPlag = event.detail.value; 
    const [nombre, espacio] = this.selectedCultPlag.split(" - ");
    const cultInfo = { 
      nombre: nombre.trim(),
      espacio: espacio.trim()
    }
    this.detailsCultivoPlaga(cultInfo.nombre)
  }

  // Detalle Cultivo-Plaga
  detailsCult:any = {
    fecha_ultima_deteccion: '--/--/--',
    nombre_plaga: '-----',
    status: '-----',
    ID:null
  }
  async detailsCultivoPlaga(idNombre:string){
    await this.usersService.detailsCultivoPlaga(idNombre)
      .subscribe(
        (res:any) => {
          this.detailsCult.ID = res
          this.detailsCult = res
          if( typeof(res) != 'number' ){
            
            if(this.detailsCult.nombre_plaga == ''){
              this.detailsCult.nombre_plaga = "Ninguna"
              this.detailsCult.status = 'Sin Plagas'

            }else{
              this.detailsCult.fecha_ultima_deteccion = this.formatDate(this.detailsCult.fecha_ultima_deteccion)
              this.detailsCult.status = 'Afectado'
            }
          }else{
            this.detailsCult= {
              nombre_cultivo: idNombre,              
              fecha_ultima_deteccion: '--/--/--',
              nombre_plaga: '-----',
              status: '-----',
              ID:res
            }
          }

          console.log(this.detailsCult)
          // console.log(this.detailsCult.fecha_ultima_deteccion)
        }
    );
  }

  // Actualizar por Deteccion
  optionsPlagaDetec(){
    if((this.opcionUpdateCultPLaga && this.confirmUpdateCultivo) && this.dataSet.rows[0].nombre){
      this.confirmUpdateCultivo = false
      this.updateDetecCult(this.dataSet.rows[0].nombre)
    }

  }
  async updateDetecCult(plaga:string){

    const plagaObj = {
      nombre_plaga:plaga,
      fecha_ultima_deteccion :this.dateToday()
    }

    // def. estado plaga
    if(!this.dataSet.rows[0].amenaza){
      plagaObj.nombre_plaga = "Libre de Plagas"
    }

    // console.log(`${this.detailsCult.ID} - ${plagaObj.nombre_plaga}`)
    console.log(this.detailsCult)

    await this.usersService.updateCultivoPlaga(this.detailsCult.ID, plagaObj)
      .subscribe(
        (res:any) => {
          console.log(res)

          // Info Insert
          this.detailsCult.nombre_plaga = plagaObj.nombre_plaga
          if(this.detailsCult.nombre_plaga == ''){
            this.detailsCult.nombre_plaga = "Ninguna"
            this.detailsCult.status = 'Sin Plagas'
          }else{
            this.detailsCult.status = 'Afectado'
          }
          this.detailsCult.fecha_ultima_deteccion = this.dateToday()

          
        }
    );
  }


  changeBtnCutivo(){
    this.btnCutivo = false
    this.opcionUpdateCultPLaga = false
    this.colorFab = "medium";
    this.detailsCult= {
      fecha_ultima_deteccion: '--/--/--',
      nombre_plaga: '-----',
      status: '-----',
    }
    console.log('Actualizar por Deteccion: Cancelada')
  }
  changeBtnCutivoT(){
    this.btnCutivo = true
    console.log(this.selectedCultPlag)
  }


  // determinar fecha actual
  dateToday(){
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

  // Setear Fechas: dd/mm/yy
  formatDate(dateString:any) {
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Los meses empiezan en 0
    const year = String(date.getFullYear()); // Obtener los últimos 2 dígitos del año
  
    return `${day}/${month}/${year}`;
  }


}
