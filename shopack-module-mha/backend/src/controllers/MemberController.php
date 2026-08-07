<?php

/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use yii\data\ActiveDataProvider;
use shopack\base\common\db\DbExpression;
use shopack\base\common\helpers\ExceptionHelper;
use shopack\base\backend\controller\BaseRestController;
use shopack\base\backend\helpers\PrivHelper;
use shopack\aaa\backend\models\UserModel;
use iranhmusic\shopack\mha\backend\models\MemberModel;
use iranhmusic\shopack\mha\backend\models\MemberSignupForm;
use iranhmusic\shopack\mha\common\enums\enuMemberKanoonStatus;

class MemberController extends BaseRestController
{
    public function actionOptions()
    {
        return 'options';
    }

    protected function findModel($id)
    {
        if (($model = MemberModel::findOne($id)) !== null)
            return $model;

        throw new NotFoundHttpException('The requested item does not exist.');
    }

    public function fillGlobalSearchFromRequest(\yii\db\ActiveQuery $query, $q)
    {
        if (empty($q) || ($q == '***'))
            return;

        $query->andWhere([
            'OR',
            ['LIKE', 'mbrRegisterCode', $q],
            ['LIKE', 'usrFirstName', $q],
            ['LIKE', 'usrFirstName_en', $q],
            ['LIKE', 'usrLastName', $q],
            ['LIKE', 'usrLastName_en', $q],
            ['LIKE', 'usrEmail', $q],
            ['LIKE', 'usrMobile', $q],
            ['LIKE', 'usrSSID', $q],
        ]);
    }

    public function actionIndex($q = null, $filter_mode = 0)
    {
        $filter = $this->checkPrivAndGetFilter('mha/member/crud', '0100', 'mbrUserID');

        $searchModel = new MemberModel;
        $query = MemberModel::find()
            // ->select(MemberModel::selectableColumns())
            ->addSelect(UserModel::selectableColumns())
            ->innerJoinWith('user')
            ->joinWith('user.imageFile')
            ->with('createdByUser')
            ->with('updatedByUser')
            ->with('removedByUser');

        $this->fillGlobalSearchFromRequest($query, $q);

        $searchModel->fillQueryFromRequest($query);

        if (empty($filter) == false)
            $query->andWhere($filter);

        //--------------------------------------------
        $fnGetConst = function ($value) {
            return $value;
        };
        $fnGetConstQouted = function ($value) {
            return "'{$value}'";
        };

        // switch ($searchModel->filter_mode) {
        switch ($filter_mode) {
            case MemberModel::FILTER_MODE_HAS_REG_CODE:
                $query
                    ->andWhere(['>', 'mbrRegisterCode', 0]);
                break;

            case MemberModel::FILTER_MODE_WAIT_FOR_SEND_TO_KANOON:
                $query->leftJoin(
                    "(
            SELECT  mbrknnMemberID
                 ,  COUNT(*) AS _cnt
              FROM  tbl_MHA_Member_Kanoon
             WHERE  mbrknnStatus = {$fnGetConstQouted(enuMemberKanoonStatus::WaitForSend)}
          GROUP BY  mbrknnMemberID
                    )  AS tmp_mbrknn_wait",
                    "tmp_mbrknn_wait.mbrknnMemberID = tbl_MHA_Member.mbrUserID"
                )
                    ->andWhere(['>', 'tmp_mbrknn_wait._cnt', 0])
                ;
                break;

            case MemberModel::FILTER_MODE_WAIT_FOR_KANOON_APPROVAL:
                $query->leftJoin(
                    "(
            SELECT  mbrknnMemberID
                 ,  COUNT(*) AS _cnt
              FROM  tbl_MHA_Member_Kanoon
             WHERE  mbrknnStatus IN ({$fnGetConstQouted(enuMemberKanoonStatus::WaitForSurvey)},
                                     {$fnGetConstQouted(enuMemberKanoonStatus::WaitForResurvey)},
                                     {$fnGetConstQouted(enuMemberKanoonStatus::Azmoon)},
                                     {$fnGetConstQouted(enuMemberKanoonStatus::WaitForDocuments)}
                                    )
          GROUP BY  mbrknnMemberID
                    )  AS tmp_mbrknn_wait",
                    "tmp_mbrknn_wait.mbrknnMemberID = tbl_MHA_Member.mbrUserID"
                )
                    ->andWhere(['>', 'tmp_mbrknn_wait._cnt', 0])
                ;
                break;

