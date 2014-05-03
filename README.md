[![Build Status](https://travis-ci.org/t-kashima/ChatWorkLibrary.svg?branch=master)](https://travis-ci.org/t-kashima/ChatWork)  [![Coverage Status](https://coveralls.io/repos/t-kashima/ChatWorkLibrary/badge.png?branch=master)](https://coveralls.io/r/t-kashima/ChatWorkLibrary?branch=master)

NAME
========
ChatWorkLibrary - ChatWork API library

SYNOPSIS
========
```php
require_once('ChatWorkLibrary.class.php');

$chatwork = new ChatWorkLibrary(CHATWORK_API_KEY);
// You can post a message in ChatWork.
$chatwork->postRoomsMessagesByRoomId(CHATWORK_ROOM_ID, 'message');
```

