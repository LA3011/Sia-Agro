import { NgModule } from '@angular/core';
import { BrowserModule, provideClientHydration } from '@angular/platform-browser';
import { RouteReuseStrategy } from '@angular/router';
import { IonicModule, IonicRouteStrategy } from '@ionic/angular';
import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';

import { PagesModule } from './pages/pages.module';
import { LoginModule } from './login/login.module';

import { UsersService } from './services/users.service';
import { TemplateModule } from './template/template.module';
import { ValidateService } from './services/validate.service';
import { provideAnimationsAsync } from '@angular/platform-browser/animations/async';

@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    BrowserModule,
    IonicModule.forRoot(),
    AppRoutingModule,
    PagesModule,
    LoginModule,
    TemplateModule,
  ],
  providers: [{
    provide: RouteReuseStrategy,
    useClass: IonicRouteStrategy },
    UsersService,
    ValidateService,
    provideAnimationsAsync(),
    // provideClientHydration()
  ],
  bootstrap: [AppComponent],
})
export class AppModule {}
