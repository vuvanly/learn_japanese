<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>語彙</title>
	<link rel="stylesheet" type="text/css" href="kanji_style.css">
</head>
<body>


<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('kanji_2.sqlite');
    }
}

// mb_internal_encoding("UTF-8");

$db = new MyDB();

$level = $_REQUEST['level'];
$part = $_REQUEST['part'];
$number_per_path = 200;
echo "<div class='word-level no-display'>N" . $level ."</div>";
echo "<div class='word-part no-display'>P" . $part ."</div>";

// $db->exec('SELECT now()');

$result = $db->query('SELECT kanji, upper(han_viet) as han_viet, nghia_viet, type, on_romaji, cau_thanh FROM kanjis');

$res = array();
echo "<div class='kanji_sec no-display'>";
while ($row = $result->fetchArray()) {
    $res[$row["kanji"]] = $row; 
    echo "<p class='kanji_item ". $row["kanji"]."'>". $row["kanji"];
    echo "<span class='kanji'>". $row["kanji"] ."</span>";
    echo "<span class='han_viet'>". mb_strtoupper($row["han_viet"], 'UTF-8') ."</span>";
    echo "<span class='nghia_viet'>". $row["nghia_viet"] ."</span>";
    echo "<span class='type'>". $row["type"] ."</span>";
    echo "<span class='on_romaji'>". $row["on_romaji"] ."</span>";
    echo "<span class='cau_thanh'>". $row["cau_thanh"] ."</span>";
    echo "</p>";
}
echo "</div>";



$result_word_n5 = $db->query('SELECT kanji, upper(han_viet) as han_viet, nghia, hiragana FROM words WHERE word_type = "N'. $level .'" ORDER BY id ASC LIMIT ' . $number_per_path . ' OFFSET ' . ($part-1) * $number_per_path);
$res_word = array();
echo "<div class='word_sec no-display'>";
while ($row = $result_word_n5->fetchArray()) {
    $res_word[] = $row;
	print_r($row);    
    echo "<p class='word_kanji ". $row["kanji"]."'>". $row["kanji"];
    echo "<span class='kanji'>". $row["kanji"] ."</span>";
    echo "<span class='han_viet'>". mb_strtoupper($row["han_viet"], 'UTF-8') ."</span>";
    echo "<span class='nghia'>". $row["nghia"] ."</span>";
    echo "<span class='hiragana'>". $row["hiragana"] ."</span>";    
}
echo "</div>";
// $json_res = json_encode($res);
// print_r($json_res);
// var_dump($res['角']);
// echo "AAA";
// var_dump($res_word[400]);
?>

