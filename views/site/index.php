<?php

/* @var $this yii\web\View */
/* @var $featuredBikes array */
/* @var $categories app\models\BikeType[] */
/* @var $stations app\models\Station[] */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VeloGo — Сервис проката велосипедов | Главная';
?>

<!-- HERO SECTION -->
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 rounded-3xl text-white p-6 sm:p-10 md:p-14 shadow-xl">
  <div class="absolute -right-8 -bottom-8 opacity-15 pointer-events-none select-none">
    <img src="<?= Url::to('@web/svg/bike.svg') ?>" class="w-64 h-64 sm:w-80 sm:h-80 filter invert" alt="">
  </div>

  <div class="relative z-10 max-w-3xl space-y-6">
    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-400/20 backdrop-blur-md border border-emerald-300/30 text-emerald-200 text-xs font-bold uppercase tracking-wider">
      <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Сезон 2026: более 1 400 велосипедов в парке
    </div>

    <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight leading-tight">
      Свобода передвижения по твоему маршруту
    </h1>

    <p class="text-slate-200 text-sm sm:text-base md:text-lg leading-relaxed max-w-2xl">
      Городские круизеры, горные MTB и быстрые электробайки. Бронируй за 1 минуту, катайся по набережным и паркам, возвращай на любой удобной станции без очередей и залогов.
    </p>

    <!-- Quick Rental Finder Box -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl shadow-2xl text-slate-800">
      <form action="<?= Url::to(['site/catalog']) ?>" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-center">
        <!-- Select Type from DB -->
        <div class="border-b sm:border-b-0 sm:border-r border-slate-200 pb-2 sm:pb-0 sm:pr-3">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Тип велосипеда</label>
          <select name="type" class="w-full text-xs sm:text-sm font-bold bg-transparent outline-none cursor-pointer text-slate-800 pt-1">
            <option value="">Любой тип (все модели)</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= Html::encode($cat->code) ?>"><?= Html::encode($cat->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Select Station from DB -->
        <div class="border-b sm:border-b-0 lg:border-r border-slate-200 pb-2 sm:pb-0 sm:pr-3">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Станция получения</label>
          <select name="station" class="w-full text-xs sm:text-sm font-bold bg-transparent outline-none cursor-pointer text-slate-800 pt-1">
            <option value="">Любая станция</option>
            <?php foreach ($stations as $st): ?>
              <option value="<?= $st->id ?>"><?= Html::encode($st->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Duration -->
        <div class="border-b sm:border-b-0 sm:border-r border-slate-200 pb-2 sm:pb-0 sm:pr-3">
          <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider">Срок аренды</label>
          <select name="duration" class="w-full text-xs sm:text-sm font-bold bg-transparent outline-none cursor-pointer text-slate-800 pt-1">
            <option value="2h">2 часа (Прогулка)</option>
            <option value="3h">3 часа (-10% скидка)</option>
            <option value="day">Весь день (до 23:00)</option>
            <option value="weekend">Уикенд (2 суток)</option>
          </select>
        </div>

        <!-- Submit Button -->
        <div>
          <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-xs sm:text-sm shadow-lg shadow-emerald-500/30 transition flex items-center justify-center gap-2">
            <span>Найти байк</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>
        </div>
      </form>
    </div>

    <!-- Trust Stats / Counters -->
    <div class="flex flex-wrap items-center gap-6 pt-2 text-xs text-slate-200">
      <div class="flex items-center gap-2">
        <span class="text-2xl font-black text-white"><?= count($stations) ?>+</span>
        <span class="text-slate-300">станций проката</span>
      </div>
      <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 hidden sm:block"></div>
      <div class="flex items-center gap-2">
        <span class="text-2xl font-black text-white">12 400+</span>
        <span class="text-slate-300">активных райдеров</span>
      </div>
      <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 hidden sm:block"></div>
      <div class="flex items-center gap-1.5">
        <span class="text-2xl font-black text-amber-300">4.9</span>
        <img src="<?= Url::to('@web/svg/star.svg') ?>" class="w-5 h-5 inline-block" alt="">
        <span class="text-slate-300">средний рейтинг</span>
      </div>
    </div>
  </div>
</section>

<!-- CATEGORIES SECTION (From DB `bike_types`) -->
<section class="space-y-4">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Категории велосипедов</h2>
      <p class="text-xs sm:text-sm text-slate-500">Подберите идеальный транспорт для города, трейлов или семейных поездок</p>
    </div>
    <?= Html::a('Все модели →', ['site/catalog'], ['class' => 'text-xs sm:text-sm font-bold text-emerald-600 hover:text-emerald-700 transition']) ?>
  </div>

  <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
    <?php foreach (array_slice($categories, 0, 4) as $cat): ?>
      <a href="<?= Url::to(['site/catalog', 'type' => $cat->code]) ?>" class="bg-white p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500 hover:shadow-xl transition group text-center space-y-2.5">
        <div class="w-16 h-16 mx-auto rounded-2xl bg-emerald-50 flex items-center justify-center group-hover:scale-110 transition duration-300">
          <img src="<?= Url::to('@web/svg/' . ($cat->icon_svg ?: 'bike.svg')) ?>" class="w-8 h-8" alt="<?= Html::encode($cat->name) ?>">
        </div>
        <div class="font-extrabold text-slate-900 text-sm sm:text-base"><?= Html::encode($cat->name) ?></div>
        <div class="text-[11px] sm:text-xs text-slate-500 leading-tight truncate"><?= Html::encode($cat->label) ?></div>
        <span class="inline-block text-[11px] font-bold text-emerald-600">Смотреть модели →</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- FEATURED HITS SECTION (From DB `bikes`) -->
<section class="space-y-4">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Популярные велосипеды сейчас</h2>
      <p class="text-xs sm:text-sm text-slate-500">Доступны для бронирования прямо сейчас на центральных станциях</p>
    </div>
    <?= Html::a('Все в каталоге', ['site/catalog'], ['class' => 'px-4 py-2 rounded-xl border border-slate-300 hover:bg-slate-100 text-xs sm:text-sm font-bold text-slate-700 transition']) ?>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($featuredBikes as $bike): ?>
      <?= $this->render('_bike_card', ['bike' => $bike]) ?>
    <?php endforeach; ?>
  </div>
</section>

<!-- PROMO BANNER -->
<section class="bg-slate-900 text-white rounded-3xl p-6 sm:p-10 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
  <div class="space-y-2 max-w-xl z-10 text-center md:text-left">
    <span class="text-xs font-bold uppercase tracking-wider text-emerald-400">Специальное предложение</span>
    <h3 class="text-2xl sm:text-3xl font-extrabold text-white">Катайся больше — плати меньше!</h3>
    <p class="text-xs sm:text-sm text-slate-300">
      При аренде от 3 часов дарим скидку 10%, а на весь день — до 40% экономии по сравнению с часовым тарифом.
    </p>
  </div>
  <div class="z-10 shrink-0">
    <?= Html::a('Рассчитать поездку', ['site/calculator'], ['class' => 'px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold text-sm shadow-xl shadow-emerald-500/20 transition']) ?>
  </div>
</section>
