<?php

namespace Drupal\moteur_calcul\service;

use Drupal;

/**
 * MoteurCalcul_Class c'est une service du module moteur_calcul
 */
class MoteurCalcul {

    private $config;

    /**
     * construct class __construct
     */
    function __construct() {
        $config = Drupal::config('moteur_calcul.parameterstable');
        $this->config = $config;
    }

    /**
     * Calcul du salaire mensuel net
     * @param int $salaireAnnuelBrute
     * @return int
     */
    public function getSalaireMensuelNet($salaireAnnuelBrute) {
        $salaireAnnuelNet = $this->getSalaireAnnuelNet($salaireAnnuelBrute);
        return $salaireAnnuelNet / 12;
    }

    /**
     * CALCUL SALAIRE ANNUEL NET
     * @param int $salaireAnnuelBrute
     * @return int
     */
    public function getSalaireAnnuelNet($salaireAnnuelBrute) {
        $chargesSociales = $this->getChargesSociales($salaireAnnuelBrute);
        return $salaireAnnuelBrute - $chargesSociales;
    }

    /**
     * CALCUL DES CHARGES SOCIALES
     * @param int $salaireAnnuelBrute
     * @return int
     */
    public function getChargesSociales($salaireAnnuelBrute) {
        $mp = $this->getMontantPass();
        $tc_1 = (floatval($this->config->get('taux_charges_sociales_tranche_1')) / 100);
        $tc_2 = (floatval($this->config->get('taux_charges_sociales_tranche_2')) / 100);
        $tc_3 = (floatval($this->config->get('taux_charges_sociales_tranche_3')) / 100);
        $maxPASS_1 = floatval($this->config->get('tranche_1_max')) * $mp;
        $maxPASS_2 = floatval($this->config->get('tranche_2_max')) * $mp;
        $minPASS_3 = floatval($this->config->get('tranche_3_min')) * $mp;
        $minPASS_2 = floatval($this->config->get('tranche_2_min')) * $mp;
        $chargesSociales = ($tc_1 * min($salaireAnnuelBrute, $maxPASS_1)) +
                ($tc_2 * min(max($salaireAnnuelBrute - $maxPASS_1, 0), ($maxPASS_2 - $minPASS_2))) +
                ($tc_3 * max(0, $salaireAnnuelBrute - $minPASS_3));
        return $chargesSociales;
    }

    /**
     * CALCUL SALAIRE JOURNALIER DE REFERENCE (SJR)
     * @param int $salaireAnnuelBrute
     * @return int SJR
     */
    public function getSJR($salaireAnnuelBrute) {
        $nombreJoursTravailles = $this->getNombreJoursTravaillesPoleEmploi();
        return $salaireAnnuelBrute / $nombreJoursTravailles;
    }

    /**
     * CALCUL ALLOCATION JOURNALIERE BRUTE POLE EMPLOI
     * @param int $salaireAnnuelBrute
     * @return int allocation journalier brute
     */
    public function getPoleEmploiAllocationJournalierBrute($salaireAnnuelBrute) {
        $plafond = $this->getMontantPass() * intval($this->getMontantMensuelIndemniseTranche2_max());
        $allocation_ple_inf_are_percent = $this->getAllocationPoleEmploiInfAre() / 100;
        $nombre_jours_travailles_pole_emploi = $this->getNombreJoursTravaillesPoleEmploi();
        $coef_passage_annee_pole_emploi = $this->getCoefPassageAnneePoleEmploi();
        $allocation_ple_partie_fixe = $this->getAllocationPoleEmploiPartieFixe();
        $allocation_ple_sup_are_percent = $this->getAllocationPoleEmploiSupAre() / 100;
        $allocation_ple_sup_sjr_percent = $this->getAllocationPoleEmploiSupSjr() / 100;
        $allocation_ple_are_min = $this->getAllocationPoleEmploiAreMin();
        $allocation_ple_plafond_are = $this->getAllocationPoleEmploiPlafondAre();
        $valeur = round(
                min(
                        min(
                                max(
                                        max(
                                                $allocation_ple_inf_are_percent * min($salaireAnnuelBrute, $plafond) / (($nombre_jours_travailles_pole_emploi * $coef_passage_annee_pole_emploi)) + $allocation_ple_partie_fixe
                                                , $allocation_ple_sup_are_percent * min($salaireAnnuelBrute, $plafond) / (($nombre_jours_travailles_pole_emploi * $coef_passage_annee_pole_emploi))
                                        )
                                        , $allocation_ple_are_min
                                )
                                , $allocation_ple_sup_sjr_percent * min($salaireAnnuelBrute, $plafond) / (($nombre_jours_travailles_pole_emploi * $coef_passage_annee_pole_emploi))
                        )
                        , $allocation_ple_plafond_are
                )
                , 2);
        return ($valeur > 0) ? $valeur : 0;
    }

