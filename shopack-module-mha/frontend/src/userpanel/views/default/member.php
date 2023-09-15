<?php
/**
 * @author Kambiz Zandi <kambizzandi@gmail.com>
 */

/** @var yii\web\View $this */

use shopack\base\frontend\common\helpers\Html;
use shopack\base\frontend\common\widgets\DetailView;
use shopack\aaa\common\enums\enuGender;
use shopack\aaa\common\enums\enuUserStatus;
use iranhmusic\shopack\mha\common\enums\enuMemberStatus;
use iranhmusic\shopack\mha\frontend\common\models\DocumentSearchModel;
use iranhmusic\shopack\mha\frontend\common\models\MemberSpecialtyModel;

$this->title = Yii::t('mha', 'My Profile');
$this->params['breadcrumbs'][] = $this->title;

$defects = $model->getDefects();

?>

<div class="profile-view w-100">
	<?php
		if (empty($defects) == false) {
			echo "<div class='text-center'>";
			echo "<h3 class='text-danger'>";
			echo "عضو محترم، لطفا نسبت به رفع نواقص پرونده خود اقدام کنید.";
			echo "</h3>";
			echo "</div>";
		}
	?>

	<div class='card border-0'>

		<div class='card-body'>
			<div class='row'>
				<div class='col-sm-9'>

					<div class='card border-default'>
						<div class='card-header bg-default'>
							<div class="float-end">
								<?= Html::updateButton('ویرایش اطلاعات پایه', ['/aaa/profile/update-user'], [
									'data' => [
										'popup-size' => 'lg',
									],
								]) ?>
								<?= $model->canUpdate() ? Html::updateButton('ویرایش اطلاعات تکمیلی', ['member/update'], [
									'data' => [
										'popup-size' => 'lg',
									],
								]) : '' ?>
							</div>
							<div class='card-title'><?= Yii::t('app', 'My Information') ?></div>
							<div class="clearfix"></div>
						</div>
						<div class='card-body'>
							<?php
								echo DetailView::widget([
									'model' => $model,
									'enableEditMode' => false,
									'cols' => 2,
									'isVertical' => false,
									'attributes' => [
										// [
										// 	'attribute' => 'mbrUserID',
										// 	'label' => 'کد کاربری',
										// ],
										// [
										// 	'attribute' => 'usrStatus',
										// 	'value' => enuUserStatus::getLabel($model->user->usrStatus),
										// ],
										'mbrRegisterCode',
										'mbrAcceptedAt:jalaliWithTime',
										[
											'attribute' => 'mbrStatus',
											'value' => enuMemberStatus::getLabel($model->mbrStatus),
										],
										'mbrExpireDate:jalali',
										[
											'group' => true,
											// 'cols' => 1,
											'label' => 'اطلاعات پایه',
											'groupOptions' => ['class' => 'info-row'],
										],
										[
											'attribute' => 'usrFirstName',
											'value' => $model->user->usrFirstName,
										],
										[
											'attribute' => 'usrFirstName_en',
											'value' => $model->user->usrFirstName_en,
										],
										[
											'attribute' => 'usrLastName',
											'value' => $model->user->usrLastName,
										],
										[
											'attribute' => 'usrLastName_en',
											'value' => $model->user->usrLastName_en,
										],
										[
											'attribute' => 'usrFatherName',
											'value' => $model->user->usrFatherName,
										],
										[
											'attribute' => 'usrFatherName_en',
											'value' => $model->user->usrFatherName_en,
										],
										[
											'attribute' => 'usrEmail',
											'valueColOptions' => ['class' => ['dir-ltr', 'text-start']],
											'value' => $model->user->usrEmail,
										],
										[
											'attribute' => 'usrEmailApprovedAt',
											'format' => 'jalaliWithTime',
											'value' => $model->user->usrEmailApprovedAt,
										],
										[
											'attribute' => 'usrMobile',
											'format' => 'phone',
											'value' => $model->user->usrMobile,
										],
										[
											'attribute' => 'usrMobileApprovedAt',
											'format' => 'jalaliWithTime',
											'value' => $model->user->usrMobileApprovedAt,
										],
										[
											'attribute' => 'usrSSID',
											'value' => $model->user->usrSSID,
										],
										[
											'attribute' => 'usrGender',
											'value' => enuGender::getLabel($model->usrGender),
										],

										[
											'group' => true,
											'cols' => 1,
											'label' => 'اطلاعات تکمیلی',
											'groupOptions' => ['class' => 'info-row'],
										],
                    'mbrMusicExperiences:paragraphs',
                    'mbrMusicExperienceStartAt:jalali',

                    [
                      'attribute' => 'mbrInstrumentID',
                      'value' => $model->instrument->bdfName ?? null,
                    ],
                    [
                      'attribute' => 'mbrSingID',
                      'value' => $model->sing->bdfName ?? null,
                    ],
                    [
                      'attribute' => 'mbrResearchID',
                      'value' => $model->research->bdfName ?? null,
                    ],
                    [
                      'attribute' => 'mbrArtDegree',
                      'value' => empty($model->mbrArtDegree) ? null
                                  : 'درجه ' . $model->mbrArtDegree,
                    ],
                    'mbrHonarCreditCode',
                    'mbrJob',
                    'mbrOwnOrgName',

										'mbrArtHistory:paragraphs',
                    'mbrMusicEducationHistory:paragraphs',

									],
								]);
							?>
						</div>
					</div>
				</div>

				<div class='col-sm-3'>
					<?php
						if (empty($defects) == false) {
					?>
					<div class='card border-default mb-3'>
						<div class='card-header bg-default'>
							<div class="float-end">
								<div class='badge bg-danger'><?= count($defects) ?></div>
							</div>
							<div class='card-title'><?= Yii::t('app', 'نواقص پرونده') ?></div>
							<div class="clearfix"></div>
						</div>
						<div class='card-body'>
							<?php
								echo '<ol>';
								foreach ($defects as $lbl => $val) {
									echo '<li>';
									if (isset($val['url'])) {
										echo Html::a('<b>' . $val['label'] . '</b>', $val['url']);
									} else {
										echo '<b>' . $val['label'] . '</b>';
									}
									echo ': ';
									if (is_array($val['desc'])) {
										echo '<ul><li>' . implode('</li><li>', $val['desc']) . '</li></ul>';
									} else {
										echo $val['desc'];
									}
									echo '</li>';
								}
								echo '</ol>';
							?>
						</div>
					</div>
					<?php
						}
					?>

					<div class='card border-default'>
						<div class='card-header bg-default'>
							<div class="float-end">
								<?= Html::updateButton(Yii::t('aaa', 'Update Image'), ['/aaa/profile/update-image'], [
									// 'modal' => false,
								]) ?>
							</div>
							<div class='card-title'><?= Yii::t('aaa', 'Image') ?></div>
							<div class="clearfix"></div>
						</div>
						<div class='card-body text-center'>
							<?php
								if ($model->user->usrImageFileID == null)
									echo Yii::t('app', 'Not defined');
								elseif (empty($model->user->imageFile->fullFileUrl))
									echo Yii::t('aaa', 'Uploading...');
								elseif ($model->user->imageFile->isImage())
									echo Html::img($model->user->imageFile->fullFileUrl, ['style' => ['width' => '100%']]);
								else
									echo Html::a(Yii::t('app', 'Download'), $model->imageFile->fullFileUrl);
							?>
						</div>
					</div>

				</div>
			</div>
    </div>
	</div>
</div>
