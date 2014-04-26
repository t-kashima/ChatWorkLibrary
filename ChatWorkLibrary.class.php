<?php

class ChatWorkLibrary {
    // END POINT
    const BASE_END_POINT = 'https://api.chatwork.com/v1';

    // API KEY
    private $api_key = '';

    public function __construct($api_key = '') {
        assert($api_key != '', 'API KEYを設定して下さい');
        $this->api_key = $api_key;
    }

    /**
     * @brief 自分自身の情報を取得
     * @return 自分自身の情報
     */
    public function getMe() {
        $url = ChatWorkLibrary::BASE_END_POINT . '/me';
        $content = $this->getContentFromUrl($url);
        return $content;        
    }

    /**
     * @brief 自分の未読数、未読To数、未完了タスク数を取得
     * @return 自分の未読数、未読To数、未完了タスク数
     */
    public function getMyStatus() {
        $url = ChatWorkLibrary::BASE_END_POINT . '/my/status';
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief 自分のタスク一覧を取得 (※100件まで取得可能)
     * @param $assigned_by_account_id タスクの依頼者のアカウントID
     * @param $status タスクのステータス (open/done)
     * @return 
     */    
    public function getMyTasks($assigned_by_account_id = -1, $status = '') {
        // 引数があるパラメータだけ設定する
        $data = array();
        if ($assigned_by_account_id != -1) {
            $data['assigned_by_account_id'] = $assigned_by_account_id;
        }
        if ($status != '') {
            $data['status'] = $status;
        }
        $url = ChatWorkLibrary::BASE_END_POINT . '/my/tasks';
        $content = $this->getContentFromUrl($url, 'GET', $data);
        return $content;
    }

    /**
     * @brief 自分のコンタクト一覧を取得
     * @return 自分のコンタクト一覧
     */
    public function getContacts() {
        $url = ChatWorkLibrary::BASE_END_POINT . '/contacts';
        $content = $this->getContentFromUrl($url);
        return $content;        
    }

    /**
     * @brief 自分のチャット一覧の取得
     * @return 自分のチャット一覧
     */
    public function getRooms() {
        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms';
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief グループチャットを新規作成
     * @param $name グループチャット名
     * @param $members_admin_ids 管理者権限のユーザーIDの配列
     * @param $description チャット概要
     * @param $icon_preset アイコン種類 (group/check/document/meeting/event/project/business, 
     *                                   study/security/star/idea/heart/magcup/beer/music, 
     *                                   sports/travel)
     * @param $members_member_ids メンバー権限のユーザーIDの配列
     * @param $members_readonly_ids 閲覧のみ権限のユーザーIDの配列
     * @return 作成したグループチャットのROOM ID
     */
    public function postRooms($name = "", $members_admin_ids = array(), $description = '', 
                              $icon_preset = '', $members_member_ids = array(), 
                              $members_readonly_ids = array()) {
        assert($name != "", 'グループチャット名を設定して下さい');
        assert(empty($members_admin_ids) == false, '管理者権限のユーザーIDの配列を設定して下さい');

        // 引数があるパラメータだけ設定する
        $data = array();
        $data['name'] = $name;
        $data['members_admin_ids'] = implode(',', $members_admin_ids);
        if ($description != '') {
            $data['description'] = $description;
        }
        if ($icon_preset != '') {
           $data['icon_preset'] = $icon_preset;
        }
        if (empty($members_member_ids) == false) {
            $data['members_member_ids'] = implode(',', $members_member_ids);
        }
        if (empty($members_readonly_ids) == false) {
            $data['members_readonly_ids'] = implode(',', $members_readonly_ids);
        }

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms';        
        $content = $this->getContentFromUrl($url, 'POST', $data);
        return $content;
    }

    /**
     * @brief チャットの名前、アイコン、種類(my/direct/group)を取得
     * @param $room_id ROOM ID
     * @return チャットの名前、アイコン、種類(my/direct/group)
     */
    public function getRoomsByRoomId($room_id = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');
        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id;        
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief チャットの名前、アイコンをアップデート
     * @param $room_id ROOM ID
     * @param $description チャット概要
     * @param $icon_preset アイコン種類 (group/check/document/meeting/event/project/business, 
     *                                   study/security/star/idea/heart/magcup/beer/music, 
     *                                   sports/travel)
     * @param $name グループチャット名
     * @return アップデートしたグループチャットのROOM ID
     */
    public function putRoomsByRoomId($room_id = -1, $description = '', $icon_preset = '', $name = '') {
        assert($room_id != -1, 'ROOM IDを設定して下さい');

        // 引数があるパラメータだけ設定する
        $data = array();
        if ($description != '') {
            $data['description'] = $description;
        }
        if ($icon_preset != '') {
            $data['icon_preset'] = $icon_preset;
        }
        if ($name != '') {
            $data['name'] = $name;
        }

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id;        
        $content = $this->getContentFromUrl($url, 'PUT', $data);
        return $content;
    }

    /**
     * @brief グループチャットを退席/削除する
     * @param $room_id ROOM ID
     * @param $action_type 退席するか、削除するか (leave/delete)
     */
    public function deleteRoomsByRoomId($room_id = -1, $action_type = '') {
        assert($room_id != -1, 'ROOM IDを設定して下さい');
        assert($action_type != '', '退席するか、削除するかを設定して下さい');        
        
        $data = array('action_type' => $action_type);
        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id;        
        $content = $this->getContentFromUrl($url, 'DELETE', $data);
        return $content;
    }

    /**
     * @brief チャットのメンバー一覧を取得
     * @param $room_id ROOM ID
     * @param $action_type 退席するか、削除するか (leave/delete)
     * @return チャットのメンバー一覧
     */    
    public function getRoomsMembersByRoomId($room_id = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/members';        
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief チャットのメンバーを一括変更
     * @param $room_id ROOM ID
     * @param $members_admin_ids 管理者権限のユーザーの配列
     * @param $members_member_ids メンバー権限のユーザーの配列
     * @param $members_readonly_ids 閲覧のみ権限のユーザーの配列
     * @return 変更後のチャットメンバー
     */    
    public function putRoomsMembersByRoomId($room_id = -1, $members_admin_ids = array(), 
                                            $members_member_ids = array(), $members_readonly_ids = array()) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');
        assert(empty($members_admin_ids) == false, '管理者権限のユーザーIDの配列を設定して下さい');
        
        $data = array('members_admin_ids' => implode(',', $members_admin_ids),
                      'members_member_ids' => implode(',', $members_member_ids), 
                      'members_readonly_ids' => implode(',', $members_readonly_ids));

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/members';        
        $content = $this->getContentFromUrl($url, 'PUT', $data);
        return $content;
    }

    /**
     * @brief チャットのメッセージ一覧を取得 (未実装)
     * @param $room_id ROOM ID
     * @return チャットのメッセージ一覧
     */    
    public function getRoomsMessagesByRoomId($room_id = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');
        
        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/messages';
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief チャットに新しいメッセージを追加
     * @param $room_id ROOM ID
     * @param $body 送信するメッセージ
     * @return 追加したメッセージのMESSAGE ID
     */
    public function postRoomsMessagesByRoomId($room_id = -1, $body = '') {
        assert($room_id != -1, 'ROOM IDを設定して下さい');        
        assert($body != '', '送信するメッセージを設定して下さい');

        $data = array('body' => $body);
        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/messages';
        $content = $this->getContentFromUrl($url, 'POST', $data);
        return $content;
    }

    /**
     * @brief メッセージ情報を取得
     * @param $room_id ROOM ID
     * @param $message_id MESSAGE ID
     * @return メッセージ情報
     */
    public function getRoomsMessagesByRoomIdAndMessageId($room_id = -1, $message_id = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');        
        assert($message_id != -1, 'MESSAGE IDを設定して下さい');

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/messages/' . $message_id;
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief チャットのタスク一覧を取得 (※100件まで取得可能)
     * @param $room_id ROOM ID
     * @param $account_id タスクの担当者のアカウントID
     * @param $assigned_by_account_id タスクの依頼者のアカウントID
     * @param $status タスクのステータス (open/done)
     * @return チャットのタスク一覧
     */
    public function getRoomsTasksByRoomId($room_id = -1, $account_id = -1, 
                                          $assigned_by_account_id = -1, $status = '') {
        assert($room_id != -1, 'ROOM IDを設定して下さい');        

        // 引数があるパラメータだけ設定する
        $data = array();
        if ($account_id != -1) {
            $data['account_id'] = $account_id;
        }
        if ($assigned_by_account_id != -1) {
            $data['assigned_by_account_id'] = $assigned_by_account_id;
        }
        if ($status != '') {
            $data['status'] = $status;
        }

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/tasks';
        $content = $this->getContentFromUrl($url, 'GET', $data);
        return $content;
    }    

    /**
     * @brief チャットに新しいタスクを追加
     * @param $room_id ROOM ID
     * @param $body タスクの内容
     * @param $to_ids 担当者のアカウントIDの配列
     * @param $limit タスクの期限 (Unix time)
     * @return 追加したタスクのTASK ID
     */
    public function postRoomsTasksByRoomId($room_id = -1, $body = '', $to_ids = array(), $limit = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');                
        assert($body != '', 'タスクの内容を設定して下さい');
        assert(empty($to_ids) == false, '担当者のアカウントIDの配列を設定して下さい');
        
        // 引数があるパラメータだけ設定する
        $data = array();
        $data['body'] = $body;
        $data['to_ids'] = implode(', ', $to_ids);
        if ($limit != -1) {
            $data['limit'] = $limit;
        }

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/tasks';
        $content = $this->getContentFromUrl($url, 'POST', $data);
        return $content;
    }

    /**
     * @brief タスク情報を取得
     * @param $room_id ROOM ID
     * @param $task_id TASK ID
     * @return タスク情報
     */
    public function getRoomsTasksByRoomIdAndTaskId($room_id = -1, $task_id = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');        
        assert($task_id != -1, 'TASK IDを設定して下さい');        

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/tasks/' . $task_id;
        $content = $this->getContentFromUrl($url);
        return $content;
    }

    /**
     * @brief チャットのファイル一覧を取得 (※100件まで取得可能)
     * @param $room_id ROOM ID
     * @param $account_id アップロードしたユーザーのアカウントID
     * @return チャットのファイル一覧
     */
    public function getRoomsFilesByRoomId($room_id = -1, $account_id = -1) {
        assert($room_id != -1, 'ROOM IDを設定して下さい');        

        // 引数があるパラメータだけ設定する
        $data = array();        
        if ($account_id != -1) {
            $data['account_id'] = $account_id;
        }

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/files';
        $content = $this->getContentFromUrl($url, 'GET', $data);
        return $content;
    }

    /**
     * @brief ファイル情報を取得
     * @param $room_id ROOM ID
     * @param $file_id FILE ID
     * @param $create_download_url ダウンロードする為のURLを生成するか (true/false)
     *                             (30秒間だけダウンロード可能なURLを生成します)
     * @return ファイル情報
     */
    public function getRoomsFilesByRoomIdAndFileId($room_id = -1, $file_id = -1, $create_download_url = '') {
        assert($room_id != -1, 'ROOM IDを設定して下さい');
        assert($file_id != -1, 'FILE IDを設定して下さい');

        // 引数があるパラメータだけ設定する
        $data = array();        
        if ($create_download_url != '') {
            $data['create_download_url'] = $create_download_url;
        }

        $url = ChatWorkLibrary::BASE_END_POINT . '/rooms/' . $room_id . '/files/' . $file_id;
        $content = $this->getContentFromUrl($url, 'GET', $data);
        return $content;
    }

    /**
     * @brief URLにアクセスして情報を取得する
     * @param $url アクセスするURL
     * @param $method メソッド
     * @param $data 送信するデータの配列
     * @return 取得した情報
     */
    private function getContentFromUrl($url, $method = 'GET', $data = array()) {
        $data = http_build_query($data, '', '&'); 

        // POST/PUT/DELETEの時はBodyにGETの時はURLにつけて送る
        $post_data = "";
        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $post_data = $data;
        } else if ($method == 'GET' && $data != "") {
            $url = $url . '?' . $data;
        }

        $header = array('X-ChatWorkToken: ' . $this->api_key);
        $context = array(
                         'http' => array(
                                         'method'  => $method,
                                         'header'  => implode('\r\n', $header),
                                         'content' => $post_data
                                         )
                         );
        $content = file_get_contents($url, false, stream_context_create($context));        
        return $content;
    }
}
