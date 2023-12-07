<?php

namespace Src\Controllers;

use Laminas\Diactoros\ServerRequest;
use MiladRahimi\PhpRouter\View\View;
use ORM;

class CourseController
{
//    public function sortCourses()
//    {
//        $courses = ORM::forTable('category')
//            ->join('course', array('course.category_id', '=', 'category.id'))
//            ->where('category_id',$_POST['orderby'])
//            ->find_many();
//        return $courses;
//    }
//    function qwe():array{
//        $orderby = 'all';
//        var_dump( $_POST['orderby']);
//        if ($orderby !== $_POST['orderby'] ) {
//
//            if (!empty($_POST['orderby'])) {
//                $courses = ORM::forTable('category')
//                    ->join('course', array('course.category_id', '=', 'category.id'))
//                    ->where('category_id', $_POST['orderby'])
//                    ->find_many();
//                return $courses;
//            }
//        }
//
//            $courses = ORM::forTable('category')
//                ->join('course', array('course.category_id', '=', 'category.id'))
//                ->find_many();
//            return $courses;
//
//    }
//    public function coursesPage(View $view)
//    {
////        $courses = ORM::forTable('category')
////            ->join('course', array('course.category_id', '=', 'category.id'))
////            ->find_many();
//        $courses= $this->qwe();
//        var_dump( $_POST['orderby']);
//        $categories = ORM::forTable('category')
//            ->find_many();
//        return $view->make('udema.courses-list-sidebar',[
//            'courses' => $courses,
//            'categories' => $categories
//        ]);
//    }
    public function coursesPage(View $view)
    {
        $courses = ORM::forTable('category')
            ->join('course', ['course.category_id', '=', 'category.id'])
            ->find_many();

        $categories = ORM::forTable('category')->find_many();
        if ( isset($_POST['orderby'])) {
            $orderby = $_POST['orderby'];
            $categoryID = ORM::forTable('category')->find_array();
            var_dump($orderby);
            var_dump($categoryID);
            $categoryID = array_column($categoryID,'category');
            var_dump($categoryID);
            if (in_array($orderby, $categoryID)) {
                $courses = ORM::forTable('category')
                    ->join('course', ['course.category_id', '=', 'category.id'])
                    ->where('category_id', $orderby)
                    ->find_many();
                return $view->make('udema.courses-list-sidebar', [
                    'courses' => $courses,
                    'categories' => $categories
                ]);
            }
        }
        return $view->make('udema.courses-list-sidebar', [
            'courses' => $courses,
            'categories' => $categories
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


//    public function sortCourses(ServerRequest $request,View $view)
//    {
//        $params = $request->getParsedBody();
//        $courses = ORM::forTable('course')->where('category_id',$params['orderby'])->find_many();
//        return $view->make('udema.courses-list-sidebar',[
//            'courses' => $courses
//        ]);
//    }


}