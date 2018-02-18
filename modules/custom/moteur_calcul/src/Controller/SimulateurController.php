<?php

namespace Drupal\moteur_calcul\Controller;

use Drupal;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class SimulateurController.
 */
class SimulateurController extends ControllerBase {

    private $errors;

    /**
     * Ecran1.
     *
     * @return string
     *   Return Template array.
     */
    public function Ecran1() {
        return [
            '#theme' => 'moteur_calcul_simulateur_page',
            '#params' => [
                'css' => 'default'
            ],
        ];
    }

    /**
     * Ecran2.
     *
     * @return string
     *   Return Template array.
     */
    public function Ecran2($salaire) {
        $moteurCalcul = Drupal::service('moteur_calcul');
        $salaireMensuelNet = round($moteurCalcul->getSalaireMensuelNet($salaire), 2);
        $allocationMensuelNetPoleEmploi = round($moteurCalcul->getPoleEmploiAllocationMensuelNet($salaire), 2);
        $manqueAgangerMensuelNet = max($salaireMensuelNet - $allocationMensuelNetPoleEmploi, 0);
        return new JsonResponse(array(
            'status' => 'success',
            'salaireMensuelNet' => number_format($salaireMensuelNet, 0, " ", " "),
            'allocationMensuelNetPoleEmploi' => number_format($allocationMensuelNetPoleEmploi, 0, " ", " "),
            'manqueGagnerMensuelNet' => number_format($manqueAgangerMensuelNet, 0, " ", " "),
        ));
    }

    /**
     * Ecran3.
     *
     * @return string
     *   Return Template array.
     */
    public function Ecran3($age, $salaire) {

        if (!$this->isValidEcran3($age, $salaire)) {
            return new JsonResponse(array(
                'status' => 'error',
                'errors' => $this->errors
            ));
        }
        $moteurCalcul = Drupal::service('moteur_calcul');

        return new JsonResponse(array(
            'status' => 'success',
            'indeminiteMensuelleMin' => $moteurCalcul->getIndemniteMensuelleMinimumMadp(),
            'plafontIndeminiteMensuelleMax' => $moteurCalcul->getPlafondIndemniteMensuelleMadp_parhauteurDesPas($salaire),
            'indeminiteMensuellePas' => $moteurCalcul->getHauteurDesPas(),
            'dureeIndemnisationMin' => $moteurCalcul->getDureeIndemnisationMin(),
            'dureeIndemnisationMax' => $moteurCalcul->getDureeIndemnisationMax(),
            'dureeIndemnisationDefault' => $moteurCalcul->getDureeIndemnisationDefault(),
        ));
    }

    public function MontantMensuelCotisation($age, $salaire, $montantMensuelIndemnise, $dureeIndemnisation) {

        if (!$this->isValidEcran3($age, $salaire)) {
            return new JsonResponse(array(
                'status' => 'error',
                'errors' => $this->errors
            ));
        }
        $moteurCalcul = Drupal::service('moteur_calcul');
        $tauxCouvertureGlobal = $moteurCalcul->getTauxCouvertureGlobal($salaire, $montantMensuelIndemnise);
        return new JsonResponse(array(
            'status' => 'success',
            'mentantMensuelCotisationDureeMin' => $moteurCalcul->getMontantMensuelCotisation($age, $montantMensuelIndemnise, $moteurCalcul->getDureeIndemnisationMin()),
            'mentantMensuelCotisationDureeMax' => $moteurCalcul->getMontantMensuelCotisation($age, $montantMensuelIndemnise, $moteurCalcul->getDureeIndemnisationMax()),
            'mentantMensuelCotisation' => $moteurCalcul->getMontantMensuelCotisation($age, $montantMensuelIndemnise, $dureeIndemnisation),
            'tauxCouverturePoleEmploi' => $moteurCalcul->getTauxCouvertureMensuellePoleEmploi($salaire),
            'tauxCouvertureMadp' => $moteurCalcul->getTauxCouvertureMensuelleMadp($salaire, $montantMensuelIndemnise),
            'tauxCouvertureglobal' => $tauxCouvertureGlobal,
            'tauxCouvertureNonCouvert' => round((100 - $tauxCouvertureGlobal), 2),
        ));
    }

    protected function isValidEcran3($age, $salaire) {
        $errors = [];
        $limit_age_inferieur = \Drupal::config("moteur_calcul.parameterstable")->get('limit_age_inferieur');
        $limit_age_superieur = \Drupal::config("moteur_calcul.parameterstable")->get('limit_age_superieur');
        $limit_salaire_inferieur = \Drupal::config("moteur_calcul.parameterstable")->get('limit_salaire_inferieur');
        $limit_salaire_superieur = \Drupal::config("moteur_calcul.parameterstable")->get('limit_salaire_superieur');
        $montant_pass = \Drupal::config("moteur_calcul.parameterstable")->get('montant_pass');
        if (!is_numeric($age)) {
            $errors['messages'][] = "Le champ Age n'est pas au bon format.";
        }
        if (!is_numeric($salaire)) {
            $errors['messages'][] = "Le champ Salaire annuel brut n'est pas au bon format.";
        }
        if ($age < $limit_age_inferieur) {
            $errors['messages'][] = \Drupal::config("moteur_calcul.parameterstable")->get('message_age_inferieur');
        }
        if ($age > $limit_age_superieur) {
            $errors['messages'][] = \Drupal::config("moteur_calcul.parameterstable")->get('message_age_superieur');
        }
        if ($salaire < $limit_salaire_inferieur) {
            $errors['messages'][] = \Drupal::config("moteur_calcul.parameterstable")->get('message_salaire_inferieur');
        }
        if ($salaire > ($limit_salaire_superieur * $montant_pass)) {
            $errors['messages'][] = \Drupal::config("moteur_calcul.parameterstable")->get('message_salaire_superieur');
        }

        if (count($errors) === 0) {
            return true;
        }
        $this->errors = $errors;
        return false;
    }

    public function cotisationTable() {
        $moteurCalcul = Drupal::service('moteur_calcul');
        $renderable = [
            '#theme' => 'moteur_calcul_cotisation_table',
            '#params' => [
                'tauxCotisationParams' => $moteurCalcul->getTauxCotisationParamsJson()
            ],
        ];
        $rendered = drupal_render($renderable);
        return new JsonResponse(array(
            'status' => 'success',
            'content' => $rendered,
        ));
    }

    /**
     * integration.
     *
     * @return string
     *   Return Template array.
     */
    public function integration(Request $request, $theme) {
        $renderable = [
            '#theme' => 'moteur_calcul_simulateur_form_integration',
            '#params' => [
                'theme' => $theme
            ],
        ];
        echo drupal_render($renderable);
        exit;
    }

}