            case MemberModel::FILTER_MODE_ONLINE_REG_REQ:
                $query
                    ->andWhere(['is', 'mbrRegisterCode', DbExpression::null()]);
                break;

                // case MemberModel::FILTER_MODE_WAIT_FOR_BASE_APPROVAL:
                //     $query
                //         ->andWhere('IFNULL(shpobjPrice, 0) > 0')
                //     ;
                //     break;
        }

        if ($filter_mode == MemberModel::FILTER_MODE_DEAD)
            $query
                ->andWhere(['is not', 'usrDeadAt', DbExpression::null()]);
        else if ($filter_mode != MemberModel::FILTER_MODE_ALL)
            $query
                ->andWhere(['is', 'usrDeadAt', DbExpression::null()]);

        //--------------------------------------------
        return $this->queryAllToResponse($query);
    }

    public function actionView($id)
    {
        if (PrivHelper::hasPriv('mha/member/crud', '0100') == false) {
            if (Yii::$app->user->id != $id)
                throw new ForbiddenHttpException('access denied');
        }

        $query = MemberModel::find()
            // ->select(MemberModel::selectableColumns())
            ->joinWith('user')
            ->joinWith('user.country')
            ->joinWith('user.state')
            ->joinWith('user.cityOrVillage')
            ->joinWith('user.town')
            ->joinWith('user.birthCityOrVillage')
            ->joinWith('user.role')
            ->joinWith('user.imageFile')
            ->joinWith('instrument')
            ->joinWith('sing')
            ->joinWith('research')
            ->with('createdByUser')
            ->with('updatedByUser')
            ->with('removedByUser')
            ->where(['mbrUserID' => $id]);

        return $this->queryOneToResponse($query);
    }

    public function actionCreate()
    {
        PrivHelper::checkPriv(['mha/member/crud' => '1000']);

        $model = new MemberModel();
        if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
            throw new NotFoundHttpException("parameters not provided");

        try {
            if ($model->save() == false)
                throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));
        } catch (\Exception $exp) {
            $msg = ExceptionHelper::CheckDuplicate($exp, $model);
            throw new UnprocessableEntityHttpException($msg);
        }

        return [
            // 'result' => [
            // 'message' => 'created',
            'mbrUserID' => $model->mbrUserID,
            'mbrStatus' => $model->mbrStatus,
            'mbrCreatedAt' => $model->mbrCreatedAt,
            'mbrCreatedBy' => $model->mbrCreatedBy,
            // ],
        ];
    }

    public function actionUpdate($id)
    {
        if (PrivHelper::hasPriv('mha/member/crud', '0010') == false) {
            if (Yii::$app->user->id != $id)
                throw new ForbiddenHttpException('access denied');
        }

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
            throw new NotFoundHttpException("parameters not provided");

        if ($model->save() == false)
            throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

        return [
            // 'result' => [
            // 'message' => 'updated',
            'mbrUserID' => $model->mbrUserID,
            'mbrStatus' => $model->mbrStatus,
            'mbrUpdatedAt' => $model->mbrUpdatedAt,
            'mbrUpdatedBy' => $model->mbrUpdatedBy,
            // ],
        ];
    }

    public function actionDelete($id)
    {
        if (PrivHelper::hasPriv('mha/member/crud', '0001') == false) {
            if (Yii::$app->user->id != $id)
                throw new ForbiddenHttpException('access denied');
        }

        $model = $this->findModel($id);

        if ($model->delete() === false)
            throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

        return [
            // 'result' => [
            // 'message' => 'deleted',
            'mbrUserID' => $model->mbrUserID,
            'mbrStatus' => $model->mbrStatus,
            'mbrRemovedAt' => $model->mbrRemovedAt,
            'mbrRemovedBy' => $model->mbrRemovedBy,
            // ],
        ];
    }

    public function actionSignup()
    {
        $model = new MemberSignupForm;

        if ($model->load(Yii::$app->request->getBodyParams(), '') == false)
            throw new NotFoundHttpException("parameters not provided");

        if ($model->mbrUserID != Yii::$app->user->id)
            PrivHelper::checkPriv(['mha/member/crud' => '1000']);

        try {
            $result = $model->signup();

            if ($result == false)
                throw new UnprocessableEntityHttpException(implode("\n", $model->getFirstErrors()));

            return $result;
        } catch (\Exception $exp) {
            $msg = ExceptionHelper::CheckDuplicate($exp, $model);
            throw new UnprocessableEntityHttpException($msg);
        }
    }
}
