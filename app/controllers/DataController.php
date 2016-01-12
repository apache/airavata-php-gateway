<?php

class DataController extends BaseController {

	public function browseView()
	{
		$metadataModels = DataManager::searchMetadata("scnakandala", "default", "test query");
		return View::make('data/browse', array("metadataModels" => $metadataModels));
	}

}

?>
