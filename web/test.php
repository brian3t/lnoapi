<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>

<?php
require_once __DIR__. '/../vendor/autoload.php';

$pst = new DateTime('2021-04-11 9:40:00', new DateTimeZone('America/Los_Angeles'));
echo $pst->getOffset()/3600 . "\n";

$utc = $pst->setTimezone(new DateTimeZone('utc'));

$winter = new DateTime('2010-12-21', new DateTimeZone('America/New_York'));
$summer = new DateTime('2008-06-21', new DateTimeZone('America/New_York'));

echo $utc->getOffset()/3600 . "\n";
echo $utc->format('Y-m-d H:i:s') . "\n";

?>
</body>
</html>

