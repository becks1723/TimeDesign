<?php

namespace TimeDesign\Response;

use TimeDesign\Core;

class PageResponse extends Response {

    private $template;
    private $args;

    public function __construct(Core $core, string $template, array $args) {
        parent::__construct($core);
        $this->template = $template;
        $this->args = $args;
    }

    public function render() {
        $this->core->addCSSFile('style.css');
        $return = $this->core->renderTwig('Header.twig', [
            'css' => $this->core->getCSSFiles(),
            'js' => $this->core->getJSFiles(),
            'title' => $this->core->getPageTitle(),
            'is_logged_in' => $this->core->isLoggedIn(),
            'is_admin' => $this->core->isLoggedIn() && $this->core->getLoggedInUser()->isIsAdmin()
        ]);
        $return .= $this->core->renderTwig($this->template, $this->args);
        $return .= $this->core->renderTwig('Footer.twig', []);
        return $return;
    }
}
