<?php
/**
 * Package: org.civicoop.relationshiponcontributionpage
 * Copyright (C) 2018, Jaap Jansma <jaap@edeveloper.nl>
 * Licensed under the GNU Affero Public License 3.0
 */

use CRM_Relationshiponcontributionpage_ExtensionUtil as E;
 
/**
 * Handler for building the contribution page and adding a dropdown for
 * relationship. 
 * Handles also the submission of the contribution page so that a relationship is created 
 * between the contact and soft credit contact.
 * 
 */
class CRM_Relationshiponcontributionpage_Form_Contribution_Handler {
	
	public static function buildForm($formName, CRM_Contribute_Form_ContributionBase &$form) {
		if ($formName != 'CRM_Contribute_Form_Contribution_Main') {
			return;
		}
		
		$contributionPageid = $form->getVar('_id');
		if (!CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::doesContributionPageProvideRelationship($contributionPageid)) {
			return;
		}
		
		$label = CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::getRelationshipLabel($contributionPageid);
		$possibleOptions = CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::getConfiguredRelationshipTypes($contributionPageid);
		$allActiveRelationshipTypes = CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::getAllActiveRelationships();
		$options = array();
		$options[] = E::ts(' - Select - ');
		foreach($possibleOptions as $type_id) {
			if (isset($allActiveRelationshipTypes[$type_id])) {
				$options[$type_id] = $allActiveRelationshipTypes[$type_id];
			}
		} 
		
		$form->add('select', 'relationship_type', $label , $options);
		CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/Relationshiponcontributionpage/Form/Contribution/Handler.tpl"
     ));
	}
	
	public static function postProcess($formName, CRM_Contribute_Form_ContributionBase &$form) {
		if ($formName != 'CRM_Contribute_Form_Contribution_Confirm') {
			return;
		}
		
		$contributionPageid = $form->getVar('_id');
		if (!CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::doesContributionPageProvideRelationship($contributionPageid)) {
			return;
		}
		
		$params = $form->getVar('_params');
		$relationship_type = $params['relationship_type'];
		$values = $form->getVar('_values');
		$contact_id = $form->getVar('_contactID');
		$honor = $values['honor'];
		$honor_id = false;
		if (isset($honor['honor_id'])) {
			$honor_id = $honor['honor_id'];
		} elseif (!empty($form->getVar('_contributionID'))) {
			try {
				$honor_id = CRM_Core_DAO::singleValueQuery("SELECT contact_id FROM civicrm_contribution_soft WHERE contribution_id = %1", array(
					1 => array($form->getVar('_contributionID'), 'Integer')
				)); 
			} catch (Exception $e) {
				// Do nothing
			}
		}
		if (!empty($relationship_type) && !empty($honor_id)) {
			civicrm_api3('Relationship', 'create', array(
				'relationship_type_id' => $relationship_type,
				'contact_id_a' => $contact_id,
				'contact_id_b' => $honor_id,
			));
		}
		
	}
	
	
	
}
