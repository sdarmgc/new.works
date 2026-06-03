/*
 * sbl_set_lang_pattern.js
 * 
 * Define all language patterns
 */
var langList = {"en":"English", "am":"Amharic", "sq":"Albanian", "bb": "Bemba", "bn":"Bengali", "bg":"Bulgarian", "pc":"Cebuano", "ny":"Chichewa", "zh":"Chinese", "hr":"Croatian", "cs":"Czech", "nl":"Dutch", "ee":"Ewe", "am":"Ethiopian", "fj":"Fijian", "fi":"Finnish", "fr":"French", "de":"German", "ht":"Haitian", "hi":"Hindi", "hl":"Hiligaynon", "hu":"Hungarian", "il":"Ilocano", "id":"Indonesian", "it":"Italian", "ja":"Japanese", "rn":"Kirundi", "km":"Khmer", "ko":"Korean", "lg":"Luganda", "ln":"Lingala", "mg":"Malagasy", "ms":"Malay", "mk":"Macedonian", "mq":"Miskito", "mn":"Mongolian", "nd":"Ndebele", "pt":"Portuguese", "ro":"Romanian", "ru":"Russian", "rw":"Rwandese", "si":"Sinhala", "sm":"Samoan", "sr":"Serbian (Cyrillic)", "srr":"Serbian (Romanised)", "sk":"Slovakian", "st":"Sotho", "es":"Spanish", "sw":"Swahili", "swc":"Swahili DRC", "th":"Thai",  "tl":"Tagalog","ta":"Tamil", "lu":"Tshiluba", "uk":"Ukrainian", "ur":"Urdu", "vi":"Vietnamese", "zu":"Zulu"};

function setLangList()
{
	listElement = document.getElementById("lang-select");
	for (var lang in langList) {
		listElement.add(new Option(langList[lang], lang));
	}
}

