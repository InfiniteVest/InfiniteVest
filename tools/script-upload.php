<META http-equiv="Content-Type" content="text/html; charset=utf-8">

<?php
//session_start();
?>

<?php require "../header.php"; ?>
	<body>
	
		<div class="container episode-editor">
			<div class="row-fluid">
				<div class="span6 offset3">
					<div class="center">
					<h2 class="bebas">Episode Uploader</h2>
					</div>
			
					<form action="script-upload-success.php" method="post">
						<input placeholder="Episode Title" type="text" name="title"> <label for="title">e.g. Off Days: Holiday Roast. </label><br/>
						<input placeholder="Episode Link" type="text" name="link"> <label for="link">The link listed on the episode's HeadGum.com post.<br>
							e.g. <a href="https://headgum.com/videos/geoffrey-the-dumbass-a-star-is-born">https://headgum.com/videos/geoffrey-the-dumbass-a-star-is-born</a></label> <br/>
						<input placeholder="Embed Source" type="text" name="link2"> <label for="link2"> Please go to YouTube, click "share" then "embed" and copy the link out of there.</label><br>
						<input placeholder="Duration" type="text" name="duration"> <label for="duration">e.g 2:25</label> <br/>
						<input placeholder="Air Date" type="text" name="air-date"> <label for="air-date"> e.g. 2019-02-12</label> <br/>
						<input placeholder="Scribe" type="text" name="scribe"> <label for="scribe">Transcriber's reddit name found on <a href="http://www.reddit.com/r/headgumscripts">/r/HeadgumScripts</a>
						<br>
						e.g. <i>Jjkae_Blumenwitz</i></label><br/>
						<span class="bebas">Script Text</span><br><label for="script">Note: line breaks will be converted into html &#060;br/&#062; tags</label> <textarea class="script-insert" rows="20" cols="40" name="script"></textarea>  <br/>
						<input type="hidden" name="script-upload" value="1">
						<div class="center">
						<input class="button" type="submit">
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>	
</html>
