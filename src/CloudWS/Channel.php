<?php

namespace CloudWS;

class Channel {
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var integer
     */
    public $totalMessages;

    /**
     * @var integer
     */
    public $lastActionTimestamp;
}
