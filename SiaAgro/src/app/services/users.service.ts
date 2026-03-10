import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { HttpClient } from '@angular/common/http'; 
import { Http } from '@capacitor-community/http';
import { Platform } from '@ionic/angular';

@Injectable({
  providedIn: 'root'
})
export class UsersService { 

  api_URL:any;

  constructor( private http:HttpClient, private platform:Platform) {
    this.AddressAPIStatus()
  }

  // Listar Detalles del Usuario
  listUserDetails(id_user:number){ 
    return this.http.get(`${this.api_URL}/usuario/detalles/${id_user}`);
  }

  // Listar Perfiles
  listPerfilUsers(){
    return this.http.get(`${this.api_URL}/usuario-perfil`);
  }

  // Listar Usuarios sin Perfil
  listUsersNotPerfil(){
    return this.http.get(`${this.api_URL}/usuario/sin-perfil`);
  }

  // Listar Usuarios
  getlistUsers(){
    return this.http.get(`${this.api_URL}/usuario`);
  }

  // Asociar [Usuario -> Perfil]
  asocUserPerfil(data:any){
    return this.http.post(`${this.api_URL}/usuario`, data);
  }

  // Listar Usuarios X Perfil
  listUsersForPerfil(id_perfil:number){
    return this.http.get(`${this.api_URL}/usuario/${id_perfil}`);
  }

  // Listar Perfil X Usuarios
  usuarioXperfil(id_perfil:number): Observable<any>{
    return this.http.get(`${this.api_URL}/usuario/detalle-perfil/${id_perfil}`);
  }

  // Listar Usuarios X Priv
  listUsersForPriv(id_priv:number){
    return this.http.get(`${this.api_URL}/usuario-perfil/privilegios/${id_priv}`);
  }

  // Listar Usuarios X Priv
  listUsersForAcc(id_priv:number){
    return this.http.get(`${this.api_URL}/usuario-perfil/acceso/${id_priv}`);
  }

  // Crear Nuevo Perfil
  aggPerfil(data:any){
    return this.http.post(`${this.api_URL}/usuario-perfil`, data);
  }

  // Crear Nuevo Perfil
  editarPerfil(id_perfil:number, data:any){
    return this.http.put(`${this.api_URL}/usuario-perfil/${id_perfil}`, data);
  }
  
  // Elimina Un perfil
  deletePerfil(id:number){
    return this.http.delete(`${this.api_URL}/usuario-perfil/${id}`);
  }
  


  // Verificar Plataforma Uso
  testWebMovil(){
    if(this.platform.is('desktop') || this.platform.is('mobileweb') || this.platform.is('pwa')){
      const plataform = "PC"
      return plataform;
    }else{
      const plataform = "PC" // Movil
      return plataform;
    }
  }
  
  // Direccion APIs
  AddressAPIStatus(){
    const url = localStorage.getItem('url');
    this.api_URL = url + "/api";
    return url
  }

  AddressAPIs(){
    const url = localStorage.getItem('url');
    return this.api_URL = url + "/api";
  }

  AddressAPI(Url:string){
      this.api_URL = Url + '/api'; 
  }