function setLangPattern(langCode)
{
	var repPattern = {};
	
	// this value is overwritten with #lesson_sabbath at the end of the function,
	// unless set in the (specific) language setting.
	$("#fso_date").val(""); 
	
	if ( langCode == "en"){
		$("#language").val("English");
		$("#lang_code").val("en");
		$("#lang_code3").val("eng");
		$("#periodical_name").val("");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Foreword|FOREWORD)");
		$("#lesson_start").val("(Lesson ([0-9]+))");
		$("#lesson_sabbath").val("(SABBATH, ([A-Z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Suggested Readings*:)\\s*(.+)?");
		$("#day").val("Sun[^ ]* |Mon[^ ]* |Tue[^ ]* |Wed[^ ]* |Thu[^ ]* |Fri[^ ]* |Sabbath");
		$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		$("#subtitle").val("^([1-5]|PERSONAL)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("First Sabbath Offering");
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
		$("#language").val("Amharic");
		$("#lang_code").val("am");
		$("#lang_code3").val("amh");
		$("#period_mark").val("፡፡");
		$("#foreword_title").val("^(መቅድም)");
		$("#lesson_start").val("(([0-9]+)ኛ ትምህርት)");
		$("#lesson_sabbath").val("(ሰንበት፣*\\s+(.*\\s+\\d+\\s*[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("^(ለጥናት የተመረ[ጡ|ጠው]* መጽሐፍ[ት]*[:፡])\\s*(.+)?");
		$("#day").val("እሁድ|ሰኞ|ማክሰኞ|ረቡዕ|ሐሙስ|አርብ|ሰንበት");
		$("#date").val("((\\d+)\\s+(መስከረም|ጥቅምት|ኅዳር|ታህሣሥ|ጥር|የካቲት|መጋቢት|ሚያዝያ|ግንቦት|ሰኔ|ሐምሌ|ነሐሴ))");
		$("#subtitle").val("^([1-5]|የግል ግምገማ ጥያቄዎች)");
		$("#day_question").val("^[ሀለሐመሠረሰ]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(ሰንበት፣*\\s+(.*\\s+\\d+\\s*[1-9][0-9]{3}\\b))");
		$("#fso_start").val("የመጀመሪያ ሰንበት ሥጦታ");
		repPattern = {};
	}

	else if ( langCode == "sq"){
		$("#language").val("Albanian");
		$("#lang_code").val("sq");
		$("#lang_code3").val("sqi");
		$("#period_mark").val(".");
		$("#foreword_title").val("(parathënie)");
		$("#lesson_start").val("(Mësimi ([0-9]+)),");
		$("#lesson_sabbath").val("(E Shtunë,\\s+(\\d+\\s+[A-Za-z]+,\\s+[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("^([^:]+:)\\s*(.+)?");
		$("#day").val("E Djelë|E Hënë|E Martë|E Mërkurë|E Enjte|E Premte|E Shtunë,");
		$("#date").val("((\\d+)\\s+(\\w+))");
		$("#subtitle").val("^([1-5]|PYETJE PËR)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
	}

	else if ( langCode == "bb"){
		$("#language").val("Bemba");
		$("#lang_code").val("bb");
		$("#lang_code3").val("bem");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Ishiwi lya Ntanshi)");
		$("#lesson_start").val("(ICISAMBILILO ([0-9]+))");
		$("#lesson_sabbath").val("(ISABATA, ([A-Za-z]+.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Umwakubelenga Mumbi:)\\s*(.+)?");
		$("#day").val("Cimo,|Cibili,|Citatu,|Cine,|Cisano,|Mutanda,|Isabata");
//		$("#day").val("Ubwa Ntanshi|Ubwa Cibili|Ubwa Citatu|Ubwa Cine|Ubwa Cisano|Ubwa Mutanda|Isabata");
		$("#date").val("((Kabengele Kanono|Kabengele Kakalamba|Kutumpu|Shinde|Akapepo Kanono|Akapepo Kakalamba|Cikungulupepo|Akasaka Ntobo|ULusuba Lunono|ULusuba Lukalamba|Cinshikubili|Umupundu Milimo) (\\d+))");
//		$("#date").val("((Kabengele Kanono|Kabengele Kakalamba|Kutumpu|Shinde|Akapepo Kanono|Akapepo Kakalamba|Cikungulupepo|Akasaka Ntobo|ULusuba Lunono|ULusuba Lukalamba|Cinshikubili|Umupundu Milimo) (\\d+))");
		$("#subtitle").val("^([1-5]|AMEPUSHO)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Isabata, ([A-Za-z]+.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Ubupe bwe Sabata lya Ntanshi");
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
		$("#language").val("Bengali");
		$("#lang_code").val("bn");
		$("#lang_code3").val("ben");
		$("#periodical_name").val("");
		$("#period_mark").val("।");
		$("#foreword_title").val("^(মুখপাত্র)");
		$("#lesson_start").val("(পাঠ ([0-9]+))");
		$("#lesson_sabbath").val("(সাব্বাথ, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(প্রস্তাবিত [পড়া|রিডিং]*\\:)\\s*(.+)?");
		$("#day").val("রবি,|সোম,|হয়,|বুধ,|বৃহ,|শুক্র,|সাব্বাথ");
//		$("#day").val("রবিবার|সোমবার|মঙ্গলবার|বুধবার|বৃহস্পতিবার|শুক্রবার|সাব্বাত");
		$("#date").val("((\\d+) (জানুয়ারি|ফেব্রুয়ারি|মার্চ|এপ্রিল|মে|জুন|জুলাই|আগস্ট|সেপ্টেম্বর|অক্টোবর|নভেম্বর|ডিসেম্বর))"); //(?!,)";
		$("#subtitle").val("^([1-5]|ব্যক্তিগত)");
		$("#day_question").val("^[ক|খ|গ|ঘ|ঙ]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(সাব্বাথ, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("প্রথম বিশ্রামবার নৈবেদ্য");
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
		$("#language").val("Bulgarian");
		$("#lang_code").val("bg");
		$("#lang_code3").val("bul");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Предговор)");
		$("#lesson_start").val("(УРОК ([0-9]+))");
		$("#lesson_sabbath").val("(СЪБОТА, \\d+ .+ \\b[1-9][0-9]{3}\\b г\\.)");
		$("#reading_lable").val("(Препоръчително четиво:)\\s*(.+)?"); //Препоръчвано четиво|Препоръчвани четива
		$("#day").val("нед\.,|пон\.,|вт\.,|ср\.,|чет\.,|пет\.,|Събота");
//		$("#day").val("Неделя|Понеделник|Вторник|Сряда|Четвъртък|Петък|Събота");
		$("#date").val("(\\d+\\s(ян|фев|март|април|май|юни|юли|август|септември|октомври|ноември|дек)\.?)$");
//		$("#date").val("(\\d+\\s(януари|фев|март|април|май|юни|юли|август|септември|октомври|ноември|декември))$");
		$("#subtitle").val("^([1-5]|ВЪПРОСИ ЗА ЛИЧЕН ПРЕГОВОР)");
		$("#day_question").val("^[а|б|в|г|д|е|ж]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(\\d+ .+ \\b[1-9][0-9]{3}\\b г\\.)");
		$("#fso_start").val("Дарби от първата събота");
		repPattern = {};
	}

	else if ( langCode == "pc"){
		$("#language").val("Cebuano");
		$("#lang_code").val("pc");
		$("#lang_code3").val("ceb");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Pasiuna)");
		$("#lesson_start").val("(LEKSYON ([0-9]+))");
		$("#lesson_sabbath").val("(Sabado, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Gisugyot nga [mga ]*Ba[la]*sahon\\:)\\s*(.+)?");
		$("#day").val("Domingo|Lunes|Martes|Mierkules|Huebes|Biernes|Sabado"); //Miyerkules|Huwebes|Biyernes|Hueves
		$("#date").val("((Enero|Febrero|Marso|Abril|Mayo|Hunyo|Hulyo|Agosto|Septyembre|Oktobre|Nobyembre|Desyembre) (\\d+))");
		$("#subtitle").val("^([1-5]|PERSONAL SUBLI NGA MGA PANGUTANA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabado, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Unang Sabado nga Halad");
		repPattern = {};
	}

	else if ( langCode == "ny"){
		$("#language").val("Chichewa");
		$("#lang_code").val("ny");
		$("#lang_code3").val("nya");
		$("#period_mark").val(".");
		$("#foreword_title").val("(MAWU OTSOGOLERA)");
		$("#lesson_start").val("(PHUNZIRO ([0-9]+))");
		$("#lesson_sabbath").val("(Sabata, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))\\.*");
		$("#reading_lable").val("(Zowelenga zoonjezera\\:)\\s*(.+)?");
		$("#day").val("Loyamba|Lachiwiri|Lachitatu|Lachinayi|Lachisanu|Lachisanu ndi chimodzi|Lachisanu ndi chiwiri");
		$("#date").val("((Januwale|Febuluwale|Marichi|Epulo|Meyi|Juni|Julaye|Ogasiti|Sepitembala|Okotobala|Novembala|Disembala) (\\d+))");
		$("#subtitle").val("^([1-6]|MAFUNSO)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabata, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))\\.*");
		$("#fso_start").val("Zopereka Sabata Loyamba");
		repPattern = {};
	}

	else if ( langCode == "zh"){
		$("#language").val("Chinese");
		$("#lang_code").val("zh");
		$("#lang_code3").val("zho");
		$("#period_mark").val("。");
		$("#foreword_title").val("^(前\\s*言)");
		$("#lesson_start").val("(第\\s*(.+)\\s*课)");
		$("#lesson_sabbath").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		$("#reading_lable").val("(建议阅读：)\\s*(.+)?");
		$("#day").val("星期日，|星期一，|星期二，|星期三，|星期四，|星期五，|安息日");
		$("#date").val("((1月|2月|3月|4月|5月|6月|7月|8月|9月|10月|11月|12月)\\s*[0-9]日)");
		$("#subtitle").val("^([1-5]\\.|个人复习题)");
		$("#day_question").val("^[a-g]");
		$("#rev_question").val("^[1-5]");
		$("#refer_text").val("(^“)|(”)");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		$("#fso_start").val("第一个?安息日奉献");
		repPattern = {};
	}

	else if ( langCode == "hr"){
		$("#language").val("Croatian");
		$("#lang_code").val("hr");
		$("#lang_code3").val("hrv");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Predgovor)");
		$("#lesson_start").val("(([0-9]+)\\. lekcija)");
		$("#lesson_sabbath").val("(Subota, (\\d+\\.\\s.+ \\b[1-9][0-9]{3}\\b\\.))");
		$("#reading_lable").val("(Predlažemo da pročitate:)\\s*(.+)?");
		$("#day").val("Nedjelja, |Ponedjeljak, |Utorak, |Srijeda, |Četvrtak, |Petak, |Subota");
		$("#date").val("(\\d+\. (Siječanja|Veljače|Ožujka|Travanj|Svibanj|Lipanj|srpnja|kolovoz|Rujna|Listopad|Studeni|Prosinca))");
		$("#subtitle").val("^([1-5]\.|PITANJA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Subota, (\\d+\\.\\s.+ \\b[1-9][0-9]{3}\\b\\.))");
		$("#fso_start").val("Dar prve subote");
		repPattern = {};
	}

	else if ( langCode == "cs"){
		$("#language").val("Czech");
		$("#lang_code").val("cs");
		$("#lang_code3").val("ces");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Předmluva)");
		$("#lesson_start").val("(LEKCE ([0-9]+))");
		$("#lesson_sabbath").val("(SOBOTA\\s(\\d+\\.\\s.+\\s[1-9][0-9]{3}))");
		$("#reading_lable").val("(Doporučená četba:)\\s*(.+)");
		$("#day").val("Ne|Po|Út|St|Čt|Pá|Sobota");
//		$("#day").val("Neděle|Pondělí|Úterý|Středa|Čtvrtek|Pátek|Sobota");
		$("#date").val("((\\d+)\\.\\s(1|2|3|4|5|6|7|8|9|10|11|12))$");
//	$("#date").val("((\\d+)\\.\\s(leden|únor|březen|duben|květen|červen|červenec|srpen|září|říjen|listopad|prosinec))$");
		$("#subtitle").val("^([1-5]|OTÁZKY K OPAKOVÁNÍ)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(.“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sobota\\s(\\d+\\.\\s.+\\s[1-9][0-9]{3}))");
		$("#fso_start").val("Sbírka první Soboty");
		repPattern = {};
	}

	else if ( langCode == "nl"){
		$("#language").val("Dutch");
		$("#lang_code").val("nl");
		$("#lang_code3").val("nld");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Voorwoord)");
		$("#lesson_start").val("(Les ([0-9]+))");
		$("#lesson_sabbath").val("(SABBAT, (\\d+\\s+(januari|februari|maart|april|mei|juni|juli|augustus|september|oktober|november|december)\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(Aanvullende studie\\s*:)\\s*(.+)?");
		$("#day").val("Zo,|Ma,|Di,|Wo,|Do,|Vr,|SABBAT");
//		$("#day").val("ZONDAG|MAANDAG|DINSDAG|WOENSDAG|DONDERDAG|VRIJDAG|SABBAT");
		$("#date").val("((\\d+)\\s+(januari|februari|maart|april|mei|juni|juli|augustus|september|oktober|november|december))"); 
		$("#subtitle").val("^([1-5]|TERUGBLIK)");
		$("#day_question").val("^[A-G]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(SABBAT, (\\d+\\s+(januari|februari|maart|april|mei|juni|juli|augustus|september|oktober|november|december)\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("Eerste Sabbatgaven");
		repPattern = {};
	}

	else if ( langCode == "ee"){
		$("#language").val("Ewe");
		$("#lang_code").val("ee");
		$("#lang_code3").val("ewe");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(ŊGƆDONYA)");
		$("#lesson_start").val("(Nusɔsrɔ̃ ([0-9]+))");
		$("#lesson_sabbath").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Nuxexlẽ si wona:)\\s*(.+)?");
