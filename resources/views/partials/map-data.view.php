<?php $map = config('map', []); ?>
data-route="<?= e(json_encode(getSetting('route', []))) ?>"
data-tiles="<?= e($map['tile_url'] ?? 'https://tile.openstreetmap.org/{z}/{x}/{y}.png') ?>"
data-attribution="<?= e($map['attribution'] ?? '') ?>"
