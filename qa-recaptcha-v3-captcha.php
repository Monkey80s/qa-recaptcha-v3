<?php

class qa_recaptcha_v3_captcha {

    // Valores por defecto para las opciones de configuración
    public function option_default($option) {
        if ($option == 'recaptcha_v3_score') {
            return '0.5'; // Puntuación recomendada por Google
        }
        return null;
    }

    // Interfaz de configuración en el panel de administración
    public function admin_form(&$qa_content) {
        $saved = false;

        if (qa_clicked('recaptcha_v3_save_button')) {
            qa_opt('recaptcha_v3_site_key', qa_post_text('recaptcha_v3_site_key'));
            qa_opt('recaptcha_v3_secret_key', qa_post_text('recaptcha_v3_secret_key'));
            qa_opt('recaptcha_v3_score', qa_post_text('recaptcha_v3_score'));
            $saved = true;
        }

        return array(
            'ok' => $saved ? 'Configuración de reCAPTCHA v3 guardada correctamente.' : null,
            'fields' => array(
                array(
                    'label' => 'Clave del Sitio (Site Key):',
                    'value' => qa_html(qa_opt('recaptcha_v3_site_key')),
                    'tags' => 'name="recaptcha_v3_site_key"',
                ),
                array(
                    'label' => 'Clave Secreta (Secret Key):',
                    'value' => qa_html(qa_opt('recaptcha_v3_secret_key')),
                    'tags' => 'name="recaptcha_v3_secret_key"',
                ),
                array(
                    'label' => 'Umbral de Puntuación (0.0 a 1.0):',
                    'type' => 'number',
                    'value' => qa_html(qa_opt('recaptcha_v3_score')),
                    'tags' => 'name="recaptcha_v3_score" step="0.1" min="0.0" max="1.0"',
                    'note' => 'Los bots obtienen ~0.0, los humanos ~1.0. El valor 0.5 es un excelente balance para iniciar.',
                ),
            ),
            'buttons' => array(
                array(
                    'label' => 'Guardar Cambios',
                    'tags' => 'name="recaptcha_v3_save_button"',
                ),
            ),
        );
    }

    // Verifica si el plugin tiene las claves configuradas para poder funcionar
    public function allow_captcha() {
        return (qa_opt('recaptcha_v3_site_key') && qa_opt('recaptcha_v3_secret_key'));
    }

    // Inyecta el script y el input oculto en el formulario (integración fluida para Snowflat)
    public function form_html(&$qa_content, $error) {
        $site_key = qa_opt('recaptcha_v3_site_key');
        $html = '';

        if (isset($error)) {
            $html .= '<div class="qa-error">'.qa_html($error).'</div>';
        }

        // Generamos un ID único por si existen múltiples captchas en la misma página
        $unique_id = 'g-recaptcha-v3-' . mt_rand();

        $html .= '<input type="hidden" id="'.$unique_id.'" name="g-recaptcha-response-v3">';
        $html .= '<script src="https://www.google.com/recaptcha/api.js?render='.qa_html($site_key).'"></script>';
        $html .= '<script>
            document.addEventListener("DOMContentLoaded", function() {
                var input = document.getElementById("'.$unique_id.'");
                var form = input.closest("form");
                if (form) {
                    form.addEventListener("submit", function(event) {
                        if (!input.value) {
                            event.preventDefault(); // Detenemos el envío
                            grecaptcha.ready(function() {
                                grecaptcha.execute("'.qa_html($site_key).'", {action: "submit"}).then(function(token) {
                                    input.value = token; // Insertamos el token
                                    form.submit(); // Retomamos el envío del formulario
                                });
                            });
                        }
                    });
                }
            });
        </script>';
        
        // Texto discreto para cumplir con las normativas de Google manteniendo un estilo limpio
        $html .= '<div style="font-size: 11px; color: #888; margin-top: 8px;">Protegido de forma invisible por reCAPTCHA v3.</div>';

        return $html;
    }

    // Valida el token recibido en el servidor contra la API de Google
    public function validate_post(&$error) {
        $secret_key = qa_opt('recaptcha_v3_secret_key');
        $token = qa_post_text('g-recaptcha-response-v3');

        if (empty($token)) {
            $error = 'Falta el token de seguridad. Por favor, asegúrate de tener JavaScript activado o recarga la página.';
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $secret_key,
            'response' => $token,
            'remoteip' => qa_remote_ip_address()
        );

        // Usamos file_get_contents con contexto para mayor compatibilidad de servidores
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = @file_get_contents($url, false, $context);

        if ($result === false) {
            $error = 'Error de conexión con el servidor de validación de Google.';
            return false;
        }

        $response = json_decode($result, true);
        $threshold = (float)qa_opt('recaptcha_v3_score');

        // Verificamos si Google aprueba el token y si la puntuación supera nuestro umbral
        if (isset($response['success']) && $response['success'] == true) {
            if (isset($response['score']) && $response['score'] >= $threshold) {
                return true;
            } else {
                $error = 'Actividad automatizada detectada. Puntuación demasiado baja.';
                return false;
            }
        }

        $error = 'La validación del reCAPTCHA ha fallado.';
        return false;
    }
}