//		$("#day").val("Kwasiɖagbe|Dzoɖagbe|Braɖagbe|Kuɖagbe|Yawoɖagbe|Fiɖagbe|Sabat");
		$("#day").val("Kwa\.,|Dzo\.,|Braɖ\.,|Kuɖ\.,|Yaw\.,|Fiɖ\.,|Sabat");
		$("#date").val("((Afɔfiɛ|Dame|Masa|Afɔfiɛ|Dame|Masa|July|August|September|October|November|Tedoxe) (\\d+))"); //(?!,)";
		$("#subtitle").val("^([1-5]|AME ÐOKUI ƑE NYABIASEWO)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Sabat Gbãtɔ Nunana");
		repPattern = {};
	}

	else if ( langCode == "fj"){
		$("#language").val("Fijian");
		$("#lang_code").val("fj");
		$("#lang_code3").val("fij");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Ai Vakamacala Taumada)");
		$("#lesson_start").val("(Lesoni ([0-9]+))");
		$("#lesson_sabbath").val("(Sigatabu, ([a-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Ai vola me qai wiliki:)\\s*(.+)?");
		$("#day").val("Siga Sade|Siga Moniti|Siga Tusiti|Siga Vukelulu|Siga Lotulevu|Siga Vakaraubuka|Siga Tabu");
		$("#date").val("((\\d+(er)*)\\s*(Janueri|Feperueri|Maji|Epereli|Me|Jiune|Jiulai|Okosita|Seviteba|Okotova|Noveba|Tiseba))");
		$("#subtitle").val("^([1-5].|NA VEI TARO ME RAICI-LESU KINA NA VULI)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("");
		$("#fso_start").val("");
		repPattern = {};
	}

	else if ( langCode == "fi"){
		$("#language").val("Finnish");
		$("#lang_code").val("fi");
		$("#lang_code3").val("fin");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Alkusanat)");
		$("#lesson_start").val("(Läksy ([0-9]+))");
		$("#lesson_sabbath").val("(Sapattina, ([A-Za-z]+.+ \\d+. \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Ehdotettua lukemista*:)\\s*(.+)?");
		$("#day").val("Sunnuntai|Maanantai|Tiistai|Keskiviikko|Torstai|Perjantai|Sapattina");
		$("#date").val("((Tammikuu|Helmikuu|Maaliskuu|Huhtikuu|Toukokuu|Kesäkuu|Heinäkuu|Elokuu|Syyskuun|Lokakuun|Marraskuun|Joulukuun) (\\d+))");
		$("#subtitle").val("^([1-5]|HENKILÖKOHTAISIA KERTAUSKYSYMYKSIÄ)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("");
		$("#fso_start").val("");
		repPattern = {};
	}

	else if ( langCode == "fr"){
		$("#language").val("French");
		$("#lang_code").val("fr");
		$("#lang_code3").val("fra");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Avant-propos)");
		$("#lesson_start").val("(Leçon ([0-9]+))");
		$("#lesson_sabbath").val("(Sabbat (\\d+(er|ᵉʳ)*\\s*(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(Lectures? proposées?\\s*:)\\s*(.+)?");
		$("#day").val("Dimanche|Lundi|Mardi|Mercredi|Jeudi|Vendredi|Sabbat");
		$("#date").val("((\\d+(er|ᵉʳ)*)\\s*(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre))");
		$("#subtitle").val("^([1-5]|RÉVISION ET)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabbat (\\d+(er|ᵉʳ)*\\s*(janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("Offrande spéciale");
		repPattern = {};
	}

	else if ( langCode == "de"){
		$("#language").val("Deutsch");
		$("#lang_code").val("de");
		$("#lang_code3").val("deu");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Vorwort)");
		$("#lesson_start").val("(([0-9]+)\\.\\s+Lektion)");
		$("#lesson_sabbath").val("(Sabbat,\\s+den\\s+(\\d+\\.\\s+(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(Zum Lesen empfohlen\\s*:)\\s*(.+)?");
		$("#day").val("\\(So[nntag]*\\)|\\(Mo[ntag]*\\)|\\(Di[enstag]*\\)|\\(Mi[ttwoch]*\\)|\\(Do[nnerstag]*\\)|\\(Fr[eitag]*\\)|Sabbat");
		$("#date").val('((\\d+)\\.\s*(\\d+)\\.)');
		//		$("#date").val("((\\d+\\.)\\s+(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember))\\s*$"); 
		$("#subtitle").val("^([1-5]|Fragen zur persönlichen)");
		$("#day_question").val("^[A-G]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabbat,\\s+den\\s+(\\d+\\.\\s+(Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember)\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("Erste Sabbatschulgaben");
		repPattern = {};
	}

	else if ( langCode == "hi"){
		$("#language").val("Hindi");
		$("#lang_code").val("hi");
		$("#lang_code3").val("hin");
		$("#period_mark").val("।");
		$("#foreword_title").val("^(प्रस्तावना)");
		$("#lesson_start").val("(पाठ\\s+([0-9]+))");
		$("#lesson_sabbath").val("(सब्बथ,\\s+(\\d+\\s+(जनवरी|फरवरी|मार्च|अप्रैल|मई|जून|जुलाई|अगस्त|सितंबर|सितम्बर|अक्टूबर|नवंबर|दिसंबर),\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(सुझाया गया पढ़ना:)\\s*(.+)?");
		$("#day").val("रविवार|सोमवार|मंगलवार|बुधवार|गुरुवार|शुक्रवार|सब्बथ");
		$("#date").val("((\\d+\\.)\\s+(जनवरी|फरवरी|मार्च|अप्रैल|मई|जून|जुलाई|अगस्त|सितम्बर|अक्टूबर|नवंबर|दिसंबर))"); 
		$("#subtitle").val("^([1-5]|व्यक्तिगत समीक्षा के प्रश्न)");
		$("#day_question").val("^[क-घ]\\)");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^\")|(“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(सब्बथ,\\s+(\\d+\\s+(जनवरी|फरवरी|मार्च|अप्रैल|मई|जून|जुलाई|अगस्त|सितंबर|सितम्बर|अक्टूबर|नवंबर|दिसंबर),\\s+(\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("पहला सब्बथ दान");
		repPattern = {};
	}

	else if ( langCode == "hl"){
		$("#language").val("Hiligaynon");
		$("#lang_code").val("hl");
		$("#lang_code3").val("hil");
		$("#periodical_name").val("LEKSION SA ESKWELA SABATIKA");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Pasiuna)");
		$("#lesson_start").val("(Leksion ([0-9]+))");
		$("#lesson_sabbath").val("(Sabado, ([A-Za-z]+ \\d+,\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Ginapanugda nga Balasahon:)\\s*(.+)?");
		$("#day").val("Dom,|Lun,|Mar,|Mier,|Hwe,|Bier,|Sabado");
//		$("#day").val("Domingo|Lunes|Martes|Miyerkules|Huwebes|Biyernes|Sabado");
		$("#date").val("((\\d+)\\s*(Ene|Feb|Mar|Apr|Mag|Giu|Lug|Ago|Set|Okt|Nob|Dis))"); //(?!,)";
//		$("#date").val("((\\d+)\\s*(Enero|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|Setyembre|Oktobre|Nobyembre|Disyembre))"); //(?!,)";
		$("#subtitle").val("^([1-5]|REPASO)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabado, ([A-Za-z]+ \\d+,\\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Unang Sabado nga Halad");
		repPattern = {};
	}

	else if ( langCode == "ht"){
		$("#language").val("Haitian");
		$("#lang_code").val("ht");
		$("#lang_code3").val("hat");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Entwodiksyon)");
		$("#lesson_start").val("(Leson ([0-9]+))");
		$("#lesson_sabbath").val("(Saba (\\d+ [A-Za-z]+ \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Lekti pwopoze*:)\\s*(.+)?");
		$("#day").val("Dimanch|Lendi|Madi|Mèkredi|Jedi|Vandredi|Saba");
		$("#date").val("((Janvye|Fevriye|Mas|Avril|Me|Jen|Jiyè|Out|Septanm|Oktòb|Novanm|Desanm) (\\d+))");
		$("#subtitle").val("^([1-5]|KESYON)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Saba (\\d+ [A-Za-z]+ \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Ofrann espesyal premye Saba pou");
		repPattern = {};
	}

	else if ( langCode == "hu"){
		$("#language").val("Hungarian");
		$("#lang_code").val("hu");
		$("#lang_code3").val("hun");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(ELŐSZÓ)");
		$("#lesson_start").val("([0-9]+\\.?\\s+Tanulmány)");
		$("#lesson_sabbath").val("(([1-9][0-9]{3}.+\\.) Szombat)$");
		$("#reading_lable").val("^(Javasolt olvasmány:)\\s*(.+)?");
		$("#day").val("Vasárnap|Hétfő|Kedd|Szerda|Csütörtök|Péntek|Szombat");
		$("#date").val("((Jan|Feb|Márc|Ápr|Máj|Jún|Júl|Aug|Szep|Okt|Nov|Dec)(\\.\\s*\\d+\.))");
//	$("#date").val("\\s*(január|február|március|április|május|június|július|augusztus|szeptember|október|november|december)$"); 
		$("#subtitle").val("^([1-5]\\.|SZEMÉLYES)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(([1-9][0-9]{3}.+\\.) Szombat)$");
		$("#fso_start").val("ELSŐ SZOMBATI ADAKOZÁS");
		repPattern = {};
	}

	else if ( langCode == "il"){
		$("#language").val("Ilocano");
		$("#lang_code").val("il");
		$("#lang_code3").val("ilo");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Pauna a Sarita)");
		$("#lesson_start").val("(Leksion ([0-9]+))");
		$("#lesson_sabbath").val("(Sabado, ([A-Za-z]+ \\d+. \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Maisingasing a Basbasaen*:)\\s*(.+)?");
		$("#day").val("Domingo|Lunes|Martes|Mierkules|Hueves|Viernes|Sabado");
		$("#date").val("((\\d+)\\s*(Enero|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|Disyembre))");
		$("#subtitle").val("^([1-5]|^PERSONAL)");
		$("#day_question").val("^[a-gk]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabado, ([A-Za-z]+ \\d+. \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Umuna a Pangsabado a Daton");
		repPattern = {};
	}

	else if ( langCode == "id"){
		$("#language").val("Indonesian");
		$("#lang_code").val("id");
		$("#lang_code3").val("ind");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Pendahuluan)");
		$("#lesson_start").val("(Pelajaran ([0-9]+))");
		$("#lesson_sabbath").val("(SABAT, (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Bacaan Dianjurkan\s*:|Bacaan yang dianjurkan*:)\s*(.+)?");
		$("#day").val("Min|Sen|Sel|Rab|Kam|Jum|Sabat");