<script type="text/javascript">	
	var bodyBack;
	var isModeHoc;
	var wordKanjis = document.getElementsByClassName("word_sec")[0].getElementsByClassName("word_kanji");
	var wordLevel = document.getElementsByClassName("word-level")[0].innerText;
	var wordPart = document.getElementsByClassName("word-part")[0].innerText;
	var cname = "word_learn_" + wordLevel + "_" + wordPart;
	var wordKanjisArrays = {};
	var lengthWord = 0;
	for (var i = wordKanjis.length - 1; i >= 0; i--) {
		var item = wordKanjis[i];		
		var kanji = item.getElementsByClassName("kanji")[0].innerText;
		var han_viet = item.getElementsByClassName("han_viet")[0].innerText;
		var nghia = item.getElementsByClassName("nghia")[0].innerText;
		var hiragana = item.getElementsByClassName("hiragana")[0].innerText;
		wordKanjisArrays[lengthWord] = {
			"kanji" : kanji,
			"han_viet" : han_viet,
			"nghia" : nghia,
			"hiragana" : hiragana
		}	
		lengthWord += 1;
	}

	var kanjis = document.getElementsByClassName("kanji_sec")[0].getElementsByClassName("kanji_item");
	var kanjisArrays = {};

	for (var i = kanjis.length - 1; i >= 0; i--) {
		var item = kanjis[i];		
		var kanji = item.getElementsByClassName("kanji")[0].innerText;
		var han_viet = item.getElementsByClassName("han_viet")[0].innerText;
		var nghia_viet = item.getElementsByClassName("nghia_viet")[0].innerText;
		var type = item.getElementsByClassName("type")[0].innerText;
		var on_romaji = item.getElementsByClassName("on_romaji")[0].innerText;
		var cau_thanh = item.getElementsByClassName("cau_thanh")[0].innerText;
		kanjisArrays[kanji] = {
			"kanji" : kanji,
			"han_viet" : han_viet,
			"nghia" : nghia_viet,
			"type" : type,
			"on_romaji" : on_romaji,
			"cau_thanh" : cau_thanh
		}			
	}

	var runIndex = 0;
	var cnameRunIndex = "word_learn_runIndex_" + kanjiLevel + "_p" + kanjiPart;
	if (getCookie(cnameRunIndex) != "") {
		runIndex = parseInt(getCookie(cnameRunIndex));
	}	
	
	function clickNext(){
		if (!isModeHoc) {			
			var toLearnCookie = getCookie(cname);
			var toLearns = toLearnCookie.split(",");
			var existsIndex = toLearns.indexOf(runIndex.toString());			
			if (existsIndex > -1) {
				toLearns.splice(existsIndex, 1);				
				setCookie(cname, toLearns.join(","), 30);
			}
		}				

		if (runIndex == lengthWord - 1) {
			runIndex = 0;						
		} else {
			runIndex += 1;
		}
		while(checkRunIndexInCookie() < 0){			
			if (runIndex == lengthWord - 1) {
				runIndex = 0;						
			} else {
				runIndex += 1;
			}
			
		}		

		loadKanji();

	}
	function clickPrev(){		
		if (runIndex == 0) {
			runIndex = lengthWord - 1;
		} else {
			runIndex -= 1;
		}
		loadKanji();
	}		

	function loadKanji(){		
		var word = wordKanjisArrays[runIndex];		
		document.getElementsByClassName("kanji-txt")[0].innerHTML = word.kanji;
		document.getElementsByClassName("hiragana-txt")[0].innerHTML = word.hiragana;
		document.getElementsByClassName("back_han_viet")[0].innerHTML = word.han_viet;
		document.getElementsByClassName("back_nghia")[0].innerHTML = word.nghia;		

		bodyBack = document.getElementsByClassName('body-content2')[0];
		document.getElementsByClassName("title_n2")[0].innerHTML = "語彙 " + wordLevel + " ("  + runIndex +"/" + lengthWord + ")";

		if (isModeHoc) {
			if (bodyBack.classList.contains("no-display")) {
			 	bodyBack.classList.remove("no-display");
			 }
		} else {
			if (!bodyBack.classList.contains("no-display")) {
			 	bodyBack.classList.add("no-display");
			 }
		}	
		setCookie(cnameRunIndex, runIndex, 60);	
	}

	function toggleBack(){
		 bodyBack = document.getElementsByClassName('body-content2')[0];
		 if (bodyBack.classList.contains("no-display")) {
		 	bodyBack.classList.remove("no-display");
		 } else {
		 	bodyBack.classList.add("no-display");
		 }
	}		

	function getCookie(cname) {
	    var name = cname + "=";
	    var ca = document.cookie.split(';');
	    for(var i=0; i<ca.length; i++) {
	        var c = ca[i];
	        while (c.charAt(0)==' ') c = c.substring(1);
	        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
	    }
	    return "";
	}

	function getFullIndex(){
		var fullIndex = [];		
		for (var i = 0; i < lengthWord; i++) {
			fullIndex.push(i);			
		}		
		return fullIndex.join(",");
	}

	function setCookie(cname, cvalue, exdays) {
	    var d = new Date();
	    d.setTime(d.getTime() + (exdays*24*60*60*1000));
	    var expires = "expires="+d.toUTCString();
	    document.cookie = cname + "=" + cvalue + "; " + expires;
	}

	function bookmark(){		
		if (!isModeHoc) {
			var toLearnCookie = getCookie(cname);
			var toLearns = toLearnCookie.split(",");
			if (!toLearns.includes(runIndex.toString())) {
				toLearns.push(runIndex);
				setCookie(cname, toLearns.join(","), 30);
			}
		}		

		if (runIndex == lengthWord - 1) {
			runIndex = 0;						
		} else {
			runIndex += 1;
		}
		while(checkRunIndexInCookie() < 0){			
			if (runIndex == lengthWord - 1) {
				runIndex = 0;						
			} else {
				runIndex += 1;
			}
			
		}		

		loadKanji();
		
	}

	function checkRunIndexInCookie(){
		var toLearnCookie = getCookie(cname);
		var toLearns = toLearnCookie.split(",");		
		return toLearns.indexOf(runIndex.toString());		
	}

	function reset(){
		if(window.confirm('本当にいいんですね？')){
			setCookie(cname, getFullIndex(), 30);
		}
	}

	window.onload = function(){		
		isModeHoc = document.getElementById("mode_0").checked;
		loadKanji();
		setCookie(cname, getFullIndex(), 30);
	}

	function onChange(){
		isModeHoc = document.getElementById("mode_0").checked;
		toggleHocTest();
		loadKanji();	
		window.focus();
		if (document.activeElement) {
		    document.activeElement.blur();
		}
	}

	var resetButton;
	var bookmarkButton;
	function toggleHocTest(){
		if (resetButton == undefined) {
			resetButton = document.getElementsByClassName('reset')[0];	
		}

		if (bookmarkButton == undefined) {
			bookmarkButton = document.getElementsByClassName('bookmark')[0];	
		}
		 
		if (resetButton.classList.contains("no-display")) {
			resetButton.classList.remove("no-display");
		} else {
			resetButton.classList.add("no-display");
		}

		if (bookmarkButton.classList.contains("no-display")) {
			bookmarkButton.classList.remove("no-display");
		} else {
			bookmarkButton.classList.add("no-display");
		}


	}

	function loadPartKanji(kanji){
		document.getElementsByClassName("part_kanji")[0].innerHTML = kanji.kanji;
		document.getElementsByClassName("part_han_viet")[0].innerHTML = kanji.han_viet;
		document.getElementsByClassName("part_nghia")[0].innerHTML = kanji.nghia;
		var textCauThanh = "";
		if (kanji.cau_thanh != null) {
			cauThanhs = kanji.cau_thanh.split("");
			for (var i = cauThanhs.length - 1; i >= 0; i--) {
				var subKanji = cauThanhs[i];
				if (kanjisArrays[subKanji]) {
					textCauThanh += " " + subKanji + " (" + kanjisArrays[subKanji].han_viet +") ";
				}else{
					textCauThanh += " " + subKanji;
				}				
			}
		}
		document.getElementsByClassName("part_cau_thanh")[0].innerHTML = textCauThanh;
	}	

	var selectedText;
	document.onselectionchange = function() {		
	    selectedText = window.getSelection().toString();
	    if (selectedText != null && kanjisArrays[selectedText]) {	    		    	
	    	loadPartKanji(kanjisArrays[selectedText]);
	    }
	};
