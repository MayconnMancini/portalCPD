
<?php

require_once('PrestadorServico.php');
require_once('Usuario.php');
require_once('Reparo.php');
require_once('Status.php');

class Atendimento {

    private $id;
    private $dataInicioAtendimento;
    private $dataFimAtendimento;
    private $status;
    private $usuario;
    private $prestadorServico;
    private $reparos = [];

    public function __construct( $dataInicioAtendimento=null, Status $status=null, Usuario $usuario=null, 
                                PrestadorServico $prestadorServico=null,
                                 $dataFimAtendimento=null,int $id=-1) {
        $this->id = $id;
        $this->dataInicioAtendimento = $dataInicioAtendimento;
        $this->dataFimAtendimento = $dataFimAtendimento;
        $this->status = $status;
        $this->usuario = $usuario;
        $this->prestadorServico = $prestadorServico;
        $this->reparos = [];
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setDataInicioAtendimento( $dataInicioAtendimento) {
        $this->dataInicioAtendimento = $dataInicioAtendimento;
    }

    public function getDataInicioAtendimento() {
        return $this->dataInicioAtendimento;
    }

    public function setDataFimAtendimento( $dataFimAtendimento) {
        $this->dataFimAtendimento = $dataFimAtendimento;
    }

    public function getDataFimAtendimento() {
        return $this->dataFimAtendimento;
    }

    public function setStatus(Status $status) {
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setUsuario(Usuario $usuario) {
        $this->usuario = $usuario;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setPrestadorServico(PrestadorServico $prestador) {
        $this->prestadorServico = $prestador;
    }

    public function getPrestadorServico() {
        return $this->prestadorServico;
    }

    public function addReparos(array $reparos) {
        $this->reparos = $reparos;
    }

    public function getReparos() {
        $this->reparos;
    }



}



?>