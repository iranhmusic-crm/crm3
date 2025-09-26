<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

use shopack\base\common\db\Migration;
use iranhmusic\shopack\mha\backend\models\ReportModel;

/*
{"mbrExpireDate": {"From": "2025/8/26"}, "mbrAcceptedAt_Has": ["1"], "mbrRegisterCode_Has": ["0"]}
->
{"mbrExpireDate": {"From": "2025/8/26"}, "Has": {"mbrAcceptedAt": ["1"], "mbrRegisterCode": ["0"]}}

{"mbrknnKanoonID": ["9"], "mbrknnMembershipDegree_Has": ["0"]}
*/

class m250926_113942_mha_report_merge_has_to_new_array extends Migration
{
	public function safeUp()
	{
		$models = ReportModel::find()->all();
		if (empty($models) == false) {
			foreach ($models as $model) {
				if (empty($model->rptInputFields))
					continue;

				$rptInputFields = $model->rptInputFields;

				if (isset($rptInputFields['Has']) == false)
					$rptInputFields['Has'] = [];

				$changed = false;
				foreach ($rptInputFields as $key => $value) {
					if (str_ends_with($key, '_Has')) {
						$kk = substr($key, 0, -4); //strip _Has fron end

						if ($kk == "mbrknnKanoonID")
							$rptInputFields['Has']["mbrknn"]["KanoonID"] = $value;
						else if ($kk == "mbrknnMembershipDegree")
							$rptInputFields['Has']["mbrknn"]["MembershipDegree"] = $value;
						else
							$rptInputFields['Has'][$kk] = $value;

						unset($rptInputFields[$key]);
						$changed = true;

					} else if ($key == "mbrknnKanoonID") {
						$rptInputFields["mbrknn"]["KanoonID"] = $value;

						unset($rptInputFields[$key]);
						$changed = true;

					} else if ($key == "mbrknnMembershipDegree") {
						$rptInputFields["mbrknn"]["MembershipDegree"] = $value;

						unset($rptInputFields[$key]);
						$changed = true;
					}
				}

				if ($changed) {
					// echo "changed***********************\n";
					// var_export([
					// 	'old' => $model->rptInputFields,
					// 	'new' => $rptInputFields,
					// ]);

					$model->rptInputFields = $rptInputFields;
					$model->save();
				}
			}
		}
	}

	public function safeDown()
	{
		echo "m250926_113942_mha_report_merge_has_to_new_array cannot be reverted.\n";
		return false;
	}
}
