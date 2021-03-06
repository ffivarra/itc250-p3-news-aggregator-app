<?php
/**
 * demo_edit.php is a single page web application that allows us to select a customer
 * and edit their data
 *
 * This page is based on demo_postback.php as well as first_crud.php, which is part of the 
 * nmPreload package
 *
 * Any number of additional steps or processes can be added by adding keywords to the switch 
 * statement and identifying a hidden form field in the previous step's form:
 *
 *<code>
 * <input type="hidden" name="act" value="next" />
 *</code>
 * 
 * The above code shows the parameter "act" being loaded with the value "next" which would be the 
 * unique identifier for the next step of a multi-step process
 *
 * @package nmCommon
 * @author Bill Newman <williamnewman@gmail.com>
 * @version 1.12 2012/02/27
 * @link http://www.newmanix.com/
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License ("OSL") v. 3.0
 * @todo add more complicated checkbox & radio button examples
 */

# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/

//END CONFIG AREA ----------------------------------------------------------

# Read the value of 'action' whether it is passed via $_POST or $_GET with $_REQUEST
if(isset($_REQUEST['act'])){$myAction = (trim($_REQUEST['act']));}else{$myAction = "";}

switch ($myAction) 
{//check 'act' for type of process
	case "edit": //2) show first name change form
	 	editDisplay();
	 	break;
	case "update": //3) Change customer's first name
		updateExecute();
		break; 
	default: //1)Select Customer from list
	 	selectFirst();
}

