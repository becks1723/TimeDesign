<?php

namespace TimeDesign\Controllers;

use TimeDesign\Response\JsonResponse;

class ClubController extends Controller {
    public function getClub(): JsonResponse {
        if (!isset($_GET['name'])) {
            return $this->jsonResponse([]);
        }
        $clubs = $this->core->getDbQueries()->getClubsWhereNameLike($_GET['name']);
        return $this->jsonResponse($clubs);
    }
}
