<?php

namespace TimeDesign;

use TimeDesign\Database\DatabaseQueries;
use TimeDesign\Database\MysqlDatabase;
use TimeDesign\Models\Config;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use TimeDesign\Models\User;

class Core {
    private $config;
    private $db;
    private $db_queries;
    private $loader;
    private $twig;
    private $css_files;
    private $js_files;
    private $page_title;
    private ?User $user;

    public function __construct() {
        $this->config = new Config();
        $this->db = new MysqlDatabase(
            $this->config->getDbUser(),
            $this->config->getDbPass(),
            $this->config->getDbName(),
            $this->config->getDbHost()
        );
        $this->db_queries = new DatabaseQueries($this->db);
        $this->initTwig();
        $this->css_files = [];
        $this->js_files = [];
        $this->page_title = "TimeDesign";
        $this->user = $this->setLoggedInUser();
    }

    public function getConfig(): Config {
        return $this->config;
    }

    public function getDbQueries(): DatabaseQueries {
        return $this->db_queries;
    }

    public function getDb() {
        return $this->db;
    }

    private function initTwig() {
        $this->loader = new FilesystemLoader(__DIR__ . '/templates');
        $this->twig = new Environment($this->loader, [
            'strict_variables' => true
        ]);
        $this->twig->addGlobal('xsrf_token', $this->getXSRFToken());
    }

    public function renderTwig(string $template, array $args): string {
        return $this->twig->render($template, $args);
    }

    public function getCSSFiles() {
        return $this->css_files;
    }

    public function getJSFiles() {
        return $this->js_files;
    }

    public function addCSSFile(string $file) {
        $this->css_files[] = $file;
    }

    public function addJSFile(string $file) {
        $this->js_files[] = $file;
    }

    public function setPageTitle(string $title) {
        $this->page_title = $title;
    }

    public function getPageTitle(): string {
        return $this->page_title;
    }

    public function isLoggedIn(): bool {
        return $this->user !== null;
    }

    private function setLoggedInUser(): ?User {
        return isset($_SESSION['user']) ? $this->getDbQueries()->getUserByRCS($_SESSION['user']) : null;
    }

    public function getLoggedInUser(): ?User {
        return $this->user;
    }

    public function getXSRFToken() {
        return $_SESSION['xsrf_token'] ?? '';
    }
}
