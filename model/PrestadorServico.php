<?php


class PrestadorServico {

    private $id;
    private $cnpj;
    private $razaoSocial;
    private $telefone;
    private $email;

    public function __construct(string $cnpj="", string $razaoSocial="", string $telefone="", string $email="",int $id=-1) {
        $this->id = $id;
        $this->razaoSocial = $razaoSocial;
        $this->cnpj = $cnpj;
        $this->telefone = $telefone;
        $this->email = $email;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setCnpj(string $cnpj) {
        $this->cnpj = $cnpj;
    }

    public function getCnpj() {
        return $this->cnpj;
    }

    public function setRazaoSocial(string $razaoSocial) {
        $this->razaoSocial = $razaoSocial;
    }

    public function getRazaoSocial() {
        return $this->razaoSocial;
    }

    public function setTelefone(string $telefone) {
        $this->telefone = $telefone;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }
}



?>