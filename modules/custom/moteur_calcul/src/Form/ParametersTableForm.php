<?php

namespace Drupal\moteur_calcul\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal;

/**
 * Class ParametersTableForm.
 */
class ParametersTableForm extends ConfigFormBase {

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'moteur_calcul.parameterstable',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'parameters_table_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('moteur_calcul.parameterstable');
        $form = parent::buildForm($form, $form_state);
        $form['#theme'] = 'moteur_calcul_parameters_table_form';
        $form['#attributes']['novalidate'] = 'novalidate';
        $form['parameterstableform'] = array(
            '#type' => 'vertical_tabs',
        );
        /* ============ Général Simulateur ============ */
        $form['simulateur'] = array(
            '#title' => $this->t('Simulateur info'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'parameterstableform'
        );
        /* ============ Régles de validation ============ */
        $form['message_erreurs'] = array(
            '#title' => $this->t('Régles de validation'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'parameterstableform'
        );
        /* ============ Général setion ============ */
        $form['general'] = array(
            '#title' => $this->t('Moteur du calcul : paramétrage'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'parameterstableform'
        );
        $form['general']['montant_pass'] = [
            '#type' => 'number',
            '#title' => $this->t('Montant du PASS (MP)'),
            '#default_value' => $config->get('montant_pass'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => '€',
            '#step' => 0.01,
        ];
        /* ============= Tranche de revenu ================= */
        $form['general']['tranche_revenu'] = array(
            '#title' => $this->t('Tranche de revenu'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#required' => TRUE,
            '#default_value' => '0'
        );
        /* === Tranche de revenu 1 Label === */
        $form['general']['tranche_revenu']['tranche_1_label'] = [
            '#type' => 'item',
            '#title' => t('Tranche de revenu 1'),
        ];
        /* Tranche de revenu 1 (minimum) */
        $form['general']['tranche_revenu']['tranche_1_min'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_1_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Minimum',
            '#field_suffix' => 'PASS',
            '#step' => 0.01,
        ];

        /* Tranche de revenu 1 (maximum) */
        $form['general']['tranche_revenu']['tranche_1_max'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_1_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Maximum',
            '#field_suffix' => 'PASS',
            '#step' => 0.01,
        ];
        /* === Tranche de revenu 2 Label === */
        $form['general']['tranche_revenu']['tranche_2_label'] = [
            '#type' => 'item',
            '#title' => t('Tranche de revenu 2'),
        ];
        /* Tranche de revenu 2 (minimum) */
        $form['general']['tranche_revenu']['tranche_2_min'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_2_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_prefix' => 'Minimum',
            '#field_suffix' => 'PASS',
        ];

        /* Tranche de revenu 2 (maximum) */
        $form['general']['tranche_revenu']['tranche_2_max'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_2_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_prefix' => 'Maximum',
            '#field_suffix' => 'PASS',
        ];
        /* === Tranche de revenu 3 Label === */
        $form['general']['tranche_revenu']['tranche_3_label'] = [
            '#type' => 'item',
            '#title' => t('Tranche de revenu 3'),
        ];
        /* Tranche de revenu 2 (minimum) */
        $form['general']['tranche_revenu']['tranche_3_min'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_3_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_prefix' => 'Minimum',
            '#field_suffix' => 'PASS',
        ];

        /* Tranche de revenu 2 (maximum) */
        $form['general']['tranche_revenu']['tranche_3_max'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_3_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_prefix' => 'Maximum',
            '#field_suffix' => '€',
        ];
        /* Taux de charges sociales */
        $form['general']['taux_charges_sociales'] = array(
            '#title' => $this->t('Taux de charges sociales'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#required' => TRUE,
            '#default_value' => '0'
        );
        $form['general']['taux_charges_sociales']['taux_charges_sociales_tranche_1'] = [
            '#type' => 'number',
            '#title' => $this->t('Tranche 1'),
            '#default_value' => $config->get('taux_charges_sociales_tranche_1'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '%',
        ];
        $form['general']['taux_charges_sociales']['taux_charges_sociales_tranche_2'] = [
            '#type' => 'number',
            '#title' => $this->t('Tranche 2'),
            '#default_value' => $config->get('taux_charges_sociales_tranche_2'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '%',
        ];
        $form['general']['taux_charges_sociales']['taux_charges_sociales_tranche_3'] = [
            '#type' => 'number',
            '#title' => $this->t('Tranche 3'),
            '#default_value' => $config->get('taux_charges_sociales_tranche_3'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '%',
        ];
        /* Nombre de jours travaillés */
        $form['general']['nombre_de_jours_travailles'] = array(
            '#title' => $this->t('Nombre de jours travaillés'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#required' => TRUE,
            '#default_value' => '0'
        );
        /* Nombre de jours travaillés Pôle Emploi */
        $form['general']['nombre_de_jours_travailles']['nombre_jours_travailles_pole_emploi'] = [
            '#type' => 'number',
            '#title' => $this->t('Nombre de jours travaillés Pôle Emploi'),
            '#default_value' => $config->get('nombre_jours_travailles_pole_emploi'),
            '#required' => TRUE,
            '#min' => 1,
            '#step' => 0.01,
            '#field_suffix' => 'Jour(s)',
        ];
        $form['general']['nombre_de_jours_travailles']['coef_passage_annee_pole_emploi'] = [
            '#type' => 'number',
            '#title' => $this->t('Coef passage année Pôle Emploi'),
            '#default_value' => $config->get('coef_passage_annee_pole_emploi'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
        ];
        /* Nombre de jours travaillés MADP */
        $form['general']['nombre_de_jours_travailles']['nombre_jours_travailles_madp'] = [
            '#type' => 'number',
            '#title' => $this->t('Nombre de jours travaillés MADP'),
            '#default_value' => $config->get('nombre_jours_travailles_madp'),
            '#required' => TRUE,
            '#min' => 1,
            '#step' => 0.01,
            '#field_suffix' => 'Jour(s)',
        ];
        /* Allocation Pôle Emploi */
        $form['general']['allocation_ple'] = [
            '#type' => 'details',
            '#title' => t('Allocation Pôle Emploi'),
            '#collapsible' => TRUE,
            '#required' => TRUE,
            '#default_value' => '0'
        ];
        /* ====== Allocation Pôle Emploi ====== */
        /* % inf ARE */
        $form['general']['allocation_ple']['allocation_ple_inf_are'] = [
            '#type' => 'number',
            '#title' => $this->t('% inf ARE'),
            '#default_value' => $config->get('allocation_ple_inf_are'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '%',
        ];
        /* % inf ARE */
        $form['general']['allocation_ple']['allocation_ple_partie_fixe'] = [
            '#type' => 'number',
            '#title' => $this->t('Partie fixe'),
            '#default_value' => $config->get('allocation_ple_partie_fixe'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '€',
        ];
        /* % sup ARE */
        $form['general']['allocation_ple']['allocation_ple_sup_are'] = [
            '#type' => 'number',
            '#title' => $this->t('% sup ARE'),
            '#default_value' => $config->get('allocation_ple_sup_are'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '%',
        ];
        /* ARE min */
        $form['general']['allocation_ple']['allocation_ple_are_min'] = [
            '#type' => 'number',
            '#title' => $this->t('ARE min'),
            '#default_value' => $config->get('allocation_ple_are_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '€',
        ];
        /* % sup SJR */
        $form['general']['allocation_ple']['allocation_ple_sup_sjr'] = [
            '#type' => 'number',
            '#title' => $this->t('% sup SJR'),
            '#default_value' => $config->get('allocation_ple_sup_sjr'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '% du salaire',
        ];

        /* Plafond ARE  */
        $form['general']['allocation_ple']['allocation_ple_plafond_are'] = [
            '#type' => 'number',
            '#title' => $this->t('Plafond ARE'),
            '#default_value' => $config->get('allocation_ple_plafond_are'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '€',
        ];
        /* ARE max */
        $form['general']['allocation_ple']['allocation_ple_are_max'] = [
            '#type' => 'number',
            '#title' => $this->t('ARE max'),
            '#default_value' => $config->get('allocation_ple_are_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.01,
            '#field_suffix' => '€',
        ];
        /* Taux de charges sociales avec frais Pôle Emploi */
        $form['general']['taux_charges_sociales_avec_frais_ple'] = [
            '#type' => 'number',
            '#title' => $this->t('Taux de charges sociales avec frais Pôle Emploi'),
            '#default_value' => $config->get('taux_charges_sociales_avec_frais_ple'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.00001,
            '#field_suffix' => '%',
        ];
        /* Taux de retraite */
        $form['general']['taux_de_retraite'] = [
            '#type' => 'number',
            '#title' => $this->t('Taux de retraite'),
            '#default_value' => $config->get('taux_de_retraite'),
            '#required' => TRUE,
            '#min' => 0,
            '#step' => 0.00001,
            '#field_suffix' => '%',
        ];
        /* Plafond de salaire indemnisé par MADP   */
        $form['general']['plafond_salaire_indeminise_madp'] = [
            '#type' => 'number',
            '#title' => $this->t('Plafond de salaire indemnisé par MADP'),
            '#default_value' => $config->get('plafond_salaire_indeminise_madp'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => 'PASS',
        ];
        /* Plafond quotidien indemnités MADP   */
        $form['general']['plafond_quotidien_indeminise_madp'] = [
            '#type' => 'number',
            '#title' => $this->t('Plafond quotidien indemnités MADP'),
            '#default_value' => $config->get('plafond_quotidien_indeminise_madp'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => '€',
        ];

        /* ============= Partie 1 text info ================= */
        $form['simulateur']['partie1'] = array(
            '#title' => $this->t('Partie 1'),
            '#type' => 'details',
            '#collapsible' => false,
            '#required' => TRUE,
            '#default_value' => '0',
            '#open' => TRUE,
        );
        /* Bloc encart informatif */
        $form['simulateur']['partie1']['encart_informatif'] = [
            '#type' => 'fieldset',
            '#title' => t('Encart informatif'),
            '#required' => TRUE,
        ];
        $form['simulateur']['partie1']['encart_informatif']['simulateur_encart_informatif_titre'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Titre'),
            '#default_value' => $config->get('simulateur_encart_informatif_titre'),
            '#required' => TRUE,
        ];
        $form['simulateur']['partie1']['encart_informatif']['simulateur_encart_informatif_text'] = [
            '#type' => 'text_format',
            '#title' => $this->t('Texte'),
            '#default_value' => $config->get('simulateur_encart_informatif_text')['value'],
            '#format' => (empty($config->get('simulateur_encart_informatif_text')['format']) ? 'basic_html' : $config->get('simulateur_encart_informatif_text')['format']),
            '#required' => TRUE,
        ];
        /* */
        $form['simulateur']['partie1']['field_salaire_annuel_brut']['field_salaire_annuel_brut_info'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Salaire annuel brut (Info bulle)'),
            '#default_value' => $config->get('field_salaire_annuel_brut_info'),
            '#required' => TRUE,
        ];
        /* ============= Partie 2 text info ================= */
        $form['simulateur']['partie2'] = array(
            '#title' => $this->t('Partie 2'),
            '#type' => 'details',
            '#collapsible' => false,
            '#required' => TRUE,
            '#default_value' => '0',
            '#open' => TRUE,
        );
        $form['simulateur']['partie2']['montant_mensuel_indemnise_info'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Montant mensuel indemnisé (Info bulle)'),
            '#default_value' => $config->get('montant_mensuel_indemnise_info'),
            '#required' => TRUE,
        ];
        $form['simulateur']['partie2']['duree_indemnisation_montant_cotisations_info'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Durée d\'indemnisation - montant mensuel des cotisations (Info bulle)'),
            '#default_value' => $config->get('duree_indemnisation_montant_cotisations_info'),
            '#required' => TRUE,
        ];

        $form['message_erreurs']['partie1']['limit_age_inferieur'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('Si âge inférieur à'),
            '#default_value' => $config->get('limit_age_inferieur'),
            '#required' => TRUE,
            '#field_suffix' => 'ans',
        ];
        $form['message_erreurs']['partie1']['message_age_inferieur'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Age inférieur (message d\'erreur)'),
            '#default_value' => $config->get('message_age_inferieur'),
            '#required' => TRUE,
        ];
        $form['message_erreurs']['partie1']['limit_age_superieur'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('Si âge supérieur à'),
            '#default_value' => $config->get('limit_age_superieur'),
            '#required' => TRUE,
            '#field_suffix' => 'ans',
        ];
        $form['message_erreurs']['partie1']['message_age_superieur'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Age supérieur (message d\'erreur)'),
            '#default_value' => $config->get('message_age_superieur'),
            '#required' => TRUE,
        ];
        $form['message_erreurs']['partie1']['limit_salaire_inferieur'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('Salaire inférieur à'),
            '#default_value' => $config->get('limit_salaire_inferieur'),
            '#required' => TRUE,
            '#field_suffix' => '€',
        ];
        $form['message_erreurs']['partie1']['message_salaire_inferieur'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Salaire inférieur (message d\'erreur)'),
            '#default_value' => $config->get('message_salaire_inferieur'),
            '#required' => TRUE,
        ];
        $form['message_erreurs']['partie1']['limit_salaire_superieur'] = [
            '#type' => 'number',
            '#min' => 0,
            '#title' => $this->t('Si salaire supérieur à'),
            '#default_value' => $config->get('limit_salaire_superieur'),
            '#required' => TRUE,
            '#field_suffix' => 'PASS',
        ];
        $form['message_erreurs']['partie1']['message_salaire_superieur'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Salaire supérieur (message d\'erreur)'),
            '#default_value' => $config->get('message_salaire_superieur'),
            '#required' => TRUE,
        ];
        /* ============= Partie 3 text info ================= */
        $form['simulateur']['partie3'] = array(
            '#title' => $this->t('Partie 3'),
            '#type' => 'details',
            '#collapsible' => false,
            '#required' => TRUE,
            '#default_value' => '0',
            '#open' => TRUE,
        );
        /* Indemnité mensuelle minimum MADP   */
        $form['simulateur']['partie3']['indemnite_mensuelle_minimum_madp'] = [
            '#type' => 'number',
            '#title' => $this->t('Indemnité mensuelle minimum MADP'),
            '#default_value' => $config->get('indemnite_mensuelle_minimum_madp'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => '€',
        ];
        /* Hauteur des pas   */
        $form['simulateur']['partie3']['hauteur_des_pas'] = [
            '#type' => 'number',
            '#title' => $this->t('Hauteur des pas'),
            '#description' => $this->t('Le curseur variera en fonction de ces pas'),
            '#default_value' => $config->get('hauteur_des_pas'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => '€',
        ];
        /* Durée d’indemnisation minimum   */
        $form['simulateur']['partie3']['duree_indemnisation_minimum'] = [
            '#type' => 'number',
            '#title' => $this->t('Durée d’indemnisation minimum'),
            '#default_value' => $config->get('duree_indemnisation_minimum'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => 'mois',
        ];
        /* Durée d’indemnisation maximum   */
        $form['simulateur']['partie3']['duree_indemnisation_maximum'] = [
            '#type' => 'number',
            '#title' => $this->t('Durée d’indemnisation maximum'),
            '#default_value' => $config->get('duree_indemnisation_maximum'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => 'mois',
        ];
        /* Durée d’indemnisation par default   */
        $form['simulateur']['partie3']['duree_indemnisation_default'] = [
            '#type' => 'number',
            '#title' => $this->t('Durée d’indemnisation par default'),
            '#default_value' => $config->get('duree_indemnisation_default'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => 'mois',
        ];

        /* ============ Taux cotisation ============ */
        $form['taux_cotisation'] = array(
            '#title' => $this->t('Taux cotisation'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#group' => 'parameterstableform',
            '#required' => TRUE,
        );
        /* Taux de cotisation minimum pour la tranche d'indemnisation 1 et le tranche d'age 1 */
        $form['taux_cotisation']['taux_cotisation_1'] = [
            '#type' => 'number',
            '#title' => 'Taux de cotisation minimum pour la tranche d\'indemnisation 1 et la tranche d\'age 1',
            '#default_value' => $config->get('taux_cotisation_1'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_suffix' => '%',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        // tranche d'indemnisation
        //----- indemnisation min
        //----- indemnisation max
        //----- indemnisation augmentation
        $form['taux_cotisation']['tranche_indemnisation'] = array(
            '#title' => $this->t('tranche d\'indemnisation'),
            '#type' => 'details',
            '#collapsible' => TRUE,
            '#required' => TRUE,
            '#default_value' => '0'
        );
        //tranche d'indemnisation 1

        /* === tranche d'indemnisation 1 Label === */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_1_label'] = [
            '#type' => 'item',
            '#title' => t('tranche d\'indemnisation 1'),
        ];
        /* tranche d'indemnisation 1 (minimum) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_1_min'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_1_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Minimum',
            '#field_suffix' => '€',
            '#step' => 0.01,
            '#required' => TRUE,
        ];

        /* tranche d'indemnisation 1 (maximum) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_1_max'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_1_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Maximum',
            '#field_suffix' => '€',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        /* tranche d'indemnisation 1 (augmentation) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_1_augmentation'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_1_augmentation'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Augmentation',
            '#field_suffix' => '%',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        //tranche d'indemnisation 2
        /* === tranche d'indemnisation 2 Label === */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_2_label'] = [
            '#type' => 'item',
            '#title' => t('tranche d\'indemnisation 2'),
        ];
        /* tranche d'indemnisation 2 (minimum) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_2_min'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_2_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Minimum',
            '#field_suffix' => '€',
            '#step' => 0.01,
            '#required' => TRUE,
        ];

        /* tranche d'indemnisation 2 (maximum) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_2_max'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_2_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Maximum',
            '#field_suffix' => '€',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        /* tranche d'indemnisation 2 (augmentation) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_2_augmentation'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_2_augmentation'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Augmentation',
            '#field_suffix' => '%',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        // // tranche d'indemnisation 3
        /* === tranche d'indemnisation 3 Label === */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_3_label'] = [
            '#type' => 'item',
            '#title' => t('tranche d\'indemnisation 3'),
        ];
        /* tranche d'indemnisation 2 (minimum) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_3_min'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_3_min'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Minimum',
            '#field_suffix' => '€',
            '#step' => 0.01,
            '#required' => TRUE,
        ];

        /* tranche d'indemnisation 3 (maximum) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_3_max'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_3_max'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Maximum',
            '#field_suffix' => '€',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        /* tranche d'indemnisation 2 (augmentation) */
        $form['taux_cotisation']['tranche_indemnisation']['tranche_indemnisation_3_augmentation'] = [
            '#type' => 'number',
            '#title' => null,
            '#default_value' => $config->get('tranche_indemnisation_3_augmentation'),
            '#required' => TRUE,
            '#min' => 0,
            '#field_prefix' => 'Augmentation',
            '#field_suffix' => '%',
            '#step' => 0.01,
            '#required' => TRUE,
        ];
        $form['taux_cotisation']['taux_cotisation_params_json'] = [
            '#type' => 'textarea',
            '#title' => $this->t('JSON'),
            '#default_value' => $config->get('taux_cotisation_params_json'),
            '#required' => TRUE,
        ];
        $form['taux_cotisation']['taux_cotisation_params_table'] = [
            '#type' => 'item',
            '#markup' => '<div id="taux_cotisation_params_table"></div>',
        ];
        $form['#attached']['library'][] = 'moteur_calcul/form-config';
        //return parent::buildForm($form, $form_state);
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
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

        Drupal::configFactory()->getEditable('moteur_calcul.parameterstable')
                ->set('montant_pass', $values['montant_pass'])
                ->set('tranche_1_min', $values['tranche_1_min'])
                ->set('tranche_1_max', $values['tranche_1_max'])
                ->set('tranche_2_min', $values['tranche_2_min'])
                ->set('tranche_2_max', $values['tranche_2_max'])
                ->set('tranche_3_min', $values['tranche_3_min'])
                ->set('tranche_3_max', $values['tranche_3_max'])
                ->set('taux_charges_sociales_tranche_1', $values['taux_charges_sociales_tranche_1'])
                ->set('taux_charges_sociales_tranche_2', $values['taux_charges_sociales_tranche_2'])
                ->set('taux_charges_sociales_tranche_3', $values['taux_charges_sociales_tranche_3'])
                ->set('nombre_jours_travailles_pole_emploi', $values['nombre_jours_travailles_pole_emploi'])
                ->set('coef_passage_annee_pole_emploi', $values['coef_passage_annee_pole_emploi'])
                ->set('nombre_jours_travailles_madp', $values['nombre_jours_travailles_madp'])
                ->set('allocation_ple_inf_are', $values['allocation_ple_inf_are'])
                ->set('allocation_ple_partie_fixe', $values['allocation_ple_partie_fixe'])
                ->set('allocation_ple_sup_are', $values['allocation_ple_sup_are'])
                ->set('allocation_ple_are_min', $values['allocation_ple_are_min'])
                ->set('allocation_ple_sup_sjr', $values['allocation_ple_sup_sjr'])
                ->set('allocation_ple_plafond_are', $values['allocation_ple_plafond_are'])
                ->set('allocation_ple_are_max', $values['allocation_ple_are_max'])
                ->set('taux_charges_sociales_avec_frais_ple', $values['taux_charges_sociales_avec_frais_ple'])
                ->set('taux_de_retraite', $values['taux_de_retraite'])
                ->set('plafond_salaire_indeminise_madp', $values['plafond_salaire_indeminise_madp'])
                ->set('plafond_quotidien_indeminise_madp', $values['plafond_quotidien_indeminise_madp'])
                ->set('indemnite_mensuelle_minimum_madp', $values['indemnite_mensuelle_minimum_madp'])
                ->set('hauteur_des_pas', $values['hauteur_des_pas'])
                ->set('duree_indemnisation_minimum', $values['duree_indemnisation_minimum'])
                ->set('duree_indemnisation_maximum', $values['duree_indemnisation_maximum'])
                ->set('duree_indemnisation_default', $values['duree_indemnisation_default'])
                ->set('simulateur_encart_informatif_titre', $values['simulateur_encart_informatif_titre'])
                ->set('simulateur_encart_informatif_text', $values['simulateur_encart_informatif_text'])
                ->set('field_salaire_annuel_brut_info', $values['field_salaire_annuel_brut_info'])
                ->set('montant_mensuel_indemnise_info', $values['montant_mensuel_indemnise_info'])
                ->set('duree_indemnisation_montant_cotisations_info', $values['duree_indemnisation_montant_cotisations_info'])
                ->set('limit_age_inferieur', $values['limit_age_inferieur'])
                ->set('message_age_inferieur', $values['message_age_inferieur'])
                ->set('limit_age_superieur', $values['limit_age_superieur'])
                ->set('message_age_superieur', $values['message_age_superieur'])
                ->set('limit_salaire_inferieur', $values['limit_salaire_inferieur'])
                ->set('message_salaire_inferieur', $values['message_salaire_inferieur'])
                ->set('limit_salaire_superieur', $values['limit_salaire_superieur'])
                ->set('message_salaire_superieur', $values['message_salaire_superieur'])
                ->set('tranche_indemnisation_1_min', $values['tranche_indemnisation_1_min'])
                ->set('tranche_indemnisation_1_max', $values['tranche_indemnisation_1_max'])
                ->set('tranche_indemnisation_1_augmentation', $values['tranche_indemnisation_1_augmentation'])
                ->set('tranche_indemnisation_2_min', $values['tranche_indemnisation_2_min'])
                ->set('tranche_indemnisation_2_max', $values['tranche_indemnisation_2_max'])
                ->set('tranche_indemnisation_2_augmentation', $values['tranche_indemnisation_2_augmentation'])
                ->set('tranche_indemnisation_3_min', $values['tranche_indemnisation_3_min'])
                ->set('tranche_indemnisation_3_max', $values['tranche_indemnisation_3_max'])
                ->set('tranche_indemnisation_3_augmentation', $values['tranche_indemnisation_3_augmentation'])
                ->set('taux_cotisation_1', $values['taux_cotisation_1'])
                ->set('taux_cotisation_params_json', $values['taux_cotisation_params_json'])
                ->save();

        parent::submitForm($form, $form_state);
    }

}
