<?php
//n_index.php
//config

require 'inc_0700/config_inc.php';

$config->titleTag = 'News';
$config->banner = 'NEWS';

//header

get_header();


$myNewsList = new Newslist();

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
