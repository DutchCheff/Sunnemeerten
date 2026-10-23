<h1 style="margin:0 0 12px; font-size:22px;">Werkplaats gaat niet door</h1>
<p style="margin:0 0 12px;">Hoi <?= e($name) ?>, helaas gaat de werkplaats waarvoor je bent aangemeld niet door:</p>
<p style="margin:0 0 12px; padding:12px; background:#154a58; border-radius:8px;">
    <strong><?= e($title) ?></strong><br>
    <?= e($when) ?><br>
    <?= e($location) ?>
</p>
<p style="margin:0 0 12px;">Je hoeft niets te doen. Wil je nog steeds helpen? Kijk dan of er een andere werkplaats is die je past.</p>
<?php
$buttonUrl = url('?page=workshops');
$buttonText = 'Bekijk de werkplaatsen';
require __DIR__ . '/button.view.php';
?>
