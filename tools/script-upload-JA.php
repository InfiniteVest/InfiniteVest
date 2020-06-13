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
			
					<!--<form action="script-upload-success-JA.php" method="post">
						<input placeholder="Episode ID" type="text" name="id">
						<input placeholder="Episode Title" type="text" name="title">
						<input placeholder="Episode Link" type="text" name="link">
						<input placeholder="Embed Source" type="text" name="link2">
						<input placeholder="Duration" type="text" name="duration">
						<input placeholder="Air Date" type="text" name="air-date">
						<input placeholder="Scribe" type="text" name="scribe"> 
						<span class="bebas">Script Text</span><br> <textarea class="script-insert" rows="20" cols="40" name="script"></textarea>  <br/>
						<input type="hidden" name="script-upload" value="1">
						<div class="center">
						<input class="button" type="submit">
						</div>
					</form>
					<div class="line-break"></div>-->
					
						<div class="center">
							<h2 class="bebas">Extras/Outtakes creation </h2>
						</div>
					<p> 
						Note: this is for <b>episodes already in the database</b>. 
						The intent is to create a new extras entry for an existing episode.
						You can add as many as you want to a single episode.
					</p>
					
					<p> 
						You can find this info on the <a href="https://www.reddit.com/r/jakeandamir/comments/11ai05/the_unofficial_ja_master_list/c7hlkoq">Reddit master list </a>
					</p>
					
					<form action="script-upload-success-JA.php" method="post">
						<input placeholder="Episode Id" type="text" name="id-extra"> <label>ID of an episode that is already in the database</label> <br/>
						<input placeholder="Name/Type" type="text" name="name-extra"> <label>e.g. "Outtakes", "Behind the scenes".</label> <br/>
						<input placeholder="Link" type="text" name="link-extra"> <label>Link to the extra (not an embed)</label> <br/>
						<input placeholder="Title" type="text" name="title-extra"> <label>Title of original episode</label> <br/>
						<input type="hidden" name="create-extra" value="1">
						<div class="center">
							<input class="button" type="submit">
						</div>
				</div>
			</div>
		</div>
	</body>	
	<div class="PAGEFOOTER">
</html>
