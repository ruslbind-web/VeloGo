<?php

/* @var $this yii\web\View */
/* @var $bikes array */
/* @var $stations app\models\Station[] */
/* @var $selectedBike string|null */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VeloGo — Тарифы и калькулятор стоимости аренды';
$isGuest = Yii::$app->user->isGuest;
$u = !$isGuest ? Yii::$app->user->identity : null;
?>

<!-- BREADCRUMBS -->
<nav class="flex items-center gap-2 text-xs text-slate-400">
  <?= Html::a('Главная', ['site/index'], ['class' => 'hover:text-emerald-600 transition']) ?>
  <span>/</span>
  <span class="text-slate-800 font-semibold">Тарифы и калькулятор стоимости</span>
</nav>

<!-- HEADER TITLE -->
<div class="space-y-2 max-w-2xl">
  <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
    Онлайн-калькулятор бронирования
  </div>
  <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
    Тарифные планы и точный расчёт аренды
  </h1>
  <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
    Укажите модель велосипеда, срок и желаемые опции — сумма пересчитается мгновенно с учетом действующих скидок.
  </p>
</div>

<!-- TARIFF TILES SUMMARY -->
<section aria-labelledby="tariffs-heading" class="space-y-4">
  <h2 id="tariffs-heading" class="sr-only">Тарифные планы аренды велосипедов</h2>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Tariff 1 -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-3 hover:border-emerald-400 transition">
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-700 uppercase">Почасовой</span>
        <div class="w-6 h-6"><img src="<?= Url::to('@web/svg/clock.svg') ?>" class="w-full h-full" alt=""></div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900">от 300 ₽ <span class="text-xs text-slate-400 font-normal">/ час</span></div>
        <div class="font-bold text-slate-800 text-sm mt-1">Прогулочный</div>
        <p class="text-xs text-slate-500 mt-1">Идеально для коротких поездок по набережной и паркам. Оплата поминутно после первого часа.</p>
      </div>
    </div>

    <!-- Tariff 2 -->
    <div class="bg-white p-5 rounded-3xl border-2 border-emerald-500 shadow-md space-y-3 relative overflow-hidden">
      <div class="absolute top-0 right-0 bg-emerald-500 text-white text-[10px] font-black px-3 py-0.5 rounded-bl-xl uppercase tracking-wider">
        Выгодно
      </div>
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase">Посуточный</span>
        <div class="w-6 h-6"><img src="<?= Url::to('@web/svg/sun.svg') ?>" class="w-full h-full" alt=""></div>
      </div>
      <div>
        <div class="text-2xl font-black text-emerald-600">от 1 100 ₽ <span class="text-xs text-slate-400 font-normal">/ сутки</span></div>
        <div class="font-bold text-slate-800 text-sm mt-1">Весь день до 23:00</div>
        <p class="text-xs text-slate-500 mt-1">Экономия до 45% по сравнению с часовой арендой. Катайтесь в своем темпе весь день.</p>
      </div>
    </div>

    <!-- Tariff 3 -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-3 hover:border-emerald-400 transition">
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-800 uppercase">Уикенд</span>
        <div class="w-6 h-6"><img src="<?= Url::to('@web/svg/sparkles.svg') ?>" class="w-full h-full" alt=""></div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900">от 2 400 ₽ <span class="text-xs text-slate-400 font-normal">/ 2 дня</span></div>
        <div class="font-bold text-slate-800 text-sm mt-1">Суббота + Воскресенье</div>
        <p class="text-xs text-slate-500 mt-1">Полный доступ на субботу и воскресенье. Байк можно забирать с собой домой на ночь.</p>
      </div>
    </div>

    <!-- Tariff 4 -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm space-y-3 hover:border-emerald-400 transition">
      <div class="flex items-center justify-between">
        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-100 text-blue-800 uppercase">Неделя</span>
        <div class="w-6 h-6"><img src="<?= Url::to('@web/svg/bike.svg') ?>" class="w-full h-full" alt=""></div>
      </div>
      <div>
        <div class="text-2xl font-black text-slate-900">от 5 900 ₽ <span class="text-xs text-slate-400 font-normal">/ 7 дней</span></div>
        <div class="font-bold text-slate-800 text-sm mt-1">Городской коммьют</div>
        <p class="text-xs text-slate-500 mt-1">Для ежедневных поездок на работу без пробок и духоты в метро. Бесплатное ТО включено.</p>
      </div>
    </div>
  </div>
