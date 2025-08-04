import pymysql
import matplotlib.pyplot as plt
import numpy as np
from collections import Counter
import matplotlib.patches as patches

# Configurar matplotlib para mejor visualización
plt.style.use('default')
plt.rcParams['font.family'] = ['Arial', 'DejaVu Sans', 'sans-serif']
plt.rcParams['axes.unicode_minus'] = False
plt.rcParams['figure.facecolor'] = 'white'
plt.rcParams['axes.facecolor'] = 'white'

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
        print("Conectando a la base de datos...")
        connection = pymysql.connect(**db_config)
        print("Conexión exitosa!")
        
        with connection.cursor() as cursor:
            # Primero verificar si hay datos en la tabla
            cursor.execute("SELECT COUNT(*) as total FROM Inscripciones")
            total_registros = cursor.fetchone()['total']
            print(f"Total de registros en Inscripciones: {total_registros}")
            
            if total_registros == 0:
                print("No hay datos en la tabla Inscripciones")
                return []
            
            # Consulta mejorada para obtener todas las materias
            sql = """
            SELECT materia_nombre, COUNT(*) as total_estudiantes
            FROM (
                SELECT Materia1 as materia_nombre FROM Inscripciones WHERE Materia1 IS NOT NULL AND Materia1 != '' AND Materia1 != 'NULL'
                UNION ALL
                SELECT Materia2 as materia_nombre FROM Inscripciones WHERE Materia2 IS NOT NULL AND Materia2 != '' AND Materia2 != 'NULL'
                UNION ALL
                SELECT Materia3 as materia_nombre FROM Inscripciones WHERE Materia3 IS NOT NULL AND Materia3 != '' AND Materia3 != 'NULL'
                UNION ALL
                SELECT Materia4 as materia_nombre FROM Inscripciones WHERE Materia4 IS NOT NULL AND Materia4 != '' AND Materia4 != 'NULL'
                UNION ALL
                SELECT Materia5 as materia_nombre FROM Inscripciones WHERE Materia5 IS NOT NULL AND Materia5 != '' AND Materia5 != 'NULL'
                UNION ALL
                SELECT Materia6 as materia_nombre FROM Inscripciones WHERE Materia6 IS NOT NULL AND Materia6 != '' AND Materia6 != 'NULL'
                UNION ALL
                SELECT Materia7 as materia_nombre FROM Inscripciones WHERE Materia7 IS NOT NULL AND Materia7 != '' AND Materia7 != 'NULL'
            ) AS todas_materias
            GROUP BY materia_nombre
            ORDER BY total_estudiantes DESC
            LIMIT 10
            """
            
            print("Ejecutando consulta...")
            cursor.execute(sql)
            resultados = cursor.fetchall()
            
            print(f"Resultados obtenidos: {len(resultados)}")
            
            # Procesar resultados
            materias_data = []
            for row in resultados:
                materia = row['materia_nombre'].strip()
                total = row['total_estudiantes']
                materias_data.append((materia, total))
                print(f"  - {materia}: {total} estudiantes")
            
            return materias_data
            
    except Exception as e:
        print(f"Error al conectar a la base de datos: {e}")
        import traceback
        traceback.print_exc()
        return []
    finally:
        if 'connection' in locals() and connection.open:
            connection.close()
            print("Conexión cerrada.")

def generar_grafica(materias):
    if not materias:
        print("No hay datos para mostrar.")
        return
    
    print(f"Generando gráfica con {len(materias)} materias...")
    
    # Separar nombres y cantidades
    nombres = [m[0] for m in materias]
    cantidades = [m[1] for m in materias]
    
    # Crear figura con tamaño adecuado
    fig, ax = plt.subplots(figsize=(12, 8))
    fig.patch.set_facecolor('#ffffff')
    
    # Definir colores modernos y atractivos
    colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', 
              '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9']
    
    # Asegurar que tenemos suficientes colores
    if len(nombres) > len(colors):
        colors = colors * (len(nombres) // len(colors) + 1)
    
    # Crear gráfica de barras VERTICALES moderna
    bars = ax.bar(range(len(nombres)), cantidades, 
                  color=colors[:len(nombres)], 
                  alpha=0.9, 
                  edgecolor='#2c3e50', 
                  linewidth=2,
                  width=0.6)
    
    # Añadir efectos visuales simples
    for i, bar in enumerate(bars):
        # Efecto de gradiente sutil cambiando el alpha
        bar.set_alpha(0.85)
        
        # Añadir borde más pronunciado para definición
        bar.set_edgecolor('#34495e')
        bar.set_linewidth(2.5)
    
    # Personalizar título principal
    ax.set_title('📚 Materias Inscritas por Estudiantes', 
                fontsize=18, fontweight='bold', pad=25, color='#2c3e50')
    
    # Añadir subtítulo con información adicional
    total_estudiantes_unicos = len(set(range(len(nombres))))  # Simplificado para este caso
    fig.suptitle(f'Análisis de Inscripciones - Total: {sum(cantidades)} inscripciones', 
                fontsize=12, color='#7f8c8d', y=0.02)
    
    # Personalizar etiquetas de ejes
    ax.set_xlabel('Materias', fontsize=14, fontweight='bold', color='#34495e')
    ax.set_ylabel('Número de Estudiantes', fontsize=14, fontweight='bold', color='#34495e')
    
    # Configurar etiquetas del eje X (nombres de materias)
    ax.set_xticks(range(len(nombres)))
    ax.set_xticklabels(nombres, rotation=45, ha='right', fontsize=10, color='#2c3e50')
    
    # Personalizar grid
    ax.grid(True, axis='y', alpha=0.3, linestyle='--', linewidth=0.8, color='#bdc3c7')
    ax.set_axisbelow(True)
    
    # Añadir valores en la parte superior de cada barra
    for i, (bar, cantidad) in enumerate(zip(bars, cantidades)):
        height = bar.get_height()
        
        # Número de estudiantes en la parte superior
        ax.text(bar.get_x() + bar.get_width()/2., height + max(cantidades) * 0.01,
                f'{int(cantidad)}',
                ha='center', va='bottom', fontsize=11, fontweight='bold', color='#2c3e50')
        
        # Ranking en la parte inferior de la barra
        ax.text(bar.get_x() + bar.get_width()/2., height/2,
                f'#{i+1}',
                ha='center', va='center', fontsize=12, fontweight='bold',
                color='white', 
                bbox=dict(boxstyle='circle,pad=0.3', facecolor='#2c3e50', alpha=0.8))
    
    # Personalizar bordes del gráfico
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.spines['left'].set_color('#bdc3c7')
    ax.spines['bottom'].set_color('#bdc3c7')
    
    # Añadir información adicional
    total_inscripciones = sum(cantidades)
    ax.text(0.02, 0.98, f'Total: {total_inscripciones} inscripciones', 
            transform=ax.transAxes, fontsize=12, fontweight='bold',
            bbox=dict(boxstyle='round,pad=0.5', facecolor='#ecf0f1', alpha=0.9),
            verticalalignment='top', color='#2c3e50')
    
    # Ajustar el layout para que las etiquetas no se corten
    plt.tight_layout()
    
    # Ajustar márgenes
    plt.subplots_adjust(bottom=0.15, top=0.9, left=0.1, right=0.95)
    
    # Guardar la imagen con alta calidad
    plt.savefig('grafica_materias.png', dpi=300, bbox_inches='tight', 
                facecolor='white', edgecolor='none')
    print("Gráfica generada correctamente: grafica_materias.png")
    
    # Cerrar la figura para liberar memoria
    plt.close()

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
