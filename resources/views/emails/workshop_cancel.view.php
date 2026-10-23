<h1 style="margin:0 0 12px; font-size:22px;">Je bent afgemeld</h1>
<p style="margin:0 0 12px;">Hoi <?= e($name) ?>, je bent afgemeld voor <strong><?= e($title) ?></strong> op <?= e($when) ?>. Jammer dat je er niet bij kunt zijn!</p>
<p style="margin:0 0 12px;">Je plek is vrijgegeven voor iemand anders. Wil je toch komen of een andere keer helpen? Bekijk dan de werkplaatsen in de app.</p>
<?php
$buttonUrl = url('?page=workshops');
$buttonText = 'Bekijk de werkplaatsen';
require __DIR__ . '/button.view.php';
?>
