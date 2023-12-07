<?php

namespace Src\Controllers;

use MiladRahimi\PhpRouter\View\View;
use ORM;

class CourseController
{
    public function coursesPage(View $view)
    {
        $courses = ORM::forTable('category')
            ->join('course', array('course.category_id', '=', 'category.id'))
            ->find_many();
        return $view->make('udema.courses-list-sidebar',[
            'courses' => $courses
        ]);
    }
    public function course_detailPage(View $view, int $course_id)
    {
        $courses = ORM::forTable('course')->find_one($course_id);
        $comments = ORM::forTable('comments')->where('course_id', $course_id)
            ->join('users', ['comments.user_id', '=', 'users.id'])->find_many();
        $contents = ORM::forTable('content')
            ->where('course_id', $course_id)
            ->find_many();

        foreach ($contents as $content) {
        $content->lessons = ORM::forTable('lessons')
            ->where('content_id',$content->id)
            ->find_many();

        }
        return $view->make('udema.course-detail', [
            'courses' => $courses,
            'contents' => $contents,
            'comments' => $comments
        ]);
    }


}