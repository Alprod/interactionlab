<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

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
		$allFeedsSend = $allFeed->findBy(
			[ $this->getTableField() => $userCurrent ],
			['createdAt' => 'DESC']);

		foreach ($allFeedsSend as $k => $feedback){
			$dateReceived  = $feedback->getCreatedAt();
			$dateStart = new \DateTime($today->format('Y-m-d'));
			$dateEnd =new \DateTime($dateReceived->format('Y-m-d'));
			$diffDate = $dateStart->diff($dateEnd);

			switch ($diffDate):
				case $diffDate->y >= 1:
					$gapFeed = 'Il y a '. $diffDate->format('%y'. ($diffDate->y === 1) ? ' an':' ans');
					break;
				case $diffDate->days === 0:
					$gapFeed = "Aujourd'hui";
					break;
				case $diffDate->days === 1:
					$gapFeed = 'Hier';
					break;
				case $diffDate->days >= 2 && $diffDate->days < 14 :
					$gapFeed = 'Il y a ' . $diffDate->format('%d jours');
					break;
				case $diffDate->days >= 14 && $diffDate->days < 30 :
					$gap = round($diffDate->d/7,0);
					$gapFeed = 'Il y a ' . $gap . ' semaines.';
					break;
				case $diffDate->m > 0:
					$gapFeed = 'Il y a '. $diffDate->format('%m mois');
					break;
			endswitch;

			$datas[$k] = [
				'issueId' => $feedback->getIssue()->getId(),
				'receivedId' => $feedback->getReceived()->getId(),
				'username' => $feedback->getReceived()->fullName(),
				'sender' => $feedback->getIssue()->fullName(),
				'grade' => $feedback->getGrade(),
				'gapFeed' => $gapFeed,
				'createdAt' => $feedback->getCreatedAt(),
				'comment' => $feedback->getComment(),
			];

		}
		return $datas;
	}

	/**
	 * @throws \Exception
	 */
	public function allFeedbacksSendOrReceivedUserCurrent(
		UserInterface $userCurrent,
		FeedbackRepository $feedbackRepo,
		\DateTime $today,
		string $tableField): array
	{
		$this->setTableField($tableField);
		return $this->checkDateGapFeedbacks( $feedbackRepo, $userCurrent, $today );
	}
}