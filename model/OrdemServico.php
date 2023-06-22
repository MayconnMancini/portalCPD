<?php 

class OrdemServico {

    private $id;
    private $numOrdemServico;
    private $valor;

    public function __construct(int $numOrdemServico=-1, float $valor=0.0 ,int $id=-1) {
        $this->id = $id;
        $this->numOdermServico = $numOrdemServico;
        $this->valor = $valor;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setNumOrdemServico(int $numOS) {
        $this->numOrdemServico = $numOS;
    }

    public function getNumOrdemServico() {
        return $this->numOrdemServico;
    }

    public function setValor(float $valor) {
        $this->valor = $valor;
    }

    public function getValor() {
        return $this->valor;
    }


}


?>