//		$("#day").val("Minggu|Senin|Selasa|Rabu|Kamis|Jumat|Sabat");
		$("#date").val("((Jan|Feb|Mar|Apr|Mei|Jun|Jul|Agu|Sep|Okt|Nov|Des) (\\d+))");
//	$("#date").val("((Januari|Februari|Maret|April|Mei|Juni|Juli|Agustus|September|Oktober|November|Desember) (\\d+))");
		$("#subtitle").val("^([1-5]\\.|PERTANYAAN)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabat, (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Persembahan Sabat Pertama");
		repPattern = {};
	}
	else if ( langCode == "it"){
		$("#language").val("Italian");
		$("#lang_code").val("it");
		$("#lang_code3").val("ita");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Prefazione)");
		$("#lesson_start").val("(([0-9]+)a\\s*Lezione)");
		$("#lesson_sabbath").val("(Sabato, (\\d+\\s*(gennaio|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|dicembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(Letture consigliate\\s*:)\\s*(.+)?");
		$("#day").val("Domenica,|Lunedì,|Martedì,|Mercoledì,|Giovedì,|Venerdì,|Sabato");
		$("#date").val("((\\d+)\\s*(gennaio|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|dicembre))");
		$("#subtitle").val("^([1-5]|Domande)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabato, (\\d+\\s*(gennaio|febbraio|marzo|aprile|maggio|giugno|luglio|agosto|settembre|ottobre|novembre|dicembre)\\s*(\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("Offerta del primo Sabato");
		repPattern = {};
	}

	else if ( langCode == "ja"){
		$("#language").val("Japanese");
		$("#lang_code").val("ja");
		$("#lang_code3").val("jpn");
		$("#period_mark").val("。");	
		$("#foreword_title").val("^(まえがき)");
		$("#lesson_start").val("(第\\s*([0-9]+)\\s*課)");
		$("#lesson_sabbath").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		$("#reading_lable").val("(推奨文献\:)\\s*(.+)?");
		$("#day").val("日曜日|月曜日|火曜日|水曜日|木曜日|金曜日|安息日");
		$("#date").val("((1月|2月|3月|4月|5月|6月|7月|8月|9月|10月|11月|12月)\\s*[0-9]+日)");
		$("#subtitle").val("^([1-5]\\.|個人的な復習問題)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("(^[「｢])|([」｣])");
		$("#ref_source").val("( p[0-9]+)");
		$("#fso_date").val("(安息日\\s*(20[0-9][0-9])年\\s*([0-9]+)月\\s*([0-9]+)日)");
		$("#fso_start").val("第一安息日献金");
		repPattern = {};
	}

	else if ( langCode == "km"){
		$("#language").val("Khmer");
		$("#lang_code").val("km");
		$("#lang_code3").val("khm");
		$("#periodical_name").val("");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(អារម្ភកថា)");
		$("#lesson_start").val("(មេរៀន ?(.+))");
		$("#lesson_sabbath").val("(សប្បាហ៍, (.+ \\d+, \\b.{3}\\b))");
		$("#reading_lable").val("(អំណាន​ដែល​បាន​ណែនាំ:*:)\\s*(.+)?");
		$("#day").val("Sun[^ ]* |Mon[^ ]* |Tue[^ ]* |Wed[^ ]* |Thu[^ ]* |Fri[^ ]* |Sabbath");
		$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		$("#subtitle").val("^([1-5]|PERSONAL)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(សប្បាហ៍, (.+ \\d+, \\b.{3}\\b))");
		$("#fso_start").val("First Sabbath Offering");
		repPattern = {};
	}

	else if ( langCode == "ko"){
		$("#language").val("Korean");
		$("#lang_code").val("ko");
		$("#lang_code3").val("kor");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(서문)");
		$("#lesson_start").val("(제\\s*([0-9]+)\\s*과)");
		$("#lesson_sabbath").val("((20[0-9][0-9])년\\s+([0-9]+)월\\s+([0-9])+일\\s+안식일)");
		$("#reading_lable").val("(참조할 연구교재:)\\s*(.+)?");
		$("#day").val("일요일|월요일|화요일|수요일|목요일|금요일|안식일");
		$("#date").val("(((1월|2월|3월|4월|5월|6월|7월|8월|9월|10월|11월|12월)\\s+[0-9]+일))");
		$("#subtitle").val("^([1-5]\\.|복습과 생각할 문제)");
		$("#day_question").val("^[가-하]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("(^“)|(”)");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("((20[0-9][0-9])년\\s+([0-9]+)월\\s+([0-9])+일\\s+안식일)");
		$("#fso_start").val("첫째 안식일 연금");
		repPattern = {};
	}

	else if ( langCode == "ln"){
		$("#language").val("Lingala");
		$("#lang_code").val("ln");
		$("#lang_code3").val("lin");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Maloba ya mokuse)");
		$("#lesson_start").val("(Liteya ya .* \([0-9]+\))");
		$("#lesson_sabbath").val("(Sabata ya (\\d+/\\d+/\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Mokanda ya kotanga:)\\s*(.+)?");
		$("#day").val("Mokolo ya liboso|Mokolo ya mibale|Mokolo ya misato|Mokolo ya minei|Mokolo ya mitano|Mokolo ya motoba");
		$("#date").val("ya (\\d+/\\d+/\\b[1-9][0-9]{3}\\b)$");
		$("#subtitle").val("^([1-5]|KOZONGELA LITEYA YA MOTO YE MOKO)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabata ya (\\d+/\\d+/\\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Likabo likeseni");
		repPattern = {};
	}

	else if ( langCode == "lg"){
		$("#language").val("Luganda");
		$("#lang_code").val("lg");
		$("#lang_code3").val("lug");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(EBYANJULA)");
		$("#lesson_start").val("(Lesson ([0-9]+))");
		$("#lesson_sabbath").val("(Sabbiti, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Mokanda ya kotanga:)\\s*(.+)?");
		$("#day").val("Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Sabbiti");
		$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))");
		$("#subtitle").val("^([1-5]|Makonka a kuambulula)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("Sabbiti, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Ekirabo kya Sabbiti Esooka");
		repPattern = {};
	}

	else if ( langCode == "mg"){
		$("#language").val("Malagasy");
		$("#lang_code").val("mg");
		$("#lang_code3").val("mlg");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Teny fanolorana)");
		$("#lesson_start").val("(Lesona ([0-9]+))");
		$("#lesson_sabbath").val("(Sabata (\\d+\\s[A-Za-z]+\\s\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Atolotra ho vakiana:)\\s*(.+)?");
		$("#day").val("Alahady|Alatsinainy|Talata|Alarobia|Alakamisy|Zoma|Sabata");
