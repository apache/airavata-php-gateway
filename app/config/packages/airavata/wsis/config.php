<?php
return array(
    /**
     * Admin Role Name
     */
    'admin-role-name' => 'admin',

    /**
     * Gateway user role
     */
    'gateway-admin' => 'Internal/everyone',

    /**
     * Tenant admin's username
     */
    'admin-username' => 'test@testphprg.scigap.org',

    /**
     * Tenant admin's password
     */
    'admin-password' => 'testadmin@scigap.org',

    /**
     * Identity server domain
     */
    'server' => 'idp.scigap.org',

    /**
     * Identity server web services endpoint
     */
    'service-url' => 'https://idp.scigap.org:7443/services/',

    /**
     * Gateway domain name
     */
    'gateway-id' => 'default',

    /**
     * Path to the server certificate file
     */
    'cafile-path' => app_path() . '/resources/security/idp_scigap_org.pem',

    /**
     * Enable HTTPS server verification
     */
    'verify-peer' => true,

    /**
     * Allow self signed server certificates
     */
    'allow-self-signed' => false
);