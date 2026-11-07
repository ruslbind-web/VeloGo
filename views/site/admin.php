<?php

/* @var $this yii\web\View */
/* @var $bikes app\models\Bike[] */
/* @var $categories app\models\BikeType[] */
/* @var $stations app\models\Station[] */
/* @var $stats array */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'VeloGo — Панель администратора | Управление базой данных';
?>

<!-- BREADCRUMBS & TITLE -->
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
  <div>
    <nav class="flex items-center gap-2 text-xs text-slate-400 mb-2">
      <?= Html::a('Главная', ['site/index'], ['class' => 'hover:text-emerald-600 transition']) ?>
      <span>/</span>
      <?= Html::a('Личный кабинет', ['site/account'], ['class' => 'hover:text-emerald-600 transition']) ?>
      <span>/</span>
      <span class="text-slate-800 font-semibold">Администрирование каталога</span>
    </nav>
    <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex items-center gap-3">
      <span>Управление базой данных VeloGo</span>
      <span class="px-3 py-1 rounded-full text-xs font-black bg-rose-100 text-rose-700 uppercase">Админ-панель</span>
    </h1>
    <p class="text-xs sm:text-sm text-slate-500 mt-1">
      Добавляйте, редактируйте и удаляйте велосипеды. Все изменения мгновенно отображаются в каталоге и калькуляторе.
    </p>
  </div>

  <button onclick="openAddModal()" class="px-5 py-3 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs sm:text-sm font-bold shadow-lg shadow-emerald-500/25 transition flex items-center gap-2 self-start sm:self-auto">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    <span>Добавить велосипед</span>
  </button>
</div>

<!-- FLASH MESSAGES -->
<?php if (Yii::$app->session->hasFlash('success')): ?>
  <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-bold flex items-center gap-3">
    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span><?= Html::encode(Yii::$app->session->getFlash('success')) ?></span>
  </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
  <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm font-bold flex items-center gap-3">
    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    <span><?= Html::encode(Yii::$app->session->getFlash('error')) ?></span>
  </div>
<?php endif; ?>

<!-- STATS CARDS -->
<div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
  <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="text-[11px] font-bold text-slate-400 uppercase">Всего байков</div>
    <div class="text-2xl font-black text-slate-900 mt-1"><?= $stats['totalBikes'] ?></div>
  </div>
  <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="text-[11px] font-bold text-emerald-600 uppercase">Доступно</div>
    <div class="text-2xl font-black text-emerald-600 mt-1"><?= $stats['availableBikes'] ?></div>
  </div>
  <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="text-[11px] font-bold text-blue-600 uppercase">Активных аренд</div>
    <div class="text-2xl font-black text-blue-600 mt-1"><?= $stats['activeRentals'] ?></div>
  </div>
  <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
    <div class="text-[11px] font-bold text-slate-400 uppercase">Станций</div>
    <div class="text-2xl font-black text-slate-900 mt-1"><?= $stats['totalStations'] ?></div>
  </div>
  <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm col-span-2 sm:col-span-1">
    <div class="text-[11px] font-bold text-slate-400 uppercase">Клиентов в БД</div>
    <div class="text-2xl font-black text-slate-900 mt-1"><?= $stats['totalUsers'] ?></div>
  </div>
</div>

