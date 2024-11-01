<?php
/*
Plugin Name: WP Featured Listings
Plugin URI: http://www.jaredritchey.com
Description: <strong>WP Featured Listings 0.9.8 </strong> -- This plugin was designed to assist real estate professionals with the marketing of their listings with their blog.  By adding featured listings directly into your WordPress posts you increase the relevance of the content associated with your listings and provide a way for search engines to index content relative to your listings.  The WP Featured Listing plugin requires <a href="http://www.open-realty.org" title="Open Realty" target="_blank">Open Realty</a> to function
Author: Jared Ritchey and Damian Danielczyk
Version: 0.9.9
Author URI: http://www.jaredritchey.com/wp-featured-listings-plugin/
*/

$wpfeatured_version = '0.9.9';

// The full path from root to your OpenRealty install.
$wpfeatures_openrealty_path = "/home/livedemo/public_html/listings/";

//////////////////////////////////////////////////
/* Set the action to show the main options page */
//////////////////////////////////////////////////

//////////////////////////////
/* admin_menu hook function */
//////////////////////////////

add_action('admin_menu', 'show_featured_listings_option');
add_action('activate_wp-featured/featured-listings.php', 'featured_install');
add_filter('the_content', 'check_content');

function featured_install()
{
    global $wpdb;
    $table = $wpdb->prefix."featured_templates";
    $structure = " CREATE TABLE $table (
					`id` INT NOT NULL AUTO_INCREMENT ,
					`listings_num` INT NOT NULL,
					`template_name` VARCHAR(255) NOT NULL,
					`template_file` VARCHAR(250) NOT NULL,
					`template_folder` VARCHAR(255) NOT NULL,
					`criterias` TEXT NOT NULL ,
					`created_at` DATETIME NOT NULL,
					PRIMARY KEY ( `id` )
					) ENGINE = MYISAM ";
    $wpdb->query($structure);
}

function show_featured_listings_option() {
//add on options page a link for our addon's admin
//add_options_page will make to show up our admin on the options tag on WP

if (function_exists('add_options_page')) {
	add_options_page("WP Featured Listings v{$wpfeatured_version} - Main", 
	"Featured Listings", 8, "featured-listings", 'featured_listings_admin_options');
	}
}


/////////////////////////////////////////////////
/* update the search criterias in the database */
/////////////////////////////////////////////////

function update_search_criterias()
	{
		global $wpdb,$wpfeatures_openrealty_path;
	    $table = $wpdb->prefix."featured_templates";
		
	$template_number=null;
	if(isset($_POST['template_number']) && $_POST['template_number'] > 0)
		$template_number=$_POST['template_number'];
		
		//$template_folder = mysql_escape_string($_POST['folder_to_include']);
		$template_folder = $wpfeatures_openrealty_path;
		$number_of_listings = mysql_escape_string($_POST['number_of_listings']);
		$template_file = mysql_escape_string($_POST['template']);
		$template_name = mysql_escape_string($_POST['template_name']);
		
		$data_arr = array();
		$data_arr[] = array(mysql_escape_string($_POST['search_criteria_1_name']), mysql_escape_string($_POST['search_criteria_1_value']));
		$data_arr[] = array(mysql_escape_string($_POST['search_criteria_2_name']), mysql_escape_string($_POST['search_criteria_2_value']));
		$data_arr[] = array(mysql_escape_string($_POST['search_criteria_3_name']), mysql_escape_string($_POST['search_criteria_3_value']));
		
		$data = serialize($data_arr);
		
		
		if($template_number){

			$wpdb->query("UPDATE $table SET listings_num = $number_of_listings, template_name = '$template_name', template_file = '$template_file', template_folder= '$template_folder', criterias='$data' WHERE id = $template_number");
			
			echo '<div id="message" class="updated fade">';
            echo '<p>Options Updated</p>';
            echo '</div>';
			
		}else{
			$sql = "INSERT INTO $table SET listings_num = $number_of_listings, template_name = '$template_name', template_file = '$template_file', template_folder= '$template_folder', criterias='$data', created_at = '".date("Y-m-d H:i:s")."'";
			//echo $sql;
			$wpdb->query($sql);
			$template_number = mysql_insert_id();
			
			echo '<div id="message" class="updated fade">';
            echo '<p>Template created, unique template tag is: {featured_'.$template_number.'}</p>';
            echo '</div>';
		}
		return $template_number;
}


