import pymysql
import pandas as pd
import os

def obtener_datos():
    # Variables de conexión
    host = 'roundhouse.proxy.rlwy.net'
    user = 'root'
    password = 'xevACkoMYkhovGUpFykFmEVStdCzQlbf'
    db = 'railway'
    port = 50768

    # Conexión a la base de datos
    conexion = pymysql.connect(
        host=host,
        user=user,
        password=password,
        db=db,
        port=port
    )

    # Realizar una consulta
    consulta = """
SELECT Materia, COUNT(*) AS Cantidad
    FROM (
        SELECT Materia1 AS Materia FROM railway.FormularioInscripcion
        UNION ALL
        SELECT Materia2 FROM railway.FormularioInscripcion
        UNION ALL
        SELECT Materia3 FROM railway.FormularioInscripcion
        UNION ALL
        SELECT Materia4 FROM railway.FormularioInscripcion
        UNION ALL
        SELECT Materia5 FROM railway.FormularioInscripcion
        UNION ALL
        SELECT Materia6 FROM railway.FormularioInscripcion
        UNION ALL
        SELECT Materia7 FROM railway.FormularioInscripcion
    ) AS Materias
    WHERE Materia <> ''
    GROUP BY Materia
    ORDER BY Cantidad DESC;
    """
    df = pd.read_sql_query(consulta, conexion)

    # Cerrar la conexión
    conexion.close()
    
    return df

if __name__ == "__main__":
    datos = obtener_datos()
    print(datos.head())


