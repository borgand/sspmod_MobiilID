<?php

/**
 * Install requirements via PEAR.
 *
 * Set to use beta versions
 * <kbd>pear config-set preferred_state beta</kbd>
 *
 * Install requirements:
 *    pear install -a SOAP
 *    pear install -a XML_Serializer
 */
require_once 'SOAP/Client.php';
require_once 'XML/Unserializer.php';

class sspmod_MobiilID_DigiDocService extends SOAP_Client
{

	function __construct($endpoint, $certificate_file)
  {
    parent::__construct($endpoint, 0, 0,
     array('curl' => array('64' => '1', '81' => '2', '10065' => $certificate_file)));
  }



  function &StartSession($SigningProfile, $SigDocXML, $bHoldSession, $datafile)
  {
        // datafile is a ComplexType DataFileData,
        // refer to wsdl for more info
    $datafile = new SOAP_Value('datafile', '{http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl}DataFileData', $datafile);
    $result = $this->call('StartSession',
      $v = array('SigningProfile' => $SigningProfile, 'SigDocXML' => $SigDocXML, 'bHoldSession' => $bHoldSession, 'datafile' => $datafile),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &CloseSession($Sesscode)
  {
    $result = $this->call('CloseSession',
      $v = array('Sesscode' => $Sesscode),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &CreateSignedDoc($Sesscode, $Format, $Version)
  {
    $result = $this->call('CreateSignedDoc',
      $v = array('Sesscode' => $Sesscode, 'Format' => $Format, 'Version' => $Version),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &AddDataFile($Sesscode, $FileName, $MimeType, $ContentType, $Size, $DigestType, $DigestValue, $Content)
  {
    $result = $this->call('AddDataFile',
      $v = array('Sesscode' => $Sesscode, 'FileName' => $FileName, 'MimeType' => $MimeType, 'ContentType' => $ContentType, 'Size' => $Size, 'DigestType' => $DigestType, 'DigestValue' => $DigestValue, 'Content' => $Content),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &RemoveDataFile($Sesscode, $DataFileId)
  {
    $result = $this->call('RemoveDataFile',
      $v = array('Sesscode' => $Sesscode, 'DataFileId' => $DataFileId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetSignedDoc($Sesscode)
  {
    $result = $this->call('GetSignedDoc',
      $v = array('Sesscode' => $Sesscode),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetSignedDocInfo($Sesscode)
  {
    $result = $this->call('GetSignedDocInfo',
      $v = array('Sesscode' => $Sesscode),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetDataFile($Sesscode, $DataFileId)
  {
    $result = $this->call('GetDataFile',
      $v = array('Sesscode' => $Sesscode, 'DataFileId' => $DataFileId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetSignersCertificate($Sesscode, $SignatureId)
  {
    $result = $this->call('GetSignersCertificate',
      $v = array('Sesscode' => $Sesscode, 'SignatureId' => $SignatureId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetNotarysCertificate($Sesscode, $SignatureId)
  {
    $result = $this->call('GetNotarysCertificate',
      $v = array('Sesscode' => $Sesscode, 'SignatureId' => $SignatureId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetNotary($Sesscode, $SignatureId)
  {
    $result = $this->call('GetNotary',
      $v = array('Sesscode' => $Sesscode, 'SignatureId' => $SignatureId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetTSACertificate($Sesscode, $TimestampId)
  {
    $result = $this->call('GetTSACertificate',
      $v = array('Sesscode' => $Sesscode, 'TimestampId' => $TimestampId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetTimestamp($Sesscode, $TimestampId)
  {
    $result = $this->call('GetTimestamp',
      $v = array('Sesscode' => $Sesscode, 'TimestampId' => $TimestampId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetCRL($Sesscode, $SignatureId)
  {
    $result = $this->call('GetCRL',
      $v = array('Sesscode' => $Sesscode, 'SignatureId' => $SignatureId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetSignatureModules($Sesscode, $Platform, $Phase, $Type)
  {
    $result = $this->call('GetSignatureModules',
      $v = array('Sesscode' => $Sesscode, 'Platform' => $Platform, 'Phase' => $Phase, 'Type' => $Type),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &PrepareSignature($Sesscode, $SignersCertificate, $SignersTokenId, $Role, $City, $State, $PostalCode, $Country, $SigningProfile)
  {
    $result = $this->call('PrepareSignature',
      $v = array('Sesscode' => $Sesscode, 'SignersCertificate' => $SignersCertificate, 'SignersTokenId' => $SignersTokenId, 'Role' => $Role, 'City' => $City, 'State' => $State, 'PostalCode' => $PostalCode, 'Country' => $Country, 'SigningProfile' => $SigningProfile),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &FinalizeSignature($Sesscode, $SignatureId, $SignatureValue)
  {
    $result = $this->call('FinalizeSignature',
      $v = array('Sesscode' => $Sesscode, 'SignatureId' => $SignatureId, 'SignatureValue' => $SignatureValue),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &RemoveSignature($Sesscode, $SignatureId)
  {
    $result = $this->call('RemoveSignature',
      $v = array('Sesscode' => $Sesscode, 'SignatureId' => $SignatureId),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetVersion()
  {
    $result = $this->call('GetVersion',
      $v = null,
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &MobileSign($Sesscode, $SignerIDCode, $SignersCountry, $SignerPhoneNo, $ServiceName, $AdditionalDataToBeDisplayed, $Language, $Role, $City, $StateOrProvince, $PostalCode, $CountryName, $SigningProfile, $MessagingMode, $AsyncConfiguration, $ReturnDocInfo, $ReturnDocData)
  {
    $result = $this->call('MobileSign',
      $v = array('Sesscode' => $Sesscode, 'SignerIDCode' => $SignerIDCode, 'SignersCountry' => $SignersCountry, 'SignerPhoneNo' => $SignerPhoneNo, 'ServiceName' => $ServiceName, 'AdditionalDataToBeDisplayed' => $AdditionalDataToBeDisplayed, 'Language' => $Language, 'Role' => $Role, 'City' => $City, 'StateOrProvince' => $StateOrProvince, 'PostalCode' => $PostalCode, 'CountryName' => $CountryName, 'SigningProfile' => $SigningProfile, 'MessagingMode' => $MessagingMode, 'AsyncConfiguration' => $AsyncConfiguration, 'ReturnDocInfo' => $ReturnDocInfo, 'ReturnDocData' => $ReturnDocData),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetStatusInfo($Sesscode, $ReturnDocInfo, $WaitSignature)
  {
    $result = $this->call('GetStatusInfo',
      $v = array('Sesscode' => $Sesscode, 'ReturnDocInfo' => $ReturnDocInfo, 'WaitSignature' => $WaitSignature),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &MobileAuthenticate($IDCode, $CountryCode, $PhoneNo, $Language, $ServiceName, $MessageToDisplay, $SPChallenge, $MessagingMode, $AsyncConfiguration, $ReturnCertData, $ReturnRevocationData)
  {
    $result = $this->call('MobileAuthenticate',
      $v = array('IDCode' => $IDCode, 'CountryCode' => $CountryCode, 'PhoneNo' => $PhoneNo, 'Language' => $Language, 'ServiceName' => $ServiceName, 'MessageToDisplay' => $MessageToDisplay, 'SPChallenge' => $SPChallenge, 'MessagingMode' => $MessagingMode, 'AsyncConfiguration' => $AsyncConfiguration, 'ReturnCertData' => $ReturnCertData, 'ReturnRevocationData' => $ReturnRevocationData),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetMobileAuthenticateStatus($Sesscode, $WaitSignature)
  {
    $result = $this->call('GetMobileAuthenticateStatus',
      $v = array('Sesscode' => $Sesscode, 'WaitSignature' => $WaitSignature),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &MobileCreateSignature($IDCode, $SignersCountry, $PhoneNo, $Language, $ServiceName, $MessageToDisplay, $Role, $City, $StateOrProvince, $PostalCode, $CountryName, $SigningProfile, $DataFiles, $Format, $Version, $SignatureID, $MessagingMode, $AsyncConfiguration)
  {
        // DataFiles is a ComplexType DataFileDigestList,
        // refer to wsdl for more info
    $DataFiles = new SOAP_Value('DataFiles', '{http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl}DataFileDigestList', $DataFiles);
    $result = $this->call('MobileCreateSignature',
      $v = array('IDCode' => $IDCode, 'SignersCountry' => $SignersCountry, 'PhoneNo' => $PhoneNo, 'Language' => $Language, 'ServiceName' => $ServiceName, 'MessageToDisplay' => $MessageToDisplay, 'Role' => $Role, 'City' => $City, 'StateOrProvince' => $StateOrProvince, 'PostalCode' => $PostalCode, 'CountryName' => $CountryName, 'SigningProfile' => $SigningProfile, 'DataFiles' => $DataFiles, 'Format' => $Format, 'Version' => $Version, 'SignatureID' => $SignatureID, 'MessagingMode' => $MessagingMode, 'AsyncConfiguration' => $AsyncConfiguration),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetMobileCreateSignatureStatus($Sesscode, $WaitSignature)
  {
    $result = $this->call('GetMobileCreateSignatureStatus',
      $v = array('Sesscode' => $Sesscode, 'WaitSignature' => $WaitSignature),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &GetMobileCertificate($IDCode, $Country, $PhoneNo, $ReturnCertData)
  {
    $result = $this->call('GetMobileCertificate',
      $v = array('IDCode' => $IDCode, 'Country' => $Country, 'PhoneNo' => $PhoneNo, 'ReturnCertData' => $ReturnCertData),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
  function &CheckCertificate($Certificate, $ReturnRevocationData)
  {
    $result = $this->call('CheckCertificate',
      $v = array('Certificate' => $Certificate, 'ReturnRevocationData' => $ReturnRevocationData),
      array('namespace' => 'http://www.sk.ee/DigiDocService/DigiDocService_2_3.wsdl',
        'soapaction' => '',
        'style' => 'rpc',
        'use' => 'encoded'));
    return $result;
  }
}

?>