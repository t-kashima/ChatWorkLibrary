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
        // アカウントIdを取得
        $result = json_decode($this->chatwork->getMe());
        $this->assertTrue(array_key_exists('account_id', $result));
    }

    public function testMy() {
        // 自分の未読数、未読To数、未完了タスク数を取得
        $result = json_decode($this->chatwork->getMyStatus());        
        $this->assertTrue(array_key_exists('mention_room_num', $result));        
        // アカウントIdを取得
        $result = json_decode($this->chatwork->getMe());
        $account_id = $result->{'account_id'};

        // ルームを作成
        $result = json_decode($this->chatwork->postRooms('Room name', array($account_id)));
        $room_id = $result->{'room_id'};

        // チャットに新しいタスクを追加
        $result = json_decode($this->chatwork->postRoomsTasksByRoomId($room_id, 'Task', array($account_id)));
        
        // 自分のタスク一覧を取得
        $result = json_decode($this->chatwork->getMyTasks($account_id, 'open'));
        $this->assertEquals($result[0]->{'assigned_by_account'}->{'account_id'}, $account_id);
        $this->assertEquals($result[0]->{'status'}, 'open');

        // ルームを削除
        $this->chatwork->deleteRoomsByRoomId($room_id, 'delete');
    }

    public function testContacts() {
        // 自分のコンタクト一覧を取得
        $result = json_decode($this->chatwork->getContacts());
        $this->assertTrue(is_array($result));
    }

    public function testRooms() {
        // 自分のチャット一覧の取得
        $result = json_decode($this->chatwork->getRooms());
        $this->assertTrue(is_array($result));
        $this->assertNotEmpty($result[0]);

        // アカウントIdを取得
        $result = json_decode($this->chatwork->getMe());
        $account_id = $result->{'account_id'};

        // グループチャットを新規作成
        $result = json_decode($this->chatwork->postRooms('Room name', array($account_id), 'Hello, world', 'group'));
        $room_id = $result->{'room_id'};        
        $this->assertNotEmpty($room_id);

        // グループチャットを新規作成
        // 同じユーザーをたくさんの権限に追加しているのでRoomを作成できない
        $result = json_decode($this->chatwork->postRooms('Room name', array($account_id), 'Hello, world', 'group', array($account_id), array($account_id)));
        $this->assertEmpty($result);        

        // チャットの名前、アイコン、種類を取得
        $result = json_decode($this->chatwork->getRoomsByRoomId($room_id));
        $this->assertEquals($result->{'room_id'}, $room_id);

        // チャットの名前、アイコンをアップデート
        $result = json_decode($this->chatwork->putRoomsByRoomId($room_id, 'description', 'document', 'Hello'));        
        $this->assertEquals($result->{'room_id'}, $room_id);
        
        // チャットのメンバー一覧を取得
        $result = json_decode($this->chatwork->getRoomsMembersByRoomId($room_id));                
        $this->assertEquals($result[0]->{'account_id'}, $account_id);

        // チャットのメンバーを一括変更
        $result = json_decode($this->chatwork->putRoomsMembersByRoomId($room_id, array($account_id), array($account_id), array($account_id)));
        $this->assertEmpty($result);

        // 未実装
        /* $result = json_decode($this->chatwork->getRoomsMessagesByRoomId($room_id)); */
        /* $this->assertEquals($result->{'room_id'}, $room_id); */
        
        // チャットに新しいメッセージを追加
        $result = json_decode($this->chatwork->postRoomsMessagesByRoomId($room_id, 'Hello, world'));
        $message_id = $result->{'message_id'};
        $this->assertNotEmpty($message_id);

        // メッセージ情報を取得
        $resut = json_decode($this->chatwork->getRoomsMessagesByRoomIdAndMessageId($room_id, $message_id));
        $this->assertEquals($result->{'message_id'}, $message_id);        

        // チャットに新しいタスクを追加
        $result = json_decode($this->chatwork->postRoomsTasksByRoomId($room_id, 'Task', array($account_id), 0));
        $this->assertTrue(is_array($result->{'task_ids'}));
        $task_id = $result->{'task_ids'}[0];
        $this->assertNotEmpty($task_id);

        // チャットに新しいタスクを追加
        $result = json_decode($this->chatwork->getRoomsTasksByRoomId($room_id, $account_id, $account_id, 'open'));
        $this->assertEquals($result[0]->{'task_id'}, $task_id);

        // タスク情報を取得
        $result = json_decode($this->chatwork->getRoomsTasksByRoomIdAndTaskId($room_id, $task_id));
        $this->assertEquals($result->{'task_id'}, $task_id);
        
        // チャットのファイル一覧を取得 
        // ファイルは存在しない
        $result = json_decode($this->chatwork->getRoomsFilesByRoomId($room_id, $account_id));        
        $this->assertEquals(count($result), 0);

        // ファイル情報を取得
        $result = json_decode($this->chatwork->getRoomsFilesByRoomIdAndFileId($room_id, 123, true));
        $this->assertNotEmpty($result);

        // ルームを削除
        $this->chatwork->deleteRoomsByRoomId($room_id, 'delete');        
    }
}