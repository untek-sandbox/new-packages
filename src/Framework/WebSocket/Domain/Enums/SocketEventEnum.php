<?php

namespace Untek\Framework\WebSocket\Domain\Enums;

class SocketEventEnum {

    const CONNECT = 'connectionEstablished';
    const DISCONNECT = 'connectionClosed';
    const MESSAGE = 'eventCreated';
    const CLIENT_MESSAGE_RECEIVED = 'clientMessageReceived';

}
