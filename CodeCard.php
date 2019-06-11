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

/**
 * Class: CodeCard
 * Properties: This class do generate codecars
 * Date: 18-05-2016
 * Copyright: DISTGUARD E.I.R.L.
 */
class CodeCard {

    public $theCard = array();
    public $RowNames = [1 => "A", 2 => "B", 3 => "C", 4 => "D", 5 => "E", 6 => "F", 7 => "G", 8 => "H", 9 => "I", 10 => "J", 11 => "K", 12 => "L", 13 => "M"];
    private $MaxRow = 13;
    private $aKey = "þdåÓBþ1VX¹Tuéâ¿3ÜÖô£ð»uöèpsº1f{r";
    private $eKey = "KIO5iIkPP&qtd:TQb?_mPrin!_MofSgO";
    private $ImageCard;

    public function __construct() {
        $this->ImageCard = new ImageCard();
    }

    public function genCard($thecolumns = 12, $therows = 7) {
        $a_aux = array();
        for ($row = 1; $row <= $therows; $row++) {
            for ($column = 1; $column <= $thecolumns; $column++) {
                $value = 0;
                while (true) {
                    $value = strtoupper(dechex(rand(16, 255)));
                    if (in_array($value, $a_aux)) {
                        // Seguimos buscando
                    } else {
                        array_push($a_aux, $value);
                        break;
                    }
                }
                $this->theCard[$this->RowNames[$row]][$column] = $value;
            }
        }
    }

    public function getCard() {
        return $this->jsonEC();
    }

    public function printCard() {
        if ($this->ImageCard->getCountElements($this->getCard())) {
            $this->ImageCard->createPNG();
        }
    }

    private function jsonEC() {
        return json_encode($this->theCard);
    }

    private function encCard(string $data) {
        $iv = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->eKey, $data, 'ctr', $iv);

        $hmac = hash_hmac('sha256', $iv . $ciphertext, $this->aKey, true);
        return base64_encode($hmac . $iv . $ciphertext);
    }

    private function decCard(string $data) {
        $decoded = base64_decode($data);
        $hmac = mb_substr($decoded, 0, 32, '8bit');
        $iv = mb_substr($decoded, 32, 16, '8bit');
        $ciphertext = mb_substr($decoded, 48, null, '8bit');

        $calculated = hash_hmac('sha256', $iv . $ciphertext, $this->aKey, true);

        if (hash_equals($hmac, $calculated)) {
            $decrypted = rtrim(
                    mcrypt_decrypt(
                            MCRYPT_RIJNDAEL_128, $this->eKey, $ciphertext, 'ctr', $iv
                    ), "\0"
            );
            return $decrypted;
        }
    }

}