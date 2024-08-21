<?php
/*
Plugin Name: Galigeo Builder
Description: Un builder personnalisé pour WordPress
Version: 1.0
Author: Frédéric Ferri
*/

if (!defined('ABSPATH')) exit;

class Mon_Builder_Personnalise {
    public function __construct() {
        add_action('init', array($this, 'init'));
    }

    public function init() {
        $this->load_dependencies();
        $this->setup_actions();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(__FILE__) . 'includes/class-builder-metabox.php';
        require_once plugin_dir_path(__FILE__) . 'includes/class-builder-frontend.php';
    }

    private function setup_actions() {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_style('builder-admin-css', plugin_dir_url(__FILE__) . 'assets/css/builder-admin.css');
        wp_enqueue_script('builder-admin-js', plugin_dir_url(__FILE__) . 'assets/js/builder-admin.js', array('jquery'), '1.0', true);
    }

    public function enqueue_frontend_scripts() {
        wp_enqueue_style('builder-tailwind-css', plugin_dir_url(__FILE__) . 'assets/css/tailwind-output.css');
        wp_enqueue_script('builder-frontend-js', plugin_dir_url(__FILE__) . 'assets/js/builder-frontend.js', array('jquery'), '1.0', true);
    }
}

add_action('wp_ajax_get_block_html', 'get_block_html_ajax');



$mon_builder_personnalise = new Mon_Builder_Personnalise();

// Fonction pour gérer la requête AJAX
function get_block_html_ajax() {
    error_log('Fonction get_block_html_ajax appelée');

    if (!check_ajax_referer('builder_nonce', 'nonce', false)) {
        error_log('Échec de la vérification du nonce');
        wp_send_json_error('Nonce invalide');
        return;
    }

    if (!isset($_POST['type']) || !isset($_POST['index'])) {
        error_log('Paramètres manquants dans la requête POST');
        wp_send_json_error('Paramètres manquants');
        return;
    }

    $type = sanitize_text_field($_POST['type']);
    $index = intval($_POST['index']);

    error_log("Type: $type, Index: $index");

    if (!class_exists('Builder_Metabox')) {
        error_log('La classe Builder_Metabox n\'existe pas');
        wp_send_json_error('Classe non trouvée');
        return;
    }

    ob_start();
    $metabox = new Builder_Metabox();
    $metabox->render_block(array('type' => $type, 'data' => array()), $index);
    $html = ob_get_clean();

    error_log("HTML généré : " . substr($html, 0, 100) . '...');

    wp_send_json_success($html);
}

// Hook pour la fonction AJAX
add_action('wp_ajax_get_block_html', 'get_block_html_ajax');