</section>

<!-- MAIN INTERACTIVE SECTION: LEFT PREVIEW + RIGHT CALCULATOR -->
<section aria-labelledby="calc-interactive-heading">
  <h2 id="calc-interactive-heading" class="sr-only">Калькулятор стоимости и онлайн-бронирование</h2>
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <!-- LEFT COLUMN: Selected Bike Specs & Inclusions (5 cols) -->
    <aside class="lg:col-span-5 space-y-6" aria-label="Карточка выбранного велосипеда">
    
      <!-- Live Selected Bike Visual Card -->
      <article class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-5">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Выбранный велосипед</span>
          <span id="bikeBadge" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800 uppercase"><img src="<?= Url::to('@web/svg/bolt.svg') ?>" class="w-3 h-3 inline-block" alt=""> Электро</span>
        </div>

        <div class="relative w-full h-56 sm:h-64 rounded-2xl overflow-hidden bg-slate-100 shadow-inner">
          <img id="bikePhoto" src="<?= Url::to('@web/images/voltride.jpg') ?>" alt="VoltRide Urban Pro" class="w-full h-full object-cover object-center transform hover:scale-105 transition duration-500">
          <div id="bikeStationNote" class="absolute bottom-2.5 left-2.5 right-2.5 bg-white/95 backdrop-blur-md text-[11px] font-bold text-slate-800 px-3 py-1.5 rounded-xl shadow-sm text-center border border-slate-200/60 flex items-center justify-center gap-1.5">
            <img src="<?= Url::to('@web/svg/pin.svg') ?>" class="w-3.5 h-3.5 inline-block" alt=""> В наличии на ст. Парк Горького и Набережная
          </div>
        </div>

        <div class="space-y-1">
          <h2 id="bikeTitle" class="text-2xl font-extrabold text-slate-900">VoltRide Urban Pro</h2>
          <p id="bikeDescription" class="text-xs text-slate-500 leading-relaxed">
            Мощный электробайк 250W с плавным ассистентом до 25 км/ч. Справляется с любыми подъёмами и обеспечивает легкую комфортную поездку без пота.
          </p>
        </div>

        <!-- Specs Matrix -->
        <div class="grid grid-cols-2 gap-3 pt-3 border-t border-slate-100 text-xs">
          <div class="p-3 bg-slate-50 rounded-2xl">
            <span class="text-slate-400 block text-[10px] uppercase font-bold">Запас хода</span>
            <span id="specRange" class="font-bold text-slate-800 text-sm">до 70 км</span>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl">
            <span class="text-slate-400 block text-[10px] uppercase font-bold">Вес байка</span>
            <span id="specWeight" class="font-bold text-slate-800 text-sm">18.5 кг</span>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl">
            <span class="text-slate-400 block text-[10px] uppercase font-bold">Тормоза</span>
            <span id="specBrakes" class="font-bold text-slate-800 text-sm">Дисковые Shimano</span>
          </div>
          <div class="p-3 bg-slate-50 rounded-2xl">
            <span class="text-slate-400 block text-[10px] uppercase font-bold">Ростовка рамы</span>
            <span id="specFrame" class="font-bold text-slate-800 text-sm">M (165–180 см)</span>
          </div>
        </div>
      </article>

      <!-- Rental Inclusions -->
      <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <h3 class="font-bold text-slate-900 text-base">В каждую поездку включено</h3>
        <div class="space-y-3 text-xs">
          <div class="flex items-start gap-3">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 p-1.5">
              <img src="<?= Url::to('@web/svg/check.svg') ?>" class="w-4 h-4 text-emerald-700" alt="">
            </div>
            <div>
              <div class="font-bold text-slate-800">Без денежного залога</div>
              <div class="text-slate-500">Авторизация по номеру телефона и карте без заморозки 10 000 ₽.</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 p-1.5">
              <img src="<?= Url::to('@web/svg/lock.svg') ?>" class="w-4 h-4 text-emerald-700" alt="">
            </div>
            <div>
              <div class="font-bold text-slate-800">Электронный смарт-замок</div>
              <div class="text-slate-500">Паркуйтесь у кафе и магазинов во время поездки через PIN-код.</div>
            </div>
          </div>

          <div class="flex items-start gap-3">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center shrink-0 p-1.5">
              <img src="<?= Url::to('@web/svg/tools.svg') ?>" class="w-4 h-4 text-emerald-700" alt="">
            </div>
            <div>
              <div class="font-bold text-slate-800">Бесплатная замена байка</div>
              <div class="text-slate-500">При проколе шины или разряде привезем новый в течение 15 минут.</div>
            </div>
          </div>
        </div>
      </div>

    </aside>

    <!-- RIGHT COLUMN: Interactive Calculator & Booking Form (7 cols) -->
    <div class="lg:col-span-7 space-y-6">
      <div class="bg-white rounded-3xl p-6 sm:p-8 border-2 border-emerald-500 shadow-xl space-y-6">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
          <div>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2">
              <img src="<?= Url::to('@web/svg/bolt.svg') ?>" class="w-6 h-6 inline-block" alt="">
              <span>Калькулятор стоимости</span>
            </h2>
            <p class="text-xs text-slate-500">Настройте параметры аренды под свой маршрут</p>
          </div>
          <span class="self-start sm:self-auto text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full">
            Живой пересчет в реальном времени
          </span>
        </div>

        <form id="rentalForm" onsubmit="handleBookingSubmit(event)" class="space-y-6">
          <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>

          <!-- 1. CHOOSE BIKE MODEL -->
          <div class="space-y-2">
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider">
              1. Выберите модель велосипеда
            </label>
            <select id="bikeSelect" onchange="onBikeChange()" class="w-full p-3.5 rounded-2xl bg-slate-50 border border-slate-200 font-bold text-slate-900 text-xs sm:text-sm outline-none focus:bg-white focus:border-emerald-500 shadow-sm cursor-pointer">
              <?php foreach ($bikes as $b): ?>
                <option value="<?= Html::encode($b['id']) ?>"
                    data-name="<?= Html::encode($b['name']) ?>"
                    data-photo="<?= Url::to('@web/' . $b['image']) ?>"
                    data-badge="<?= Html::encode($b['badge']) ?>"
                    data-hour="<?= (int)$b['priceHour'] ?>"
                    data-day="<?= (int)$b['priceDay'] ?>"
                    data-desc="<?= Html::encode($b['desc']) ?>"
                    data-range="<?= Html::encode($b['range']) ?>"
                    data-weight="<?= Html::encode($b['weight']) ?>"
                    data-brakes="<?= Html::encode($b['brakes']) ?>"
                    data-frame="<?= Html::encode($b['frame'] . ' (' . $b['size'] . ')') ?>">
                  <?= Html::encode($b['name']) ?> (<?= Html::encode($b['badge']) ?>) — <?= $b['priceHour'] ?> ₽/ч | <?= number_format($b['priceDay'], 0, '', ' ') ?> ₽/день
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- 2. TARIFF MODE (HOURLY / DAILY) -->
          <div class="space-y-2">
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider">
              2. Тарифный режим
            </label>
            <div class="grid grid-cols-2 gap-2 bg-slate-100 p-1.5 rounded-2xl text-xs font-bold">
              <button type="button" id="btnModeHour" onclick="switchTariffMode('hour')" class="py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition flex items-center justify-center gap-2">
                <img src="<?= Url::to('@web/svg/clock.svg') ?>" class="w-4 h-4 inline-block" alt="">
                <span>По часам</span>
              </button>
              <button type="button" id="btnModeDay" onclick="switchTariffMode('day')" class="py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition flex items-center justify-center gap-2">
                <img src="<?= Url::to('@web/svg/sun.svg') ?>" class="w-4 h-4 inline-block" alt="">
                <span>По суткам (-40%)</span>
              </button>
            </div>
          </div>

          <!-- 3. DURATION SLIDER -->
          <div class="space-y-3 bg-slate-50 p-4 rounded-2xl border border-slate-200/80">
            <div class="flex items-center justify-between">
              <label id="durationLabel" class="text-xs font-bold text-slate-800">
                Срок аренды: 3 часа
              </label>
              <span id="discountTag" class="text-xs font-black text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                Скидка -10% применена
              </span>
            </div>
            
            <input type="range" id="durationSlider" min="1" max="12" value="3" oninput="onDurationChange(this.value)" class="w-full accent-emerald-500 cursor-pointer">
            
            <!-- Quick preset pills -->
            <div id="quickPills" class="flex flex-wrap items-center justify-between gap-1 text-[11px] text-slate-500 font-bold">
              <button type="button" onclick="setDurationPreset(1)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">1 час</button>
              <button type="button" onclick="setDurationPreset(2)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">2 часа</button>
              <button type="button" onclick="setDurationPreset(3)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">3 часа (-10%)</button>
              <button type="button" onclick="setDurationPreset(6)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">6 часов (-15%)</button>
              <button type="button" onclick="setDurationPreset(12)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">12 часов (-15%)</button>
            </div>
          </div>

          <!-- 4. STATIONS (PICKUP / RETURN) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
            <div class="space-y-1.5">
              <label class="font-bold text-slate-700 block">Станция получения</label>
              <select id="pickupStation" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
                <?php foreach ($stations as $st): ?>
                  <option value="<?= $st->id ?>"><?= Html::encode($st->name) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="space-y-1.5">
              <label class="font-bold text-slate-700 block">Станция возврата</label>
              <select id="returnStation" onchange="calculateTotal()" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium outline-none focus:bg-white focus:border-emerald-500">
                <option value="same">На станцию получения (0 ₽)</option>
                <option value="any">На любую другую станцию (+100 ₽)</option>
              </select>
            </div>
          </div>

          <!-- 5. ADDITIONAL OPTIONS (Checkboxes) -->
          <div class="space-y-2.5 pt-2 border-t border-slate-100">
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider">
              Дополнительное снаряжение и опции
            </label>

            <div class="space-y-2 text-xs">
              <!-- Option 1: Helmet -->
              <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 hover:border-emerald-300 cursor-pointer transition">
                <div class="flex items-center gap-3">
                  <input type="checkbox" id="optHelmet" onchange="calculateTotal()" checked class="rounded text-emerald-500 focus:ring-emerald-400 w-4 h-4">
                  <div>
                    <div class="font-bold text-slate-900">Защитный шлем Kask Urban</div>
                    <div class="text-[11px] text-slate-400">Дезинфицирован, размер регулируется (S/M/L)</div>
                  </div>
                </div>
                <span class="font-bold text-slate-800">+150 ₽</span>
              </label>

              <!-- Option 2: Insurance -->
              <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 hover:border-emerald-300 cursor-pointer transition">
                <div class="flex items-center gap-3">
                  <input type="checkbox" id="optInsurance" onchange="calculateTotal()" checked class="rounded text-emerald-500 focus:ring-emerald-400 w-4 h-4">
                  <div>
                    <div class="font-bold text-slate-900">Страховка «КАСКО-Байк»</div>
                    <div class="text-[11px] text-slate-400">Покрывает случайные повреждения и прокол колёс</div>
                  </div>
                </div>
                <span class="font-bold text-slate-800">+250 ₽</span>
              </label>

              <!-- Option 3: Phone holder -->
              <label class="flex items-center justify-between p-3 rounded-2xl border border-slate-200 hover:border-emerald-300 cursor-pointer transition">
                <div class="flex items-center gap-3">
                  <input type="checkbox" id="optHolder" onchange="calculateTotal()" class="rounded text-emerald-500 focus:ring-emerald-400 w-4 h-4">
                  <div>
                    <div class="font-bold text-slate-900">Держатель телефона + PowerBank</div>
                    <div class="text-[11px] text-slate-400">Для навигации по маршрутам и зарядки в пути</div>
                  </div>
                </div>
                <span class="font-bold text-slate-800">+100 ₽</span>
              </label>
            </div>
          </div>

          <!-- 6. LIVE COST BREAKDOWN SUMMARY -->
          <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-5 rounded-2xl space-y-3 shadow-lg">
            <div class="text-xs text-slate-400 font-bold uppercase tracking-wider flex justify-between">
              <span>Детализация сметы</span>
              <span class="text-emerald-400 font-bold">Тариф активен</span>
            </div>

            <div class="space-y-1.5 text-xs">
              <div class="flex justify-between text-slate-300">
                <span id="breakdownBaseLabel">Базовый тариф (3 ч × 550 ₽):</span>
                <span id="breakdownBaseVal" class="font-bold text-white">1 650 ₽</span>
              </div>
              <div class="flex justify-between text-slate-300">
                <span>Дополнительные опции:</span>
                <span id="breakdownAddonsVal" class="font-bold text-white">400 ₽</span>
              </div>
              <div class="flex justify-between text-emerald-400 font-medium">
                <span id="breakdownDiscountLabel">Скидка за длительность (-10%):</span>
                <span id="breakdownDiscountVal">-165 ₽</span>
              </div>
            </div>

            <div class="border-t border-slate-700 pt-3 flex justify-between items-baseline">
              <div>
                <span class="text-sm font-extrabold text-white">Итого к оплате:</span>
                <div class="text-[11px] text-slate-400">Списание при начале поездки</div>
              </div>
              <span id="breakdownTotalVal" class="text-3xl font-black text-emerald-400">
                1 885 ₽
              </span>
            </div>
          </div>

          <!-- 7. CUSTOMER CONTACT DETAILS -->
          <div class="space-y-3 pt-2 border-t border-slate-100 text-xs">
            <label class="block text-xs font-black text-slate-800 uppercase tracking-wider">
              Данные клиента для брони
            </label>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-slate-600 font-bold mb-1">Имя и фамилия</label>
                <input type="text" id="clientName" required value="<?= $u ? Html::encode($u->full_name) : 'Анна Ковалёва' ?>" placeholder="Ваше имя" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
              </div>

              <div>
                <label class="block text-slate-600 font-bold mb-1">Телефон (для PIN-кода замка)</label>
                <input type="tel" id="clientPhone" required value="<?= $u ? Html::encode($u->phone) : '+7 (999) 123-45-67' ?>" placeholder="+7 (999) 000-00-00" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
              </div>
            </div>

            <div>
              <label class="block text-slate-600 font-bold mb-1">Email для электронного чека</label>
              <input type="email" id="clientEmail" required value="<?= $u ? Html::encode($u->email) : 'anna.k@mail.ru' ?>" placeholder="email@example.com" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
            </div>

            <label class="flex items-start gap-2 pt-1 text-[11px] text-slate-500 cursor-pointer">
              <input type="checkbox" required checked class="rounded text-emerald-500 focus:ring-emerald-400 mt-0.5">
              <span>Я согласен с условиями безопасной эксплуатации велосипеда и правилами сервиса VeloGo.</span>
            </label>
          </div>

          <!-- SUBMIT BUTTON -->
          <button type="submit" class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black text-sm sm:text-base shadow-xl shadow-emerald-500/30 transition flex items-center justify-center gap-2">
            <span>Забронировать поездку</span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>

        </form>

      </div>
    </div>

  </div>
