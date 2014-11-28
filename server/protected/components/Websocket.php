<?php

class Websocket extends CApplicationComponent
{
    public $url;
    public $projectId;
    public $secretKey;
    
    /**
     * Ping server
     * @return bool
     */
    public function ping()
    {
        $self = $this;

        return Yii::app()->controller->tryMe(
            function () use ($self) {

                $client = new GuzzleHttp\Client();

                $response = $client->post(
                    $self->url . '/api/' . $self->projectId,
                    ['body' => $self->prepareData([], [])]
                );

                return $response->getStatusCode() == '200';
            },
            function () {
                return false;
            }
        );
    }

    public function prepareData($channel, $data)
    {
        $params = json_encode([
            'method' => 'publish',
            'params' => [
                'channel' => $channel,
                'data'    => $data
            ]
        ]);

        return [
            'sign' => hash_hmac('md5', $this->projectId . $params, $this->secretKey),
            'data' => $params
        ];
    }

    /**
     * Publish to channel
     * @param string $channel
     * @param array $data
     * @return bool|array
     */
    public function send($channel, $data)
    {
        $self = $this;

        return Yii::app()->controller->tryMe(
            function () use ($self, $channel, $data) {

                $client = new GuzzleHttp\Client();

                $response = $client->post(
                    $self->url . '/api/' . $self->projectId,
                    ['body' => $self->prepareData($channel, $data)]
                );

                return $response->json()[0];
            }
        );
    }

    /**
     * Generate token
     * @param $userId
     * @param $timestamp
     * @return string
     */
    public function generateToken($userId, $timestamp)
    {
        return hash_hmac('md5', $this->projectId . $userId . $timestamp, $this->secretKey);
    }

    public function connectJsCode()
    {
        $timestamp = time();
        
        $options = [
            'url'       => $this->url . '/connection',
            'project'   => (string)$this->projectId,
            'user'      => (string)Yii::app()->user->id,
            'timestamp' => (string)$timestamp,
            'token'     => $this->generateToken(Yii::app()->user->id, $timestamp)
        ];
        
        $options = CJavaScript::encode($options);

        Yii::app()->clientScript
            ->registerScriptFile('public/plugins/sockjs-0.3.4.min.js')
            ->registerScriptFile('public/plugins/centrifuge.js')
            ->registerScript(
                'centrifuge_' . uniqid(),
                'centrifuge = new Centrifuge(' . $options . '); centrifuge.connect(); userId = ' . Yii::app()->user->id,
                CClientScript::POS_END
            );
    }
}
