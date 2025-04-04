import os
from tensorflow.keras.preprocessing.image import ImageDataGenerator
from tensorflow.keras.applications import ResNet50
from tensorflow.keras.models import Model
from tensorflow.keras.layers import Dense, Dropout, Flatten, GlobalAveragePooling2D
from tensorflow.keras.callbacks import EarlyStopping, ReduceLROnPlateau, ModelCheckpoint
from tensorflow.keras.optimizers import Adam
import matplotlib.pyplot as plt

# Limpiar cualquier sesión previa
from tensorflow.keras import backend as K
K.clear_session()

# Rutas de datos
directorio_actual = os.path.dirname(os.path.abspath(__file__))
directorio_padre = os.path.abspath(os.path.join(directorio_actual, os.pardir))
data_validacion = os.path.join(directorio_padre, 'data', 'validacion')
data_entrenamiento = os.path.join(directorio_padre, 'data', 'entrenamiento')

# Determinar el número de clases
def contar_carpetas(ruta):
    try:
        carpetas = [nombre for nombre in os.listdir(ruta) if os.path.isdir(os.path.join(ruta, nombre))]
        return len(carpetas)
    except FileNotFoundError:
        print(f"La ruta {ruta} no existe.")
        return 0

clases = contar_carpetas(data_validacion)

# Hiperparámetros actualizados
altura, longitud = 224, 224  # Resolución recomendada para ResNet50
batch_size = 32
epocas_transfer = 15  # Épocas de transferencia
epocas_finetuning = 10  # Épocas de fine-tuning

# Generadores de imágenes con aumento de datos
entrenamiento_datagen = ImageDataGenerator(
    rescale=1. / 255,
    shear_range=0.3,  # Incrementa el rango de corte
    zoom_range=0.3,  # Incrementa el rango de zoom
    horizontal_flip=True,
    vertical_flip=True,
    rotation_range=30,  # Más rotaciones
    width_shift_range=0.3,  # Más traslados horizontales
    height_shift_range=0.3,  # Más traslados verticales
    brightness_range=[0.6, 1.4]  # Cambios más amplios en brillo
)

validacion_datagen = ImageDataGenerator(rescale=1. / 255)

imagen_entrenamiento = entrenamiento_datagen.flow_from_directory(
    data_entrenamiento,
    target_size=(altura, longitud),
    batch_size=batch_size,
    class_mode='categorical'
)
imagen_validacion = validacion_datagen.flow_from_directory(
    data_validacion,
    target_size=(altura, longitud),
    batch_size=batch_size,
    class_mode='categorical'
)

# Cargar modelo base preentrenado (ResNet50)
base_model = ResNet50(weights='imagenet', include_top=False, input_shape=(altura, longitud, 3))

# Congelar capas del modelo base (para transfer learning)
for layer in base_model.layers:
    layer.trainable = False

# Añadir nuevas capas (cabeza personalizada)
x = base_model.output
x = GlobalAveragePooling2D()(x)  # Sustituye Flatten por GlobalAveragePooling2D
x = Dense(256, activation='relu')(x)
x = Dropout(0.6)(x)
output = Dense(clases, activation='softmax')(x)  # Salida personalizada
model = Model(inputs=base_model.input, outputs=output)

# Compilar el modelo para transfer learning
model.compile(optimizer=Adam(learning_rate=1e-4), 
              loss='categorical_crossentropy', 
              metrics=['accuracy'])

# Callbacks
early_stopping = EarlyStopping(monitor='val_loss', patience=5, restore_best_weights=True)
reduce_lr = ReduceLROnPlateau(monitor='val_loss', factor=0.2, patience=5, min_lr=1e-6)
checkpoint = ModelCheckpoint(
    os.path.join(directorio_padre, 'modelsCHECKPOINT.h5'),
    monitor='val_loss',
    save_best_only=True,
    mode='min'
)
callbacks = [early_stopping, reduce_lr, checkpoint]

# Transfer learning (cabezas personalizadas)
print("Iniciando Transfer Learning con ResNet50...")
history = model.fit(
    imagen_entrenamiento,
    validation_data=imagen_validacion,
    epochs=epocas_transfer,
    steps_per_epoch=len(imagen_entrenamiento),
    validation_steps=len(imagen_validacion),
    callbacks=callbacks
)

# Descongelar las últimas capas del modelo base para fine-tuning
for layer in base_model.layers[-5:]:  # Descongela las últimas 10 capas para ajuste fino
    layer.trainable = True

# Recompilar el modelo con una tasa de aprendizaje más baja para fine-tuning
model.compile(optimizer=Adam(learning_rate=1e-5), 
              loss='categorical_crossentropy', 
              metrics=['accuracy'])

# Fine-tuning
print("Iniciando Fine-Tuning con ResNet50...")
history_finetune = model.fit(
    imagen_entrenamiento,
    validation_data=imagen_validacion,
    epochs=epocas_finetuning,
    steps_per_epoch=len(imagen_entrenamiento),
    validation_steps=len(imagen_validacion),
    callbacks=callbacks
)

# Guardar el modelo entrenado
modelo_guardado = os.path.join(directorio_padre, 'ModelosGeneradosH5', '3. [ResNet50] + [(15-10)] + [Augmentation] + [neuronas descongeladas -10] + [ (+) neuronas]', 'TEST-02','entrenarPrecargado.h5')
model.save(modelo_guardado)
print(f"Modelo guardado en: {modelo_guardado}")

# Graficar los resultados
plt.figure(figsize=(12, 6))
plt.plot(history.history['accuracy'], label='Precisión Transfer Learning')
plt.plot(history_finetune.history['accuracy'], label='Precisión Fine-Tuning', linestyle='--')
plt.plot(history.history['loss'], label='Pérdida Transfer Learning')
plt.plot(history_finetune.history['loss'], label='Pérdida Fine-Tuning', linestyle='--')
plt.xlabel('Épocas')
plt.ylabel('Métrica')
plt.legend()
plt.title('Precisión y Pérdida Durante Entrenamiento con ResNet50')
plt.show()
