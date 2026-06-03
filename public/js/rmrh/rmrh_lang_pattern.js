/*
 * sbl_set_lang_pattern.js
 * 
 * Define all language patterns
 */
var langList = {"en":"English", "am":"Amharic", "sq":"Albanian", "bb": "Bemba", "bn":"Bengali", "bg":"Bulgarian", "pc":"Cebuano", "ny":"Chichewa", "zh":"Chinese", "hr":"Croatian", "cs":"Czech", "nl":"Dutch", "ee":"Ewe", "am":"Ethiopian", "fj":"Fijian", "fi":"Finnish", "fr":"French", "de":"German", "ht":"Haitian", "hi":"Hindi", "hl":"Hiligaynon", "hu":"Hungarian", "il":"Ilocano", "id":"Indonesian", "it":"Italian", "ja":"Japanese", "rn":"Kirundi",  "ko":"Korean", "lg":"Luganda", "ln":"Lingala", "mg":"Malagasy", "ms":"Malay", "mk":"Macedonian", "mq":"Miskito", "mn":"Mongolian", "nd":"Ndebele", "pt":"Portuguese", "ro":"Romanian", "ru":"Russian", "rw":"Rwandese", "si":"Sinhala", "sm":"Samoan", "sr":"Serbian (Cyrillic)", "srr":"Serbian (Romanised)", "sk":"Slovakian", "st":"Sotho", "es":"Spanish", "sw":"Swahili", "swc":"Swahili DRC", "th":"Thai",  "tl":"Tagalog","ta":"Tamil", "lu":"Tshiluba", "uk":"Ukrainian", "ur":"Urdu", "vi":"Vietnamese", "zu":"Zulu"};

function setLangList()
{
	listElement = document.getElementById("lang");
	for (var lang in langList) {
		listElement.add(new Option(langList[lang], lang));
	}
}

$("#lang").change(function() {
    setLangPattern( $(this).val() );
});