//		$("#date").val("((Janoary|February|Martsa|Aprily|May|Jona|Jolay|Aogositra|Septambra|Oktobra|Novembra|Desambra) (\\d+))");
		$("#date").val("((\\d+/\\d+))");
		$("#subtitle").val("^([1-5]|FAMERENANA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(SABATA (\\d+\\s[A-Za-z]+\\s\\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Fanatitry ny Sabata voalohany");
		repPattern = {};
	}

	else if ( langCode == "mk"){
		$("#language").val("Macedonian");
		$("#lang_code").val("mk");
		$("#lang_code3").val("mkd");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Предговор\:?)");
		$("#lesson_start").val("(([0-9]+) ЛЕКЦИЈА)");
		$("#lesson_sabbath").val("(САБОТА, \\d+\\. (јануари|февруари|март|април|мај|јуни|јули|август|септември|октомври|ноември|декември) \\b[1-9][0-9]{3}\\b)\\.");
		$("#reading_lable").val("(Предлагаме да прочитате*:)\\s*(.+)?");
		$("#day").val("Нед|Пон|Вто|Сре|Чет|Пет|Сабота");
//		$("#day").val("Недела|Понеделник|Вторник|Среда|Четврток|Петок|Сабота");
		$("#date").val("(\\d+\\.\\s(јан|фев|мар|апр|мај|јун|јул|авг|сеп|окт|ное|дек))$");
//		$("#date").val("(\\d+\\.\\s(јануари|февруари|март|април|мај|јуни|јули|август|септември|октомври|ноември|декември))$");
		$("#subtitle").val("^([1-5]|ЛИЧЕН ПРЕГЛЕД НА ПРАШАЊАТА)");
		$("#day_question").val("^[а|б|в|г|д|ѓ|е]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(САБОТА, \\d+\\. (јан|фев|мар|апр|мај|јун|јул|авг|сеп|окт|ное|дек) \\b[1-9][0-9]{3}\\b)\\.");
		$("#fso_start").val("Дар во првата сабота");
		repPattern = {};
	}

	else if ( langCode == "ms"){
		$("#language").val("Malaysian");
		$("#lang_code").val("ms");
		$("#lang_code3").val("msa");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Kata Pengantar)");
		$("#lesson_start").val("(Pelajaran ([0-9]+))");
		$("#lesson_sabbath").val("(Sabat, ([A-Z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Cadangan Bacaan:)\\s*(.+)?");
		$("#day").val("Ahad|Isnin|Selasa|Rabu|Khamis|Jumaat|Sabat");
		$("#date").val("((January|February|March|April|May|June|July|August|September|Oktober|November|Disember) (\\d+))"); //(?!,)";
		$("#subtitle").val("^([1-5]|SOALAN)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabat, (\\d+ [A-Za-z]+ \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Persembahan Sabat Pertama");
		repPattern = {};
	}

	else if ( langCode == "mn"){
		$("#language").val("Mongolian");
		$("#lang_code").val("mn");
		$("#lang_code3").val("mon");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Өмнөх Үг)");
		$("#lesson_start").val("(Хичээл ([0-9]+))");
		$("#lesson_sabbath").val("(Амралтын Өдөр, (\\d+-р\\sсарын\\s\\d+,\\s\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("^([^:]+:)\\s*(.+)?");
		$("#day").val("Ням|Даваа|Мягмар|Лхагва|Пүрэв|Баасан|Aмралтын Өдөр");
		$("#date").val("((\\d+)\\s+(Hэгдүгээр сарын|Хоёрдугаар сарын|Гуравдугаар сар|Дөрөвдүгээр сар|Тавдугаар сар|Зургадугаар сар|долдугаар|Наймдугаар|Есдүгээр|Аравдугаар сар|Арваннэгдүгээр сар|Арванхоёрдугаар сар))");
		$("#subtitle").val("^([1-5]|ХУВИЙН ТОЙМ Асуултууд Зүүд)");
		$("#day_question").val("^[а|б|в|г|д]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("(^\“)|(\")");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Амралтын Өдөр, (\\d+-р\\sсарын\\s\\d+,\\s\\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Анхны Амралтын өдөр Tахилыг");
		repPattern = {};
	}

	else if ( langCode == "mq"){
		$("#language").val("Miskito");
		$("#lang_code").val("mq");
		$("#lang_code3").val("miq");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(TA BILA)");
		$("#lesson_start").val("(Lisan ([0-9]+))");
		$("#lesson_sabbath").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Kau aisikaikaia*:)\\s*(.+)?");
		$("#day").val("Sandi|Mundi|Tiusdi|Wensdi|Tausdi|Praidi|Sabat");
		$("#date").val("((Siakwa kati|Kuswa kati|Kakamuk kati|Lih wainhka kati|Lih mairin kati|Li kati|Pastara kati|Sikla kati|Wis kati|Waupasa kati|Yahbra kati|Krismis kati), (\\d+))");
		$("#subtitle").val("^([1-5]|MAKABANKA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(\"))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabat, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Pas sabat kaliksanka");
		repPattern = {};
	}

	else if ( langCode == "nd"){
		$("#language").val("Ndebele");
		$("#lang_code").val("nd");
		$("#lang_code3").val("nde");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Ilizwi Lokuvula)");
		$("#lesson_start").val("(Isifundo ([0-9]+))");
		$("#lesson_sabbath").val("(Sabatha, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Ukufunda Okuvavanyiweyo:)\\s*(.+)?");
		$("#day").val("Sonto|Mvulo|Lwesibili|Lwesithathu|Lwesine|Lwesihlanu|Sabatha");
		$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))");
		$("#subtitle").val("^([1-5]|NA VEITARO)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(\"))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("Sabatha, (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Iminikelo yamasabatha Akuqala");
		repPattern = {};
	}

	else if ( langCode == "pt"){
		$("#language").val("Portuguese");
		$("#lang_code").val("pt");
		$("#lang_code3").val("por");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Prefácio)");
		$("#lesson_start").val("(Lição ([0-9]+))");
		$("#lesson_sabbath").val("(Sábado, (\\d+(º)* de (Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro) de (\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(Estudo adicional:)\\s*(.+)?");
		$("#day").val("Domingo,|Segunda-feira,|Terça-feira,|Quarta-feira,|Quinta-feira,|Sexta-feira,|Sábado");
		$("#date").val("((\\d+(º)*) de (Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro))");
		$("#subtitle").val("^([1-5]|PARA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sábado, (\\d+(º)* de (Janeiro|Fevereiro|Março|Abril|Maio|Junho|Julho|Agosto|Setembro|Outubro|Novembro|Dezembro) de (\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("Oferta de Primeiro Sábado");
		repPattern = {};
	}

	else if ( langCode == "rn"){
		$("#language").val("Kirundi");
		$("#lang_code").val("rn");
		$("#lang_code3").val("run");
		$("#periodical_name").val("");
		$("#period_mark").val(".");
		$("#foreword_title").val("(INTANGA MARARA)");
		$("#lesson_start").val("(Lesson ([0-9]+))"); //ICIRWACA MBERE, ICIGWACA KABIRI, ICIRWACA3, ICIRWACAKANE, ICIGWACAGATANU
		$("#lesson_sabbath").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Suggested Readings*:)\\s*(.+)?");
		$("#day").val("Sunday|Monday|Tuesday|Wednesday|Thursday|Friday|Sabbath");
		$("#date").val("((January|February|March|April|May|June|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		$("#subtitle").val("^([1-5]|Review)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Amaturo  y'Isabato ya mbere");
		repPattern = {};
	}

	else if ( langCode == "ro"){
		$("#language").val("Romanian");
		$("#lang_code").val("ro");
		$("#lang_code3").val("ron");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Cuvânt înainte)"); // ţ or ț
		$("#lesson_start").val("(Lecția ([0-9]+))");
		$("#lesson_sabbath").val("(Sabat, (\\d+ [A-Za-z]+, [1-9][0-9]{3}\\b))");
		$("#reading_lable").val("^(Recomandare pentru studiu:)\\s*(.+)?");
		$("#day").val("Duminică|Luni|Marți|Miercuri|Joi|Vineri|Sabat");
		$("#date").val("((\\d+)\\s+(ianuarie|februarie|martie|aprilie|mai|iunie|iulie|august|septembrie|octombrie|noiembrie|decembrie))");
		$("#subtitle").val("^([1-5]|Întrebări)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabat, (\\d+ [A-Za-z]+, [1-9][0-9]{3}\\b))");
		$("#fso_start").val("Darul Sabatului Întâi");
		repPattern = {};
	}

	else if ( langCode == "ru"){
		$("#language").val("Russian");
		$("#lang_code").val("ru");
		$("#lang_code3").val("rus");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Предисловие)");
		$("#lesson_start").val("(УРОК ([0-9]+))");
		$("#lesson_sabbath").val("(Суббота,\\s+(\\d+\\s+.+\\s+[1-9][0-9]{3})\\s+.+)");
		$("#reading_lable").val("(Дополнительные материалы для изучения:)\\s*(.+)?");
		$("#day").val("Вс, |Пн, |Вт, |Ср, |Чт, |Пт, |Суббота");
