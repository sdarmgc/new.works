<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SDARM WORKS | Magazine text converter</title>
        <meta name="description" content="Magazine text converter">
        <meta name="author" content="Sean Kim">
		<meta name="version" content="1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link href="https://dl.sdarm.org/css/article.css" rel="stylesheet">
		<link href="/css/article_converter.css?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/css/article_converter.css') !!}" rel="stylesheet">
	</head>
    <body>
        <div id="app" class="app-body article-converter">
			<div class="panel row" id="panel-container">
				<div class="panel column attributes"  id="attributes-panel">
					<div class="header">
						Attributes
					</div>
					<div class="content">
						<div class="" style='font-weight:bold'>Book Name: <br />
							<select id="book-name" name='book-name'>
								<option value='rmrh'>The Reformation Herald</option>
								<option value='ym'>Youth Messanger</option>
							</select>
						</div>
						<div class="" style='font-weight:bold'>Language: 
							<br />
							<select id="lang">
								<option value="--">Choose a Language</option>
							</select>
						</div>
						<div class="" style='font-weight:bold'>Year: <br /><input type='text' id="year" name='year' value='<?php echo date("Y"); ?>' /></div>
						<div class="" style='font-weight:bold'>Issue: <br />
							<select id="issue" name='issue'>
								<option value=''>Select Number</option>
								<option value='1'>1</option>
								<option value='2'>2</option>
								<option value='3'>3</option>
								<option value='4'>4</option>
								<option value='5'>5</option>
								<option value='6'>6</option>
							</select>
						</div>
						<div class="" style='font-weight:bold'>Volume: <br /><input type='text' id="volume" name='volume' value='' /></div>
						<div class="" style='display: none; font-weight:bold'>Title: <br /><input type='text' id="book-title" name='book-title' value='The Reformation Herald' /></div>
						<div class="" style='font-weight:bold'>Subtitle: <br /><input type='text' id="book-subtitle" name='subtitle' value='' /></div>
						<div class="attr-space">&nbsp;</div>
						<div class="menu" id="command-export-xml">export</div>
						<div class="menu disabled" id="command-save" title="save changes to the local storage">save</div>
						<div class="menu" id="command-undo">undo attribute</div>
						<div class="menu-subtitle">Group Attributes</div>
						<div class="menu menu-attr block-attr" id="attr-article">article</div>
						<div class="menu menu-attr block-attr" id="attr-sect">sect1</div>
						<div class="menu menu-attr block-attr" id="attr-sect">sect2</div>
						<div class="menu menu-attr block-attr" id="attr-sect">sect3</div>
						<div class="menu menu-attr block-attr" id="attr-abstract">abstract</div>
						<div class="menu menu-attr block-attr" id="attr-epigraph">epigraph</div>
						<div class="menu menu-attr block-attr" id="attr-annotation">annotation</div>
						<div class="menu menu-attr block-attr" id="attr-bibliography">bibliography</div>
						<div class="menu-subtitle">Paragraph Attributes</div>
						<div class="menu menu-attr" id="attr-plain">plain</div>
						<div class="menu menu-attr parag-attr parag-attr-overwrite" id="attr-keyword">keyword</div>
						<div class="menu menu-attr parag-attr parag-attr-overwrite" id="attr-title">title</div>
						<div class="menu menu-attr parag-attr parag-attr-overwrite" id="attr-subtitle">subtitle</div>
						<div class="menu menu-attr parag-attr parag-attr-overwrite" id="attr-part-no">part-no</div>
						<div class="menu menu-attr parag-attr parag-attr-overwrite" id="attr-author">author</div>
						<div class="menu menu-attr parag-attr parag-attr-overwrite" id="attr-biblioentry">biblioentry</div>
						<div class="menu-subtitle">Span Attributes</div>
						<div class="menu menu-attr span-attr" id="attr-cap">cap</div>
						<div class="menu menu-attr span-attr" id="attr-bold">bold</div>
						<div class="menu menu-attr span-attr" id="attr-italic">italic</div>
						<div class="menu menu-attr span-attr" id="attr-underline">underline</div>
						<div class="menu menu-attr span-attr" id="attr-citation">citation</div>
						<div class="menu menu-attr span-attr" id="attr-olink">olink</div>
						<div class="attr-space">&nbsp;</div>
						<div class="" style='font-weight:bold'>Bibliography pattern: <br />
							<input type='text' id="pattern-bibliography" name='pattern-bibliography' value='References?' />
						</div>
						<div class="" style='font-weight:bold'>Biblioentry pattern: <br />
							<input type='text' id="pattern-biblioentry" name='pattern-biblioentry' value="(\]\s*$)|(\d+\.$)|(http)|(Ibid\.)| (18\d\d\.)|(19\d\d\.)" />
						</div>
						<div class="" style='font-weight:bold'>Article Starter pattern: <br />
							<input type='text' id="pattern-article" name='pattern-article' value="(editorial)|(^Children’s Corner)|(^\w+, \w+ \d{1,2}, 20\d{2}$)" />
						</div>
						<div class="" style='font-weight:bold'>Title pattern: <br />
							<input type='text' id="pattern-title" name='pattern-title' value="(^#)|(^@)|(^[^\.]{1,50}$)" />
						</div>
						<div class="" style='font-weight:bold'>Auther pattern: <br />
							<input type='text' id="pattern-auther" name='pattern-auther' value="(^.+ — .+$)" />
						</div>
						<div class="" style='font-weight:bold'>Citation style: <br />
							<input type='text' id="citation-style" name='citation-style' value='10' />
						</div>
					</div>
				</div>
				<div class="panel column" id="content-panel">
					<div class="panel column editor" id="panel-editor-wrapper">
						<div class="header">
							<div class="title">HTML Editor</div>
							<div class="cur-pos-indicator-wrapper">
								<sapn id="cur-pos-indicator" class="cur-pos-indicator app-info-item" title="Current position">Current position</span>
							</div>
						</div>
						<div class="content sdarm-article book" id="panel-editor-content">
							Contents
						</div>
						<div class="footer row">
							<div id="selection-indicator">SELECTED ELEMENT</div>
							<div id="hover-element"></div>
						</div>
					</div>
					<div class="panel panel-spliter" id="panel-spliter-vertical"></div>
					<div class="panel row"  id="panel-source-wrapper">
						<div class="panel column" id="panel-source">
							<div class="panel">
								<div class="column control-source">
									Input File: 
									<input type="file" id="file-source" accept=".rtf, .xml, .txt"/>
									<br />
									<button type="button" id="button-convert" >Convert to HTML</button>
									<input type="checkbox" id="auto-cap" name="auto-cap"><label for="auto-cap">Auto Cap</label>
									<input type="checkbox" id="disable-ignorable" name="disable-ignorable" checked=true><label for="disable-ignorable">Disable Ignorable</label>
								</div>
								<div class="column control-results">
									<input type="checkbox" id="validate" name="validate" checked=true><label for="validate">Validate</label>
									<input type="checkbox" id="overwrite" name="overwrite"><label for="overwrite">Overwrite</label>
									<input type="checkbox" id="db-update" name="db-update"><label for="db-update">Update DB</label>
									<input type="checkbox" id="auto-upload" name="auto-upload"><label for="auto-upload">Auto Upload</label>
									<br />
									<button type="button" id="button-do-validation" >Do Validation</button>
									<button type="button" id="button-upload-xml" >Upload to Server</button>
								</div>
							</div>				
							<div class="panel content">
								<div class="content">
									<textarea class="source-text" id="source-text"></textarea>
								</div>
							</div>
						</div>
						<div class="panel panel-spliter" id="panel-spliter-horizontal"></div>
						<div class="panel" id="panel-logger">
							<textarea class="log-text" id="log-text"></textarea>
						</div>
					</div>
				</div>
				<div id="waiting-icon" class="hide"></div>
			</div>
		</div>

        <!-- Scripts -->
        @stack('before-scripts')
        {!! script(mix('js/manifest.js')) !!}
        {!! script(mix('js/vendor.js')) !!}
        {!! script(mix('js/frontend.js')) !!}
        @stack('after-scripts')

		<link rel="stylesheet" type="text/css" href="/css/tln.min.css"/>
		<script type="text/javascript" src="/js/tln.min.js"></script>
		<script type="text/javascript" src="/js/converter.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/converter.js') !!}"></script>
        <script type="text/javascript" src="/js/rmrh/rmrh_lang_pattern.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/rmrh/rmrh_lang_pattern.js') !!}"></script>
        <script type="text/javascript" src="/js/rmrh/article_converter.js?v={!! filemtime($_SERVER['DOCUMENT_ROOT'] . '/js/rmrh/article_converter.js') !!}"></script>
		<script>
			setLangList();
		</script>
	</body>
</html>