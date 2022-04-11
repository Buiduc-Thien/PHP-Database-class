<?

require 'core/allErr.php';
require 'core/database.php';
require 'config.php';
require 'libraries/database_drivers/mysqlDriver.php';

$db = new mySqlDB($config);

$db ->table('users')
    ->update(3,[
        'account' => 'yêu',
        'password' => 'daskjlfhvadkjl'
    ]);

$users= $db ->table('users')
            ->limit(10)
            ->get();
foreach($users as $user){
    echo $user->account;
    echo "<br>";
}

