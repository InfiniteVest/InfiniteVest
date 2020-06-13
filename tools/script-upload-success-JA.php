<META http-equiv="Content-Type" content="text/html; charset=utf-8"> 

<?php

	function printErrors($array) {		
		//$array = array_unique($array);
		foreach ($array as $value) {
			echo $value . ' ';
		}	
	}
	
	// Only use for scripts
	function fixSpecialCharacters($script) {
		
		$newString = str_replace("’","'", $script );
		$newString = str_replace("‘","'", $newString );
		$newString = str_replace("…","...", $newString);
		$newString = str_replace("–","-", $newString);
		$newString = str_replace('“','"', $newString);
		$newString = str_replace('”','"', $newString);
		$newString = str_replace('é','e', $newString);
		
		$newString = utf8_encode(nl2br($newString));
		return $newString;
		
	}

?>

<?php require "../header.php"; ?>
	<body>
		<div class="container">
			<div class="span6 offset3">
			<br>
			<a class="button" href="script-upload-JA.php"> &larr; Back to uploader </a> <br/>
			<h2 class="bebas">Please check the below for errors.</h2>
			<?php
	    include "dbConfig.php";
			$con=mysqli_connect($hostName, $userName, $password, $database);
			
			if (mysqli_connect_errno($con))	{
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
			
			mysqli_query($con, "set names 'utf8'");
			mb_internal_encoding('UTF-8');
			mb_http_output('UTF-8');
			mb_http_input('UTF-8');
			
			if(isset($_POST['script-upload'])) {
				/*=================*
				 * NEW EPISODE UPLOAD *
				*=================*/
				
				if(empty($_POST['id'])) {

					$title = $link = $embedSrc = $duration = $airDate = $scribe = $script = "";
					$errors = array();
					
					if($_SERVER["REQUEST_METHOD"] == "POST") {
											
						if(!empty($_POST["title"])) {
							$title =  mysqli_real_escape_string($con, $_POST["title"] );
						} else {
							array_push($errors, "title");
						}
						if(!empty($_POST["link"])) {
							$link =  mysqli_real_escape_string($con, $_POST["link"] );
						} else {
							array_push($errors, "link");
						}
						if(!empty($_POST["link2"])) {
							$embedSrc =  mysqli_real_escape_string($con, $_POST["link2"] );
						} else {
							array_push($errors, "embed");
						}
						if(!empty($_POST["duration"])) {
							$duration =  mysqli_real_escape_string($con, $_POST["duration"] );
						} else {
							array_push($errors, "duration");
						}
						if(!empty($_POST["air-date"])) {
							$airDate =  mysqli_real_escape_string($con, $_POST["air-date"] );
						} else {
							array_push($errors, "air date");
						}
						if(!empty($_POST["scribe"])) {
							$scribe =  mysqli_real_escape_string($con, $_POST["scribe"] );
						} else {
							array_push($errors, "scribe");
						}
						if(!empty($_POST["script"])) {
							$script = mysqli_real_escape_string($con, fixSpecialCharacters($_POST["script"]));
						} else {
							array_push($errors, "script");
						}				
					}
					
					echo "<b>Title:</b> " . $title . "<br>";
					echo "<b>Link:</b> " . $link . "<br>";
					echo "<b>Embed source:</b> " . $embedSrc . "<br>";
					echo "<b>Duration:</b> " . $duration . "<br>";
					echo "<b>Air date:</b> " . $airDate . "<br>";
					echo "<b>Scribe:</b> " . $scribe . "<br><br>";
					echo '<textarea rows="20" cols="40" name="script-result">' . $script . '</textarea>';
					
					echo "<br>";
					
					if(empty($errors)){
						//echo "Running query: <br>";
						
						$insertionQuery = 'INSERT INTO ja_episodes (title, link, link2, duration, air_date, scribe, script) 
							VALUES ("' . $title . '", "' . $link . '", "' . $embedSrc . '", "' . $duration . '", "' 
								. $airDate . '", "' . $scribe . '", "' . $script . '");';	
						
						$insertionQuery = 'INSERT INTO ja_headgum (title, link, link2, duration, air_date, scribe, script) 
							VALUES ("' . $title . '", "' . $link . '", "' . $embedSrc . '", "' . $duration . '", "' 
								. $airDate . '", "' . $scribe . '", "' . $script . '");';
	
						$fakeQuery = 'INSERT INTO ja_episodes (title, link, link2, duration, air_date, scribe, script) 
							VALUES ("' . $title . '", "' . $link . '", "' . $embedSrc . '", "' . $duration . '", "' 
								. $airDate . '", "' . $scribe . '", [script goes here]);';				
						
						//echo $fakeQuery;
						
						mysqli_query($con, $insertionQuery);
						
						$insertionQuery = 'INSERT INTO ja_headgum (title, link, link2, duration, air_date, scribe, script) 
							VALUES ("' . $title . '", "' . $link . '", "' . $embedSrc . '", "' . $duration . '", "' 
								. $airDate . '", "' . $scribe . '", "' . $script . '");';
	
						$fakeQuery = 'INSERT INTO ja_episodes (title, link, link2, duration, air_date, scribe, script) 
							VALUES ("' . $title . '", "' . $link . '", "' . $embedSrc . '", "' . $duration . '", "' 
								. $airDate . '", "' . $scribe . '", [script goes here]);';
						
						//echo $fakeQuery;
						
						mysqli_query($con, $insertionQuery);
						
						echo '<br>No errors were found. Thank you for contributing!';
						echo "<br><br>";
					} else {
						echo "Error(s) detected -- you're missing some fields. The episode creation will not continue until the fields listed
						 are added: <br>";
						printErrors($errors);
						echo "<br><br>";
					}
					
				}
				
				/*=================*
				 *	    Modify     *
				 *=================*/
				else {
				
					$id = $_POST["id"];
					
					if($_SERVER["REQUEST_METHOD"] == "POST") {
						
						//echo "Running update query... <br>";
						
						mysqli_query($con, "set names utf8");
						
						if(!empty($_POST["title"])) {
							$title =  mysqli_real_escape_string($con, $_POST["title"] );
							$updateQuery = 'UPDATE ja_episodes SET title="' . $title . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$title =  mysqli_real_escape_string($con, $_POST["title"] );
							$updateQuery = 'UPDATE ja_headgum SET title="' . $title . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						}
						if(!empty($_POST["link"])) {
							$link =  mysqli_real_escape_string($con, $_POST["link"] );
							$updateQuery = 'UPDATE ja_episodes SET link="' . $link . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$link =  mysqli_real_escape_string($con, $_POST["link"] );
							$updateQuery = 'UPDATE ja_headgum SET link="' . $link . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						} 
						if(!empty($_POST["link2"])) {
							$embedSrc =  mysqli_real_escape_string($con, $_POST["link2"] );
							$updateQuery = 'UPDATE ja_episodes SET link2="' . $embedSrc . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$embedSrc =  mysqli_real_escape_string($con, $_POST["link2"] );
							$updateQuery = 'UPDATE ja_headgum SET link2="' . $embedSrc . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						}
						if(!empty($_POST["duration"])) {
							$duration =  mysqli_real_escape_string($con, $_POST["duration"] );
							$updateQuery = 'UPDATE ja_episodes SET duration="' . $duration . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$duration =  mysqli_real_escape_string($con, $_POST["duration"] );
							$updateQuery = 'UPDATE ja_headgum SET duration="' . $duration . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						}
						if(!empty($_POST["air-date"])) {
							$airDate =  mysqli_real_escape_string($con, $_POST["air-date"] );
							$updateQuery = 'UPDATE ja_episodes SET air_date="' . $airDate . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$airDate =  mysqli_real_escape_string($con, $_POST["air-date"] );
							$updateQuery = 'UPDATE ja_headgum SET air_date="' . $airDate . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						}
						if(!empty($_POST["scribe"])) {
							$scribe =  mysqli_real_escape_string($con, $_POST["scribe"] );
							$updateQuery = 'UPDATE ja_episodes SET scribe="' . $scribe . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$scribe =  mysqli_real_escape_string($con, $_POST["scribe"] );
							$updateQuery = 'UPDATE ja_headgum SET scribe="' . $scribe . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						}				
						if(!empty($_POST["script"])) {
							$script = mysqli_real_escape_string($con, fixSpecialCharacters($_POST["script"]));					
							$updateQuery = 'UPDATE ja_episodes SET script="' . $script . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
							$script = mysqli_real_escape_string($con, fixSpecialCharacters($_POST["script"]));					
							$updateQuery = 'UPDATE ja_headgum SET script="' . $script . '" WHERE id=' . $id . ';';
							mysqli_query($con, $updateQuery);
						}			
					}
					
					echo "<br>";
					echo '<br>Check the database to confirm your results. If you fucked up, you can always update it again.';
						
					
				}
			}
			/*=================*
			 *	    Create     *
			 *=================*/
			else if (isset($_POST['create-extra'])) {
				$id = $name = $link = $title = "";
				$errors = array();
				
				if($_SERVER["REQUEST_METHOD"] == "POST") {
										
					
					if(!empty($_POST["id-extra"])) {
						$id =  mysqli_real_escape_string($con, $_POST["id-extra"] );
					} else {
						array_push($errors, "id");
					}				
					if(!empty($_POST["name-extra"])) {
						$name =  mysqli_real_escape_string($con, $_POST["name-extra"] );
					} else {
						array_push($errors, "name");
					}				
					if(!empty($_POST["link-extra"])) {
						$link = mysqli_real_escape_string($con, $_POST["link-extra"]) ;
					} else {
						array_push($errors, "link");
					}				
					if(!empty($_POST["title-extra"])) {
						$title = mysqli_real_escape_string($con, $_POST["title-extra"]) ;
					} else {
						array_push($errors, "title");
					}				
				}
				
				echo "id: " . $id . "<br>";
				echo "name: " . $name . "<br>";
				echo "link: " . $link . "<br>";
				echo "title: " . $title . "<br>";
				
				echo "<br>";
				
				if(empty($errors)){
					echo "Running query: <br>";
					
					$insertionQuery = 'INSERT INTO extras (episode_id, name, link, title) 
						VALUES ("' . $id . '", "' . $name . '", "' . $link . '", "' . $title . '");';		
							
							
					echo $insertionQuery;
					
					
					mysqli_query($con, "set names utf8");
					mysqli_query($con, $insertionQuery);
					echo '<br>query completed'; 
					
				} else {
					echo "Error(s) detected -- you're missing some fields. The extras creation will not continue until the fields listed
						 are added. <br>";
					printErrors($errors);					
				}
			}
			
					
			?>
			</div>
		</div>
	</body>	
</html>
