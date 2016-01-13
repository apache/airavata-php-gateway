<?php
return array(
    /**
     * *****************************************************************
     *  These are WSO2 Identity Server Related Configurations
     * *****************************************************************
     */

    'wsis' => [

        /**
         * Admin Role Name
         */
        'admin-role-name' => 'Internal/everyone',

        /**
         * Read only Admin Role Name
         */
        'read-only-admin-role-name' => 'admin-read-only',

        /**
         * Gateway user role
         */
        'user-role-name' => 'airavata-user',

        /**
         * Tenant Domain
         */
        'tenant-domain' => 'master.airavata',

        /**
         * Tenant admin's username
         */
        'admin-username' => 'master',

        /**
         * Tenant admin's password
         */
        'admin-password' => 'master',

        /**
         * OAuth client key
         */
        'oauth-client-key' => 'O3iUdkkVYyHgzWPiVTQpY_tb96Ma',

        /**
         * OAuth client secret
         */
        'oauth-client-secret' => '6Ck1jZoa2oRtrzodSqkUZ2iINkUa',

        /**
         * Identity server domain
         */
        'server' => 'idp.scigap.org',

        /**
         * Identity server url
         */
        'service-url' => 'https://idp.scigap.org:7443/',

        /**
         * Enable HTTPS server verification
         */
        'verify-peer' => true,

        /**
         * Path to the server certificate file
         */
        'cafile-path' => app_path() . '/resources/security/idp_scigap_org.pem',

        /**
         * Allow self signed server certificates
         */
        'allow-self-signed-cert' => false
    ],


    /**
     * *****************************************************************
     *  These are Airavata Related Configurations
     * *****************************************************************
     */
    'airavata' => [

        /**
         * Airavata API server location. Use tls:// as the protocol to
         * connect TLS enabled Airavata
         */
        'airavata-server' => 'gw77.iu.xsede.org',

        /**
         * Airavata API server port
         */
        'airavata-port' => '8930',

        /**
         * Airavata API server thrift communication timeout
         */
        'airavata-timeout' => '1000000',

        /**
         * Data Manager CPI server host
         */
        'data-manager-server' => 'localhost',

        /**
         * Data Manager CPI server port
         */
        'data-manager-port' => '8990',

        /**
         * Data Manager CPI server thrift communication timeout
         */
        'data-manager-timeout' => '1000000',

        /**
         * PGA Gateway ID
         */
        'gateway-id' => 'seagrid',

        /**
         * Maximum size of a file which is allowed to upload to the server
         */
        'server-allowed-file-size' => 64,

        /**
         * absolute path of the data dir
         */
        'experiment-data-absolute-path' => '/Library/WebServer/Documents/experimentData',

        /**
         * username for the user for accessing the experiment data over ssh
         */
        'ssh-user' => 'root',

        /**
         * Advanced experiments options
         */
        'advanced-experiment-options' => '',

        /**
         * Default queue name
         */
        'queue-name' => 'long',

        /**
         * Default node count
         */
        'node-count' => '1',

        /**
         * Default total core count
         */
        'total-cpu-count' => '16',

        /**
         * Default wall time limit
         */
        'wall-time-limit' => '30',

        /**
         * Enable app-catalog cache
         */
        'enable-app-catalog-cache' => true,

        /**
         * Life time of app catalog data cache in minutes
         */
        'app-catalog-cache-duration' => 5,

        /**
         * Gateway data store resource id
         */
         'gateway-data-store-resource-id' => ''
    ],
    /**
     * *****************************************************************
     *  Portal Related Configurations
     * *****************************************************************
     */
    'portal' => [

        /**
         * Whether this portal is the super admin portal
         */
        'super-admin-portal' => true,

        /**
         * Set the name of theme in use here
         */
        'theme' => 'base',

        /**
         * Portal titles
         */
        'portal-title' => 'Airavata PHP Gateway',

        /**
         * Email addresses of the portal admins. Portal admins well get email notifications for events
         * such as new user creation
         */
        'admin-emails' => ['eroma.abeysinghe@gmail.com','supun.nakandala@gmail.com'],

        /**
         * Email account that the portal should login to send emails
         */
        'portal-email-username' => 'pga.airavata@gmail.com',

        /**
         * Password for the portal's email account
         */
        'portal-email-password' => 'airavata12',

        /**
         * SMTP server on which the portal should connect
         */
        'portal-smtp-server-host' => 'smtp.gmail.com',

        /**
         * SMTP server port on which the portal should connect
         */
        'portal-smtp-server-port' => '587'
    ]
);
