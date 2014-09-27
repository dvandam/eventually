<?php

namespace Event;


class JobPosted {
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

    /**
     * @param string $id
     * @param $title
     * @param string $startDate
     * @param string $endDate
     */
    function __construct($id, $title, $startDate, $endDate)
    {
        $this->endDate = $endDate;
        $this->id = $id;
        $this->startDate = $startDate;
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
} 