<?php
namespace Battleship\Network;

use Battleship\Game\QueueMgr;

class PacketHandler
{
    static function cmsg_request_field(\stdClass $data, ClientSession $session)
    {
        $session->generateField();
    }

    static function cmsg_join_queue(\stdClass $data, ClientSession $session)
    {
        QueueMgr::getInstance()->joinQueue($session);
    }
    
    static function cmsg_leave_queue(\stdClass $data, ClientSession $session)
    {
        QueueMgr::getInstance()->leaveQueue($session);
    }
    
    static function cmsg_player_move(\stdClass $data, ClientSession $session)
    {
        $game = $session->getGame();
        
        if ($game)
            $game->playerMove($data, $session);
    }
    
    static function cmsg_leave_game(\stdClass $data, ClientSession $session)
    {
        $game = $session->getGame();
        
        if ($game)
            $game->playerLeave($session);
    }
    
    static function cmsg_ping(\stdClass $data, ClientSession $session)
    {
        $packet = new Packet([
            'opcode' => 'smsg_pong',
            'data' => []
        ]);

        $session->SendPacket($packet);
    }
    
    static function cmsg_online(\stdClass $data, ClientSession $session)
    {
        $packet = new Packet([
            'opcode' => 'smsg_online',
            'data' => [
                'online' => /*$context->GetUserMgr()->GetUsersCount()*/0
            ]
        ]);

        $session->SendPacket($packet);
    }
    
    static function cmsg_game_chat_message(\stdClass $data, ClientSession $session)
    {
        $game = $session->getGame();
        
        if ($game)
            $game->chatMessage($data->message, $session);
    }
}

?>
