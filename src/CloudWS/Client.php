<?php

namespace CloudWS;


class Client
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $cloudWSUrl = 'https://cloudws.io';

    /**
     * CloudWSClient constructor.
     * @param string $token;
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * @param resource $ch
     * @return mixed
     * @throws CloudWSException
     */
    private function makeRequest($ch) {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'token: '.$this->token,
            'Content-Type: application/json;charset=UTF-8',
        ]);
        $res = curl_exec($ch);
        $requestInfo = curl_getinfo($ch);

        if ($requestInfo['http_code'] >= 300) {
            $parsedRes = json_decode($res);
            throw new CloudWSException($parsedRes->message);
        }

        return json_decode($res);
    }

    /**
     * @param Object $responseChannel
     * @return Channel
     */
    private function convertResponseChannelToChannel($responseChannel) {
        $channel = new Channel();
        $channel->id = $responseChannel->id;
        $channel->name = $responseChannel->name;
        $channel->totalMessages = $responseChannel->totalMessages;
        $channel->lastActionTimestamp = $responseChannel->lastActionTimestamp;
        return $channel;
    }

    /**
     * @param string $channelName
     * @return Channel
     * @throws CloudWSException
     */
    public function createChannel($channelName)
    {
        $ch = curl_init($this->cloudWSUrl . '/api/channel/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(["name" => $channelName]));

        $res = $this->makeRequest($ch);
        return $this->convertResponseChannelToChannel($res);
    }

    /**
     * @return Channel[]
     * @throws CloudWSException
     */
    public function getAllChannels()
    {
        $ch = curl_init($this->cloudWSUrl . '/api/channel/');
        $res = $this->makeRequest($ch);

        return array_map(function ($channel) {
            return $this->convertResponseChannelToChannel($channel);
        }, $res);
    }


    /**
     * @param string $channelName
     * @param mixed $message
     * @throws CloudWSException
     */
    public function sendMessage($channelName, $message)
    {
        $ch = curl_init($this->cloudWSUrl . '/api/channel/' . $channelName . '/message');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $this->makeRequest($ch);
    }

    /**
     * @param string $name
     * @throws CloudWSException
     */
    public function deleteChannel($name)
    {
        $ch = curl_init($this->cloudWSUrl . '/api/channel/' . $name);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

        $this->makeRequest($ch);
    }
}
