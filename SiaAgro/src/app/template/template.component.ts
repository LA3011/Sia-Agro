import { Component, NgModule,OnDestroy, OnInit } from '@angular/core';
import { Router, RouterModule } from '@angular/router';
import { ValidateService } from '../services/validate.service';


@Component({
  selector: 'app-template',
  templateUrl: './template.component.html',
  styleUrls: ['./template.component.scss'],
})
export class TemplateComponent  implements OnInit {

  userPass = {};

  constructor(
    public validateService:ValidateService,
    public router:Router,
  ) { }
  
    ngOnInit(){ 
    }
  
   

}
