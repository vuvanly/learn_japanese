<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>漢字</title>
	<link rel="stylesheet" type="text/css" href="kanji_style.css">
</head>
<body id="char">


<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('kanji_2.sqlite');
    }
}

$level = $_REQUEST['level'];
$part = $_REQUEST['part'];
$number_per_path = 100;
echo "<div class='kanji-level no-display'>N" . $level ."</div>";
echo "<div class='kanji-part no-display'>N" . $part ."</div>";



// mb_internal_encoding("UTF-8");

$db = new MyDB();

$bothu_res = $db->query('SELECT kanji, upper(han_viet) as han_viet, nghia FROM kanjis WHERE type="bothu"');
$bothu = array();
echo "<div class='bothu_sec no-display'>";
while ($row = $bothu_res->fetchArray()) {		
	echo "<p class='bothu_item ". $row["kanji"]."'>". $row["kanji"];
	echo "<span class='kanji'>". $row["kanji"] ."</span>";
	echo "<span class='han_viet'>". mb_strtoupper($row["han_viet"], 'UTF-8') ."</span>";
	echo "<span class='nghia'>". $row["nghia"] ."</span>";
	echo "</p>";
}
echo "</div>";

$result = $db->query('SELECT kanji, upper(han_viet) as han_viet, nghia_viet, type, on_romaji, cau_thanh, radical FROM kanjis WHERE type="N'. $level .'" ORDER BY id ASC LIMIT ' . $number_per_path . ' OFFSET ' . ($part-1) * $number_per_path);

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
    echo "<span class='radical'>". $row["radical"] ."</span>";
    echo "</p>";
}
echo "</div>";
// var_dump($res);

$result_full = $db->query('SELECT kanji, upper(han_viet) as han_viet, nghia_viet, nghia, type, on_romaji, cau_thanh, radical FROM kanjis');

$res = array();
echo "<div class='kanji_full_sec no-display'>";
while ($row = $result_full->fetchArray()) {
    $res[$row["kanji"]] = $row; 
    echo "<p class='kanji_item ". $row["kanji"]."'>". $row["kanji"];
    echo "<span class='kanji'>". $row["kanji"] ."</span>";
    echo "<span class='han_viet'>". mb_strtoupper($row["han_viet"], 'UTF-8') ."</span>";
    if ($row["nghia_viet"]) {
    	echo "<span class='nghia_viet'>". $row["nghia_viet"] ."</span>";
    }else{
    	echo "<span class='nghia_viet'>". $row["nghia"] ."</span>";
    }
    
    echo "<span class='type'>". $row["type"] ."</span>";
    echo "<span class='on_romaji'>". $row["on_romaji"] ."</span>";
    echo "<span class='cau_thanh'>". $row["cau_thanh"] ."</span>";
    echo "<span class='radical'>". $row["radical"] ."</span>";
    echo "</p>";
}
echo "</div>";
?>



