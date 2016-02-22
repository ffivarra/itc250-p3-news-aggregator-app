<?php

//n_index.php
//config

session_start();

require 'inc_0700/config_inc.php';

$config->titleTag = 'News';
$config->banner = 'NEWS';

//header

get_header();

//for each loop and date_diff function

/*$datetime1 = new DateTime("10:00:00");
$datetime2 = new DateTime(date("h:i:s"));
//$interval = $datetime1->diff($datetime2);
//$hours   = $interval->format('%h'); 
//$minutes = $interval->format('%i');
//echo 'Diff. in minutes is: '.($hours * 60 + $minutes); 
$interval = date_diff($datetime1,$datetime2);
$hours   = $interval->format('%h'); 
$minutes = $interval->format('%i');
$seconds = $interval->format('%s');
$diffmin = ($hours * 60 + $minutes + $seconds/60);
echo $diffmin;
//echo $diff->format('%h:%i:%s'); */


$myNewsList = new Newslist();


foreach($myNewsList as $mnl)

	{//get date 
	$datetime[$mnl] = new DateTime(date("h:i:s"));
	if ($mnl=1){$interval = new DateTime("0:0:0");} 
	else {
	$interval = date_diff($datetime[$mnl],$datetime[$mnl-1]);
	}
	$hours   = $interval->format('%h'); 
	$minutes = $interval->format('%i');
	$seconds = $interval->format('%s');
	$diffmin = ($hours * 60 + $minutes + $seconds/60);
	
		if ($diffmin>10)
		{//create an instance of constructor
		echo $diffmin;
		$myNewsList = new Newslist();
	
		} //else
		
	}//end for each loop
	
get_footer();


class NewsList
{
	public $CategoryID;
	public $CategoryName;
	public $CategoryDescription;

	//create constructor function

	public function __construct()//deleted $id variable
	{

		//$this->CategoryID = (int)$id;

		//SQL Statement

		$sql = "SELECT * FROM p3_categories";

		//IDB::conn() creates a shareable database connection viea a singleton class
		$result = mysqli_query(IDB::conn(), $sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

		if(mysqli_num_rows($result) > 0)
			{#there are records - present data
			while($row = mysqli_fetch_assoc($result))
				{//pull data from array
				
				$this->CategoryID = $row['CategoryID'];
				$this->CategoryName = $row['CategoryName'];
				$this->CategoryDescription = $row['CategoryDescription'];
				
				//echo data
				echo '<p>';
	   			echo '<b><a href="news_view.php?id=' . $this->CategoryID . '">' . $this->CategoryName . '</a></b><br />';
	   			echo $this->CategoryDescription;
	   			//echo '<a href="' . $row['FeedURL'] . '">';
	   			echo '<p>';
	   			
				} //end while statement
			
			
			} //end if statement


		@mysqli_free_result($result);	

	} //end function

} //end class


?>
