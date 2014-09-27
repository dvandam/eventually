<?php

namespace Aggregate;

class Job implements \Aggregate
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $startDate;

    /**
     * @var string
     */
    private $endDate;

    public function __construct()
    {
        $this->id = null;
        $this->title = null;
        $this->startDate = null;
        $this->endDate = null;
    }

    /**
     * @param \Command $command
     * @return \Event[]
     * @throws \Exception\CommandNotSupported
     */
    public function handle(\Command $command)
    {
        switch(true)
        {
            case $command instanceof \Command\PostJob:
                return $this->handlePostJob($command);
                break;
            case $command instanceof \Command\EditJob:
                return $this->handleEditJob($command);
                break;
            default:
                throw new \Exception\CommandNotSupported();
        }
    }

    /**
     * @param \Command\PostJob $postJob
     * @return \Event[]
     * @throws \Exception\JobAlreadyPosted
     * @throws \Exception\JobDurationTooLong
     */
    private function handlePostJob(\Command\PostJob $postJob)
    {
        if (!is_null($this->id)) {
            throw new \Exception\JobAlreadyPosted();
        }

        $startDate = \DateTime::createFromFormat('Y-m-d', $postJob->getStartDate());
        $endDate = \DateTime::createFromFormat('Y-m-d', $postJob->getEndDate());
        $diff = $endDate->diff($startDate);
        if ($diff->format('d') > 60) {
            throw new \Exception\JobDurationTooLong();
        }

        return [new \Event\JobPosted(
            $postJob->getId(), $postJob->getTitle(), $postJob->getStartDate(), $postJob->getEndDate()
        )];
    }

    /**
     * @param \Command\EditJob $editJob
     * @return \Event[]
     * @throws \Exception\JobNotPosted
     * @throws \Exception\JobDurationTooLong
     */
    private function handleEditJob(\Command\EditJob $editJob)
    {
        if (is_null($this->id)) {
            throw new \Exception\JobNotPosted();
        }

        $startDate = \DateTime::createFromFormat('Y-m-d', $editJob->getStartDate());
        $endDate = \DateTime::createFromFormat('Y-m-d', $editJob->getEndDate());
        $diff = $endDate->diff($startDate);
        if ($diff->format('d') > 60) {
            throw new \Exception\JobDurationTooLong();
        }

        return [new \Event\JobPosted(
            $editJob->getId(), $editJob->getTitle(), $editJob->getStartDate(), $editJob->getEndDate()
        )];
    }

    /**
     * @param \Event $event
     * @throws \Exception\CommandNotSupported
     */
    public function apply(\Event $event)
    {
        switch(true)
        {
            case $event instanceof \Event\JobPosted:
                $this->setInitialValues($event);
                break;
            default:
                throw new \Exception\CommandNotSupported();
        }
    }

    /**
     * @param \Event\JobPosted $event
     */
    private function setInitialValues(\Event\JobPosted $event)
    {
        $this->id = $event->getId();
        $this->title = $event->getTitle();
        $this->startDate = $event->getStartDate();
        $this->endDate = $event->getEndDate();
    }
}