<?php

/**
 * This is the Mobiil-ID authentication class for SimpleSAMLphp.
 *
 * This code uses bits and pieces from:
 * - http://demo.digidoc.ee/auth_sample/
 *
 * This requires Crypt_RSA module from phpseclib:
 * Install: 
 * > pear channel-discover phpseclib.sourceforge.net
 * > pear install phpseclib/Crypt_RSA
 */
class sspmod_MobiilID_Auth_Source_MobiilID extends SimpleSAML_Auth_Source {

  /**
   * The key of the AuthId field in the state.
   */
  const AUTHID = 'sspmod_MobiilID_Auth_Source_MobiilID.AuthId';

  /**
   * The string used to identify initial login stage.
   */
  const STAGEID_LOGIN = 'sspmod_MobiilID_Auth_Source_MobiilID.StageIdLogin';

  /**
   * The string used to identify check status stage.
   */
  const STAGEID_STATUS = 'sspmod_MobiilID_Auth_Source_MobiilID.StageIdStatus';

  /**
   * The key to ID of MID auth session
   */
  const MIDAUTHID = 'sspmod_MobiilID_Auth_Source_MobiilID.MidAuthId';

  /**
   * The key to Challenge CODE of MID auth
   */
  const MID_CHALLENGE_CODE = 'sspmod_MobiilID_Auth_Source_MobiilID.MidChallengeCode';

  /**
   * The key to Challenge that gets signed in the Mid Auth process
   */
  const MID_CHALLENGE = 'sspmod_MobiilID_Auth_Source_MobiilID.MidChallenge';

  /**
   * The key to mobile number in auth state
   */
  const MID_NUMBER = 'sspmod_MobiilID_Auth_Source_MobiilID.MidNumber';

  /**
   * The key to user certificate data
   */
  const MID_CERT_DATA = 'sspmod_MobiilID_Auth_Source_MobiilID.MidCertData';

  /**
   * The key to spChallenge random HEX of MID auth
   */
  const SPCHALLENGE = 'sspmod_MobiilID_Auth_Source_MobiilID.SpChallenge';

  /**
   * The pointer to WSDL instance used to talk to DigiDoc service
   */
  protected $WSDL;

  /**
   * The service name displayed on mobile phone
   */
  protected $service_name;

  /**
   * The message to display on mobile device
   */
  protected $messageToDisplay = "";

  /**
   * How often to refresh status page. Default: 2 sec
   */
  public $statusRefresh = 2;

  /**
   * Constructor for this authentication source.
   *
   * @param array $info    Information about this authentication source.
   * @param array $config  Configuration.
   */
  public function __construct($info, $config) {
    assert('is_array($info)');
    assert('is_array($config)');

    /* Call the parent constructor first, as required by the interface. */
    parent::__construct($info, $config);

    if (!array_key_exists('endpoint', $config)) {
      throw new Exception('The required "endpoint" config option was not found');
    }

    if (!array_key_exists('endpoint_certificate', $config)) {
      throw new Exception('The required "endpoint_certificate" config option was not found');
    }

    // set up WSDL
    $this->WSDL = new sspmod_MobiilID_DigiDocService($config['endpoint'], $config['endpoint_certificate']);

    // Set Service name
    if (array_key_exists('service_name', $config)) {
      $this->service_name = $config['service_name'];
    }
    else {
      throw new Exception('The required "service_name" config option was not found');
    }

    // Set optional Message to display
    if (array_key_exists('message_to_display', $config)) {
      $this->messageToDisplay = $config['message_to_display'];
    }

    // Set optional Message to display
    if (array_key_exists('status_refresh', $config)) {
      $this->statusRefresh = $config['status_refresh'];
    }
  }

