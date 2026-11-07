<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $bike array */
?>
<article class="bike-card bg-white rounded-[24px] border border-slate-200/80 shadow-sm hover:shadow-xl transition overflow-hidden flex flex-col justify-between"
     data-id="<?= Html::encode($bike['id']) ?>"
     data-type="<?= Html::encode($bike['type']) ?>"
     data-price="<?= (int)$bike['priceHour'] ?>"
     data-size="<?= Html::encode($bike['size']) ?>"
     data-name="<?= Html::encode(mb_strtolower($bike['name'] . ' ' . $bike['typeLabel'])) ?>">
  <div>
    <div class="relative w-full h-52 sm:h-56 bg-slate-100 overflow-hidden">
      <?= Html::img('@web/' . $bike['image'], [
          'alt' => $bike['name'],
          'class' => 'w-full h-full object-cover object-center transform hover:scale-105 transition duration-300'
      ]) ?>
    </div>
    <div class="p-5 space-y-3">
      <div>
        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider"><?= Html::encode($bike['typeLabel']) ?></div>
        <h3 class="font-extrabold text-slate-900 text-lg sm:text-xl mt-0.5"><?= Html::encode($bike['name']) ?></h3>
        <p class="text-xs text-slate-500 leading-relaxed mt-1"><?= Html::encode($bike['desc']) ?></p>
      </div>
      <div class="flex items-center gap-2 text-xs text-slate-600 font-medium">
        <span class="flex items-center gap-1 text-amber-500 font-bold">
          <img src="<?= Url::to('@web/svg/star.svg') ?>" class="w-3.5 h-3.5 inline-block" alt=""> <?= Html::encode($bike['rating']) ?>
        </span>
        <span>(<?= Html::encode($bike['reviewsCount']) ?>)</span>
        <span>•</span>
        <span>Рама <?= Html::encode($bike['size']) ?></span>
      </div>
    </div>
  </div>
  <div class="px-5 pb-5 pt-3 border-t border-slate-100 flex items-center justify-between">
    <div>
      <div class="text-2xl font-black text-slate-900 leading-none">
        <?= number_format($bike['priceHour'], 0, '', ' ') ?> ₽ <span class="text-xs font-normal text-slate-400">/ час</span>
      </div>
      <div class="text-xs text-emerald-600 font-bold mt-1"><?= number_format($bike['priceDay'], 0, '', ' ') ?> ₽ / сутки</div>
    </div>
    <?= Html::a('Рассчитать', ['site/calculator', 'bike' => $bike['id']], [
        'class' => 'px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl text-xs sm:text-sm font-bold shadow-md shadow-emerald-500/20 transition'
    ]) ?>
  </div>
</article>
