<?php
class GDMLWeb
{
	/*
	* Verify nonce for security
	* Created on 27 August 2014
	* Updated on 27 August 2014
	* */
	public function gdml_validateNonce($nonce, $field)
	{
		if (!wp_verify_nonce($nonce, $field)) 
			die('<div class="error"><p>Security check failed!</p></div>');
    }

	/*
	* Save Mapping Folder
	* Created on 27 August 2014
	* Updated on 27 August 2014
	* */
	public function gdml_saveMappingFolder($mappingFolder, $nonce, $nonceField)
	{
		$this->gdml_validateNonce($nonce, $nonceField);
		$url = "https://googledrive.com/host/{$mappingFolder}/";

		if(empty($mappingFolder))
			return "<div class='error'><p>Google Drive folder is required!</p></div>";

		if (!@file_get_contents($url)) 
			return "<div class='error'><p>Google Drive folder does not exist!</p></div>";

		$mappingFolder = sanitize_text_field($mappingFolder);
        
		if(update_option('gdml_mapping_folder', $mappingFolder))
			return "<div class='updated'><p>Google Drive folder has been saved successfully.</p></div>";
	}
    
	/*
	* Save Mapping File
	* Created on 27 August 2014
	* Updated on 27 August 2014
	* */
	function gdml_saveMappingFile($fileName, $fileID, $folder, $description, $nonce, $nonceField)
	{
		$this->gdml_validateNonce($nonce, $nonceField);
        
		// Verify Google Drive mapping folder
		//if (empty($folder))
		//	return "<div class='error'><p>Please set up Google Drive folder in Mapping Folder!</p></div>";

		// Verify file name
		if (empty($fileName))
			return "<div class='error'><p>File name is required!</p></div>";

		// Verify file id
		if (empty($fileID))
			return "<div class='error'><p>File ID is required!</p></div>";

		// unpack file id from potential URL
		if (strpos($fileID, "https://") !== false && ($i = strpos($fileID, "id=")) !== false)
		{
			$fileID = substr($fileID, $i + 3);
		}
	
		$filePath = "GDML-Mapping/id={$fileID}/{$fileName}";
		//$fullFile = "https://googledrive.com/host/{$folder}/{$fileName}";
		$fullFile = "https://drive.google.com/uc?id={$fileID}";

		if (@fclose(@fopen($fullFile, "rb")))
		{
			$imageSize = getimagesize($fullFile);
			$imageWidth = $imageSize[0];
			$imageHeight = $imageSize[1];
			$fileType = $imageSize["mime"];

			$attachment = array(
				'post_mime_type' => $fileType,
				'guid' => $filePath,
                'post_parent' => 0,
				'post_title' => $fileName,
				'post_content' => $description
			);

            $attachmentId = wp_insert_attachment($attachment, $filePath, 0);
			
	        $imageMeta = array(
				'aperture' => 0,
				'credit' => '',
				'camera' => '',
				'caption' => $fileName,
				'created_timestamp' => 0,
				'copyright' => '',
				'focal_length' => 0,
				'iso' => 0,
				'shutter_speed' => 0,
				'title' => $fileName,
				'orientation' => 0,
				'keywords' => array(),
			);
            $attachmentMeta = array(
				'image_meta' => $imageMeta,
				'width' => $imageWidth,
				'height' => $imageHeight,
                'file' => $filePath,
				'GDML' => true,
				'GDMLID' => $fileID
			);
				
			//$attachmentMeta = wp_generate_attachment_metadata($attachmentId, $filePath);
				
            if (wp_update_attachment_metadata($attachmentId,  $attachmentMeta))
                return "<div class='updated'><p>File <em>'{$fileName}'</em> has been saved successfully.</p><p><pre>".print_r($imageSize, true)."</pre></p></div>";
        }
        else
		{
            return "<div class='error'><p>File <em>'{$fileName}'</em> does not exist!</p></div>";
		}
    }
    
} // end of class
?>