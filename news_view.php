<?php 
//news_view.php
//config

require 'inc_0700/config_inc.php';

//header
get_header();


//set id in order to call feed
if(isset($_GET['id']) && (int)$_GET['id'] > 0)
	{//get data
	$id = $_GET['id'];

	}else
	{//bad data, redirected
    header('Location:index.php');
	}


if(!isset($_SESSION['News']))
{//create session


//calling constructor for News class to obtain the feed
$myNews = new NewsView($id);

$time = getdate();
echo 'session time: ' . date("h:i:sa");

//echo date_diff($time,getdate());

}


//footer
get_footer(); 



//create class to get News Feed

class NewsView
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
				$this->FeedURL = $row['FeedURL'];
				$this->FeedID = $row['FeedID'];
				
				/*$request = ' " ' . $this->FeedURL . ' " ';
  				$response = file_get_contents($request);
  				$xml = simplexml_load_string($response); */
				
				//echo data
				echo '<p>';
				//echo '<b><a href=' . $this->FeedURL . '>' . $this->FeedName . '</a></b><br/>';
				echo '<b><a href="news_list.php?id=' . $this->FeedID . '">' . $this->FeedName . '</a></b><br />';
	   			echo $this->FeedDescription;
	   			echo '<p>';
	   			
	   			//feed
	   			
	   			/*$request = $this->FeedURL;
  				$response = file_get_contents($request);
  				$xml = simplexml_load_string($response);
  				print '<h1>' . $xml->channel->title . '</h1>';
  					foreach($xml->channel->item as $story)
  						{
    					echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
    					echo '<p>' . $story->description . '</p><br /><br />';
 						 } */
	   			
				} //end while statement
			
			
			} //end if statement

		echo '<p><a href="n_index.php">BACK</a></p>';
		@mysqli_free_result($result);	

	} //end function

} //end class

?>



