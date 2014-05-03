<?php
require_once(dirname(__DIR__) . '/ChatWorkLibrary.class.php');

class ChatWorkLibraryTest extends PHPUnit_Framework_TestCase {
    private $chatwork;
    private $account_id;
    private $room_id;

    public function setUp() {
        $this->chatwork = new ChatWorkLibrary(getenv('CHATWORK_API_KEY'));

        // テストのためのアカウントIdを取得する
        $result = json_decode($this->chatwork->getMe());
        $this->account_id = $result->{'account_id'};

        // テストのためのルームを作成する
        $result = json_decode($this->chatwork->postRooms('Room name', array($this->account_id)));
        $this->room_id = $result->{'room_id'};
    }

    public function tearDown() {
        /* // テストのためのルームを削除する */
        $this->chatwork->deleteRoomsByRoomId($this->room_id, 'delete');
    }

    public function testMe() {
        $result = json_decode($this->chatwork->getMe());
        $this->assertTrue(array_key_exists('account_id', $result));
    }

    public function testMy() {
        $result = json_decode($this->chatwork->getMyStatus());        
        $this->assertTrue(array_key_exists('mention_room_num', $result));        

        $result = json_decode($this->chatwork->postRoomsTasksByRoomId($this->room_id, 'Task', array($this->account_id)));

        $result = json_decode($this->chatwork->getMyTasks($this->account_id, 'open'));
        $this->assertEquals($result[0]->{'assigned_by_account'}->{'account_id'}, $this->account_id);
        $this->assertEquals($result[0]->{'status'}, 'open');
    }

    public function testContacts() {
        $result = json_decode($this->chatwork->getContacts());
        $this->assertTrue(is_array($result));
    }

    public function testRooms() {
        $result = json_decode($this->chatwork->getRooms());
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result[0]);
        
    }
}