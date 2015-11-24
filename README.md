# holyshit setup

### 1.setting app/config/database.php
``` php 
<?php

return array(
	'connectionString' => 'mysql:host=localhost;dbname=holyshit',
	'emulatePrepare' => true,
	'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
);
```

### 2.setting app/node/server.js
``` javascript
var db = mysql.createPool({
    connectionLimit   :   100,
    host              :   'localhost',
    user              :   'root',
    password          :   '',
    database          :   'holyshit',
    debug             :   false
});
```

### 3. replace some code look at this
in app/sendTest.html
```
app.get("/",function(req, res){
    res.redirect('http://localhost/~joel.zhong/104/holyshit/app/index.php/client/');
});
```


<code>http://localhost/~joel.zhong/104/holyshit/app/index.php/client/</code>

you can replace to your correct url.

like this

<code>http://localhost/holyshit-online/app/index.php/client/</code>


### 4.open the terminal then keypress
<code>node your_holyshit_folder/app/node/server.js</code>



### have fun :)
