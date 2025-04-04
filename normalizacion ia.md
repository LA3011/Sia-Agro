# Eliminar Archivos (delete)
  > InteligenciaArtificial/data/entrenamiento/.x
  > InteligenciaArtificial/data/validacion/.x

  De lo contrario, si "NO SE ELIMINAN" y se llega agregar una etiqueta La IA empezara a dar fallas, 
  para solucionarlo se debe hacer lo siguiente. 
  
    1) Eliminar todas las Etiquetas <InteligenciaArtificial/data/validacion> y <InteligenciaArtificial/data/entrenamiento> 
    3) Se debe Mantener (1) "Models.h5" por defecto en el "stagingArea" como en el "modelos_create", de lo contrario se debe ejecutar 2 veces el entrenamiento... Ya que el primero sufrira de un 'error' por motivos que el sistema no encuentra 1 modelo que eliminar y por ende genera el error 'no such file or directory, unlink' al momento de terminar el 'ENTRENAMIENTO' (debe levantar el servidor cuando este error ocurra).
    2) modificar manualmente el archivo <InteligenciaArtificial/inferenciasPredict/predecir_umbral.py> y debe quedar:

    ###> ./SiaAgro/InteligenciaArtificial/inferenciasPredict/predecir_umbral.py: 

    # Comparar con el umbral
    if confianza_maxima < umbral: 
        return print('noReconocia')
    else:
        # -------------------  Inicializando ----------------------
        if respuesta == 'inicio': 
            return print('inicio')
        
        #elif respuesta == x:
        #    return print('x. ',confianza_maxima)

        # --------------------------------------------------------- 
        #{{elif}}
            #{{return}}

# Tener en Cuenta (models.h5)
  Son Invalidos para ejecutar una deteccion "Se debe agregar una nueva etiqueta, y someterlo a un entrenamiento para que genere models.h5 validos"

