<?php

/**
 * This page performs actual Mobiil-ID authentication and waits for the status
 *
 * @author Laas Toom <laas.toom@ut.ee>
 */

if (!array_key_exists('AuthState', $_REQUEST)) {
  throw new SimpleSAML_Error_BadRequest('Missing AuthState parameter.');
}
$authStateId = $_REQUEST['AuthState'];

/* Retrieve the auth refresh stage. */
$state = SimpleSAML_Auth_State::loadState($authStateId, sspmod_MobiilID_Auth_Source_MobiilID::STAGEID_STATUS);

// Load the authSource
if (array_key_exists(sspmod_MobiilID_Auth_Source_MobiilID::AUTHID, $state)) {
  $authId = $state[sspmod_MobiilID_Auth_Source_MobiilID::AUTHID];
  $as = SimpleSAML_Auth_Source::getById($authId);
} else {
  throw new SimpleSAML_Error_BadRequest('Unable to load AuthSource from state.');
}

// Check auth status - this won't return if auth successfully completed
$midStatus = $as->checkMidAuthStatus($state);



$globalConfig = SimpleSAML_Configuration::getInstance();
$t = new SimpleSAML_XHTML_Template($globalConfig, 'MobiilID:status.php');

// set automatic refresh if in progress
if ($midStatus['stage'] == 'progress'){
  $t->data['head'] = ($t->data['head'] ?: "") . '<meta http-equiv="refresh" content="' . $as->statusRefresh . '">';
}

$t->data['authstate'] = $authStateId;
$t->data['midStatus'] = $midStatus;
$t->show();
exit();

?>