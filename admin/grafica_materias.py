import pymysql
import matplotlib.pyplot as plt
import numpy as np
from collections import Counter

# Configuración de la base de datos
db_config = {
    'host': 'roundhouse.proxy.rlwy.net',
    'user': 'root',
    'password': 'xevACkoMYkhovGUpFykFmEVStdCzQlbf',
    'database': 'railway',
    'port': 50768,
    'charset': 'utf8mb4',
    'cursorclass': pymysql.cursors.DictCursor
}

def obtener_materias_inscritas():
    """Obtiene las materias más inscritas de la tabla Inscripciones"""
    try:
        connection = pymysql.connect(**db_config)
        print("Conectado a la base de datos...")
        print("Consultando datos de la tabla Inscripciones...")
        
        with connection.cursor() as cursor:
            # Verificar total de inscripciones
            cursor.execute("SELECT COUNT(*) as total FROM Inscripciones")
            total_inscripciones = cursor.fetchone()['total']
            print(f"Total de inscripciones encontradas: {total_inscripciones}")
            
            # Obtener todas las inscripciones
            cursor.execute("SELECT * FROM Inscripciones")
            inscripciones = cursor.fetchall()
            
            # Contar materias
            contador_materias = Counter()
            total_materias_inscritas = 0
            
            for inscripcion in inscripciones:
                for i in range(1, 8):
                    materia = inscripcion[f'Materia{i}']
                    if materia and materia.strip() and materia.strip() != 'NULL':
                        materia_limpia = materia.strip()
                        contador_materias[materia_limpia] += 1
                        total_materias_inscritas += 1
            
            # Convertir a lista ordenada
            materias_ordenadas = contador_materias.most_common()
            
            print(f"Total de materias inscritas: {total_materias_inscritas}")
            print(f"Materias únicas encontradas: {len(materias_ordenadas)}")
            
            print("\n=== MATERIAS MÁS INSCRITAS ===")
            for i, (materia, cantidad) in enumerate(materias_ordenadas[:15], 1):
                print(f"{i:2d}. {materia}: {cantidad} estudiantes")
            
            return materias_ordenadas
            
    except Exception as e:
        print(f"Error al obtener datos: {e}")
        import traceback
        traceback.print_exc()
        return []
    finally:
        if 'connection' in locals():
            connection.close()

def generar_grafica_materias(materias_ordenadas):
    """Genera una gráfica con las materias más inscritas"""
    if not materias_ordenadas:
        print("No hay datos para mostrar.")
        return
    
    # Tomar solo las top 15 materias para que sea legible
    top_materias = materias_ordenadas[:15]
    
    # Preparar datos
    nombres = [m[0] for m in top_materias]
    cantidades = [m[1] for m in top_materias]
    
    # Configurar matplotlib
    plt.rcParams['font.family'] = 'DejaVu Sans'
    plt.rcParams['font.size'] = 10
    
    # Crear figura más grande para acomodar nombres largos
    fig, ax = plt.subplots(figsize=(16, 10))
    
    # Colores atractivos (gradiente de azules y verdes)
    colores = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FECA57', 
               '#FF9FF3', '#54A0FF', '#5F27CD', '#00D2D3', '#FF9F43',
               '#26de81', '#fc5c65', '#fed330', '#fd79a8', '#fdcb6e']
    
    # Crear barras
    barras = ax.bar(range(len(nombres)), cantidades, 
                   color=colores[:len(nombres)], 
                   edgecolor='white', linewidth=2)
    
    # Personalizar gráfica
    ax.set_xlabel('Materias', fontsize=14, fontweight='bold')
    ax.set_ylabel('Número de Estudiantes Inscritos', fontsize=14, fontweight='bold')
    ax.set_title('Top 15 Materias Más Inscritas\nDatos de la Tabla Inscripciones', 
                fontsize=16, fontweight='bold', pad=20)
    
    # Configurar etiquetas del eje X (rotar más para nombres largos)
    ax.set_xticks(range(len(nombres)))
    # Acortar nombres muy largos
    nombres_cortos = []
    for nombre in nombres:
        if len(nombre) > 30:
            nombres_cortos.append(nombre[:27] + '...')
        else:
            nombres_cortos.append(nombre)
    
    ax.set_xticklabels(nombres_cortos, rotation=45, ha='right', fontsize=10)
    
    # Agregar valores encima de las barras
    for i, (barra, cantidad) in enumerate(zip(barras, cantidades)):
        altura = barra.get_height()
        # Número encima de la barra
        ax.text(barra.get_x() + barra.get_width()/2., altura + 0.2,
                f'{cantidad}', ha='center', va='bottom', 
                fontweight='bold', fontsize=11)
        # Ranking dentro de la barra (solo si la barra es suficientemente alta)
        if altura > 2:
            ax.text(barra.get_x() + barra.get_width()/2., altura/2,
                    f'#{i+1}', ha='center', va='center', 
                    fontweight='bold', fontsize=10, color='white')
    
    # Agregar información estadística
    total_materias = len(materias_ordenadas)
    total_inscripciones = sum([m[1] for m in materias_ordenadas])
    promedio = total_inscripciones / total_materias if total_materias > 0 else 0
    
    # Texto informativo
    info_text = f'Total: {total_materias} materias únicas | {total_inscripciones} inscripciones | Promedio: {promedio:.1f}'
    ax.text(0.02, 0.98, info_text, transform=ax.transAxes, 
            fontsize=10, verticalalignment='top',
            bbox=dict(boxstyle='round', facecolor='lightblue', alpha=0.8))
    
    # Agregar grid sutil
    ax.grid(True, alpha=0.3, linestyle='--', axis='y')
    ax.set_axisbelow(True)
    
    # Ajustar layout
    plt.tight_layout()
    
    # Guardar con alta calidad
    plt.savefig('grafica_materias.png', dpi=300, bbox_inches='tight', 
                facecolor='white', edgecolor='none')
    
    print("Gráfica guardada como 'grafica_materias.png'")
    plt.close()
    # Mostrar la gráfica (opcional)
    # plt.show()
    
    plt.close()

if __name__ == "__main__":
    print("=== GENERANDO GRÁFICA DE MATERIAS MÁS INSCRITAS ===")
    print("Usando datos de la tabla Inscripciones")
    
    # Obtener datos
    materias_ordenadas = obtener_materias_inscritas()
    
    if materias_ordenadas:
        # Generar gráfica
        generar_grafica_materias(materias_ordenadas)
        print("¡Gráfica generada exitosamente!")
    else:
        print("No se pudieron obtener los datos para generar la gráfica.")
