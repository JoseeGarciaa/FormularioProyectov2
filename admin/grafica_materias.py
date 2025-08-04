import pymysql
import matplotlib.pyplot as plt
import numpy as np
from collections import Counter
import matplotlib.patches as patches

# Configurar matplotlib para usar una fuente que soporte caracteres especiales
plt.rcParams['font.family'] = ['DejaVu Sans', 'Arial', 'sans-serif']
plt.rcParams['axes.unicode_minus'] = False

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
    
    # Limitar a las top 10 materias para mejor visualización
    top_materias = materias[:10]
    
    # Separar nombres y cantidades
    nombres = [m[0] for m in top_materias]
    cantidades = [m[1] for m in top_materias]
    
    # Crear colores degradados
    colors = plt.cm.viridis(np.linspace(0.2, 0.8, len(nombres)))
    
    # Crear la figura con fondo personalizado
    fig, ax = plt.subplots(figsize=(14, 10))
    fig.patch.set_facecolor('#f8f9fa')
    ax.set_facecolor('#ffffff')
    
    # Crear la gráfica de barras horizontales
    y_pos = np.arange(len(nombres))
    bars = ax.barh(y_pos, cantidades, color=colors, alpha=0.8, height=0.6)
    
    # Añadir gradiente a las barras
    for i, bar in enumerate(bars):
        # Crear gradiente
        gradient = np.linspace(0, 1, 256).reshape(1, -1)
        gradient = np.vstack((gradient, gradient))
        
        # Aplicar gradiente
        bar_color = colors[i]
        bar.set_color(bar_color)
    
    # Personalizar el título
    ax.set_title('📊 Materias Más Inscritas por Estudiantes', 
                fontsize=20, fontweight='bold', pad=30, color='#2c3e50')
    
    # Personalizar etiquetas de ejes
    ax.set_xlabel('Número de Estudiantes Inscritos', fontsize=14, fontweight='bold', color='#34495e')
    ax.set_ylabel('Materias', fontsize=14, fontweight='bold', color='#34495e')
    
    # Ajustar las etiquetas del eje Y
    ax.set_yticks(y_pos)
    ax.set_yticklabels(nombres, fontsize=11, color='#2c3e50')
    
    # Personalizar grid
    ax.grid(True, axis='x', alpha=0.3, linestyle='--', linewidth=0.8)
    ax.set_axisbelow(True)
    
    # Añadir los valores en las barras con mejor formato
    for i, (bar, cantidad) in enumerate(zip(bars, cantidades)):
        width = bar.get_width()
        # Añadir número de estudiantes
        ax.text(width + max(cantidades) * 0.01, bar.get_y() + bar.get_height()/2., 
                f'{int(width)} estudiantes', 
                ha='left', va='center', fontsize=11, fontweight='bold', color='#2c3e50')
        
        # Añadir ranking
        ax.text(-max(cantidades) * 0.02, bar.get_y() + bar.get_height()/2., 
                f'#{i+1}', 
                ha='right', va='center', fontsize=10, fontweight='bold', 
                color='white', bbox=dict(boxstyle='circle', facecolor=colors[i], alpha=0.8))
    
    # Personalizar bordes
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.spines['left'].set_color('#bdc3c7')
    ax.spines['bottom'].set_color('#bdc3c7')
    
    # Añadir información adicional
    total_inscripciones = sum(cantidades)
    ax.text(0.02, 0.98, f'Total de inscripciones: {total_inscripciones}', 
            transform=ax.transAxes, fontsize=12, fontweight='bold',
            bbox=dict(boxstyle='round,pad=0.5', facecolor='#ecf0f1', alpha=0.8),
            verticalalignment='top', color='#2c3e50')
    
    # Ajustar márgenes
    plt.subplots_adjust(left=0.25, right=0.95, top=0.9, bottom=0.1)
    
    # Guardar la imagen con alta calidad
    plt.savefig('grafica_materias.png', dpi=300, bbox_inches='tight', 
                facecolor='#f8f9fa', edgecolor='none')
    print("Gráfica generada correctamente: grafica_materias.png")
    
    # No mostrar la gráfica en modo servidor
    # plt.show()

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
