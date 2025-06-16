<?php

class Comment implements JsonSerializable{

    public $id;
    public $author;
    public $text;
    public $created;
    public $likes;

    public function __construct($id, $author, $text, $image, $created, $likes = []) {
        $this->id = $id;
        $this->author = $author;
        $this->text = $text;
        $this->image = $image;
        $this->created = $created;
        $this->likes = $likes;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}