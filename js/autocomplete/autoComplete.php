<link rel="stylesheet" href="../js/autocomplete/jquery-ui.min.css">
<link rel="stylesheet" href="../js/autocomplete/jquery-ui.structure.min.css">
<link rel="stylesheet" href="../js/autocomplete/custome.css">
<script src="../js/jquery-3.6.4.min.js"></script>
<script src="../js/autocomplete/jquery-ui.min.js"></script>
<?php
// PHP array
$myArray = DB_Connection::getInstance()->query("SELECT `store_name` FROM `stores`;");

$toConvert = array();
// Convert PHP array to JSON string
while ($row = $myArray->fetch_assoc()) {
    array_push($toConvert, $row["store_name"]);
}
$jsonArray = json_encode($toConvert);

// Pass JSON string to JavaScript variable
echo "<script>var storesNames = JSON.parse('" . $jsonArray . "');
		$('#search').autocomplete({source: storesNames}, {
		autoFocus: true,
		delay: 0
		}); </script>";
?>
<script>
    function submitForm(event) {
        event.preventDefault();
        $("#myForm").submit();
    }

    $("#ui-id-1").click(submitForm);
    $("#search").keypress(function(event) {
        if (event.which == 13) {
            submitForm(event);
        }
    });
</script>