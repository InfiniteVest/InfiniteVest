<?php if(isset($_GET['do-search']) || isset($_GET['single-episode'])) { //a request was made to display results ?>
		<div id="body_wrapper">
			<div class="container">
				<div class="row-fluid">
					<div class="results-wrapper span12">
						<span class="results-number"><!-- TODO: Results --></span> Searched : <br> 
						<span class="search-string">
							<?php printSearchTerms(array_unique($search)); echo $searchErr ?>
						</span>
						<span class="advanced-info">
						<?php
	$selected = $_GET['option'];
						if($selected == "headgum") {
							echo '<br>HeadGum Scripts';
						} else if($selected == "jaonly") {
							echo '<br>J&A Scripts';
						} else {
							echo '<br>Both HeadGum and J&A Scripts';
						}
						if(!empty($exactPhrase)) echo ', Exact phrase';
						if(!empty($titleOnly)) echo ", Title only <br>";  
						if(!empty($fromDate) && !empty($toDate)) echo 'Between ' . $fromDate . ' and ' . $toDate .'<br>';
						?>
						</span>
						<?php
							// Create connection
							
							include __DIR__ . '/tools/dbConfig.php';

							$con=mysqli_connect($hostName, $userName, $password, $database);

							// Check connection
							if (mysqli_connect_errno($con))
							{
							  echo "Failed to connect to MySQL: " . mysqli_connect_error();
							}
							//else { echo "Successfully connected. <br>"; }
							
							/* fields
								search : array
								exact-phrase : string
								title-only : string {checked='checked'}
								from-date : string
								to-date : string
							
							*/							
						
              // join all search terms into one string
							if(!empty($exactPhrase)) {
                $exactString = implode(" ", $search);
                $search = array();
                array_push($search, $exactString);
							}
								
							
									// Create query for single episode
							if(isset($_GET['single-episode']) && isset($_GET['single-id']))	
							{
								$singleId = $_GET['single-id'];
								if($selected == "headgum") {
								   $fullQuery = "SELECT * FROM episodes WHERE id=" . $singleId . ";";
								} else if($selected == "jaonly") {
								   $fullQuery = "SELECT * FROM ja_episodes WHERE id=" . $singleId . ";";
								}
								else {
    								$fullQuery = "SELECT * FROM ja_headgum WHERE id=" . $singleId . ";";
							}
								   }
							// Create query for search
							else {														
								// Build the title search terms
								if($selected == "headgum") {
								$titleQuery = "SELECT * FROM episodes WHERE((";
									} else if($selected == "jaonly") {
								$titleQuery = "SELECT * FROM ja_episodes WHERE((";
								}else{
									$titleQuery = "SELECT * FROM ja_headgum WHERE ((";
									  }
								$scriptQuery = "";							
               					$dateQuery = "";
               					$orderBy = "ORDER BY air_date ASC"; //by default sort by air date
								$searchLength = count($search);
								
								//set default sort button values
								$newSortTypeTitle = "title-desc";
								$newSortTypeAirDate = "air-date-asc";
								//set sort order
								if(!empty($sortType)){
								
									if($sortType == "title-asc"){
										$orderBy = "ORDER BY title ASC";
										$newSortTypeTitle = "title-desc";
									}
									else if($sortType == "title-desc"){
										$orderBy = "ORDER BY title DESC";
										$newSortTypeTitle = "title-asc";
									}
									else if ($sortType == "air-date-asc"){
										$orderBy = "ORDER BY air_date ASC";
										$newSortTypeAirDate = "air-date-desc";
									}
									else if ($sortType == "air-date-desc"){
										$orderBy = "ORDER BY air_date DESC";
										$newSortTypeAirDate = "air-date-asc";
									}
								} else {$sortType = "title-asc";}
								
								// Construct query
								if( isset($search) && $searchLength > 0){
									// construct title query
									for ($i = 0; $i < $searchLength; $i++) {
										$word = mysqli_real_escape_string($con, $search[$i]);
										$titleQuery = $titleQuery . "title LIKE '%" . $word . "%'";
										if($i != $searchLength - 1){
											$titleQuery = $titleQuery . " AND ";
										} 
									}
								
									if( empty($titleOnly)){							
										$scriptQuery = ") OR (";
										
										// construct script query
										for ($i = 0; $i < $searchLength; $i++) {
											$word = mysqli_real_escape_string($con, $search[$i]);
											$scriptQuery = $scriptQuery . "script LIKE '%" . $word . "%'";
											if($i != $searchLength - 1){
												$scriptQuery = $scriptQuery . " AND ";
											} else {
												$scriptQuery = $scriptQuery . "))";
											}
										}
																				
									} else {
										$titleQuery = $titleQuery . "))"; 
									}

									if(!empty($toDate) && !empty($fromDate)) {
                    					$dateQuery = " AND (air_date <='". $toDate ."' AND air_date >= '" . $fromDate . "')";
                  					}
								$fullQuery = $titleQuery . "" . $scriptQuery . "" . $dateQuery . "" . $orderBy . ";"; //may not need semicolon
								
								}								
								
							}
							// echo $orderBy;
							?>
							<?php 
							//clear old sort type
							$sortTypeUrl = preg_replace('/&?sort-type=[^&]*/', '', $_SERVER['REQUEST_URI']);
							?>
							
							
							
							
							
							<div class="sorter-buttons">
							<button class="sorter button" id="sort-by-title" value="<?php echo $newSortTypeTitle;?>" type="submit">Title </button>
							<button class="sorter button" id="sort-by-air-date" value="<?php echo $newSortTypeAirDate;?>" type="submit"><span class="darr"></span>Date</button>
							</div>
							
							
							
							<?php $sortType = $_GET['sort-type']; ?>
							
							<?php if ($sortType == 'title-asc'){ ?>
								<script> $('#sort-by-title').prepend('&darr; '); </script>
							<?php } elseif($sortType == 'title-desc') {?>
								<script> $('#sort-by-title').prepend('&uarr; '); </script>

							<?php } elseif($sortType == 'air-date-asc') {?>
								<script> $('#sort-by-air-date').prepend('&darr; '); </script>
							<?php } elseif($sortType == 'air-date-desc') {?>
								<script> $('#sort-by-air-date').prepend('&uarr; '); </script>
							<?php } else { ?>
								<script> $('#sort-by-air-date').prepend('&darr; '); </script>
							<?php } ?>
							
							<script>
							/* bind sort button click events */
							$(document).ready(function(){
								$('#sort-by-title').click(function(){
									window.location.href = '<?php echo $sortTypeUrl . "&sort-type=" . $newSortTypeTitle; ?>';
								});
								
								$('#sort-by-air-date').click(function(){
									window.location.href = '<?php echo $sortTypeUrl . "&sort-type=" . $newSortTypeAirDate; ?>';
								});
							});
							</script>

							<script>console.log('<?php echo $fullQuery; ?>');</script>
					</div>
				</div>
				<div class="row-fluid">
					<div class="episodes-wrapper span12">	
							<?php
							//execute the SQL query and return records
							$result = mysqli_query($con, $fullQuery);										   
						
						?>
						
						
						<?php
							//fetch tha data from the database
							while ($row = mysqli_fetch_array($result)) {
								
								$id = $row{'id'};
								$title = $row{'title'};
								$link = $row{'link'};
								$script = $row{'script'};
								$duration = $row{'duration'};
								$air_date = $row{'air_date'};
								$scribe = $row{'scribe'};
								$iframe = $row{'link2'};
								//continued below		
						?>
						
						<!-- Starts and Episode Item -->
						<article class="episode-item">
							<div class="episode-item-header">
								<table class="episode-header-inner">
									<tr>
										<td class="header-inner-dropdown"><div class="arrow-wrapper"><span class="dropdown_arrow"></span></span></td>
										<td class="header-inner-title"><?php echo $title; ?></td>
										<td class="header-inner-calendar hidden-phone"><span class="calendar"></span></td>
										<td class="header-inner-date hidden-phone"><span><?php echo $air_date; ?></span></td>
									</tr>
								</table>
							</div>
							<div class="episode-item-content">
								<div class="episode-item-content-inner">
									<div class="row-fluid">
										<div class="span7 episode-video">
											<div class="episode-video-inner">
												<!-- TODO: iframe on this <a href="<?php echo $link;?>" target="blank"> Episode </a> -->
												<iframe data-src="<?php echo $iframe; ?>" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>
											</div>
										</div>
										<div class="span5 episode-details-wrapper">
											<table class="episode-details">
											<!-- <tr>
												<td><span class="play"></span></td>
												 <td>Movie Date 2 (with Ben Schwartz and Thomas Middleditch)</td>
											</tr> -->
											<tr class="visible-phone">
												<td><span class="calendar"></span></td>
												<td><?php echo $air_date; ?></td>
											</tr>
											<tr>
												<td><span class="clock-icon"></span></td>
												<td><?php echo $duration; ?></td>
											</tr>
											
											<!-- if Outtakes exist -->
											<?php 
											$extrasQuery = "SELECT * FROM extras WHERE episode_id = ".$id;
											$extrasResult = mysqli_query($con, $extrasQuery);
											while($exRow = mysqli_fetch_array($extrasResult)) {
												$exLink = $exRow{'link'};
												$exName = $exRow{'name'}; ?>
												<tr>
													<td><span class="noun_project_2863"></span></td>
													<td><a class="button" href="<?php echo $exLink; ?>"><?php echo $exName; ?></a></td>
												</tr>
											<?php } ?>
											<!-- endif -->
											<?php $singleEpisodeUrl = "index.php?single-episode=1&single-id=" . $id ; ?>
											<tr>
												<td><span class="reddit_icon"></span></td>
												<td>Transcribed by <a target="_blank" class="button" href="http://www.reddit.com/u/<?php echo $scribe; ?>"><?php echo $scribe; ?></a></td>			
											</tr>
											<tr>
												<td><span class="icon-share"></span></td>
												<td>
													<a target="_blank" class="button b-facebook" href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo $singleEpisodeUrl; ?>&p[title]=<?php echo $title; ?>&p[summary]=">
														Facebook
													</a><a target="_blank" class="button b-twitter" href="http://twitter.com/home?status= <?php echo $title; ?> <?php echo $singleEpisodeUrl; ?>">
														Twitter
													</a>
												</td>
											</tr>
											</table>
											<button class="script-button">
												<table>
													<tr>
														<td><span class="script-icon"></span></td>
														<td>&nbsp;&nbsp;<span class="show-script">Show</span><span class="hide-script">Hide</span> Episode Script</td>
													</tr>
												</table>								
											</button>
										</div>
										<div class="row-fluid">
											<div class="span12">
												<div class="episode-script">
													<div class="episode-script-inner">
														<?php echo $script;?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>						
						</article>										
						<?php
							} //end row iteration
							//} //end if statement
						?>
						<!-- End Episode -->
					</div>
				</div>
			</div>
		</div>
<?php } ?>