    /**
     * Allocation mensuelle nette par Pôle Emploi
     * @param int $salaireAnnuelBrute
     * @return int Allocation mensuelle nette
     */
    public function getPoleEmploiAllocationMensuelNet($salaireAnnuelBrute) {
        $nombre_jours_travailles_pole_emploi = $this->getNombreJoursTravaillesPoleEmploi();
        $coef_passage_annee_pole_emploi = $this->getCoefPassageAnneePoleEmploi();
        $allocationAnnuelJournalierNette = $this->getPoleEmploiAllocationJournalierNette($salaireAnnuelBrute);
        return $allocationAnnuelJournalierNette * (($nombre_jours_travailles_pole_emploi * $coef_passage_annee_pole_emploi) / 12);
    }

    /**
     * CALCUL MANQUE A GAGNER MENSUEL NET
     * @param int $salaireAnnuelBrute
     * @return int Manque à gagner mensuel nette
     */
    public function getManqueAGangerMensuelNet($salaireAnnuelBrute) {
        $salaireMensuelNet = $this->getSalaireMensuelNet($salaireAnnuelBrute);
        $AllocationMensuelleNetPoleEmploi = $this->getPoleEmploiAllocationMensuelNet($salaireAnnuelBrute);
        $nombreJoursTravaillesPle = $this->getNombreJoursTravaillesPoleEmploi();
        $coefPassagePle = $this->getCoefPassageAnneePoleEmploi();
        return ($salaireMensuelNet - $AllocationMensuelleNetPoleEmploi) * (($nombreJoursTravaillesPle * $coefPassagePle) / 12);
    }

    /**
     * CALCUL Manque à gagner quotidien après charges sociales
     * @param int $salaireAnnuelBrute
     * @return int Manque à gagner quotidien après charges sociales
     */
    public function getManqueAGangerQuotidienApresChargesSociales($salaireAnnuelBrute) {
        $allocationPoleEmploiJournalierNette = $this->getPoleEmploiAllocationJournalierNette($salaireAnnuelBrute);
        $salaireAnnuelNet = $this->getSalaireAnnuelNet($salaireAnnuelBrute);
        $nombre_jours_travailles_pole_emploi = $this->getNombreJoursTravaillesPoleEmploi();
        $coef_passage_annee_pole_emploi = $this->getCoefPassageAnneePoleEmploi();
        return ($salaireAnnuelNet / ($nombre_jours_travailles_pole_emploi * $coef_passage_annee_pole_emploi)) - $allocationPoleEmploiJournalierNette;
    }

    /**
     * CALCUL Plafond indemnité mensuelle MADP
     * @param int $salaireAnnuelBrute
     * @return int
     */
    public function getPlafondIndemniteMensuelleMadp($salaireAnnuelBrute) {
        $plafondQuotidienIndeminiseMadp = $this->getPlafondIndemniteQuotidienMadp($salaireAnnuelBrute);
        $nombre_jours_travailles_madp = $this->getNombreJoursTravaillesMadp();
        return $plafondQuotidienIndeminiseMadp * ($nombre_jours_travailles_madp / 12);
    }

