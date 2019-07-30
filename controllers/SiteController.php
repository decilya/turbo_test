<?php

namespace app\controllers;

use app\models\News;
use app\models\UsersTest;
use app\models\UsersTurbo;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
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
     * /site/first-task
     *
     * @throws \yii\db\Exception
     */
    public function actionFirstTask()
    {
        // Напишите запросы для выборки и обновления контента.
        // Вариант 1:
        $connection = Yii::$app->getDb();

        $firstRecord = News::find()->where(['title' => "Первый пост за локалхост"])->asArray()->one();

        // добавим запись, если ее нет
        if (empty($firstRecord)) {
            $newsNew = $connection->createCommand("
                INSERT INTO `news`  (`title`, `user_id`,  `content`, `created_at`)
                VALUES ('Первый пост за локалхост', 1, 'Повседневная практика показывает, что рамки и место обучения кадров в значительной степени обуславливает создание системы обучения кадров, соответствует насущным потребностям. С другой стороны реализация намеченных плановых заданий обеспечивает широкому кругу (специалистов) участие в формировании дальнейших направлений развития. Таким образом постоянный количественный рост и сфера нашей активности в значительной степени обуславливает создание направлений прогрессивного развития.', " . time() . ")
                
            ")->execute();
        }

        $content = $connection->createCommand("
            SELECT `content` FROM `news` 
            WHERE  `user_id` = 1
        ")->queryAll();

        echo "<pre>";
        print_r($content);
        echo "</pre>";

        // В реальной системе вряд ли будут поиски по тайтлу, по этой причине в индексы я его не добавил
        $updateContent = $connection->createCommand("
            UPDATE `news`
            SET `content`= 'Идейные соображения высшего порядка, а также консультация с широким активом играет важную роль в формировании дальнейших направлений развития. Идейные соображения высшего порядка, а также сложившаяся структура организации обеспечивает широкому кругу (специалистов) участие в формировании форм развития. Равным образом укрепление и развитие структуры требуют от нас анализа систем массового участия.',
            `updated_at` = " . time() . "
            WHERE `title`='Первый пост за локалхост'")->execute();

        $content = $connection->createCommand("
            SELECT `content` FROM `news` 
            WHERE  `user_id` = 1
        ")->queryAll();

        echo "<pre>";
        print_r($content);
        echo "</pre >";

        // Вариант 2: не через запросы mySql, а через ActiveRecord yii2
        $record = News::findOne(['title' => 'Еще одна запись']);
        if (empty($record)) {
            $record = new News();
            $record->title = 'Еще одна запись';
            $record->content = 'Задача организации, в особенности же постоянное информационно-пропагандистское обеспечение нашей деятельности позволяет оценить значение форм развития.';
            $record->user_id = isset(Yii::$app->user->identity->id) ? Yii::$app->user->identity->id : 0;

            if (!$record->save()) {
                echo "<pre>";
                print_r($record->errors);
                echo "</pre>";
            }
        }

        echo "<pre>";
        print_r($record);
        echo "</pre>";

        $news = News::findOne(['title' => 'Еще одна запись']);

        $news->title = 'Изменим заголовок';
        $news->content = 'Как уже неоднократно упомянуто, явные признаки победы институционализации могут быть своевременно верифицированы. Сложно сказать, почему акционеры крупнейших компаний в равной степени предоставлены сами себе. Приятно, граждане, наблюдать, как некоторые особенности внутренней политики ассоциативно-> распределены по отраслям.';
        $news->save();

        echo "<pre>";
        print_r($news);
        echo "</pre>";

    }

    /**
     * site/second-task
     */
    public function actionSecondTask()
    {
        /*
         * В таблице более 100 млн записей, и она находится под нагрузкой в production (идут запросы на
        добавление / изменение / удаление).
        В поле email может быть от одного до нескольких перечисленных через запятую адресов. Может
        быть пусто.
        Напишите скрипт, который выведет список представленных в таблице почтовых доменов с
        количеством пользователей по каждому домену.
         */
        $usersTurbo = UsersTurbo::find()->all();

        $domains = [];
        $tmpDomains = [];
        /** @var UsersTurbo $user */
        foreach ($usersTurbo as $user) {

            foreach ($user->domains as $domain => $value) {
                if (!in_array($domain, $tmpDomains)) {
                    $tmpDomains[] = $domain;
                    $domains[$domain] = $value;
                } else {
                    $domains[$domain] = $domains[$domain] + $value;
                }
            }
        }

        // Конечно для 100 млн записей под нагрузкой это так себе решение...... но ничего лучше в голову не приходит
        echo "<pre>";
        print_r($domains);
        echo "</pre>";
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
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

}
