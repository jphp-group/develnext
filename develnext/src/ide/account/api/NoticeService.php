<?php
namespace ide\account\api;

/**
 * Class NoticeService
 * @package ide\account\api
 */
class NoticeService extends AbstractService
{
    /**
     * @var string
     */
    protected $lastUpdatedAt;

    /**
     * @return string
     */
    public function getLastUpdatedAt()
    {
        return $this->lastUpdatedAt;
    }

    /**
     * @param string $lastUpdatedAt
     */
    public function setLastUpdatedAt($lastUpdatedAt)
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }
}