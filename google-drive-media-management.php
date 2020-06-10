<?php
$currentPath = plugin_dir_url(__FILE__);
$mappingFolder = get_option('gdml_mapping_folder');
?>

<!--
Show notification after submitting form
Created on 26 August 2014
Updated on 26 August 2014
-->
<div id="info" style="display: none"></div>

<!--
Define tabs
Created on 26 August 2014
Updated on 26 August 2014
-->
<div id="tabs">
    <ul>
        <li><a href="#tabs-1">About</a></li>
        <li><a href="#tabs-2">Mapping File</a></li>
        <li><a href="#tabs-3" style="display:none;">Mapping Folder</a></li>
    </ul>
    
        <div id="tabs-1" style="background: whitesmoke;">
        <h4>Google Drive Media Library</h4>
        Contributor          : Felix Wei (v1.0), Mirosław Toton (v1.X)<br>
        Requires at least    : Wordpress 3.5<br>
        Tested up to         : Wordpress 5.4.1<br>
        Version              : 1.2<br>
        License              : GPLv2 or later<br>
        License URI          : http://www.gnu.org/licenses/gpl-2.0.html<br>
        <p>Description:<br>
        Mapping file from Google Drive into Wordpress Media. Support Google Drive CDN to access file from Google Drive faster.
        </p>

        <p>Features:<br>
        - Mapping files from Google Drive into WordPress Media Library.<br>
        - Attach Google Drive files to Wordpress posts.<br>
        - Support Google Drive CDN.<br>
        </p>

        <p>Required:<br>
        - PHP 5.3.0</p>
        
        <p>How it works:
        <br />1. Create a folder in Google Drive and share it. 
        <br />2. Upload a file in Google Drive folder.
        <br />3. Add file name in Wordpress Admin >> Media >> Google Drive Media Library >> Mapping File.
        <br />4. Go to menu Google Drive folder in Wordpress Admin >> Media >> Library. Now you can see your Google Drive file in preview.
        </p>
    </div> <!-- end of tab 1 -->

    <div id="tabs-2">
        <form id="frmMappingFile" name="frmMappingFile" method="post" action="<?php echo $currentPath ?>includes/process.php">
        <table>
            <tr>
                <td>File Name</td>
                <td>:</td>
                <td>
                    <input type="text" name="mappingFileName" title="eg: file.jpg" size="80" id="mappingFileName">
                </td>
            </tr>
            <tr>
                <td>File ID</td>
                <td>:</td>
                <td>
                    <input type="text" name="mappingFileID" title="eg: 0B4fIuYID3dr-aHY2eS1NcHdMeEU" size="80" id="mappingFileID">
                </td>
            </tr>
            <tr>
                <td>File Description</td>
                <td>:</td>
                <td>
                    <input type="text" name="mappingFileDescription" title="eg: file description" size="80" id="mappingFileDescription">
                </td>
            </tr>
        </table>

        <?php $mappingFileNonce = wp_create_nonce( "mapping-file-nonce" ); ?>
        <input type="hidden" name="mapping-file-nonce" id="mapping-file-nonce" value="<?php echo $mappingFileNonce ?>">

        <p style="margin-left: 120px;">
            <button type="submit" id="btnSaveMappingFile"><?php _e('Save')?>
                <img src="<?php echo $currentPath ?>images/preloader-flat.gif" id="imgLoadingButton" style="display: none;">
            </button>
        </p>
        </form>
    </div> <!-- end of tab 2 -->
	
    <div id="tabs-3">
        <form id="frmMappingFolder" name="frmMappingFolder">
        <table>
            <tr>
                <td>Google Drive Folder</td>
                <td>:</td>
                <td>
                    <input type="text" id="mappingFolder" name="mappingFolder" value="<?php echo $mappingFolder ?>"
                        title="eg: 0CoQaLQyW8F1cT1Y3OXZxxxxxXxS" size="40">
                </td>
                <td><p style="font-size: 12px; color: blue;">&nbsp;&nbsp;
                    <a href="#" id="hrefFolderDocumentation">What's this?</a></p></td>
            </tr>
        </table>

        <?php $mappingFolderNonce = wp_create_nonce( "mapping-folder-nonce" );?>
        <input type="hidden" name="mapping-folder-nonce" id="mapping-folder-nonce" value="<?php echo $mappingFolderNonce ?>" >
        <p style="margin-left:140px;">
            <button id="btnSaveMappingFolder"><?php _e('Save') ?>
                <img src="<?php echo $currentPath ?>images/preloader-flat.gif" id="imgFolderButton" style="display: none;">
            </button>
        </p>
        </form>

        <div id="folderDocumentation" style="display: none; background: whitesmoke; padding: 10px;">
            <h4>To find Google Drive folder:</h4>
            <ol>
                <li>Locate and select the <strong>folder </strong>you wish to share.</li>
                <li>Click <strong>Share >> Share</strong> from menu.
                    <div class="leftImage" style="margin-top: 0pt;">
                    <img width="600px" height="auto" src="<?php echo $currentPath ?>/images/documentation/folder-share.png" alt="Screenshot of Google Drive">
                    </div>
                </li>
                <br>
                <li><strong>Sharing settings</strong> dialog box will appear. Select and copy Google Drive folder like in red highlight.
                    <br>Please make sure you set up folder access as <strong>Public on the web</strong>.
                    <div class="leftImage" style="margin-top: 0pt;">
                        <img width="600px" height="auto" src="<?php echo $currentPath ?>/images/documentation/folder-id.png" alt="Screenshot of Google Drive">
                    </div>
                </li>
            </ol>
        </div>

    </div> <!-- end of tab 3 -->

</div> <!-- end of tabs -->