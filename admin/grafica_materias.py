import pymysql
import matplotlib.pyplot as plt
import numpy as np

# Configuración de la conexión a la base de datos
db_config = {
    'host': 'roundhouse.proxy.rlwy.net',
    'user': 'root',
    'password': 'xevACkoMYkhovGUpFykFmEVStdCzQlbf',
    'database': 'railway',
    'port': 50768,
    'charset': 'utf8mb4',
    'cursorclass': pymysql.cursors.DictCursor
}

def obtener_datos_materias():
    try:
        # Conectar a la base de datos
        connection = pymysql.connect(**db_config)
        
        # Consulta para obtener el conteo de cada materia
        with connection.cursor() as cursor:
            # Consulta que cuenta la frecuencia de cada materia en todas las columnas de materias
            sql = """
                SELECT 'Materia1' as tipo, materia1 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia1 IS NOT NULL AND materia1 != ''
                GROUP BY materia1
                
                UNION ALL
                
                SELECT 'Materia2' as tipo, materia2 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia2 IS NOT NULL AND materia2 != ''
                GROUP BY materia2
                
                UNION ALL
                
                SELECT 'Materia3' as tipo, materia3 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia3 IS NOT NULL AND materia3 != ''
                GROUP BY materia3
                
                UNION ALL
                
                SELECT 'Materia4' as tipo, materia4 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia4 IS NOT NULL AND materia4 != ''
                GROUP BY materia4
                
                UNION ALL
                
                SELECT 'Materia5' as tipo, materia5 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia5 IS NOT NULL AND materia5 != ''
                GROUP BY materia5
                
                UNION ALL
                
                SELECT 'Materia6' as tipo, materia6 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia6 IS NOT NULL AND materia6 != ''
                GROUP BY materia6
                
                UNION ALL
                
                SELECT 'Materia7' as tipo, materia7 as nombre, COUNT(*) as total 
                FROM Inscripciones 
                WHERE materia7 IS NOT NULL AND materia7 != ''
                GROUP BY materia7
                
                ORDER BY total DESC
                LIMIT 15  # Mostrar solo las 15 materias más populares
            """
            cursor.execute(sql)
            resultados = cursor.fetchall()
            
            # Procesar resultados para agrupar por nombre de materia
            materias = {}
            for row in resultados:
                nombre = row['nombre']
                if nombre in materias:
                    materias[nombre] += row['total']
                else:
                    materias[nombre] = row['total']
            
            # Ordenar por cantidad descendente
            materias_ordenadas = sorted(materias.items(), key=lambda x: x[1], reverse=True)
            
            return materias_ordenadas
            
    except Exception as e:
        print(f"Error al conectar a la base de datos: {e}")
        return []
    finally:
        if 'connection' in locals() and connection.open:
            connection.close()

def generar_grafica(materias):
    if not materias:
        print("No hay datos para mostrar.")
        return
    
    # Separar nombres y cantidades
    nombres = [m[0] for m in materias]
    cantidades = [m[1] for m in materias]
    
    # Crear la figura
    plt.figure(figsize=(12, 8))
    
    # Crear la gráfica de barras horizontales
    y_pos = np.arange(len(nombres))
    bars = plt.barh(y_pos, cantidades, color='#001f87', alpha=0.7)
    
    # Añadir etiquetas y título
    plt.xlabel('Número de Inscripciones', fontsize=12)
    plt.ylabel('Materias', fontsize=12)
    plt.title('Materias más Populares - Próximo Semestre', fontsize=16, pad=20)
    
    # Ajustar las etiquetas del eje Y
    plt.yticks(y_pos, nombres, fontsize=10)
    
    # Añadir los valores en las barras
    for bar in bars:
        width = bar.get_width()
        plt.text(width + 0.1, bar.get_y() + bar.get_height()/2., 
                f'{int(width)}', 
                ha='left', va='center', fontsize=10)
    
    # Ajustar el diseño
    plt.tight_layout()
    
    # Guardar la imagen
    plt.savefig('grafica_materias.png', dpi=300, bbox_inches='tight')
    print("Gráfica generada correctamente: grafica_materias.png")
    
    # Mostrar la gráfica
    plt.show()

if __name__ == "__main__":
    print("Obteniendo datos de materias...")
    datos_materias = obtener_datos_materias()
    
    if datos_materias:
        print(f"Se encontraron {len(datos_materias)} materias con inscripciones.")
        print("\nTop 15 materias más populares:")
        for i, (materia, cantidad) in enumerate(datos_materias, 1):
            print(f"{i}. {materia}: {cantidad} inscripciones")
        
        print("\nGenerando gráfica...")
        generar_grafica(datos_materias)
    else:
        print("No se encontraron datos de materias.")
