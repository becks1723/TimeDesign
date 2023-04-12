<?php

namespace TimeDesign\Models;

class Config {
    private $db_user;
    private $db_pass;
    private $db_name;
    private $db_host;
    private $uri;

    public function __construct() {
        $json = json_decode(file_get_contents(__DIR__ . "/../../config.json"), true);
        $this->db_host = $json['db_host'];
        $this->db_name = $json['db_name'];
        $this->db_user = $json['db_user'];
        $this->db_pass = $json['db_pass'];
        $this->uri = $json['uri'];
    }

    public function getDbHost(): string {
        return $this->db_host;
    }

    public function getDbName(): string {
        return $this->db_name;
    }

    public function getDbUser(): string {
        return $this->db_user;
    }

    public function getDbPass(): string {
        return $this->db_pass;
    }

    public function getURI() {
        return $this->uri;
    }
}
