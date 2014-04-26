[![Build Status](https://travis-ci.org/t-kashima/ChatWorkLibrary.svg?branch=master)](https://travis-ci.org/t-kashima/ChatWork)
NAME
========
ChatWorkLibrary - ChatWork API wrapper library

SYNOPSIS
========
```php
require_once('ChatWorkLibrary.class.php');

$chatwork = new ChatWorkLibrary(CHATWORK_API_KEY);
// You can post a message in ChatWork.
$chatwork->postRoomsMessagesByRoomId(CHATWORK_ROOM_ID, 'message');
```

