import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { HomeComponent } from './pages/home/home.component';
import { LoginComponent } from './login/login.component';
import { Error404Component } from './template/error404/error404.component';
import { InteligencyArtComponent } from './pages/inteligency-art/inteligency-art.component';
import { InteligencyArtModelsComponent } from './pages/inteligency-art-models/inteligency-art-models.component';
import { ConexionComponent } from './pages/conexion/conexion.component';
import { PerfilComponent } from './pages/perfil/perfil.component';
import { CultivosComponent } from './pages/cultivos/cultivos.component';
import { AnimalesComponent } from './pages/animales/animales.component';
import { TestComponent } from './pages/test/test.component';

const routes: Routes = [

  { path: '', component: LoginComponent},
  // { path: '**', redirectTo: 'error404', pathMatch: 'full' },
  { path: 'error404', component: Error404Component },
  { path: 'Conexion', component: ConexionComponent },
  { path: 'inicio', component: HomeComponent },
  // { path: 'usuarios', component: UsersComponent },
  { path: 'usuarios', component: PerfilComponent },
  { path: 'login', component: LoginComponent },
  { path: 'Inteligencia-Artificial', component: InteligencyArtComponent },
  { path: 'Inteligencia-Artificial-Models', component: InteligencyArtModelsComponent },
  { path: 'perfil', component: PerfilComponent },
  { path: 'cultivos', component: CultivosComponent },
  { path: 'animales', component: AnimalesComponent },
  { path: 'prueba', component: TestComponent },


]; 

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