function print_menu()
{
		global $wpdb;
	    $table = $wpdb->prefix."featured_templates";
	$query = "SELECT * FROM $table ORDER BY created_at DESC";
	$items =$wpdb->get_results($query);

		if ($items) :
			
			echo '<table class="widefat" style="width: 800px;">
				<thead>
				<tr>
			    <th scope="col" style="width: 50px; text-align: center;">ID</th>
			    <th scope="col" style="width: 300px; text-align: left;">Template Name</th>
			    <th scope="col" style="width: 250px; text-align: left;">Template Tag</th>
			    <th scope="col" style="width: 150px; text-align: left;">Created At</th>
				<th scope="col" style="width: 50px; text-align: left;">Action</th>
			  	</tr>
				</thead><tbody id="the-list">
				<tr id="page-10" class="alternate">';
			
			foreach($items as $item){		
				
				echo  '<td scope="row" style="text-align: center">'.$item->id.'</td>
					<td scope="row" style="text-align: left;"><a class="edit" href="?page=featured-listings&edit_id='.$item->id.'">'.$item->template_name.'</a></td>
					<td style="text-align: left;">{featured_'.$item->id.'}</td>
					<td style="text-align: left;">'.$item->created_at.'</td>
					<td style="text-align: left;"><a href="?page=featured-listings&delete_id='.$item->id.'">Delete</a></td></tr>';
				
			}
			
			echo "
		  </tbody>
		</table>";			
		endif;
	
	
}

	////////////////////////////////
	/* used when displaying admin */
	////////////////////////////////

	function featured_listings_admin_options()
		{

		if($_GET['delete_id'])
			delete_template($_GET['delete_id']);

		if($_POST['form'] == 'sent')
			{
			$template_number = update_search_criterias();
			featured_listings_options_form();
			print_menu();
			}else
			{
			featured_listings_options_form($_GET['edit_id']);
			print_menu();
		}
	}

	//check the contents for our tags which we want

	
	function delete_template($id)
	{
		global $wpdb;
	    $table = $wpdb->prefix."featured_templates";
	$query = "DELETE FROM $table WHERE id=$id LIMIT 1";
	$wpdb->query($query);
	
		
	echo '<div id="message" class="updated fade">';
    echo '<p>Template ID: '.$id.' has been deleted.</p>';
    echo '</div>';	
	}
	

	///////////////////////////
	/* Look for trigger text */
	///////////////////////////

	function check_content($content) 
		{
		preg_match_all('/{featured_([^{}]*?)}/',$content,$tags_found);
		$tags_found = $tags_found[1];

		foreach($tags_found as $tag)
			$content=str_replace("{featured_".$tag."}",wp_template_show_featured_listings($tag),$content);
		return $content;

	/* if (strpos($content, '<!-- ddfm' . $this->inst . ' -->') !== FALSE) 
		{
		$content = preg_replace('/<p>\s*<!--(.*)-->\s*<\/p>/i', "<!--$1-->", $content);
		$content = str_replace('<!-- ddfm' . $this->inst . ' -->', $this->generate_data(), $content);
		} */
		//return $content;

	}



///////////////////////////////////
/* form for the search criterias */
///////////////////////////////////