//		$("#day").val("Воскресенье|Понедельник|Вторник|Среда|Четверг|Пятница|Суббота");
		$("#date").val("((\\d+)\\s(янв|фев|мар|апр|май|июн|июл|авг|сен|окт|ноя|дек)\\.)$");
//	$("#date").val("((\\d+)\\s(январь|февраль|март|апрель|май|июнь|июль|август|сентябрь|октябрь|ноябрь|декабрь))$");
		$("#subtitle").val("^([1-5]|ВОПРОСЫ ДЛЯ ПОВТОРЕНИЯ)");
		$("#day_question").val("^[а|б|в|г|д|е|ж|g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^«)|(»))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Суббота,\\s+(\\d+\\s+.+\\s+[1-9][0-9]{3})\\s+.+)");
		$("#fso_start").val("ПОЖЕРТВОВАНИЯ ПЕРВОЙ СУББОТЫ");
		repPattern = {};
	}

	else if ( langCode == "rw"){
		$("#language").val("Rwandese");
		$("#lang_code").val("rw");
		$("#lang_code3").val("kin");
		$("#period_mark").val(".");
		$("#foreword_title").val("(Ijambo ry’Ibanze)");
		$("#lesson_start").val("(Icyigisho ([0-9]+))");
		$("#lesson_sabbath").val("(Ku Isabato, (\\d+ [A-Za-z]+,* \\b[1-9][0-9]{3}\\b))\.*");
		$("#reading_lable").val("(Ibitabo Byifashishijwe\\s*:)\\s*(.+)?");
		$("#day").val("Kuwa Mbere|Kuwa Kabiri|Kuwa Gatatu|Kuwa Kane|Kuwa Gatanu|Kuwa Gatandatu|Ku Isabato|ISABATO YO KUWA");
		$("#date").val("(\\b(\\d+)\\s+(Mutarama|Gashyantare|Werurwe|Mata|Gicurasi|Kamena|Nyakanga|Kanama|Nzeri|Ukwakira|Ugushyingo|Ukuboza))");
		$("#subtitle").val("^([1-5]|6. IBIBAZO BYO KUZIRIKANWA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Ku Isabato, (\\d+ [A-Za-z]+,* \\b[1-9][0-9]{3}\\b))\.*");
		$("#fso_start").val("Amaturo y’Isabato ya Mbere");
		repPattern = {};
	}

	else if ( langCode == "si"){
		$("#language").val("Sinhala");
		$("#lang_code").val("si");
		$("#lang_code3").val("sin");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(පෙරවදන)");
		$("#lesson_start").val("(පාඩම් ([0-9]+))");
		$("#lesson_sabbath").val("(සබත්, .* \\d+, \\b[1-9][0-9]{3}\\b)");
		$("#reading_lable").val("(යෝජිත කියවීම*:)\\s*(.+)?");
		$("#day").val("ඉරිදා|සඳුදා|අඟහරුවාදා|බදාදා|බ්‍රහස්පතින්දා|සිකුරාදා|සබත්");
		$("#date").val("((January|February|මාර්තු|අප්‍රේල්|මැයි|ජුනි|July|August|September|October|November|December) (\\d+))"); //(?!,)";
		$("#subtitle").val("(^[1-5]|පුද්ගලික සමාලෝචන ප්‍රශ්න)");
		$("#day_question").val("^[A-G]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(සබත්, .* \\d+, \\b[1-9][0-9]{3}\\b)");
		$("#fso_start").val("පළමු සබත් පූජා");
		repPattern = {};
	}

	else if ( langCode == "sm"){
		$("#language").val("Samoan");
		$("#lang_code").val("sm");
		$("#lang_code3").val("smo");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Fa’atomuaga)");
		$("#lesson_start").val("(LESONA ([0-9]+))");
		$("#lesson_sabbath").val("(Sapati,* ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Faitauga Fautuaina\\s*:)\\s*(.+)?");
		$("#day").val("Ulua’*i Aso, |Aso Gafua, |Aso Lua, |Aso Lulu, |Aso Tofi, |Aso Faraile, |Sapati");
		$("#date").val("(\\b(Ianuari|Fepuari|Marti|Aperila|Me|Iuni|Iulai|Aokuso|Setema|Oketopa|Novema|Tesema)\\s+(\\d+))");
		$("#subtitle").val("^([1-5]|FESILI)");
		$("#day_question").val("^[a-giou]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sapati,* ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Taulaga Sapati Muamua");
		repPattern = {};
	}

	else if ( langCode == "sr"){
		$("#language").val("Serbian-Cyrillic");
		$("#lang_code").val("sr");
		$("#lang_code3").val("srp");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Предговор:?)");
		$("#lesson_start").val("(Лекција ([0-9]+))");
		$("#lesson_sabbath").val("((Субота,* \\d+\\. .+ \\b[1-9][0-9]{3}\\b)\.*)");
		$("#reading_lable").val("^(Предлажемо да прочитате:)\\s*(.+)?");
		$("#day").val("Недеља|Понедељак|Уторак|Среда|Четвртак|Петак|Субота");
		$("#date").val("((Јануара|Фебруара|Марта|Април|Маја|Јуна|Јула|Августа|Септембар|Октобар|Новембар|Децембар) (\\d+))");
		$("#subtitle").val("^([1-5]|ПИТАЊА ЗА ЛИЧНО РАЗМИШЉАЊЕ\\:?)");
		$("#day_question").val("^[а|б|ц|в|г|д|ђ]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(“))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("((Субота,* \\d+\\. .* \\b[1-9][0-9]{3}\\b)\.*)");
		$("#fso_start").val("Дар прве суботе\\:?");
		repPattern = {};
	}

	else if ( langCode == "srr"){
		$("#language").val("Serbian - Romanised");
		$("#lang_code").val("sr");
		$("#lang_code3").val("srp");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(PREDGOVOR)\:");
		$("#lesson_start").val("(Lekcija ([0-9]+))");
		$("#lesson_sabbath").val("(([A-Za-z]+, \\d+\. [A-Za-z]+ \\b[1-9][0-9]{3}\\b)\.)");
		$("#reading_lable").val("(Predlažemo da pročitate:)\\s*(.+)?");
		$("#day").val("Nedelja|Ponedeljak|Utorak|Sreda|Četvrtak|Petak|Subota");
		$("#date").val("((\\d+)\.)\\s*(januar|februar|mart|april|maj|jun|juli|avgust|septembar|oktobar|novembar|decembar)$");
		$("#subtitle").val("^([1-5]|PITANJA ZA LIČNO RAZMIŠLJANJE)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^„)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(([A-Za-z]+, \\d+\. [A-Za-z]+ \\b[1-9][0-9]{3}\\b)\.)");
		$("#fso_start").val("Dar prve subote");
		repPattern = {};
	}

	else if ( langCode == "sk"){
		$("#language").val("Slovak");
		$("#lang_code").val("sk");
		$("#lang_code3").val("slk");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(PREDSLOV)");
		$("#lesson_start").val("(([0-9]+)\\.?\\s+úloha)");
		$("#lesson_sabbath").val("(Sobota,\\s+(.+))$");
		$("#reading_lable").val("^([^:]+:)\\s*(.+)?");
		$("#day").val("Nedeľa|Pondelok|Utorok|Streda|Štvrtok|Piatok|Sobota");
		$("#date").val("\\s*(.+)$"); 
		$("#subtitle").val("^([1-5]|OTÁZKY)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("");
		$("#fso_start").val("");
		repPattern = {};
	}

	else if ( langCode == "st"){
		$("#language").val("Sotho");
		$("#lang_code").val("st");
		$("#lang_code3").val("sot");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(SELELEKELA)");
		$("#lesson_start").val("(Thuto ([0-9]+))");
		$("#lesson_sabbath").val("(Sabbatha (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(O kgothaletswa hore o bale:)\\s*(.+)?");
		$("#day").val("Sontaha|Mantaha|Labobedi|Laboraro|Labone|Labohlano|Sabata");
		$("#date").val("((Pherekgong|Hlakola|Hlakubele|Mmesa|Motsheanong|Phupjane|Phupu|Phato|Lwetse|Mphalane|Pudungwana|Tshitwe) (\\d+))");
		$("#subtitle").val("^([1-5]\\.|ITLHATLHOBE KA DIPOTSO TSENA)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabbatha (\\d+ [A-Za-z]+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Kabelo ya Sabata la Pele bakeng ya");
		repPattern = {};
	}

	else if ( langCode == "es"){
		$("#language").val("Spanish");
		$("#lang_code").val("es");
		$("#lang_code3").val("spa");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Prefacio)");
		$("#lesson_start").val("(Lección ([0-9]+))");
		$("#lesson_sabbath").val("(Sábado, (\\d+(º)* de (Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre) de (\\b[1-9][0-9]{3}\\b)))");
		$("#reading_lable").val("(Lectura.* sugerida.*:)\\s*(.+)?");
		$("#day").val('Dom, |Lun, |Mar, |Mié, |Jue, |Vie, |Sábado');
		$("#date").val('((\\d+) de (\\w+))');
//		$("#day").val("Domingo|Lunes|Martes|Miércoles|Jueves|Viernes|Sábado");
//		$("#date").val("((\\d+(º)*) de (Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre))\\s*$");
		$("#subtitle").val("^([1-5]|PREGUNTAS)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(SÁBADO, (\\d+(º)* de (Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre) de (\\b[1-9][0-9]{3}\\b)))");
		$("#fso_start").val("Ofrenda del Primer Sábado");
		repPattern = {};
	}

	else if ( langCode == "sw"){
		$("#language").val("Swahili");
		$("#lang_code").val("sw");
		$("#lang_code3").val("swa");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Utangulizi)"); //Weche Motelo
		$("#lesson_start").val("(SOMO LA ([0-9]+))");
		$("#lesson_sabbath").val("(Sabato( ya Tarehe)*, (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Inapendekezwa kusoma\\:)\\s*(.+)?"); //Somo moketi //Masomo yaliyopendekezwa
		$("#day").val("Jpil,|Jtat,|Jnne,|Jtan,|Alh,|Ijum,|Sabato");
