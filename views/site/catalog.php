<?php

/* @var $this yii\web\View */
/* @var $bikes array */
/* @var $categories app\models\BikeType[] */
/* @var $stations app\models\Station[] */
/* @var $categoryCounts array */
/* @var $selectedType string|null */
/* @var $selectedStation string|null */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VeloGo — Каталог велосипедов';
?>

<!-- BREADCRUMBS -->
<nav class="flex items-center gap-2 text-xs text-slate-400">
  <?= Html::a('Главная', ['site/index'], ['class' => 'hover:text-emerald-600 transition']) ?>
  <span>/</span>
  <span class="text-slate-800 font-semibold">Каталог велосипедов</span>
</nav>

<!-- TITLE & QUICK SEARCH BAR -->
<div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
  <div>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Каталог велосипедов в прокат</h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1" id="bikes-found-text">
      Показано <?= count($bikes) ?> моделей в парке • Ближайшая доступность: от 5 минут
    </p>
  </div>

  <!-- Search Input & Sort Selector -->
  <div class="flex flex-wrap items-center gap-2.5">
    <div class="relative w-full sm:w-64">
      <input type="text" id="searchInput" oninput="applyFilters()" placeholder="Поиск по названию или типу..." class="w-full pl-9 pr-3 py-2.5 rounded-xl bg-white border border-slate-200 text-xs sm:text-sm font-medium focus:outline-none focus:border-emerald-500 shadow-sm">
      <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
    </div>

    <select id="sortSelect" onchange="applySorting()" class="py-2.5 px-3 rounded-xl bg-white border border-slate-200 text-xs sm:text-sm font-semibold text-slate-700 outline-none shadow-sm cursor-pointer">
      <option value="popular">Сначала популярные</option>
      <option value="price-asc">Сначала недорогие (по цене/час)</option>
      <option value="price-desc">Сначала премиум / электро</option>
    </select>

    <button onclick="toggleMobileFilters()" class="lg:hidden px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-bold text-slate-700 flex items-center gap-1.5">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
      <span>Фильтры</span>
    </button>
  </div>
</div>

<!-- MAIN TWO-COLUMN LAYOUT (Filters + Cards Grid) -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-start">
  
  <!-- SIDEBAR FILTERS (From DB `bike_types` and `stations`) -->
  <aside id="filterSidebar" class="hidden lg:block bg-white rounded-3xl border border-slate-200/80 p-5 space-y-6 shadow-sm">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <span class="font-extrabold text-slate-900 text-sm flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
        Параметры подбора
      </span>
      <button onclick="resetFilters()" class="text-xs font-semibold text-emerald-600 hover:underline">
        Сбросить
      </button>
    </div>

    <!-- Filter 1: Type (from DB `bike_types`) -->
    <div class="space-y-2.5">
      <label class="text-xs font-black text-slate-800 uppercase tracking-wider block">Тип велосипеда</label>
      <div class="space-y-2 text-xs">
        <?php foreach ($categories as $cat): ?>
          <label class="flex items-center justify-between p-2 rounded-xl bg-slate-50 hover:bg-slate-100 cursor-pointer transition">
            <span class="flex items-center gap-2 font-medium text-slate-700">
              <input type="checkbox" value="<?= Html::encode($cat->code) ?>" <?= ($selectedType === $cat->code) ? 'checked' : '' ?> onchange="applyFilters()" class="type-filter rounded text-emerald-500 focus:ring-emerald-400 w-4 h-4">
              <span class="flex items-center gap-1.5">
                <img src="<?= Url::to('@web/svg/' . ($cat->icon_svg ?: 'bike.svg')) ?>" class="w-3.5 h-3.5 inline-block" alt="">
                <span><?= Html::encode($cat->name) ?></span>
              </span>
            </span>
            <span class="text-slate-400 font-semibold text-[11px]"><?= $categoryCounts[$cat->code] ?? 0 ?></span>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Filter 2: Frame Size -->
    <div class="space-y-2.5">
      <label class="text-xs font-black text-slate-800 uppercase tracking-wider block">Рост райдера (Рама)</label>
      <div class="grid grid-cols-4 gap-1.5 text-center text-xs font-bold">
        <button onclick="setSizeFilter(this, 'S')" class="size-btn py-2 rounded-xl border border-slate-200 hover:border-emerald-500 text-slate-600 transition">S</button>
        <button onclick="setSizeFilter(this, 'M')" class="size-btn py-2 rounded-xl border border-slate-200 hover:border-emerald-500 text-slate-600 transition">M</button>
        <button onclick="setSizeFilter(this, 'L')" class="size-btn py-2 rounded-xl border border-slate-200 hover:border-emerald-500 text-slate-600 transition">L</button>
        <button onclick="setSizeFilter(this, 'ALL')" class="size-btn py-2 rounded-xl bg-emerald-500 text-white shadow-sm transition">Все</button>
      </div>
      <div class="text-[11px] text-slate-400 text-center">S (150–165 см) • M (165–180 см) • L (180–195 см)</div>
    </div>

    <!-- Filter 3: Price Slider -->
    <div class="space-y-2.5">
      <div class="flex items-center justify-between text-xs">
        <label class="font-black text-slate-800 uppercase tracking-wider">Макс. цена в час</label>
        <span id="priceVal" class="font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg">1 000 ₽/ч</span>
      </div>
      <input type="range" id="priceRange" min="200" max="1000" step="50" value="1000" oninput="onPriceSlider(this.value)" class="w-full accent-emerald-500 cursor-pointer">
      <div class="flex justify-between text-[11px] text-slate-400 font-medium">
        <span>от 200 ₽</span>
        <span>до 1 000 ₽</span>
      </div>
    </div>

    <!-- Station Filter (from DB `stations`) -->
    <div class="space-y-2">
      <label class="text-xs font-black text-slate-800 uppercase tracking-wider block">Станция проката</label>
      <select id="stationSelect" onchange="applyFilters()" class="w-full text-xs font-medium bg-slate-50 border border-slate-200 rounded-xl p-2.5 outline-none focus:bg-white focus:border-emerald-500">
        <option value="any">Любая станция рядом</option>
        <?php foreach ($stations as $st): ?>
          <option value="<?= $st->id ?>" <?= ($selectedStation == $st->id) ? 'selected' : '' ?>><?= Html::encode($st->name) ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <button onclick="applyFilters()" class="w-full py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm">
      Применить фильтр
    </button>
  </aside>

  <!-- BIKES CARDS GRID (3 cols on desktop) -->
  <section class="lg:col-span-3 space-y-4" aria-label="Список велосипедов">
    <!-- Active filters pills bar -->
    <div id="activePillsBar" class="hidden flex flex-wrap items-center gap-2 text-xs">
      <span class="text-slate-400 text-[11px]">Фильтры:</span>
      <div id="pillsContainer" class="flex flex-wrap gap-1.5"></div>
      <button onclick="resetFilters()" class="text-emerald-600 font-bold text-[11px] hover:underline ml-2">Сбросить все</button>
    </div>

    <!-- Grid of cards -->
    <div id="bikesGrid" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
      <?php foreach ($bikes as $bike): ?>
        <?= $this->render('_bike_card', ['bike' => $bike]) ?>
      <?php endforeach; ?>
    </div>

    <!-- Zero results placeholder -->
    <div id="emptyState" class="hidden text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
      <div class="w-16 h-16 mx-auto rounded-full bg-slate-100 flex items-center justify-center p-3 text-slate-400">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <h3 class="font-extrabold text-slate-800 text-lg">Велосипедов по заданным критериям не найдено</h3>
      <p class="text-xs text-slate-500 max-w-sm mx-auto">
        Попробуйте увеличить максимальную цену, выбрать другой размер рамы или сбросить фильтры.
      </p>
      <button onclick="resetFilters()" class="px-5 py-2.5 bg-emerald-500 text-white rounded-xl font-bold text-xs hover:bg-emerald-600 transition">
        Сбросить все фильтры
      </button>
    </div>
  </section>

