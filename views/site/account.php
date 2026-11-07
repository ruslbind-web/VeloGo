<?php

/* @var $this yii\web\View */
/* @var $user app\models\User */
/* @var $activeRental app\models\Rental|null */
/* @var $historyRentals app\models\Rental[] */
/* @var $favorites app\models\Bike[] */
/* @var $totalTrips int */
/* @var $totalDistance float */
/* @var $totalSpent float */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VeloGo — Личный кабинет клиента | История аренд';
?>

<!-- BREADCRUMBS -->
<nav class="flex items-center gap-2 text-xs text-slate-400">
  <?= Html::a('Главная', ['site/index'], ['class' => 'hover:text-emerald-600 transition']) ?>
  <span>/</span>
  <span class="text-slate-800 font-semibold">Личный кабинет</span>
</nav>

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Личный кабинет клиента</h1>
  <?php if ($user->getIsAdmin()): ?>
    <?= Html::a('⚡ Перейти в панель управления базой данных (Админ)', ['site/admin'], [
        'class' => 'px-4 py-2 rounded-xl bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 text-xs font-bold transition'
    ]) ?>
  <?php endif; ?>
</div>

<!-- LAYOUT: LEFT MENU + RIGHT CONTENT -->
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
  
  <!-- LEFT SIDEBAR: PROFILE SUMMARY & NAVIGATION (4 cols) -->
  <aside class="lg:col-span-4 space-y-5">
    
    <!-- User Identity Mini Card (From DB `users`) -->
    <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white font-extrabold text-xl flex items-center justify-center shadow-md shadow-emerald-500/20 shrink-0">
        <?= Html::encode($user->getInitials()) ?>
      </div>
      <div class="overflow-hidden">
        <h2 class="font-extrabold text-slate-900 text-base leading-snug"><?= Html::encode($user->full_name) ?></h2>
        <div class="text-xs text-slate-400 truncate"><?= Html::encode($user->email) ?></div>
        <div class="text-[11px] text-emerald-600 font-bold mt-0.5 flex items-center gap-1">
          <span>●</span> <?= $user->is_verified ? 'Верифицирован' : 'Подтвержден' ?>
          <?php if ($user->getIsAdmin()): ?>
            <span class="text-rose-600 font-black ml-1">[Администратор]</span>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Account Side Nav -->
    <div class="bg-white rounded-3xl p-3 border border-slate-200/80 shadow-sm text-xs font-bold space-y-1">
      <button onclick="switchTab('active')" id="tab-btn-active" class="account-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl bg-emerald-500 text-white shadow-sm transition">
        <span class="flex items-center gap-2.5">
          <img src="<?= Url::to('@web/svg/bike.svg') ?>" class="w-4 h-4 filter brightness-0 invert" alt="">
          <span>Текущая аренда</span>
        </span>
        <span class="px-2 py-0.5 rounded-full <?= $activeRental ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' ?> text-[10px] font-black uppercase">
          <?= $activeRental ? '1 акт.' : '0 акт.' ?>
        </span>
      </button>

      <button onclick="switchTab('history')" id="tab-btn-history" class="account-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 transition">
        <span class="flex items-center gap-2.5">
          <img src="<?= Url::to('@web/svg/clipboard.svg') ?>" class="w-4 h-4" alt="">
          <span>История аренд</span>
        </span>
        <span class="text-slate-400 font-semibold"><?= count($historyRentals) ?></span>
      </button>

      <button onclick="switchTab('favorites')" id="tab-btn-favorites" class="account-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 transition">
        <span class="flex items-center gap-2.5">
          <img src="<?= Url::to('@web/svg/heart.svg') ?>" class="w-4 h-4" alt="">
          <span>Избранные байки</span>
        </span>
        <span class="text-slate-400 font-semibold"><?= count($favorites) ?></span>
      </button>

      <button onclick="switchTab('personal')" id="tab-btn-personal" class="account-nav-btn w-full flex items-center justify-between px-3.5 py-3 rounded-2xl text-slate-600 hover:bg-slate-50 transition">
        <span class="flex items-center gap-2.5">
          <img src="<?= Url::to('@web/svg/user.svg') ?>" class="w-4 h-4" alt="">
          <span>Личные данные</span>
        </span>
      </button>

      <div class="border-t border-slate-100 my-1 pt-1">
        <?= Html::a('
          <img src="' . Url::to('@web/svg/logout.svg') . '" class="w-4 h-4" alt="">
          <span>Выйти из профиля</span>
        ', ['site/logout'], ['class' => 'flex items-center gap-2.5 px-3.5 py-2.5 rounded-2xl text-rose-500 hover:bg-rose-50 transition']) ?>
      </div>
    </div>

    <!-- Quick Help Card -->
    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white rounded-3xl p-5 space-y-3">
      <div class="font-bold text-sm">Служба заботы о райдерах</div>
      <p class="text-xs text-slate-300">
        Спустило колесо или возникли вопросы по замку? Нажмите кнопку или позвоните.
      </p>
      <a href="tel:88005553535" class="block w-full py-2.5 text-center bg-white/10 hover:bg-white/20 border border-white/20 rounded-xl text-xs font-bold text-white transition flex items-center justify-center gap-2">
        <img src="<?= Url::to('@web/svg/phone.svg') ?>" class="w-4 h-4" alt="">
        <span>8 (800) 555-35-35</span>
      </a>
    </div>

  </aside>

  <!-- RIGHT MAIN CONTENT (8 cols) -->
  <div class="lg:col-span-8 space-y-6">
    
    <!-- TOP STATS BANNER (From DB `users` and `rentals`) -->
    <div class="bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-slate-100 rounded-3xl p-6 border border-emerald-500/20 flex flex-col sm:flex-row items-center justify-between gap-5">
      <div class="flex items-center gap-4 text-center sm:text-left">
        <div class="w-16 h-16 rounded-2xl bg-emerald-500 text-white font-black flex items-center justify-center text-2xl shadow-lg shadow-emerald-500/25 shrink-0">
          <?= Html::encode($user->getInitials()) ?>
        </div>
        <div>
          <h2 class="text-xl font-extrabold text-slate-900"><?= Html::encode($user->full_name) ?></h2>
          <p class="text-xs text-slate-500">Логин: <?= Html::encode($user->username) ?> • Телефон: <?= Html::encode($user->phone) ?></p>
          
          <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 sm:gap-6 mt-3 text-xs">
            <div>
              <span class="text-base font-black text-slate-900"><?= $totalTrips ?></span>
              <span class="text-slate-500 block text-[11px]">поездок</span>
            </div>
            <div class="border-l border-slate-300 pl-4">
              <span class="text-base font-black text-slate-900"><?= $totalDistance ?> км</span>
              <span class="text-slate-500 block text-[11px]">пройдено</span>
            </div>
            <div class="border-l border-slate-300 pl-4">
              <span class="text-base font-black text-emerald-600"><?= number_format($totalSpent, 0, '', ' ') ?> ₽</span>
              <span class="text-slate-500 block text-[11px]">оплачено</span>
            </div>
            <div class="border-l border-slate-300 pl-4">
              <span class="text-base font-black text-amber-500"><?= $user->bonus_points ?> б.</span>
              <span class="text-slate-500 block text-[11px]">бонусов</span>
            </div>
          </div>
        </div>
      </div>

      <button onclick="switchTab('personal')" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-800 border border-slate-200 rounded-xl text-xs font-bold transition shadow-sm whitespace-nowrap">
        Редактировать
      </button>
    </div>

    <!-- 1. ACTIVE LIVE RENTAL CARD (From DB `rentals`) -->
    <section id="section-active" class="bg-white rounded-3xl p-6 border-2 border-emerald-500 shadow-md space-y-4">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
        <div class="flex items-center gap-2.5">
          <span class="relative flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
          </span>
          <h2 class="font-black text-slate-900 text-base sm:text-lg">Текущая активная поездка</h2>
        </div>
        <?php if ($activeRental): ?>
          <div class="flex items-center gap-2">
            <span class="text-xs text-slate-400">PIN замка:</span>
            <span class="px-3 py-1 rounded-full text-xs font-mono font-black bg-emerald-100 text-emerald-800">
              <?= Html::encode($activeRental->pin_code) ?>
            </span>
          </div>
        <?php endif; ?>
      </div>

      <?php if ($activeRental): ?>
        <!-- Bike in trip summary -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/60">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden shadow-sm border border-slate-200/80 shrink-0 bg-slate-100">
              <img src="<?= Url::to('@web/' . ($activeRental->bike ? $activeRental->bike->image : 'images/voltride.jpg')) ?>" alt="" class="w-full h-full object-cover">
            </div>
            <div>
              <div class="text-[11px] text-slate-400 uppercase font-black">
                Аренда № <?= Html::encode($activeRental->rental_number) ?> • Тариф «<?= $activeRental->tariff_type === 'daily' ? 'Посуточный' : 'Почасовой' ?>»
              </div>
              <div class="font-extrabold text-slate-900 text-base">
                <?= Html::encode($activeRental->bike ? $activeRental->bike->name : 'Велосипед VeloGo') ?>
              </div>
              <div class="text-xs text-slate-500 mt-0.5">
                Старт: <?= date('d.m.Y в H:i', strtotime($activeRental->start_time)) ?> • <?= Html::encode($activeRental->startStation ? $activeRental->startStation->name : 'Станция 1') ?>
              </div>
              <div class="text-xs text-emerald-600 font-semibold mt-0.5">
                Опции: <?= $activeRental->helmet_included ? 'Шлем' : '' ?> <?= $activeRental->insurance_included ? '+ Страховка' : '' ?> <?= $activeRental->holder_included ? '+ Держатель' : '' ?>
              </div>
            </div>
          </div>

          <!-- Cost counter & Finish button -->
          <div class="flex items-center justify-between md:justify-end gap-5 w-full md:w-auto pt-3 md:pt-0 border-t md:border-t-0 border-slate-200">
            <div class="text-left md:text-right">
              <div class="text-[11px] text-slate-400 font-medium">Стоимость:</div>
              <div class="text-2xl font-black text-slate-900"><?= number_format($activeRental->total_price, 0, '', ' ') ?> ₽</div>
              <div class="text-[10px] text-emerald-600">Оплачено онлайн</div>
            </div>

            <button onclick="finishLiveRental()" class="px-5 py-3 bg-rose-500 hover:bg-rose-600 text-white rounded-xl text-xs font-bold shadow-md shadow-rose-500/25 transition">
              Завершить поездку
            </button>
          </div>
        </div>

        <!-- Quick lock controls -->
        <div class="flex flex-wrap items-center justify-between gap-3 text-xs bg-emerald-50/60 p-3 rounded-xl border border-emerald-100">
          <span class="text-emerald-800 font-medium flex items-center gap-1.5">
            <img src="<?= Url::to('@web/svg/lock.svg') ?>" class="w-4 h-4 inline-block text-emerald-800" alt="">
            <span>PIN-код смарт-замка: <strong class="font-mono text-slate-900 text-sm"><?= Html::encode($activeRental->pin_code) ?></strong>. Нажмите кнопку при парковке:</span>
          </span>
          <div class="flex gap-2">
            <button onclick="alert('Смарт-замок заблокирован на временную паузу. PIN-код: <?= Html::encode($activeRental->pin_code) ?>')" class="px-3 py-1.5 bg-white border border-emerald-300 text-emerald-800 rounded-lg font-bold hover:bg-emerald-100 transition">
              Поставить на паузу
            </button>
          </div>
        </div>

      <?php else: ?>
        <div class="text-center py-8 space-y-3">
          <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-50 flex items-center justify-center p-3 text-emerald-600">
            <img src="<?= Url::to('@web/svg/bike.svg') ?>" class="w-8 h-8" alt="">
          </div>
          <div class="font-extrabold text-slate-800 text-base">У вас сейчас нет активной поездки</div>
          <p class="text-xs text-slate-500 max-w-sm mx-auto">
            Выберите понравившийся велосипед в каталоге или воспользуйтесь калькулятором аренды.
          </p>
          <?= Html::a('Выбрать велосипед в каталоге', ['site/catalog'], [
              'class' => 'inline-block px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-md transition'
          ]) ?>
        </div>
      <?php endif; ?>
    </section>

    <!-- 2. RENTAL HISTORY (From DB `rentals`) -->
    <section id="section-history" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div>
          <h2 class="font-extrabold text-slate-900 text-lg">История аренд (Завершенные поездки)</h2>
          <p class="text-xs text-slate-500">Записи из базы данных MySQL</p>
        </div>
      </div>

      <!-- History items container -->
      <div id="historyList" class="space-y-3">
        <?php if (!empty($historyRentals)): ?>
          <?php foreach ($historyRentals as $rental): ?>
            <article class="p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-sm border border-slate-200/80 shrink-0 bg-slate-100">
                  <img src="<?= Url::to('@web/' . ($rental->bike ? $rental->bike->image : 'images/voltride.jpg')) ?>" alt="" class="w-full h-full object-cover">
                </div>
                <div>
                  <div class="flex flex-wrap items-center gap-2">
                    <span class="font-bold text-slate-900 text-sm">
                      Аренда № <?= Html::encode($rental->rental_number) ?> — <?= Html::encode($rental->bike ? $rental->bike->name : 'Велосипед') ?>
                    </span>
                    <?php if ($rental->status === 'completed'): ?>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Завершена</span>
                    <?php else: ?>
                      <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-100 text-rose-700">Отменена</span>
                    <?php endif; ?>
                  </div>
                  <div class="text-xs text-slate-500 mt-0.5">
                    <?= date('d.m.Y', strtotime($rental->start_time)) ?> • <?= $rental->duration_hours ?> ч • 
                    <?= Html::encode($rental->route_name ?: ($rental->startStation ? $rental->startStation->name : 'Станция 1')) ?>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-4 self-end sm:self-center">
                <span class="font-black text-slate-900 text-base"><?= number_format($rental->total_price, 0, '', ' ') ?> ₽</span>
                <?php if ($rental->bike): ?>
                  <?= Html::a('Повторить', ['site/calculator', 'bike' => $rental->bike->slug], [
                      'class' => 'px-3.5 py-1.5 rounded-xl border border-slate-200 text-slate-700 text-xs font-bold hover:bg-slate-50 transition'
                  ]) ?>
                <?php endif; ?>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-center py-6 text-xs text-slate-400">История поездок пуста.</div>
        <?php endif; ?>
      </div>
    </section>

    <!-- 3. PERSONAL DETAILS (Form saving to DB `users`) -->
    <section id="section-personal" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div>
          <h2 class="font-extrabold text-slate-900 text-lg">Личные данные клиента</h2>
          <p class="text-xs text-slate-500">Сохраняются в таблице `users` базы данных</p>
        </div>
      </div>

      <div id="profileSaveMsg" class="hidden p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold"></div>

      <form onsubmit="savePersonalData(event)" class="space-y-4 text-xs">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div>
            <label class="block text-slate-500 font-bold mb-1">Имя и фамилия</label>
            <input type="text" id="profName" value="<?= Html::encode($user->full_name) ?>" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>

          <div>
            <label class="block text-slate-500 font-bold mb-1">Телефон для SMS к замку</label>
            <input type="tel" id="profPhone" value="<?= Html::encode($user->phone) ?>" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>

          <div>
            <label class="block text-slate-500 font-bold mb-1">Email для электронных чеков</label>
            <input type="email" id="profEmail" value="<?= Html::encode($user->email) ?>" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>

          <div>
            <label class="block text-slate-500 font-bold mb-1">Дата рождения</label>
            <input type="date" id="profBirth" value="<?= Html::encode($user->birth_date) ?>" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>
        </div>

        <div>
          <label class="block text-slate-500 font-bold mb-1">Основной адрес / Станция по умолчанию</label>
          <input type="text" id="profAddress" value="<?= Html::encode($user->default_address) ?>" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div class="flex items-center gap-3 pt-2">
          <button type="submit" id="saveProfBtn" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold shadow-md shadow-emerald-500/20 transition">
            Сохранить изменения в БД
          </button>
        </div>
      </form>
    </section>

    <!-- 4. FAVORITE BIKES (From DB `user_favorites`) -->
    <section id="section-favorites" class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-sm space-y-4">
      <div class="flex items-center justify-between border-b border-slate-100 pb-3">
        <div>
          <h2 class="font-extrabold text-slate-900 text-lg">Избранные велосипеды (<?= count($favorites) ?>)</h2>
          <p class="text-xs text-slate-500">Быстрое бронирование моделей из базы данных</p>
        </div>
        <?= Html::a('Смотреть весь каталог →', ['site/catalog'], ['class' => 'text-xs font-bold text-emerald-600 hover:underline']) ?>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <?php foreach ($favorites as $fav): ?>
          <article class="bg-white rounded-[20px] border border-slate-200/80 overflow-hidden hover:shadow-lg transition flex flex-col justify-between">
            <div>
              <div class="relative w-full h-36 bg-slate-100 overflow-hidden">
                <img src="<?= Url::to('@web/' . $fav->image) ?>" alt="" class="w-full h-full object-cover">
                <span class="absolute top-2 left-2 text-[10px] font-black bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                  <?= Html::encode($fav->badge ?: 'ХИТ') ?>
                </span>
              </div>
              <div class="p-3.5 space-y-1">
                <div class="text-[10px] text-slate-400 font-bold uppercase"><?= Html::encode($fav->type_label) ?></div>
                <div class="font-extrabold text-slate-900 text-sm"><?= Html::encode($fav->name) ?></div>
                <div class="text-xs font-black text-emerald-600"><?= $fav->price_hour ?> ₽ / час</div>
              </div>
            </div>
            <div class="p-3.5 pt-0">
              <?= Html::a('Рассчитать', ['site/calculator', 'bike' => $fav->slug], [
                  'class' => 'block text-center py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition'
              ]) ?>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>

  </div>

</div>

<script>
  function switchTab(tabId) {
    const sections = ['active', 'history', 'personal', 'favorites'];
    sections.forEach(s => {
      const el = document.getElementById('section-' + s);
      const btn = document.getElementById('tab-btn-' + s);
      if (el) {
        if (s === tabId) {
          el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
      }
    });
  }

  function finishLiveRental() {
    if (!confirm('Вы уверены, что припарковали велосипед на станции и хотите завершить поездку?')) {
      return;
    }

    const formData = new FormData();
    formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');

    fetch('<?= Url::to(['site/finish-rental']) ?>', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
        window.location.reload();
      } else {
        alert(data.message || 'Ошибка завершения поездки.');
      }
    })
    .catch(err => {
      alert('Ошибка соединения с базой данных.');
    });
  }

  function savePersonalData(e) {
    e.preventDefault();
    const btn = document.getElementById('saveProfBtn');
    const msg = document.getElementById('profileSaveMsg');
    btn.disabled = true;
    btn.innerText = 'Сохранение...';

    const formData = new FormData();
    formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
    formData.append('full_name', document.getElementById('profName').value);
    formData.append('phone', document.getElementById('profPhone').value);
    formData.append('email', document.getElementById('profEmail').value);
    formData.append('birth_date', document.getElementById('profBirth').value);
    formData.append('default_address', document.getElementById('profAddress').value);

    fetch('<?= Url::to(['site/save-profile']) ?>', {
      method: 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      btn.disabled = false;
      btn.innerText = 'Сохранить изменения в БД';
      if (data.success) {
        msg.innerText = data.message;
        msg.classList.remove('hidden');
        setTimeout(() => msg.classList.add('hidden'), 3500);
      } else {
        alert(data.message || 'Ошибка сохранения данных.');
      }
    })
    .catch(err => {
      btn.disabled = false;
      btn.innerText = 'Сохранить изменения в БД';
      alert('Ошибка соединения с сервером.');
    });
  }
</script>
