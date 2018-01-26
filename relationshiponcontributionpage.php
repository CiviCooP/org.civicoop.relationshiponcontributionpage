<?php

require_once 'relationshiponcontributionpage.civix.php';
use CRM_Relationshiponcontributionpage_ExtensionUtil as E;

function relationshiponcontributionpage_civicrm_buildForm($formName, &$form) {
	if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
		// If the contribution page is configured to display relationship do that in the function below.
		CRM_Relationshiponcontributionpage_Form_Contribution_Handler::buildForm($formName, $form);
	}
	if ($formName == 'CRM_Contribute_Form_ContributionPage_Settings') {
		CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::buildForm($formName, $form);
	}
}

function relationshiponcontributionpage_civicrm_postProcess($formName, CRM_Core_Form &$form) {
	if ($formName == 'CRM_Contribute_Form_Contribution_Confirm') {
		// Handle the creation of the relationship in the function below when the contribution page 
		// is configured to also create a relationship. 
		CRM_Relationshiponcontributionpage_Form_Contribution_Handler::postProcess($formName, $form);
	}
	if ($formName == 'CRM_Contribute_Form_ContributionPage_Settings') {
		CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::postProcess($formName, $form);
	}
	if ($formName == 'CRM_Contribute_Form_ContributionPage_Delete') {
		CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::deletePostProcess($formName, $form);
	}
}
/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function relationshiponcontributionpage_civicrm_config(&$config) {
  _relationshiponcontributionpage_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function relationshiponcontributionpage_civicrm_xmlMenu(&$files) {
  _relationshiponcontributionpage_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function relationshiponcontributionpage_civicrm_install() {
  _relationshiponcontributionpage_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function relationshiponcontributionpage_civicrm_postInstall() {
  _relationshiponcontributionpage_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function relationshiponcontributionpage_civicrm_uninstall() {
  _relationshiponcontributionpage_civix_civicrm_uninstall();
	
	CRM_Core_DAO::executeQuery("DELETE FROM civicrm_setting WHERE name LIKE %1", array(
		1 => array(CRM_Relationshiponcontributionpage_Form_ContributionPage_Settings::SETTING_NAME_PREFIX.'%', 'String'),
	));
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function relationshiponcontributionpage_civicrm_enable() {
  _relationshiponcontributionpage_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function relationshiponcontributionpage_civicrm_disable() {
  _relationshiponcontributionpage_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function relationshiponcontributionpage_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _relationshiponcontributionpage_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function relationshiponcontributionpage_civicrm_managed(&$entities) {
  _relationshiponcontributionpage_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function relationshiponcontributionpage_civicrm_caseTypes(&$caseTypes) {
  _relationshiponcontributionpage_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function relationshiponcontributionpage_civicrm_angularModules(&$angularModules) {
  _relationshiponcontributionpage_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function relationshiponcontributionpage_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _relationshiponcontributionpage_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function relationshiponcontributionpage_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function relationshiponcontributionpage_civicrm_navigationMenu(&$menu) {
  _relationshiponcontributionpage_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _relationshiponcontributionpage_civix_navigationMenu($menu);
} // */