    /**
     * CALCUL Plafond quotidien indemnisation quotidien MADP
     * @param int $salaireAnnuelBrute
     * @return int
     */
    public function getPlafondIndemniteQuotidienMadp($salaireAnnuelBrute) {
        $manqueAGangerQuotidienApresChargesSociales = $this->getManqueAGangerQuotidienApresChargesSociales($salaireAnnuelBrute);
        return min($this->getPlafondQuotidienIndeminiseMadp(), $manqueAGangerQuotidienApresChargesSociales);
    }

    /**
     * CALCUL Plafond quotidien indemnisation mensuelle MADP par pas
     * @param int $salaireAnnuelBrute
     * @return int
     */
    public function getPlafondIndemniteMensuelleMadp_parhauteurDesPas($salaireAnnuelBrute) {
        $valeur1 = $this->getPlafondIndemniteMensuelleMadp($salaireAnnuelBrute);
        $valeur2 = $this->roundDownToAny($valeur1, $this->getHauteurDesPas());
        return min($valeur2, $this->getMontantMensuelIndemniseTranche3_max());
    }

    /**
     * CALCUL ALLOCATION JOURNALIERE NETTE POLE EMPLOI
     * @param int $salaireAnnuelBrute
     * @return int allocation journalier nette
     */
    public function getPoleEmploiAllocationJournalierNette($salaireAnnuelBrute) {
        $valeur = 0;
        $plafond = $this->getMontantPass() * intval($this->getMontantMensuelIndemniseTranche2_max());
        $allocationJournalierBrute = $this->getPoleEmploiAllocationJournalierBrute($salaireAnnuelBrute);
        $allocation_ple_are_min = $this->getAllocationPoleEmploiAreMin();
        $allocation_ple_are_man = $this->getAllocationPoleEmploiAreMax();
        $nombre_jours_travailles_pole_emploi = $this->getNombreJoursTravaillesPoleEmploi();
        $coef_passage_annee_pole_emploi = $this->getCoefPassageAnneePoleEmploi();
        $taux_de_retraite_percent = $this->getTauxDeRetraite() / 100;
        $taux_charges_sociales_percent = $this->getTauxChargesSocialesAvecFraisPle() / 100;
        if ($allocationJournalierBrute < $allocation_ple_are_min) {
            $valeur = $allocationJournalierBrute;
        } elseif ($allocationJournalierBrute < $allocation_ple_are_man) {
            $valeur = $allocationJournalierBrute - $taux_de_retraite_percent * (min($salaireAnnuelBrute, $plafond) / (min($nombre_jours_travailles_pole_emploi, 261) * $coef_passage_annee_pole_emploi));
        } else {
            $valeur = $allocationJournalierBrute * (1 - $taux_charges_sociales_percent) - $taux_de_retraite_percent * (min($salaireAnnuelBrute, $plafond) / ( $nombre_jours_travailles_pole_emploi * $coef_passage_annee_pole_emploi));
        }
        return ($valeur > 0) ? $valeur : 0;
    }

    /**
     * CALCUL MONTANT MENSUEL DE COTISATION
     * @param int $age
     * @param int $montantMensuelIndemnise
     * @param int $dureeIndemnisation
     * @return float
     */
    public function getMontantMensuelCotisation($age, $montantMensuelIndemnise, $dureeIndemnisation) {
        $tauxCotisation = $this->getTauxCotisation($age, $montantMensuelIndemnise);
        if (is_null($tauxCotisation)) {
            return null;
        }
        $result = round(($montantMensuelIndemnise * ($tauxCotisation / 100) * $dureeIndemnisation) / 12, 2);
        return $result;
    }

    /**
     * CALCUL Taux de cotisation
     * @param int $age
     * @param int $montantMensuelIndemnise
     * @return float
     */
    public function getTauxCotisation($age, $montantMensuelIndemnise) {
        $tauxCotisationParams = $this->getTauxCotisationParamsJson();
        foreach ($tauxCotisationParams as $tauxCotisationParam) {
            $ageRange = explode(";", $tauxCotisationParam->age_range);
            $age_min = intval($ageRange[0]);
            $age_max = intval($ageRange[1]);
            if (($age_min <= $age) && ($age <= $age_max)) {
                return $this->getTauxCotisationParTrancheMontantMensuelIndemnise($tauxCotisationParam, $montantMensuelIndemnise);
            }
        }
        return null;
    }

