phpbb-saml2
===========

A custom authentication module for phpBB v3.x that supports:

- Federated user authentication with SAML2
- Automatic user profile creation on phpBB
- Automatic management of user group-memberships on phpBB

The authentication module wraps [SimpleSamlPhP](http://www.simplesamlphp.org) in a phpBB authentication module and integrates with the phpBB user and group management system, so that a profile is automatically created for new users, and new users are made members of relevant groups in phpBB based on attributes in their SAML2 token.

The module is quite rudimentary, as it was developed in a very short timeframe for a one-off project with somewhat specific requirements. It has, however, been used on a medium traffic production phpBB site for the past year and a half without any issues to date.

##### Limitations

This module is merely the plumbing between [SimpleSamlPhP](http://www.simplesamlphp.org) and [phpBB](http://www.phpBB.org). It does not deal the configuration of [SimpleSamlPhP](http://www.simplesamlphp.org), and it requires some knowledge of [phpBB](http://www.phpBB.org) to install and enable the authentication module.

[SimpleSamlPhP](http://www.simplesamlphp.org) is a very mature framework that is successfully used in large production environments with thousands of simultaneous users, and multiple logins (issued tokens) per second. It does require some knowledge about things like certificates, SSL, and SAML2 federation to configure it, but their website provides a great starting point for howtos.

I highly recommend that a basic [SimpleSamlPhP](http://www.simplesamlphp.org) is successfully tested with the identity provider before the module is enabled in phpBB. Different identity providers have different default settings, and it can take some tweaking of configurations for [SimpleSamlPhP](http://www.simplesamlphp.org) to make it work.

I have successfully tested with module with several different identity providers including [SimpleSamlPhP](http://www.simplesamlphp.org) itself, [Safewhere*Identify](http://safewhere.com/), and Microsoft AD FS2.0.