<!-- BIKES TABLE -->
<div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
  <div class="p-5 border-b border-slate-100 flex items-center justify-between">
    <div>
      <h2 class="font-extrabold text-slate-900 text-lg">Каталог моделей в базе данных (Таблица `bikes`)</h2>
      <p class="text-xs text-slate-400">Данные считываются из MySQL в режиме реального времени</p>
    </div>
    <?= Html::a('Открыть каталог на сайте →', ['site/catalog'], ['class' => 'text-xs font-bold text-emerald-600 hover:underline', 'target' => '_blank']) ?>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-left text-xs">
      <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black border-b border-slate-100">
        <tr>
          <th class="py-3.5 px-4">Байк / Фото</th>
          <th class="py-3.5 px-4">Категория</th>
          <th class="py-3.5 px-4">Тарифы (час / день)</th>
          <th class="py-3.5 px-4">Рама / Вес</th>
          <th class="py-3.5 px-4">Статус</th>
          <th class="py-3.5 px-4">Станция</th>
          <th class="py-3.5 px-4 text-right">Действия</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100 font-medium">
        <?php foreach ($bikes as $bike): ?>
          <tr class="hover:bg-slate-50/70 transition">
            <td class="py-3.5 px-4 flex items-center gap-3">
              <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200/60">
                <img src="<?= Url::to('@web/' . $bike->image) ?>" alt="" class="w-full h-full object-cover">
              </div>
              <div>
                <div class="font-extrabold text-slate-900 text-sm"><?= Html::encode($bike->name) ?></div>
                <div class="text-[11px] text-slate-400 font-mono">slug: <?= Html::encode($bike->slug) ?> (id: <?= $bike->id ?>)</div>
              </div>
            </td>

            <td class="py-3.5 px-4">
              <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-100 text-slate-700">
                <?= Html::encode($bike->type ? $bike->type->name : $bike->type_code) ?>
              </span>
            </td>

            <td class="py-3.5 px-4">
              <div class="font-black text-slate-900"><?= $bike->price_hour ?> ₽ <span class="text-slate-400 font-normal">/ час</span></div>
              <div class="text-emerald-600 font-bold"><?= number_format($bike->price_day, 0, '', ' ') ?> ₽ / день</div>
            </td>

            <td class="py-3.5 px-4 text-slate-600">
              <div>Рама: <span class="font-bold text-slate-800"><?= Html::encode($bike->size) ?></span></div>
              <div class="text-slate-400 text-[11px]"><?= Html::encode($bike->weight ?: '—') ?></div>
            </td>

            <td class="py-3.5 px-4">
              <?php if ($bike->status === 'available'): ?>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-800">Доступен</span>
              <?php elseif ($bike->status === 'rented'): ?>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-blue-100 text-blue-800">В аренде</span>
              <?php else: ?>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-800">На ТО</span>
              <?php endif; ?>
            </td>

            <td class="py-3.5 px-4 text-slate-600">
              <?= Html::encode($bike->station ? $bike->station->name : 'Не указана') ?>
            </td>

            <td class="py-3.5 px-4 text-right space-x-2 whitespace-nowrap">
              <button onclick='openEditModal(<?= json_encode($bike->attributes) ?>)' class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition">
                Изменить
              </button>

              <?= Html::a('Удалить', ['site/bike-delete', 'id' => $bike->id], [
                  'class' => 'px-3 py-1.5 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold transition',
                  'data' => [
                      'confirm' => 'Вы уверены, что хотите удалить велосипед «' . $bike->name . '» из базы данных?',
                      'method' => 'post',
                  ],
              ]) ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL: ADD BIKE -->