  /**
   * Initialize Mobiil-ID login.
   *
   * This function saves the information about the login, and redirects to a
   * Mobiil-ID login page.
   *
   * @param array &$state  Information about the current authentication.
   */
  public function authenticate(&$state) {
    assert('is_array($state)');

    /*
     * Save the identifier of this authentication source, so that we can
     * retrieve it later. This allows us to call the login()-function on
     * the current object.
     */
    $state[self::AUTHID] = $this->authId;

    /* Save the $state-array, so that we can restore it after a redirect. */
    $id = SimpleSAML_Auth_State::saveState($state, self::STAGEID_LOGIN);

    /*
     * Redirect to the login form. We include the identifier of the saved
     * state array as a parameter to the login form.
     */
    $url = SimpleSAML_Module::getModuleURL('MobiilID/login.php');
    $params = array('AuthState' => $id);
    SimpleSAML_Utilities::redirect($url, $params);

    /* The previous function never returns, so this code is never executed. */
    assert('FALSE');
  }

  /**
   * Saves mobile number, starts Auth process and advances state.
   *
   * This method is called when the user gives mobile number
   * and submits the form. This saves the number into state,
   * starts MidAuth process
   * and advances stage so that it can't be replayed.
   *
   * @param string $midnumber Given mobile number.
   * @param array $state AuthState
   */
  public function startMidAuth($midnumber, &$state) {
    assert('is_string($midnumber)');
    assert('is_array($state)');

    // save unchanged midnumber so users won't be confused with automatically changing numbers
    $state[self::MID_NUMBER] = $midnumber;

    if (substr($midnumber,0,2)!="37") {
      $midnumber = "372".$midnumber;
    }

    // Generate random HEX string of 10 bytes (20 chars) and store it for later
    $spChallenge = $this->randomHex(10);
    $state[self::SPCHALLENGE] = $spChallenge;

    $data = array();


    $result = $this->WSDL->MobileAuthenticate("", "EE", $midnumber, "EST", $this->service_name, $this->messageToDisplay, $spChallenge, "asynchClientServer", NULL, true, FALSE);

    if (
        (isset($result) && is_object($result) && is_a($result, 'SOAP_Fault')) 
        ||
        !array_key_exists('Status', $result)
      ) {
      $data["stage"] = "error";
      $data["message"] = $this->getSOAPError($result);
    }
    else {
      $data["message"] = $result["ChallengeID"];
      $data["stage"] = "progress";

      $state[self::MIDAUTHID] = intval($result["Sesscode"]);
      $state[self::MID_CHALLENGE_CODE] = $result["ChallengeID"];
      $state[self::MID_CHALLENGE] = $result["Challenge"];
      $state[self::MID_CERT_DATA] = $result["CertificateData"];

      // We store isikukood here, because status-check won't return it anymore
      $state['Attributes'] = array('isikukood' => array($result["UserIDCode"]));

      /* Save the $state-array, so that we can restore it after a redirect. */
      $id = SimpleSAML_Auth_State::saveState($state, self::STAGEID_STATUS);

      /*
       * Redirect to the status page
       */
      $url = SimpleSAML_Module::getModuleURL('MobiilID/status.php');
      $params = array('AuthState' => $id);
      SimpleSAML_Utilities::redirect($url, $params);
    }

    return $data;
  }

  /**
   * Checks MID auth status.
   *
   * This function is called each time the status page is refreshed
   * and this checks the status of the MID auth process.
   *
   * @param array $state AuthState where MID Auth process id is stored
   * @return array The current status of MID auth process
   */
  public  function checkMidAuthStatus(&$state)
  {
    assert('is_array($state)');

    // The data regarding current Mid Auth State
    $data = array();
    

    $result = $this->WSDL->GetMobileAuthenticateStatus($state[self::MIDAUTHID], false);

    // Apparently the result can be either an array of multiple params
    // or a single string if only 'Status' value was returned (see SOAP_Client#__decodeResponse)
    // Depends on whether the signature was requested previously
    $status = null;
    if (array_key_exists('Status', $result)){
      if(strlen($result['Status'])>3) {
        $status = $result['Status'];
      }
      else {
        $status = $result->backtrace[0]["args"][0];
      }
    }
    else {
      $status = $result;
    }
    switch($status){
      case "OUTSTANDING_TRANSACTION":
        $data["stage"] = "progress";
        $data['message'] = $state[self::MID_CHALLENGE_CODE];
        break;

      case "USER_AUTHENTICATED":
        if (
             $this->verifyChallenge($state[self::SPCHALLENGE], $state[self::MID_CHALLENGE])
             &&
             $this->verifySignature($state[self::MID_CHALLENGE], $result['Signature'], $state[self::MID_CERT_DATA])
           ) {
          // COLPLETE AUTH and redirect
          SimpleSAML_Auth_Source::completeAuth($state);
        }
        // If we got this far, the signature was invalid
        $data["stage"] = "error";
        $data["message"] = "mid_invalid_signature";
        break;

      case "MID_NOT_READY":
        $data["stage"] = "error";
        $data["message"] = "mid_not_ready";
        break;

      case "USER_CANCEL":
        $data["stage"] = "error";
        $data["message"] = "mid_user_cancel";
        break;

      case "PHONE_ABSENT":
        $data["stage"] = "error";
        $data["message"] = "mid_phone_absent";
        break;

      default:
        $data["stage"] = "error";
        $data["message"] = "mid_unknown_error";

        error_log("SimpleSAMLphp MobiilID: Unknown ERROR occured. STATUS: $status");
    }

    return $data;
  }

