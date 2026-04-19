<?php
/*
    Plugin Name: reCAPTCHA v3 Invisible
    Plugin Description: Protege los formularios contra bots utilizando Google reCAPTCHA v3.
    Plugin Version: 1.0
    Plugin Date: 2026-04-18
    Plugin Author: Monkey 🐒
*/

if (!defined('QA_VERSION')) {
    header('Location: ../../');
    exit;
}

qa_register_plugin_module('captcha', 'qa-recaptcha-v3-captcha.php', 'qa_recaptcha_v3_captcha', 'reCAPTCHA v3');
