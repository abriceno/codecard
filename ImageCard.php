<?php

/*
 * New BSD License
 * 
 * 
 * Copyright (c) <2017>, DISTGUARD E.I.R.L. (https://www.distguard.com)
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer in the
 *    documentation and/or other materials provided with the distribution.
 * 3. Neither the name of copyright holders nor the names of its
 *    contributors may be used to endorse or promote products derived
 *    from this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * ''AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED
 * TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL COPYRIGHT HOLDERS OR CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

/*
 * Class : ImageCard
 * Objetive: Create and prepare image for CodeCard to EndUser
 */

class ImageCard {
    public $width;
    public $height;
    public $cell_width = 40;
    public $cell_height = 30;
    public $background;
    public $background_color = '#225454';
    public $border_color = "#FFFFFF";
    public $border_size = 2;
    public $border_margin = 15;
    public $space_color = "#66ffff";
    public $space_width;
    public $space_height;
    public $line_color = "#43a8a8";
    public $random_lines_color = "#66cccc";
    private $x1;
    private $x2;
    private $y1;
    private $y2;
    public $font_family = "InputMonoCondensed-Regular.ttf";
    public $font_size = 12;
    public $font_color = "#225454";
    public $titleCard = "Security CodeCard for user: awesome";
    public $font_family_title = "InputMonoCondensed-Medium.ttf";
    public $font_size_title = 16;
    public $font_color_title = "#66ffff";
    public $height_title = 48;
    private $numberOfColumns;
    private $numberOfRows;
    private $numRandomDots = 10000;
    private $numRandomLines = 300;
    private $aelements;
    private $img;
    public $imgFile;

    public function __construct() {
        
    }

    public function CreateDimension() {
        $this->img = imagecreate($this->width, $this->height);
    }

    public function SetBackGround() {
        $rgb = $this->hexcolor2rgb($this->background_color);
        imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
    }

    public function SetSpace() {
        $rgb = $this->hexcolor2rgb($this->space_color);
        $space_color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        // Calculando el punto central para el borde
        $this->space_width = $this->width - 2 * ($this->border_size + $this->border_margin);
        $this->space_height = $this->height - 2 * ($this->border_size + $this->border_margin);
        $this->x1 = ($this->width - $this->space_width) / 2;
        $this->x2 = $this->x1 + $this->space_width;
        $this->y1 = (($this->height - $this->space_height) / 2) + $this->height_title;
        $this->y2 = $this->y1 + $this->space_height - $this->height_title;

        imagefilledrectangle($this->img, $this->x1, $this->y1, $this->x2, $this->y2, $space_color);
    }

    public function randomLines() {
        $rgb = $this->hexcolor2rgb($this->random_lines_color);
        $lines_color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        for ($veces = 1; $veces < $this->numRandomLines; $veces++) {

            $x1 = rand($this->border_margin + $this->border_size, $this->width - $this->border_margin - $this->border_size);
            $y1 = rand($this->border_margin + $this->border_size + $this->height_title, $this->height - $this->border_margin - $this->border_size);
            $x2 = rand($this->border_margin + $this->border_size, $this->width - $this->border_margin - $this->border_size);
            $y2 = rand($this->border_margin + $this->border_size + $this->height_title, $this->height - $this->border_margin - $this->border_size);

            imageline($this->img, $x1, $y1, $x2, $y2, $lines_color);
        }
    }

    public function randomDots() {
        $rgb = $this->hexcolor2rgb($this->background_color);
        $dots_color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        for($veces = 1; $veces< $this->numRandomDots; $veces++) {
            $x1 = rand($this->border_margin + $this->border_size, $this->width - $this->border_margin - $this->border_size);
            $y2 = rand($this->border_margin + $this->border_size + $this->height_title, $this->height - $this->border_margin - $this->border_size);
            imagesetpixel($this->img, $x1, $y2, $dots_color);
        }
    }

    public function setVerticalLine($x1, $y1, $height) {
        $rgb = $this->hexcolor2rgb($this->line_color);
        $line_color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        $x2 = $x1;
        $y2 = $y1 + $height;
        imageline($this->img, $x1, $y1, $x2, $y2, $line_color);
    }

    public function setHorizontalLine($x1, $y1, $width) {
        $rgb = $this->hexcolor2rgb($this->line_color);
        $line_color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        $x2 = $x1 + $width;
        $y2 = $y1;
        imageline($this->img, $x1, $y1, $x2, $y2, $line_color);
    }

    public function validate_json($json_str = null) {
        if (is_string($json_str)) {
            @json_decode($json_str);
            return (json_last_error() === JSON_ERROR_NONE);
        }
        return false;
    }

    public function getCountElements($elements = null) {
        if ($this->validate_json($elements)) {
            $this->aelements = json_decode($elements, true);
            $this->numberOfRows = count($this->aelements);
            $this->numberOfColumns = (count($this->aelements, COUNT_RECURSIVE) - $this->numberOfRows) / $this->numberOfRows;
            return true;
        } else {
            return false;
        }
    }

    public function calcWidthCodeCard() {
        $this->width = (($this->border_margin + $this->border_size) * 2) + ($this->cell_width * ($this->numberOfColumns + 1));
    }

    public function calcHeightCodeCard() {
        $this->height = $this->height_title + (($this->border_margin + $this->border_size) * 2) + ($this->cell_height * ($this->numberOfRows + 1));
    }

    public function calcDimension() {
        $this->calcWidthCodeCard();
        $this->calcHeightCodeCard();
    }