</script>
<div class="content">
	<div class="kanji_part">
		<span class="part_kanji">確</span><br/>
		<span class="part_han_viet">HAN</span><br/>
		<span class="part_nghia">Nghia</span><br/>
		<span class="part_cau_thanh">Cau Thanh</span><br/>				
	</div>
	<h1 class="title_n2">KANJI N2 (0)</h1>
	<div class="menu">
		<div class="menu_left">
			<ol>
				<li><span>N5</span>
					<ul>
						<li><a href="/words.php?level=5&part=1">N5_1</a></li>
						<li><a href="/words.php?level=5&part=2">N5_2</a></li>
						<li><a href="/words.php?level=5&part=3">N5_3</a></li>
					</ul>
				</li>
				<li><span>N4</span>
					<ul>
						<li><a href="/words.php?level=4&part=1">N4_1</a></li>
						<li><a href="/words.php?level=4&part=1">N4_2</a></li>
						<li><a href="/words.php?level=4&part=1">N4_3</a></li>
					</ul>
				</li>
				<li><span>N3</span>
					<ul>
						<li><a href="/words.php?level=3&part=1">N3_1</a></li>
						<li><a href="/words.php?level=3&part=2">N3_2</a></li>
						<li><a href="/words.php?level=3&part=3">N3_3</a></li>
						<li><a href="/words.php?level=3&part=4">N3_4</a></li>
						<li><a href="/words.php?level=3&part=5">N3_1</a></li>
						<li><a href="/words.php?level=3&part=6">N3_2</a></li>
						<li><a href="/words.php?level=3&part=7">N3_3</a></li>
						<li><a href="/words.php?level=3&part=8">N3_4</a></li>
						<li><a href="/words.php?level=3&part=9">N3_4</a></li>
					</ul>
				</li>								
			</ol>
		</div>
		<div class="menu_right">
			<ol start="4">
				<li><span>N2</span>
					<ul>
						<li><a href="/words.php?level=2&part=1">N2_1</a></li>
						<li><a href="/words.php?level=2&part=2">N2_2</a></li>
						<li><a href="/words.php?level=2&part=3">N2_3</a></li>
						<li><a href="/words.php?level=2&part=4">N2_4</a></li>
						<li><a href="/words.php?level=2&part=5">N2_1</a></li>
						<li><a href="/words.php?level=2&part=6">N2_2</a></li>
						<li><a href="/words.php?level=2&part=7">N2_3</a></li>
						<li><a href="/words.php?level=2&part=8">N2_4</a></li>
						<li><a href="/words.php?level=2&part=9">N2_4</a></li>
					</ul>
				</li>
				<li><span>N1</span>
					<ul>
						<li><a href="/words.php?level=1&part=1">N1_1</a></li>
						<li><a href="/words.php?level=1&part=2">N1_2</a></li>
						<li><a href="/words.php?level=1&part=3">N1_3</a></li>
						<li><a href="/words.php?level=1&part=4">N1_4</a></li>
						<li><a href="/words.php?level=1&part=5">N1_5</a></li>
						<li><a href="/words.php?level=1&part=6">N1_6</a></li>
						<li><a href="/words.php?level=1&part=7">N1_7</a></li>
						<li><a href="/words.php?level=1&part=8">N1_8</a></li>
						<li><a href="/words.php?level=1&part=9">N1_9</a></li>
						<li><a href="/words.php?level=1&part=10">N1_10</a></li>
						<li><a href="/words.php?level=1&part=11">N1_11</a></li>												
					</ul>
				</li>				
			</ol>
		</div>	
	</div>
	<input id="mode_0" type="radio" name="mode" value="0" onchange="onChange();" checked>
		<label for="mode_0">LEARN</label>
	</input>
	<input id="mode_1" type="radio" name="mode" value="1" onchange="onChange();">
		<label for="mode_1">TEST</label>
	</input>
	<!-- <h2 class="count_number"></h2> -->
	<div class="kanji">		
		<div class="body-content1">
			<div class="front">
				<div class="kanji-txt">
					確認長		
				</div>
				<div class="hiragana-txt">
					かくにんちょ
				</div>
			</div>			
		</div>
		<div class="button">
			<button class="reset no-display" onclick="reset();">RESET</button>
			<button onclick="clickPrev();">PREV</button>
			<button onclick="clickNext();">NEXT</button>
			<button class="bookmark no-display" onclick="bookmark();">BOOKMARK & NEXT</button>
		</div>		

		<div class="button show-button" onclick="toggleBack();">
			<button>SHOW BACK</button>
		</div>

		<div class="body-content2">
			<div class="back">
				<p class="back_han_viet">(XÁC NHẬN TRƯỜNG)</p>
				<p class="back_nghia">Xác nhận trường</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>
