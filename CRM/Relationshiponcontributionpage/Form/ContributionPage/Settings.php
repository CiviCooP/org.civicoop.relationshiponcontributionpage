<?php
/**
 * Package: org.civicoop.relationshiponcontributionpage
 * Copyright (C) 2018, Jaap Jansma <jaap@edeveloper.nl>
 * Licensed under the GNU Affero Public License 3.0
 */

use CRM_Relationshiponcontributionpage_ExtensionUtil as E;

/**
 * This class will provide the possibility to enable the relationship 
 * on a contribution page. 
 */
class CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings {
	
	const SETTING_GROUP = 'org.civicoop.relationshipcontributionpage';
	const SETTING_NAME_PREFIX = 'org.civicoop.relationship_contribution_page_';
	
	/**
	 * Returns whether the contribution page has enabled the honoree section 
	 * and the relationship section.
	 * 
	 * @param int $contributionPageId
	 * @return true;
	 */
	public static function doesContributionPageProvideRelationship($contributionPageId) {
		$name = self::getSettingName($contributionPageId);
		$strData = CRM_Core_BAO_Setting::getItem(self::SETTING_GROUP, $name);
		$data = json_decode($strData, true);
		if (isset($data['enabled']) && $data['enabled']) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns a list with relationship types
	 * 
	 * @param int $contributionPageId
	 * @return array
	 */
	public static function getConfiguredRelationshipTypes($contributionPageId) {
		$name = self::getSettingName($contributionPageId);
		$strData = CRM_Core_BAO_Setting::getItem(self::SETTING_GROUP, $name);
		$data = json_decode($strData, true);
		if (isset($data['enabled']) && $data['enabled'] && isset($data['options']) && is_array($data['options'])) {
			return $data['options'];
		}
		return array();
	}
	
	private static function getSettingName($contributionPageId) {
		return self::SETTING_NAME_PREFIX.$contributionPageId;
	}
	
	/**
	 * Returns the label for the relationship field.
	 * 
	 * @param int $contributionPageId
	 * @return string
	 */
	public static function getRelationshipLabel($contributionPageId) {
		$name = self::getSettingName($contributionPageId);
		$strData = CRM_Core_BAO_Setting::getItem('org.civicoop.relationshipcontributionpage', $name);
		$data = json_decode($strData, true);
		if (isset($data['enabled']) && $data['enabled'] && isset($data['label'])) {
			return $data['label'];
		}
		return E::ts('Relationship');
	}
	
	public static function getAllActiveRelationships() {
		$relationshipTypes = CRM_Core_PseudoConstant::relationshipType();
		$relationshipTypeOptions = array();
		foreach($relationshipTypes as $typeId => $type) {
			$relationshipTypeOptions[$typeId] = $type['label_a_b'];
		}
		return $relationshipTypeOptions;
	}
	
	public static function buildForm($formName, CRM_Core_Form &$form) {
		if ($formName != 'CRM_Contribute_Form_ContributionPage_Settings') {
			return;
		}
		if (!$form->get_template_vars('snippet') || $form->get_template_vars('snippet') != 'json') {
			return;
		}
		
		$relationshipTypeOptions = self::getAllActiveRelationships();
		
		$form->add('checkbox', 'soft_credit_relationship_enabled', E::ts('Create relationship between contact and honoree'));
		$form->add('text', 'soft_credit_relationship_label', E::ts('Relationship label'), array(
			'size' => CRM_Utils_Type::HUGE,
		));
		$form->addSelect('soft_credit_relationship_options',array(
			'label' => E::ts('Exposed relationships'), 
		  'options' => $relationshipTypeOptions, 
			'multiple' => true,
		));
		
		$defaults['soft_credit_relationship_enabled'] = false;
		$defaults['soft_credit_relationship_label'] = E::ts('Relationship');
		$defaults['soft_credit_relationship_options'] = array_keys($relationshipTypeOptions);
		if (self::doesContributionPageProvideRelationship($form->getVar('_id'))) {
			$defaults['soft_credit_relationship_enabled'] = true;
			$defaults['soft_credit_relationship_label'] = self::getRelationshipLabel($form->getVar('_id'));
			$defaults['soft_credit_relationship_options'] = self::getConfiguredRelationshipTypes($form->getVar('_id'));	
		}
		$form->setDefaults($defaults);
		
		CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/Relationshiponcontributionpage/Form/ContributionPage/Settings.tpl"
   	));
	}

	public static function postProcess($formName, CRM_Core_Form &$form) {
		if ($formName != 'CRM_Contribute_Form_ContributionPage_Settings') {
			return;
		}
		
		$submit = $form->getSubmitValues();
		$data['enabled'] = !empty($submit['soft_credit_relationship_enabled']) ? true : false;
		if ($data['enabled']) {
			$data['label'] = $submit['soft_credit_relationship_label'];
			$data['options'] = $submit['soft_credit_relationship_options'];
		}
		$strData = json_encode($data);
		$name = self::getSettingName($form->getVar('_id'));
		CRM_Core_BAO_Setting::setItem($strData, self::SETTING_GROUP, $name);
	}

	public static function deletePostProcess($formName, CRM_Core_Form &$form) {
		if ($formName != 'CRM_Contribute_Form_ContributionPage_Delete') {
			return;
		}
		$name = self::getSettingName($form->getVar('_id'));
		$domainId = \CRM_Core_Config::domainID();
		$dao = new CRM_Core_DAO_Setting();
		$dao->domain_id = $domainId;
		$dao->name = $name;
		if ($dao->find(TRUE)) {
			$dao->delete();
		}
	}
	
}
