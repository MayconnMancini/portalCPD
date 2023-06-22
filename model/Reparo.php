
<?php

require_once('Atendimento.php');
require_once('Balanca.php');
require_once('OrdemServico.php');
require_once('Status.php');

class Reparo {

    private $id;
    //private $numReparo;
    private $balanca;
    private $atendimento;
    private $descricaoDefeito;
    private $dataInicioReparo;
    private $dataFimReparo;
    private $status;
    private $observacao;
    private $ordemServico;

    public function __construct(Balanca $balanca=null,
                                Atendimento $atendimento=null, string $descricaoDefeito="", 
                                $dataInicioReparo=null, $dataFimReparo=null, 
                                Status $status=null, string $observacao="", OrdemServico $ordemServico=null ,int $id=-1) {
        $this->id = $id;
        $this->balanca = $balanca;
        //$this->numReparo = $numReparo;
        $this->atendimento = $atendimento;
        $this->descricaoDefeito = $descricaoDefeito;
        $this->dataInicioReparo = $dataInicioReparo;
        $this->dataFimReparo = $dataFimReparo;
        $this->status = $status;
        $this->observacao = $observacao;
        $this->ordemServico = $ordemServico;
        
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }
    /*
    public function setnumReparo(int $numReparo) {
        $this->numReparo = $numReparo;
    }

    public function getnumReparo() {
        return $this->numReparo;
    }
    */
    public function setBalanca(Balanca $balanca) {
        $this->balanca = $balanca;
    }

    public function getBalanca() {
        return $this->balanca;
    }

    public function setAtendimento(Atendimento $atendimento) {
        $this->atendimento = $atendimento;
    }

    public function getAtendimento() {
        return $this->atendimento;
    }

    public function setDescricaoDefeito(string $descricaoDefeito) {
        $this->descricaoDefeito = $descricaoDefeito;
    }

    public function getDescricaoDefeito() {
        return $this->descricaoDefeito;
    }

    public function setDataInicioReparo($dataInicioReparo) {
        $this->dataInicioReparo = $dataInicioReparo;
    }

    public function getDataInicioReparo() {
        return $this->dataInicioReparo;
    }

    public function setDataFimReparo($dataFimReparo) {
        $this->dataFimReparo = $dataFimReparo;
    }

    public function getDataFimReparo() {
        return $this->dataFimReparo;
    }

    public function setStatus(Status $status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setObservacao(string $obs) {
        $this->observacao = $obs;
    }

    public function getObservacao() {
        return $this->observacao;
    }

    public function setOrdemServico(OrdemServico $os) {
        $this->ordemServico = $os;
    }

    public function getOrdemServico() {
        return $this->ordemServico;
    }


}



?>