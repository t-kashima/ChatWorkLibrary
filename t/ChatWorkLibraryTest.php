<?php
require_once(dirname(__DIR__) . '/ChatWorkLibrary.class.php');

class ChatWorkLibraryTest extends PHPUnit_Framework_TestCase {
    private $chatwork;

    public function setUp() {
        $this->chatwork = new ChatWorkLibrary(getenv('CHATWORK_API_KEY'));
    }

    public function testMe() {
        $result = json_decode($this->chatwork->getMe());
        $this->assertTrue(array_key_exists('account_id', $result));
    }

    public function testMy() {
        $result = json_decode($this->chatwork->getMyStatus());        
        $this->assertTrue(array_key_exists('mention_room_num', $result));        

        $result = json_decode($this->chatwork->getMyTasks());
        $this->assertTrue(is_array($result));
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