function setLangPattern(langCode)
{
	var repPattern = {};
	
	// this value is overwritten with #pattern-title at the end of the function,
	// unless set in the (specific) language setting.
	//$("#fso_date").val(""); 
	
	if ( langCode == "en"){
		//$("#language").val("English");
		$("#lang").val("en");
		//$("#lang_code3").val("eng");
		$("#pattern-bibliography").val("References");
		$("#pattern-biblioentry").val("(\\]\\s*$)|(\\d+\\.$)|(http)|(Ibid\\.)| (18\\d\\d\\.)|(19\\d\\d\\.)");
		$("#pattern-article").val("(^@)|(^Editorial)|(^Children’s Corner)|(^\\w+, \\w+ \\d{1,2}, 20\\d{2}$)");
		$("#pattern-title").val("(^#)|(^[^\\.]{1,50}$)");
		$("#pattern-auther").val("(^BY)"); //.val("(^.+ — .+$)");
		//$("#day").val("Sun[^ ]* |Mon[^ ]* |Tue[^ ]* |Wed[^ ]* |Thu[^ ]* |Fri[^ ]* |Sabbath");
		//$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^PERSONAL)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {
			"Sunday":"Sun",
			"Monday":"Mon",
			"Tuesday":"Tue",
			"Wednesday":"Wed",
			"Thursday":"Thu",
			"Friday":"Fri"
		};
	}

	else if ( langCode == "am"){
		//$("#language").val("Amharic");
		$("#lang").val("am");
		//$("#lang_code3").val("amh");
		$("#pattern-bibliography").val("፡፡");
		$("#pattern-biblioentry").val("(መቅድም)");
		$("#pattern-article").val("(([0-9]+)ኛ ትምህርት)");
		$("#pattern-title").val("(ሰንበት፣*\\s+(.*\\s+\\d+\\s*[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("^(ለጥናት የተመረ[ጡ|ጠው]* መጽሐፍ[ት]*[:፡])\\s*(.+)?");
		//$("#day").val("እሁድ|ሰኞ|ማክሰኞ|ረቡዕ|ሐሙስ|አርብ|ሰንበት");
		//$("#date").val("((\\d+)\\s+(መስከረም|ጥቅምት|ኅዳር|ታህሣሥ|ጥር|የካቲት|መጋቢት|ሚያዝያ|ግንቦት|ሰኔ|ሐምሌ|ነሐሴ))");
		//$("#subtitle").val("(^[1-5]|^የግል ግምገማ ጥያቄዎች)");
		//$("#citation-style").val("^[ሀለሐመሠረሰ]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|())");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(ሰንበት፣*\\s+(.*\\s+\\d+\\s*[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "sq"){
		//$("#language").val("Albanian");
		$("#lang").val("sq");
		//$("#lang_code3").val("sqi");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(parathënie)");
		$("#pattern-article").val("(Mësimi ([0-9]+)),");
		$("#pattern-title").val("(E Shtunë,\\s+(\\d+\\s+[A-Za-z]+,\\s+[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("^([^:]+:)\\s*(.+)?");
		//$("#day").val("E Djelë|E Hënë|E Martë|E Mërkurë|E Enjte|E Premte|E Shtunë,");
		//$("#date").val("((\\d+)\\s+(\\w+))");
		//$("#subtitle").val("(^[1-5]|^PYETJE PËR)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
	}

	else if ( langCode == "bb"){
		//$("#language").val("Bemba");
		$("#lang").val("bb");
		//$("#lang_code3").val("bem");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Ishiwi lya Ntanshi)");
		$("#pattern-article").val("(ICISAMBILILO ([0-9]+))");
		$("#pattern-title").val("(ISABATA, ([A-Za-z]+.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Umwakubelenga Mumbi:)\\s*(.+)?");
		//$("#day").val("Cimo,|Cibili,|Citatu,|Cine,|Cisano,|Mutanda,|Isabata");
  		//$("#day").val("Ubwa Ntanshi|Ubwa Cibili|Ubwa Citatu|Ubwa Cine|Ubwa Cisano|Ubwa Mutanda|Isabata");
		//$("#date").val("((Kabengele Kanono|Kabengele Kakalamba|Kutumpu|Shinde|Akapepo Kanono|Akapepo Kakalamba|Cikungulupepo|Akasaka Ntobo|ULusuba Lunono|ULusuba Lukalamba|Cinshikubili|Umupundu Milimo) (\\d+))");
  		//$("#date").val("((Kabengele Kanono|Kabengele Kakalamba|Kutumpu|Shinde|Akapepo Kanono|Akapepo Kakalamba|Cikungulupepo|Akasaka Ntobo|ULusuba Lunono|ULusuba Lukalamba|Cinshikubili|Umupundu Milimo) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^AMEPUSHO)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Isabata, ([A-Za-z]+.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {
			"Ubwa Ntanshi":"Nta",
			"Ubwa Cibili":"Cib",
			"Ubwa Citatu":"Cit",
			"Ubwa Cine":"Cin",
			"Ubwa Cisano":"Cis",
			"Ubwa Mutanda":"Mut"
		};
	}

	else if ( langCode == "bn"){
		//$("#language").val("Bengali");
		$("#lang").val("bn");
		//$("#lang_code3").val("ben");
		$("#pattern-bibliography").val("।");
		$("#pattern-biblioentry").val("(মুখপাত্র)");
		$("#pattern-article").val("(পাঠ ([0-9]+))");
		$("#pattern-title").val("(সাব্বাথ, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(প্রস্তাবিত [পড়া|রিডিং]*\\:)\\s*(.+)?");
		//$("#day").val("রবি,|সোম,|হয়,|বুধ,|বৃহ,|শুক্র,|সাব্বাথ");
  		//$("#day").val("রবিবার|সোমবার|মঙ্গলবার|বুধবার|বৃহস্পতিবার|শুক্রবার|সাব্বাত");
		//$("#date").val("((\\d+) (জানুয়ারি|ফেব্রুয়ারি|মার্চ|এপ্রিল|মে|জুন|জুলাই|আগস্ট|সেপ্টেম্বর|অক্টোবর|নভেম্বর|ডিসেম্বর))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^ব্যক্তিগত)");
		//$("#citation-style").val("^[ক|খ|গ|ঘ|ঙ]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(সাব্বাথ, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {
			"":"",
			"":"",
			"":"",
			"":"",
			"":"",
			"":""
		};
	}

	else if ( langCode == "bg"){
		//$("#language").val("Bulgarian");
		$("#lang").val("bg");
		//$("#lang_code3").val("bul");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Предговор)");
		$("#pattern-article").val("(УРОК ([0-9]+))");
		$("#pattern-title").val("(СЪБОТА, \\d+ .+ \\b[1-9][0-9]{3}\\b г\\.)");
		$("#pattern-auther").val("(Препоръчително четиво:)\\s*(.+)?"); //Препоръчвано четиво|Препоръчвани четива
		//$("#day").val("нед\.,|пон\.,|вт\.,|ср\.,|чет\.,|пет\.,|Събота");
  		//$("#day").val("Неделя|Понеделник|Вторник|Сряда|Четвъртък|Петък|Събота");
		//$("#date").val("(\\d+\\s(ян|фев|март|април|май|юни|юли|август|септември|октомври|ноември|дек)\.?)$");
  		//$("#date").val("(\\d+\\s(януари|фев|март|април|май|юни|юли|август|септември|октомври|ноември|декември))$");
		//$("#subtitle").val("(^[1-5]|^ВЪПРОСИ ЗА ЛИЧЕН ПРЕГОВОР)");
		//$("#citation-style").val("^[а|б|в|г|д|е|ж]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(\\d+ .+ \\b[1-9][0-9]{3}\\b г\\.)");
		repPattern = {};
	}

	else if ( langCode == "pc"){
		//$("#language").val("Cebuano");
		$("#lang").val("pc");
		//$("#lang_code3").val("ceb");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Pasiuna)");
		$("#pattern-article").val("(LEKSYON ([0-9]+))");
		$("#pattern-title").val("(Sabado, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Gisugyot nga [mga ]*Ba[la]*sahon\\:)\\s*(.+)?");
		//$("#day").val("Domingo|Lunes|Martes|Mierkules|Huebes|Biernes|Sabado"); //Miyerkules|Huwebes|Biyernes|Hueves
		//$("#date").val("((Enero|Febrero|Marso|Abril|Mayo|Hunyo|Hulyo|Agosto|Septyembre|Oktobre|Nobyembre|Desyembre) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^PERSONAL SUBLI NGA MGA PANGUTANA)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabado, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "ny"){
		//$("#language").val("Chichewa");
		$("#lang").val("ny");
		//$("#lang_code3").val("nya");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(MAWU OTSOGOLERA)");
		$("#pattern-article").val("(PHUNZIRO ([0-9]+))");
		$("#pattern-title").val("(Sabata, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))\\.*");
		$("#pattern-auther").val("(Zowelenga zoonjezera\\:)\\s*(.+)?");
		//$("#day").val("Loyamba|Lachiwiri|Lachitatu|Lachinayi|Lachisanu|Lachisanu ndi chimodzi|Lachisanu ndi chiwiri");
		//$("#date").val("((Januwale|Febuluwale|Marichi|Epulo|Meyi|Juni|Julaye|Ogasiti|Sepitembala|Okotobala|Novembala|Disembala) (\\d+))");
		//$("#subtitle").val("(^[1-6]|^MAFUNSO)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabata, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))\\.*");
		repPattern = {};
	}

	else if ( langCode == "zh"){
		//$("#language").val("Chinese");
		$("#lang").val("zh");
		//$("#lang_code3").val("zho");
		$("#pattern-bibliography").val("。");
		$("#pattern-biblioentry").val("(前\\s*言)");
		$("#pattern-article").val("(第\\s*(.+)\\s*课)");
		$("#pattern-title").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		$("#pattern-auther").val("(建议阅读：)\\s*(.+)?");
		//$("#day").val("星期日，|星期一，|星期二，|星期三，|星期四，|星期五，|安息日");
		//$("#date").val("((1月|2月|3月|4月|5月|6月|7月|8月|9月|10月|11月|12月)\\s*[0-9]日)");
		//$("#subtitle").val("^([1-5]\\.|个人复习题)");
		//$("#citation-style").val("^[a-g]");
		//$("#rev_question").val("^[1-5]");
		//$("#refer_text").val("(^“)|(”)");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		repPattern = {};
	}

	else if ( langCode == "hr"){
		//$("#language").val("Croatian");
		$("#lang").val("hr");
		//$("#lang_code3").val("hrv");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Predgovor)");
		$("#pattern-article").val("(([0-9]+)\\. lekcija)");
		$("#pattern-title").val("(Subota, (\\d+\\.\\s.+ \\b[1-9][0-9]{3}\\b\\.))");
		$("#pattern-auther").val("(Predlažemo da pročitate:)\\s*(.+)?");
		//$("#day").val("Nedjelja,|Ponedjeljak,|Utorak,|Srijeda,|Četvrtak,|Petak,|Subota");
		//$("#date").val("(\\d+\. (Siječanja|Veljače|Ožujka|Travanj|Svibanj|Lipanj|srpnja|kolovoz|Rujna|Listopad|Studeni|Prosinca))");
		//$("#subtitle").val("(^[1-5]|^PITANJA)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Subota, (\\d+\\.\\s.+ \\b[1-9][0-9]{3}\\b\\.))");
		repPattern = {};
	}

	else if ( langCode == "cs"){
		//$("#language").val("Czech");
		$("#lang").val("cs");
		//$("#lang_code3").val("ces");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Předmluva)");
		$("#pattern-article").val("(LEKCE ([0-9]+))");
		$("#pattern-title").val("(SOBOTA\\s(\\d+\\.\\s.+\\s[1-9][0-9]{3}))");
		$("#pattern-auther").val("(Doporučená četba:)\\s*(.+)");
		//$("#day").val("Ne|Po|Út|St|Čt|Pá|Sobota");
  		//$("#day").val("Neděle|Pondělí|Úterý|Středa|Čtvrtek|Pátek|Sobota");
		//$("#date").val("((\\d+)\\.\\s(1|2|3|4|5|6|7|8|9|10|11|12))$");
//	//$("#date").val("((\\d+)\\.\\s(leden|únor|březen|duben|květen|červen|červenec|srpen|září|říjen|listopad|prosinec))$");
		//$("#subtitle").val("(^[1-5]|^OTÁZKY K OPAKOVÁNÍ)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(.“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sobota\\s(\\d+\\.\\s.+\\s[1-9][0-9]{3}))");
		repPattern = {};
	}

	else if ( langCode == "nl"){
		//$("#language").val("Dutch");
		$("#lang").val("nl");
		//$("#lang_code3").val("nld");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Voorwoord)");
		$("#pattern-article").val("(Les ([0-9]+))");
		$("#pattern-title").val("(SABBAT, (\\d+\\s+(januari|februari|maart|april|mei|juni|juli|augustus|september|oktober|november|december)\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(Aanvullende studie\\s*:)\\s*(.+)?");
		//$("#day").val("Zo,|Ma,|Di,|Wo,|Do,|Vr,|SABBAT");
  		//$("#day").val("ZONDAG|MAANDAG|DINSDAG|WOENSDAG|DONDERDAG|VRIJDAG|SABBAT");
		//$("#date").val("((\\d+)\\s+(januari|februari|maart|april|mei|juni|juli|augustus|september|oktober|november|december))"); 
		//$("#subtitle").val("(^[1-5]|^TERUGBLIK)");
		//$("#citation-style").val("^[A-G]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(SABBAT, (\\d+\\s+(januari|februari|maart|april|mei|juni|juli|augustus|september|oktober|november|december)\\s+(\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "ee"){
		//$("#language").val("Ewe");
		$("#lang").val("ee");
		//$("#lang_code3").val("ewe");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(ŊGƆDONYA)");
		$("#pattern-article").val("(Nusɔsrɔ̃ ([0-9]+))");
		$("#pattern-title").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Nuxexlẽ si wona:)\\s*(.+)?");
  		//$("#day").val("Kwasiɖagbe|Dzoɖagbe|Braɖagbe|Kuɖagbe|Yawoɖagbe|Fiɖagbe|Sabat");
		//$("#day").val("Kwa\.,|Dzo\.,|Braɖ\.,|Kuɖ\.,|Yaw\.,|Fiɖ\.,|Sabat");
		//$("#date").val("((Afɔfiɛ|Dame|Masa|Afɔfiɛ|Dame|Masa|July|August|September|October|November|Tedoxe) (\\d+))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^AME ÐOKUI ƑE NYABIASEWO)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "fj"){
		//$("#language").val("Fijian");
		$("#lang").val("fj");
		//$("#lang_code3").val("fij");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Ai Vakamacala Taumada)");
		$("#pattern-article").val("(Lesoni ([0-9]+))");
		$("#pattern-title").val("(Sigatabu, ([a-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Ai vola me qai wiliki:)\\s*(.+)?");
		//$("#day").val("Siga Sade|Siga Moniti|Siga Tusiti|Siga Vukelulu|Siga Lotulevu|Siga Vakaraubuka|Siga Tabu");
		//$("#date").val("((\\d+(er)*)\\s*(Janueri|Feperueri|Maji|Epereli|Me|Jiune|Jiulai|Okosita|Seviteba|Okotova|Noveba|Tiseba))");
		//$("#subtitle").val("(^[1-5].|^NA VEI TARO ME RAICI-LESU KINA NA VULI)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("");
		repPattern = {};
	}

	else if ( langCode == "fi"){
		//$("#language").val("Finnish");
		$("#lang").val("fi");
		//$("#lang_code3").val("fin");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Alkusanat)");
		$("#pattern-article").val("(Läksy ([0-9]+))");
		$("#pattern-title").val("(Sapattina, ([A-Za-z]+.+ \\d+. \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Ehdotettua lukemista*:)\\s*(.+)?");
		//$("#day").val("Sunnuntai|Maanantai|Tiistai|Keskiviikko|Torstai|Perjantai|Sapattina");
		//$("#date").val("((Tammikuu|Helmikuu|Maaliskuu|Huhtikuu|Toukokuu|Kesäkuu|Heinäkuu|Elokuu|Syyskuun|Lokakuun|Marraskuun|Joulukuun) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^HENKILÖKOHTAISIA KERTAUSKYSYMYKSIÄ)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("");
		repPattern = {};
	}

	else if ( langCode == "fr"){
		//$("#language").val("French");
		$("#lang").val("fr");
		//$("#lang_code3").val("fra");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Avant-propos)");
		$("#pattern-article").val("(Leçon ([0-9]+))");
		$("#pattern-title").val("(Sabbat (\\d+(er|ᵉʳ)*\\s*(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(Lectures? proposées?\\s*:)\\s*(.+)?");
		//$("#day").val("Dimanche|Lundi|Mardi|Mercredi|Jeudi|Vendredi|Sabbat");
		//$("#date").val("((\\d+(er|ᵉʳ)*)\\s*(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre))");
		//$("#subtitle").val("(^[1-5]|^RÉVISION ET)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabbat (\\d+(er|ᵉʳ)*\\s*(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "de"){
		//$("#language").val("Deutsch");
		$("#lang").val("de");
		//$("#lang_code3").val("deu");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Vorwort)");
		$("#pattern-article").val("(([0-9]+)\\.\\s+Lektion)");
		$("#pattern-title").val("(Sabbat,\\s+den\\s+(\\d+\\.\\s+(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(Zum Lesen empfohlen\\s*:)\\s*(.+)?");
		//$("#day").val("\\(So[nntag]*\\)|\\(Mo[ntag]*\\)|\\(Di[enstag]*\\)|\\(Mi[ttwoch]*\\)|\\(Do[nnerstag]*\\)|\\(Fr[eitag]*\\)|Sabbat");
		//$("#date").val('((\\d+)\\.\s*(\\d+)\\.)');
		  		//$("#date").val("((\\d+\\.)\\s+(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember))\\s*$"); 
		//$("#subtitle").val("(^[1-5]|^Fragen zur persönlichen)");
		//$("#citation-style").val("^[A-G]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabbat,\\s+den\\s+(\\d+\\.\\s+(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)\\s+(\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "hi"){
		//$("#language").val("Hindi");
		$("#lang").val("hi");
		//$("#lang_code3").val("hin");
		$("#pattern-bibliography").val("।");
		$("#pattern-biblioentry").val("(प्रस्तावना)");
		$("#pattern-article").val("(पाठ\\s+([0-9]+))");
		$("#pattern-title").val("(सब्बथ,\\s+(\\d+\\s+(जनवरी|फरवरी|मार्च|अप्रैल|मई|जून|जुलाई|अगस्त|सितंबर|सितम्बर|अक्टूबर|नवंबर|दिसंबर),\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(सुझाया गया पढ़ना:)\\s*(.+)?");
		//$("#day").val("रविवार|सोमवार|मंगलवार|बुधवार|गुरुवार|शुक्रवार|सब्बथ");
		//$("#date").val("((\\d+\\.)\\s+(जनवरी|फरवरी|मार्च|अप्रैल|मई|जून|जुलाई|अगस्त|सितम्बर|अक्टूबर|नवंबर|दिसंबर))"); 
		//$("#subtitle").val("(^[1-5]|^व्यक्तिगत समीक्षा के प्रश्न)");
		//$("#citation-style").val("^[क-घ]\\)");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^\")|(“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(सब्बथ,\\s+(\\d+\\s+(जनवरी|फरवरी|मार्च|अप्रैल|मई|जून|जुलाई|अगस्त|सितंबर|सितम्बर|अक्टूबर|नवंबर|दिसंबर),\\s+(\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "hl"){
		//$("#language").val("Hiligaynon");
		$("#lang").val("hl");
		//$("#lang_code3").val("hil");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Pasiuna)");
		$("#pattern-article").val("(Leksion ([0-9]+))");
		$("#pattern-title").val("(Sabado, ([A-Za-z]+ \\d+,\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Ginapanugda nga Balasahon:)\\s*(.+)?");
		//$("#day").val("Dom,|Lun,|Mar,|Mier,|Hwe,|Bier,|Sabado");
  		//$("#day").val("Domingo|Lunes|Martes|Miyerkules|Huwebes|Biyernes|Sabado");
		//$("#date").val("((\\d+)\\s*(Ene|Feb|Mar|Apr|Mag|Giu|Lug|Ago|Set|Okt|Nob|Dis))"); //(?!,)";
  		//$("#date").val("((\\d+)\\s*(Enero|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|Setyembre|Oktobre|Nobyembre|Disyembre))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^REPASO)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabado, ([A-Za-z]+ \\d+,\\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "ht"){
		//$("#language").val("Haitian");
		$("#lang").val("ht");
		//$("#lang_code3").val("hat");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Entwodiksyon)");
		$("#pattern-article").val("(Leson ([0-9]+))");
		$("#pattern-title").val("(Saba (\\d+ [A-Za-z]+ \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Lekti pwopoze*:)\\s*(.+)?");
		//$("#day").val("Dimanch|Lendi|Madi|Mèkredi|Jedi|Vandredi|Saba");
		//$("#date").val("((Janvye|Fevriye|Mas|Avril|Me|Jen|Jiyè|Out|Septanm|Oktòb|Novanm|Desanm) (\\d+))");
		//$("#subtitle").val("(^[1-5]|KESYON POU REVIZYON PÈSONÈL)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Saba (\\d+ [A-Za-z]+ \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "hu"){
		//$("#language").val("Hungarian");
		$("#lang").val("hu");
		//$("#lang_code3").val("hun");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(ELŐSZÓ)");
		$("#pattern-article").val("([0-9]+\\.?\\s+Tanulmány)");
		$("#pattern-title").val("(([1-9][0-9]{3}.+\\.) Szombat)$");
		$("#pattern-auther").val("^(Javasolt olvasmány:)\\s*(.+)?");
		//$("#day").val("Vasárnap|Hétfő|Kedd|Szerda|Csütörtök|Péntek|Szombat");
		//$("#date").val("((Jan|Feb|Márc|Ápr|Máj|Jún|Júl|Aug|Szep|Okt|Nov|Dec)(\\.\\s*\\d+\.))");
		//$("#date").val("\\s*(január|február|március|április|május|június|július|augusztus|szeptember|október|november|december)$"); 
		//$("#subtitle").val("(^[1-5]\\.|^SZEMÉLYES)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(([1-9][0-9]{3}.+\\.) Szombat)$");
		repPattern = {};
	}

	else if ( langCode == "il"){
		//$("#language").val("Ilocano");
		$("#lang").val("il");
		//$("#lang_code3").val("ilo");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Pauna a Sarita)");
		$("#pattern-article").val("(Leksion ([0-9]+))");
		$("#pattern-title").val("(Sabado, ([A-Za-z]+ \\d+. \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Maisingasing a Basbasaen*:)\\s*(.+)?");
		//$("#day").val("Domingo|Lunes|Martes|Mierkules|Hueves|Viernes|Sabado");
		//$("#date").val("((\\d+)\\s*(Enero|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|Disyembre))");
		//$("#subtitle").val("(^[1-5]|^PERSONAL A PANGREPASO A SALSALUDSOD)");
		//$("#citation-style").val("^[a-gk]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabado, ([A-Za-z]+ \\d+. \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "id"){
		//$("#language").val("Indonesian");
		$("#lang").val("id");
		//$("#lang_code3").val("ind");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Pendahuluan)");
		$("#pattern-article").val("(Pelajaran ([0-9]+))");
		$("#pattern-title").val("(SABAT, (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Bacaan Dianjurkan\s*:|Bacaan yang dianjurkan*:)\s*(.+)?");
		//$("#day").val("Min|Sen|Sel|Rab|Kam|Jum|Sabat");
  		//$("#day").val("Minggu|Senin|Selasa|Rabu|Kamis|Jumat|Sabat");
		//$("#date").val("((Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agu|Sep|Okt|Nov|Des) (\\d+))");
		//$("#date").val("((Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember) (\\d+))");
		//$("#subtitle").val("(^[1-5]\\.|^PERTANYAAN ULANGAN PRIBADI)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabat, (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}
	else if ( langCode == "it"){
		//$("#language").val("Italian");
		$("#lang").val("it");
		//$("#lang_code3").val("ita");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Prefazione)");
		$("#pattern-article").val("(([0-9]+)a\\s*Lezione)");
		$("#pattern-title").val("(Sabato, (\\d+\\s*(gennaio|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|dicembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(Letture consigliate\\s*:)\\s*(.+)?");
		//$("#day").val("Domenica,|Lunedì,|Martedì,|Mercoledì,|Giovedì,|Venerdì,|Sabato");
		//$("#date").val("((\\d+)\\s*(gennaio|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|dicembre))");
		//$("#subtitle").val("(^[1-5]|^Domande)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabato, (\\d+\\s*(gennaio|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|dicembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "ja"){
		//$("#language").val("Japanese");
		$("#lang").val("ja");
		//$("#lang_code3").val("jpn");
		$("#pattern-bibliography").val("^引用：");	
		$("#pattern-biblioentry").val("(^\\d+\\.)");
		$("#pattern-article").val("(^@)|(^編集記)|(^\\d{2,4}年\\d+月\\d+日..日$)");
		$("#pattern-title").val("(^#)|(^[^\\.\\?\\!]{1,50}$)");
		$("#pattern-auther").val("(^-)");
		//$("#day").val("日曜日|月曜日|火曜日|水曜日|木曜日|金曜日|安息日");
		//$("#date").val("((1月|2月|3月|4月|5月|6月|7月|8月|9月|10月|11月|12月)\\s*[0-9]+日)");
		//$("#subtitle").val("^([1-5]\\.|個人的な復習問題)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("(^[「｢])|([」｣])");
		//$("#ref_source").val("( p[0-9]+)");
		//$("#fso_date").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		repPattern = {};
	}

	else if ( langCode == "ko"){
		//$("#language").val("Korean");
		$("#lang").val("ko");
		//$("#lang_code3").val("kor");
		$("#pattern-bibliography").val("References");
		$("#pattern-biblioentry").val("(\\]\\s*$)|(\\d+\\.$)|(http)|(Ibid\\.)| (18\\d\\d\\.)|(19\\d\\d\\.)");
		$("#pattern-article").val("(^@)|(^편집자 서문)|(^\\d\\d월 \\d+일 ..일$)");
		$("#pattern-title").val("(^#)|(^[^\\.\\?\\!]{1,50}$)");
		$("#pattern-auther").val("(^-)");
		//$("#day").val("일요일|월요일|화요일|수요일|목요일|금요일|안식일");
		//$("#date").val("(((1월|2월|3월|4월|5월|6월|7월|8월|9월|10월|11월|12월)\\s+[0-9]+일))");
		//$("#subtitle").val("^([1-5]\\.|복습과 생각할 문제)");
		//$("#citation-style").val("^[가-하]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("(^“)|(”)");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("((20[0-9][0-9])년\\s+([0-9]+)월\\s+([0-9])+일\\s+안식일)");
		repPattern = {};
	}

	else if ( langCode == "ln"){
		//$("#language").val("Lingala");
		$("#lang").val("ln");
		//$("#lang_code3").val("lin");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Maloba ya mokuse)");
		$("#pattern-article").val("(Liteya ya .* \([0-9]+\))");
		$("#pattern-title").val("(Sabata ya (\\d+/\\d+/\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Mokanda ya kotanga:)\\s*(.+)?");
		//$("#day").val("Mokolo ya liboso|Mokolo ya mibale|Mokolo ya misato|Mokolo ya minei|Mokolo ya mitano|Mokolo ya motoba");
		//$("#date").val("ya (\\d+/\\d+/\\b[1-9][0-9]{3}\\b)$");
		//$("#subtitle").val("(^[1-5]|^KOZONGELA LITEYA YA MOTO YE MOKO)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabata ya (\\d+/\\d+/\\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "lg"){
		//$("#language").val("Luganda");
		$("#lang").val("lg");
		//$("#lang_code3").val("lug");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(EBYANJULA)");
		$("#pattern-article").val("(Lesson ([0-9]+))");
		$("#pattern-title").val("(Sabbiti, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Mokanda ya kotanga:)\\s*(.+)?");
		//$("#day").val("Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Sabbiti");
		//$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^Makonka a kuambulula)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("Sabbiti, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "mg"){
		//$("#language").val("Malagasy");
		$("#lang").val("mg");
		//$("#lang_code3").val("mlg");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Teny fanolorana)");
		$("#pattern-article").val("(Lesona ([0-9]+))");
		$("#pattern-title").val("(Sabata (\\d+\\s[A-Za-z]+\\s\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Atolotra ho vakiana:)\\s*(.+)?");
		//$("#day").val("Alahady|Alatsinainy|Talata|Alarobia|Alakamisy|Zoma|Sabata");
  		//$("#date").val("((Janoary|February|Martsa|Aprily|May|Jona|Jolay|Aogositra|Septambra|Oktobra|Novembra|Desambra) (\\d+))");
		//$("#date").val("((\\d+/\\d+))");
		//$("#subtitle").val("(^[1-5]|FAMERENANA ATAON’ NY TENA MANOKANA)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(SABATA (\\d+\\s[A-Za-z]+\\s\\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "mk"){
		//$("#language").val("Macedonian");
		$("#lang").val("mk");
		//$("#lang_code3").val("mkd");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Предговор\:?)");
		$("#pattern-article").val("(([0-9]+) ЛЕКЦИЈА)");
		$("#pattern-title").val("(САБОТА, \\d+\\. (јануари|февруари|март|април|мај|јуни|јули|август|септември|октомври|ноември|декември) \\b[1-9][0-9]{3}\\b)\\.");
		$("#pattern-auther").val("(Предлагаме да прочитате*:)\\s*(.+)?");
		//$("#day").val("Нед|Пон|Вто|Сре|Чет|Пет|Сабота");
  		//$("#day").val("Недела|Понеделник|Вторник|Среда|Четврток|Петок|Сабота");
		//$("#date").val("(\\d+\\.\\s(јан|фев|мар|апр|мај|јун|јул|авг|сеп|окт|ное|дек))$");
  		//$("#date").val("(\\d+\\.\\s(јануари|февруари|март|април|мај|јуни|јули|август|септември|октомври|ноември|декември))$");
		//$("#subtitle").val("(^[1-5]|^ЛИЧЕН ПРЕГЛЕД НА ПРАШАЊАТА)");
		//$("#citation-style").val("^[а|б|в|г|д|ѓ|е]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(САБОТА, \\d+\\. (јан|фев|мар|апр|мај|јун|јул|авг|сеп|окт|ное|дек) \\b[1-9][0-9]{3}\\b)\\.");
		repPattern = {};
	}

	else if ( langCode == "ms"){
		//$("#language").val("Malaysian");
		$("#lang").val("ms");
		//$("#lang_code3").val("msa");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Kata Pengantar)");
		$("#pattern-article").val("(Pelajaran ([0-9]+))");
		$("#pattern-title").val("(Sabat, ([A-Z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Cadangan Bacaan:)\\s*(.+)?");
		//$("#day").val("Ahad|Isnin|Selasa|Rabu|Khamis|Jumaat|Sabat");
		//$("#date").val("((January|February|March|April|May|June|July|August|September|Oktober|November|Disember) (\\d+))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|SOALAN)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabat, (\\d+ [A-Za-z]+ \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "mn"){
		//$("#language").val("Mongolian");
		$("#lang").val("mn");
		//$("#lang_code3").val("mon");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Өмнөх Үг)");
		$("#pattern-article").val("(Хичээл ([0-9]+))");
		$("#pattern-title").val("(Амралтын Өдөр, (\\d+-р\\sсарын\\s\\d+,\\s\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("^([^:]+:)\\s*(.+)?");
		//$("#day").val("Ням|Даваа|Мягмар|Лхагва|Пүрэв|Баасан|Aмралтын Өдөр");
		//$("#date").val("((\\d+)\\s+(Hэгдүгээр сарын|Хоёрдугаар сарын|Гуравдугаар сар|Дөрөвдүгээр сар|Тавдугаар сар|Зургадугаар сар|долдугаар|Наймдугаар|Есдүгээр|Аравдугаар сар|Арваннэгдүгээр сар|Арванхоёрдугаар сар))");
		//$("#subtitle").val("(^[1-5]|^ХУВИЙН ТОЙМ Асуултууд Зүүд)");
		//$("#citation-style").val("^[а|б|в|г|д]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("(^\“)|(\")");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Амралтын Өдөр, (\\d+-р\\sсарын\\s\\d+,\\s\\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "mq"){
		//$("#language").val("Miskito");
		$("#lang").val("mq");
		//$("#lang_code3").val("miq");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(TA BILA)");
		$("#pattern-article").val("(Lisan ([0-9]+))");
		$("#pattern-title").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Kau aisikaikaia*:)\\s*(.+)?");
		//$("#day").val("Sandi|Mundi|Tiusdi|Wensdi|Tausdi|Praidi|Sabat");
		//$("#date").val("((Siakwa kati|Kuswa kati|Kakamuk kati|Lih wainhka kati|Lih mairin kati|Li kati|Pastara kati|Sikla kati|Wis kati|Waupasa kati|Yahbra kati|Krismis kati), (\\d+))");
		//$("#subtitle").val("(^[1-5]|^MAKABANKA LUAN NANI BA LAKI KAIKS)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(\"))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "nd"){
		//$("#language").val("Ndebele");
		$("#lang").val("nd");
		//$("#lang_code3").val("nde");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Ilizwi Lokuvula)");
		$("#pattern-article").val("(Isifundo ([0-9]+))");
		$("#pattern-title").val("(Sabatha, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Ukufunda Okuvavanyiweyo:)\\s*(.+)?");
		//$("#day").val("Sonto|Mvulo|Lwesibili|Lwesithathu|Lwesine|Lwesihlanu|Sabatha");
		//$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^NA VEITARO E SO ME TALEVI-LESU KINA NA LESONI)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(\"))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("Sabatha, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "pt"){
		//$("#language").val("Portuguese");
		$("#lang").val("pt");
		//$("#lang_code3").val("por");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Prefácio)");
		$("#pattern-article").val("(Lição ([0-9]+))");
		$("#pattern-title").val("(Sábado, (\\d+(º)* de (Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro) de (\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(Estudo adicional:)\\s*(.+)?");
		//$("#day").val("Domingo,|Segunda-feira,|Terça-feira,|Quarta-feira,|Quinta-feira,|Sexta-feira,|Sábado");
		//$("#date").val("((\\d+(º)*) de (Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro))");
		//$("#subtitle").val("(^[1-5]|PARA)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sábado, (\\d+(º)* de (Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro) de (\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "rn"){
		//$("#language").val("Kirundi");
		$("#lang").val("rn");
		//$("#lang_code3").val("run");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(INTANGA MARARA)");
		$("#pattern-article").val("(Lesson ([0-9]+))"); //ICIRWACA MBERE, ICIGWACA KABIRI, ICIRWACA3, ICIRWACAKANE, ICIGWACAGATANU
		$("#pattern-title").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Suggested Readings*:)\\s*(.+)?");
		//$("#day").val("Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Sabbath");
		//$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^Review)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "ro"){
		//$("#language").val("Romanian");
		$("#lang").val("ro");
		//$("#lang_code3").val("ron");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("Cuvânt înainte"); // ţ or ț
		$("#pattern-article").val("(Lecția ([0-9]+))");
		$("#pattern-title").val("(Sabat, (\\d+ [A-Za-z]+, [1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("^(Recomandare pentru studiu:)\\s*(.+)?");
		//$("#day").val("Duminică|Luni|Marți|Miercuri|Joi|Vineri|Sabat");
		//$("#date").val("((\\d+)\\s+(ianuarie|februarie|martie|aprilie|mai|iunie|iulie|august|septembrie|octombrie|noiembrie|decembrie))");
		//$("#subtitle").val("(^[1-5]|^Întrebări)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabat, (\\d+ [A-Za-z]+, [1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "ru"){
		//$("#language").val("Russian");
		$("#lang").val("ru");
		//$("#lang_code3").val("rus");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Предисловие)");
		$("#pattern-article").val("(УРОК ([0-9]+))");
		$("#pattern-title").val("(Суббота,\\s+(\\d+\\s+.+\\s+[1-9][0-9]{3})\\s+.+)");
		$("#pattern-auther").val("(Дополнительные материалы для изучения:)\\s*(.+)?");
		//$("#day").val("Вс|Пн|Вт|Ср|Чт|Пт|Суббота");
  		//$("#day").val("Воскресенье|Понедельник|Вторник|Среда|Четверг|Пятница|Суббота");
		//$("#date").val("((\\d+)\\s(янв|фев|мар|апр|май|июн|июл|авг|сен|окт|ноя|дек)\\.)$");
//	//$("#date").val("((\\d+)\\s(январь|февраль|март|апрель|май|июнь|июль|август|сентябрь|октябрь|ноябрь|декабрь))$");
		//$("#subtitle").val("(^[1-5]|^ВОПРОСЫ ДЛЯ ПОВТОРЕНИЯ)");
		//$("#citation-style").val("^[а|б|в|г|д|е|ж|g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^«)|(»))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Суббота,\\s+(\\d+\\s+.+\\s+[1-9][0-9]{3})\\s+.+)");
		repPattern = {};
	}

	else if ( langCode == "rw"){
		//$("#language").val("Rwandese");
		$("#lang").val("rw");
		//$("#lang_code3").val("kin");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Ijambo ry’Ibanze)");
		$("#pattern-article").val("(Icyigisho ([0-9]+))");
		$("#pattern-title").val("(Ku Isabato, (\\d+ [A-Za-z]+,* \\b[1-9][0-9]{3}\\b))\.*");
		$("#pattern-auther").val("(Ibitabo Byifashishijwe\\s*:)\\s*(.+)?");
		//$("#day").val("Kuwa Mbere|Kuwa Kabiri|Kuwa Gatatu|Kuwa Kane|Kuwa Gatanu|Kuwa Gatandatu|Ku Isabato|ISABATO YO KUWA");
		//$("#date").val("(\\b(\\d+)\\s+(Mutarama|Gashyantare|Werurwe|Mata|Gicurasi|Kamena|Nyakanga|Kanama|Nzeri|Ukwakira|Ugushyingo|Ukuboza))");
		//$("#subtitle").val("(^[1-5]|^6. IBIBAZO BYO KUZIRIKANWA)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Ku Isabato, (\\d+ [A-Za-z]+,* \\b[1-9][0-9]{3}\\b))\.*");
		repPattern = {};
	}

	else if ( langCode == "si"){
		//$("#language").val("Sinhala");
		$("#lang").val("si");
		//$("#lang_code3").val("sin");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(පෙරවදන)");
		$("#pattern-article").val("(පාඩම් ([0-9]+))");
		$("#pattern-title").val("(සබත්, .* \\d+, \\b[1-9][0-9]{3}\\b)");
		$("#pattern-auther").val("(යෝජිත කියවීම*:)\\s*(.+)?");
		//$("#day").val("ඉරිදා|සඳුදා|අඟහරුවාදා|බදාදා|බ්‍රහස්පතින්දා|සිකුරාදා|සබත්");
		//$("#date").val("((January|February|මාර්තු|අප්‍රේල්|මැයි|ජුනි|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^පුද්ගලික සමාලෝචන ප්‍රශ්න)");
		//$("#citation-style").val("^[A-G]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(සබත්, .* \\d+, \\b[1-9][0-9]{3}\\b)");
		repPattern = {};
	}

	else if ( langCode == "sm"){
		//$("#language").val("Samoan");
		$("#lang").val("sm");
		//$("#lang_code3").val("smo");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Upu Tomua)");
		$("#pattern-article").val("(Lesona ([0-9]+))");
		$("#pattern-title").val("(Sapati,* ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Faitauga Fautuaina\\s*:)\\s*(.+)?");
		//$("#day").val("Ulua’*i Aso|Aso Gafua|Aso Lua|Aso Lulu|Aso Tofi|Aso Faraile|Sapati");
		//$("#date").val("(\\b(Ianuari|Fepuari|Marti|Aperila|Me|Iuni|Iulai|Aokuso|Setema|Oketopa|Novema|Tesema)\\s+(\\d+))");
		//$("#subtitle").val("(^[1-5]|^FESILI TOE FAAMANATU)");
		//$("#citation-style").val("^[a-giou]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sapati,* ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "sr"){
		//$("#language").val("Serbian-Cyrillic");
		$("#lang").val("sr");
		//$("#lang_code3").val("srp");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("^(Предговор:?)");
		$("#pattern-article").val("(Лекција ([0-9]+))");
		$("#pattern-title").val("((Субота,* \\d+\\. .+ \\b[1-9][0-9]{3}\\b)\.*)");
		$("#pattern-auther").val("^(Предлажемо да прочитате:)\\s*(.+)?");
		//$("#day").val("Недеља|Понедељак|Уторак|Среда|Четвртак|Петак|Субота");
		//$("#date").val("((Јануара|Фебруара|Марта|Април|Маја|Јуна|Јула|Августа|Септембар|Октобар|Новембар|Децембар) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^ПИТАЊА ЗА ЛИЧНО РАЗМИШЉАЊЕ\\:?)");
		//$("#citation-style").val("^[а|б|ц|в|г|д|ђ]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(“))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("((Субота,* \\d+\\. .* \\b[1-9][0-9]{3}\\b)\.*)");
		repPattern = {};
	}

	else if ( langCode == "srr"){
		//$("#language").val("Serbian - Romanised");
		$("#lang").val("sr");
		//$("#lang_code3").val("srp");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(PREDGOVOR)\:");
		$("#pattern-article").val("(Lekcija ([0-9]+))");
		$("#pattern-title").val("(([A-Za-z]+, \\d+\. [A-Za-z]+ \\b[1-9][0-9]{3}\\b)\.)");
		$("#pattern-auther").val("(Predlažemo da pročitate:)\\s*(.+)?");
		//$("#day").val("Nedelja|Ponedeljak|Utorak|Sreda|Četvrtak|Petak|Subota");
		//$("#date").val("((\\d+)\.)\\s*(januar|februar|mart|april|maj|jun|juli|avgust|septembar|oktobar|novembar|decembar)$");
		//$("#subtitle").val("(^[1-5]|PITANJA ZA LIČNO RAZMIŠLJANJE)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^„)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(([A-Za-z]+, \\d+\. [A-Za-z]+ \\b[1-9][0-9]{3}\\b)\.)");
		repPattern = {};
	}

	else if ( langCode == "sk"){
		//$("#language").val("Slovak");
		$("#lang").val("sk");
		//$("#lang_code3").val("slk");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(PREDSLOV)");
		$("#pattern-article").val("(([0-9]+)\\.?\\s+úloha)");
		$("#pattern-title").val("(Sobota,\\s+(.+))$");
		$("#pattern-auther").val("^([^:]+:)\\s*(.+)?");
		//$("#day").val("Nedeľa|Pondelok|Utorok|Streda|Štvrtok|Piatok|Sobota");
		//$("#date").val("\\s*(.+)$"); 
		//$("#subtitle").val("(^[1-5]|^OTÁZKY)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("");
		repPattern = {};
	}

	else if ( langCode == "st"){
		//$("#language").val("Sotho");
		$("#lang").val("st");
		//$("#lang_code3").val("sot");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(SELELEKELA)");
		$("#pattern-article").val("(Thuto ([0-9]+))");
		$("#pattern-title").val("(Sabbatha (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(O kgothaletswa hore o bale:)\\s*(.+)?");
		//$("#day").val("Sontaha|Mantaha|Labobedi|Laboraro|Labone|Labohlano|Sabata");
		//$("#date").val("((Pherekgong|Hlakola|Hlakubele|Mmesa|Motsheanong|Phupjane|Phupu|Phato|Lwetse|Mphalane|Pudungwana|Tshitwe) (\\d+))");
		//$("#subtitle").val("(^[1-5]\\.|^ITLHATLHOBE KA DIPOTSO TSENA)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabbatha (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "es"){
		//$("#language").val("Spanish");
		$("#lang").val("es");
		//$("#lang_code3").val("spa");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Prefacio)");
		$("#pattern-article").val("(Lección ([0-9]+))");
		$("#pattern-title").val("(Sábado, (\\d+(º)* de (Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre) de (\\b[1-9][0-9]{3}\\b)))");
		$("#pattern-auther").val("(Lectura.* sugerida.*:)\\s*(.+)?");
		//$("#day").val('Dom, |Lun, |Mar, |Mié, |Jue, |Vie, |Sábado');
		//$("#date").val('((\\d+) de (\\w+))');
  		//$("#day").val("Domingo|Lunes|Martes|Miércoles|Jueves|Viernes|Sábado");
  		//$("#date").val("((\\d+(º)*) de (Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre))\\s*$");
		//$("#subtitle").val("(^[1-5]|^PREGUNTAS)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(SÁBADO, (\\d+(º)* de (Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre) de (\\b[1-9][0-9]{3}\\b)))");
		repPattern = {};
	}

	else if ( langCode == "sw"){
		//$("#language").val("Swahili");
		$("#lang").val("sw");
		//$("#lang_code3").val("swa");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Utangulizi)"); //Weche Motelo
		$("#pattern-article").val("(SOMO LA ([0-9]+))");
		$("#pattern-title").val("(Sabato( ya Tarehe)*, (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Inapendekezwa kusoma\\:)\\s*(.+)?"); //Somo moketi //Masomo yaliyopendekezwa
		//$("#day").val("Jpil,|Jtat,|Jnne,|Jtan,|Alh,|Ijum,|Sabato");
  		//$("#day").val("Jumapili Tarehe|Jumatatu Tarehe|Jumanne Tarehe|Jumatano Tarehe|Alhamisi Tarehe|Ijumaa Tarehe|Sabato ya Tarehe");
  		//$("#day").val("Chak Tich|Tich Ariyo|Tich Adek|Tich Ang’wen|Tich Abich|Tich Auchiel|Sabato");
		//$("#date").val("(Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba)\\s*\\d+$");
		//$("#subtitle").val("(^[1-5]|^MASWALI)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabato( ya Tarehe)*, (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "swc"){
		//$("#language").val("Swahili DRC");
		$("#lang").val("sw");
		//$("#lang_code3").val("swc");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(UTANGULIZI)");
		$("#pattern-article").val("(Somo la ([0-9]+))");
		$("#pattern-title").val("(Sabato (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Somo lililopendekezwa:)\\s*(.+)?");
		//$("#day").val("Siku ya kwanza|Siku ya pili|Siku ya tatu|Siku ya ine|Siku ya tano|Siku ya maandalio|Sabato ya Tarehe");
		//$("#date").val("(Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba)\\s*\\d+$");
		//$("#subtitle").val("(^[1-5]|^MASWALI YA KUFIKIRIA)") ;
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|())");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabato (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "th"){
		//$("#language").val("Thai");
		$("#lang").val("th");
		//$("#lang_code3").val("tha");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(คำนำ)");
		$("#pattern-article").val("\\s(บทที่ ([0-9]+))");
		$("#pattern-title").val("(วันสะบาโต, (.* \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(แนะนำให้อ่านเพิ่มเติม:)\\s*(.+)?");
		//$("#day").val("วันอาทิตย์|วันจันทร์|วันอังคาร|วันพุธ|วันพฤหัสบดี|วันศุกร์|วันสะบาโต");
		//$("#date").val("((มกราคม|กุมภาพันธุ์|มีนาคม|เมษายน|อาจ|มิถุนายน|กรกฎาคม|สิงหาคม|กันยายน|ตุลาคม|พฤศจิกายน|ธันวาคม) (\\d+))"); //(?!,)";
		//$("#subtitle").val("(^[1-5]|^คำถามทบทวนส่วนตัว)");
		//$("#citation-style").val("^[ก|ข|ค|ง|จ]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(วันสะบาโต, (.* \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "tl"){
		//$("#language").val("Tagalog");
		$("#lang").val("tl");
		//$("#lang_code3").val("tgl");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Paunang Salita)");
		$("#pattern-article").val("(LEKSIYON ([0-9]+))");
		$("#pattern-title").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(Iminumungkahing Babasahin\\:|Mga Iminumungkahing Babasahin\\:)\\s*(.*)?");
		//$("#day").val("Lin|Lun|Mar|Miy|Huw|Biy|Sabado");
  		//$("#day").val("Linggo|Lunes|Martes|Miyerkules|Huwebes|Biyernes|Sabado");
		//$("#date").val("((Enero|Pebrero|Marso|Abril|Mayo|Hunyo|Hulyo|Agosto|Setyembre|Oktubre|Nobyembre|Disyembre) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^PERSONAL NA MGA KATANUNGAN SA PAGBABALIK-ARAL)");
		//$("#citation-style").val("^[a|b|c|d|e|f|g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}

	else if ( langCode == "ta"){
		//$("#language").val("Tamil");
		$("#lang").val("ta");
		//$("#lang_code3").val("tam");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(முன்னுரை)");
		$("#pattern-article").val("(பாடம் ([0-9]+))");
		$("#pattern-title").val("(ஓய்வுநாள், (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(வாசிக்க பரிந்துரைக்கப்பட்ட பகுதி\\:)\\s*(.+)?");
		//$("#day").val("ஞாயிறு,|திங்கள்,|செவ்வாய்,|புதன்,|வியாழன்,|வெள்ளி,|ஓய்வுநாள்");
		//$("#date").val("((ஜனவரி|பெப்ரவரி|மார்ச்|ஏப்ரல்|மே|ஜூன்|ஜூலை|ஆகஸ்ட்|செப்டம்பர்|அக்டோபர்|நவம்பர்|டிசம்பர்) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^தனிப்பட்ட)");
		//$("#citation-style").val("^[அ|ஆ|இ|ஈ|உ|ஊ|௧|௨|௩|௪|௫|ங|ச|க|உ|ங|ச|ரு]\\.");
		//$("#rev_question").val("^[1-5|௧|௨|௩|௪|௫|க|உ|ங|ச|ரு]\\.");
		//$("#refer_text").val("((^“)|(\"))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(ஓய்வுநாள், (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {
			"க. ":"௧. ",
		};
	}

	else if ( langCode == "lu"){
		//$("#language").val("Tshiluba");
		$("#lang").val("lu");
		//$("#lang_code3").val("lub");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Meyi a mbangilu)");
		$("#pattern-article").val("(Dilesona\\s+([0-9]+))");
		$("#pattern-title").val("(Nsabatu wa (\\d+\\/\\d+\\/[1-9][0-9]{3}))$");
		$("#pattern-auther").val("(Bia\\s*kubala\\s*:)\\s*(.+)?");
		//$("#day").val("Dia kumudilu|Dibidi|Disatu|Dinayi|Ditanu|Disambombo|Nsabatu");
		//$("#date").val("(\\d+\\/\\d+)$"); 
		//$("#subtitle").val("(^[1-5]|^Makonka a kuambulula)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^«|^“)|(»|”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Nsabatu wa (\\d+\\/\\d+\\/[1-9][0-9]{3}))$");
		repPattern = {};
	}

	else if ( langCode == "uk"){
		//$("#language").val("Ukrainian");
		$("#lang").val("uk");
		//$("#lang_code3").val("ukr");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Передмова)");
		$("#pattern-article").val("(УРОК ([0-9]+))");
		$("#pattern-title").val("(СУБОТА, (\\d+ .+ \\b[1-9][0-9]{3}\\b РОКУ))");
		$("#pattern-auther").val("(Додаткові матеріали для вивчення*:)\\s*(.+)?");
		//$("#day").val("Нд, |Пн, |Вт, |Ср, |Чт, |Пт, |Субота");
		//$("#date").val("((\\d+) (Січ|Лют|бер|квіт|трав|черв|Липень|Серпень|Вересня|Жовтень|У листопаді|Груд))");
// 		//$("#day").val("Неділя|Понеділок|Вівторок|Середа|Четвер|П['’]ятниця|Субота");
// 		//$("#date").val("((Січень|Лютого|Березень|Квітень|Травень|Червень|Липень|Серпень|Вересня|Жовтень|У листопаді|Грудень) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^ЗАПИТАННЯ)");
		//$("#citation-style").val("^[а|б|в|г|д|е|ж]\\.") ;
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^«)|(»))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Субота, (\\d+ .+ \\b[1-9][0-9]{3}\\b року))");
		repPattern = {};
	}

	else if ( langCode == "ur"){
		//$("#language").val("Urdu");
		$("#lang").val("ur");
		//$("#lang_code3").val("urd");
		$("#pattern-bibliography").val("۔");
		$("#pattern-biblioentry").val("(پیش لفظ)");
		$("#pattern-article").val("(سبق نمبر ([0-9]+))");
		$("#pattern-title").val("(سبت، .* [1-9][0-9]{3} ,\\d+)");
  		$("#pattern-title_tag").val("<sabbath>$1‎$2</sabbath>");
		$("#pattern-auther").val("()-\\s*(.+)?");
		//$("#day").val("اتوار|سوموار|منگل|بدھ|جمعرات |جمعہ| سبت");
		//$("#date").val("((جنوری|فروری|مارچ|اپریل|مئی|جون|جولائی|اگست|ستمبر|اکتوبر|نومبر|دسمبر) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^ذاتی نظر ثانی کیلئے سوالات)");
  		$("#subtitle_tag").val("<subtitle>‎$1</subtitle>");
		//$("#citation-style").val("^(الف *۔|ب *۔|ج *۔)");
		//$("#rev_question").val("^-[1-5]");
  		$("#rev_question_tag").val("<question>$1</question>");
		//$("#refer_text").val("(()|())");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(سبت، .* [1-9][0-9]{3} ,\\d+)");
		repPattern = {};
	}

	else if ( langCode == "vi"){
		//$("#language").val("Vietnamese");
		$("#lang").val("vi");
		//$("#lang_code3").val("vie");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(LỜI TỰA)");
		$("#pattern-article").val("(Bài học ([0-9]+))");
		$("#pattern-title").val("(Sabát (\\d+\\-\\d+\\-\\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(BÀI ĐỌC GỢI Ý:)\\s*(.+)?");
		//$("#day").val("Chủ Nhật|Thứ Hai|Thứ Ba|Thứ Tư|Thứ Năm|Thứ Sáu|Sabát");
  		//$("#date").val("((\\d+)\\stháng \\d+ năm \\b[1-9][0-9]{3}\\b)");
		//$("#date").val("((Tháng Một|Tháng Hai|Tháng Ba|Tháng Tư|Tháng Năm|Tháng Sáu|Tháng Bảy|Tháng Tám|Tháng Chín|Tháng Mười|Tháng Mười Một|Tháng Mười Hai) (\\d+))");
		//$("#subtitle").val("(^[1-5]|^NHỮNG CÂU HỎI SUY NGẪM)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(Sabát (\\d+\\/\\d+\\/))");
		repPattern = {};
	}

	else if ( langCode == "zu"){
		//$("#language").val("Zulu");
		$("#lang").val("zu");
		//$("#lang_code3").val("zul");
		$("#pattern-bibliography").val(".");
		$("#pattern-biblioentry").val("(Isingeniso)");
		$("#pattern-article").val("(ISIFUNDO ([0-9]+))");
		$("#pattern-title").val("(ISABATHA, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#pattern-auther").val("(I[zi]*ncwadi E[zi]*nikeziwe:)\\s*(.+)?");
		//$("#day").val("NgeSonto,|NgoMsombuluko,|Ngolwesibili,|Ngolwesithathu,|Ngolwesine,|Ngolwesihlanu,|ISabatha");
		//$("#date").val("((Januwari|Februwari|Mashi|April|May|Juni|Julayi|Agasti|Septhemba|Okthoba|Novemba|Disemba) (\\d+))");
		//$("#subtitle").val("(^[1-5]|IMIBUZO YOMUNTU NGAMUNYE)");
		//$("#citation-style").val("^[a-g]\\.");
		//$("#rev_question").val("^[1-5]\\.");
		//$("#refer_text").val("((^“)|(”))");
		//$("#ref_source").val("([-].[0-9]+\\.)");
		//$("#fso_date").val("(ISabatha, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		repPattern = {};
	}
	else {
	// alert("The translation pattern for the chosen language is not available!" );
		//$("#language").val("");
		$("#lang").val("");
		//$("#lang_code3").val("");
		$("#pattern-bibliography").val("");
		$("#pattern-biblioentry").val("");
		$("#pattern-article").val("");
		$("#pattern-title").val("");
		$("#pattern-auther").val("");
		//$("#day").val("");
		//$("#date").val("");
		//$("#subtitle").val("");
		//$("#citation-style").val("");
		//$("#rev_question").val("");
		//$("#refer_text").val("");
		//$("#ref_source").val("");
	}

	// 
	// let str = "";
	// for (const [key, value] of Object.entries(repPattern)) {
    //     str += `${key}=>${value}\n`;
    // }
    // $("#replace_text").val(str);
    
}