import pymysql
import matplotlib.pyplot as plt
import numpy as np

# Configuración de la base de datos (igual que en dashboard.php)
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
    """
    Obtiene las materias más inscritas consultando la misma tabla que dashboard.php
    """
    try:
        connection = pymysql.connect(**db_config)
        print("Conectado a la base de datos...")
        
        with connection.cursor() as cursor:
            # Consultar todas las inscripciones (igual que dashboard.php)
            sql = """
            SELECT Materia1, Materia2, Materia3, Materia4, Materia5, Materia6, Materia7
            FROM Inscripciones
            """
            cursor.execute(sql)
            inscripciones = cursor.fetchall()
            
            print(f"Total de inscripciones encontradas: {len(inscripciones)}")
            
            # Contar cada materia
            contador_materias = {}
            
            for inscripcion in inscripciones:
                # Revisar cada columna de materia
                for i in range(1, 8):
                    materia = inscripcion[f'Materia{i}']
                    if materia and materia.strip() and materia != 'NULL':
                        materia = materia.strip()
                        if materia in contador_materias:
                            contador_materias[materia] += 1
                        else:
                            contador_materias[materia] = 1
            
            # Ordenar de mayor a menor
            materias_ordenadas = sorted(contador_materias.items(), key=lambda x: x[1], reverse=True)
            
            print("\nMaterias más inscritas:")
            for i, (materia, cantidad) in enumerate(materias_ordenadas, 1):
                print(f"{i}. {materia}: {cantidad} estudiantes")
            
            return materias_ordenadas
            
    except Exception as e:
        print(f"Error: {e}")
        return []
    finally:
        if 'connection' in locals():
            connection.close()

def crear_grafica(materias_data):
    """
    Crea una gráfica de barras simple y bonita
    """
    if not materias_data:
        print("No hay datos para la gráfica")
        return
    
    # Preparar datos
    materias = [item[0] for item in materias_data]
    cantidades = [item[1] for item in materias_data]
    
    # Crear la gráfica
    plt.figure(figsize=(12, 8))
    
    # Colores atractivos
    colores = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', 
               '#DDA0DD', '#98D8C8', '#F7DC6F', '#BB8FCE', '#85C1E9']
    
    # Crear barras
    barras = plt.bar(range(len(materias)), cantidades, 
                     color=colores[:len(materias)], 
                     alpha=0.8, 
                     edgecolor='black', 
                     linewidth=1)
    
    # Personalizar la gráfica
    plt.title('Materias Más Inscritas por Estudiantes', fontsize=16, fontweight='bold', pad=20)
    plt.xlabel('Materias', fontsize=12, fontweight='bold')
    plt.ylabel('Número de Estudiantes', fontsize=12, fontweight='bold')
    
    # Etiquetas del eje X
    plt.xticks(range(len(materias)), materias, rotation=45, ha='right')
    
    # Agregar valores encima de las barras
    for i, (barra, cantidad) in enumerate(zip(barras, cantidades)):
        plt.text(barra.get_x() + barra.get_width()/2, barra.get_height() + 1,
                str(cantidad), ha='center', va='bottom', fontweight='bold')
    
    # Grid para mejor lectura
    plt.grid(axis='y', alpha=0.3)
    
    # Ajustar layout
    plt.tight_layout()
    
    # Guardar la gráfica
    plt.savefig('grafica_materias.png', dpi=300, bbox_inches='tight')
    print("Gráfica guardada como 'grafica_materias.png'")
    
    # Mostrar la gráfica (opcional)
    # plt.show()
    
    plt.close()

if __name__ == "__main__":
    print("=== GENERANDO GRÁFICA DE MATERIAS ===")
    
    # Obtener datos
    materias_data = obtener_materias_inscritas()
    
    if materias_data:
        # Crear gráfica
        crear_grafica(materias_data)
        print("¡Gráfica generada exitosamente!")
    else:
        print("No se pudieron obtener los datos")