</section>

<!-- SUCCESS MODAL -->
<div id="successModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl text-center space-y-5 animate-scaleUp">
    <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 flex items-center justify-center p-3.5">
      <img src="<?= Url::to('@web/svg/check.svg') ?>" class="w-9 h-9 text-emerald-600" alt="">
    </div>
    <div>
      <h3 class="text-2xl font-extrabold text-slate-900">Бронирование успешно!</h3>
      <p class="text-xs text-slate-500 mt-1" id="modalBookingInfo">
        Аренда № 20492 оформлена. Велосипед зарезервирован на 20 минут.
      </p>
    </div>

    <div class="bg-slate-50 p-4 rounded-2xl text-left text-xs space-y-2 border border-slate-100">
      <div class="flex justify-between">
        <span class="text-slate-400">Клиент:</span>
        <span class="font-bold text-slate-800" id="modalClient">Анна Ковалёва</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-400">Велосипед:</span>
        <span class="font-bold text-slate-800" id="modalBike">VoltRide Urban Pro</span>
      </div>
      <div class="flex justify-between">
        <span class="text-slate-400">Сумма к оплате:</span>
        <span class="font-bold text-emerald-600 text-sm" id="modalTotal">1 885 ₽</span>
      </div>
      <div class="flex justify-between border-t border-slate-200 pt-2">
        <span class="text-slate-500 font-bold">PIN-код замка:</span>
        <span id="modalPinCode" class="font-mono text-base font-black text-slate-900 bg-white px-2 rounded border border-slate-300">8492</span>
      </div>
    </div>

    <div class="space-y-2">
      <?= Html::a('Перейти в Личный кабинет', ['site/account'], [
          'class' => 'block w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition text-center'
      ]) ?>
      <button onclick="closeSuccessModal()" class="w-full py-2.5 text-slate-500 hover:text-slate-800 text-xs font-semibold">
        Остаться на странице
      </button>
    </div>
  </div>
