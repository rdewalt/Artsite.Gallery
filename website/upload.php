<?php
        $tags = str_replace("|",", ",'b|i|u|size|color|center|quote|url');

require_once 'header.inc';
include_once 'library.inc';
?>
<div style="clear:both; margin: 5px; padding: 5px;">
<script type="text/javascript">
function DoForm(){
 if (document.theForm.MyButton.value=="Upload File")
        { document.theForm.MyButton.value="Processing..."; return true;}
        else
        return false;
}

</script>
<?php
if (isset($_GET['e'])) {
if ($_GET['e']=="Success") { print "<h1 class='success'>Image Upload Successful.</h1>";}
if ($_GET['e']=="BadThing") { print "<h1>Upload Failed, Check Things, Try Again.</h1>";}
}
?>
<h2>Image Upload: Text to go below later...</h2>
<p class="upboxspan">Currently Acceptable formats: (images) <b>GIF/JPG/PNG</b>
<br><br><b>Formats coming soon:</b> (prose) TXT/RTF  (audio) MP3/OGG.  Maybe more formats later.

<br><br>
<form name="theForm" action="up.php" method="post" onSubmit="return DoForm();" enctype="multipart/form-data">
<style>
td { background-color: #ddddff; }
span.upboxspan { background-color: #ddddff; }
input { background-color: #ffffff; }
select { background-color: #ffffff; }
</style>
<table border="0" cellpadding=2 cellspacing=0>
<tr><td align=right valign=top><span class="upboxspan">File:</span></td>
<td align=left valign=top colspan="4">
<input type=hidden name="MAX_FILE_SIZE" value="<?=1024*1024*5?>">
<input type=hidden name="funct" value="Submit">
<input type="file" name="Image">
</td></tr>
<tr><td align=right valign=top><span class="upboxspan">Custom Thumbnail:</span></td>
<td align=left valign=top colspan="4">
<input type="file" name="Thumbnail"> (Optional)
</td></tr>
<tr><td align=right><span class="upboxspan">Image Category:</span></td><td><select name="category" id="artCategory">
<?
    $dbh=getDBH();
    $sql="select Cat_ID,Cat_Name from categories order by Cat_Name;";
    if ($stmt = $dbh->prepare($sql)) {
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) > 0) {
        	foreach ($foo as $f) {
        		print "<option value='".$f['Cat_ID']."'>".$f['Cat_Name']."</option>";
        	}
        }
    }
?>
</select></td>

<td align=right><span class="upboxspan">Image Folder:</span></td><td colspan=3><select name="Folder" id="artFolder">
<option value="0">- No Folder -</option>
<?
    $dbh=getDBH();
    $sql="select FolderID,FolderName from ArtFolders where UserID=:UserID";
    if ($stmt = $dbh->prepare($sql)) {
        $user_id = $_SESSION['user_id'];
        $stmt->bindParam(':UserID', $user_id);
        $stmt->execute();
        $foo=$stmt->fetchAll();
        if (count($foo) > 0) {
            foreach ($foo as $f) {
                print "<option value='".$f['FolderID']."'>".$f['FolderName']."</option>";
            }
        }
    }
?>
</select></td></tr>
<tr><td align=right valign=top><span class="upboxspan">Image Title:</span></td><td align=left valign=top colspan="4"><input type="text" name="ImageTitle" size=50 maxlength=255></td></tr>
<tr><td align=right valign=top><span class="upboxspan">Keywords:</span></td><td align=left valign=top colspan="4"><input type="text" name="ImageKeywords" size=50></td></tr>

<tr><td align=right><span class="upboxspan">Description:</span></td><td colspan="3" valign="top"><textarea name="desc" cols="100" rows="10" wrap="hard"></textarea></td>
</tr>
<tr><td align=right><span class="upboxspan">Is This NSFW:</span></td><td colspan="3"><input type="Radio" name="Adult" value="N" CHECKED>No  <input type="Radio" name="Adult" value="Y">Yes</td>
</tr><tr><td align=right><span class="upboxspan">Skip Recent Uploads:</span></td><td colspan="3"><input type="Checkbox" name="skip" value="Y"> Image will NOT show on the front page when checked.</td></tr>
<tr><td valign=top align=center colspan="4">
<input type=submit value="Upload File" name="MyButton"></td></tr>
</table>
</form>
</div>

<br clear="both"><b>BBCODE tags available in Description:</b> <?=$tags?>
<br><br><b>"Skip Recent Uploads"</b> if checked, means the image will only show up on YOUR artist page, not in the "last 50" or any other public listing.  This way you can use it for uploading sketches or small things that you don't want
to show up on the main front page.
<br><br><B>"Custom Thumbnail"</B> Attach a thumbnail that is at most 150x150px.  If you do not, the site will automatically generate a thumbnail for your submission.
<br><br><b>"Keywords"</b> As many as you deem necessary, within limits,  COMMA DELIMTIED please
<br><br><B>"Image Folder"</B> Placeholder for now until I get that part done, but basically allows you to organize your uploads into folders.
<br><br>There is no <b>"Adult"</b> set of categories.  Do mark anything Adult, or even questionably NSFW properly.

<?php
require_once 'footer.inc';
?>