    /**
     * CALCUL Le taux de cotisation par tranche (MontantMensuelIndemnise)
     * @param object $tauxCotisationParam
     * @param int $montantMensuelIndemnise
     * @return float
     */
    public function getTauxCotisationParTrancheMontantMensuelIndemnise($tauxCotisationParam, $montantMensuelIndemnise) {
        $trancheMontantMensuelIndemnise = 'taux_tr' . $this->getTrancheMontantMensuelIndemnise($montantMensuelIndemnise);
        if (isset($tauxCotisationParam->$trancheMontantMensuelIndemnise)) {
            return $tauxCotisationParam->$trancheMontantMensuelIndemnise;
        }
        return null;
    }

    /**
     * getTrancheMontantMensuelIndemnise
     * @param int $montantMensuelIndemnise
     * @return int
     */
    public function getTrancheMontantMensuelIndemnise($montantMensuelIndemnise) {
        if (($this->getMontantMensuelIndemniseTranche1_min() <= $montantMensuelIndemnise) && ($montantMensuelIndemnise <= $this->getMontantMensuelIndemniseTranche1_max())) {
            return 1;
        }
        if (($this->getMontantMensuelIndemniseTranche2_min() <= $montantMensuelIndemnise) && ($montantMensuelIndemnise <= $this->getMontantMensuelIndemniseTranche2_max())) {
            return 2;
        }
        if (($this->getMontantMensuelIndemniseTranche3_min() <= $montantMensuelIndemnise) && ($montantMensuelIndemnise <= $this->getMontantMensuelIndemniseTranche3_max())) {
            return 3;
        }
    }

    /**
     * CALCUL Taux de couverture que représente l’allocation mensuelle nette Pôle Emploi
     * @param int $salaireAnnuelBrute
     * @return int % du salaire mensuel net couvert par l’allocation mensuelle nette Pôle Emploi
     */
    function getTauxCouvertureMensuellePoleEmploi($salaireAnnuelBrute) {
        return round(($this->getPoleEmploiAllocationMensuelNet($salaireAnnuelBrute) * 100) / $this->getSalaireMensuelNet($salaireAnnuelBrute), 2);
    }

    /**
     * CALCUL Taux de couverture que représente l’indemnisation mensuelle MADP
     * @param int $salaireAnnuelBrute
     * @return int % du salaire mensuel net couvert par l’indemnisation mensuelle MADP
     */
    public function getTauxCouvertureMensuelleMadp($salaireAnnuelBrute, $montantMensuelIndemnise) {
        return round(($montantMensuelIndemnise * 100) / $this->getSalaireMensuelNet($salaireAnnuelBrute), 2);
    }

    /**
     * CALCUL Taux de couverture global
     * @param int $salaireAnnuelBrute
     * @return int % du salaire mensuel net non couvet
     */
    public function getTauxCouvertureGlobal($salaireAnnuelBrute, $montantMensuelIndemnise) {
        return round((($montantMensuelIndemnise + $this->getPoleEmploiAllocationMensuelNet($salaireAnnuelBrute)) * 100) / $this->getSalaireMensuelNet($salaireAnnuelBrute), 2);
    }

    /**
     * getMontantPass (paramétrer BIO)
     * @return int
     */
    public function getMontantPass() {
        return intval($this->config->get('montant_pass'));
    }

    /**
     * getNombreJoursTravaillesMadp (paramétrer BIO)
     * @return int
     */
    public function getNombreJoursTravaillesMadp() {
        return intval($this->config->get('nombre_jours_travailles_madp'));
    }

    /**
     * getNombreJoursTravaillesMadp (paramétrer BIO)
     * @return int
     */
    public function getNombreJoursTravaillesPoleEmploi() {
        return intval($this->config->get('nombre_jours_travailles_pole_emploi'));
    }

    /**
     * getCoefPassageAnneePoleEmploi (paramétrer BIO)
     * @return float
     */
    public function getCoefPassageAnneePoleEmploi() {
        return floatval($this->config->get('coef_passage_annee_pole_emploi'));
    }

