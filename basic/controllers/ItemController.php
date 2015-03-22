<?php

namespace app\controllers;

use app\models\tables\ItemImage;
use Yii;
use app\models\tables\Item;
use app\models\tables\ItemSearch;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use yii\helpers\Json;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $userIdentity = Yii::$app->user->identity;

        $model = new Item([
            'created' => date('Y-m-d H:i:s'),
            'updated' => date('Y-m-d H:i:s'),
            'user_id' => $userIdentity->id,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['update', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->updated = date('Y-m-d H:i:s');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * @return bool
     */
    public function actionFileUpload()
    {
        $request = Yii::$app->request;

        if (!$request->isAjax) {
            return Json::encode(false);
        }

        $userId = Yii::$app->request->post('user_id');
        $itemId = Yii::$app->request->post('item_id');

        $model = Item::find()->andWhere(['user_id' => $userId, 'id' => $itemId])->one();

        if ($model) {
            $model->images = UploadedFile::getInstance($model, 'images');
            if (!$model->images) {
                return Json::encode(false);
            }
            if ($model->validate()) {
                $src = $model->images->baseName . '.' . $model->images->extension;
                $imageModel = new ItemImage([
                    'item_id' => $itemId,
                    'src' => $src
                ]);
                $dirName = Yii::$app->params['item.images'] . DIRECTORY_SEPARATOR
                    . $userId . DIRECTORY_SEPARATOR . $itemId;

                if (!is_dir($dirName)) {
                    mkdir($dirName, 0777, true);
                }
                if ($model->images->saveAs($dirName . DIRECTORY_SEPARATOR . $src)) {
                    $imageModel->save();
                }
            }
        }

        return Json::encode(true);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist . ');
        }
    }
}