function selectFirst()
{//Select Customer
	global $config;
	$config->loadhead .= '<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
			function checkForm(thisForm)
			{//check form data for valid info
				if(empty(thisForm.FeedID,"Please Select a Feed.")){return false;}
				return true;//if all is passed, submit!
			}
	</script>
	';
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>';

	$sql = "select * from p3_feeds";
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if (mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		echo '<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">';  //TWO COPIES OF THIS LINE IN ORIG!!
		echo '<table align="center" border="1" style="border-collapse:collapse" cellpadding="3" cellspacing="3">';
		echo '<tr>
				<th>FeedID</th>
				<th>Feed Name</th>
				<th>Feed Description</th>
				<th>Feed URL</th>
			</tr>
			';
		while ($row = mysqli_fetch_assoc($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
			echo '<tr>
					<td>
				 	<input type="radio" name="FeedID" value="' . (int)$row['FeedID'] . '">'
				     . (int)$row['FeedID'] . '</td>
				    <td>' . dbOut($row['FeedName']) . '</td>
				    <td>' . dbOut($row['FeedDescription']) . '</td>
				    <td>' . dbOut($row['FeedURL']) . '</td>
				</tr>
				';
		}
		echo '<input type="hidden" name="act" value="edit" />';
		echo '<tr>
				<td align="center" colspan="4">
					<input type="submit" value="Choose Feed!"></em>
				</td>
			  </tr>
			  </table>
			  </form>
			  ';
	}else{//no records
      echo '<div align="center"><h3>Currently No Feeds in Database.</h3></div>';
	}
	@mysqli_free_result($result); //free resources
	get_footer();
}

function editDisplay()
{# shows details from a single customer, and preloads their first name in a form.
	global $config;
	if(!is_numeric($_POST['FeedID']))
	{//data must be alphanumeric only	
		feedback("id passed was not a number. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect(THIS_PAGE);
	}


	$myID = (int)$_POST['FeedID'];  //forcibly convert to integer

	$sql = sprintf("select * from p3_feeds WHERE FeedID=%d",$myID);
	$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
	if(mysqli_num_rows($result) > 0)//at least one record!
	{//show results
		while ($row = mysqli_fetch_array($result))
		{//dbOut() function is a 'wrapper' designed to strip slashes, etc. of data leaving db
		     $Name = dbOut($row['FeedName']) ;
		     $Description = dbOut($row['FeedDescription']);
		     $URL = dbOut($row['FeedURL']);
		}
	}else{//no records
      //feedback issue to user/developer
      feedback("No such feed. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
	  myRedirect(THIS_PAGE);
	}
	
	$config->loadhead .= '
	<script type="text/javascript" src="' . VIRTUAL_PATH . 'include/util.js"></script>
	<script type="text/javascript">
		function checkForm(thisForm)
		{//check form data for valid info
			if(empty(thisForm.Name,"Please Enter Feed\'s  Name")){return false;}
			if(empty(thisForm.Description,"Please Enter a Description")){return false;}
            if(empty(thisForm.URL,"Please Enter an URL")){return false;}
			return true;//if all is passed, submit!
		}
	</script>';
    
	
	get_header();
	echo '<h3 align="center">' . smartTitle() . '</h3>
	<h4 align="center">Update Feed\'s Name</h4>
	<p align="center">Feed: <font color="red"><b>' . $Name . '</b>
      Description: <font color="red"><b>' . $Description . '</b></font> 
	 URL: <font color="red"><b>' . $URL . '</b></font> 
	<form action="' . THIS_PAGE . '" method="post" onsubmit="return checkForm(this);">
	<table align="center">
	   <tr><td align="right"> Name</td>
		   	<td>
		   		<input type="text" name="Name" value="' .  $Name . '">
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <tr><td align="right">Description</td>
		   	<td>
		   		<input type="text" name="Description" value="' .  $Description . '">
		   		<font color="red"><b>*</b></font> <em>(alphanumerics & punctuation)</em>
		   	</td>
	   </tr>
	   <tr><td align="right">URL</td>
		   	<td>
		   		<input type="text" name="URL" value="' .  $URL . '">
		   		<font color="red"><b>*</b></font> 
		   	</td>
	   </tr>
	   <input type="hidden" name="FeedID" value="' . $myID . '" />
	   <input type="hidden" name="act" value="update" />
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Update Info!"><em>(<font color="red"><b>*</b> required field</font>)</em>
	   		</td>
	   </tr>
	</table>    
	</form>
	<div align="center"><a href="' . THIS_PAGE . '">Exit Without Update</a></div>
	';
	@mysqli_free_result($result); //free resources
	get_footer();
	
}

function updateExecute()
{
	if(!is_numeric($_POST['FeedID']))
	{//data must be alphanumeric only	
		feedback("id passed was not a number. (error code #" . createErrorCode(THIS_PAGE,__LINE__) . ")","error");
		myRedirect(THIS_PAGE);
	}
	
	
	

	$iConn = IDB::conn();//must have DB as variable to pass to mysqli_real_escape() via iformReq()
	
	
	$redirect = THIS_PAGE; //global var used for following formReq redirection on failure

	$FeedID = iformReq('FeedID',$iConn); //calls mysqli_real_escape() internally, to check form data
	$FeedName = strip_tags(iformReq('FeedName',$iConn));
	$FeedDescription = strip_tags(iformReq('FeedDescription',$iConn));
	$URL = strip_tags(iformReq('FeedURL',$iConn));
	
	//next check for specific issues with data
	if(!ctype_graph($_POST['FeedName']))
	{//data must be alphanumeric or punctuation only	
		feedback("Feed Name must contain letters, numbers or punctuation","warning");
		myRedirect(THIS_PAGE);
	}
	
	
	

    //build string for SQL insert with replacement vars, %s for string, %d for digits 
    $sql = "UPDATE p3_feeds set  
    FeedName='%s',
    FeedDescription='%s',
    FeedURL='%s'
     WHERE FeedID=%d"
     ; 
     
     
     
     
    # sprintf() allows us to filter (parameterize) form data 
	$sql = sprintf($sql,$FeedName,$FeedDescription,$FeedURL,(int)$FeedID);

	@mysqli_query($iConn,$sql) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
	#feedback success or failure of update
	if (mysqli_affected_rows($iConn) > 0)
	{//success!  provide feedback, chance to change another!
	 feedback("Data Updated Successfully!","success");
	 
	}else{//Problem!  Provide feedback!
	 feedback("Data NOT changed!","warning");
	}
	myRedirect(THIS_PAGE);
}

