<h1 style="margin:0 0 12px; font-size:22px;">Hoi <?= e($name) ?>!</h1>
<p style="margin:0 0 12px;">Leuk dat je meedoet met Sunnemeerten. Bevestig je e-mailadres, dan kun je foto's plaatsen, reageren en je aanmelden voor een werkplaats.</p>
<?php
$buttonUrl = $verifyUrl;
$buttonText = 'Bevestig mijn e-mailadres';
require __DIR__ . '/button.view.php';
?>
<p style="margin:0 0 12px; font-size:14px;">Werkt de knop niet? Kopieer dan deze link in je browser:<br>
    <a href="<?= e($verifyUrl) ?>" style="color:#FFE399; word-break:break-all;"><?= e($verifyUrl) ?></a></p>
<p style="margin:0; font-size:14px;">De link is 48 uur geldig. Heb je geen account aangemaakt? Dan kun je deze mail negeren.</p>
