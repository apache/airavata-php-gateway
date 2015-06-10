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
        'admin-role-name' => 'admin',

        /**
         * Gateway user role
         */
        'user-role-name' => 'Internal/everyone',

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
        'allow-self-signed-cert' => false
    ],


    /**
     * *****************************************************************
     *  These are Airavata Related Configurations
     * *****************************************************************
     */
    'airavata' => [
        /**
         * Airavata API server location
        'airavata-server' => 'gw111.iu.xsede.org',
         */
        'airavata-server' => 'localhost',

        /**
         * Airavata API server port
         */
        'airavata-port' => '8930',

        /**
         * Airavata API server thrift communication timeout
         */
        'airavata-timeout' => '1000000',

        /**
         * PGA Gateway ID
         */
        'gateway-id' => 'default',

        /**
         * Maximum size of a file which is allowed to upload to the server
         */
        'server-allowed-file-size' => 64,

        /**
         * directory in the web server where experiment data is staged. This path should be a relative path from app root
         */
        'experiment-data-root' => '/../experimentData/',

        /**
         * Advanced experiments options
         */
        'advanced-experiment-options' => '',

        /**
         * Credentials Store Token
         */
        'credential-store-token' => 'bdc612fe-401e-4684-88e9-317f99409c45',

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
        'total-cpu-count' => '4',

        /**
         * Default wall time limit
         */
        'wall-time-limit' => '30',

        /**
         * Life time of app catalog data cache in minutes
         */
        'app-catalog-cache-duration' => 5
    ]

);
