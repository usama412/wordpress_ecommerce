<?php


namespace Vehica\Chat;


use JsonSerializable;

/**
 * Class Message
 * @package Vehica\Chat
 */
class Message implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $userFrom;

    /**
     * @var int
     */
    private $userTo;

    /**
     * @var string
     */
    private $message;

    /**
     * @var int
     */
    private $seen;

    /**
     * @var string
     */
    private $createdAt;

    /**
     * @var bool
     */
    private $showDate;

    /**
     * Message constructor.
     * @param int $id
     * @param int $userFrom
     * @param int $userTo
     * @param string $message
     * @param int $seen
     * @param string $createdAt
     */
    public function __construct($id, $userFrom, $userTo, $message, $seen, $createdAt)
    {
        $this->id = (int)$id;
        $this->userFrom = (int)$userFrom;
        $this->userTo = (int)$userTo;
        $this->message = $message;
        $this->seen = $seen;
        $this->createdAt = $createdAt;
    }

    /**
     * @param bool $show
     */
    public function setShowDate($show)
    {
        $this->showDate = $show;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function seen()
    {
        return !empty($this->seen);
    }

    /**
     * @return int
     */
    public function getUserFromId()
    {
        return $this->userFrom;
    }

    /**
     * @return int
     */
    public function getUserToId()
    {
        return $this->userTo;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return stripslashes_deep($this->message);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param array $data
     * @return Message
     */
    public static function make(array $data)
    {
        return new self(
            $data['id'],
            $data['user_from'],
            $data['user_to'],
            $data['message'],
            $data['seen'],
            $data['created_at']
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'userFromId' => $this->getUserFromId(),
            'userToId' => $this->getUserToId(),
            'message' => $this->getMessage(),
            'seen' => $this->seen(),
            'createdAt' => $this->getCreatedAt(),
            'formattedDate' => $this->getFormattedDate(),
            'fullDate' => $this->getFullDate(),
            'intro' => $this->getIntro(),
            'showDate' => $this->showDate,
        ];
    }

    /**
     * @return string
     */
    private function getIntro()
    {
        $message = strip_tags($this->getMessage());

        if (mb_strlen($this->message) > 15) {
            return mb_substr($message, 0, 15) . '...';
        }

        return $message;
    }

    /**
     * @return string
     */
    public function getFormattedDate()
    {
        $timestamp = strtotime($this->getCreatedAt());

        if (date('Y-m-d') === date('Y-m-d', $timestamp)) {
            return get_date_from_gmt($this->getCreatedAt(), get_option('time_format'));
        }

        return get_date_from_gmt($this->getCreatedAt(), get_option('date_format'))
            . ' - ' . get_date_from_gmt($this->getCreatedAt(), get_option('time_format'));
    }

    /**
     * @return string
     */
    public function getFullDate()
    {
        return get_date_from_gmt($this->getCreatedAt(), get_option('date_format'))
            . ' ' . get_date_from_gmt($this->getCreatedAt(), get_option('time_format'));
    }

}