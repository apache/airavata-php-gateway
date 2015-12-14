<?php

class GatewayprofileController extends BaseController {

	public function __construct()
	{
		$this->beforeFilter('verifyadmin');
		Session::put("nav-active", "gateway-profile");
	}

	public function createView()
	{
		return View::make("gateway/create");
	}

	public function createSubmit()
	{
		$gatewayProfileId = CRUtilities::create_or_update_gateway_profile( Input::all() );
		//TODO:: Maybe this is a better way. Things to ponder upon.
		//return Redirect::to("gp/browse")->with("gpId", $gatewayProfileId);
		return Redirect::to("gp/browse")->with("message","Gateway has been created. You can set preferences now.");
	}

	public function editGP()
	{
		$gatewayProfileId = CRUtilities::create_or_update_gateway_profile( Input::all(), true );
		return Redirect::to("gp/browse")->with("message","Gateway has been created. You can set preferences now.");
	}

	public function browseView()
	{
		//var_dump( $crObjects[0]); exit;
		return View::make("gateway/browse", array(	"gatewayProfiles" => CRUtilities::getAllGatewayProfilesData(),
													"computeResources" => CRUtilities::getAllCRObjects(),
													"crData" => CRUtilities::getEditCRData()
 												));
	}

	public function modifyCRP()
	{
		if( CRUtilities::add_or_update_CRP( Input::all()) )
		{
			return Redirect::to("admin/dashboard/gateway")->with("message","Compute Resource Preference for the desired Gateway has been set.");
		}
	}

	public function modifyDSP()
	{
		if( SRUtilities::add_or_update_DSP( Input::all()) )
		{
			return Redirect::to("admin/dashboard/gateway")->with("message","Data Storage Preference for the desired Gateway has been set.");
		}
	}

	public function delete()
	{
		//var_dump( Input::all()); exit;
		$error = false;
		if( Input::has("del-gpId")) // if Gateway has to be deleted
		{
			if( CRUtilities::deleteGP( Input::get("del-gpId")) )
				return Redirect::to("admin/dashboard/gateway")->with("message","Gateway Profile has been deleted.");
			else
				$error = true;
		}
		else if( Input::has("rem-crId")) // if Compute Resource has to be removed from Gateway
		{
			if(CRUtilities::deleteCR( Input::all()) )
				return Redirect::to("admin/dashboard/gateway")->with("message", "The selected Compute Resource has been successfully removed");
			else
				$error = true;
		}
		else
			$error = true;


		if( $error)
		{
			return Redirect::to("admin/dashboard/gateway")->with("message","An error has occurred. Please try again later or report a bug using the link in the Help menu");
		}
	}

	public function cstChange(){
		$inputs = Input::all();
		
		if( CRUtilities::updateGatewayProfile( $inputs) )
		{
            return "Credential Store Token has been updated";     
        }
        else
            return "An error has occurred. Please try again later or report a bug using the link in the Help menu";
	}
}

?>