</div>

<script>
  let selectedSize = 'ALL';

  function setSizeFilter(btn, size) {
    selectedSize = size;
    document.querySelectorAll('.size-btn').forEach(b => {
      b.className = 'size-btn py-2 rounded-xl border border-slate-200 hover:border-emerald-500 text-slate-600 transition';
    });
    btn.className = 'size-btn py-2 rounded-xl bg-emerald-500 text-white shadow-sm transition';
    applyFilters();
  }

  function onPriceSlider(val) {
    document.getElementById('priceVal').innerText = val + ' ₽/ч';
    applyFilters();
  }

  function applyFilters() {
    const search = document.getElementById('searchInput').value.toLowerCase().trim();
    const maxPrice = parseInt(document.getElementById('priceRange').value, 10);
    const checkedTypes = Array.from(document.querySelectorAll('.type-filter:checked')).map(cb => cb.value);

    const cards = document.querySelectorAll('.bike-card');
    let visibleCount = 0;

    cards.forEach(card => {
      const type = card.dataset.type;
      const price = parseInt(card.dataset.price, 10);
      const size = card.dataset.size;
      const name = card.dataset.name;

      const matchSearch = !search || name.includes(search);
      const matchType = checkedTypes.length === 0 || checkedTypes.includes(type);
      const matchSize = selectedSize === 'ALL' || size === selectedSize;
      const matchPrice = price <= maxPrice;

      if (matchSearch && matchType && matchSize && matchPrice) {
        card.style.display = '';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    // Update counter
    document.getElementById('bikes-found-text').innerText = 'Найдено ' + visibleCount + ' моделей в парке • Ближайшая доступность: от 5 минут';

    // Show/hide zero results
    const emptyState = document.getElementById('emptyState');
    if (visibleCount === 0) {
      emptyState.classList.remove('hidden');
    } else {
      emptyState.classList.add('hidden');
    }
  }

  function applySorting() {
    const sort = document.getElementById('sortSelect').value;
    const grid = document.getElementById('bikesGrid');
    const cards = Array.from(grid.querySelectorAll('.bike-card'));

    cards.sort((a, b) => {
      if (sort === 'price-asc') {
        return parseInt(a.dataset.price, 10) - parseInt(b.dataset.price, 10);
      } else if (sort === 'price-desc') {
        return parseInt(b.dataset.price, 10) - parseInt(a.dataset.price, 10);
      } else {
        return a.dataset.id.localeCompare(b.dataset.id);
      }
    });

    cards.forEach(c => grid.appendChild(c));
  }

  function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('priceRange').value = 1000;
    document.getElementById('priceVal').innerText = '1 000 ₽/ч';
    document.querySelectorAll('.type-filter').forEach(cb => cb.checked = false);

    const allBtn = document.querySelectorAll('.size-btn')[3];
    if (allBtn) setSizeFilter(allBtn, 'ALL');

    applyFilters();
  }

  function toggleMobileFilters() {
    const sidebar = document.getElementById('filterSidebar');
    sidebar.classList.toggle('hidden');
  }

  document.addEventListener('DOMContentLoaded', function () {
    applyFilters();
  });
</script>
