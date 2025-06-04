import matplotlib.pyplot as plt
from consulta_mysql import obtener_datos

def generar_grafica():
    # Obtener los datos
    df = obtener_datos()

    # Verificar las columnas
    print(df.columns)

    # Crear una gráfica
    plt.figure(figsize=(18, 10))  # Aumentar el tamaño
    plt.bar(df['Materia'], df['Cantidad'])
    plt.title('Cantidad de Registros por Materia')
    plt.xlabel('Materia')
    plt.ylabel('Cantidad')
    plt.xticks(rotation=60, ha='right', fontsize=10)  # Rotar a 60 grados y alinear a la derecha
    plt.grid(True)
    
    plt.tight_layout()  # Ajustar automáticamente el layout
    
    # Guardar la gráfica como un archivo
    plt.savefig('grafica.png')

if __name__ == "__main__":
        generar_grafica() 
