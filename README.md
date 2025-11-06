# 🚀 Proyecto: Isla Transfers

App de gestión de **transfers** en **PHP nativo** con arquitectura **MVC (Estilo API/Web)**, **Composer** y **Docker**.

---

## ⚙️ Puesta en Marcha (Setup)

### 🧩 Requisitos

- Docker
- Docker Compose
- Composer

---

### 🔧 Clonar el repositorio

```bash
git clone [URL-DE-TU-REPOSITORIO-GIT]
cd [NOMBRE-DEL-PROYECTO]
```

---

### 🧱 Crear el archivo `.env`

Crea un archivo llamado `.env` en la raíz del proyecto.
Si existe un `.env.example`, cópialo.
Si no, añade tus credenciales manualmente:

```env
DB_HOST=db
DB_NAME=isla_transfers
DB_USER=user
DB_PASS=pass
DB_ROOT_PASS=root
```

---

### 📦 Instalar dependencias de PHP

Necesitas **Composer** instalado localmente:

```bash
composer install
```

---

### 🗃️ Importar la base de datos

Asegúrate de que el archivo `.sql` que te dieron está en la carpeta `/sql`.

---

### 🐳 Construir y arrancar el entorno

(Primera vez)

```bash
docker-compose up -d --build
```

---

## 🏃‍♂️ Uso Diario

**Iniciar:**

```bash
docker-compose up -d
```

**Detener:**

```bash
docker-compose down
```

---

## 🌐 Accesos y Credenciales

### 🔗 URLs

- **Aplicación Web (Vistas):** [http://localhost:8080](http://localhost:8080)
- **Endpoints de API:** [http://localhost:8080/api/...](http://localhost:8080/api/...)
- **phpMyAdmin:** [http://localhost:8081](http://localhost:8081)

---

### 🧠 Credenciales BD

_(para `app/config/config.php` o `.env`)_

| Clave    | Valor                                     |
| -------- | ----------------------------------------- |
| **Host** | db _(o `DB_HOST` en `.env`)_              |
| **DB**   | isla*transfers *(o `DB_NAME` en `.env`)\_ |
| **User** | user _(o `DB_USER` en `.env`)_            |
| **Pass** | pass _(o `DB_PASS` en `.env`)_            |

---

### 🔑 Credenciales phpMyAdmin

_(para acceder vía navegador)_

| Campo          | Valor |
| -------------- | ----- |
| **Servidor**   | db    |
| **Usuario**    | root  |
| **Contraseña** | root  |

Para regenerar el Auto Loader después de añadir un controlador:

```bash
composer dump-autoload
```


# Resetear los Contenedores
## 1. Destruir Contenedores
```bash
docker-compose down -v
```

## 2. Volver a Crear
```bash
docker-compose up -d --build
```
