<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.05.2018
 * Time: 22:08
 */

namespace App\Console\Commands\Socket\Servers;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class OnlineUserServer implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $data;

    /**
     * OnlineUserServer constructor.
     */
    public function __construct()
    {
        $this->data = new \SplObjectStorage();
    }

    /**
     * {@inheritdoc}
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $this->data->contains($conn) ?: $this->data->attach($conn);
    }

    /**
     * {@inheritdoc}
     */
    public function onClose(ConnectionInterface $conn)
    {
        $this->data->detach($conn);
    }

    /**
     * {@inheritdoc}
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->close();
    }

    /**
     * Отправляем всем информацию что данный юзер онлайн
     *
     * {@inheritdoc}
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        foreach ($this->data as $data) {
            $data->send($msg);
        }
    }
}