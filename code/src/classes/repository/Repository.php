<?php

namespace iutnc\nrv\repository;

use PDO;

class Repository
{
    private \PDO $pdo;
    private static ?Repository $instance = null;
    private static array $config = [ ];

    private function __construct(array $conf) {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }
    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new Repository(self::$config);
        }
        return self::$instance;
    }
    public static function setConfig(string $file) : void{
        $conf = parse_ini_file($file, true);
        if ($conf === false) {
            throw new \Exception("Error reading configuration file");
        }
        self::$config = [ 'dsn'=> $conf['dsn'],'user'=> $conf['user'],'pass'=> $conf['pass'] ];
    }
}