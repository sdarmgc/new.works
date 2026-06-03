CACHE MANIFEST
#sbl_tranbslator_sbl
#version 1.0.1 {!! date("Y-m-d H:i") !!}

CACHE:
/publications/translator/{!! $book !!}/{!! $year !!}/{!! $issue !!}/{!! $lang !!}/{!! $s_lang !!}
/favicon.ico
/css/frontend.css
/css/Translator.css
/css/images/progress_1.gif
/css/images/loading.gif
/js/jquery.min.js
/js/frontend/publications/translator.js

NETWORK:
#/login
#/publications/translator/*