function featured_listings_options_form ($template_number = null) 
	{
		global $wpdb;
	    $table = $wpdb->prefix."featured_templates";
	
	$template='';
	//$folder_to_include ... Open Realty folder to include
    if($template_number){
	$sql = "SELECT * FROM $table WHERE id=$template_number";
	$res = $wpdb->get_row($sql);
	
	$data = unserialize($res->criterias);
	
	$search_criteria_1_name = $data[0][0];
	$search_criteria_1_value = $data[0][1];
	$search_criteria_2_name = $data[1][0];
	$search_criteria_2_value = $data[1][1];
	$search_criteria_3_name = $data[2][0];
	$search_criteria_3_value = $data[2][1];
	$number_of_listings=$res->listings_num;
	$template_file=$res->template_file;
	$folder_to_include = $res->template_folder;
	$template_name = $res->template_name;
	
	}
	$templates='<select name="template">';
	foreach(glob("../wp-content/plugins/wp-featured/template/*.html") as $file)
		if($template_file=='')
			if($file=="../wp-content/plugins/wp-featured/template/featured.html")
				$templates.='<option value="'.substr($file,1).'" selected>'.substr($file,strlen("../wp-content/plugins/wp-featured/template/"));
			else
				$templates.='<option value="'.substr($file,1).'">'.substr($file,strlen("../wp-content/plugins/wp-featured/template/"));
		else
			if(substr($file,1)==$template_file)
				$templates.='<option value="'.substr($file,1).'" selected>'.substr($file,strlen("../wp-content/plugins/wp-featured/template/"));
			else
				$templates.='<option value="'.substr($file,1).'">'.substr($file,strlen("../wp-content/plugins/wp-featured/template/"));
	$templates.='</select>';

	echo '<div class="wrap">';
    echo '<h2>Featured Listings v-0.9.8</h2>';
	echo '
<fieldset class="options">
<table width="89%" border="0" cellspacing="0" cellpadding="0">
    <tr>
    <td colspan="4"><p style="font-size:12px">In the section below you should follow strict attention to detail as incorrect entries will produce incorrect results. For the &quot;Field Name&quot; you must enter the field name exactly as you have it in Open Realty and the Field Value must be a numerica value or actual field value you want to sort by. If you enter for instance Baths and then 5 your featured listings results will only return listings with that criteria. Wrong values produce wrong results or no resules so please be cautious and use the log included in the documentation. </p></td>
  </tr>
<tr><td colspan="4"><form action="?page=featured-listings" method="post"><input type="submit" name="submit" value="Create new template" /></form><br/></td></tr>

<form method="post">
<tr>
    <td width="26%" align="right" valign="top"><strong><u>Template Name</u></strong>:&nbsp;</td>
    <td width="25%" align="left" valign="top">&nbsp;<input type="text" name="template_name" value="'.$template_name.'" /></td><td align="center" valign="top" colspan="2">&nbsp;</td>
  </tr>  

<tr>
    <td width="26%" align="right" valign="top"><strong>Field Name 1</strong>:&nbsp;</td>
    <td width="25%" align="left" valign="top">&nbsp;<input type="text" name="search_criteria_1_name" value="'.$search_criteria_1_name.'" /></td>
    <td width="16%" align="right" valign="top"><strong>Field Value 1:&nbsp;</strong></td>
    <td width="33%" align="left" valign="top">&nbsp;<input type="text" name="search_criteria_1_value" value="'.$search_criteria_1_value.'" /></td>
  </tr>
  <tr>
    <td valign="top" align="right"><strong>Field Name 2</strong>:&nbsp;</td>
    <td valign="top" align="left">&nbsp;<input type="text" name="search_criteria_2_name" value="'.$search_criteria_2_name.'" /></td>
    <td valign="top" align="right"><strong>Field Value 2:&nbsp;</strong></td>
    <td valign="top" align="left">&nbsp;<input type="text" name="search_criteria_2_value" value="'.$search_criteria_2_value.'" /></td>
  </tr>
  <tr>
    <td valign="top" align="right"><strong>Field Name 3</strong>:&nbsp;</td>
    <td valign="top" align="left">&nbsp;<input type="text" name="search_criteria_3_name" value="'.$search_criteria_3_name.'" /></td>
    <td valign="top" align="right"><strong>Field Value 3:&nbsp;</strong></td>
    <td valign="top" align="left">&nbsp;<input type="text" name="search_criteria_3_value" value="'.$search_criteria_3_value.'" /></td>
  </tr>
  <tr>
    <td colspan="4" align="right" valign="top">&nbsp;</td>
  </tr>';
  /*<tr>
    <td valign="top" align="right"><strong>Open Realty Folder:</strong>&nbsp;</td>
    <td valign="top" align="left">&nbsp;<input type="text" name="folder_to_include" value="'.$folder_to_include.'" /></td>
    <td colspan="2" align="left" valign="top"><span style="padding:0px; margin-bottom:8px; font-size:11px; display:block;">This field value is a relative value. If your blog is in /blog and your Open Realty installation is on the same level titled /openrealty then the folder path should be ../openrealty/ (add trailing slash)</span></td>
  </tr>
  <tr>';*/
   echo '<td valign="top" align="right"><strong>Number of Listings:</strong>&nbsp;<br /></td>
    <td valign="top" align="left">&nbsp;<input type="text" name="number_of_listings" value="'.$number_of_listings.'" /></td>
    <td colspan="2" align="left" valign="top"><span style="padding:0px; margin-bottom:8px; font-size:11px; display:block;"> <img src="../wp-content/plugins/wp-featured/images/warning.jpg" alt="warning" /> <strong>Important!</strong> For best results, you should set this number to an optimal number between 1 and 10. By selecting 10, you will produce 10 featured results which could make a page load slow depending on the featured template you use. For best results on most blogs set this from between 1 to 5.</span></td>
  </tr>
  <tr>
    <td valign="top" align="right"><strong>Template to use:</strong> &nbsp;</td>
    <td valign="top" align="left">&nbsp;<label>'.$templates.'</label></td>
    <td colspan="2" align="right" valign="top">&nbsp;      <input type="submit" name="submit" value="Submit" /></td>
  </tr>
</table>
<input type="hidden" name="template_number" value="'.$template_number.'">
<input type="hidden" name="form" value="sent">
</form>
</fieldset>
';
echo '</div>';
}