    public function paintVerticalLines() {
        if ($this->numberOfColumns > 1) {
            for ($veces = 1; $veces <= $this->numberOfColumns; $veces++) {
                $this->setVerticalLine($this->x1 + ($veces * $this->cell_width), $this->y1, $this->space_height - $this->height_title);
            }
        }
    }

    public function paintHorozontalLines() {
        if ($this->numberOfRows > 1) {
            for ($veces = 1; $veces <= $this->numberOfRows; $veces++) {
                $this->setHorizontalLine($this->x1, $this->y1 + ($veces * $this->cell_height), $this->space_width);
            }
        }
    }

    public function paintLines() {
        $this->paintVerticalLines();
        $this->paintHorozontalLines();
    }

    public function paintYCaptions() {
        $ypos = $this->y1 + $this->cell_height + ($this->cell_height + $this->font_size) / 2;
        $xpos = $this->x1 + ($this->cell_width + $this->font_size) / 3;
        $rgb = $this->hexcolor2rgb($this->font_color);
        $color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        $fontfile = 'fonts/' . $this->font_family;
        foreach ($this->aelements as $keyCaption => $rowData) {
            imagettftext($this->img, $this->font_size, 0, $xpos, $ypos, $color, $fontfile, $keyCaption);
            $ypos += $this->cell_height;
        }
    }

    public function paintXCaptions() {
        $xpos = $this->x1 + $this->cell_width + ($this->cell_width + $this->font_size) / 3;
        $ypos = $this->y1 + ($this->cell_height + $this->font_size) / 2;
        $rgb = $this->hexcolor2rgb($this->font_color);
        $color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        $fontfile = 'fonts/' . $this->font_family;
        for ($rowCaption = 1; $rowCaption <= $this->numberOfColumns; $rowCaption++) {
            imagettftext($this->img, $this->font_size, 0, $xpos, $ypos, $color, $fontfile, $rowCaption);
            $xpos += $this->cell_width;
        }
    }

    public function paintCaptions() {
        $this->paintYCaptions();
        $this->paintXCaptions();
    }

    public function paintCodes() {
        $rgb = $this->hexcolor2rgb($this->font_color);
        $color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        $fontfile = 'fonts/'. $this->font_family;
        
        
        $ypos = $this->y1+$this->cell_height+($this->cell_height-($this->font_size*2/3));
        
        foreach($this->aelements as $RowCaption=>$listRow) {
            $xpos = $this->x1+$this->cell_width+($this->cell_width-$this->font_size)/2;
            foreach($listRow as $data) {
                imagettftext($this->img, $this->font_size, 0, $xpos, $ypos, $color, $fontfile, $data);
                $xpos +=$this->cell_width;
            } 
            $ypos +=$this->cell_height;
        }
    }

    public function hexcolor2rgb($hexacolor) {
        if (substr($hexacolor, 0, 1) == "#") {
            $hexacolor = substr($hexacolor, 1);
        }
        return array('R' => hexdec(substr($hexacolor, 0, 2)), 'G' => hexdec(substr($hexacolor, 2, 2)), 'B' => hexdec(substr($hexacolor, 4, 2)));
    }

    public function setTitle() {
        $rgb = $this->hexcolor2rgb($this->font_color_title);
        $color = imagecolorallocate($this->img, $rgb['R'], $rgb['G'], $rgb['B']);
        $fontfile = 'fonts/' . $this->font_family_title;
        $x1 = ($this->width - $this->space_width) / 2;
        $y1 = $this->border_margin + $this->font_size_title;
        imagettftext($this->img, $this->font_size_title, 0, $x1, $y1, $color, $fontfile, $this->titleCard);
    }

    public function createPNG() {
        $this->calcDimension();
        $this->CreateDimension();
        $this->SetBackGround();
        $this->SetSpace();
        $this->randomLines();
        $this->randomDots();
        $this->paintLines();
        $this->setTitle();
        $this->paintCaptions();
        $this->paintCodes();
        $this->imgFile = 'veremos.png';
        imagepng($this->img, $this->imgFile);
        imagedestroy($this->img);
    }

}
/*
$imagen = new ImageCard();
$json_ccard = '{"A":{"1":"BD","2":"FD","3":"65","4":"E5","5":"F1","6":"A3","7":"55","8":"1B","9":"95","10":"53","11":"FF","12":"DA"},"B":{"1":"64","2":"83","3":"C5","4":"C7","5":"61","6":"D1","7":"9C","8":"A7","9":"4E","10":"A2","11":"67","12":"FE"},"C":{"1":"90","2":"37","3":"B6","4":"66","5":"8B","6":"69","7":"1D","8":"21","9":"3A","10":"10","11":"CD","12":"9E"},"D":{"1":"BF","2":"FA","3":"FC","4":"E1","5":"16","6":"DE","7":"7B","8":"9B","9":"A6","10":"D7","11":"70","12":"E0"},"E":{"1":"9D","2":"1F","3":"C8","4":"7C","5":"8A","6":"84","7":"76","8":"30","9":"AD","10":"AB","11":"91","12":"29"},"F":{"1":"AE","2":"C4","3":"C9","4":"26","5":"7E","6":"3F","7":"CC","8":"57","9":"AF","10":"6A","11":"3E","12":"92"},"G":{"1":"4B","2":"F2","3":"C1","4":"11","5":"6B","6":"5B","7":"DD","8":"56","9":"B0","10":"E7","11":"82","12":"50"}}';
$imagen->getCountElements($json_ccard);
$imagen->createPNG();
*/
