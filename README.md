# Q2A reCAPTCHA v3 Invisible

Un plugin ligero y eficiente para **Question2Answer (Q2A)** que protege tus formularios de registro y publicación contra bots de spam, utilizando la tecnología de Google reCAPTCHA v3. 

A diferencia de versiones anteriores, este plugin es **100% invisible**. No requiere que los usuarios marquen casillas de "No soy un robot" ni resuelvan molestos acertijos visuales. Todo el análisis de seguridad se realiza en segundo plano, mejorando drásticamente la experiencia de usuario (UX) en tu sitio, manteniendo una compatibilidad perfecta con temas modernos como Snowflat.

## ✨ Características

* **Protección Invisible:** Detiene el spam automatizado sin interrumpir a los usuarios legítimos.
* **Puntuación Configurable:** Permite ajustar el umbral de seguridad (Score) directamente desde el panel de administración.
* **Integración Limpia:** Inyecta un texto discreto de privacidad en lugar del badge gigante flotante de Google.
* **Fácil de Configurar:** Panel de administración intuitivo dentro de Q2A.

## 🚀 Instalación

1. Descarga el código fuente de este repositorio.
2. Extrae la carpeta y asegúrate de que se llame `qa-recaptcha-v3`.
3. Sube la carpeta completa al directorio `qa-plugin/` de tu instalación de Question2Answer.
4. Ve a tu sitio web, inicia sesión como Administrador y dirígete a **Administración > Plugins**.
5. Busca "reCAPTCHA v3 Invisible" en la lista de plugins.

## 🔑 Cómo obtener las claves de la API de Google

Para que el plugin funcione, necesitas conectar tu sitio web con Google reCAPTCHA. Sigue estos pasos para obtener tus claves de forma gratuita:

1. Ingresa a la [Consola de administración de reCAPTCHA](https://www.google.com/recaptcha/admin/create) con tu cuenta de Google.
2. En el campo **Etiqueta**, escribe el nombre de tu sitio web (ej. *Mi Foro Q2A*).
3. En **Tipo de reCAPTCHA**, es muy importante que selecciones **reCAPTCHA v3** (las claves v2 no funcionarán con este plugin).
4. En **Dominios**, añade la URL de tu sitio web (ej. `tusitio.com`). No incluyas `http://` ni `www`. Si haces pruebas en local, puedes añadir `localhost`.
5. Acepta las condiciones de servicio de reCAPTCHA y haz clic en **Enviar**.
6. La pantalla final te mostrará dos claves:
   * **Clave del sitio (Site Key)**
   * **Clave secreta (Secret Key)**
7. Copia ambas claves.

## ⚙️ Configuración en Q2A

1. En el panel de **Administración > Plugins** de tu Q2A, pega la **Clave del sitio** y la **Clave secreta** en los campos correspondientes del plugin.
2. El umbral por defecto es `0.5`, que es el recomendado por Google (los bots puntúan cerca de 0.0, los humanos cerca de 1.0).
3. Haz clic en **Guardar Cambios**.
4. Finalmente, ve a **Administración > Spam** en Q2A y asegúrate de seleccionar "reCAPTCHA v3" como el sistema de captcha predeterminado.

## 📝 Licencia

Este proyecto se distribuye bajo la licencia **GPLv2**. Siéntete libre de usarlo, modificarlo y compartirlo para ayudar a crecer la comunidad de Question2Answer.

**Autor:** Monkey 🐒
