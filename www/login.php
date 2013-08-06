<?php

/**
 * This page asks for User tel no and presents the verification code.
 *
 * @author Laas Toom <laas.toom@ut.ee>
 */

if (!array_key_exists('AuthState', $_REQUEST)) {
  throw new SimpleSAML_Error_BadRequest('Missing AuthState parameter.');
}
$authStateId = $_REQUEST['AuthState'];

// Retrieve the authentication state.
$state = SimpleSAML_Auth_State::loadState($authStateId, sspmod_MobiilID_Auth_Source_MobiilID::STAGEID_LOGIN);

// Load the authSource
if (array_key_exists(sspmod_MobiilID_Auth_Source_MobiilID::AUTHID, $state)) {
  $authId = $state[sspmod_MobiilID_Auth_Source_MobiilID::AUTHID];
  $as = SimpleSAML_Auth_Source::getById($authId);
} else {
  throw new SimpleSAML_Error_BadRequest('Unable to load AuthSource from state.');
}

// expect this not to be used
$midStatus = null;
// If we have mobile number given, start auth, else display form
if($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('midnumber', $_POST) && isset($_POST['midnumber'])){
  // Redirects to status.php if success
  $midStatus = $as->startMidAuth($_POST['midnumber'], $state);
}

// Continuing means we got some error

$globalConfig = SimpleSAML_Configuration::getInstance();
$t = new SimpleSAML_XHTML_Template($globalConfig, 'MobiilID:login.php');
$t->data['authstate'] = $authStateId;
$t->data['midStatus'] = $midStatus;
$t->show();
exit();

?>