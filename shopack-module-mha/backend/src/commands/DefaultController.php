<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

namespace iranhmusic\shopack\mha\backend\commands;

use Yii;
use yii\console\ExitCode;
use yii\console\Controller;
use shopack\aaa\common\enums\enuUserStatus;

/*

-- HEARTBEAT:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii mha/default/heartbeat 2>&1 >>logs/mha-heartbeat.log

-- check-accounts-expiration:
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii mha/default/check-accounts-expiration 2>&1 >>logs/mha-check-accounts-expiration.log

*/

class DefaultController extends Controller
{
  public function log($message, $type='INFO')
  {
		if (Yii::$app->isConsole == false)
			return;

    if ($message instanceof \Throwable) {
			$message = $message->getMessage();
      $type = 'ERROR';
    }

		if (empty($type))
    	echo "[" . date('Y/m/d H:i:s') . "] {$message}\n";
		else
    	echo "[" . date('Y/m/d H:i:s') . "][{$type}] {$message}\n";
  }

	public function actionHeartbeat()
	{
		return ExitCode::OK;
	}

	//call by cron every one hour
	public function actionCheckAccountsExpiration()
	{
		if (Yii::$app->mutex->isAcquired('CheckAccountsExpiration'))
			return;

		if (Yii::$app->mutex->acquire('CheckAccountsExpiration') == false)
			return;

    // $fnGetConst = function($value) { return $value; };
		$fnGetConstQouted = function($value) { return "'{$value}'"; };

		try {
			$qry =<<<SQL
      SELECT  tbl_AAA_User.*
           ,  tbl_MHA_Member.*
           ,  DATEDIFF(mbrExpireDate, NOW()) AS _diff
        FROM  tbl_MHA_Member
  INNER JOIN  tbl_AAA_User
          ON  tbl_AAA_User.usrID = tbl_MHA_Member.mbrUserID
       WHERE  usrStatus != {$fnGetConstQouted(enuUserStatus::Removed)}
         AND  usrMobile IS NOT NULL
         AND  usrMobileApprovedAt IS NOT NULL
         AND  mbrExpireDate IS NOT NULL
--         AND  mbrExpireDate < NOW()
         AND  DATEDIFF(mbrExpireDate, NOW()) BETWEEN -60 AND 60
SQL;
// AND  DATEDIFF(mbrExpireDate, NOW()) IN (-30,-7,-1,0,1,7,30)

			$rows = Yii::$app->db->createCommand($qry)->queryAll();

			/*
			$rows = [
				[ '_diff' => 14, 'mbrExpireDateLastAlertAtDayDiff' => null ],
				[ '_diff' => 14, 'mbrExpireDateLastAlertAtDayDiff' => 14 ],
				[ '_diff' => 13, 'mbrExpireDateLastAlertAtDayDiff' => 14 ],
				[ '_diff' => 12, 'mbrExpireDateLastAlertAtDayDiff' => 14 ],
				[ '_diff' => 11, 'mbrExpireDateLastAlertAtDayDiff' => 14 ],
				[ '_diff' => 10, 'mbrExpireDateLastAlertAtDayDiff' => 14 ],
				[ '_diff' => 10, 'mbrExpireDateLastAlertAtDayDiff' => 10 ],
				[ '_diff' =>  9, 'mbrExpireDateLastAlertAtDayDiff' => 10 ],
				[ '_diff' =>  8, 'mbrExpireDateLastAlertAtDayDiff' => 10 ],
				[ '_diff' =>  7, 'mbrExpireDateLastAlertAtDayDiff' => 10 ],
				[ '_diff' =>  6, 'mbrExpireDateLastAlertAtDayDiff' => 10 ],
				[ '_diff' =>  5, 'mbrExpireDateLastAlertAtDayDiff' => 10 ],
				[ '_diff' =>  5, 'mbrExpireDateLastAlertAtDayDiff' =>  5 ],
				[ '_diff' =>  4, 'mbrExpireDateLastAlertAtDayDiff' =>  5 ],
				[ '_diff' =>  3, 'mbrExpireDateLastAlertAtDayDiff' =>  5 ],
				[ '_diff' =>  2, 'mbrExpireDateLastAlertAtDayDiff' =>  5 ],
				[ '_diff' =>  1, 'mbrExpireDateLastAlertAtDayDiff' =>  5 ],
				[ '_diff' =>  1, 'mbrExpireDateLastAlertAtDayDiff' =>  1 ],

				[ '_diff' =>  0, 'mbrExpireDateLastAlertAtDayDiff' =>  1 ],
				[ '_diff' =>  0, 'mbrExpireDateLastAlertAtDayDiff' =>  0 ],

				[ '_diff' =>  -1, 'mbrExpireDateLastAlertAtDayDiff' =>   0 ],
				[ '_diff' =>  -1, 'mbrExpireDateLastAlertAtDayDiff' =>  -1 ],
				[ '_diff' =>  -2, 'mbrExpireDateLastAlertAtDayDiff' =>  -1 ],
				[ '_diff' =>  -3, 'mbrExpireDateLastAlertAtDayDiff' =>  -1 ],
				[ '_diff' =>  -4, 'mbrExpireDateLastAlertAtDayDiff' =>  -1 ],
				[ '_diff' =>  -5, 'mbrExpireDateLastAlertAtDayDiff' =>  -1 ],
				[ '_diff' =>  -5, 'mbrExpireDateLastAlertAtDayDiff' =>  -5 ],
				[ '_diff' =>  -6, 'mbrExpireDateLastAlertAtDayDiff' =>  -5 ],
				[ '_diff' =>  -7, 'mbrExpireDateLastAlertAtDayDiff' =>  -5 ],
				[ '_diff' =>  -8, 'mbrExpireDateLastAlertAtDayDiff' =>  -5 ],
				[ '_diff' =>  -9, 'mbrExpireDateLastAlertAtDayDiff' =>  -5 ],
				[ '_diff' => -10, 'mbrExpireDateLastAlertAtDayDiff' =>  -5 ],
				[ '_diff' => -10, 'mbrExpireDateLastAlertAtDayDiff' => -10 ],
				[ '_diff' => -11, 'mbrExpireDateLastAlertAtDayDiff' => -10 ],
				[ '_diff' => -12, 'mbrExpireDateLastAlertAtDayDiff' => -10 ],
				[ '_diff' => -13, 'mbrExpireDateLastAlertAtDayDiff' => -10 ],
				[ '_diff' => -14, 'mbrExpireDateLastAlertAtDayDiff' => -10 ],
				[ '_diff' => -14, 'mbrExpireDateLastAlertAtDayDiff' => null ],
			];
			*/

			if (empty($rows))
				return ExitCode::OK;

			// $this->log("rows count: (" . count($rows) . ")");

			$messageSteps_Expired = [
				-30 => 'msg_mha_alert_expired__30',
				-15 => 'msg_mha_alert_expired__15',
				 -7 => 'msg_mha_alert_expired__7',
				 -1 => 'msg_mha_alert_expired__1',
				// -1 => 'msg_mha_alert_expired__1',
				// -5 => 'msg_mha_alert_expired__5',
				// -10 => 'msg_mha_alert_expired__10',
				// -15 => 'msg_mha_alert_expired__15',
				// -20 => 'msg_mha_alert_expired__20',
			];
			$messageSteps_ExpiresToday = 'msg_mha_alert_expires_today';
			$messageSteps_ExpiresSoon = [
				30 => 'msg_mha_alert_expires_soon_30',
				15 => 'msg_mha_alert_expires_soon_15',
				 7 => 'msg_mha_alert_expires_soon_7',
				 1 => 'msg_mha_alert_expires_soon_1',
			];

			// ksort($messageSteps, SORT_NUMERIC);

			// $msgKey = $fnGetAlertMessage(0, null);	$this->log("\t\t\t\t\tmsg({$msgKey})");

			foreach ($rows as $row) {
				$_diff = $row['_diff'];
				$_alertedAt = $row['mbrExpireDateLastAlertAtDayDiff'] ?? null;

				if (($_alertedAt !== null) && ($_alertedAt == $_diff)) {
					$this->log("check: _diff({$_diff})\t_alertedAt({$_alertedAt})");
					continue;
				}

				$msgKey = null;

				//expires today
				if (($_diff == 0) && (($_alertedAt === null) || ($_alertedAt != 0))) {

					$msgKey = $messageSteps_ExpiresToday;

				} else if ($_diff > 0) { //expires soon

					foreach ($messageSteps_ExpiresSoon as $step => $msg) {
						if ($_diff > $step)
							continue; //skip largest abs() windows

						if (($_alertedAt !== null) && ($_alertedAt <= $step))
							continue;

						$msgKey = $msg;

						break;
					}

				} else if ($_diff < 0) { //expired

					foreach ($messageSteps_Expired as $step => $msg) {
						if ($_diff > $step)
							continue; //skip largest abs() windows

						if (($_alertedAt !== null) && ($_alertedAt <= $step))
							continue;

						$msgKey = $msg;

						break;
					}

				}

				// if ($msgKey !== null)
				// 	$this->log("alert: _diff({$_diff}) _alertedAt({$_alertedAt}) msg({$msgKey})");

				$this->log("check: _diff({$_diff})\t_alertedAt({$_alertedAt})\tmsg({$msgKey})");

				if ($msgKey === null)
					continue;

				//send alert and save _alertedAt


				//todo: (vi) complete this
			}

		} catch (\Throwable $e) {
			$this->log($e);
      Yii::error($e, __METHOD__);
		} finally {
			Yii::$app->mutex->release('CheckAccountsExpiration');
		}

		return ExitCode::OK;
	}

}