//		$("#day").val("Jumapili Tarehe|Jumatatu Tarehe|Jumanne Tarehe|Jumatano Tarehe|Alhamisi Tarehe|Ijumaa Tarehe|Sabato ya Tarehe");
//		$("#day").val("Chak Tich|Tich Ariyo|Tich Adek|Tich Ang’wen|Tich Abich|Tich Auchiel|Sabato");
		$("#date").val("(Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba)\\s*\\d+$");
		$("#subtitle").val("^([1-5]|MASWALI)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabato( ya Tarehe)*, (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Sadaka za Sabato ya Kwanza");
		repPattern = {};
	}

	else if ( langCode == "swc"){
		$("#language").val("Swahili DRC");
		$("#lang_code").val("sw");
		$("#lang_code3").val("swc");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(UTANGULIZI)");
		$("#lesson_start").val("(Somo la ([0-9]+))");
		$("#lesson_sabbath").val("(Sabato (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Somo lililopendekezwa:)\\s*(.+)?");
		$("#day").val("Siku ya kwanza|Siku ya pili|Siku ya tatu|Siku ya ine|Siku ya tano|Siku ya maandalio|Sabato ya Tarehe");
		$("#date").val("(Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba)\\s*\\d+$");
		$("#subtitle").val("^([1-5]|MASWALI YA KUFIKIRIA)") ;
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabato (Januari|Februari|Machi|Aprili|Mei|Juni|Julai|Agosti|Septemba|Oktoba|Novemba|Desemba) \\d+, (\\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Matoleo Ya Kipekee");
		repPattern = {};
	}

	else if ( langCode == "th"){
		$("#language").val("Thai");
		$("#lang_code").val("th");
		$("#lang_code3").val("tha");
		$("#periodical_name").val("");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(คำนำ)");
		$("#lesson_start").val("\\s(บทที่ ([0-9]+))");
		$("#lesson_sabbath").val("(วันสะบาโต, (.* \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(แนะนำให้อ่านเพิ่มเติม:)\\s*(.+)?");
		$("#day").val("วันอาทิตย์|วันจันทร์|วันอังคาร|วันพุธ|วันพฤหัสบดี|วันศุกร์|วันสะบาโต");
		$("#date").val("((มกราคม|กุมภาพันธุ์|มีนาคม|เมษายน|อาจ|มิถุนายน|กรกฎาคม|สิงหาคม|กันยายน|ตุลาคม|พฤศจิกายน|ธันวาคม) (\\d+))"); //(?!,)";
		$("#subtitle").val("^([1-5]|คำถามทบทวนส่วนตัว)");
		$("#day_question").val("^[ก|ข|ค|ง|จ]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(วันสะบาโต, (.* \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("การถวายวันสะบาโตครั้งแรก");
		repPattern = {};
	}

	else if ( langCode == "tl"){
		$("#language").val("Tagalog");
		$("#lang_code").val("tl");
		$("#lang_code3").val("tgl");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Paunang Salita)");
		$("#lesson_start").val("(LEKSIYON ([0-9]+))");
		$("#lesson_sabbath").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(Iminumungkahing Babasahin\\:|Mga Iminumungkahing Babasahin\\:)\\s*(.*)?");
		$("#day").val("Lin|Lun|Mar|Miy|Huw|Biy|Sabado");
//		$("#day").val("Linggo|Lunes|Martes|Miyerkules|Huwebes|Biyernes|Sabado");
		$("#date").val("((Enero|Pebrero|Marso|Abril|Mayo|Hunyo|Hulyo|Agosto|Setyembre|Oktubre|Nobyembre|Disyembre) (\\d+))");
		$("#subtitle").val("^([1-5]|PERSONAL)");
		$("#day_question").val("^[a|b|c|d|e|f|g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabbath, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Handog sa Unang Sabbath");
		repPattern = {};
	}

	else if ( langCode == "ta"){
		$("#language").val("Tamil");
		$("#lang_code").val("ta");
		$("#lang_code3").val("tam");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(முன்னுரை)");
		$("#lesson_start").val("(பாடம் ([0-9]+))");
		$("#lesson_sabbath").val("(ஓய்வுநாள், (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(வாசிக்க பரிந்துரைக்கப்பட்ட பகுதி\\:)\\s*(.+)?");
		$("#day").val("ஞாயிறு,|திங்கள்,|செவ்வாய்,|புதன்,|வியாழன்,|வெள்ளி,|ஓய்வுநாள்");
		$("#date").val("((ஜனவரி|பெப்ரவரி|மார்ச்|ஏப்ரல்|மே|ஜூன்|ஜூலை|ஆகஸ்ட்|செப்டம்பர்|அக்டோபர்|நவம்பர்|டிசம்பர்) (\\d+))");
		$("#subtitle").val("^([1-5]|தனிப்பட்ட)");
		$("#day_question").val("^[அ|ஆ|இ|ஈ|உ|ஊ|௧|௨|௩|௪|௫|ங|ச|க|உ|ங|ச|ரு]\\.");
		$("#rev_question").val("^[1-5|௧|௨|௩|௪|௫|க|உ|ங|ச|ரு]\\.");
		$("#refer_text").val("((^“)|(\"))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(ஓய்வுநாள், (.+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("முதல் ஓய்வுநாள் காணிக்க");
		repPattern = {
			"க. ":"௧. ",
		};
	}

	else if ( langCode == "lu"){
		$("#language").val("Tshiluba");
		$("#lang_code").val("lu");
		$("#lang_code3").val("lub");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Meyi a mbangilu)");
		$("#lesson_start").val("(Dilesona\\s+([0-9]+))");
		$("#lesson_sabbath").val("(Nsabatu wa (\\d+\\/\\d+\\/[1-9][0-9]{3}))$");
		$("#reading_lable").val("(Bia\\s*kubala\\s*:)\\s*(.+)?");
		$("#day").val("Dia kumudilu|Dibidi|Disatu|Dinayi|Ditanu|Disambombo|Nsabatu");
		$("#date").val("(\\d+\\/\\d+)$"); 
		$("#subtitle").val("^([1-5]|Makonka a kuambulula)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^«|^“)|(»|”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Nsabatu wa (\\d+\\/\\d+\\/[1-9][0-9]{3}))$");
		$("#fso_start").val("Mulambu wa pa buawu");
		repPattern = {};
	}

	else if ( langCode == "uk"){
		$("#language").val("Ukrainian");
		$("#lang_code").val("uk");
		$("#lang_code3").val("ukr");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Передмова)");
		$("#lesson_start").val("(УРОК ([0-9]+))");
		$("#lesson_sabbath").val("(СУБОТА, (\\d+ .+ \\b[1-9][0-9]{3}\\b РОКУ))");
		$("#reading_lable").val("(Додаткові матеріали для вивчення*:)\\s*(.+)?");
		$("#day").val("Нд, |Пн, |Вт, |Ср, |Чт, |Пт, |Субота");
		$("#date").val("((\\d+) (Січ|Лют|бер|квіт|трав|черв|Липень|Серпень|Вересня|Жовтень|У листопаді|Груд))");
// 		$("#day").val("Неділя|Понеділок|Вівторок|Середа|Четвер|П['’]ятниця|Субота");
// 		$("#date").val("((Січень|Лютого|Березень|Квітень|Травень|Червень|Липень|Серпень|Вересня|Жовтень|У листопаді|Грудень) (\\d+))");
		$("#subtitle").val("^([1-5]|ЗАПИТАННЯ)");
		$("#day_question").val("^[а|б|в|г|д|е|ж]\\.") ;
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^«)|(»))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Субота, (\\d+ .+ \\b[1-9][0-9]{3}\\b року))");
		$("#fso_start").val("Пожертвування першої суботи для");
		repPattern = {};
	}

	else if ( langCode == "ur"){
		$("#language").val("Urdu");
		$("#lang_code").val("ur");
		$("#lang_code3").val("urd");
		$("#period_mark").val("۔");
		$("#foreword_title").val("^(پیش لفظ)");
		$("#lesson_start").val("(سبق نمبر ([0-9]+))");
		$("#lesson_sabbath").val("(سبت، .* [1-9][0-9]{3} ,\\d+)");
//		$("#lesson_sabbath_tag").val("<sabbath>$1‎$2</sabbath>");
		$("#reading_lable").val("()-\\s*(.+)?");
		$("#day").val("اتوار|سوموار|منگل|بدھ|جمعرات |جمعہ| سبت");
		$("#date").val("((جنوری|فروری|مارچ|اپریل|مئی|جون|جولائی|اگست|ستمبر|اکتوبر|نومبر|دسمبر) (\\d+))");
		$("#subtitle").val("^([1-5]|ذاتی نظر ثانی کیلئے سوالات)");
//		$("#subtitle_tag").val("<subtitle>‎$1</subtitle>");
		$("#day_question").val("^(الف *۔|ب *۔|ج *۔)");
		$("#rev_question").val("^-[1-5]");
//		$("#rev_question_tag").val("<question>$1</question>");
		$("#refer_text").val("(()|())");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(سبت، .* [1-9][0-9]{3} ,\\d+)");
		$("#fso_start").val("پہلے سبت کا چندہ");
		repPattern = {};
	}

	else if ( langCode == "vi"){
		$("#language").val("Vietnamese");
		$("#lang_code").val("vi");
		$("#lang_code3").val("vie");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(LỜI TỰA)");
		$("#lesson_start").val("(Bài học ([0-9]+))");
		$("#lesson_sabbath").val("(Sabát (\\d+\\-\\d+\\-\\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(BÀI ĐỌC GỢI Ý:)\\s*(.+)?");
		$("#day").val("Chủ Nhật|Thứ Hai|Thứ Ba|Thứ Tư|Thứ Năm|Thứ Sáu|Sabát");
//		$("#date").val("((\\d+)\\stháng \\d+ năm \\b[1-9][0-9]{3}\\b)");
		$("#date").val("((Tháng Một|Tháng Hai|Tháng Ba|Tháng Tư|Tháng Năm|Tháng Sáu|Tháng Bảy|Tháng Tám|Tháng Chín|Tháng Mười|Tháng Mười Một|Tháng Mười Hai) (\\d+))");
		$("#subtitle").val("^([1-5]|NHỮNG CÂU HỎI SUY NGẪM)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(Sabát (\\d+\\/\\d+\\/))");
		$("#fso_start").val("CỦA LỄ DÂNG SABAT ĐẦU TIÊN");
		repPattern = {};
	}

	else if ( langCode == "zu"){
		$("#language").val("Zulu");
		$("#lang_code").val("zu");
		$("#lang_code3").val("zul");
		$("#period_mark").val(".");
		$("#foreword_title").val("^(Isingeniso)");
		$("#lesson_start").val("(ISIFUNDO ([0-9]+))");
		$("#lesson_sabbath").val("(ISABATHA, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#reading_lable").val("(I[zi]*ncwadi E[zi]*nikeziwe:)\\s*(.+)?");
		$("#day").val("NgeSonto,|NgoMsombuluko,|Ngolwesibili,|Ngolwesithathu,|Ngolwesine,|Ngolwesihlanu,|ISabatha");
		$("#date").val("((Januwari|Februwari|Mashi|April|May|Juni|Julayi|Agasti|Septhemba|Okthoba|Novemba|Disemba) (\\d+))");
		$("#subtitle").val("^([1-5]|IMIBUZO YOMUNTU NGAMUNYE)");
		$("#day_question").val("^[a-g]\\.");
		$("#rev_question").val("^[1-5]\\.");
		$("#refer_text").val("((^“)|(”))");
		$("#ref_source").val("([-].[0-9]+\\.)");
		$("#fso_date").val("(ISabatha, ([A-Za-z]+ \\d+, \\b[1-9][0-9]{3}\\b))");
		$("#fso_start").val("Umnikelo weSabatha Lokuqala");
		repPattern = {};
	}
	else {
	// alert("The translation pattern for the chosen language is not available!" );
		$("#language").val("");
		$("#lang_code").val("");
		$("#lang_code3").val("");
		$("#period_mark").val("");
		$("#foreword_title").val("");
		$("#lesson_start").val("");
		$("#lesson_sabbath").val("");
		$("#reading_lable").val("");
		$("#day").val("");
		$("#date").val("");
		$("#subtitle").val("");
		$("#day_question").val("");
		$("#rev_question").val("");
		$("#refer_text").val("");
		$("#ref_source").val("");
	}

	// this value is overwritten with #lesson_sabbath,
	// unless set in the (specific) language setting above.
	if ($("#fso_date").val() == "")
		$("#fso_date").val($("#lesson_sabbath").val());
		
	// 
	let str = "";
	for (const [key, value] of Object.entries(repPattern)) {
        str += `${key}=>${value}\n`;
    }
    $("#replace_text").val(str);
    
	/*
	 * fill the days-months label at the bottom area 
	 */
	var match = /[^\(]+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|.+\|[^\)]+/.exec($("#date").val());
	if( match && match.length == 1 ) {
		var months = match[0].split("|");
		$(".label-months").each(function( index, element ) {
			element.textContent = months[index];
		});
	}
	else {
		$(".label-months").each(function( index, element ) {
			element.textContent = "";
		});
	}
	if( langCode == 'zh' || langCode == 'ja' || langCode == 'ko' ) {
		var match = [$("#day").val()];
		if( langCode == 'ko') 
			var sabbath = "|안식일";
		else
			var sabbath = "|安息日";
	}
	else {
		var match = /.+\|.+\|.+\|.+\|.+\|.+/.exec($("#day").val());
		var sabbath = "|Sabbath";
	}
	if( match && match.length == 1 ) {
		var days = (match[0]+sabbath).split("|");
		$(".label-days").each(function( index, element ) {
			element.textContent = days[index];
		});
	}
	else {
		$(".label-days").each(function( index, element ) {
			element.textContent = "";
		});
	}	
}