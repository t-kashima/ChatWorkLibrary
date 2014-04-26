[![Build Status](https://travis-ci.org/t-kashima/ChatWork.svg?branch=master)](https://travis-ci.org/t-kashima/ChatWork)
NAME
========
ChatWork - ChatWork API Library

SYNOPSIS
========
```php
require_once('ChatWork.class.php');

$chatwork = new ChatWork(CHATWORK_API_KEY);
// You can post a message in ChatWork.
$chatwork->postRoomsMessagesByRoomId(CHATWORK_ROOM_ID, 'message');
```

