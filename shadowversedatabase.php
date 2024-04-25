<?php
$servername = "localhost";
$username ="Sabber234";
$password = "";
$dbname = "shadowversedata";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {   die("Connection failed: " . $conn->connect_error); }
?>

<?php
echo "<table>";
echo "<tr><td><b>Billede</b></td><td><b>Udviklet_billede</b></td><td><b>Navn</b></td><td><b>Skade</b></td><td><b>Liv</b></td><td><b>Udviklet_skade</b></td><td><b>Udviklet_liv</b></td><td><b>Beskrivelse</b></td></tr>";

$sql = "SELECT Billede, Udviklet_billede, Navn, Skade, Liv, Udviklet_skade, Udviklet_liv, Beskrivelse FROM kort";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
    echo "<tr><td>";
    echo "<img src='" . $row['Billede']."'></td><td>";
    echo "<img src='" . $row['Udviklet_billede']."'></td><td>";
    echo $row['Navn']."</td><td>";
    echo $row['Skade']."</td><td>";
    echo $row['Liv']."</td><td>";
    echo $row['Udviklet_skade']."</td><td>";
    echo $row['Udviklet_liv']."</td><td>";
    echo $row['Beskrivelse']."</td></tr>";

    // in deck, look at class, traits and archetypes
    // in full database, look at class, traits and archetypes
    // suggest matching cards
}

echo "</table>";
$result->close();

$sql_deckid = 2;
$sql_deck = "SELECT Kort_ID FROM kort_i_deck WHERE Deck_ID = $sql_deckid"; // Henter alle kort fra bestemt deck.
$result_deck = $conn->query($sql_deck);

$kort_ids = array();
while ($row_deck = $result_deck->fetch_assoc()) {
    $kort_ids[] = $row_deck['Kort_ID'];
}

$result_deck->close();

$sql_korttrait = "SELECT Kort_ID FROM kort_har_trait WHERE Trait_ID IN (SELECT Trait_ID FROM kort_har_trait WHERE Kort_ID IN (" . implode(",",$kort_ids) . "))"; // Finder alle kort med matchende traits.
$sql_kortarc = "SELECT Kort_ID FROM kort_har_archetype WHERE Arc_ID IN (SELECT Arc_ID FROM kort_har_archetype WHERE Kort_ID IN (" . implode(",",$kort_ids) . "))"; // Finder alle kort med matchende archetypes.
$sql_kortkey = "SELECT Kort_ID FROM keywords_i_kort WHERE Keyword_ID IN (SELECT Keyword_ID FROM keywords_i_kort WHERE Kort_ID IN (" . implode(",",$kort_ids) . "))"; // Finder alle kort med matchende keywords.


$sql_recommend = "SELECT Navn FROM kort WHERE Kort_ID IN ($sql_korttrait UNION $sql_kortarc UNION $sql_kortkey)";
$result_recommend = $conn->query($sql_recommend);

echo "<table>";
while($row_recommend = $result_recommend->fetch_assoc()) {
    echo "<tr><td>";
    echo $row_recommend['Navn'];
    echo "</tr></td>";
}
echo "</table>";

$result_recommend->close();
$conn->close();





?>
