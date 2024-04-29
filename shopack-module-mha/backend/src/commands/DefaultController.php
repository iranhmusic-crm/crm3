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
cd /home2/iranhmus/domains/api.iranhmusic.ir/public_html; /usr/local/php-8.1/bin/php yii mha/default/heartbeat 2>&1 >>logs/mha-heartbeat.log
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
	protected function actionCheckAccountsExpiration()
	{
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
         AND  DATEDIFF(mbrExpireDate, NOW()) IN (-30,-7,-1,0,1,7,30)
SQL;

			$rows = Yii::$app->db->createCommand($qry)->queryAll();
			if (empty($rows))
				return;

			foreach ($rows as $row) {

			}

		} catch (\Throwable $e) {
			$this->log($e);
      Yii::error($e, __METHOD__);
		}

		return ExitCode::OK;
	}

}
