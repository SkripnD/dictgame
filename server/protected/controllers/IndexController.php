<?php

class IndexController extends FrontController
{

    public function actionVerbs($count = 3)
    {
        $count = intval($count);
        $dict  = new Dictionary();
        $words = $dict->randVerbs($count);
        $this->response($words);
    }

    public function actionAdjectives($count = 3)
    {
        $count = intval($count);
        $dict  = new Dictionary();
        $words = $dict->randAdjectives($count);
        $this->response($words);
    }

    public function actionAdverbs($count = 3)
    {
        $count = intval($count);
        $dict  = new Dictionary();
        $words = $dict->randAdverbs($count);
        $this->response($words);
    }

    public function actionNouns($count = 3)
    {
        $count = intval($count);
        $dict  = new Dictionary();
        $words = $dict->randNouns($count);
        $this->response($words);
    }

}
