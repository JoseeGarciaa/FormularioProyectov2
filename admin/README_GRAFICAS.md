# 📊 Gráficas de Materias - Panel de Administrador

## Descripción
Esta funcionalidad permite a los administradores visualizar y descargar gráficas que muestran las materias más inscritas por los estudiantes.

## Características

### 🎯 Visualización
- **Top 10 materias más inscritas**: Muestra las materias con mayor número de inscripciones
- **Ranking visual**: Cada barra tiene un número de ranking (#1, #2, etc.)
- **Información detallada**: Muestra el número exacto de estudiantes por materia
- **Total de inscripciones**: Contador general en la esquina superior izquierda
- **Diseño moderno**: Colores degradados y diseño profesional

### 🔄 Funcionalidades
- **Actualización en tiempo real**: Botón "Actualizar" para regenerar la gráfica con datos actuales
- **Descarga**: Botón "Descargar" para obtener la gráfica en formato PNG
- **Estados visuales**: Loading, éxito y error con mensajes informativos
- **Responsive**: Se adapta a diferentes tamaños de pantalla

## Uso

### En el Dashboard
1. Ve al **Panel de Administración** (`dashboard.php`)
2. Busca la sección **"Gráfica de Materias Más Inscritas"**
3. Si no hay gráfica, haz clic en **"Actualizar"** para generarla
4. Una vez generada, puedes hacer clic en **"Descargar"** para obtener el archivo PNG

### Botones Disponibles
- **🔄 Actualizar**: Regenera la gráfica con los datos más recientes
- **📥 Descargar**: Descarga la gráfica como archivo PNG con timestamp

## Archivos Técnicos

### Python (`grafica_materias.py`)
- Conecta a la base de datos MySQL
- Consulta las inscripciones de todas las materias (Materia1 a Materia7)
- Genera gráfica con matplotlib
- Guarda como `grafica_materias.png`

### PHP (`descargar_grafica.php`)
- Maneja la descarga del archivo PNG
- Verifica permisos de administrador
- Genera nombre único con timestamp
- Configura headers apropiados para descarga

### Integración (`dashboard.php`)
- Sección HTML para mostrar la gráfica
- JavaScript para AJAX y manejo de estados
- CSS personalizado para diseño responsive

## Requisitos Técnicos

### Dependencias Python
```bash
pip install pymysql matplotlib numpy
```

### Permisos
- Solo usuarios con rol 'admin' pueden acceder
- Verificación de sesión en todos los archivos PHP

## Datos Mostrados

La gráfica analiza las siguientes columnas de la tabla `Inscripciones`:
- Materia1, Materia2, Materia3, Materia4, Materia5, Materia6, Materia7

### Ejemplo de Datos
```
#1. Sistemas Operativos: 54 estudiantes
#2. Matemáticas I: 40 estudiantes  
#3. Base de Datos: 38 estudiantes
#4. Ingeniería de Software: 36 estudiantes
#5. Cálculo II: 35 estudiantes
```

## Personalización

### Colores
- Paleta de colores: Viridis (azul-verde-amarillo)
- Fondo: #f8f9fa (gris claro)
- Texto: #2c3e50 (gris oscuro)

### Límites
- **Top 10**: Solo muestra las 10 materias más populares
- **Resolución**: 300 DPI para alta calidad
- **Formato**: PNG con transparencia

## Solución de Problemas

### Error: "No module named 'pymysql'"
```bash
pip install pymysql matplotlib numpy
```

### Error: "No se pudo generar la gráfica"
1. Verificar conexión a base de datos
2. Comprobar que existen datos en la tabla `Inscripciones`
3. Verificar permisos de escritura en el directorio

### La gráfica no se actualiza
- Hacer clic en "Actualizar" para regenerar
- Verificar que el script Python se ejecute correctamente
- Revisar logs del servidor web