/* ////////////////////////////////////////////////////////////////////
wp_template_show_featured_listings... 
use this in template to show the featured listings
param template_number ... it's the template number used on {featured_x}
//////////////////////////////////////////////////////////////////// */
function wp_template_show_featured_listings($template_number)
	{
	global $wpdb;
    $table = $wpdb->prefix."featured_templates";
	$number_of_listings=5;	
	$sql = "SELECT * FROM $table WHERE id=$template_number";
	$res = $wpdb->get_row($sql);
	if(!$res) return "<span> The Featured Listing No Longer Exists </span>";
	$data = unserialize($res->criterias);
	$search_criteria_1_name = $data[0][0];
	$search_criteria_1_value = $data[0][1];
	$search_criteria_2_name = $data[1][0];
	$search_criteria_2_value = $data[1][1];
	$search_criteria_3_name = $data[2][0];
	$search_criteria_3_value = $data[2][1];
	$number_of_listings=$res->listings_num;
	$template_file=$res->template_file;
	$folder_to_include = $res->template_folder;
	$template_name = $res->template_name;

	//include the include/common.php config file 
	require_once($folder_to_include.'include/common.php');
	global $config;
	
	$featured_listings='';
	for($i=1;$i<=3;$i++)
		{
		//use $$ to get the values
		$name='search_criteria_'.$i.'_name';
		$value='search_criteria_'.$i.'_value';
		$empty=0;
		if(strlen($$name)==0 || strlen($$value)==0)
			$empty=1;

		if(strlen($featured_listings)>0)
			{
			if($empty==0)
				{
				$sql="select listingsdb_id from ".$config['table_prefix']."listingsdbelements where listingsdbelements_field_name='".$$name ."' and listingsdbelements_field_value='".$$value."' and listingsdb_id in (".$featured_listings.") order by rand()";
				//clean up the featured listings
				$featured_listings='';
				}
			else
				continue;
			}
		else
			{
			if($empty==0)
				{
				$sql="select listingsdb_id from ".$config['table_prefix']."listingsdbelements where listingsdbelements_field_name='".$$name ."' and listingsdbelements_field_value='".$$value."' order by rand()";
				}
			else
				{
				if($i<3)
					continue;
				else
					{
					echo("there aren't listings with those search criterias");
					break;
					}
				}
			}
		$select=mysql_query($sql);
		echo(mysql_error()."<br>");
		while($row=mysql_fetch_array($select))
		$featured_listings.=$row[0].", ";
		$featured_listings=substr($featured_listings,0,-2);
		}
	$listings=explode(", ",$featured_listings);
	if(count($listings)<=$number_of_listings)
	$number_of_listings=count($listings);

/* ////////////////////////////////////////////////////////////////////////////
if we have a template defined for that tag, meaning a file with name
featured_x.html, where x is the tag number, use that file... 
if we don't have one, use the default one to have a template for a tag number,
just add in the template folder of the addon a file called featured_x.html, 
where x is the tag number 
//////////////////////////////////////////////////////////////////////////// */

//add the user's option
	if($template_file!='')
		if(file_exists($template_file))
			$template=file_get_contents($template_file);
	else
	$template=file_get_contents("wp-content/plugins/wp-featured/template/featured.html");
	else
		
	if(file_exists("wp-content/plugins/wp-featured/template/featured_".$template_number.".html"))
	$template=file_get_contents("wp-content/plugins/wp-featured/template/featured_".$template_number.".html");
	else
	
	$template=file_get_contents("wp-content/plugins/wp-featured/template/featured.html");
	preg_match_all('/{listing_field_([^{}]*?)_value}/',$template,$tags_found);
	$tags_found = $tags_found[1];
	$template_with_listings='';
	template_with_listing;
	for($i=0;$i<$number_of_listings;$i++)
		{
		//echo($listings[$i]."<br>");		
		$template_with_listing=$template;
		if(strlen($listings[$i])<=0)
			continue;
		foreach($tags_found as $tag)
			{
			$tag_value='';
			if($tag=="listingID")
				$tag_value=$listings[$i];
			if($tag=="photo")
				{
				$photo=$folder_to_include."images/nophoto.gif";
				$sql="select listingsimages_file_name from ".$config['table_prefix']."listingsimages where listingsdb_id=".$listings[$i];
				$select=mysql_query($sql);
				echo(mysql_error());
				while($row=mysql_fetch_array($select))
					$photo=$row[0];
				if($photo!=$folder_to_include."images/nophoto.gif" )
					$photo=$config["listings_view_images_path"]."/".$photo;
				$tag_value=$photo;
				}
			if($tag_value=='')
				{
				$underscore=strpos($tag,"_short");
				if($underscore!==false)
					$tag=substr($tag,0,$underscore);

///////////////////////////////////////////////////////////
/*  use this to take the name of the field without "_short"
we use this to take just the first 100 letters from the 
fields like remarks, description field_name_short */
///////////////////////////////////////////////////////////
				
			$sql="select listingsdbelements_field_value from ".$config['table_prefix']."listingsdbelements where listingsdbelements_field_name='".$tag."' and listingsdb_id=".$listings[$i];
				//echo($sql);
				$select=mysql_query($sql);
				while($row=mysql_fetch_array($select))
					$tag_value=$row[0];
				if($underscore!==false)
					{
					$tag.="_short";
					$tag_value=substr($tag_value, 0 , 100);
					}
				}
			$template_with_listing=str_replace("{listing_field_".$tag."_value}",$tag_value,$template_with_listing);
			}
			$sql = "SELECT listingsdb_title FROM ".$config['table_prefix']."listingsdb WHERE listingsdb_id=".$listings[$i]." LIMIT 1";
			$title = mysql_fetch_assoc(mysql_query($sql));
			$temp_folder_to_include = $folder_to_include;
			if($folder_to_include[count($folder_to_include-1)] == "/") $temp_folder_to_include = $folder_to_include.'/'; 
			$url_data = explode("/", $temp_folder_to_include);
			$template_with_listing = str_replace("{field_title}", $title['listingsdb_title'], $template_with_listing);
		$template_with_listing = str_replace("{link_to_listing}", "http://".$_SERVER['HTTP_HOST']."/".$url_data[count($url_data)-2].'/listing-'.str_replace(array("_", " "), "-", $title['listingsdb_title']).'-'.$listings[$i].'.html', $template_with_listing);
		$template_with_listings.=$template_with_listing."<br /> ";
		}
	return $template_with_listings;
	}
?>