</div>

<script>
  let currentMode = 'hour'; // 'hour' | 'day'
  let currentDuration = 3;

  function onBikeChange() {
    const select = document.getElementById('bikeSelect');
    const opt = select.options[select.selectedIndex];

    // Update Left Preview Card (Photo & Specs)
    document.getElementById('bikeTitle').innerText = opt.dataset.name;
    if (opt.dataset.photo) {
      document.getElementById('bikePhoto').src = opt.dataset.photo;
    }
    document.getElementById('bikeBadge').innerText = opt.dataset.badge;
    document.getElementById('bikeDescription').innerText = opt.dataset.desc;
    document.getElementById('specRange').innerText = opt.dataset.range;
    document.getElementById('specWeight').innerText = opt.dataset.weight;
    document.getElementById('specBrakes').innerText = opt.dataset.brakes;
    document.getElementById('specFrame').innerText = opt.dataset.frame;

    calculateTotal();
  }

  function switchTariffMode(mode) {
    currentMode = mode;
    const btnH = document.getElementById('btnModeHour');
    const btnD = document.getElementById('btnModeDay');
    const slider = document.getElementById('durationSlider');
    const quickPills = document.getElementById('quickPills');

    if (mode === 'hour') {
      btnH.className = 'py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition flex items-center justify-center gap-2';
      btnD.className = 'py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition flex items-center justify-center gap-2';
      slider.min = 1;
      slider.max = 12;
      slider.value = 3;
      currentDuration = 3;
      quickPills.innerHTML = `
        <button type="button" onclick="setDurationPreset(1)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">1 час</button>
        <button type="button" onclick="setDurationPreset(2)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">2 часа</button>
        <button type="button" onclick="setDurationPreset(3)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">3 часа (-10%)</button>
        <button type="button" onclick="setDurationPreset(6)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">6 часов (-15%)</button>
        <button type="button" onclick="setDurationPreset(12)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">12 часов (-15%)</button>
      `;
    } else {
      btnD.className = 'py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition flex items-center justify-center gap-2';
      btnH.className = 'py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition flex items-center justify-center gap-2';
      slider.min = 1;
      slider.max = 7;
      slider.value = 1;
      currentDuration = 1;
      quickPills.innerHTML = `
        <button type="button" onclick="setDurationPreset(1)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">1 день</button>
        <button type="button" onclick="setDurationPreset(2)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">2 дня (Уикенд)</button>
        <button type="button" onclick="setDurationPreset(3)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">3 дня (-10%)</button>
        <button type="button" onclick="setDurationPreset(5)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">5 дней (-15%)</button>
        <button type="button" onclick="setDurationPreset(7)" class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 hover:border-emerald-500">7 дней (-15%)</button>
      `;
    }
    onDurationChange(slider.value);
  }

  function setDurationPreset(val) {
    document.getElementById('durationSlider').value = val;
    onDurationChange(val);
  }

  function onDurationChange(val) {
    currentDuration = parseInt(val);
    const label = document.getElementById('durationLabel');
    const discountTag = document.getElementById('discountTag');

    if (currentMode === 'hour') {
      label.innerText = `Срок аренды: ${currentDuration} ${currentDuration === 1 ? 'час' : (currentDuration < 5 ? 'часа' : 'часов')}`;
    } else {
      label.innerText = `Срок аренды: ${currentDuration} ${currentDuration === 1 ? 'день' : (currentDuration < 5 ? 'дня' : 'дней')}`;
    }

    // Check discount qualification
    if ((currentMode === 'hour' && currentDuration >= 6) || (currentMode === 'day' && currentDuration >= 5)) {
      discountTag.style.display = 'inline-block';
      discountTag.innerText = 'Скидка -15% применена';
    } else if ((currentMode === 'hour' && currentDuration >= 3) || (currentMode === 'day' && currentDuration >= 3)) {
      discountTag.style.display = 'inline-block';
      discountTag.innerText = 'Скидка -10% применена';
    } else {
      discountTag.style.display = 'none';
    }

    calculateTotal();
  }

  function calculateTotal() {
    const select = document.getElementById('bikeSelect');
    const opt = select.options[select.selectedIndex];
    const ratePerHour = parseInt(opt.dataset.hour);
    const ratePerDay = parseInt(opt.dataset.day);

    let baseRate = (currentMode === 'hour') ? ratePerHour : ratePerDay;
    let rawBase = baseRate * currentDuration;

    // Addons
    let addons = 0;
    if (document.getElementById('optHelmet').checked) addons += 150;
    if (document.getElementById('optInsurance').checked) addons += 250;
    if (document.getElementById('optHolder').checked) addons += 100;
    if (document.getElementById('returnStation').value === 'any') addons += 100;

    // Discount calculation
    let discountPercent = 0;
    if ((currentMode === 'hour' && currentDuration >= 6) || (currentMode === 'day' && currentDuration >= 5)) {
      discountPercent = 0.15;
    } else if ((currentMode === 'hour' && currentDuration >= 3) || (currentMode === 'day' && currentDuration >= 3)) {
      discountPercent = 0.10;
    }

    let discountSum = Math.round(rawBase * discountPercent);
    let total = rawBase + addons - discountSum;
    calculatedTotalPrice = total;

    // Update Breakdown DOM
    const unitText = (currentMode === 'hour') ? 'ч' : 'дн.';
    document.getElementById('breakdownBaseLabel').innerText = `Базовый тариф (${currentDuration} ${unitText} × ${baseRate.toLocaleString('ru-RU')} ₽):`;
    document.getElementById('breakdownBaseVal').innerText = `${rawBase.toLocaleString('ru-RU')} ₽`;
    document.getElementById('breakdownAddonsVal').innerText = `${addons.toLocaleString('ru-RU')} ₽`;
    document.getElementById('breakdownDiscountLabel').innerText = `Скидка за длительность (-${discountPercent * 100}%):`;
    document.getElementById('breakdownDiscountVal').innerText = `-${discountSum.toLocaleString('ru-RU')} ₽`;
    document.getElementById('breakdownTotalVal').innerText = `${total.toLocaleString('ru-RU')} ₽`;
  }

  let calculatedTotalPrice = 1885;

  function handleBookingSubmit(e) {
    e.preventDefault();

    const isGuest = <?= Yii::$app->user->isGuest ? 'true' : 'false' ?>;
    if (isGuest) {
      openAuthModal('login');
      document.getElementById('authGateSubtitle').innerText = 'Для оформления бронирования велосипеда войдите в аккаунт или зарегистрируйтесь.';
      return;
    }

    const select = document.getElementById('bikeSelect');
    const opt = select.options[select.selectedIndex];
    const stationId = document.getElementById('pickupStation').value;
    const helmet = document.getElementById('optHelmet').checked ? 1 : 0;
    const insurance = document.getElementById('optInsurance').checked ? 1 : 0;
    const holder = document.getElementById('optHolder').checked ? 1 : 0;

    const btn = e.target.querySelector('button[type="submit"]');
    const origBtnText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<span>Сохранение в базу данных...</span>';

    const formData = new FormData();
    formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
    formData.append('bike_slug', opt.value);
    formData.append('station_id', stationId);
    formData.append('tariff_type', currentMode === 'hour' ? 'hourly' : 'daily');
    formData.append('duration_hours', currentDuration);
    formData.append('helmet', helmet);
    formData.append('insurance', insurance);
    formData.append('holder', holder);
    formData.append('total_price', calculatedTotalPrice);

    fetch('<?= Url::to(['site/book-rental']) ?>', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      btn.disabled = false;
      btn.innerHTML = origBtnText;

      if (data.success) {
        document.getElementById('modalBookingInfo').innerText = 'Аренда № ' + data.rentalNumber + ' оформлена и сохранена в базе данных!';
        document.getElementById('modalClient').innerText = data.clientName;
        document.getElementById('modalBike').innerText = data.bikeName;
        document.getElementById('modalTotal').innerText = data.totalPrice;
        document.getElementById('modalPinCode').innerText = data.pin;
        document.getElementById('successModal').classList.remove('hidden');
      } else {
        alert(data.message || 'Ошибка оформления бронирования.');
      }
    })
    .catch(err => {
      btn.disabled = false;
      btn.innerHTML = origBtnText;
      alert('Ошибка соединения с базой данных.');
    });
  }

  function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
  }

  // Init with URL params (e.g. ?bike=city)
  window.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const bikeParam = urlParams.get('bike');
    if (bikeParam) {
      const select = document.getElementById('bikeSelect');
      for (let i = 0; i < select.options.length; i++) {
        if (select.options[i].value === bikeParam) {
          select.selectedIndex = i;
          break;
        }
      }
    }
    onBikeChange();
  });
</script>
