<?php

/* @var $this yii\web\View */
/* @var $model app\models\LoginForm */
/* @var $signupModel app\models\SignupForm */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VeloGo — Вход и Регистрация';
?>

<div class="max-w-md mx-auto my-6 sm:my-10">
  <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xl space-y-6">
    
    <!-- BRAND HEADER -->
    <div class="text-center space-y-2">
      <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-black text-2xl shadow-lg shadow-emerald-500/25">
        V
      </div>
      <h1 class="text-2xl font-black text-slate-900">Добро пожаловать в VeloGo</h1>
      <p class="text-xs text-slate-500">Войдите в личный кабинет или зарегистрируйтесь за 30 секунд</p>
    </div>

    <!-- TABS -->
    <div class="grid grid-cols-2 gap-1 bg-slate-100 p-1.5 rounded-2xl text-xs font-bold">
      <button type="button" id="tabBtnLogin" onclick="switchAuthTab('login')" class="py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition">
        Вход в профиль
      </button>
      <button type="button" id="tabBtnSignup" onclick="switchAuthTab('signup')" class="py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition">
        Быстрая регистрация
      </button>
    </div>

    <!-- FLASH MESSAGES -->
    <?php if (Yii::$app->session->hasFlash('error')): ?>
      <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-bold">
        <?= Html::encode(Yii::$app->session->getFlash('error')) ?>
      </div>
    <?php endif; ?>

    <!-- 1. LOGIN FORM -->
    <div id="authPanelLogin" class="space-y-4">
      <?= Html::beginForm(['site/login'], 'post', ['class' => 'space-y-3.5 text-xs']) ?>
        <div>
          <label class="block text-slate-600 font-bold mb-1">Логин или Email</label>
          <input type="text" name="LoginForm[username]" required autofocus placeholder="admin или anna" value="<?= Html::encode($model->username) ?>" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Пароль</label>
          <input type="password" name="LoginForm[password]" required placeholder="••••••••" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div class="flex items-center justify-between text-slate-500 pt-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="LoginForm[rememberMe]" value="1" checked class="rounded text-emerald-500 focus:ring-emerald-400">
            <span>Запомнить меня</span>
          </label>
        </div>

        <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-md shadow-emerald-500/25 transition">
          Войти в профиль
        </button>
      <?= Html::endForm() ?>

      <!-- QUICK TEST LOGINS -->
      <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] text-slate-500 space-y-1">
        <div class="font-bold text-slate-700">Тестовые аккаунты из базы данных:</div>
        <div>👤 Клиент: <button onclick="fillLogin('anna', 'anna123')" class="font-mono text-emerald-600 font-bold hover:underline">anna / anna123</button></div>
        <div>🔑 Админ: <button onclick="fillLogin('admin', 'admin')" class="font-mono text-emerald-600 font-bold hover:underline">admin / admin</button></div>
      </div>
    </div>

    <!-- 2. SIGNUP FORM -->
    <div id="authPanelSignup" class="hidden space-y-4">
      <?= Html::beginForm(['site/signup'], 'post', ['class' => 'space-y-3.5 text-xs']) ?>
        <div>
          <label class="block text-slate-600 font-bold mb-1">Ваше имя и фамилия *</label>
          <input type="text" name="SignupForm[full_name]" required placeholder="Иван Петров" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Логин для входа *</label>
          <input type="text" name="SignupForm[username]" required placeholder="ivan_rider" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Номер телефона *</label>
          <input type="tel" name="SignupForm[phone]" required placeholder="+7 (999) 000-00-00" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Электронная почта *</label>
          <input type="email" name="SignupForm[email]" required placeholder="ivan@example.com" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Придумайте пароль *</label>
          <input type="password" name="SignupForm[password]" required placeholder="Минимум 4 символа" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Повторите пароль *</label>
          <input type="password" name="SignupForm[password_repeat]" required placeholder="Повторите пароль" class="w-full p-3.5 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <button type="submit" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm shadow-md shadow-emerald-500/25 transition">
          Зарегистрироваться (+100 бонусов)
        </button>
      <?= Html::endForm() ?>
    </div>

  </div>
</div>

<script>
  function switchAuthTab(tab) {
    const btnL = document.getElementById('tabBtnLogin');
    const btnS = document.getElementById('tabBtnSignup');
    const panelL = document.getElementById('authPanelLogin');
    const panelS = document.getElementById('authPanelSignup');

    if (tab === 'login') {
      btnL.className = 'py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition';
      btnS.className = 'py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition';
      panelL.classList.remove('hidden');
      panelS.classList.add('hidden');
    } else {
      btnS.className = 'py-2.5 rounded-xl bg-white text-slate-900 shadow-sm transition';
      btnL.className = 'py-2.5 rounded-xl text-slate-600 hover:text-slate-900 transition';
      panelS.classList.remove('hidden');
      panelL.classList.add('hidden');
    }
  }

  function fillLogin(u, p) {
    document.querySelector('input[name="LoginForm[username]"]').value = u;
    document.querySelector('input[name="LoginForm[password]"]').value = p;
  }
</script>
