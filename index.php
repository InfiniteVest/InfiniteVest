<?php

require "php/functions.php"; 
session_start();

?>

<?php require "header.php"; ?>
	<body class="preload">
		<div class="site_info"></div>
		<div id="header_wrapper">
			<div id="header-inner">
				<div class="container">
					<div class="row-fluid">
						<div class="span8 offset2 search-wrapper">
					
					<?php // Declare all search parameters for the search form
					
					$searchErr = $exactPhraseErr = $titleOnlyErr = $fromDateErr = $toDateErr = "";
					$titleOnly = $exactPhrase = $fromDate = $toDate = "";					
					$search = array();
					
					if($_SERVER["REQUEST_METHOD"] == "GET") {
						if(empty($_GET["search"])) {
							$searchErr = "No search terms selected.";
						} else {
							$search = removeBlanks(explode(" ", $_GET["search"]));
						}
						if(isset($_GET["exact-phrase"])) {
							$exactPhrase = "checked";
						} else {
							$exactPhraseErr = "No search terms selected.";
						}						
						if(isset($_GET["title-only"])) {
							$titleOnly =  "checked";
						} else {
							$titleOnlyErr = "Missing title-only";
						}
						if(empty($_GET["from-date"])) {
							$fromDateErr = "Missing";
						} else {
							$fromDate = toSqlDate($_GET["from-date"]);
						}
						if(empty($_GET["to-date"])) {
							$toDateErr = "Missing";
						} else {
							$toDate = toSqlDate($_GET["to-date"]);
						}
						if(empty($_GET["sort-type"])) {
							$sortTypeErr = "missing";
						} else {
							$sortType = $_GET["sort-type"];
						}
					}
					?>
							<!-- Search Form -->					
							<form method="get" action="index.php">
								<label for="search-terms-bar" class="ie">Search any phrase...</label>
								<input id="search-terms-bar" class="default-text" type="text" name="search" value="<?php printSearchTerms($search); ?>" autocomplete="off" placeholder="Search any phrase..." >
								<button class="search-button" type="submit"><span class="profilesearch"></span></button>											<a class="button advanced-button">Advanced Options</a>

								<div id="advanced-options">
									<input id="title" type="checkbox" name="title-only"><label for="title">Episode Title</label>
									<input id="phrase" type="checkbox" name="exact-phrase"><label for="phrase">Exact Phrase</label>
									<br>
									<div class="range-wrapper">
										<input autocomplete="off" id="range-start" class="range datepicker" placeholder="Start Date" type="text" name="from-date" />
										<span class="range-label"> to </span>
										<input autocomplete="off" id="range-end"  class="range datepicker" placeholder="End Date" type="text" name="to-date" />
										<input type="hidden" name="do-search" value="1">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
				
		<?php if(!empty($search)) { include "test-results.php"; }?>
		
		<div id="footer-wrapper">
			<div class="footer">
				<div class="container">
					<div class="row-fluid">
						<div class="span12">
				&copy; 2019 Ben Donnell, based on <a href='http://scripts.jakeandamir.com/'>scripts.jakeandamir.com</a> | All videos owned by <a href="http://headgum.com">HeadGum.com</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		<<a class="info" data-original-title="Oh sheesh, y'all!" data-toggle="popover" data-html="true" data-placement="left" data-content="The Jake & Amir script archive was originally developed by J&A fans <a href='http://www.ccs.neu.edu/home/812chuc/'>Christopher Chu</a> and <a href='http://www.garrettboatman.com'>Garrett Boatman</a>. <br> <br>I have replicated the site to archive the HeadGum scripts instead. <br> <br>See some issues or have some feedback or suggestions? <br> <a href='mailto:ben@infinitejest.x10host.com?subject=HeadGum Episode Archive'>Let me know!</a>" title="">
        <span class="profileinfo"></span>
        </a>
		
		<!-- "Fork us on Git" -->
		<!-- <a href='https://github.com/garrettboatman/ForTheWolf/'><img src='img/GitHub_Logo.png'></a> -->
		 
	</body>
	

	<script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
	<!-- Development JS -->
	<script type="text/javascript" src="js/bootstrap.js"></script> 
	<script type="text/javascript" src="js/jquery.highlight-4.js"></script>
	<script type="text/javascript" src="js/modernizr.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
	
	<script>
		 $('.info').popover();
		 
		 // Highlight the search terms if not searching just title
		 // WARNING: Disgraceful mixing 
		 <?php if(empty($titleOnly)) {
			for ($i = 0; $i < $searchLength; $i++) { ?>
			$('.episode-script-inner').highlight("<?php echo $search[$i];?>");
		<?php } } ?>
	</script>
</html>