    /**
     * getAllocationPoleEmploiInfAre (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiInfAre() {
        return floatval($this->config->get('allocation_ple_inf_are'));
    }

    /**
     * getAllocationPoleEmploiPartieFixe (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiPartieFixe() {
        return floatval($this->config->get('allocation_ple_partie_fixe'));
    }

    /**
     * getAllocationPoleEmploiSupAre (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiSupAre() {
        return floatval($this->config->get('allocation_ple_sup_are'));
    }

    /**
     * getAllocationPoleEmploiSupSjr (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiSupSjr() {
        return floatval($this->config->get('allocation_ple_sup_sjr'));
    }

    /**
     * getAllocationPoleEmploiPlafondAre (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiPlafondAre() {
        return floatval($this->config->get('allocation_ple_plafond_are'));
    }

    /**
     * getAllocationPoleEmploiAreMin (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiAreMin() {
        return floatval($this->config->get('allocation_ple_are_min'));
    }

    /**
     * getAllocationPoleEmploiAreMax (paramétrer BIO)
     * @return float
     */
    public function getAllocationPoleEmploiAreMax() {
        return floatval($this->config->get('allocation_ple_are_max'));
    }

    /**
     * getPlafondQuotidienIndeminiseMadp (paramétrer BIO)
     * @return float
     */
    public function getPlafondQuotidienIndeminiseMadp() {
        return intval($this->config->get('plafond_quotidien_indeminise_madp'));
    }

    /**
     * getHauteurDesPas (paramétrer BIO)
     * @return float
     */
    public function getHauteurDesPas() {
        return intval($this->config->get('hauteur_des_pas'));
    }

    /**
     * getIndemniteMensuelleMinimumMadp (paramétrer BIO)
     * @return float
     */
    public function getIndemniteMensuelleMinimumMadp() {
        return intval($this->config->get('indemnite_mensuelle_minimum_madp'));
    }

    /**
     * Retourner l’entier immédiatement inférieur (par pas de ex:50) au résultat obtenu
     * @param int $n
     * @param int $x step to down
     * @return int
     */
    public function roundDownToAny($n, $x = 50) {
        return (ceil($n) % $x === 0) ? ceil($n) : round(($n - $x / 2) / $x) * $x;
    }

    /**
     *
     * @return int
     */
    public function getDureeIndemnisationMin() {
        return intval($this->config->get('duree_indemnisation_minimum'));
    }

    /**
     *
     * @return int
     */
    public function getDureeIndemnisationMax() {
        return intval($this->config->get('duree_indemnisation_maximum'));
    }

    /**
     *
     * @return int
     */
    public function getDureeIndemnisationDefault() {
        return intval($this->config->get('duree_indemnisation_default'));
    }

    public function getTauxCotisationParamsJson() {
        return json_decode($this->config->get('taux_cotisation_params_json'));
    }

    public function getMontantMensuelIndemniseTranche1_min() {
        return json_decode($this->config->get('tranche_indemnisation_1_min'));
    }

    public function getMontantMensuelIndemniseTranche1_max() {
        return json_decode($this->config->get('tranche_indemnisation_1_max'));
    }

    public function getMontantMensuelIndemniseTranche2_min() {
        return json_decode($this->config->get('tranche_indemnisation_2_min'));
    }

    public function getMontantMensuelIndemniseTranche2_max() {
        return json_decode($this->config->get('tranche_indemnisation_2_max'));
    }

    public function getMontantMensuelIndemniseTranche3_min() {
        return json_decode($this->config->get('tranche_indemnisation_3_min'));
    }

    public function getMontantMensuelIndemniseTranche3_max() {
        return json_decode($this->config->get('tranche_indemnisation_3_max'));
    }

    public function getTauxDeRetraite() {
        return floatval($this->config->get('taux_de_retraite'));
    }

    public function getTauxChargesSocialesAvecFraisPle() {
        return floatval($this->config->get('taux_charges_sociales_avec_frais_ple'));
    }

}
