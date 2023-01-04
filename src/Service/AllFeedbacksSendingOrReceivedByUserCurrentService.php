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


	/**
	 * @throws \Exception
	 */
	public function checkDateGapFeedbacks (FeedbackRepository $allFeed, $userCurrent ,$today): array
	{
		$datas =[];
		$gapFeed = '';
		$allFeedsSend = $allFeed->findBy([ $this->getTableField() => $userCurrent ], ['createdAt' => 'DESC']);

		foreach ($allFeedsSend as $k => $feedback){
			$dateReceived  = $feedback->getCreatedAt();
			$dateStart = new \DateTime($today->format('Y-m-d'));
			$dateEnd =new \DateTime($dateReceived->format('Y-m-d'));
			$diffDate = $dateStart->diff($dateEnd);
			

			switch ($diffDate):
				case $diffDate->y >= 1:
					$gapFeed = 'Il y a '. $diffDate->format('%y'. ($diffDate->y === 1) ? ' an':' ans');
					break;
				case $diffDate->d === 0:
					$gapFeed = "Aujourd'hui";
					break;
				case $diffDate->d === 1:
					$gapFeed = 'Hier';
					break;
				case $diffDate->d >= 2 && $diffDate->d < 14 :
					$gapFeed = 'Il y a ' . $diffDate->format('%d jours');
					break;
				case $diffDate->d >= 14 && $diffDate->d < 30 :
					$gap = round($diffDate->d/7,0);
					$gapFeed = 'Il y a ' . $gap . ' semaines.';
					break;
				case $diffDate->m > 0:
					$gapFeed = 'Il y a '. $diffDate->format('%m mois');
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