<script type="text/javascript">	
	var bodyBack;
	var isModeHoc;
	var bothus = document.getElementsByClassName("bothu_sec")[0].getElementsByClassName("bothu_item");
	var kanjiLevel = document.getElementsByClassName("kanji-level")[0].innerText;
	var kanjiPart = document.getElementsByClassName("kanji-part")[0].innerText;
	var bothusArrays = {};	
	for (var i = bothus.length - 1; i >= 0; i--) {
		var item = bothus[i];		
		var kanji = item.getElementsByClassName("kanji")[0].innerText;
		var han_viet = item.getElementsByClassName("han_viet")[0].innerText;
		var nghia = item.getElementsByClassName("nghia")[0].innerText;		
		bothusArrays[kanji] = {
			"kanji" : kanji,
			"han_viet" : han_viet,
			"nghia" : nghia			
		}			
	}
	
	var kanjis = document.getElementsByClassName("kanji_sec")[0].getElementsByClassName("kanji_item");
	var kanjisArrays = {};	
	var lengthWord = 0;
	for (var i = kanjis.length - 1; i >= 0; i--) {
		var item = kanjis[i];

		var kanji = item.getElementsByClassName("kanji")[0].innerText;
		var han_viet = item.getElementsByClassName("han_viet")[0].innerText;
		var nghia_viet = item.getElementsByClassName("nghia_viet")[0].innerText;
		var type = item.getElementsByClassName("type")[0].innerText;
		var on_romaji = item.getElementsByClassName("on_romaji")[0].innerText;
		var cau_thanh = item.getElementsByClassName("cau_thanh")[0].innerText;
		var radical = item.getElementsByClassName("radical")[0].innerText;
		kanjisArrays[lengthWord] = {
			"kanji" : kanji,
			"han_viet" : han_viet,
			"nghia" : nghia_viet,
			"type" : type,
			"on_romaji" : on_romaji,
			"cau_thanh" : cau_thanh,
			"radical" : radical,
		};
		
		lengthWord += 1;		
	}

	var kanjiArraysWord = {};
	var kanjisFull = document.getElementsByClassName("kanji_full_sec")[0].getElementsByClassName("kanji_item");
	for (var i = kanjisFull.length - 1; i >= 0; i--) {
		var item = kanjisFull[i];
		var kanji = item.getElementsByClassName("kanji")[0].innerText;
		var han_viet = item.getElementsByClassName("han_viet")[0].innerText;
		var nghia_viet = item.getElementsByClassName("nghia_viet")[0].innerText;
		var type = item.getElementsByClassName("type")[0].innerText;
		var on_romaji = item.getElementsByClassName("on_romaji")[0].innerText;
		var cau_thanh = item.getElementsByClassName("cau_thanh")[0].innerText;
		var radical = item.getElementsByClassName("radical")[0].innerText;
		kanjiArraysWord[kanji] = {
			"kanji" : kanji,
			"han_viet" : han_viet,
			"nghia" : nghia_viet,
			"type" : type,
			"on_romaji" : on_romaji,
			"cau_thanh" : cau_thanh,
			"radical" : radical,
		};
	};


	var runIndex = 0;
	var cnameRunIndex = "to_learn_runIndex_" + kanjiLevel + "_p" + kanjiPart;
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

	function checkRunIndexInCookie(){
		var toLearnCookie = getCookie(cname);
		var toLearns = toLearnCookie.split(",");		
		return toLearns.indexOf(runIndex.toString());		
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
		var word = kanjisArrays[runIndex];		
		document.getElementsByClassName("kanji-txt")[0].innerHTML = word.kanji;		
		document.getElementsByClassName("back_han_viet")[0].innerHTML = word.han_viet;
		document.getElementsByClassName("back_on_romaji")[0].innerHTML = word.on_romaji;
		document.getElementsByClassName("back_nghia")[0].innerHTML = word.nghia;
		if (bothusArrays[word.radical] !== undefined) {
			document.getElementsByClassName("back_bo")[0].innerHTML = "BỘ: " + bothusArrays[word.radical]['kanji'] + " - " + bothusArrays[word.radical]['han_viet'] + " (" + bothusArrays[word.radical]['nghia'] +") ";
		} else {
			document.getElementsByClassName("back_bo")[0].innerHTML = "";
		}
		
		var textCauThanh = "CẤU THÀNH: ";
		if (word.cau_thanh != null && word.cau_thanh != '') {			
			cauThanhs = word.cau_thanh.split("");
			for (var i = cauThanhs.length - 1; i >= 0; i--) {
				var subKanji = cauThanhs[i];				
				if (kanjiArraysWord[subKanji]) {
					textCauThanh += " " + subKanji + " (" + kanjiArraysWord[subKanji].han_viet +") ";
				}else{
					textCauThanh += " " + subKanji;
				}				
			}
		}else{
			textCauThanh = "";
		}
		document.getElementsByClassName("back_cau_thanh")[0].innerHTML = textCauThanh;
		document.getElementsByClassName("title_n2")[0].innerHTML = "漢字 " + kanjiLevel + " ("  + runIndex +"/" + lengthWord + ")";

		bodyBack = document.getElementsByClassName('body-content2')[0];

		if (isModeHoc) {
			if (bodyBack.classList.contains("no-display")) {
			 	bodyBack.classList.remove("no-display");
			 }
		} else {
			if (!bodyBack.classList.contains("no-display")) {
			 	bodyBack.classList.add("no-display");
			 }
		}

		loadXMLDoc();
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
	var cname = "to_learn_" + kanjiLevel + "_p" + kanjiPart;

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
				if (kanjiArraysWord[subKanji]) {
					textCauThanh += " " + subKanji + " (" + kanjiArraysWord[subKanji].han_viet +") ";
				}else{
					textCauThanh += " " + subKanji;
				}				
			}
		}
		document.getElementsByClassName("part_cau_thanh")[0].innerHTML = textCauThanh;
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

	function reset(){
		if(window.confirm('本当にいいんですね？')){
			setCookie(cname, getFullIndex(), 30);
		}
	}

	var selectedText;
	document.onselectionchange = function() {		
	    selectedText = window.getSelection().toString();
	    if (selectedText != null && kanjiArraysWord[selectedText]) {	    	
	    	loadPartKanji(kanjiArraysWord[selectedText]);
	    }
	};

	function loadXMLDoc() {
	    var xmlhttp;

	    if (window.XMLHttpRequest) {
	        // code for IE7+, Firefox, Chrome, Opera, Safari
	        xmlhttp = new XMLHttpRequest();
	    } else {
	        // code for IE6, IE5
	        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	    }

	    xmlhttp.onreadystatechange = function() {
	        if (xmlhttp.readyState == XMLHttpRequest.DONE ) {
	           if(xmlhttp.status == 200){
	           		document.getElementsByClassName("word_relateds")[0].innerHTML = xmlhttp.responseText;	           		               
	           }
	           else if(xmlhttp.status == 400) {
	              alert('There was an error 400')
	           }
	           else {
	               alert('something else other than 200 was returned')
	           }
	        }
	    }

	    xmlhttp.open("GET", "word_related.php?kanji=" + kanjisArrays[runIndex].kanji, true);
	    xmlhttp.send();
	}
	var charfield=document.getElementById("char")
		charfield.onkeydown=function(e){
		var e=window.event || e;				
		if (e.keyCode == '37') {
			clickPrev();
		};
		if (e.keyCode == '39') {
			clickNext();
		};
		if (e.keyCode == '38') {
			toggleBack();
		};
		if (e.keyCode == '40' && !isModeHoc) {
			bookmark();
		};		
	}

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
				<li><a href="/kanji.php?level=5&part=1"><span>N5</span></a></li>
				<li><span>N4</span>
					<ul>
						<li><a href="/kanji.php?level=4&part=1">N4_1</a></li>
						<li><a href="/kanji.php?level=4&part=1">N4_2</a></li>
					</ul>
				</li>
				<li><span>N3</span>
					<ul>
						<li><a href="/kanji.php?level=3&part=1">N3_1</a></li>
						<li><a href="/kanji.php?level=3&part=2">N3_2</a></li>
						<li><a href="/kanji.php?level=3&part=3">N3_3</a></li>
						<li><a href="/kanji.php?level=3&part=4">N3_4</a></li>
					</ul>
				</li>
				<li><span>N2</span>
					<ul>
						<li><a href="/kanji.php?level=2&part=1">N2_1</a></li>
						<li><a href="/kanji.php?level=2&part=2">N2_2</a></li>
						<li><a href="/kanji.php?level=2&part=3">N2_3</a></li>
						<li><a href="/kanji.php?level=2&part=4">N2_4</a></li>
					</ul>
				</li>				
			</ol>
		</div>
		<div class="menu_right">
			<ol start="5">
				<li><span>N1</span>
					<ul>
						<li><a href="/kanji.php?level=1&part=1">N1_1</a></li>
						<li><a href="/kanji.php?level=1&part=2">N1_2</a></li>
						<li><a href="/kanji.php?level=1&part=3">N1_3</a></li>
						<li><a href="/kanji.php?level=1&part=4">N1_4</a></li>
						<li><a href="/kanji.php?level=1&part=5">N1_5</a></li>
						<li><a href="/kanji.php?level=1&part=6">N1_6</a></li>
						<li><a href="/kanji.php?level=1&part=7">N1_7</a></li>
						<li><a href="/kanji.php?level=1&part=8">N1_8</a></li>
						<li><a href="/kanji.php?level=1&part=9">N1_9</a></li>
						<li><a href="/kanji.php?level=1&part=10">N1_10</a></li>
						<li><a href="/kanji.php?level=1&part=11">N1_11</a></li>
						<li><a href="/kanji.php?level=1&part=12">N1_12</a></li>
						<li><a href="/kanji.php?level=1&part=13">N1_13</a></li>						
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
	<div class="kanji">		
		<div class="body-content1">
			<div class="front">
				<div class="kanji-txt">
					確		
				</div>				
			</div>			
		</div>
		<div class="button">
			<button class="reset no-display" onclick="reset();">RESET</button>
			<button onclick="clickPrev();" class="prev-button">PREV</button>
			<button onclick="clickNext();">NEXT</button>
			<button class="bookmark no-display" onclick="bookmark();">BOOKMARK & NEXT</button>
		</div>		

		<div class="button show-button" onclick="toggleBack();">
			<button>SHOW BACK</button>
		</div>

		<div class="body-content2">
			<div class="back">
				<div class="back_han_viet">(XÁC NHẬN TRƯỜNG)</div>
				<div class="back_on_romaji">On Romaji</div>
				<div class="back_bo">BỘ</div>
				<div class="back_cau_thanh">CAU THANH</div>
				<div class="back_nghia">Xác nhận trường</div>
				<div class="word_relateds"></div>
			</div>
		</div>
	</div>
</div>

</body>
</html>
