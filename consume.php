<?php
include_once 'CodeCard.php';
include_once 'ImageCard.php';
$mitarjeta = new CodeCard;
$mitarjeta->genCard();
echo $mitarjeta->getCard();
$mitarjeta->printCard();
