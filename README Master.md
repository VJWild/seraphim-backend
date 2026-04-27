# **🪽 Seraphim \- Bitácora de Emergencia SOS**

## **📖 Sobre el Proyecto**

**Seraphim** es una plataforma web integral diseñada para actuar como un "ángel guardián" digital en situaciones de emergencia médica o accidentes viales.

El sistema permite a los usuarios registrar sus datos vitales (tipo de sangre, alergias, condiciones médicas) y contactos de emergencia. A partir de esto, genera un código QR optimizado (tamaño 3x3 cm) diseñado para adherirse al reverso de la Cédula de Identidad. Al ser escaneado por un primer respondiente, Seraphim despliega un perfil clínico de lectura rápida y permite notificar a los familiares vía WhatsApp con un solo toque, enviando alertas y ubicación exacta.

## **🏗 Arquitectura del Sistema**

Seraphim utiliza una arquitectura Headless (desacoplada) para garantizar la máxima velocidad de carga en dispositivos móviles durante situaciones críticas.

* **Backend (API REST):** Laravel 11.x  
* **Frontend (SPA):** React.js \+ Vite  
* **Estilos:** Tailwind CSS (Diseño Mobile-First, UI limpia y de alto contraste).  
* **Base de Datos:** MySQL  
* **Integraciones:** Google Maps API (Geolocalización) y WhatsApp API (Notificaciones).

## **🎨 Sistema de Diseño (UI/UX)**

El objetivo visual de Seraphim es transmitir **cercanía, modernidad y una alerta clara sin frialdad clínica**.

### **1\. Tipografía 🔤**

Se utiliza una única familia tipográfica geométrica y redondeada para mantener una excelente legibilidad en pantallas móviles.

* **Familia:** Poppins (Google Fonts).  
* **Pesos Principales:**  
  * **Regular (400):** Textos de lectura largos y datos secundarios.  
  * **Medium (500):** Subtítulos y pestañas de navegación.  
  * **Semi-Bold (600):** Resaltar información crítica (Nombres, medicamentos, títulos).  
  * **Bold (700):** Logotipo, alertas médicas extremas y botones (CTAs).

### **2\. Paleta de Colores 🎨**

El sistema soporta Modo Claro y Oscuro, basándose en tonos salmón para evitar la frialdad clásica de las apps de hospitales.

**Modo Claro (Light Theme)**

