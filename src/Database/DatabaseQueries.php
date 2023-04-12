<?php

namespace TimeDesign\Database;

use TimeDesign\Models\User;

class DatabaseQueries {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function getMigrations() {
        $this->database->query("SELECT * FROM migrations ORDER BY up_time");
        return $this->database->getRows();
    }

    public function migrationTableExists(): bool {
        $this->database->query(
            "SELECT * FROM information_schema.tables WHERE table_name = ?",
            ["migrations"]
        );
        return count($this->database->getRows()) == 1;
    }

    public function addMigration(string $name) {
        $this->database->query(
            "INSERT INTO migrations (name) VALUES (?)",
            [$name]
        );
    }

    public function removeMigration(string $name) {
        $this->database->query(
            "DELETE FROM migrations WHERE name = ?",
            [$name]
        );
    }

    public function getCoursesWhereNameLike(string $name) {
        $this->database->query(
            "SELECT * FROM courses WHERE name LIKE ? AND verified = 1 ORDER BY courses.school_course_id",
            ["%$name%"]
        );
        return $this->database->getRows();
    }

    public function getJobsWhereNameLike(string $name) {
        $this->database->query(
            "SELECT * FROM jobs WHERE name LIKE ? AND verified = 1 ORDER BY jobs.company, jobs.name",
            ["%$name%"]
        );
        return $this->database->getRows();
    }

    public function getClubsWhereNameLike(string $name) {
        $this->database->query(
            "SELECT * FROM clubs WHERE name LIKE ? AND verified = 1 ORDER BY clubs.name",
            ["%$name%"]
        );
        return $this->database->getRows();
    }

    public function getCoursesWithAvgHoursWhereNameLike(string $name) {
        $this->database->query(
            "SELECT c.*, AVG(cr.homework_hours) as hw_avg, AVG(cr.lecture_hours) as lec_avg,
                    AVG(cr.study_hours) as study_avg, json_arrayagg(cr.comments) as comments,
                    AVG(cr.difficulty) as difficulty FROM courses c
                    LEFT JOIN course_responses cr on c.id = cr.course_id
                    WHERE name LIKE ? AND verified = 1 GROUP BY c.id, c.school_course_id ORDER BY c.school_course_id",
            ["%$name%"]
        );
        $courses = $this->database->getRows();
        foreach ($courses as &$c) {
            if ($c['comments'] === '[null]') {
                $c['comments'] = [];
                continue;
            }
            $c['comments'] = json_decode($c['comments']);
        }
        return $courses;
    }

    public function getJobsWithAvgHoursWhereNameLike(string $name) {
        $this->database->query(
            "SELECT j.*, AVG(jr.hours) as hr_avg FROM jobs j LEFT JOIN job_responses jr on j.id = jr.job_id
                    WHERE name LIKE ? AND verified = 1 GROUP BY j.id, j.company, j.name
                    ORDER BY j.company, j.name",
            ["%$name%"]
        );
        return $this->database->getRows();
    }

    public function getClubsWithAvgHoursWhereNameLike(string $name) {
        $this->database->query(
            "SELECT c.*, AVG(cr.hours) as hr_avg FROM clubs c LEFT JOIN club_responses cr on c.id = cr.club_id
                    WHERE name LIKE ? AND verified = 1 GROUP BY c.id, c.name
                    ORDER BY c.name",
            ["%$name%"]
        );
        return $this->database->getRows();
    }

    public function getUserByRCS(string $rcs) {
        $this->database->query(
            "SELECT * FROM users WHERE rcs_id = ?",
            [$rcs]
        );
        $res = $this->database->getRows();
        if (count($res) === 0) {
            return null;
        }
        return new User(intval($res[0]["id"]), $res[0]["rcs_id"], $res[0]["created_on"], intval($res[0]["is_admin"]) === 1);
    }

    public function addUser(string $rcs) {
        $this->database->query(
            "INSERT INTO users (rcs_id) VALUES (?)",
            [$rcs]
        );
    }

    public function createCourse(string $name, string $id) {
        $this->database->query(
            "INSERT INTO courses (school_course_id, name) VALUES (?, ?)",
            [$id, $name]
        );
    }

