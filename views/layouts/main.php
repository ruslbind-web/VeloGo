<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use app\assets\AppAsset;

AppAsset::register($this);
$actionId = Yii::$app->controller->action->id;
$isGuest = Yii::$app->user->isGuest;
$currentUser = !$isGuest ? Yii::$app->user->identity : null;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">
<?php $this->beginBody() ?>

  <!-- TOP INFO BAR -->
  <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800 hidden sm:block">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
      <div class="flex items-center gap-6">
        <span class="flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span> Работаем круглосуточно 24/7</span>
        <span class="flex items-center gap-1.5"><img src="<?= Url::to('@web/svg/pin.svg') ?>" class="w-3.5 h-3.5 inline-block" alt=""> Санкт-Петербург (45 станций)</span>
        <span class="flex items-center gap-1.5"><img src="<?= Url::to('@web/svg/phone.svg') ?>" class="w-3.5 h-3.5 inline-block" alt=""> 8 (800) 555-35-35 (Бесплатно по РФ)</span>
      </div>
      <div class="flex items-center gap-4 text-slate-400">
        <?php if (!$isGuest): ?>
          <span class="text-emerald-400 font-semibold flex items-center gap-1">
            <span>●</span> Вы вошли как <?= Html::encode($currentUser->full_name) ?> (<?= $currentUser->bonus_points ?> бонусов)
          </span>
          <?php if ($currentUser->getIsAdmin()): ?>
            <?= Html::a('⚡ Панель БД (Админ)', ['site/admin'], ['class' => 'text-amber-400 hover:text-amber-300 font-bold']) ?>
          <?php endif; ?>
          <?= Html::a('Выйти', ['site/logout'], ['class' => 'text-rose-400 hover:text-rose-300 font-medium']) ?>
        <?php else: ?>
          <span>Сезон 2026 открыт</span>
          <button onclick="openAuthModal('login')" class="text-emerald-400 hover:text-emerald-300 font-medium cursor-pointer">
            Войти в профиль →
          </button>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- MAIN HEADER / NAVBAR -->
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200/80 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
      <!-- Logo -->
      <a href="<?= Url::to(['site/index']) ?>" class="flex items-center gap-3 group">
        <div class="w-11 h-11 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-2xl shadow-md shadow-emerald-500/25 group-hover:scale-105 transition select-none">
          V
        </div>
        <span class="font-black text-2xl sm:text-3xl tracking-tight text-slate-900 leading-none">Velo<span class="text-emerald-500">Go</span></span>
      </a>

      <!-- Desktop Navigation -->
      <nav class="hidden md:flex items-center gap-1 bg-slate-100/80 p-1.5 rounded-2xl border border-slate-200/60 text-sm font-semibold">
        <a href="<?= Url::to(['site/index']) ?>" class="px-4 py-2 rounded-xl <?= $actionId === 'index' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' ?> transition">
          Главная
        </a>
        <a href="<?= Url::to(['site/catalog']) ?>" class="px-4 py-2 rounded-xl <?= $actionId === 'catalog' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' ?> transition">
          Каталог
        </a>
        <a href="<?= Url::to(['site/calculator']) ?>" class="px-4 py-2 rounded-xl <?= $actionId === 'calculator' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-white/60' ?> transition flex items-center gap-1.5">
          <span>Тарифы и калькулятор</span>
          <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-1.5 py-0.5 rounded-full">-10%</span>
        </a>
        <?php if (!$isGuest && $currentUser->getIsAdmin()): ?>
          <a href="<?= Url::to(['site/admin']) ?>" class="px-4 py-2 rounded-xl <?= $actionId === 'admin' ? 'bg-rose-500 text-white shadow-sm' : 'text-rose-600 hover:bg-rose-50' ?> transition font-bold flex items-center gap-1">
            <span>Админ БД</span>
          </a>
        <?php endif; ?>
      </nav>

      <!-- Profile Button / Auth Button & Mobile Toggle -->
      <div class="flex items-center gap-3">
        <?php if (!$isGuest): ?>
          <a href="<?= Url::to(['site/account']) ?>" class="flex items-center gap-2.5 px-4 py-2 rounded-xl <?= $actionId === 'account' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/30' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200/80 shadow-sm' ?> text-sm font-bold transition">
            <div class="w-7 h-7 rounded-lg <?= $actionId === 'account' ? 'bg-white/20 text-white' : 'bg-emerald-500 text-white' ?> flex items-center justify-center text-xs font-black shadow-sm">
              <?= Html::encode($currentUser->getInitials()) ?>
            </div>
            <span class="hidden sm:inline"><?= Html::encode($currentUser->full_name) ?></span>
          </a>
          <?= Html::a('
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          ', ['site/logout'], [
              'class' => 'p-2.5 rounded-xl bg-slate-100 text-slate-500 hover:text-rose-600 hover:bg-rose-50 transition',
              'title' => 'Выйти из профиля',
          ]) ?>
        <?php else: ?>
          <button onclick="openAuthModal('login')" class="flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-bold shadow-md shadow-emerald-500/25 transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            <span>Вход / Регистрация</span>
          </button>
        <?php endif; ?>

        <!-- Mobile Menu Toggle Button -->
        <button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 focus:outline-none" aria-label="Открыть меню">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div id="mobile-menu" class="hidden md:hidden border-t border-slate-200 bg-white px-4 py-4 space-y-2 text-sm font-semibold">
      <a href="<?= Url::to(['site/index']) ?>" class="block px-3 py-2 rounded-xl <?= $actionId === 'index' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-100' ?>">Главная</a>
      <a href="<?= Url::to(['site/catalog']) ?>" class="block px-3 py-2 rounded-xl <?= $actionId === 'catalog' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-100' ?>">Каталог велосипедов</a>
      <a href="<?= Url::to(['site/calculator']) ?>" class="block px-3 py-2 rounded-xl <?= $actionId === 'calculator' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-100' ?>">Тарифы и калькулятор</a>
      <?php if (!$isGuest): ?>
        <a href="<?= Url::to(['site/account']) ?>" class="block px-3 py-2 rounded-xl <?= $actionId === 'account' ? 'bg-emerald-50 text-emerald-700' : 'text-slate-700 hover:bg-slate-100' ?>">Личный кабинет (<?= Html::encode($currentUser->full_name) ?>)</a>
        <?php if ($currentUser->getIsAdmin()): ?>
          <a href="<?= Url::to(['site/admin']) ?>" class="block px-3 py-2 rounded-xl text-rose-600 font-bold hover:bg-rose-50">Админ-панель каталога БД</a>
        <?php endif; ?>
        <a href="<?= Url::to(['site/logout']) ?>" class="block px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50">Выйти из аккаунта</a>
      <?php else: ?>
        <button onclick="openAuthModal('login')" class="w-full text-left px-3 py-2 rounded-xl bg-emerald-500 text-white font-bold">Войти / Регистрация</button>
      <?php endif; ?>
    </div>
  </header>

  <!-- MAIN CONTENT CONTAINER -->
  <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 py-6 md:py-8 space-y-8 sm:space-y-12">
    <?= $content ?>
  </main>

  <!-- FOOTER -->
  <footer class="bg-slate-900 text-slate-300 mt-auto border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12 space-y-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Col 1: Brand Info -->
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-xl shadow-md select-none">
              V
            </div>
            <span class="font-black text-white text-2xl tracking-tight leading-none">Velo<span class="text-emerald-400">Go</span></span>
          </div>
          <p class="text-xs text-slate-400 leading-relaxed">
            Современный сервис поминутной и посуточной аренды велосипедов. Полная интеграция с базой данных MySQL.
          </p>
          <div class="text-xs text-emerald-400 font-semibold">
            Поддержка: 8 (800) 555-35-35 (24/7)
          </div>
        </div>

        <!-- Col 2: Navigation Links -->
        <div class="space-y-3 text-xs">
          <div class="font-bold text-white text-sm uppercase tracking-wider">Разделы сайта</div>
          <ul class="space-y-2 text-slate-400">
            <li><?= Html::a('Главная страница', ['site/index'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <li><?= Html::a('Каталог велосипедов', ['site/catalog'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <li><?= Html::a('Тарифы и калькулятор стоимости', ['site/calculator'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <li><?= Html::a('Личный кабинет клиента', ['site/account'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <?php if (!$isGuest && $currentUser->getIsAdmin()): ?>
              <li><?= Html::a('⚡ Панель управления БД', ['site/admin'], ['class' => 'text-amber-400 hover:underline font-bold']) ?></li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- Col 3: Categories -->
        <div class="space-y-3 text-xs">
          <div class="font-bold text-white text-sm uppercase tracking-wider">Категории байков</div>
          <ul class="space-y-2 text-slate-400">
            <li><?= Html::a('Электровелосипеды', ['site/catalog', 'type' => 'electro'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <li><?= Html::a('Городские круизеры', ['site/catalog', 'type' => 'city'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <li><?= Html::a('Горные трейловые (MTB)', ['site/catalog', 'type' => 'mtb'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
            <li><?= Html::a('Детские и семейные байки', ['site/catalog', 'type' => 'family'], ['class' => 'hover:text-emerald-400 transition']) ?></li>
          </ul>
        </div>

        <!-- Col 4: Newsletter -->
        <div class="space-y-3">
          <div class="font-bold text-white text-sm uppercase tracking-wider">Рассылка акций</div>
          <p class="text-xs text-slate-400">Промокоды на бесплатные часы проката раз в месяц без спама.</p>
          <form onsubmit="event.preventDefault(); alert('Спасибо! Промокод на 1 час бесплатной поездки отправлен на ваш Email.');" class="flex gap-2">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
            <input type="email" required placeholder="Ваш email" class="bg-slate-800 border border-slate-700 text-white text-xs px-3.5 py-2.5 rounded-xl w-full focus:outline-none focus:border-emerald-500">
            <button type="submit" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition">OK</button>
          </form>
        </div>

      </div>

      <!-- Bottom Credits -->
      <div class="border-t border-slate-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
        <div>&copy; 2026 VeloGo Inc. Все права защищены.</div>
        <div class="flex items-center gap-4">
          <span class="text-emerald-400 font-medium">● База данных MySQL активна</span>
          <span class="text-slate-600">|</span>
          <span class="text-slate-400">OpenServer PHP 8.0</span>
        </div>
      </div>
    </div>
  </footer>

  <!-- ========================================================================= -->
  <!-- MODAL: REGISTRATION & LOGIN GATE FOR GUEST USERS                          -->
  <!-- ========================================================================= -->
  <div id="authGateModal" class="hidden fixed inset-0 z-50 bg-slate-900/70 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl space-y-5 animate-scaleUp relative max-h-[92vh] overflow-y-auto">
      
      <!-- Close Button -->
      <button onclick="closeAuthModal()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 text-2xl font-bold leading-none cursor-pointer">
        &times;
      </button>

      <!-- BRAND HEADER -->
      <div class="text-center space-y-1.5 pt-1">
        <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-emerald-500/25">
          V
        </div>
        <h3 class="text-xl sm:text-2xl font-black text-slate-900">Вход в сервис VeloGo</h3>
        <p class="text-xs text-slate-500" id="authGateSubtitle">
          Войдите или зарегистрируйтесь, чтобы бронировать велосипеды
        </p>
      </div>

      <!-- TABS -->
      <div class="grid grid-cols-2 gap-1 bg-slate-100 p-1.5 rounded-2xl text-xs font-bold">
        <button type="button" id="gateTabLogin" onclick="switchModalAuthTab('login')" class="py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition">
          Войти
        </button>
        <button type="button" id="gateTabSignup" onclick="switchModalAuthTab('signup')" class="py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition">
          Регистрация (+100 б.)
        </button>
      </div>

      <!-- Error box -->
      <div id="modalAuthError" class="hidden p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold"></div>

      <!-- 1. LOGIN FORM -->
      <form id="modalLoginForm" onsubmit="submitModalLogin(event)" class="space-y-3.5 text-xs">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
        <div>
          <label class="block text-slate-600 font-bold mb-1">Логин или Email</label>
          <input type="text" id="modalLoginUser" required placeholder="anna или admin" value="anna" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Пароль</label>
          <input type="password" id="modalLoginPass" required placeholder="••••••••" value="anna123" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <button type="submit" id="modalLoginSubmitBtn" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-md shadow-emerald-500/25 transition flex items-center justify-center gap-2">
          <span>Войти в аккаунт</span>
        </button>

        <!-- Fast fill buttons -->
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
          <div class="font-bold text-slate-700">Быстрый тестовый вход из БД:</div>
          <div class="flex gap-2">
            <button type="button" onclick="setFastAuth('anna', 'anna123')" class="px-2 py-1 rounded bg-white border border-slate-200 text-emerald-700 font-bold hover:border-emerald-500">
              👤 Клиент: anna
            </button>
            <button type="button" onclick="setFastAuth('admin', 'admin')" class="px-2 py-1 rounded bg-white border border-slate-200 text-rose-700 font-bold hover:border-rose-500">
              🔑 Админ: admin
            </button>
          </div>
        </div>
      </form>

      <!-- 2. SIGNUP FORM -->
      <form id="modalSignupForm" onsubmit="submitModalSignup(event)" class="hidden space-y-3 text-xs">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
        <div>
          <label class="block text-slate-600 font-bold mb-1">Имя и фамилия *</label>
          <input type="text" id="modalSignName" required placeholder="Александр Смирнов" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-slate-600 font-bold mb-1">Логин *</label>
            <input type="text" id="modalSignUsername" required placeholder="alex_rider" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>
          <div>
            <label class="block text-slate-600 font-bold mb-1">Телефон *</label>
            <input type="tel" id="modalSignPhone" required placeholder="+7 (999) 000-00-00" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Email *</label>
          <input type="email" id="modalSignEmail" required placeholder="alex@mail.ru" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div class="grid grid-cols-2 gap-2">
          <div>
            <label class="block text-slate-600 font-bold mb-1">Пароль *</label>
            <input type="password" id="modalSignPass" required placeholder="••••••" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>
          <div>
            <label class="block text-slate-600 font-bold mb-1">Повтор *</label>
            <input type="password" id="modalSignPassRep" required placeholder="••••••" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
          </div>
        </div>

        <button type="submit" id="modalSignSubmitBtn" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-md shadow-emerald-500/25 transition">
          Зарегистрироваться (+100 бонусов)
        </button>
      </form>

      <div class="text-center pt-1 border-t border-slate-100">
        <button type="button" onclick="closeAuthModal()" class="text-xs text-slate-400 hover:text-slate-600 font-semibold cursor-pointer">
          Продолжить просмотр каталога без входа →
        </button>
      </div>

    </div>
  </div>

  <script>
    // AUTO OPEN AUTH MODAL FOR GUESTS UPON ENTERING THE SITE
    document.addEventListener('DOMContentLoaded', function () {
      <?php if ($isGuest): ?>
        // Show modal on entry if not dismissed in current session
        const dismissed = sessionStorage.getItem('velogo_auth_dismissed');
        if (!dismissed) {
          setTimeout(function() {
            openAuthModal('login');
          }, 400);
        }
      <?php endif; ?>
    });

    function openAuthModal(tab) {
      const modal = document.getElementById('authGateModal');
      if (modal) {
        modal.classList.remove('hidden');
        switchModalAuthTab(tab || 'login');
      }
    }

    function closeAuthModal() {
      const modal = document.getElementById('authGateModal');
      if (modal) {
        modal.classList.add('hidden');
        sessionStorage.setItem('velogo_auth_dismissed', '1');
      }
    }

    function switchModalAuthTab(tab) {
      const tabL = document.getElementById('gateTabLogin');
      const tabS = document.getElementById('gateTabSignup');
      const formL = document.getElementById('modalLoginForm');
      const formS = document.getElementById('modalSignupForm');
      const errBox = document.getElementById('modalAuthError');
      errBox.classList.add('hidden');

      if (tab === 'login') {
        tabL.className = 'py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition';
        tabS.className = 'py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition';
        formL.classList.remove('hidden');
        formS.classList.add('hidden');
      } else {
        tabS.className = 'py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition';
        tabL.className = 'py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition';
        formS.classList.remove('hidden');
        formL.classList.add('hidden');
      }
    }

    function setFastAuth(u, p) {
      document.getElementById('modalLoginUser').value = u;
      document.getElementById('modalLoginPass').value = p;
    }

    // AJAX LOGIN
    function submitModalLogin(e) {
      e.preventDefault();
      const errBox = document.getElementById('modalAuthError');
      const btn = document.getElementById('modalLoginSubmitBtn');
      errBox.classList.add('hidden');
      btn.disabled = true;
      btn.innerText = 'Вход...';

      const formData = new FormData();
      formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
      formData.append('LoginForm[username]', document.getElementById('modalLoginUser').value);
      formData.append('LoginForm[password]', document.getElementById('modalLoginPass').value);

      fetch('<?= Url::to(['site/login']) ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = 'Войти в аккаунт';
        if (data.success) {
          window.location.reload();
        } else {
          errBox.innerText = data.message || 'Ошибка входа.';
          errBox.classList.remove('hidden');
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = 'Войти в аккаунт';
        errBox.innerText = 'Ошибка соединения с сервером.';
        errBox.classList.remove('hidden');
      });
    }

    // AJAX SIGNUP
    function submitModalSignup(e) {
      e.preventDefault();
      const errBox = document.getElementById('modalAuthError');
      const btn = document.getElementById('modalSignSubmitBtn');
      errBox.classList.add('hidden');

      const p1 = document.getElementById('modalSignPass').value;
      const p2 = document.getElementById('modalSignPassRep').value;
      if (p1 !== p2) {
        errBox.innerText = 'Введенные пароли не совпадают!';
        errBox.classList.remove('hidden');
        return;
      }

      btn.disabled = true;
      btn.innerText = 'Создание аккаунта...';

      const formData = new FormData();
      formData.append('<?= Yii::$app->request->csrfParam ?>', '<?= Yii::$app->request->getCsrfToken() ?>');
      formData.append('SignupForm[full_name]', document.getElementById('modalSignName').value);
      formData.append('SignupForm[username]', document.getElementById('modalSignUsername').value);
      formData.append('SignupForm[phone]', document.getElementById('modalSignPhone').value);
      formData.append('SignupForm[email]', document.getElementById('modalSignEmail').value);
      formData.append('SignupForm[password]', p1);
      formData.append('SignupForm[password_repeat]', p2);

      fetch('<?= Url::to(['site/signup']) ?>', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        btn.disabled = false;
        btn.innerText = 'Зарегистрироваться (+100 бонусов)';
        if (data.success) {
          window.location.reload();
        } else {
          errBox.innerText = data.message || 'Ошибка регистрации.';
          errBox.classList.remove('hidden');
        }
      })
      .catch(err => {
        btn.disabled = false;
        btn.innerText = 'Зарегистрироваться (+100 бонусов)';
        errBox.innerText = 'Ошибка соединения с сервером.';
        errBox.classList.remove('hidden');
      });
    }

    function toggleMobileMenu() {
      const menu = document.getElementById('mobile-menu');
      menu.classList.toggle('hidden');
    }
  </script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
