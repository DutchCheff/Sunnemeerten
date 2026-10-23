<h1 style="margin:0 0 12px; font-size:22px;">Bedankt, <?= e($name) ?>!</h1>
<p style="margin:0 0 12px;">We hebben je aanmelding als vrijwilliger voor de parade ontvangen. We nemen zo snel mogelijk contact met je op.</p>
<p style="margin:0 0 4px;">Dit heb je ons gestuurd:</p>
<p style="margin:0 0 12px; padding:12px; background:#154a58; border-radius:8px;"><?= nl2br(e($motivation)) ?></p>
<?php
$buttonUrl = url('?page=workshops');
$buttonText = 'Bekijk de werkplaatsen';
require __DIR__ . '/button.view.php';
?>
<p style="margin:0; font-size:14px;">We gebruiken je gegevens alleen om contact met je op te nemen over de parade en verwijderen ze daarna.</p>
