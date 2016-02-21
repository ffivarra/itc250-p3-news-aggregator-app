<?php 
//news_view.php
//config

require 'inc_0700/config_inc.php';

//header
get_header();

//set id in order to call feed
if(isset($_GET['id']) && (int)$_GET['id'] > 0){//get data
$id = $_GET['id'];
}
else
{//bad data, redirected
    header('Location:index.php');
}

//calling constructor for News class to obtain the feed
$myNews = new News($id);


//footer
get_footer(); 

//create class to get News Feed

class News
{
	public $CategoryID;
	public $FeedName;
	public $FeedDescription;

	//create constructor function

	public function __construct($id)
	{

		$this->CategoryID = (int)$id;

		//SQL Statement

		$sql = "SELECT * FROM p3_feeds WHERE CategoryID = $id";

		//IDB::conn() creates a shareable database connection viea a singleton class
		$result = mysqli_query(IDB::conn(), $sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

		if(mysqli_num_rows($result) > 0)
			{#there are records - present data
			while($row = mysqli_fetch_assoc($result))
				{//pull data from array
	
				$this->FeedName = $row['FeedName'];
				$this->FeedDescription = $row['FeedDescription'];
				
				//echo data
				echo '<p>';
				echo '<b>' . $this->FeedName . '</b><br />';
	   			echo $this->FeedDescription;
	   			//echo '<a href="' . $row['FeedURL'] . '">';
	   			echo '<p>';
	   			
				} //end while statement
			
			
			} //end if statement

		echo '<p><a href="n_index.php">BACK</a></p>';
		@mysqli_free_result($result);	

	} //end function

} //end class

?>



