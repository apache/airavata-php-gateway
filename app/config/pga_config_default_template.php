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
        'tenant-domain' => '',

        /**
         * Tenant admin's username
         */
        'admin-username' => 'scigap_admin',

        /**
         * Tenant admin's password
         */
        'admin-password' => 'sci9067@min',

        /**
         * OAuth client key
         */
        'oauth-client-key' => 'fI0fo8luZYsDMPqfIYH8fN6wtfMa',

        /**
         * OAuth client secret
         */
        'oauth-client-secret' => 'cRKUjG8jPUgWj7NxUNT5Tf5621Aa',

        /**
         * Identity server domain
         */
        'server' => 'idp.scigap.org',

        /**
         * Identity server url
         */
        'service-url' => 'https://idp.scigap.org:9443/',

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
        'airavata-server' => 'gw56.iu.xsede.org',

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
        'gateway-id' => 'scigap',

        /**
         * Maximum size of a file which is allowed to upload to the server
         */
        'server-allowed-file-size' => 64,

        /**
         * directory in the web server where experiment data is staged. (relative to the PGA documents root)
         */
        'experiment-data-dir' => '/../experimentData',

        /**
         * absolute path of the data dir
         */
        'experiment-data-absolute-path' => 'C:\wamp\www\experimentData',

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
        'app-catalog-cache-duration' => 5
    ],

    /**
     * *****************************************************************
     *  Portal Related Configurations
     * *****************************************************************
     */
    'portal' => [
    /**
         * Whether this portal is the SciGaP admin portal
         */
        'super-admin-portal' => true,

        /**
         * Set the name of theme in use here
         */
        'theme' => 'base',

        /**
         * Portal title
         */
        'portal-title' => 'SciGaP Admin Portal',

        /**
         * Email address of the portal admin. Portal admin well get email notifications for events
         * such as new user creation
         */
        'admin-emails' => ['sgg@iu.edu','eroma.abeysinghe@gmail.com','supun.nakandala@gmail.com'],

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
        'portal-smtp-server-port' => '587',

        /**
         * Set JIRA Issue Collector scripts here.
         */
        'jira-help' => 
        [
            /**
             * Report Issue Script issued for your app by Atlassian JIRA
             */
            'report-issue-script' => '',
            /**
             * Collector id at the end of the above script
             */
            'report-issue-collector-id' => '',
            /**
             * Create Report Script issued for your app by Atlassian JIRA
             */
            'request-feature-script' => '',
            /**
             * Collector id at the end of the above script
             */
            'request-feature-collector-id' => ''
        ],

        /**
         * Set Google Analytics Id here. ID format that generates from  
         * creating tracker object should be 
         *
         * UA-XXXXX-Y 
         *
         * for it to be working correctly. Currently it is only set for 
         * sending pageviews.
         */
        'google-analytics-id' => ''
    ]
);