<div id="addModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl space-y-5 animate-scaleUp max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <h3 class="text-xl font-extrabold text-slate-900">Добавить новый велосипед в БД</h3>
      <button onclick="closeAddModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
    </div>

    <?= Html::beginForm(['site/bike-create'], 'post', ['class' => 'space-y-4 text-xs']) ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2">
          <label class="block text-slate-600 font-bold mb-1">Название велосипеда *</label>
          <input type="text" name="Bike[name]" required placeholder="Например, HyperSpeed Urban GT" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Категория *</label>
          <select name="Bike[type_code]" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat->code ?>"><?= Html::encode($cat->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Размер рамы</label>
          <select name="Bike[size]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <option value="S">S (150-165 см)</option>
            <option value="M" selected>M (165-180 см)</option>
            <option value="L">L (180-195 см)</option>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Цена за 1 час (₽) *</label>
          <input type="number" name="Bike[price_hour]" required value="400" min="50" step="50" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Цена за сутки (₽) *</label>
          <input type="number" name="Bike[price_day]" required value="1400" min="200" step="50" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Фотография модели</label>
          <select name="Bike[image]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <option value="images/voltride.jpg">VoltRide (Электро)</option>
            <option value="images/city.jpg">City Cruiser (Городской)</option>
            <option value="images/trailmaster.jpg">TrailMaster (MTB Горный)</option>
            <option value="images/foldmaster.jpg">FoldMaster (Складной)</option>
            <option value="images/family.jpg">FamilyTour (Семейный)</option>
            <option value="images/gravel.jpg">Gravel Speedster (Шоссе)</option>
            <option value="images/ecargo.jpg">E-Cargo (Грузовой)</option>
            <option value="images/fatbike.jpg">Fatbike (Вездеход)</option>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Станция размещения</label>
          <select name="Bike[current_station_id]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <?php foreach ($stations as $st): ?>
              <option value="<?= $st->id ?>"><?= Html::encode($st->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Бейдж на карточке</label>
          <input type="text" name="Bike[badge]" value="Новинка" placeholder="Хит продаж, Новинка, Комфорт" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Запас хода (или Без батареи)</label>
          <input type="text" name="Bike[range_km]" value="до 60 км" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Вес байка</label>
          <input type="text" name="Bike[weight]" value="15.2 кг" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Тормоза</label>
          <input type="text" name="Bike[brakes]" value="Дисковые гидравлические" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div class="sm:col-span-2">
          <label class="block text-slate-600 font-bold mb-1">Краткое описание *</label>
          <textarea name="Bike[description]" required rows="2" placeholder="Особенности конструкции, навесное оборудование..." class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">Надежный велосипед нового поколения для комфортных поездок по городу и паркам.</textarea>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeAddModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">
          Отмена
        </button>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold shadow-md shadow-emerald-500/20 transition">
          Сохранить в БД
        </button>
      </div>
    <?= Html::endForm() ?>
  </div>
</div>

<!-- MODAL: EDIT BIKE -->
<div id="editModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
  <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-xl w-full shadow-2xl space-y-5 animate-scaleUp max-h-[90vh] overflow-y-auto">
    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
      <h3 class="text-xl font-extrabold text-slate-900">Редактирование велосипеда</h3>
      <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-xl font-bold">&times;</button>
    </div>

    <form id="editForm" method="post" class="space-y-4 text-xs">
      <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>
      
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="sm:col-span-2">
          <label class="block text-slate-600 font-bold mb-1">Название велосипеда *</label>
          <input type="text" id="editName" name="Bike[name]" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Категория *</label>
          <select id="editTypeCode" name="Bike[type_code]" required class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat->code ?>"><?= Html::encode($cat->name) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Статус доступности</label>
          <select id="editStatus" name="Bike[status]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <option value="available">Доступен (в прокате)</option>
            <option value="rented">В аренде у клиента</option>
            <option value="maintenance">На техническом обслуживании</option>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Цена за 1 час (₽) *</label>
          <input type="number" id="editPriceHour" name="Bike[price_hour]" required min="50" step="50" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Цена за сутки (₽) *</label>
          <input type="number" id="editPriceDay" name="Bike[price_day]" required min="200" step="50" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Размер рамы</label>
          <select id="editSize" name="Bike[size]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-bold text-slate-800 outline-none focus:bg-white focus:border-emerald-500 cursor-pointer">
            <option value="S">S (150-165 см)</option>
            <option value="M">M (165-180 см)</option>
            <option value="L">L (180-195 см)</option>
          </select>
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Бейдж на карточке</label>
          <input type="text" id="editBadge" name="Bike[badge]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Запас хода</label>
          <input type="text" id="editRange" name="Bike[range_km]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div>
          <label class="block text-slate-600 font-bold mb-1">Вес байка</label>
          <input type="text" id="editWeight" name="Bike[weight]" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
        </div>

        <div class="sm:col-span-2">
          <label class="block text-slate-600 font-bold mb-1">Описание *</label>
          <textarea id="editDesc" name="Bike[description]" required rows="2" class="w-full p-3 rounded-xl bg-slate-50 border border-slate-200 font-medium text-slate-800 outline-none focus:bg-white focus:border-emerald-500"></textarea>
        </div>
      </div>

      <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-100">
        <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold transition">
          Отмена
        </button>
        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold shadow-md shadow-emerald-500/20 transition">
          Сохранить изменения
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function openAddModal() {
    document.getElementById('addModal').classList.remove('hidden');
  }
  function closeAddModal() {
    document.getElementById('addModal').classList.add('hidden');
  }

  function openEditModal(bike) {
    document.getElementById('editForm').action = '<?= Url::to(['site/bike-update']) ?>?id=' + bike.id;
    document.getElementById('editName').value = bike.name || '';
    document.getElementById('editTypeCode').value = bike.type_code || 'city';
    document.getElementById('editStatus').value = bike.status || 'available';
    document.getElementById('editPriceHour').value = bike.price_hour || '';
    document.getElementById('editPriceDay').value = bike.price_day || '';
    document.getElementById('editSize').value = bike.size || 'M';
    document.getElementById('editBadge').value = bike.badge || '';
    document.getElementById('editRange').value = bike.range_km || '';
    document.getElementById('editWeight').value = bike.weight || '';
    document.getElementById('editDesc').value = bike.description || '';

    document.getElementById('editModal').classList.remove('hidden');
  }
  function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
  }
</script>
