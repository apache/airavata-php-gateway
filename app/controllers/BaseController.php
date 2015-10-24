<?php

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		//Theme gets set baesd on the one chosen in pga_config. default is basic.
		$themeName = Config::get('pga_config.portal')['theme'];
		//$theme = Theme::uses( $themeName); 
		Session::put( "theme", $themeName);

		
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

}
