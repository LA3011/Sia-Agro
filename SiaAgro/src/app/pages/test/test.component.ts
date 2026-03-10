import { Component, OnInit } from '@angular/core';
import { Camera, CameraResultType, CameraSource } from '@capacitor/camera';

@Component({
  selector: 'app-test',
  templateUrl: './test.component.html',
  styleUrls: ['./test.component.scss'],
})
export class TestComponent  implements OnInit {
  
  
  constructor() { }
  
  ngOnInit() {}
  
  // Captura de Foto
  capturedImage: string | undefined; // Para almacenar la imagen capturada
  async takePicture() {
    try {
      const photo = await Camera.getPhoto({
        quality: 90,
        allowEditing: false,
        resultType: CameraResultType.Base64, // También puedes usar Uri si prefieres la URL
        source: CameraSource.Prompt, // Permite elegir entre cámara o archivos
      });

      this.capturedImage = `data:image/jpeg;base64,${photo.base64String}`;
      console.log('Foto tomada:', this.capturedImage);
    } catch (error) {
      console.error('Error al tomar la foto:', error);
    }
  }


}