  /**
   * Fetch error message from SOAP result
   *
   * @param mixed $result The SOAP result
   * @return string The error message.
   */
  private function getSOAPError($result) {
    $errormsg = "";
    switch ($result->backtrace[0]["args"][0]) {
      case 201:
        $errormsg = "mid_no_certificate";
        break;

      case 301:
        $errormsg = "mid_not_registered";
        break;

      case 302:
        $errormsg = "mid_cert_revoked";
        break;

      case 303:
        $errormsg = "mid_not_activated";
        break;

      default:
        $errormsg = "mid_unknown_error";
        $fault_code = $result->backtrace[0]["args"][0];
        error_log("SimpleSAMLphp MobiilID: Unknown ERROR occured. SOAP Fault: $fault_code");
    }
    return $errormsg;
  }

  /**
   * Generates N bytes of random string and returns HEX encoding of that
   *
   * @param int $n How many bytes of random is needed. Default: 10
   * @return string HEX representation of generated random
   */
  public function randomHex($n=10) {
    // As output is in HEX, these could possibly be any random bytes, but let's limit to alphanum initially.
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $n; $i++) {
      $randomString .= bin2hex($characters[rand(0, strlen($characters) - 1)]);
    }
    // Just in case turn to uppercase
    return strtoupper($randomString);
  }

  /**
   * Verifies that returned challenge actually begins with issued challenge
   *
   * @param string $spChallenge SP-generated random string
   * @param string $challenge Complete challenge sent to mobile device
   * @return boolean Returns true if the complete challenge begins with generated string
   */
  private function verifyChallenge($spChallenge, $challenge) {
    // Verify that challenge begins with spChallenge
    return strncmp($challenge, $spChallenge, 20) === 0;
  }

  /**
   * Verifies that returned challenge signature is valid. NB! Does not validate certificate.
   *
   * @param string $challenge Complete challenge sent to mobile device
   * @param string $signature Signature returned from mobile device
   * @param string $certData User certificate data in BASE64 encoding
   * @return boolean Returns true if the signature is valid
   */
  private function verifySignature($challenge, $signature, $certData) {
    $success = false;

    // The signature is prefixed by ASN.1 identificator for SHA-1 algorithm (which isn't actually used)
    $asn1_prefix = "3021300906052B0E03021A05000414";

    // turn the Cert into PEM format
    $certData = <<<EOC
-----BEGIN CERTIFICATE-----
$certData
-----END CERTIFICATE-----
EOC;
    $pubkey = openssl_pkey_get_public($certData);

    // Mobiil-ID signature is taken from unhashed challenge
    // therefore we use decrypt instead of verify (which assumes a hash is used)
    $decrypt = null;
    openssl_public_decrypt(base64_decode($signature), $decrypt, $pubkey);
    if (!empty($decrypt)) {
      // turn decrypted string back to uppercase HEX
      $decrypt = strtoupper(bin2hex($decrypt));

      // verify signature (complete with ASN.1 prefix)
      $success = $decrypt == $asn1_prefix . $challenge;
    }

    return $success;
  }
}
