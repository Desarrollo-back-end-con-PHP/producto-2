# 🚀 Proyecto: Isla Transfers

App de gestión de transfers en PHP nativo (MVC) y Docker.

## ⚙️ Puesta en Marcha

**Requisitos:** Docker y Docker Compose.

1.  **Clonar el repo:**
    ```bash
    git clone [URL-DE-TU-REPOSITORIO-GIT]
    cd [NOMBRE-DEL-PROYECTO]
    ```

2.  **Poner el `.sql`:**
    * Asegúrate de que el archivo `.sql` que te dieron está en la carpeta `/sql`.

3.  **Construir y arrancar (primera vez):**
    ```bash
    docker-compose up -d --build
    ```

---

## 🏃‍♂️ Uso Diario

* **Iniciar:** `docker-compose up -d`
* **Detener:** `docker-compose down`

---

## 🌐 Accesos y Credenciales

### URLs
* **Aplicación Web:** [http://localhost:8080](http://localhost:8080)
* **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)

### Credenciales BD (para `app/config/config.php`)
* **Host:** `db`
* **DB:** `isla_transfers`
* **User:** `user`
* **Pass:** `pass`

### Credenciales phpMyAdmin (en el navegador)
* **Servidor:** `db`
* **Usuario:** `root`
* **Contraseña:** `root`