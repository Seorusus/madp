<?php

namespace Drupal\general_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'general_config.config',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'config_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('general_config.config');
        $form['configform'] = array(
            '#type' => 'vertical_tabs',
        );
        /* ============ General setion ============ */
        $form['general'] = array(
            '#title' => $this->t('Header'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'configform'
        );
        $form['general']['site']['site_logo'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Logo MADP'),
            '#default_value' => $config->get('site_logo'),
            '#upload_location' => 'public://general_config/images/',
            '#multiple' => FALSE,
            '#upload_validators' => [
                'file_validate_is_image' => array(),
                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(25600000)
            ],
            '#theme' => 'image_widget',
            '#preview_image_style' => 'medium',
        ];
        $form['general']['site']['site_signature'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Signature MADP'),
            '#default_value' => $config->get('site_signature')['value'],
            '#format' => (empty($config->get('site_signature')['format']) ? 'basic_html' : $config->get('site_signature')['format']),
        ];
        $form['general']['titre_espace_personel'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Bouton Espace personel : Titre'),
            '#default_value' => $config->get('titre_espace_personel'),
        ];
        $form['general']['lien_espace_personel'] = [
            '#type' => 'url',
            '#title' => $this->t('Bouton Espace personel : Url'),
            '#default_value' => $config->get('lien_espace_personel'),
            '#url' => '',
        ];

        /* ============ Page d'accueil setion ============ */
        $form['footer'] = array(
            '#title' => $this->t('Pied de page'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'configform'
        );
        $form['footer']['footer_site_logo'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Logo footer MADP'),
            '#default_value' => $config->get('footer_site_logo'),
            '#upload_location' => 'public://general_config/images/',
            '#multiple' => FALSE,
            '#upload_validators' => [
                'file_validate_is_image' => array(),
                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(25600000)
            ],
            '#theme' => 'image_widget',
            '#preview_image_style' => 'medium',
        ];
        $form['footer']['footer_site_signature'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Signature footer MADP'),
            '#default_value' => $config->get('footer_site_signature')['value'],
            '#format' => (empty($config->get('footer_site_signature')['format']) ? 'basic_html' : $config->get('footer_site_signature')['format']),
        ];
        /* ============ Page d'accueil setion ============ */
        $form['home_page'] = array(
            '#title' => $this->t('Page d\'accueil'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'configform'
        );

        $form['home_page']['bloc_accueil']['accueil_visuel'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Visuel'),
            '#default_value' => $config->get('accueil_visuel'),
            '#upload_location' => 'public://general_config/images/accueil_visuel',
            '#multiple' => FALSE,
            '#upload_validators' => [
                'file_validate_is_image' => array(),
                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(25600000)
            ],
            '#theme' => 'image_widget',
            '#preview_image_style' => 'medium',
        ];
        $form['home_page']['bloc_accueil']['accueil_visuel_mobile'] = [
            '#type' => 'managed_file',
            '#title' => $this->t('Visuel mobile'),
            '#default_value' => $config->get('accueil_visuel_mobile'),
            '#upload_location' => 'public://general_config/images/accueil_visuel_mobile',
            '#multiple' => FALSE,
            '#upload_validators' => [
                'file_validate_is_image' => array(),
                'file_validate_extensions' => array('gif png jpg jpeg'),
                'file_validate_size' => array(25600000)
            ],
            '#theme' => 'image_widget',
            '#preview_image_style' => 'medium',
        ];
        $form['home_page']['bloc_accueil']['accueil_message_1'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Texte 1'),
            '#default_value' => $config->get('accueil_message_1')['value'],
            '#format' => (empty($config->get('accueil_message_1')['format']) ? 'basic_html' : $config->get('accueil_message_1')['format']),
        ];
        $form['home_page']['bloc_accueil']['accueil_message_2'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Texte 2'),
            '#default_value' => $config->get('accueil_message_2')['value'],
            '#format' => (empty($config->get('accueil_message_2')['format']) ? 'basic_html' : $config->get('accueil_message_2')['format']),
        ];
        $form['home_page']['bloc_accueil']['text_button_faire_une_simulation'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Texte bouton (Faire une simulation)'),
            '#default_value' => $config->get('text_button_faire_une_simulation'),
            '#required' => TRUE,
        ];
        $form['home_page']['bloc_accueil']['sous_text_button_faire_une_simulation'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Sous texte bouton (Faire une simulation)'),
            '#default_value' => $config->get('sous_text_button_faire_une_simulation'),
            '#required' => TRUE,
        ];
        return parent::buildForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();
        if (is_array($values['accueil_visuel'])) {
            foreach ($values['accueil_visuel'] as $value) {
                $file = File::load($value);
                $file->setPermanent();
                $file->save();
                $file_usage = \Drupal::service('file.usage');
                $list = $file_usage->listUsage($file);
                if (empty($list)) {
                    $file_usage->add($file, 'general_config', 'node', 0);
                }
            }
        }
        if (is_array($values['accueil_visuel_mobile'])) {
            foreach ($values['accueil_visuel_mobile'] as $value) {
                $file = File::load($value);
                $file->setPermanent();
                $file->save();
                $file_usage = \Drupal::service('file.usage');
                $list = $file_usage->listUsage($file);
                if (empty($list)) {
                    $file_usage->add($file, 'general_config', 'node', 0);
                }
            }
        }
        if (is_array($values['site_logo'])) {
            foreach ($values['site_logo'] as $value) {
                $file = File::load($value);
                $file->setPermanent();
                $file->save();
                $file_usage = \Drupal::service('file.usage');
                $list = $file_usage->listUsage($file);
                if (empty($list)) {
                    $file_usage->add($file, 'general_config', 'node', 0);
                }
            }
        }
        if (is_array($values['footer_site_logo'])) {
            foreach ($values['footer_site_logo'] as $value) {
                $file = File::load($value);
                $file->setPermanent();
                $file->save();
                $file_usage = \Drupal::service('file.usage');
                $list = $file_usage->listUsage($file);
                if (empty($list)) {
                    $file_usage->add($file, 'general_config', 'node', 0);
                }
            }
        }
        parent::validateForm($form, $form_state);
        if (!$form_state->hasAnyErrors()) {
            drupal_flush_all_caches(); // clear all cache
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        $values = $form_state->getValues();


        $this->config('general_config.config')
                ->set('site_logo', $values['site_logo'])
                ->set('site_signature', $values['site_signature'])
                ->set('titre_espace_personel', $values['titre_espace_personel'])
                ->set('lien_espace_personel', $values['lien_espace_personel'])
                ->set('footer_site_logo', $values['footer_site_logo'])
                ->set('footer_site_signature', $values['footer_site_signature'])
                ->set('accueil_visuel', $values['accueil_visuel'])
                ->set('accueil_visuel_mobile', $values['accueil_visuel_mobile'])
                ->set('accueil_message_1', $values['accueil_message_1'])
                ->set('accueil_message_2', $values['accueil_message_2'])
                ->set('text_button_faire_une_simulation', $values['text_button_faire_une_simulation'])
                ->set('sous_text_button_faire_une_simulation', $values['sous_text_button_faire_une_simulation'])
                ->save();
        parent::submitForm($form, $form_state);
    }

}
