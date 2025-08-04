import pymysql
import matplotlib.pyplot as plt
import numpy as np
from collections import Counter

# Configurar matplotlib para mejor visualización
plt.style.use('default')
plt.rcParams['font.family'] = ['Arial', 'sans-serif']
plt.rcParams['axes.unicode_minus'] = False
plt.rcParams['figure.facecolor'] = '#f8f9fa'
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
    """
    Obtiene los datos reales de materias desde la base de datos
    Cuenta cuántos estudiantes han inscrito cada materia
    """
    try:
        print("Conectando a la base de datos...")
        connection = pymysql.connect(**db_config)
        
        with connection.cursor() as cursor:
            # Verificar total de inscripciones
            cursor.execute("SELECT COUNT(*) as total FROM Inscripciones")
            total_registros = cursor.fetchone()['total']
            print(f"Total de inscripciones: {total_registros}")
            
            if total_registros == 0:
                print("No hay datos en la tabla Inscripciones")
                return []
            
            # Consulta simplificada y más eficiente
            materias_count = {}
            
            # Contar cada materia en cada columna
            for i in range(1, 8):
                sql = f"""
                SELECT Materia{i} as materia, COUNT(*) as cantidad
                FROM Inscripciones 
                WHERE Materia{i} IS NOT NULL 
                  AND Materia{i} != '' 
                  AND Materia{i} != 'NULL'
                  AND TRIM(Materia{i}) != ''
                GROUP BY Materia{i}
                """
                
                cursor.execute(sql)
                resultados = cursor.fetchall()
                
                for row in resultados:
                    materia = row['materia'].strip()
                    cantidad = row['cantidad']
                    
                    if materia in materias_count:
                        materias_count[materia] += cantidad
                    else:
                        materias_count[materia] = cantidad
            
            # Ordenar por cantidad (descendente) y tomar top 10
            materias_ordenadas = sorted(materias_count.items(), key=lambda x: x[1], reverse=True)[:10]
            
            print("\nMaterias encontradas:")
            for materia, cantidad in materias_ordenadas:
                print(f"  {materia}: {cantidad} estudiantes")
            
            return materias_ordenadas
            
    except Exception as e:
        print(f"Error: {e}")
        import traceback
        traceback.print_exc()
        return []
    finally:
        if 'connection' in locals() and connection.open:
            connection.close()

def generar_grafica(materias):
    """
    Genera una gráfica moderna y atractiva de barras verticales
    """
    if not materias:
        print("No hay datos para mostrar.")
        return
    
    print(f"Generando gráfica con {len(materias)} materias...")
    
    # Separar nombres y cantidades
    nombres = [m[0] for m in materias]
    cantidades = [m[1] for m in materias]
    
    # Crear figura con diseño moderno
    fig, ax = plt.subplots(figsize=(14, 9))
    fig.patch.set_facecolor('#f8f9fa')
    
    # Paleta de colores moderna y vibrante
    colors = ['#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6', 
              '#1abc9c', '#e67e22', '#34495e', '#f1c40f', '#95a5a6']
    
    # Crear barras con efectos modernos
    x_pos = np.arange(len(nombres))
    bars = ax.bar(x_pos, cantidades, 
                  color=colors[:len(nombres)], 
                  alpha=0.8,
                  edgecolor='white',
                  linewidth=3,
                  width=0.7)
    
    # Añadir efectos visuales simples
    for i, bar in enumerate(bars):
        # Mejorar el borde de cada barra
        bar.set_edgecolor('#2c3e50')
        bar.set_linewidth(2)
    
    # Título principal elegante
    ax.set_title('Materias Más Inscritas por Estudiantes', 
                fontsize=22, fontweight='bold', pad=30, 
                color='#2c3e50', family='serif')
    
    # Subtítulo informativo
    total_inscripciones = sum(cantidades)
    ax.text(0.5, 0.95, f'Total de Inscripciones: {total_inscripciones}',
            transform=ax.transAxes, ha='center', fontsize=14,
            style='italic', color='#7f8c8d')
    
    # Etiquetas de ejes con estilo
    ax.set_xlabel('Materias Académicas', fontsize=16, fontweight='bold', 
                  color='#34495e', labelpad=15)
    ax.set_ylabel('Número de Estudiantes Inscritos', fontsize=16, fontweight='bold', 
                  color='#34495e', labelpad=15)
    
    # Configurar etiquetas del eje X
    ax.set_xticks(x_pos)
    ax.set_xticklabels(nombres, rotation=45, ha='right', fontsize=12, 
                       color='#2c3e50', fontweight='500')
    
    # Grid sutil y elegante
    ax.grid(True, axis='y', alpha=0.3, linestyle='-', linewidth=0.5, color='#bdc3c7')
    ax.set_axisbelow(True)
    
    # Valores en las barras con diseño mejorado
    for i, (bar, cantidad) in enumerate(zip(bars, cantidades)):
        height = bar.get_height()
        
        # Número de estudiantes arriba de la barra
        ax.text(bar.get_x() + bar.get_width()/2., height + max(cantidades) * 0.02,
                f'{int(cantidad)}',
                ha='center', va='bottom', fontsize=13, fontweight='bold', 
                color='#2c3e50',
                bbox=dict(boxstyle='round,pad=0.3', facecolor='white', 
                         edgecolor=colors[i], linewidth=2, alpha=0.9))
        
        # Ranking dentro de la barra
        ax.text(bar.get_x() + bar.get_width()/2., height * 0.1,
                f'#{i+1}',
                ha='center', va='center', fontsize=14, fontweight='bold',
                color='white', 
                bbox=dict(boxstyle='circle,pad=0.4', facecolor='#2c3e50', alpha=0.9))
    
    # Personalizar bordes
    for spine in ax.spines.values():
        spine.set_visible(False)
    
    # Configurar límites del eje Y para mejor visualización
    ax.set_ylim(0, max(cantidades) * 1.15)
    
    # Añadir información estadística
    promedio = np.mean(cantidades)
    ax.axhline(y=promedio, color='#e74c3c', linestyle='--', alpha=0.7, linewidth=2)
    ax.text(len(nombres) - 1, promedio + max(cantidades) * 0.03, 
            f'Promedio: {promedio:.1f}',
            ha='right', va='bottom', fontsize=11, 
            bbox=dict(boxstyle='round,pad=0.3', facecolor='#e74c3c', 
                     alpha=0.8, edgecolor='white'),
            color='white', fontweight='bold')
    
    # Ajustar layout
    plt.tight_layout()
    plt.subplots_adjust(bottom=0.2, top=0.85, left=0.1, right=0.95)
    
    # Guardar con alta calidad
    plt.savefig('grafica_materias.png', dpi=300, bbox_inches='tight', 
                facecolor='#f8f9fa', edgecolor='none', pad_inches=0.2)
    print("Gráfica generada correctamente: grafica_materias.png")
    
    # Liberar memoria
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
