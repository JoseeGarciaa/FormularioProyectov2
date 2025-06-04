import os

def subir_a_github():
    os.system("git init")
    os.system("git add .")
    os.system('git commit -m "Inicial commit"')
    os.system("git remote add origin https://github.com/JoseeGarciaa/FormularioProyecto.git")
    os.system("git push -u origin master")

if __name__ == "__main__":
    subir_a_github()
