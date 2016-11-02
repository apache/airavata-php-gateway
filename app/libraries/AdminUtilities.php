<?php

use Airavata\Model\Workspace\Gateway;
use Airavata\Model\Workspace\Notification;
use Airavata\Model\Workspace\NotificationPriority;
use Airavata\Model\Workspace\GatewayApprovalStatus;

class AdminUtilities
{

    /**
     * To create a new gateway
     * @param $input
     * @return string
     */
    public static function add_gateway($input)
    {
        $gateway = new Gateway();
        $gateway->gatewayId = $input["gatewayName"];
        $gateway->domain = $input["domain"];
        $gateway->gatewayName = $input["gatewayName"];
        $gateway->emailAddress = $input["admin-email"];
        $gateway->gatewayApprovalStatus = GatewayApprovalStatus::APPROVED;
        return Airavata::addGateway(Session::get('authz-token'), $gateway);
    }

    /**
     * Method to get experiment execution statistics object
     * @param $fromTime
     * @param $toTime
     * @return \Airavata\Model\Experiment\ExperimentStatistics
     */
    public static function get_experiment_execution_statistics($fromTime, $toTime)
    {
        return Airavata::getExperimentStatistics(Session::get('authz-token'),
            Config::get('pga_config.airavata')['gateway-id'], $fromTime, $toTime);
    }

    /**
     * Method to get experiments of a particular time range
     * @param $inputs
     * @return array
     */
    public static function get_experiments_of_time_range($inputs)
    {
        $experimentStatistics = AdminUtilities::get_experiment_execution_statistics(
            strtotime($inputs["from-date"]) * 1000,
            strtotime($inputs["to-date"]) * 1000
        );
        $experiments = array();
        if ($inputs["status-type"] == "ALL") {
            $experiments = $experimentStatistics->allExperiments;
        }else if ($inputs["status-type"] == "COMPLETED") {
            $experiments = $experimentStatistics->completedExperiments;
        }else if ($inputs["status-type"] == "CREATED") {
            $experiments = $experimentStatistics->createdExperiments;
        }else if ($inputs["status-type"] == "RUNNING") {
            $experiments = $experimentStatistics->runningExperiments;
        } elseif ($inputs["status-type"] == "FAILED") {
            $experiments = $experimentStatistics->failedExperiments;
        } else if ($inputs["status-type"] == "CANCELED") {
            $experiments = $experimentStatistics->cancelledExperiments;
        }

        $expContainer = array();
        $expNum = 0;
        foreach ($experiments as $experiment) {
            //var_dump( $experiment); exit;
            $expValue = ExperimentUtilities::get_experiment_values($experiment, true);
            $expContainer[$expNum]['experiment'] = $experiment;
            $expValue["editable"] = false;
            $expContainer[$expNum]['expValue'] = $expValue;
            $expNum++;
        }

        return $expContainer;
    }

    public static function create_ssh_token(){
        return $newToken = Airavata::generateAndRegisterSSHKeys( Session::get('authz-token'), Session::get("gateway_id"), Session::get("username"));
    }

    public static function create_pwd_token($inputs){
        $username = $inputs['username'];
        $password = $inputs['password'];
        $description = $inputs['description'];
        return $newToken = Airavata::registerPwdCredential( Session::get('authz-token'), Session::get("gateway_id"),
            Session::get("username"), $username, $password, $description);

    }

    public static function get_all_ssh_tokens(){
        return Airavata::getAllGatewaySSHPubKeys( Session::get('authz-token'), Session::get("gateway_id") );
    }

    public static function get_all_pwd_tokens(){
        return Airavata::getAllGatewayPWDCredentials( Session::get('authz-token'), Session::get("gateway_id") );
    }

    public static function get_pubkey_from_token( $token){
        return Airavata::getSSHPubKey( Session::get('authz-token'), $token, Session::get("gateway_id"));
    }

    public static function remove_ssh_token( $token){
        return Airavata::deletePWDCredential( Session::get('authz-token'), $token, Session::get("gateway_id"));
    }

    public static function remove_pwd_token( $token){
        return Airavata::deleteSSHPubKey( Session::get('authz-token'), $token, Session::get("gateway_id"));
    }

    public static function add_or_update_notice( $notifData, $update = false){
        $notification = new Notification();
        $notification->gatewayId = Session::get("gateway_id");
        $notification->title = $notifData["title"];
        $notification->notificationMessage = $notifData["notificationMessage"];
        $notification->publishedTime = strtotime( $notifData["publishedTime"])* 1000;
        $notification->expirationTime = strtotime( $notifData["expirationTime"]) * 1000;
        $notification->priority = intval($notifData["priority"]);

        if( $update){
            $notification->notificationId =  $notifData["notificationId"];
            if( Airavata::updateNotification( Session::get("authz-token"), $notification) )
            {
                return json_encode( Airavata::getNotification(  Session::get('authz-token'), 
                                                                Session::get("gateway_id"), 
                                                                $notifData["notificationId"] ));
            }
            else
                0;
        }
        else
            return Airavata::getNotification( 
                    Session::get('authz-token'), 
                    Session::get("gateway_id"), 
                    Airavata::createNotification( Session::get("authz-token"), $notification) );
    }

    public static function delete_notice( $notificationId){
        return Airavata::deleteNotification( Session::get('authz-token'), Session::get("gateway_id"), $notificationId);
    }

    public static function add_or_update_IDP($inputs)
    {
        $gatewayId = $inputs['gatewayId'];
        $identityServerTenant = $inputs['identityServerTenant'];
        $identityServerPwdCredToken = $inputs['identityServerPwdCredToken'];

        $gp = Airavata::getGatewayResourceProfile(Session::get('authz-token'), $gatewayId);
        if(!empty($identityServerTenant)){
            $gp->identityServerTenant = $identityServerTenant;
        }else{
            $gp->identityServerTenant = "";
        }

        if(!empty($identityServerPwdCredToken) and $identityServerPwdCredToken != 'DO-NOT-SET'){
            $gp->identityServerPwdCredToken = $identityServerPwdCredToken;
        }else{
            $gp->identityServerPwdCredToken = null;
        }


        Airavata::updateGatewayResourceProfile(Session::get('authz-token'), $gatewayId, $gp);

        return true;
    }
}