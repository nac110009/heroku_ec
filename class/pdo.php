<?php
/**
   * PDOを取得する
   *
   * @return PDO
   */
function get_pdo()
{
  $dsn = 'pgsql:host=ec2-23-23-182-18.compute-1.amazonaws.com options=\'--client_encoding=UTF8\'; dbname=d10s0ratom3vis';
  $username = 'hkpkqyzdpucciw';
  $password = '984d13bb9ce61645eb3cdd11964347bf8657d28b28f61d069f1a98e375ee1085';
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];
  return new PDO($dsn, $username, $password, $options);
}
