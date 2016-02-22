<?php
//news_list.php

require 'inc_0700/config_inc.php';



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


	$myFeed = new FeedView($id);
	}

get_footer();




class FeedView
{
	public $FeedID;
	public $FeedName;
	public $response;
	public $request;
	public $xml;

	//create constructor function

	public function __construct($id)
	{

		$this->FeedID = (int)$id;

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
				
				//$request = ' " ' . $this->FeedURL . ' " ';
				$request = "https://news.google.com/news?cf=all&hl=en&pz=1&ned=us&topic=tc&output=rss";
  				$response = file_get_contents($request);
  				$xml = simplexml_load_string($response); 
				
				//echo data
				echo '<p>';
	   			echo $this->FeedName;
	   			echo '<p>';
	   			
	   			//feed
	   			
	   			$request = $this->FeedURL;
  				$response = file_get_contents($request);
  				$xml = simplexml_load_string($response);
  				print '<h1>' . $xml->channel->title . '</h1>';
  					foreach($xml->channel->item as $story)
  						{
    					echo '<a href="' . $story->link . '">' . $story->title . '</a><br />'; 
    					echo '<p>' . $story->description . '</p><br /><br />';
 						 } 
	   			
				} //end while statement
			
			
			} //end if statement

		echo '<p><a href="n_index.php">BACK</a></p>';
		@mysqli_free_result($result);	

	} //end function

} //end class

?>
