import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TemplateComponent } from './template.component';
import { IonicModule } from '@ionic/angular';
import { RouterModule } from '@angular/router';
import { ValidateService } from '../services/validate.service';
import { LoginModule } from '../login/login.module';
import { PagesModule } from '../pages/pages.module';
import { Error404Component } from './error404/error404.component';

@NgModule({
  declarations: [
    TemplateComponent,
    Error404Component
  ],
  imports: [
    CommonModule,
    IonicModule,
    RouterModule,
  ],
  exports:[
    TemplateComponent,
  ],
  providers:[
    ValidateService
  ],
})
export class TemplateModule { }
