<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;
use app\models\Bike;
use app\models\BikeType;
use app\models\Station;
use app\models\Rental;
use app\models\User;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'account', 'admin', 'bike-create', 'bike-update', 'bike-delete'],
                'rules' => [
                    [
                        'actions' => ['logout', 'account'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['admin', 'bike-create', 'bike-update', 'bike-delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return !Yii::$app->user->isGuest && Yii::$app->user->identity->getIsAdmin();
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post', 'get'],
                    'book-rental' => ['post'],
                    'finish-rental' => ['post'],
                    'save-profile' => ['post'],
                    'bike-create' => ['post'],
                    'bike-update' => ['post'],
                    'bike-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $allBikes = Bike::getAll();
        $featuredBikes = array_slice($allBikes, 0, 4);
        $categories = BikeType::find()->all();
        $stations = Station::find()->where(['is_active' => 1])->all();

        return $this->render('index', [
            'featuredBikes' => $featuredBikes,
            'categories' => $categories,
            'stations' => $stations,
        ]);
    }

    /**
     * Displays bike catalog.
     *
     * @return string
     */
    public function actionCatalog($type = null, $station = null)
    {
        $bikes = Bike::getAll();
        $categories = BikeType::find()->all();
        $stations = Station::find()->where(['is_active' => 1])->all();

        // Calculate counts per category
        $categoryCounts = [];
        $rawCounts = Bike::find()
            ->select(['type_code', 'COUNT(*) as cnt'])
            ->groupBy('type_code')
            ->asArray()
            ->all();
        foreach ($rawCounts as $c) {
            $categoryCounts[$c['type_code']] = (int)$c['cnt'];
        }

        return $this->render('catalog', [
            'bikes' => $bikes,
            'categories' => $categories,
            'stations' => $stations,
            'categoryCounts' => $categoryCounts,
            'selectedType' => $type,
            'selectedStation' => $station,
        ]);
    }

    /**
     * Displays rental calculator & tariffs.
     *
     * @return string
     */
    public function actionCalculator($bike = null)
    {
        $bikes = Bike::getAll();
        $stations = Station::find()->where(['is_active' => 1])->all();

        return $this->render('calculator', [
            'bikes' => $bikes,
            'stations' => $stations,
            'selectedBike' => $bike,
        ]);
    }

    /**
     * Handles live booking from calculator.
     *
     * @return Response
     */
    public function actionBookRental()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return [
                'success' => false,
                'requireAuth' => true,
                'message' => 'Для оформления бронирования необходимо войти или зарегистрироваться.',
            ];
        }

        $post = Yii::$app->request->post();
        $bikeSlug = $post['bike_slug'] ?? 'voltride';
        $bike = Bike::findOne(['slug' => $bikeSlug]) ?? Bike::findOne(1);

        if (!$bike) {
            return ['success' => false, 'message' => 'Выбранный велосипед не найден в базе данных.'];
        }

        $startStationId = (int)($post['station_id'] ?? 1);
        $tariffType = in_array($post['tariff_type'] ?? '', ['hourly', 'daily', 'weekend', 'week'])
            ? $post['tariff_type']
            : 'hourly';
        $durationHours = max(1, (int)($post['duration_hours'] ?? 1));

        $helmet = !empty($post['helmet']) ? 1 : 0;
        $insurance = !empty($post['insurance']) ? 1 : 0;
        $holder = !empty($post['holder']) ? 1 : 0;

        $basePrice = (float)($post['base_price'] ?? ($tariffType === 'daily' ? $bike->price_day : $bike->price_hour * $durationHours));
        $addonsPrice = (float)($post['addons_price'] ?? 0);
        $discountAmount = (float)($post['discount_amount'] ?? 0);
        $totalPrice = (float)($post['total_price'] ?? max(100, $basePrice + $addonsPrice - $discountAmount));

        $pin = sprintf('%04d', rand(1000, 9999));
        $rentalNumber = (string)rand(20500, 99999);

        $rental = new Rental();
        $rental->rental_number = $rentalNumber;
        $rental->user_id = Yii::$app->user->id;
        $rental->bike_id = $bike->id;
        $rental->start_station_id = $startStationId;
        $rental->tariff_type = $tariffType;
        $rental->start_time = date('Y-m-d H:i:s');
        $rental->duration_hours = $durationHours;
        $rental->pin_code = $pin;
        $rental->helmet_included = $helmet;
        $rental->insurance_included = $insurance;
        $rental->holder_included = $holder;
        $rental->base_price = $basePrice;
        $rental->addons_price = $addonsPrice;
        $rental->discount_amount = $discountAmount;
        $rental->total_price = $totalPrice;
        $rental->status = 'active';
        $rental->distance_km = 0.0;

        if ($rental->save()) {
            return [
                'success' => true,
                'rentalNumber' => $rentalNumber,
                'pin' => $pin,
                'bikeName' => $bike->name,
                'totalPrice' => number_format($totalPrice, 0, '', ' ') . ' ₽',
                'clientName' => Yii::$app->user->identity->full_name,
                'redirect' => \yii\helpers\Url::to(['/site/account']),
            ];
        }

        return [
            'success' => false,
            'message' => 'Ошибка сохранения бронирования: ' . json_encode($rental->getErrors(), JSON_UNESCAPED_UNICODE),
        ];
    }

    /**
     * Displays personal account.
     *
     * @return Response|string
     */
    public function actionAccount()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;

        // Fetch active rental from DB
        $activeRental = Rental::find()
            ->where(['user_id' => $user->id, 'status' => 'active'])
            ->with(['bike', 'startStation'])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        // Fetch past rental history from DB
        $historyRentals = Rental::find()
            ->where(['user_id' => $user->id])
            ->andWhere(['!=', 'status', 'active'])
            ->with(['bike', 'startStation', 'endStation'])
            ->orderBy(['id' => SORT_DESC])
            ->all();

        // Calculate summary stats
        $totalTrips = count($historyRentals) + ($activeRental ? 1 : 0);
        $totalDistance = (float)Rental::find()->where(['user_id' => $user->id])->sum('distance_km');
        $totalSpent = (float)Rental::find()->where(['user_id' => $user->id, 'status' => 'completed'])->sum('total_price');

        // Fetch favorite bikes
        $favorites = $user->getFavorites()->all();
        if (empty($favorites)) {
            $favorites = Bike::find()->limit(3)->all();
        }

        return $this->render('account', [
            'user' => $user,
            'activeRental' => $activeRental,
            'historyRentals' => $historyRentals,
            'favorites' => $favorites,
            'totalTrips' => $totalTrips,
            'totalDistance' => round($totalDistance, 1),
            'totalSpent' => $totalSpent,
        ]);
    }

    /**
     * Finishes the active rental in personal account.
     *
     * @return Response
     */
    public function actionFinishRental()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Необходимо авторизоваться.'];
        }

        $activeRental = Rental::find()
            ->where(['user_id' => Yii::$app->user->id, 'status' => 'active'])
            ->orderBy(['id' => SORT_DESC])
            ->one();

        if ($activeRental) {
            $activeRental->status = 'completed';
            $activeRental->end_time = date('Y-m-d H:i:s');
            $activeRental->end_station_id = $activeRental->start_station_id;
            if ($activeRental->distance_km <= 0) {
                $activeRental->distance_km = round(mt_rand(50, 180) / 10, 1);
            }
            $activeRental->save(false);

            // Award bonus points (5% of total price)
            $user = Yii::$app->user->identity;
            $user->bonus_points += (int)($activeRental->total_price * 0.05);
            $user->save(false);

            return ['success' => true, 'message' => 'Поездка успешно завершена!'];
        }

        return ['success' => false, 'message' => 'Активная поездка не найдена.'];
    }

    /**
     * Updates personal profile data.
     *
     * @return Response
     */
    public function actionSaveProfile()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'message' => 'Необходимо авторизоваться.'];
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;
        $post = Yii::$app->request->post();

        if (!empty($post['full_name'])) {
            $user->full_name = trim($post['full_name']);
        }
        if (!empty($post['phone'])) {
            $user->phone = trim($post['phone']);
        }
        if (!empty($post['email'])) {
            $user->email = trim($post['email']);
        }
        if (!empty($post['birth_date'])) {
            $user->birth_date = trim($post['birth_date']);
        }
        if (isset($post['default_address'])) {
            $user->default_address = trim($post['default_address']);
        }

        if ($user->save()) {
            return ['success' => true, 'message' => 'Личные данные успешно обновлены.'];
        }

        return ['success' => false, 'message' => 'Ошибка: ' . current($user->getFirstErrors())];
    }

    /**
     * Login action (supports both standard form and AJAX from modal).
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ['success' => true, 'redirect' => \yii\helpers\Url::to(['site/account'])];
            }
            return $this->redirect(['site/account']);
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->login()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ['success' => true, 'redirect' => \yii\helpers\Url::to(['/site/account'])];
                }
                return $this->redirect(['site/account']);
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => current($model->getFirstErrors()) ?: 'Неверный логин или пароль.',
                    'errors' => $model->getErrors(),
                ];
            }
        }

        $signupModel = new SignupForm();
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            'signupModel' => $signupModel,
        ]);
    }

    /**
     * User registration action (supports both standard and AJAX).
     *
     * @return Response|string
     */
    public function actionSignup()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['site/account']);
        }

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->request->isAjax) {
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'message' => 'Регистрация прошла успешно! Добро пожаловать, ' . $user->full_name,
                        'redirect' => \yii\helpers\Url::to(['/site/account']),
                    ];
                }
                Yii::$app->session->setFlash('success', 'Регистрация прошла успешно! Добро пожаловать, ' . $user->full_name);
                return $this->redirect(['site/account']);
            } elseif (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'success' => false,
                    'message' => current($model->getFirstErrors()) ?: 'Ошибка валидации при регистрации.',
                    'errors' => $model->getErrors(),
                ];
            }
        }

        return $this->redirect(['site/login']);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Admin panel for managing bikes and site catalog in MySQL.
     *
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionAdmin()
    {
        $bikes = Bike::find()->with(['type', 'station'])->orderBy(['id' => SORT_DESC])->all();
        $categories = BikeType::find()->all();
        $stations = Station::find()->all();

        $stats = [
            'totalBikes' => count($bikes),
            'availableBikes' => Bike::find()->where(['status' => 'available'])->count(),
            'activeRentals' => Rental::find()->where(['status' => 'active'])->count(),
            'totalUsers' => User::find()->count(),
            'totalStations' => count($stations),
        ];

        return $this->render('admin', [
            'bikes' => $bikes,
            'categories' => $categories,
            'stations' => $stations,
            'stats' => $stats,
        ]);
    }

    /**
     * Creates a new bike in database.
     *
     * @return Response
     */
    public function actionBikeCreate()
    {
        $model = new Bike();
        if ($model->load(Yii::$app->request->post())) {
            if (empty($model->image)) {
                $model->image = 'images/voltride.jpg';
            }
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Велосипед «' . $model->name . '» успешно добавлен в базу данных!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка сохранения: ' . current($model->getFirstErrors()));
            }
        }

        return $this->redirect(['site/admin']);
    }

    /**
     * Updates an existing bike in database.
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionBikeUpdate($id)
    {
        $model = Bike::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Велосипед не найден.');
        }

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Велосипед «' . $model->name . '» успешно обновлен!');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка обновления: ' . current($model->getFirstErrors()));
            }
        }

        return $this->redirect(['site/admin']);
    }

    /**
     * Deletes a bike from database.
     *
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionBikeDelete($id)
    {
        $model = Bike::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Велосипед не найден.');
        }

        // Check if there are active rentals
        $hasActiveRentals = Rental::find()->where(['bike_id' => $id, 'status' => 'active'])->exists();
        if ($hasActiveRentals) {
            Yii::$app->session->setFlash('error', 'Невозможно удалить велосипед: он сейчас находится в активной аренде!');
            return $this->redirect(['site/admin']);
        }

        try {
            // Delete associated favorites first
            Yii::$app->db->createCommand()->delete('user_favorites', ['bike_id' => $id])->execute();
            // Delete non-active rentals history or delete bike
            Yii::$app->db->createCommand()->delete('rentals', ['bike_id' => $id])->execute();
            $name = $model->name;
            $model->delete();
            Yii::$app->session->setFlash('success', 'Велосипед «' . $name . '» успешно удален из базы данных.');
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Ошибка удаления: ' . $e->getMessage());
        }

        return $this->redirect(['site/admin']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
