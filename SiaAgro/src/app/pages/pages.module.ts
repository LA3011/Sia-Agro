import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HomeComponent } from './home/home.component';
import { IonicModule } from '@ionic/angular';
import { UsersService } from '../services/users.service';
import { HttpClientModule } from '@angular/common/http';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ValidateService } from '../services/validate.service';
import { RouterLink } from '@angular/router';
import { InteligencyArtComponent } from './inteligency-art/inteligency-art.component';
import { InteligencyArtModelsComponent } from './inteligency-art-models/inteligency-art-models.component';
import { ConexionComponent } from './conexion/conexion.component';
import { PerfilComponent } from './perfil/perfil.component';
import { CultivosComponent } from './cultivos/cultivos.component';
import { AnimalesComponent } from './animales/animales.component';
import { TestComponent } from './test/test.component';

@NgModule({
  declarations: [
    HomeComponent,
    InteligencyArtComponent,
    InteligencyArtModelsComponent,
    ConexionComponent,
    PerfilComponent,
    CultivosComponent,
    AnimalesComponent,
    TestComponent,
  ],
  imports: [
    CommonModule,
    IonicModule,
    HttpClientModule,
    FormsModule,
    RouterLink,
    ReactiveFormsModule,
  ],
  providers:[
    ValidateService
  ]
})
export class PagesModule { }
