<?php

namespace App\Service;

use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AllFeedbacksSendingOrReceivedByUserCurrentService
{
	private string $tableField;

	/**
	 * @return string
	 */
	public function getTableField(): string
	{
		return $this->tableField;
	}

	/**
	 * @param mixed  $tableField
	 */
	public function setTableField( mixed $tableField ): void
	{
		$this->tableField = $tableField;
	}


	public function checkDateGapFeedbacks (FeedbackRepository $allFeed, $userCurrent ,$today): array
	{
		$datas =[];
		$gapFeed = '';
		$allFeedsSend = $allFeed->findBy([ $this->getTableField() => $userCurrent ], ['createdAt' => 'DESC']);

		foreach ($allFeedsSend as $k => $feedback){
			$dateReceived  = $feedback->getCreatedAt();
			$diffDate = $dateReceived->diff($today, true);

			switch ($diffDate):
				case $diffDate->y >= 1:
					$gap = $diffDate->y;
					$gapFeed = 'Il y a '. $gap . (($diffDate->y === 1) ? ' an':' ans');
					break;
				case $diffDate->days === 0:
					$gapFeed = "haujourd'hui";
					break;
				case $diffDate->days === 1:
					$gapFeed = 'Hier';
					break;
				case $diffDate->days >= 2 && $diffDate->days < 14 :
					$gapFeed = 'Il y a ' . $diffDate->days . ' jours.';
					break;
				case $diffDate->days >= 14 && $diffDate->days < 30 :
					$gap = round($diffDate->days/7,0);
					$gapFeed = 'Il y a ' . $gap . ' semaines.';
					break;
				case $diffDate->days >= 30:
					$gap = round($diffDate->days/30, 0);
					$gapFeed = 'Il y a '. $gap . ' mois';
					break;
			endswitch;

			$datas[$k] = [
				'username' => $feedback->getReceived()->fullName(),
				'sender' => $feedback->getIssue()->fullName(),
				'grade' => round(100/5, 2) * $feedback->getGrade(),
				'gapFeed' => $gapFeed
			];

		}
		return $datas;
	}
}