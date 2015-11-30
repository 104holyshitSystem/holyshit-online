# holyshit setup


### 1.create empty database called holyshit. remember using utf8 encoding.

---

### 2.setting app/protectd/config/database.php
``` 
<?php

return array(
	'connectionString' => 'mysql:host=localhost;dbname=holyshit',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => 'your_password',
	'charset' => 'utf8',
);
```

---

### 3.setting app/node/server.js
```
var db = mysql.createPool({
    connectionLimit   :   100,
    host              :   'localhost',
    user              :   'root',
    password          :   'your_password',
    database          :   'holyshit',
    debug             :   false
});
```

---

### 4.db migtation initialize
<code>cd holyshit-online/app/protected </code>
<code>php yiic migrate</code>

---

### 5.start node.js server
<code>node your_holyshit_folder/app/node/server.js</code>

---

The api of sensor receive in <code>localhost:3000/api/</code>

You can test in this url <code>http://localhost/holyshit-online/test-program/send-test.html</code> if you want to test api.

### have fun AGAIN :)