* **Fondo Base (\#FEF8F7):** Blanco hueso con un micro-toque salmón cálido.  
* **Primario \- Salmón (\#FF6B6B):** Color de marca (botones, pestañas activas).  
* **Alerta Crítica (\#DC2626):** Rojo profundo (alergias graves, tipo de sangre).  
* **Textos:** Gris pizarra oscuro (slate-800 de Tailwind).

**Modo Oscuro (Dark Theme)**

* **Fondo Base (\#1C1514):** Gris casi negro con un matiz cálido/marrón.  
* **Primario \- Salmón Brillante (\#FF8A8A):** Ajustado para alto contraste.  
* **Alerta Crítica (\#EF4444):** Rojo vibrante.  
* **Textos:** Gris muy claro (slate-200 de Tailwind).

### **3\. Elementos Visuales e Interacción ✨**

* **La "Gotica de Sangre" 🩸:** El indicador del tipo de sangre utiliza un truco CSS (border-radius: 0 50% 50% 50%; transform: rotate(45deg);) para transformar un cuadrado en una gota, añadiendo semántica visual al dato médico.  
* **Botones "Hover Fill" 🖱️:** En reposo son minimalistas (solo contorno color Salmón). Al interactuar (hover), un div interno sólido se desliza y llena el botón, cambiando el texto a blanco.  
* **Glassmorphism 🪟:** Las tarjetas principales utilizan fondos translúcidos (bg-white/70), desenfoque (backdrop-blur-md) y bordes sutiles iluminados para crear una estética Premium de placa de vidrio flotante.

## **🗄️ Esquema de Base de Datos**

Este es el diseño oficial de la base de datos relacional, estructurado para optimizar la consulta rápida de datos vitales.

### **1\. Tabla users**

Maneja la autenticación, seguridad y acceso al panel de control del usuario. Incluye gestión de roles para el panel de administración.

| Campo | Tipo | Restricciones | Descripción |
| :---- | :---- | :---- | :---- |
| id | BigInt | Primary Key, Auto Increment | Identificador único del usuario. |
| email | String | Unique, Not Null | Correo electrónico para acceso. |
| password | String | Not Null, Hashed | Contraseña encriptada. |
| is\_active | Boolean | Default: true | Permite desactivar la cuenta sin borrar datos. |
| is\_admin | Boolean | Default: false | Otorga permisos para ver métricas. |
| created\_at | Timestamp | Nullable | Fecha de creación. |
| updated\_at | Timestamp | Nullable | Fecha de última actualización. |

### **2\. Tabla profiles**

Entidad central (Relación 1:1 con users). Almacena la información vital y pública.

| Campo | Tipo | Restricciones | Descripción |
| :---- | :---- | :---- | :---- |
| id\_number | String | Unique, Not Null | Cédula de Identidad (Ej: V-12345678). |
| full\_name | String | Not Null | Nombre completo del usuario. |
| birth\_date | Date | Not Null | Fecha de nacimiento. |
| gender | Enum | 'M', 'F', 'Otro' | Género biológico/identidad. |
| weight | Decimal(5,2) | Nullable | Peso en Kilogramos (Ej: 75.50). |
| height | Decimal(3,2) | Nullable | Altura en Metros (Ej: 1.75). |
| blood\_type | Enum | 'A+', 'O-', etc. | Grupo sanguíneo para transfusiones. |
| allergies | Text | Nullable | Alergias a medicamentos, alimentos, etc. |
| medical\_conditions | Text | Nullable | Enfermedades base (Hipertensión, asma, etc). |
| current\_medications | Text | Nullable | Medicinas que toma a diario actualmente. |
| organ\_donor | Boolean | Default: false | Indicador de donante de órganos. |
| address\_text | String | Not Null | Dirección de residencia formateada/escrita. |
| latitude | Decimal(10,8) | Nullable | Coordenada GPS para Google Maps. |
| longitude | Decimal(11,8) | Nullable | Coordenada GPS para Google Maps. |
| qr\_slug | String | Unique, Not Null | UUID o cadena aleatoria para URL pública. |

### **3\. Tabla emergency\_contacts**

Directorio de emergencia (Relación 1:N con profiles).

| Campo | Tipo | Restricciones | Descripción |
| :---- | :---- | :---- | :---- |
| name | String | Not Null | Nombre y apellido del contacto. |
| relationship | String | Not Null | Parentesco (Madre, Padre, Pareja, etc). |
| phone\_number | String | Not Null | Teléfono en formato internacional (+58...). |
| is\_primary | Boolean | Default: false | Indica si es el primer contacto a llamar. |

### **4\. Tabla feedbacks**

Almacena las valoraciones y respuestas de encuestas de los usuarios para métricas.

| Campo | Tipo | Restricciones | Descripción |
| :---- | :---- | :---- | :---- |
| rating | TinyInteger | Unsigned (1 a 5\) | Calificación general de la aplicación. |
| comments | Text | Nullable | Comentarios o sugerencias del usuario. |
| survey\_responses | JSON | Nullable | Respuestas dinámicas a encuestas. |

## **🔒 Privacidad y Seguridad (By Design)**

* **Anonimato del QR:** Los códigos QR de Seraphim **nunca** exponen la Cédula de Identidad del usuario en la URL pública. Utilizan un slug único (ej. seraphim.app/sos/a1b2c3d4) que el backend resuelve de manera segura.  
* **Protección Anti-Scraping:** El endpoint público de emergencias cuenta con *Rate Limiting* estricto para evitar la extracción masiva de datos médicos.

## **🚀 Instalación Local (Fase 1\)**

### **Backend (seraphim-backend)**

cd seraphim-backend  
composer install  
cp .env.example .env  
php artisan key:generate  
\# Configurar variables DB en .env  
php artisan migrate  
php artisan serve

### **Frontend (seraphim-frontend)**

**⚠️ Nota Importante:** Es obligatorio que la instalación inicial y cualquier instalación futura de paquetes o dependencias en este proyecto se realice utilizando el flag \--legacy-peer-deps. Esto asegura la compatibilidad y evita conflictos de versiones en el entorno de React y Vite.

cd seraphim-frontend  
npm install \--legacy-peer-deps  
npm run dev

*Proyecto iniciado con el objetivo de salvar vidas conectando lo físico (Cédula) con lo digital de manera inmediata.*