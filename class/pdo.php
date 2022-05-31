<?php
/**
   * PDOを取得する
   *
   * @return PDO
   */
function get_pdo()
{
  $dsn = 'pgsql:host=ec2-35-169-37-64.compute-1.amazonaws.com options=\'--client_encoding=UTF8\'; dbname=df4tpusuv0mai1';
  $username = 'nkfjcruoqytugb';
  $password = '9239554344ac10d5e6bccbe422c1dcbcb6d06907e57437639010629e49e6218c';
  $options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  ];
  return new PDO($dsn, $username, $password, $options);
}
