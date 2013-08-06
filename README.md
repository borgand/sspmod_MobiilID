Mobiil-ID module for SimpleSAMLphp
==================================

This module implements Estonian [Mobiil-ID](http://www.id.ee/index.php?id=35739) authentication for [SimpleSAMLphp](http://simplesamlphp.org) <abbr title="Authentication and Authorization Infrastructure">AAI</abbr> framework.

Refer to the [DigiDocService documentation](http://www.sk.ee/upload/files/DigiDocService_spec_eng.pdf) for details on the underlying architecture.


This code is considered production-ready.


USAGE
=====

This module handles all Mobiil-ID traffic, starting by asking for the phone number and ending in one of the two results:

* _successful authentication_ - the user's personal ID code is placed into `Attributes` array as `isikukood`
* _authentication failure_ for any number of reasons - the user will be presented with appropriate message and the session stops there.

  It is possible to further customize the `status.php` page and include links to some troubleshooter or help center etc via normal [SSP theming](http://simplesamlphp.org/docs/stable/simplesamlphp-theming).

**NB!** **SECURITY BREACH** might result unless the ID code is further processed and authorization aplied. If this is not done, _all_  Estonian Mobiil-ID users will be passed as valid users of the SSP site.


Installation
============

Dependencies
------------

This module depends on a few PEAR modules. To get the latest versions, first instruct PEAR to prefer beta versions (older stables might not work):

    pear config-set preferred_state beta

And then install modules:

    pear install -a SOAP
    pear install -a XML_Serializer
    
    pear channel-discover phpseclib.sourceforge.net
    pear install phpseclib/Crypt_RSA


Clone the module
----------------

Clone the module from GitHub into SSP `modules` folder:

    cd modules
    git clone https://github.com/borgand/sspmod_MobiilID MobiilID

**Note:** the resulting folder name must be **MobiilID** or SimpleSAMLphp won't find the source files and the module won't work (see: [SSP autoloader](http://code.google.com/p/simplesamlphp/source/browse/trunk/lib/_autoload.php)).


Enable the module
-----------------

The module is disabled by default. Enable it by:

    touch MobiilID/enable

Authsource
----------

Configure Mobiil-ID as an authsource in the `config/authsources.php` file, e.g using the [test DigiDocService](https://www.openxades.org):

    'mobiilid' => array(
      'MobiilID:MobiilID',
      'endpoint' => 'https://www.openxades.org:8443/?wsdl',
      'endpoint_certificate' => '/path/to/endpoint.cert',
      'service_name' => 'Testimine',
      'message_to_display' => 'Verify that codes match!',
      'status_refresh' => 5,
    ),

This configures the authsource, but to actually use it, you must configure this as the default authsource in `metadata/saml20-idp-hosted.php` or alternatively use SSP's [MultiAuth](http://simplesamlphp.org/docs/stable/multiauth:multiauth) module to use multiple authsources simultaneously.

Credit
======

It is based on the demo auth application published by AS Sertifitseerimiskeskus:

  * [http://demo.digidoc.ee/auth_sample/](http://demo.digidoc.ee/auth_sample/)


Authors & Contributors
======================

This module was at [University of Tartu](http://www.ut.ee/en) by:

 * Laas Toom