  // Etiquetas
  listTags():Observable<any>{
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-tag`);
  }
  MoveExpandTag(tagName:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-tag/${tagName}`);
  }

  ExtraExpandTag(tagName:any){
    return this.http.put(`${this.api_URL}/inteligencyArtificial-models-tag`,tagName);
  }
  UrlExtraExpandTag(){
    return `${this.api_URL}/inteligencyArtificial-models-tag`;
  }

  DistExpandTag(tagName:any){
    return this.http.post(`${this.api_URL}/inteligencyArtificial-models-tag`,tagName);
  }
  UrlDistExpandTag(){
    return `${this.api_URL}/inteligencyArtificial-models-tag`;
  }

  deleteTags(tagName:any):Observable<any>{
    return this.http.delete(`${this.api_URL}/inteligencyArtificial-models-tag/${tagName}`);
  }

  PredicWrite(){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-tag-Completed`);
  }
  UrlPredicWrite(){
    return `${this.api_URL}/inteligencyArtificial-models-tag-Completed`;
  }

  listTagsInfo(tag:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-tag-Completed/${tag}`);
  }
  validTags(){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-entrenamiento-Valid`);
  }
  
  InfoRendimientoIA(): Observable<any>{
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-Informacion/rendimient`);
  }

  setDetectionIA(): Observable<any>{
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-Informacion/detection`);
  }

  sendLikesIA(like: boolean, dislike: boolean): Observable<any>{
    const likes = {
      like: like,
      dislike: dislike
    }
    return this.http.post(`${this.api_URL}/inteligencyArtificial-models-Informacion/likes`, likes);
  }

  sendInputOpinionModalIA(ModalInfIA:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-Informacion/presicion`, ModalInfIA);
  }

  sendInputTextModalIA(ModalInfIA:any){
    return this.http.post(`${this.api_URL}/inteligencyArtificial-models-Informacion`, ModalInfIA);
  }
  descInfoM(tagName:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-Informacion/${tagName}`);
  }
  deleteInfoModalIA(tagName:any):Observable<any>{
    return this.http.delete(`${this.api_URL}/inteligencyArtificial-models-Informacion/${tagName}`);
  }
  UpdateInputTextModalIA(id:any, ModalInfIA:any){
    return this.http.put(`${this.api_URL}/inteligencyArtificial-models-Informacion/${id}`, ModalInfIA);
  }

  // Inteligencia Artificial
  artify():Observable<any>{
    return this.http.get(`${this.api_URL}/inteligencyArtificial`);
  }
  uploadFile(image: any){ //! Datails
    return this.http.post(`${this.api_URL}/inteligencyArtificial`, image);
  }
  dataSetBD(setData: any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-dataSet/${setData}`);
  }

  

  UrlUploadZip(){ 
    return `${this.api_URL}/InteligencyArtificial-model`;
  }

  uploadZip(zip: any){ 
    return this.http.post(`${this.api_URL}/InteligencyArtificial-model`, zip);
  }


  move(name:any){ 
    return this.http.put(`${this.api_URL}/inteligencyArtificial-models-entrenamiento`, name);
  }
  
  desc(dir:any){
    return this.http.put(`${this.api_URL}/InteligencyArtificial`, dir);
  }
  UrlDesc(){ 
    return `${this.api_URL}/InteligencyArtificial`;
  }



 test(path:any){
    return this.http.get(`${this.api_URL}/InteligencyArtificial/test/${path}`);
  } 



  dist(dirMov:any){
    return this.http.post(`${this.api_URL}/inteligencyArtificial-models`, dirMov);
  }
  Urldist(){ 
    return `${this.api_URL}/inteligencyArtificial-models`;
  }



  limpMove(limpMove:any){
    return this.http.put(`${this.api_URL}/inteligencyArtificial-models`, limpMove);
  }

  UrlLimpMove(){
    return `${this.api_URL}/inteligencyArtificial-models`;
  }


  write(write:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models/${write}`); 
  } 


  completed(completed:any){
    return this.http.post(`${this.api_URL}/inteligencyArtificial-models-entrenamiento`, completed)
  }
  UrlCompleted(){
    return `${this.api_URL}/inteligencyArtificial-models-entrenamiento`
  }

  writeEntren(classe:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-entrenamiento/${classe}`)
  }
  dataSet(){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed`);  
  } 
  UrldataSet(){
    return `${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed`
  } 
  autoConfig(dataset:any){
    return this.http.put(`${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed`, dataset);  
  }
  UrlautoConfig(){
    return `${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed`
  }

  timeModel(time:any){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed/${time}`);  
  }
  UrlTimeModel(time:any){
    return `${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed/${time}`;  
  }

  getTimeModel(){
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed/time`);  
  } 
  UrlgetTimeModel(){
    return `${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed/time`;  
  } 

  entren(nada:any){
    return this.http.post(`${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed`, nada);  
  }
  UrlEntren(){
    return `${this.api_URL}/inteligencyArtificial-models-entrenamiento-Completed`;  
  }
  
  
  killProcessEntren(){ // Eliminar Process.py
    return this.http.get(`${this.api_URL}/inteligencyArtificial-cancel`);  
  }
  VerifyProcessEntren(nada:any){ // Verificar Process.py
    return this.http.get(`${this.api_URL}/inteligencyArtificial-cancel/${nada}`);  
  }
  UrlVerifyProcessEntren(){
    return `${this.api_URL}/inteligencyArtificial-cancel/nada`;  
  }


  // Usuarios
  getGames(): Observable<any>{
    return this.http.get(`${this.api_URL}/games`);
  }
  getGame(id: string){
    return this.http.get(`${this.api_URL}/games/${id}`);
  }
  saveGame(game: any){ 
    return this.http.post(`${this.api_URL}/games`, game);
  } 
  deleteGame(id: string){
    return this.http.delete(`${this.api_URL}/games/${id}`);
  }
  updateGame(id: any, updateGame: any): Observable<any>{
    return this.http.put(`${this.api_URL}/games/${id}`, updateGame);
  }

  // Login (Old)
  search(login: any): Observable<any>{
    return this.http.post(`${this.api_URL}/login`, login);
  }

  // Login SiaAgro
  searchSiaAgroBD(login: any): Observable<any>{
    console.log(this.api_URL)
    return this.http.post(`${this.api_URL}/login/Usuario`, login);
  }

  // Etiqueta BD
  updateTagNotify(id:any, tagNotify:any): Observable<any>{
    return this.http.put(`${this.api_URL}/inteligencyArtificial-models-Notificacion/${id}`, tagNotify);
  }
  UrlUpdateTagNotify(id:any){
    return `${this.api_URL}/inteligencyArtificial-models-Notificacion/${id}`;
  }

  viewTagNotify(): Observable<any>{
    return this.http.get(`${this.api_URL}/inteligencyArtificial-models-Notificacion`);
  }

  // Cultivos
  listCultivoPlaga(){   // Listar Cultivo-Plaga
    return this.http.get(`${this.api_URL}/cultivoPlaga`);
  }
  listCultivoPlagaCosechado(){   // Listar Cultivo-Plaga
    return this.http.get(`${this.api_URL}/cultivoPlaga/cosechado`);
  }
  UrlListCultivoPlaga(){   // Listar Cultivo-Plaga 
    return `${this.api_URL}/cultivoPlaga`;
  }
  UrlListCultivoPlagaCosechado(){   // Listar Cultivo-Plaga (cosechado)
    return `${this.api_URL}/cultivoPlaga/cosechado`;
  }

  detailsCultivoPlaga(idEspacio:string){ // Detalle Cultivo-Plaga
    return this.http.get(`${this.api_URL}/cultivoPlaga/${idEspacio}`);
  }

  updateCultivoPlaga(id:number, plaga:any){ // Lista detalle Cultivo-Plaga
    return this.http.put(`${this.api_URL}/cultivoPlaga/${id}`, plaga);
  }
  UrlUpdateCultivoPlaga(id:number){ // Lista detalle Cultivo-Plaga
    return `${this.api_URL}/cultivoPlaga/${id}`;
  }

  fetchupdateCultivoPlaga(id:number){ // Lista detalle Cultivo-Plaga
    return this.http.get(`${this.api_URL}/cultivoPlaga/historial/${id}`);
  }
  fetchupdateCultivoPlagaCosechado(id:number){ // Lista detalle Cultivo-Plaga
    return this.http.get(`${this.api_URL}/cultivoPlaga/cosechado/historial/${id}`);
  }
  UrlfetchupdateCultivoPlaga(id:any){ // Lista detalle Cultivo-Plaga
    return `${this.api_URL}/cultivoPlaga/historial/${id}`;
  }
  UrlfetchupdateCultivoPlagaCosechado(id:any){ // Lista detalle Cultivo-Plaga Coechado
    return `${this.api_URL}/cultivoPlaga/cosechado/historial/${id}`;
  }
  
  // Animals
  listAnimalsVenta(){   // Listar Animales-Venta
    return `${this.api_URL}/animales`;
  }
  listAnimalsPastoreo(){   // Listar Pastoreo-Animal
    return `${this.api_URL}/animales/pastoreo`;
  }
  
}
