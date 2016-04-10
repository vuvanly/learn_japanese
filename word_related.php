<?php
class MyDB extends SQLite3
{
    function __construct()
    {
        $this->open('kanji_2.sqlite');
    }
}
$db = new MyDB();
$kanji = $_REQUEST['kanji'];
// echo $kanji;

$statement = $db->prepare('SELECT kanji, upper(han_viet) as han_viet, nghia, hiragana FROM words WHERE kanji LIKE :kanji');
$statement->bindValue(':kanji', "%" . $kanji . "%");

$result = $statement->execute();
// var_dump($result);
// var_dump($result->fetchArray());
// echo $query;
// $result = $db->query();
$max = 0;
$res_return = "";
while ($row = $result->fetchArray()) {
	// var_dump($row);
	if ($max == 3) {
		break;
	}
	$max += 1;
	$res_return .= "<div class='word_kanji'>" . $row['kanji'] . "</div>";
	$res_return .= "<div class='word_hiragana'>" . $row['hiragana'] . "</div>";
	$res_return .= "<div class='word_han_viet'>" . mb_strtoupper($row["han_viet"], 'UTF-8') . "</div>";
	$res_return .= "<div class='word_nghia'>" . $row['nghia'] . "</div>";	
	$res_return .="<br/>";
}
if ($max == 0) {
	$statement = $db->prepare('SELECT kanji, upper(han_viet) as han_viet, nghia, hiragana FROM other_words WHERE kanji LIKE :kanji');
	$statement->bindValue(':kanji', "%" . $kanji . "%");

	$result = $statement->execute();	
	while ($row = $result->fetchArray()) {
		if ($max == 3) {
			break;
		}
		$max += 1;
		$res_return .= "<div class='word_kanji'>" . $row['kanji'] . "</div>";
		$res_return .= "<div class='word_hiragana'>" . $row['hiragana'] . "</div>";
		$res_return .= "<div class='word_han_viet'>" . mb_strtoupper($row["han_viet"], 'UTF-8') . "</div>";
		$res_return .= "<div class='word_nghia'>" . $row['nghia'] . "</div>";		
		$res_return .="<br/>";
	}
}
echo $res_return;
// echo "AAA";
?>