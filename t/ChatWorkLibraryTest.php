<?php
require_once(dirname(__DIR__) . '/ChatWorkLibrary.class.php');

class ChatWorkLibraryTest extends PHPUnit_Framework_TestCase {
    private $chatwork;
    private $account_id;
    private $room_id;

    public function setUp() {
        $this->chatwork = new ChatWorkLibrary(getenv('CHATWORK_API_KEY'));
    }

    public function tearDown() {
    }

    public function testMe() {
        $result = json_decode($this->chatwork->getMe());
        $this->assertTrue(array_key_exists('account_id', $result));
    }

    public function testMy() {
        $result = json_decode($this->chatwork->getMyStatus());        
        $this->assertTrue(array_key_exists('mention_room_num', $result));        

        // テストのためのアカウントIdを取得する
        $result = json_decode($this->chatwork->getMe());
        $account_id = $result->{'account_id'};

        // テストのためのルームを作成する
        $result = json_decode($this->chatwork->postRooms('Room name', array($account_id)));
        $room_id = $result->{'room_id'};

        $result = json_decode($this->chatwork->postRoomsTasksByRoomId($room_id, 'Task', array($account_id)));
        $result = json_decode($this->chatwork->getMyTasks($account_id, 'open'));
        $this->assertEquals($result[0]->{'assigned_by_account'}->{'account_id'}, $account_id);
        $this->assertEquals($result[0]->{'status'}, 'open');

        // テストのためのルームを削除する
        $this->chatwork->deleteRoomsByRoomId($room_id, 'delete');
    }

    public function testContacts() {
        $result = json_decode($this->chatwork->getContacts());
        $this->assertTrue(is_array($result));
    }

    public function testRooms() {
        $result = json_decode($this->chatwork->getRooms());
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result[0]);

        // テストのためのアカウントIdを取得する
        $result = json_decode($this->chatwork->getMe());
        $account_id = $result->{'account_id'};

        $result = json_decode($this->chatwork->postRooms('Room name', array($account_id), 'Hello, world', 'group'));
        $room_id = $result->{'room_id'};        
        $this->assertNotEmpty($room_id);

        $result = json_decode($this->chatwork->getRoomsByRoomId($room_id));
        $this->assertEquals($result->{'room_id'}, $room_id);
        
        $this->chatwork->deleteRoomsByRoomId($room_id, 'delete');        
    }
}