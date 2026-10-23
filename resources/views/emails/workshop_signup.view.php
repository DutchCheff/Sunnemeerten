<h1 style="margin:0 0 12px; font-size:22px;">Je bent aangemeld, <?= e($name) ?>!</h1>
<p style="margin:0 0 12px;">Leuk dat je komt helpen bij het bouwen van de parade. Je staat ingeschreven voor <strong><?= e($title) ?></strong>.</p>
<p style="margin:0 0 12px; padding:12px; background:#154a58; border-radius:8px;">
    <strong>Wanneer:</strong> <?= e($when) ?><br>
    <strong>Waar:</strong> <?= e($location) ?>
</p>
<p style="margin:0 0 12px;"><?= nl2br(e($description)) ?></p>
<?php
$buttonUrl = url('?page=workshops');
$buttonText = 'Bekijk de werkplaatsen';
require __DIR__ . '/button.view.php';
?>
<p style="margin:0; font-size:14px;">Kun je toch niet? Meld je dan af bij de werkplaats in de app, dan komt je plek vrij voor iemand anders.</p>
