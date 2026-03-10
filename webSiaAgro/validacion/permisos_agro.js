 
 $('#PROGRAMAS').click(function(){
  $('#ANIMALES').prop('checked', $(this).prop('checked'));
  $('#CULTIVOS').prop('checked', $(this).prop('checked'));
  $('#VENTA').prop('checked', $(this).prop('checked'));
  $('#FINANZAS').prop('checked', $(this).prop('checked'));
  $('#RECURSOS_HUMANOS').prop('checked', $(this).prop('checked'));
  $('#CONFIGURACION').prop('checked', $(this).prop('checked'));

  $('#general_animales').prop('checked', $(this).prop('checked'));
  $('#movimiento_animal').prop('checked', $(this).prop('checked'));
  $('#general_cultivos').prop('checked', $(this).prop('checked'));
  $('#seguimiento_cultivos').prop('checked', $(this).prop('checked'));
  $('#venta').prop('checked', $(this).prop('checked'));
  $('#general_finanzas').prop('checked', $(this).prop('checked'));
  $('#costos').prop('checked', $(this).prop('checked'));
  $('#empleados').prop('checked', $(this).prop('checked'));
  $('#ajustes').prop('checked', $(this).prop('checked'));

  $('#registro_animales').prop('checked', $(this).prop('checked'));
  $('#reproducciones_animales').prop('checked', $(this).prop('checked'));
  $('#registro_potreros').prop('checked', $(this).prop('checked'));
  $('#actividad_animal').prop('checked', $(this).prop('checked'));
  $('#pastoreo').prop('checked', $(this).prop('checked'));
  $('#insumos_animal').prop('checked', $(this).prop('checked'));
  $('#animales').prop('checked', $(this).prop('checked'));
  $('#cultivo').prop('checked', $(this).prop('checked'));
  $('#costo_fijo').prop('checked', $(this).prop('checked'));
  $('#costo_variable').prop('checked', $(this).prop('checked'));
  $('#usuarios').prop('checked', $(this).prop('checked'));
  $('#permisos').prop('checked', $(this).prop('checked'));
  $('#siembra').prop('checked', $(this).prop('checked'));
  $('#espacios').prop('checked', $(this).prop('checked'));
  $('#actividades').prop('checked', $(this).prop('checked'));
  $('#control_fertilizante').prop('checked', $(this).prop('checked'));
  $('#control_plagas').prop('checked', $(this).prop('checked'));
  $('#insumos_cultivo').prop('checked', $(this).prop('checked'));
  $('#orden_salida').prop('checked', $(this).prop('checked'));
  $('#animales_venta').prop('checked', $(this).prop('checked'));
  $('#bitacora').prop('checked', $(this).prop('checked'));
});

 $('#ANIMALES').click(function(){
  $('#general_animales').prop('checked', $(this).prop('checked'));
  $('#movimiento_animal').prop('checked', $(this).prop('checked'));
  $('#registro_animales').prop('checked', $(this).prop('checked'));
  $('#reproducciones_animales').prop('checked', $(this).prop('checked'));
  $('#registro_potreros').prop('checked', $(this).prop('checked'));
  $('#actividad_animal').prop('checked', $(this).prop('checked'));
  $('#pastoreo').prop('checked', $(this).prop('checked'));
  $('#insumos_animal').prop('checked', $(this).prop('checked'));
});
 $('#general_animales').click(function(){
  $('#registro_animales').prop('checked', $(this).prop('checked'));
  $('#reproducciones_animales').prop('checked', $(this).prop('checked'));
  $('#registro_potreros').prop('checked', $(this).prop('checked'));
});
 $('#movimiento_animal').click(function(){
  $('#actividad_animal').prop('checked', $(this).prop('checked'));
  $('#pastoreo').prop('checked', $(this).prop('checked'));
  $('#insumos_animal').prop('checked', $(this).prop('checked'));
});


 $('#CULTIVOS').click(function(){
  $('#general_cultivos').prop('checked', $(this).prop('checked'));
  $('#seguimiento_cultivos').prop('checked', $(this).prop('checked'));
  $('#siembra').prop('checked', $(this).prop('checked'));
  $('#espacios').prop('checked', $(this).prop('checked'));
  $('#actividades').prop('checked', $(this).prop('checked'));
  $('#control_fertilizante').prop('checked', $(this).prop('checked'));
  $('#control_plagas').prop('checked', $(this).prop('checked'));
  $('#insumos_cultivo').prop('checked', $(this).prop('checked'));
});
 $('#general_cultivos').click(function(){
  $('#siembra').prop('checked', $(this).prop('checked'));
  $('#espacios').prop('checked', $(this).prop('checked'));
  $('#actividades').prop('checked', $(this).prop('checked'));
});
 $('#seguimiento_cultivos').click(function(){
  $('#control_fertilizante').prop('checked', $(this).prop('checked'));
  $('#control_plagas').prop('checked', $(this).prop('checked'));
  $('#insumos_cultivo').prop('checked', $(this).prop('checked'));
});


 $('#VENTA').click(function(){
  $('#venta').prop('checked', $(this).prop('checked'));
  $('#orden_salida').prop('checked', $(this).prop('checked'));
  $('#animales_venta').prop('checked', $(this).prop('checked'));
});
 $('#venta').click(function(){
  $('#VENTA').prop('checked', $(this).prop('checked'));
});
 $('#venta').click(function(){
  $('#orden_salida').prop('checked', $(this).prop('checked'));
  $('#animales_venta').prop('checked', $(this).prop('checked'));
});


 $('#FINANZAS').click(function(){
  $('#general_finanzas').prop('checked', $(this).prop('checked'));
  $('#costos').prop('checked', $(this).prop('checked'));
  $('#animales').prop('checked', $(this).prop('checked'));
  $('#cultivo').prop('checked', $(this).prop('checked'));
  $('#costo_fijo').prop('checked', $(this).prop('checked'));
  $('#costo_variable').prop('checked', $(this).prop('checked'));
});
 $('#general_finanzas').click(function(){
  $('#animales').prop('checked', $(this).prop('checked'));
  $('#cultivo').prop('checked', $(this).prop('checked'));
});
 $('#costos').click(function(){
  $('#costo_fijo').prop('checked', $(this).prop('checked'));
  $('#costo_variable').prop('checked', $(this).prop('checked'));
});

 $('#RECURSOS_HUMANOS').click(function(){
  $('#empleados').prop('checked', $(this).prop('checked'));
});
 $('#empleados').click(function(){
  $('#RECURSOS_HUMANOS').prop('checked', $(this).prop('checked'));
});
 $('#empleados').click(function(){
  $('#empleados').prop('checked', $(this).prop('checked'));
});

 $('#CONFIGURACION').click(function(){
  $('#ajustes').prop('checked', $(this).prop('checked'));
  $('#usuarios').prop('checked', $(this).prop('checked'));
  $('#permisos').prop('checked', $(this).prop('checked'));
});

 $('#ajustes').click(function(){
  $('#CONFIGURACION').prop('checked', $(this).prop('checked'));
});
 $('#ajustes').click(function(){
  $('#usuarios').prop('checked', $(this).prop('checked'));
  $('#permisos').prop('checked', $(this).prop('checked'));
});






 $('.programasedit').click(function(){

  $('.animales123').prop('checked', $(this).prop('checked'));
  $('.cultivos123').prop('checked', $(this).prop('checked'));
  $('.finanzas123').prop('checked', $(this).prop('checked'));
  $('.ventas123').prop('checked', $(this).prop('checked'));
  $('.empleados123').prop('checked', $(this).prop('checked'));
  $('.configuracion123').prop('checked', $(this).prop('checked'));
  $('.animales1').prop('checked', $(this).prop('checked'));
  $('.animales2').prop('checked', $(this).prop('checked'));
  $('.cultivos1').prop('checked', $(this).prop('checked'));
  $('.cultivos2').prop('checked', $(this).prop('checked'));
  $('.finanzas1').prop('checked', $(this).prop('checked'));
  $('.finanzas2').prop('checked', $(this).prop('checked'));
  $('.ventas1').prop('checked', $(this).prop('checked'));
  $('.empleados1').prop('checked', $(this).prop('checked'));
  $('.configuracion1').prop('checked', $(this).prop('checked'));
  $('.animales1').prop('checked', $(this).prop('checked'));

  $('.animales2').prop('checked', $(this).prop('checked'));
  $('.animalessub1').prop('checked', $(this).prop('checked'));
  $('.animalessub2').prop('checked', $(this).prop('checked'));
  $('.cultivos1').prop('checked', $(this).prop('checked'));
  $('.cultivos2').prop('checked', $(this).prop('checked'));
  $('.cultivossub1').prop('checked', $(this).prop('checked'));
  $('.cultivossub2').prop('checked', $(this).prop('checked'));
  $('.ventasxq').prop('checked', $(this).prop('checked'));
  $('.ventassubxq').prop('checked', $(this).prop('checked'));
  $('.finanzas456').prop('checked', $(this).prop('checked'));
  $('.costos456').prop('checked', $(this).prop('checked'));
  $('.finanzassub456').prop('checked', $(this).prop('checked'));
  $('.costossub456').prop('checked', $(this).prop('checked'));
  $('.ajustessub123').prop('checked', $(this).prop('checked'));
  $('.ajustes123').prop('checked', $(this).prop('checked'));
  $('.empleadossub123').prop('checked', $(this).prop('checked'));
});


 $('.animales123').click(function(){
  $('.animales1').prop('checked', $(this).prop('checked'));
  $('.animales2').prop('checked', $(this).prop('checked'));
  $('.animalessub1').prop('checked', $(this).prop('checked'));
  $('.animalessub2').prop('checked', $(this).prop('checked'));
});
 $('.cultivos123').click(function(){
  $('.cultivossub1').prop('checked', $(this).prop('checked'));
  $('.cultivossub2').prop('checked', $(this).prop('checked'));
  $('.cultivos1').prop('checked', $(this).prop('checked'));
  $('.cultivos2').prop('checked', $(this).prop('checked'));
});
 $('.finanzas123').click(function(){
  $('.finanzas1').prop('checked', $(this).prop('checked'));
  $('.finanzas2').prop('checked', $(this).prop('checked'));
});
 $('.ventas123').click(function(){
  $('.ventas1').prop('checked', $(this).prop('checked'));
  $('.ventassubxq').prop('checked', $(this).prop('checked'));
});
 $('.ventas1').click(function(){
  $('.ventas123').prop('checked', $(this).prop('checked'));
}); 
 $('.empleados123').click(function(){
  $('.empleados1').prop('checked', $(this).prop('checked'));
});
 $('.empleados1').click(function(){
  $('.empleados123').prop('checked', $(this).prop('checked'));
}); 
 $('.configuracion123').click(function(){
  $('.configuracion1').prop('checked', $(this).prop('checked'));
});
 $('.configuracion1').click(function(){
  $('.configuracion123').prop('checked', $(this).prop('checked'));
}); 



 $('.animales123').click(function(){
  $('.animales1').prop('checked', $(this).prop('checked'));
  $('.animales2').prop('checked', $(this).prop('checked'));
});
 $('.animales1').click(function(){
  $('.animalessub1').prop('checked', $(this).prop('checked'));
});
 $('.animales2').click(function(){
  $('.animalessub2').prop('checked', $(this).prop('checked'));
});


 $('.cultivos123').click(function(){
  $('.cultivos1').prop('checked', $(this).prop('checked'));
  $('.cultivos2').prop('checked', $(this).prop('checked'));
});
 $('.cultivos1').click(function(){
  $('.cultivossub1').prop('checked', $(this).prop('checked'));
});
 $('.cultivos2').click(function(){
  $('.cultivossub2').prop('checked', $(this).prop('checked'));
});


 $('.ventas123').click(function(){
  $('.ventasxq').prop('checked', $(this).prop('checked'));
});
 $('.ventasxq').click(function(){
  $('.ventassubxq').prop('checked', $(this).prop('checked'));
});


 $('.finanzas123').click(function(){
  $('.finanzassub456').prop('checked', $(this).prop('checked'));
  $('.costossub456').prop('checked', $(this).prop('checked'));
  $('.finanzas456').prop('checked', $(this).prop('checked'));
  $('.costos456').prop('checked', $(this).prop('checked'));
});
 $('.finanzas456').click(function(){
  $('.finanzassub456').prop('checked', $(this).prop('checked'));
});
 $('.costos456').click(function(){
  $('.costossub456').prop('checked', $(this).prop('checked'));
});

 $('.ajustes123').click(function(){
  $('.ajustessub123').prop('checked', $(this).prop('checked'));
});
 $('.configuracion123').click(function(){
  $('.ajustessub123').prop('checked', $(this).prop('checked'));
  $('.ajustes123').prop('checked', $(this).prop('checked'));
});


 $('.empleados123').click(function(){
  $('.empleadossub123').prop('checked', $(this).prop('checked'));
});

 $('.priv2').click(function(){
  $('.subpriv2').prop('checked', $(this).prop('checked'));
});