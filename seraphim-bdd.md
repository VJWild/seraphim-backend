🪽 Seraphim - Bitácora de Emergencia SOS

📖 Sobre el Proyecto

Seraphim es una plataforma web integral diseñada para actuar como un "ángel guardián" digital en situaciones de emergencia médica o accidentes viales.

El sistema permite a los usuarios registrar sus datos vitales (tipo de sangre, alergias, condiciones médicas) y contactos de emergencia. A partir de esto, genera un código QR optimizado (tamaño 3x3 cm) diseñado para adherirse al reverso de la Cédula de Identidad. Al ser escaneado por un primer respondiente, Seraphim despliega un perfil clínico de lectura rápida y permite notificar a los familiares vía WhatsApp con un solo toque, enviando alertas y ubicación exacta.

🏗 Arquitectura del Sistema

Seraphim utiliza una arquitectura Headless (desacoplada) para garantizar la máxima velocidad de carga en dispositivos móviles durante situaciones críticas.

Backend (API REST): Laravel 11.x

Frontend (SPA): React.js + Vite

Estilos: Tailwind CSS (Diseño Mobile-First, UI limpia y de alto contraste).

Base de Datos: MySQL

Integraciones: Google Maps API (Geolocalización) y WhatsApp API (Notificaciones).

🗄️ Esquema de Base de Datos

Este es el diseño oficial de la base de datos relacional, estructurado para optimizar la consulta rápida de datos vitales.

1. Tabla users

Maneja la autenticación, seguridad y acceso al panel de control del usuario.

Campo

Tipo

Restricciones

Descripción

id

BigInt

Primary Key, Auto Increment

Identificador único del usuario.

email

String

Unique, Not Null

Correo electrónico para acceso.

password

String

Not Null, Hashed

Contraseña encriptada.

is_active

Boolean

Default: true

Permite desactivar la cuenta sin borrar datos.

created_at

Timestamp

Nullable

Fecha de creación.

updated_at

Timestamp

Nullable

Fecha de última actualización.

2. Tabla profiles

Entidad central (Relación 1:1 con users). Almacena la información vital y pública que se renderiza al escanear el código QR.

Datos de Identidad y Físicos

Campo

Tipo

Restricciones

Descripción

id

BigInt

Primary Key, Auto Increment

Identificador único del perfil.

user_id

BigInt

Foreign Key (users.id)

Relación con el usuario dueño del perfil.

id_number

String

Unique, Not Null

Cédula de Identidad (Ej: V-12345678).

full_name

String

Not Null

Nombre completo del usuario.

birth_date

Date

Not Null

Fecha de nacimiento (calcula edad dinámica).

gender

Enum

'M', 'F', 'Otro'

Género biológico/identidad.

weight

Decimal(5,2)

Nullable

Peso en Kilogramos (Ej: 75.50).

height

Decimal(3,2)

Nullable

Altura en Metros (Ej: 1.75).

Datos Médicos (Vitales)

Campo

Tipo

Restricciones

Descripción

blood_type

Enum

'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'

Grupo sanguíneo para transfusiones.

allergies

Text

Nullable

Alergias a medicamentos, alimentos, etc.

medical_conditions

Text

Nullable

Enfermedades base (Hipertensión, asma, etc).

current_medications

Text

Nullable

Medicinas que toma a diario actualmente.

organ_donor

Boolean

Default: false

Indicador de donante de órganos.

health_insurance

String

Nullable

Empresa aseguradora o servicio de salud.

Geolocalización y Sistema

Campo

Tipo

Restricciones

Descripción

address_text

String

Not Null

Dirección de residencia formateada/escrita.

latitude

Decimal(10,8)

Nullable

Coordenada GPS para Google Maps.

longitude

Decimal(11,8)

Nullable

Coordenada GPS para Google Maps.

qr_slug

String

Unique, Not Null

UUID o cadena aleatoria para URL pública.

created_at

Timestamp

Nullable

Fecha de creación.

updated_at

Timestamp

Nullable

Fecha de última actualización.

3. Tabla emergency_contacts

Directorio de emergencia (Relación 1:N con profiles). Un usuario puede tener múltiples contactos.

Campo

Tipo

Restricciones

Descripción

id

BigInt

Primary Key, Auto Increment

Identificador único del contacto.

profile_id

BigInt

Foreign Key (profiles.id)

Relación con el perfil del usuario.

name

String

Not Null

Nombre y apellido del contacto.

relationship

String

Not Null

Parentesco (Madre, Padre, Pareja, etc).

phone_number

String

Not Null

Teléfono en formato internacional (+58...).

is_primary

Boolean

Default: false

Indica si es el primer contacto a llamar.

created_at

Timestamp

Nullable

Fecha de creación.

updated_at

Timestamp

Nullable

Fecha de última actualización.

🔒 Privacidad y Seguridad (By Design)

Anonimato del QR: Los códigos QR de Seraphim nunca exponen la Cédula de Identidad del usuario en la URL pública. Utilizan un slug único (ej. seraphim.app/sos/a1b2c3d4) que el backend resuelve de manera segura.

Protección Anti-Scraping: El endpoint público de emergencias cuenta con Rate Limiting estricto para evitar la extracción masiva de datos médicos.

🚀 Instalación Local (Fase 1)

Backend (seraphim-backend)

cd seraphim-backend
composer install
cp .env.example .env
php artisan key:generate
# Configurar variables DB en .env
php artisan migrate
php artisan serve


Frontend (seraphim-frontend)

⚠️ Nota Importante: Es obligatorio que la instalación inicial y cualquier instalación futura de paquetes o dependencias en este proyecto se realice utilizando el flag --legacy-peer-deps. Esto asegura la compatibilidad y evita conflictos de versiones (peer dependencies) en el entorno de React y Vite.

cd seraphim-frontend
npm install --legacy-peer-deps
# Ejemplo para dependencias futuras: npm install nombre-del-paquete --legacy-peer-deps
npm run dev


Proyecto iniciado con el objetivo de salvar vidas conectando lo físico (Cédula) con lo digital de manera inmediata.
