<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDARM WORKS | Magazine viewer</title>
        <meta name="description" content="Magazine text converter">
        <meta name="author" content="Sean Kim">
		<meta name="version" content="1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">
    	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
		<link href="/css/article_converter.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/article_converter.css') !!}" rel="stylesheet" />
		<link href="https://dl.sdarm.org/css/article.css" rel="stylesheet" />
        <link href="https://dl.sdarm.org/css/library.css" media="all" type="text/css" rel="stylesheet" />
		<style>
			.magazine-toc ul { margin: 0; padding: 0 1em;}
			.magazine-toc ul li {list-style-type:none; margin: 0; padding: 0;}
			.magazine-toc .abstract {margin:0 0 0.5em; font-size: 0.8em; text-align: left;}
		</style>
	</head>
    <body>
        <div id="app" class="app-body article-preview">
			<div class="panel row" id="panel-container">
				<div class="panel column attributes"  id="attributes-panel">
					<div class="header">
						Attributes
					</div>
					<div class="content">
						<div class="" style='font-weight:bold'>Book Name: <br />
							<select id="book-name" name='book-name'>
								<option value='rmrh' <?php echo $book=='rmrh'?'selected':''; ?>>The Reformation Herald</option>
								<option value='ym' <?php echo $book=='ym'?'selected':''; ?>>Youth Messanger</option>
							</select>
						</div>
						<div class="" style='font-weight:bold'>Language: <br /><input type='text' id="lang" name='lang' value='<?php echo $lang; ?>' /></div>
						<div class="" style='font-weight:bold'>Year: <br /><input type='text' id="year" name='year' value='<?php echo $year; ?>' /></div>
						<div class="" style='font-weight:bold'>Issue: <br />
							<select id="issue" name='issue'>
								<option value=''>Select Number</option>
								<option value='1' <?php echo $issue=='1'?'selected':''; ?>>1</option>
								<option value='2' <?php echo $issue=='2'?'selected':''; ?>>2</option>
								<option value='3' <?php echo $issue=='3'?'selected':''; ?>>3</option>
								<option value='4' <?php echo $issue=='4'?'selected':''; ?>>4</option>
								<option value='5' <?php echo $issue=='5'?'selected':''; ?>>5</option>
								<option value='6' <?php echo $issue=='6'?'selected':''; ?>>6</option>
							</select>
						</div>
						<div class="" style='font-weight:bold'>Volume: <br /><input type='text' id="volume" name='volume' value='' /></div>
						<!--div class="" style='display: none; font-weight:bold'>Title: <br /><input type='text' id="book-title" name='book-title' value='The Reformation Herald' /></div-->
						<div class="" style='font-weight:bold'>Subtitle: <br /><input type='text' id="book-subtitle" name='subtitle' value='<?php echo $subtitle; ?>' /></div>
						<div class="attr-space">&nbsp;</div>
						<div class="" style='font-weight:bold; margin: 10px 0;'>Table of Contents</div>
						<div class="" id="link-articles">{!! $toc !!}</div>
					</div>
				</div>
				<div class="panel panel-spliter" id="panel-spliter-horizontal"></div>
				<div class="panel column" id="content-panel">
					<div class="panel column editor" id="panel-article-wrapper">
						<div class="header">
							Article Contents
						</div>
						<div class="content sdarm-article sdarm-dl" id="panel-preview-content" dl-server="https://dl.sdarm.org" lang="en" langEGW="en" >
                            {!! $contents !!}
						</div>
						<div class="footer row">
							<div id="hover-element"></div>
						</div>
					</div>
				</div>
				<div id="waiting-icon" class="hide"></div>
			</div>
		</div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://dl.sdarm.org/js/sdarm_dl.js?v=1.3"></script>

        <script src="/js/rmrh/article_preview.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/rmrh/article_converter.js') !!}"></script>
		<script>
			if ($("#book-name").val() == 'rmrh') {
				$("#volume").val( (parseInt($("#year").val()) - 2020 + 61).toString() ); //year 2020 Volume number is RH - 61, and YM - 39
			}
			else if ($("#book-name").val() == 'ym') {
				$("#volume").val( (parseInt($("#year").val()) - 2020 + 39).toString() ); //year 2020 Volume number is RH - 61, and YM - 39
			}
	  	</script>
	</body>
</html>