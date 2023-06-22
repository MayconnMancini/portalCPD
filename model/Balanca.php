
<?php

require_once('StatusBalanca.php');

class Balanca {

    private $id;
    private $numBalanca;
    private $pip;
    private $setor;
    private $numSerie;
    private $localAtual;
    private $statusBalanca;

    public function __construct(float $numBalanca=0, int $pip=0, string $setor="", int $numSerie=0, $localAtual="", StatusBalanca $statusBalanca=null, int $id=-1) {
        $this->id = $id;
        $this->setor = $setor;
        $this->numBalanca = $numBalanca;
        $this->pip = $pip;
        $this->numSerie = $numSerie;
        $this->localAtual = $localAtual;
        $this->statusBalanca = $statusBalanca;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setNumBalanca(int $numBalanca) {
        $this->numBalanca = $numBalanca;
    }

    public function getNumBalanca() {
        return $this->numBalanca;
    }

    public function setPip(int $pip) {
        $this->pip = $pip;
    }

    public function getPip() {
        return $this->pip;
    }

    public function setSetor(string $setor) {
        $this->setor = $setor;
    }

    public function getSetor() {
        return $this->setor;
    }

    public function setNumSerie(int $numSerie) {
        $this->numSerie = $numSerie;
    }

    public function getNumSerie() {
        return $this->numSerie;
    }

    public function setLocalAtual(string $localAtual) {
        $this->localAtual = $localAtual;
    }

    public function getLocalAtual() {
        return $this->localAtual;
    }

    public function setStatusBalanca(StatusBalanca $statusBalanca) {
        $this->statusBalanca = $statusBalanca;
    }

    public function getStatusBalanca() {
        return $this->statusBalanca;
    }



}



?>