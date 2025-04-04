import os
import numpy as np
from tensorflow.keras.preprocessing.image import load_img, img_to_array
from tensorflow.keras.models import load_model

# Obtener la ruta del directorio actual
directorio_actual = os.path.dirname(os.path.abspath(__file__))
# Retroceder un directorio
directorio_padre = os.path.abspath(os.path.join(directorio_actual, os.pardir))

# Crear la ruta completa de la imagen y el modelo
modelo_path = os.path.join(directorio_padre, 'modelos_create', 'models.h5')
imagen_path = os.path.join(directorio_padre, 'imagesTest', 'imagenProcess.jpg')

# Cargar el modelo previamente entrenado (incluye arquitectura y pesos)
modelo = load_model(modelo_path)

# Función para predecir con umbral de confianza
def predecir_con_umbral(imagen_path, umbral):
    # Cargar la imagen y redimensionarla al tamaño de entrada esperado por el modelo
    img = load_img(imagen_path, target_size=(299, 299))  # Ajustar al tamaño esperado: (224, 224)
    x = img_to_array(img)  # Convertir la imagen a un array
    x = x / 255.0  # Normalizar valores de píxeles (rango [0, 1])
    x = np.expand_dims(x, axis=0)  # Añadir una dimensión extra para el batch (shape: (1, 224, 224, 3))

    # Realizar la predicción con el modelo
    arreglo = modelo.predict(x)
    resultado = arreglo[0]  # Obtener las probabilidades para cada clase
    respuesta = np.argmax(resultado)  # Índice de la clase con mayor probabilidad
    confianza_maxima = np.max(resultado)  # Probabilidad más alta

    # Comparar la confianza con el umbral
    if confianza_maxima < umbral:
        return print('noReconocia',confianza_maxima)
    else:
        # -------------------  Inicializando ----------------------
        if respuesta == 'inicio': 
            return print('inicio')
        
        # --------------------------------------------------------- 
        if respuesta == 0:
            return print('Hoja de Guayaba')
        elif respuesta == 1:
            return print('random X', confianza_maxima) # EVITAR FALSOS POSITIVOS
        #{{elif}}
            #{{return}}
        else:
            return print('Clase desconocida', confianza_maxima)

# Definir el umbral de confianza (por ejemplo, 10%)
umbral = 0.6
predecir_con_umbral(imagen_path, umbral)