    public function createJob(string $name, string $company) {
        $this->database->query(
            "INSERT INTO jobs (company, name) VALUES (?, ?)",
            [$company, $name]
        );
    }

    public function createClub(string $name) {
        $this->database->query(
            "INSERT INTO clubs (name) VALUES (?)",
            [$name]
        );
    }

    public function addCourseReview(
        $course_id,
        $user_id,
        $professor,
        $lec_hours,
        $hw_hours,
        $study_hours,
        $difficulty = null,
        $comments = null
    ) {
        $this->database->query("
                INSERT INTO course_responses (course_id, submitter_user_id, professor,
                    lecture_hours, homework_hours, study_hours, difficulty, comments)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ",
            [$course_id, $user_id, $professor, $lec_hours, $hw_hours, $study_hours, $difficulty, $comments]
        );
    }

    public function addJobReview(
        $job_id,
        $user_id,
        $job_type,
        $hours
    ) {
        $this->database->query("
                INSERT INTO job_responses (job_id, submitter_user_id, job_type, hours)
                    VALUES (?, ?, ?, ?)
            ",
            [$job_id, $user_id, $job_type, $hours]
        );
    }

    public function addClubReview(
        $club_id,
        $user_id,
        $role,
        $hours
    ) {
        $this->database->query("
                INSERT INTO club_responses (club_id, submitter_user_id, role, hours)
                    VALUES (?, ?, ?, ?)
            ",
            [$club_id, $user_id, $role, $hours]
        );
    }

    public function getUnverifiedCourses() {
        $this->database->query("
            SELECT * FROM courses WHERE verified = 0
        ");
        return $this->database->getRows();
    }

    public function getUnverifiedJobs() {
        $this->database->query("
            SELECT * FROM jobs WHERE verified = 0
        ");
        return $this->database->getRows();
    }

    public function getUnverifiedClubs() {
        $this->database->query("
            SELECT * FROM clubs WHERE verified = 0
        ");
        return $this->database->getRows();
    }

    public function verifyCourse($course_id) {
        $this->database->query("
            UPDATE courses SET verified = 1 WHERE id = ?
        ", [$course_id]);
    }

    public function verifyJob($job_id) {
        $this->database->query("
            UPDATE jobs SET verified = 1 WHERE id = ?
        ", [$job_id]);
    }

    public function verifyClub($club_id) {
        $this->database->query("
            UPDATE clubs SET verified = 1 WHERE id = ?
        ", [$club_id]);
    }

    private function createQMList(int $count) {
        $str = "(";
        for ($i = 0; $i < $count; $i++) {
            $str .= "?";
            if ($i !== $count-1) {
                $str .= ",";
            }
        }
        $str .= ")";
        return $str;
    }

    public function getCourseHours(array $course_ids) {
        if (count($course_ids) === 0) {
            return [];
        }
        $pList = $this->createQMList(count($course_ids));
        $this->database->query("
            SELECT c2.name, c2.id, c.lecture_hours, c.homework_hours, c.study_hours FROM course_responses c
                JOIN courses c2 ON c2.id = c.course_id
                WHERE c.course_id IN $pList
        ", $course_ids);
        return $this->database->getRows();
    }

    public function getJobHours(array $job_ids) {
        if (count($job_ids) === 0) {
            return [];
        }
        $pList = $this->createQMList(count($job_ids));
        $this->database->query("
            SELECT j2.company, j2.name, j2.id, j.hours FROM job_responses j
                JOIN jobs j2 ON j.job_id = j2.id
                WHERE j.job_id IN $pList
        ", $job_ids);
        return $this->database->getRows();
    }

    public function getClubHours(array $club_ids) {
        if (count($club_ids) === 0) {
            return [];
        }
        $pList = $this->createQMList(count($club_ids));
        $this->database->query("
            SELECT c2.name, c2.id, c.hours FROM club_responses c
                JOIN clubs c2 ON c.club_id = c2.id
                WHERE c.club_id IN $pList
        ", $club_ids);
        return $this->database->getRows();
    }
}
